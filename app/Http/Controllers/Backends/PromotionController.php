<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Promotion;
use App\Models\Promotion_product;
use App\Models\Promotion_category;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\helpers\ImageManager;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest('id')->paginate(10);

        return view('backends.promotion.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function create()
    {
        $product = Product::pluck('name', 'id');
        $promotion = Promotion::pluck('title', 'id');
        $category = category::pluck('name', 'id');
        return view('backends.promotion.create', compact('product', 'category', 'promotion'));
    }


    public function store(Request $request)
    {




        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'promotion_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }



        try {




            // dd($request->promotion_type);
            DB::beginTransaction();

            // Create promotion instance
            $promotion = new Promotion;
            $promotion->title = $request->title;
            $promotion->description = $request->description;
            $promotion->discount_type = $request->discount_type;
            $promotion->discount_value = $request->discount_value;
            $promotion->promotion_type = $request->promotion_type;
            $promotion->start_date = $request->start_date;
            $promotion->end_date = $request->end_date;
            // $promotion->created_by = auth()->user()->id;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '-' . $image->getClientOriginalName();
                $directory = public_path('uploads/promotions');

                // Make sure the directory exists
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0777, true);
                }

                // Move the image
                $image->move($directory, $imageName);

                // Save image name in the database
                $promotion->image = $imageName;
            }

            $promotion->save();

            DB::commit();
            $output = ['success' => 1, 'msg' => __('Created successfully')];
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            \Log::error('Promotion creation error: ' . $e->getMessage());
            $output = ['success' => 0, 'msg' => __('Something went wrong')];
        }


        try {

            $validated = $request->validate([
                'product_id' => 'required',
            ]);

            $promotion = Promotion::latest()->first(); // Or use a query to get a specific promotion

            // Check if a promotion exists
            if (!$promotion) {
                return redirect()->back()->with('error', 'No promotions available.');
            }

            // Create the promotion_product entry with the fetched promotion ID
            $promotion_Product = Promotion_product::create([
                'promotion_id' => $promotion->id, // Use the promotion ID from the database
                'product_id' => $validated['product_id'],
            ]);
            // {product category}
            return redirect()->route('admin.promotion.index')->with($output);
        } catch (\Exception $e) {

            $validated = $request->validate([
                'category_id' => 'required', // Remove 'promotion_id' from validation
            ]);

            // Fetch the promotion ID from the database (example: latest promotion)
            $promotion = Promotion::latest()->first(); // Or use a query to get a specific promotion

            // Check if a promotion exists
            if (!$promotion) {
                return redirect()->back()->with('error', 'No promotions available.');
            }

            // Create the promotion_product entry with the fetched promotion ID
            $promotion_category = Promotion_category::create([
                'promotion_id' => $promotion->id, // Use the promotion ID from the database
                'category_id' => $validated['category_id'],
            ]);
            // {product category}

        }
        return redirect()->route('admin.promotion.index')->with($output);
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



        $promotion = Promotion::where('id', $id)->first();
        // $product = Product::where('id', 1)->first();
        $products = Product::pluck('name', 'id');
        $promotion_product = Promotion_product::where('promotion_id', $id)->first();
        $promotion_category = Promotion_category::where('promotion_id', $id)->first();
        $categorys = Category::pluck('name', 'id');
        // $category = Category::where('id', $id)->first();
        // $record->delete();
        return view('backends.promotion.edit', compact(
            'promotion',
            'promotion_product',
            'products',
            'categorys',
            'promotion_category'
        ));
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
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'promotion_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();

            $promotion = Promotion::findOrFail($id);
            $promotion->title = $request->title;
            $promotion->description = $request->description;
            $promotion->discount_type = $request->discount_type;
            $promotion->discount_value = $request->discount_value;
            $promotion->promotion_type = $request->promotion_type;
            $promotion->start_date = $request->start_date;
            $promotion->end_date = $request->end_date;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '-' . $image->getClientOriginalName();
                $directory = public_path('uploads/promotions');

                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0777, true);
                }

                // Move the image and update the product
                $image->move($directory, $imageName);
                $promotion->image = $imageName;

                // Debugging statement
                \Log::info('New image uploaded: ' . $imageName);
            } else {
                // Debugging statement
                \Log::info('No new image uploaded');
            }

            $promotion->save();

            // Update promotion_product
            $promotion_product = Promotion_product::where('promotion_id', $id)->first();
            if ($promotion_product) {
                $promotion_product->product_id = $request->product_id;
                $promotion_product->save();
            } else {
                // Create a new promotion_product if it doesn't exist
                Promotion_product::create([
                    'promotion_id' => $id,
                    'product_id' => $request->product_id,
                ]);
            }

            // Update promotion_category
            $promotion_category = Promotion_category::where('promotion_id', $id)->first();
            if ($promotion_category) {
                $promotion_category->category_id = $request->category_id;
                $promotion_category->save();
            } else {
                // Create a new promotion_category if it doesn't exist
                Promotion_category::create([
                    'promotion_id' => $id,
                    'category_id' => $request->category_id,
                ]);
            }

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => __('Updated successfully')
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong')
            ];

            // Debugging statement
            \Log::error('Error updating promotion: ' . $e->getMessage());
        }

        return redirect()->route('admin.promotion.index')->with($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {





        try {

            DB::beginTransaction();




            try {
                DB::beginTransaction();

                $promotion_category = Promotion_category::where('promotion_id', $id)->first();

                if ($promotion_category) {
                    $promotion_category->delete();
                } else {
                    $promotion_product = Promotion_product::where('promotion_id', $id)->first();
                    if ($promotion_product) {
                        $promotion_product->delete();
                    }
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                // Handle the error (e.g., log it or return an error response)
            }



            $promotions = Promotion::findOrFail($id);




            $promotions->delete();

            $promotions = Promotion::latest('id')->paginate(10);
            $view = view('backends.promotion._table', compact('promotions'))->render();

            DB::commit();
            $output = [
                'status' => 1,
                'view'  => $view,
                'msg' => __('User Deleted successfully')
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $output = [
                'status' => 0,
                'msg' => __('Something when wrong')
            ];
        }

        return response()->json($output);
    }
    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->id);
            $product->status = $product->status == 1 ? 0 : 1;
            $product->save();

            $output = ['status' => 1, 'msg' => __('Status updated')];

            DB::commit();
        } catch (Exception $e) {

            $output = ['status' => 0, 'msg' => __('Something went wrong')];
            DB::rollBack();
        }

        return response()->json($output);
    }
}
