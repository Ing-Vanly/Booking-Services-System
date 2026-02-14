<?php

namespace App\Http\Controllers\bookingwebsite;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionSellLine;
use Illuminate\Support\Facades\DB;  // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\ProductDate;
use App\Models\Customer;


class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $setting = new BusinessSetting();
        $data['company_name'] = @$setting->where('type', 'company_name')->first()->value;
        $data['phone'] = @$setting->where('type', 'phone')->first()->value;
        $data['email'] = @$setting->where('type', 'email')->first()->value;
        $data['company_address'] = @$setting->where('type', 'company_address')->first()->value;
        $data['copy_right_text'] = @$setting->where('type', 'copy_right_text')->first()->value;
        $data['timezone'] = @$setting->where('type', 'timezone')->first()->value;
        $data['currency'] = @$setting->where('type', 'currency')->first()->value;

        $selectedCarts = $request->input('selected_carts', []);

        // If selected_carts is passed as a string, decode it, otherwise use as is
        if (is_string($selectedCarts)) {
            $selectedCarts = json_decode($selectedCarts);
        }

        $cartsQuery = Cart::where('customer_id', auth()->guard('customer')->user()->id);

        if (!empty($selectedCarts)) {
            $cartsQuery->whereIn('id', $selectedCarts);
        }

        $carts = $cartsQuery->get();
        $data['cartsSelected'] = $carts;
        $data['selected_carts'] = $selectedCarts;

        $subtotal = $carts->sum('total');
        $data['sub_total'] = $subtotal;

        $discount = $carts->sum('discount');
        $data['discount'] = $discount;

        $grandtotal = $subtotal - $discount;
        $data['grand_total'] = $grandtotal;

        return view('bookingwebsite.checkout.index', $data);
    }

    public function payment(Request $request)
    {
        $data = [];

        $selectedCarts = $request->input('selected_carts', []);
        if (is_string($selectedCarts)) {
            $selectedCarts = json_decode($selectedCarts, true);
        }

        Log::info('Selected Carts for Payment: ', ['selected_carts' => $selectedCarts]);

        $cartsQuery = Cart::where('customer_id', auth()->guard('customer')->user()->id);

        if (!empty($selectedCarts)) {
            $cartsQuery->whereIn('id', $selectedCarts);
        }

        $carts = $cartsQuery->get();

        $subtotal = $carts->sum(function ($cart) {
            return $cart->total ?? 0;
        });

        $discount = $carts->sum(function ($cart) {
            return $cart->discount ?? 0;
        });

        $grandtotal = $subtotal - $discount;

        $data['sub_total'] = $subtotal;
        $data['discount'] = $discount;
        $data['grand_total'] = $grandtotal;

        return view('bookingwebsite.checkout.payment', $data);
    }
    public function update(Request $request, $id)
    {
        try {
            // \Log::info('Customer Update Request', $request->all());

            $customer = Customer::findOrFail($id);
            $customer->update($request->only(['first_name', 'last_name', 'phone', 'email', 'country']));

            return response()->json(['success' => true, 'msg' => __('Updated Successfully!')]); // ✅ Ensure JSON response
        } catch (\Exception $e) {
            // \Log::error('Customer update error: ' . $e->getMessage());

            return response()->json(['success' => false, 'msg' => __('Something went wrong!')], 500);
        }
    }
    public function completeBooking(Request $request)
    {
        $request->validate([
            'paymentMethod' => 'required',
            'policy' => 'accepted',
        ]);

        $selectedCarts = $request->input('selected_carts', []);
        if (is_string($selectedCarts)) {
            $selectedCarts = json_decode($selectedCarts, true);
        }

        Log::info('Selected Carts for Booking: ', ['selected_carts' => $selectedCarts]);

        if (empty($selectedCarts)) {
            return redirect()->route('cart.index')->with('error', 'No items selected.');
        }

        $cartsQuery = Cart::where('customer_id', auth()->guard('customer')->user()->id);
        if (!empty($selectedCarts)) {
            $cartsQuery->whereIn('id', $selectedCarts);
        }
        $carts = $cartsQuery->get();
        $subtotal = $carts->sum('total');
        $discount = $carts->sum('discount');
        $grandtotal = $subtotal - $discount;

        $customer = auth()->guard('customer')->user();
        $guestInfo = [
            'first_name'  => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
        ];
        $guestInfoJson = json_encode($guestInfo);

        $invoiceNo = 'INV-' . strtoupper(uniqid());

        // DB::beginTransaction();
        // try {
        //     $transaction = new Transaction();
        //     $transaction->invoice_no = $invoiceNo;
        //     $transaction->guest_info = $guestInfoJson;
        //     $transaction->user_id = 1;
        //     $transaction->customer_id = $customer->id;
        //     $transaction->payment_method = $request->input('paymentMethod');
        //     $transaction->sub_total = $subtotal;
        //     $transaction->final_total = $grandtotal;
        //     $transaction->booking_date = $firstCart->start_date ?? null;
        //     $transaction->start_date = $firstCart->start_date ?? null;
        //     $transaction->end_date = $firstCart->end_date ?? null;
        //     $transaction->payment_status = 'unpaid';
        //     $transaction->status = 'Request';
        //     $transaction->created_by = 1;
        //     $transaction->additional_info = $request->input('additional');
        //     $transaction->save();

        //     foreach ($carts as $cart) {
        //         $transactionSellLine = new TransactionSellLine();
        //         $transactionSellLine->transaction_id = $transaction->id;
        //         $transactionSellLine->product_id = $cart->product_id;
        //         $transactionSellLine->qty = $cart->qty;
        //         $transactionSellLine->sub_total = $cart->price * $cart->qty;
        //         $transactionSellLine->final_total = $grandtotal;
        //         $transactionSellLine->discount_amount = $cart->discount;
        //         $transactionSellLine->save();
        //     }

        //     Cart::whereIn('id', $selectedCarts)->delete();
        //     DB::commit();

        //     return redirect()->route('cart.index')->with('success', 'Transaction completed successfully.');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     Log::error('Error completing booking: ' . $e->getMessage());
        //     return redirect()->route('cart.index')->with('error', 'Transaction failed, please try again.');
        // }

        DB::beginTransaction();
        try {
            $transaction = new Transaction();
            $transaction->invoice_no = $invoiceNo;
            $transaction->guest_info = $guestInfoJson;
            $transaction->user_id = 1;
            $transaction->customer_id = $customer->id;
            $transaction->payment_method = $request->input('paymentMethod');
            $transaction->sub_total = $subtotal;
            $transaction->final_total = $grandtotal;
            $transaction->booking_date = now()->toDateString();
            $transaction->payment_status = 'unpaid';
            $transaction->status = 'Request';
            $transaction->created_by = 1;
            $transaction->additional_info = $request->input('additional');
            $transaction->save();

            foreach ($carts as $cart) {
                $totalDays = null;
                if ($cart->start_date && $cart->end_date) {
                    $totalDays = \Carbon\Carbon::parse($cart->start_date)->diffInDays(\Carbon\Carbon::parse($cart->end_date)) + 1;
                }

                $transactionSellLine = new TransactionSellLine();
                $transactionSellLine->transaction_id = $transaction->id;
                $transactionSellLine->product_id = $cart->product_id;
                $transactionSellLine->qty = $cart->qty;
                $transactionSellLine->sub_total = $cart->price * $cart->qty;
                $transactionSellLine->final_total = $cart->price * $cart->qty - $cart->discount;
                $transactionSellLine->discount_amount = $cart->discount;
                $transactionSellLine->start_date = $cart->start_date;
                $transactionSellLine->end_date = $cart->end_date;
                $transactionSellLine->total_date = $totalDays;
                $transactionSellLine->save();

                // Update the number field in product_dates table based on date range
                $startDate = new \DateTime($cart->start_date);
                $endDate = new \DateTime($cart->end_date);
                $interval = new \DateInterval('P1D');
                $dateRange = new \DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

                foreach ($dateRange as $date) {
                    DB::table('product_dates')
                        ->where('product_id', $cart->product_id)
                        ->whereDate('start_date', '<=', $date->format('Y-m-d'))
                        ->whereDate('end_date', '>=', $date->format('Y-m-d'))
                        ->decrement('number', $cart->qty);
                }
            }

            Cart::whereIn('id', $selectedCarts)->delete();
            DB::commit();

            return redirect()->route('cart.index')->with('success', 'Transaction completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error completing booking: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Transaction failed, please try again.');
        }
    }
}
