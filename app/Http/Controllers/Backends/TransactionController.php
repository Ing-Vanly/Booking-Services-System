<?php

namespace App\Http\Controllers\Backends;

use Exception;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionSellLine;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        if(!auth()->user()->can('transaction.view')) {
            abort(403,'Unauthorized action.');
        }
        $transactions = Transaction::latest('id')->paginate(10);
        $sellLines = TransactionSellLine::latest('id')->paginate(10);
        return view('backends.transaction.index', compact('transactions', 'sellLines'));
    }

    public function create()
    {

        if(!auth()->user()->can('transaction.create')) {
            abort(403,'Unauthorized action.');
        }
        $user = User::pluck('name', 'id');
        $customers = Customer::where('status', 1)->get();
        $products = Product::select('id', 'name', 'price', 'status')->get();

        $filePath = public_path('backend/plugins/country/countries.json');
        $countryNames = [];

        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            $countries = json_decode($json, true);

            foreach ($countries as $country) {
                $countryNames[strtolower($country['cca2'])] = $country['name']['common'];
            }
        }

        return view('backends.transaction.create', compact('user', 'products', 'customers', 'countryNames'));
    }


    public function store(Request $request)
    {
        if(!auth()->user()->can('transaction.create')) {
            abort(403,'Unauthorized action.');
        }
        $rules = [
            'customer_id' => 'required|integer',
            'sub_total' => 'required|numeric',
            'final_total' => 'required|numeric',
            'payment_status' => 'required|in:paid,unpaid',
            'status' => 'required|in:request,confirmed,cancel',
            // 'booking_date' => 'required|date',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payment_method' => 'required|string',
            'guest_info' => 'required|array',
            'sell_lines' => 'required|array|min:1',
            'sell_lines.*.product_id' => 'required|integer',
            'sell_lines.*.qty' => 'required|numeric|min:1',
            'sell_lines.*.discount_type' => 'required|in:percent,fix',
            'sell_lines.*.discount_amount' => 'required|numeric|min:0',
            'sell_lines.*.sub_total' => 'required|numeric|min:0',
            'sell_lines.*.final_total' => 'required|numeric|min:0',
        ];

        $validatedData = $request->validate($rules);

        try {
            DB::beginTransaction();

            $bookingDate = now(); 

            $guestInfoJson = json_encode($validatedData['guest_info']);
            $invoiceNo = 'INV-' . strtoupper(uniqid());
            $totalDate = \Carbon\Carbon::parse($validatedData['start_date'])->diffInDays(\Carbon\Carbon::parse($validatedData['end_date'])) + 1;

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'customer_id' => $validatedData['customer_id'],
                'guest_info' => $guestInfoJson,
                'sub_total' => $validatedData['sub_total'],
                'final_total' => $validatedData['final_total'],
                'invoice_no' => $invoiceNo,
                'booking_date' => $bookingDate,
                'payment_method' => $validatedData['payment_method'],
                'payment_status' => $validatedData['payment_status'],
                'status' => $validatedData['status'],
                'created_by' => auth()->id(),
            ]);

            foreach ($validatedData['sell_lines'] as $line) {
                TransactionSellLine::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $line['product_id'],
                    'qty' => $line['qty'],
                    'discount_type' => $line['discount_type'],
                    'discount_amount' => $line['discount_amount'],
                    'sub_total' => $line['sub_total'],
                    'final_total' => $line['final_total'],
                    'start_date' => $validatedData['start_date'],
                    'end_date' => $validatedData['end_date'],
                    'total_date' => $totalDate,
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 1,
                'msg' => __('Created Successfully!'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 0,
                'msg' => __('Something went wrong!') . $e->getMessage(),
            ], 500);
        }
    }


    public function edit($id)
    {
        if(!auth()->user()->can('transaction.edit')) {
            abort(403,'Unauthorized action.');
        }
        $transaction = Transaction::with('transactionSellLines.product', 'customer')->findOrFail($id);
        $transaction_selllines = TransactionSellLine::where('transaction_id', $id)->first();
        $user = User::pluck('name', 'id');
        $customers = Customer::where('status', 1)->get();
        $products = Product::select('id', 'name', 'price', 'status')->get();

        $filePath = public_path('backend/plugins/country/countries.json');
        $countryNames = [];

        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            $countries = json_decode($json, true);

            foreach ($countries as $country) {
                $countryNames[strtolower($country['cca2'])] = $country['name']['common'];
            }
        }

        return view('backends.transaction.edit', compact('transaction', 'user', 'products', 'customers', 'countryNames', 'transaction_selllines'));
    }

    public function update(Request $request, $id)
    {
        if(!auth()->user()->can('transaction.edit')) {
            abort(403,'Unauthorized action.');
        }
        // Validation rules
        $rules = [
            'customer_id'      => 'required|integer',
            'sub_total'        => 'required|numeric',
            'final_total'      => 'required|numeric',
            'payment_status'   => 'required|in:paid,unpaid',
            'status'           => 'required|in:request,confirmed,cancel',
            // 'booking_date'     => 'required|date',
            'start_date'       => 'required|date|before_or_equal:end_date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'payment_method'   => 'required|string',
            'sell_lines'       => 'required|array|min:1',
            'sell_lines.*.product_id'      => 'required|integer',
            'sell_lines.*.qty'             => 'required|numeric|min:1',
            'sell_lines.*.discount_type'   => 'required|in:percent,fix',
            'sell_lines.*.discount_amount' => 'required|numeric|min:0',
            'sell_lines.*.sub_total'       => 'required|numeric|min:0',
            'sell_lines.*.final_total'     => 'required|numeric|min:0',
        ];

        // Validate the request
        $validatedData = $request->validate($rules);

        try {
            // Begin a database transaction
            DB::beginTransaction();

            // $bookingDate = now(); 

            // Find the transaction to update
            $transaction = Transaction::findOrFail($id);

            $customer = Customer::findOrFail($validatedData['customer_id']);
            $guestInfo = [
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
            ];
            $totalDate = \Carbon\Carbon::parse($validatedData['start_date'])->diffInDays(\Carbon\Carbon::parse($validatedData['end_date'])) + 1;

            // Update the transaction
            $transaction->update([
                'customer_id' => $validatedData['customer_id'],
                'guest_info'  => json_encode([$guestInfo]),
                'sub_total' => $validatedData['sub_total'],
                'final_total' => $validatedData['final_total'],
                // 'booking_date' => $bookingDate,
                'payment_method' => $validatedData['payment_method'],
                'payment_status' => $validatedData['payment_status'],
                'status' => $validatedData['status'],
                'updated_by' => auth()->id(),
            ]);

            // Delete existing sell lines
            $transaction->transactionSellLines()->delete();

            // Add new sell lines
            foreach ($validatedData['sell_lines'] as $line) {
                TransactionSellLine::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $line['product_id'],
                    'qty' => $line['qty'],
                    'discount_type' => $line['discount_type'],
                    'discount_amount' => $line['discount_amount'],
                    'sub_total' => $line['sub_total'],
                    'final_total' => $line['final_total'],
                    'start_date' => $validatedData['start_date'],
                    'end_date' => $validatedData['end_date'],
                    'created_by' => auth()->id(),
                    'total_date' => $totalDate,
                ]);
            }

            // Commit the transaction
            DB::commit();

            return response()->json([
                'status' => 1,
                'msg' => __('Updated Successfully!'),
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 0,
                'msg' => __('Something went wrong!') . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        if(!auth()->user()->can('transaction.delete')) {
            abort(403,'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $transaction = Transaction::findOrFail($id);
            $transaction->delete();

            $transactions = Transaction::latest('id')->paginate(10);
            $view = view('backends.transaction._table', compact('transactions'))->render();

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
}
