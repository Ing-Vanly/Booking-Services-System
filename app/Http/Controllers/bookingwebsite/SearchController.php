<?php

namespace App\Http\Controllers\bookingwebsite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\BrandCategory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;

class SearchController extends Controller
{public function index(Request $request)
    {
        $carts = Cart::all();
        $subtotal = $carts->sum('total');
    
        $categories = Category::all();
        $brandcategories = BrandCategory::withCount('products')->get();
    
        $query = Product::query();
    
        // Apply filters to the query
        if ($request->filled('filter_locations')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->filter_locations);
            });
        }
    
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }
    
        if ($request->has('brandcategories')) {
            $brandcategoriesParam = $request->brandcategories;
    
            // If it's a single value, make it an array
            if (!is_array($brandcategoriesParam)) {
                $brandcategoriesParam = [$brandcategoriesParam];
            }
    
            // Apply filter if array
            if (count($brandcategoriesParam) > 0) {
                $query->whereIn('brand_category_id', $brandcategoriesParam);
            }
        }
    
        // Apply status filter
        $query->where('status', true);
    
        // Handle ordering
        switch ($request->orderby) {
            case 'popularity':
                $query->select('products.*')
                    ->join('transaction_selllines', 'transaction_selllines.product_id', '=', 'products.id')
                    ->groupBy('products.id')
                    ->orderBy(DB::raw('COUNT(transaction_selllines.product_id)'), 'desc');
                break;
    
            case 'date':
                $query->orderBy('created_at', 'desc');
                break;
    
            case 'price':
                $query->orderBy('price', 'asc');
                break;
    
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
    
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    
        // Get paginated results
        $products = $query->with('brandCategory')->paginate(6); // Adjust per page as needed
    
        // Get the total number of products for display
        $productCount = $products->total();
    
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
    
        // If the request is an AJAX call, return only the products
        if ($request->ajax()) {
            return response()->json([
                'products' => view('bookingwebsite.search.index', compact('products'))->render(),
            ]);
        }
    
        // Return the view with filters and paginated products
        return view('bookingwebsite.search.index', compact('carts', 'subtotal', 
        'categories', 'brandcategories', 'products','promotionCategories','promotionProducts', 'productCount'));
    }
    
}
