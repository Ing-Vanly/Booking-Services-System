<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\Slider;
use App\helpers\AppHelper;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\BusinessSetting;

class SliderController extends Controller
{
    public function index()
    {
        if(!auth()->user()->can('slider.view')) {
            abort(403,'Unauthorized action.');
        }
        $sliders = Slider::latest('id')->paginate(10);
        return view('backends.slider.index', compact('sliders'));
    }

    public function create()
    {
        if(!auth()->user()->can('slider.create')) {
            abort(403,'Unauthorized action.');
        }

        $language = BusinessSetting::where('type', 'language')->first();
        $language = $language->value ?? null;
        $default_lang = 'en';
        $default_lang = json_decode($language, true)[0]['code'];

        return view('backends.slider.create', compact('language', 'default_lang'));
    }

    public function store(Request $request)
    {
        if(!auth()->user()->can('slider.create')) {
            abort(403,'Unauthorized action.');
        }
    //     return response()->json($output);
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();

            $record = new Slider; // Change 'YourModel' to your actual model name
            $record->title = $request->title;

            if ($request->hasFile('image')) {
                $record->image = ImageManager::upload('uploads/images/', $request->image);
            }

            $record->save();

            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __('Created Successfully!')
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong!')
            ];
        }

        return redirect()->route('admin.slider.index')->with($output); // Change 'records' to your actual route name

    }

    public function edit($id)
    {
        if(!auth()->user()->can('slider.edit')) {
            abort(403,'Unauthorized action.');
        }
        $record = Slider::findOrFail($id);
        // $record->delete();
        return view('backends.slider.edit', compact('record'));
    }

    public function update(Request $request, $id)
    {
        if(!auth()->user()->can('slider.edit')) {
            abort(403,'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();

            $record = Slider::findOrFail($id);
            $record->title = $request->title;

            if ($request->hasFile('image')) {
                $record->image = ImageManager::update('uploads/images/', $record->image, $request->image);
            }

            $record->save();

            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __('Updated Successfully!')
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('Something went wrong!')
            ];
        }

        return redirect()->route('admin.slider.index')->with($output);
    }
    public function destroy($id)
    {

        if(!auth()->user()->can('slider.delete')) {
            abort(403,'Unauthorized action.');
        }
        try {

           DB::beginTransaction();
            $slider = Slider::findOrFail($id);

            $slider->delete();
            $sliders = Slider::latest('id')->paginate(10);
            $view = view('backends.slider._table', compact('sliders'))->render();

            DB::commit();
            $output = [
                'status' => 1,
                'view'  => $view,
                'msg' => __('Deleted Successfully!')
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $output = [
                'status' => 0,
                'msg' => __('Something when wrong!')
            ];
        }

        return response()->json($output);

    }
}
