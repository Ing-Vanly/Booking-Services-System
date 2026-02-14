<?php

namespace App\Http\Controllers\Backends;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\BrandCategory;
use App\Models\BusinessSetting;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandCategoryController extends Controller
{
    public function index()
    {
        if(!auth()->user()->can('brand.view')) {
            abort(403,'Unauthorized action.');
        }
        $brandCategories = BrandCategory::latest('id')->paginate(10);
        return view('backends.brand-category.index', compact('brandCategories'));
    }

    public function create()
    {
        if(!auth()->user()->can('brand.create')) {
            abort(403,'Unauthorized action.');
        }
        $categories = Category::where('status', 1)->pluck('name', 'id');
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.brand-category.create', compact('categories', 'language', 'default_lang'));
    }

    public function store(Request $request)
    {
        if(!auth()->user()->can('brand.create')) {
            abort(403,'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'name'      => 'required|array',
            'category'  => 'required',
        ]);

        if (array_search('en', $request->lang) === false ||
            empty($request->name[array_search('en', $request->lang)]))
        {
            $validator->after(function ($validator) {
                $validator->errors()->add('name', 'The English name field is required!');
            });
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();


            $enIndex = array_search('en', $request->lang);
            if ($enIndex === false) {
                throw new \Exception("English language not provided.");
            }

            $brandCategory = new BrandCategory;
            $brandCategory->name = $request->name[$enIndex];
            $brandCategory->category_id = $request->category;
            $brandCategory->created_by = auth()->user()->id;
            $brandCategory->save();

            $data = [];
            foreach ($request->lang as $index => $lang) {
                if ($lang !== 'en' && !empty($request->name[$index])) {
                    $data[] = [
                        'translationable_type' => 'App\Models\BrandCategory',
                        'translationable_id'   => $brandCategory->id,
                        'locale'               => $lang,
                        'key'                  => 'name',
                        'value'                => $request->name[$index],
                        'created_at'           => now(),
                        'updated_at'           => now(),
                    ];
                }
            }

            if (!empty($data)) {
                Translation::insert($data);
            }

            DB::commit();
            return redirect()->route('admin.brand-category.index')
                ->with(['success' => 1, 'msg' => __('Created Successfully!')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with(['success' => 0, 'msg' => __('Something went wrong!') . ' ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        if(!auth()->user()->can('brand.edit')) {
            abort(403,'Unauthorized action.');
        }

        $brandCategory = BrandCategory::withoutGlobalScopes()->with('translations')->findOrFail($id);
        $categories = Category::where('status', 1)->pluck('name', 'id');
        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.brand-category.edit', compact('brandCategory', 'categories', 'language', 'default_lang'));
    }

    public function update(Request $request, $id)
    {
        if(!auth()->user()->can('brand.edit')) {
            abort(403,'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category' => 'required',
        ]);

        if (is_null($request->name[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add('name', 'Name field is required!');
            });
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();

            $brandCategory = BrandCategory::findOrFail($id);
            $brandCategory->name = $request->name[array_search('en', $request->lang)];
            $brandCategory->category_id = $request->category;
            $brandCategory->save();

            foreach ($request->lang as $index => $lang) {
                if ($request->name[$index] && $lang != 'en') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\BrandCategory',
                            'translationable_id' => $brandCategory->id,
                            'locale' => $lang,
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
            $output = ['success' => 0, 'msg' => __('Something went wrong!')];
        }

        return redirect()->route('admin.brand-category.index')->with($output);
    }

    public function destroy($id)
    {
        if(!auth()->user()->can('brand.delete')) {
            abort(403,'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $brandCategory = BrandCategory::findOrFail($id);
            $translation = Translation::where('translationable_type', 'App\Models\BrandCategory')
                ->where('translationable_id', $brandCategory->id);
            $translation->delete();
            $brandCategory->delete();

            $brandCategories = BrandCategory::latest('id')->paginate(10);
            $view = view('backends.brand-category._table', compact('brandCategories'))->render();

            DB::commit();
            $output = [
                'status' => 1,
                'view' => $view,
                'msg' => __('Deleted Successfully!')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $output = [
                'status' => 0,
                'msg' => __('Something went wrong!')
            ];
        }

        return response()->json($output);
    }

    public function updateStatus(Request $request)
    {
        if(!auth()->user()->can('brand.edit')) {
            abort(403,'Unauthorized action.');
        }
        try {
            DB::beginTransaction();

            $brandCategory = BrandCategory::findOrFail($request->id);
            $brandCategory->status = $brandCategory->status == 1 ? 0 : 1;
            $brandCategory->save();

            DB::commit();
            $output = ['status' => 1, 'msg' => __('Status Updated!')];
        } catch (\Exception $e) {
            DB::rollBack();
            $output = ['status' => 0, 'msg' => __('Something went wrong!')];
        }

        return response()->json($output);
    }
}
