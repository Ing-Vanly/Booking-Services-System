<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Product;
use App\Models\Category;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use App\Models\BrandCategory;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->can('product.view')){
            abort(403,'Unauthorized action.');
        }
        $products = Product::latest('id')->paginate(10);

        return view('backends.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('product.create')){
            abort(403,'Unauthorized action.');
        }
        $categories = Category::pluck('name', 'id');
        $brandCategories = BrandCategory::pluck('name', 'id');
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.product.create', compact('categories','brandCategories', 'language', 'default_lang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {if(!auth()->user()->can('product.create')){
        abort(403,'Unauthorized action.');
    }
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'category' => 'required',
        'price' => 'required|numeric',
        'event_id' => 'required',
        'brandCategory' => 'required',
        'partner_id' => 'required',
    ]);
    
    if (is_null($request->name[array_search('en', $request->lang)])) {
        $validator->after(function ($validator) {
            $validator->errors()->add('name', 'Name field is required!');
        });
    }
    
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput()->with(['success' => 0, 'msg' => __('Invalid form input')]);
    }
    
    try {
        DB::beginTransaction();
    
        // Create product instance
        $product = new Product;
        $product->name = $request->name[array_search('en', $request->lang)];
        $product->category_id = $request->category;
        $product->price = $request->price;
        $product->discount_type = $request->discount_type;
        $product->discount = $request->discount;
        $product->event_id = $request->event_id;
        $product->brand_category_id = $request->brandCategory;
        $product->number_available = $request->number_available;
        $product->description = $request->description;
        $product->partner_id = $request->partner_id;
        $product->status = $request->status;
        $product->created_by = auth()->user()->id;
    
        // Calculate and store discount_price
        if ($product->discount_type == 'percentage') {
            $product->discount_price = $product->price - ($product->price * $product->discount / 100);
        } else {
            $product->discount_price = $product->price - $product->discount;
        }
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '-' . $image->getClientOriginalName();
            $directory = public_path('uploads/products');
    
            // Make sure the directory exists
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0777, true);
            }
    
            // Move the image
            $image->move($directory, $imageName);
    
            // Save image name in the database
            $product->image = $imageName;
        }
    
        $product->save();
    
        // Store translations
        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                array_push($data, [
                    'translationable_type' => 'App\Models\Product',
                    'translationable_id' => $product->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                ]);
            }
        }
    
        Translation::insert($data);
    
        DB::commit();
        $output = ['success' => 1, 'msg' => __('Created Successfully!')];
    
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Product creation error: ' . $e->getMessage());
        $output = ['success' => 0, 'msg' => __('Something went wrong!')];
    }
    
    return redirect()->route('admin.product.index')->with($output);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        if(!auth()->user()->can('product.edit')){
            abort(403,'Unauthorized action.');
        }

        $product = Product::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $categories = Category::pluck('name', 'id');
        $brandCategories = BrandCategory::pluck('name', 'id');
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.product.edit', compact('product', 'categories','brandCategories', 'language', 'default_lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!auth()->user()->can('product.edit')){
            abort(403,'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'event_id' => 'required',
            'brandCategory' => 'required',
        ]);

        if (is_null($request->name[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add('name', 'Name field is required!');
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            $product->name = $request->name[array_search('en', $request->lang)];
            $product->category_id = $request->category;
            $product->price = $request->price;
            $product->discount_type = $request->discount_type;
            $product->discount = $request->discount;
            $product->event_id = $request->event_id;
            $product->brand_category_id = $request->brandCategory;
            $product->number_available = $request->number_available;
            $product->description = $request->description;
            $product->partner_id = $request->partner_id;
            $product->status = $request->status;

            // Handle image upload if updated
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '-' . $image->getClientOriginalName();
                $directory = public_path('uploads/products');

                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0777, true);
                }

                // Move the image and update the product
                $image->move($directory, $imageName);
                $product->image = $imageName;
            }

            $product->save();

            // Update translations
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Product',
                            'translationable_id' => $product->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $request->name[$index]]
                    );
                }
            }

            DB::commit();
            $output = ['success' => 1, 'msg' => __('Updated Successfully!')];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product update error: ' . $e->getMessage());
            $output = ['success' => 0, 'msg' => __('Something went wrong!')];
        }

        return redirect()->route('admin.product.index')->with($output);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('product.delete')){
            abort(403,'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            $translation = Translation::where('translationable_type', 'App\Models\Product')
                ->where('translationable_id', $product->id);
            $translation->delete();
            $product->delete();

            $products = Product::latest('id')->paginate(10);
            $view = view('backends.product._table', compact('products'))->render();

            DB::commit();
            $output = [
                'status' => 1,
                'view' => $view,
                'msg' => __('Deleted Successfully!')
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'status' => 0,
                'msg' => __('Something went wrong!')
            ];
        }

        return response()->json($output);
    }

    public function updateStatus (Request $request)
    {
        if(!auth()->user()->can('product.edit')){
            abort(403,'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->id);
            $product->status = $product->status == 1 ? 0 : 1;
            $product->save();

            $output = ['status' => 1, 'msg' => __('Status Updated!')];

            DB::commit();
        } catch (Exception $e) {

            $output = ['status' => 0, 'msg' => __('Something went wrong!')];
            DB::rollBack();
        }

        return response()->json($output);
    }
}
