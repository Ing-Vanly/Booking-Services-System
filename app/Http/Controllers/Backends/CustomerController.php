<?php

namespace App\Http\Controllers\Backends;
use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        if(!auth()->user()->can('customer.view')) {
            abort(403,'Unauthorized action.');
        }
        $customers = Customer::latest('id')->paginate(10);
        return view('backends.customer.index', compact('customers'));
    }
    public function create()
    {
        if(!auth()->user()->can('customer.create')) {
            abort(403,'Unauthorized action.');
        }
        $filePath = public_path('backend/plugins/country/countries.json');
        $countryNames = [];

        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            $countries = json_decode($json, true);

            foreach ($countries as $country) {
                $countryNames[strtolower($country['cca2'])] = $country['name']['common'];
            }
        }
        $customers = Customer::all();
        return view('backends.customer.create', compact('customers', 'countryNames'));
    }
    public function store(Request $request)
    {
        if(!auth()->user()->can('customer.create')) {
            abort(403,'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();

            // Create customer instance
            $customer = new Customer;
            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;
            $customer->phone = $request->phone;
            $customer->email = $request->email;
            $customer->password = $request->password;
            $customer->country = $request->country;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '-' . $image->getClientOriginalName();
                $directory = public_path('uploads/customers');

                // Make sure the directory exists
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0777, true);
                }

                // Move the image
                $image->move($directory, $imageName);

                // Save image name in the database
                $customer->image = $imageName;
            }

            $customer->save();

            DB::commit();
            $output = ['success' => 1, 'msg' => __('Created Successfully!')];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('customer creation error: ' . $e->getMessage());
            $output = ['success' => 0, 'msg' => __('Something went wrong!')];
        }

        if ($request->input('redirect_to') === 'transaction_create') {
            return redirect()->route('admin.transaction.create')->with($output);
        }

        return redirect()->route('admin.customer.index')->with($output);
    }

    public function edit($id)
    {
        if(!auth()->user()->can('customer.edit')) {
            abort(403,'Unauthorized action.');
        }
        $filePath = public_path('backend/plugins/country/countries.json');
        $countryNames = [];

        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            $countries = json_decode($json, true);

            foreach ($countries as $country) {
                $countryNames[strtolower($country['cca2'])] = $country['name']['common'];
            }
        }
        $customer = Customer::findOrFail($id);
        return view('backends.customer.edit', compact('customer', 'countryNames'));
    }
    public function update(Request $request, $id)
    {
        if(!auth()->user()->can('customer.edit')) {
            abort(403,'Unauthorized action.');
        }
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|string|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country' => 'nullable|string|max:255',  // Optional
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }

        try {
            DB::beginTransaction();

            $customer = Customer::findOrFail($id);
            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;
            $customer->phone = $request->phone;
            $customer->email = $request->email;
            $customer->country = $request->country;
            if ($request->password) {
                $customer->password = Hash::make($request['password']);
            }

            // Handle image upload if updated
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '-' . $image->getClientOriginalName();
                $directory = public_path('uploads/customers');

                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0777, true);
                }

                $image->move($directory, $imageName);
                $customer->image = $imageName;
            }

            $customer->save();

            DB::commit();
            $output = ['success' => 1, 'msg' => __('Updated Successfully!')];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('customer update error: ' . $e->getMessage());
            $output = ['success' => 0, 'msg' => __('Something went wrong!')];
        }

        if ($request->input('redirect_to') === 'transaction_create') {
            // If coming from the transaction create page, redirect back to it.
            return redirect()->route('admin.transaction.create')->with($output);
        } else{
            // redirect to the customer index.
            return redirect()->route('admin.customer.index')->with($output);
        }
    }

    public function destroy($id)
    {
        if(!auth()->user()->can('customer.delete')) {
            abort(403,'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $customer = Customer::findOrFail($id);
            $customer->delete();

            $customers = customer::latest('id')->paginate(10);
            $view = view('backends.customer._table', compact('customers'))->render();

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
    public function updateStatus(Request $request)
    {
        if(!auth()->user()->can('customer.edit')) {
            abort(403,'Unauthorized action.');
        }
        try {
            DB::beginTransaction();

            $customer = Customer::findOrFail($request->id);
            $customer->status = $customer->status == 1 ? 0 : 1;
            $customer->save();

            $output = ['status' => 1, 'msg' => __('Status Updated!')];

            DB::commit();
        } catch (Exception $e) {

            $output = ['status' => 0, 'msg' => __('Something went wrong!')];
            DB::rollBack();
        }

        return response()->json($output);
    }
}
