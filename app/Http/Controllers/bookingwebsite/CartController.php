<?php

namespace App\Http\Controllers\bookingwebsite;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\ProductDate;

class CartController extends Controller
{
    // public function index()
    // {
    //     $customer = auth()->guard('customer')->user();

    //     if (!$customer) {
    //         return redirect('/customer/login')->with('error', 'Please log in to view your cart.');
    //     }

    //     $carts = Cart::where('customer_id', $customer->id)->get();
    //     $subtotal = $carts->sum('total');

    //     return view('bookingwebsite.cart.index', compact('carts', 'subtotal'));
    // }

//     public function index()
// {
//     $customer = auth()->guard('customer')->user();

//     if (!$customer) {
//         return redirect('/customer/login')->with('error', 'Please log in to view your cart.');
//     }

//     $carts = Cart::where('customer_id', $customer->id)->get();
//     $subtotal = $carts->sum('total');

//     // Fetch product dates for stock check
//     $productDates = ProductDate::whereIn('product_id', $carts->pluck('product_id'))->get()->keyBy('product_id');

//     return view('bookingwebsite.cart.index', compact('carts', 'subtotal', 'productDates'));
// }


public function index()
{
    $customer = auth()->guard('customer')->user();

    if (!$customer) {
        return redirect('/customer/login')->with('error', 'Please log in to view your cart.');
    }

    $carts = Cart::where('customer_id', $customer->id)->get();
    $subtotal = $carts->sum('total');

    // Fetch product dates for stock check
    $productDates = ProductDate::whereIn('product_id', $carts->pluck('product_id'))->get()->keyBy('product_id');
    

    return view('bookingwebsite.cart.index', compact('carts', 'subtotal', 'productDates'));

}




    public function create($id)
    {
        $carts = Cart::all();
        $subtotal = $carts->sum('total');
        $product = Product::findOrFail($id);

        // // Fetch related products with category and brand info
        // $relatedProducts = Product::where('brand_category_id', $product->brand_category_id)
        //     ->where('id', '!=', $product->id)
        //     ->with(['category', 'brandCategory']) // Load category and brand relationships
        //     ->limit(4)
        //     ->get();
        $relatedProducts = Product::where('brand_category_id', $product->brand_category_id)
        ->where('id', '!=', $product->id)
        ->with(['category', 'brandCategory']) // Load category and brand relationships
        ->paginate(8); // 2 items per page

        // Fetch promotion products with their promotions using a join
        $promotionProducts = DB::table('promotion_products')
            ->join('promotions', 'promotion_products.promotion_id', '=', 'promotions.id')
            ->select('promotion_products.*', 'promotions.discount_value')
            ->get();

        // Fetch promotion categories with their promotions using a join
        $promotionCategories = DB::table('promotion_categories')
            ->join('promotions', 'promotion_categories.promotion_id', '=', 'promotions.id')
            ->select('promotion_categories.*', 'promotions.discount_value')
            ->get();


        return view('bookingwebsite.cart.create', compact(
            'product',
            'carts',
            'subtotal',
            'relatedProducts',
            'promotionProducts',
            'promotionCategories'
        ));
    }

    // public function checkAvailability(Request $request)
    // {
    //     $validated = $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date|after_or_equal:start_date',
    //         'qty' => 'required|integer|min:1',
    //     ]);

    //     $customer = auth()->guard('customer')->user();


    //     if (!$customer) {
    //         $output = [
    //             'success' => false,
    //             'msg' => 'You must be logged in to add to cart',
    //         ];
    //         return redirect('/customer/login')->with($output);
    //     }


    //     $product = Product::find($request->product_id);

    //     if (!$product) {
    //         $output = [
    //             'success' => false,
    //             'msg' => 'Product not found',
    //         ];
    //         return redirect()->back()->with($output);
    //     }

    //     $filters = $request->only(['start_date', 'end_date', 'qty']);

    //     if ($product->isAvailableAt($filters)) {
    //         try {
    //             DB::beginTransaction();

    //             $cart = Cart::create([
    //                 'customer_id' => $customer->id,
    //                 'product_id' => $product->id,
    //                 'start_date' => $filters['start_date'],
    //                 'end_date' => $filters['end_date'],
    //                 'qty' => $filters['qty'],
    //                 'price' => $product->price,
    //                 'total' => $product->price * $filters['qty'],
    //             ]);

    //             DB::commit();
    //             $output = [
    //                 'success' => true,
    //                 'msg' => 'Product added to cart',
    //             ];
    //             return redirect()->back()->with($output);
    //         } catch (Exception $e) {
    //             // \Log::error($e->getMessage());
    //             DB::rollBack();
    //             $output = [
    //                 'success' => false,
    //                 'msg' => 'Something went wrong!',
    //             ];
    //             return redirect()->back()->with($output);
    //         }
    //     } else {
    //         $output = [
    //             'success' => false,
    //             'msg' => 'Product is not available',
    //         ];
    //         return redirect()->back()->with($output);
    //     }
    // }


    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'qty' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $customer = auth()->guard('customer')->user();
        if (!$customer) {
            return redirect('/customer/login')->with([
                'success' => false,
                'msg' => 'You must be logged in to Create your cart.',
            ]);
        }

        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'msg' => 'Product not found',
            ]);
        }

        $filters = $request->only(['start_date', 'end_date', 'qty', 'discount']);

        // Retrieve product availability
        $dates = DB::table('product_dates')
            ->where('product_id', $product->id)
            ->where('start_date', '<=', $filters['end_date'])
            ->where('end_date', '>=', $filters['start_date'])
            ->where('is_active', true)
            ->get();
        
        $start = new \DateTime($filters['start_date']);
        $end = new \DateTime($filters['end_date']);
        $end->modify('+1 day');

        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);

        $minAvailable = PHP_INT_MAX;
        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $available = $dates->first(function ($d) use ($day) {
                return $day >= $d->start_date && $day <= $d->end_date;
            });

            if (!$available) {
                $minAvailable = 0;
                break;
            } else {
                $minAvailable = min($minAvailable, $available->number);
            }
        }

        $totalBooked = DB::table('transaction_selllines')
            ->join('transactions', 'transactions.id', '=', 'transaction_selllines.transaction_id')
            ->where('transaction_selllines.product_id', $request->product_id)
            ->where(function ($query) use ($request) {
                $query->where('transaction_selllines.start_date', '<=', $request->end_date)
                ->where('transaction_selllines.end_date', '>=', $request->start_date);
      })
            ->sum('transaction_selllines.qty');

        $availableStock = $minAvailable - $totalBooked;

        if ($availableStock < $filters['qty']) {
            return redirect()->back()->with([
                'success' => false,
                'msg' => __("stock_error", [
                    'availableStock' => $availableStock,
                    'qty' => $filters['qty'],
                ]),
            ]);
        }

        try {
            DB::beginTransaction();

            Cart::create([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'start_date' => $filters['start_date'],
                'end_date' => $filters['end_date'],
                'qty' => $filters['qty'],
                'price' => $product->price,
                'total' => $product->price * $filters['qty'],
                'discount' => (($product->price * $filters['qty']) - ($filters['discount'])*$filters['qty']) ,
                'grand_total' => $filters['discount']*$filters['qty'],
                // 'discount' => ($product->price * $filters['qty']) - ($product->discount_price * $filters['qty']),
                // 'grand_total' => ($product->price * $filters['qty']) - ($product->price * $filters['qty']) + ($product->discount_price * $filters['qty']),

            ]);

            DB::commit();

            $output = [
                'success' => true,
                'msg' => __('Created Successfully!'),
            ];
            return redirect()->back()->with($output);
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => false,
                'msg' => __('Something went wrong!'),
            ];
            return redirect()->back()->with($output);
        }
    }

    public function edit($id)
    {
        $customer = auth()->guard('customer')->user();
        $carts = Cart::where('customer_id', $customer->id)->get();
        $subtotal = $carts->sum('total');
        $cart = Cart::findOrFail($id);
        $product = $cart->product;
        // $relatedProducts = Product::where('brand_category_id', $product->brand_category_id)
        //     ->where('id', '!=', $product->id)
        //     ->with(['category', 'brandCategory']) // Load category and brand relationships
        //     ->limit(4)
        //     ->get();
        $relatedProducts = Product::where('brand_category_id', $product->brand_category_id)
        ->where('id', '!=', $product->id)
        ->with(['category', 'brandCategory']) // Load category and brand relationships
        ->paginate(8); 

        // Fetch promotion products with their promotions using a join
        $promotionProducts = DB::table('promotion_products')
            ->join('promotions', 'promotion_products.promotion_id', '=', 'promotions.id')
            ->select('promotion_products.*', 'promotions.discount_value')
            ->get();

        // Fetch promotion categories with their promotions using a join
        $promotionCategories = DB::table('promotion_categories')
            ->join('promotions', 'promotion_categories.promotion_id', '=', 'promotions.id')
            ->select('promotion_categories.*', 'promotions.discount_value')
            ->get();

        return view('bookingwebsite.cart.edit', compact('cart', 'product', 'carts', 'subtotal', 'relatedProducts', 'promotionProducts', 'promotionCategories'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'qty' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $customer = auth()->guard('customer')->user();
        if (!$customer) {
            return redirect('/customer/login')->with([
                'success' => false,
                'msg' => 'You must be logged in to update your cart.',
            ]);
        }

        $cart = Cart::findOrFail($id);
        $product = $cart->product;

        if (!$product) {
            return redirect()->back()->with([
                'success' => false,
                'msg' => 'Product not found.',
            ]);
        }

        $filters = $request->only(['start_date', 'end_date', 'qty' ,'discount']);

        // ✅ Check product availability
        $dates = DB::table('product_dates')
            ->where('product_id', $product->id)
            ->where('start_date', '<=', $filters['end_date'])
            ->where('end_date', '>=', $filters['start_date'])
            ->where('is_active', true)
            ->get();

        $start = new \DateTime($filters['start_date']);
        $end = new \DateTime($filters['end_date']);
        $end->modify('+1 day');

        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);

        $minAvailable = PHP_INT_MAX;
        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $available = $dates->first(fn($d) => $day >= $d->start_date && $day <= $d->end_date);

            if (!$available) {
                $minAvailable = 0;
                break;
            } else {
                $minAvailable = min($minAvailable, $available->number);
            }
        }

        $totalBooked = DB::table('transaction_selllines')
            ->join('transactions', 'transactions.id', '=', 'transaction_selllines.transaction_id')
            ->where('transaction_selllines.product_id', $product->id)
            ->where(function ($query) use ($filters) {
                $query->where('transaction_selllines.start_date', '<=', $filters['end_date'])
                    ->where('transaction_selllines.end_date', '>=', $filters['start_date']);
            })
            ->sum('transaction_selllines.qty');

        $availableStock = $minAvailable - $totalBooked;

        if ($availableStock < $filters['qty']) {
            return redirect()->back()->with([
                'success' => false,
                'msg' => __("stock_error", [
                    'availableStock' => $availableStock,
                    'qty' => $filters['qty'],
                ]),
            ]);
        }

        // ✅ Update cart if availability check passes
        try {
            DB::beginTransaction();

            $cart->start_date = $filters['start_date'];
            $cart->end_date = $filters['end_date'];
            $cart->qty = $filters['qty'];
            $cart->total = $product->price * $filters['qty'];
            $cart->discount = (($product->price * $filters['qty']) - ($filters['discount'])*$filters['qty']);
            $cart->grand_total = $cart->total - $cart->discount;
            

            $cart->save();

            DB::commit();

            $output = [
                'success' => true,
                'msg' => __('Updated Successfully!'),
            ];
            return redirect()->back()->with($output);
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => false,
                'msg' => __('Something went wrong!'),
            ];
            return redirect()->back()->with($output);
        }
    }
    public function checkBookingAvailability(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'qty' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $customer = auth()->guard('customer')->user();
        if (!$customer) {
            return redirect('/customer/login')->with([
                'success' => false,
                'msg' => 'You must be logged in to add to cart',
            ]);
        }

        $product = Product::find($request->product_id);
        $filters = $request->only(['start_date', 'end_date', 'qty','discount']);

        // Retrieve product availability from product_dates table
        $dates = DB::table('product_dates')
            ->where('product_id', $product->id)
            ->where('start_date', '<=', $filters['end_date'])
            ->where('end_date', '>=', $filters['start_date'])
            ->where('is_active', true)
            ->get();

        // Convert dates to a period
        $start = new \DateTime($filters['start_date']);
        $end = new \DateTime($filters['end_date']);
        $end->modify('+1 day'); // Ensure end date is included

        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);

        // Check the minimum available stock for each day in the range
        $minAvailable = PHP_INT_MAX; // Start with a high number
        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $available = $dates->first(function ($d) use ($day) {
                return $day >= $d->start_date && $day <= $d->end_date;
            });

            // If no availability for a specific date, booking is not possible
            if (!$available) {
                $minAvailable = 0;
                break;
            } else {
                $minAvailable = min($minAvailable, $available->number);
            }
        }

        // Get the total number of products already booked in this period
        $totalBooked = DB::table('transaction_selllines')
            ->join('transactions', 'transactions.id', '=', 'transaction_selllines.transaction_id')
            ->where('transaction_selllines.product_id', $request->product_id)
            ->where(function ($query) use ($request) {
                $query->where('transaction_selllines.start_date', '<=', $request->end_date)
                ->where('transaction_selllines.end_date', '>=', $request->start_date);
      })
            ->sum('transaction_selllines.qty');

        // Final available stock after deducting booked items
        $availableStock = $minAvailable - $totalBooked;

        // If not enough stock, prevent booking
        if ($availableStock < $filters['qty']) {
            return redirect()->back()->with([
                'success' => false,
               'msg' => __("stock_error", [
                    'availableStock' => $availableStock,
                    'qty' => $filters['qty'],
                ]),
            ]);
        }

        // Add product to cart
        try {
            DB::beginTransaction();

            $cart = Cart::create([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'start_date' => $filters['start_date'],
                'end_date' => $filters['end_date'],
                'qty' => $filters['qty'],
                'price' => $product->price,
                'total' => $product->price * $filters['qty'],
                'discount' => (($product->price * $filters['qty']) - ($filters['discount'])*$filters['qty']) ,
                'grand_total' => $filters['discount']*$filters['qty'],
            ]);

            DB::commit();

            // Construct the URL with the selected cart_id as query parameter
            $selectedCarts = json_encode([$cart->id]);
           
            // Redirect with the selected_carts as a query parameter
            // return redirect("http://127.0.0.1:8000/checkout?selected_carts=$selectedCarts")->with([
            //     'success' => true,
            //     'msg' => 'Product is available for the selected dates and quantity',
            // ]);

            return redirect()->route('checkout.index', ['selected_carts' => $selectedCarts])->with([
                'success' => true,
                'msg' => 'Product is available for the selected dates and quantity',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with([
                'success' => false,
                'msg' => __('Something went wrong!'),
            ]);
        }
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $cart = Cart::findOrFail($id);
            $cart->delete();
            DB::commit();

            $output = [
                'success' => true,
                'msg' => __('Deleted Successfully!'),
            ];
            return redirect()->route('cart.index')->with($output);
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => false,
                'msg' => __('Something went wrong!'),
            ];
            return redirect()->route('cart.index')->with($output);
        }
    }
}
