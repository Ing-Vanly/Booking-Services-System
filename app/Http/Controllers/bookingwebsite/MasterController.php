<?php

namespace App\Http\Controllers\bookingwebsite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Promotion;
use App\Models\Promotionproduct;
use App\Models\BusinessSetting;
use App\Models\BrandCategory;
use App\Models\Promotion_product;
use Illuminate\Support\Facades\File;
use App\Models\translations;
use Illuminate\Support\Facades\DB;
class MasterController extends Controller
{
   public function show($id){
    $product = Product::findOrFail($id);
    return view('bookingwebsite.detail.index', compact('product'));
   }



public function showProducts($id)
{
    $brandCategory = BrandCategory::findOrFail($id);
    // $products = Product::where('brand_category_id', $id)->get(); // Assuming you have a brand_category_id in your products table
    $products = Product::where('brand_category_id', $id)->paginate(6);
    $categories = Category::with('brandCategories')->get();
    $brandcategories = BrandCategory::all();
    $productCount = $products->count();
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

    return view('bookingwebsite.productbymodel.products_by_brand_category', compact('brandCategory', 'products', 
    'categories', 'brandcategories','promotionCategories','promotionProducts','productCount'));
}
//     public function index()
//     {
     
//         $setting = new BusinessSetting();
//         $web_header_logo = optional($setting->where('type', 'web_header_logo')->first())->value;
//         $web_scroll_logo = optional($setting->where('type', 'web_scroll_logo')->first())->value;

    
//     $phone = optional($setting->where('type', 'phone')->first())->value;
//     $email = optional($setting->where('type', 'email')->first())->value;
//     $company_name = optional($setting->where('type', 'company_name')->first())->value;
      
//         // $categories = Category::with('brandCategories')->get();
//         // $productsByCategory = [];
//         // foreach ($categories as $category) {
//         //     $productsByCategory[$category->slug] = Product::where('category_id', $category->id)->get();
//         // }
//         // $brandCategories = BrandCategory::all();

    
//     $categories = Category::with('brandCategories')->get();
//     $productsByCategory = [];
//     foreach ($categories as $category) {
//         $productsByCategory[$category->slug] = Product::where('category_id', $category->id)
//                                                        ->whereHas('transaction_selllines', function($query) {
//                                                            // You can add additional filters on transactions here, if needed.
//                                                        })
//                                                        ->get();
//     }
//     $brandCategories = BrandCategory::all();
  
//         $brandCategories = BrandCategory::all();

//         // $carts = Cart::with('product')->get();

//         $carts = auth()->guard('customer')->check() ? Cart::where('customer_id', auth()->guard('customer')->id())->get() : collect();
//         $subtotal = $carts->sum(function ($cart) {
//             return $cart->qty * $cart->price;
//         });
//         $sliders = Slider::all();
//     // Fetch social media links & decode JSON
//     $socialMedia = optional($setting->where('type', 'social_media')->first())->value;
//     $socialMedias = $socialMedia ? json_decode($socialMedia, true) : [];

//     // Ensure all social media links have https://
//     foreach ($socialMedias as &$social) {
//         if (!str_starts_with($social['link'], 'http')) {
//             $social['link'] = 'https://' . $social['link'];
//         }
//     }
  

//      // Fetch promotion products with their promotions using a join
//      $promotionProducts = DB::table('promotion_products')
//      ->join('promotions', 'promotion_products.promotion_id', '=', 'promotions.id')
//      ->select('promotion_products.*', 'promotions.discount_value')
//      ->get();

//  // Fetch promotion categories with their promotions using a join
//  $promotionCategories = DB::table('promotion_categories')
//      ->join('promotions', 'promotion_categories.promotion_id', '=', 'promotions.id')
//      ->select('promotion_categories.*', 'promotions.discount_value')
//      ->get();

//      $promotion = Promotion::where('start_date', '<=', now())
//      ->where('end_date', '>=', now())
//      ->first();

//         return view('bookingwebsite.home', compact('carts', 'subtotal', 'sliders', 'categories', 'productsByCategory', 'web_header_logo', 'web_scroll_logo',
//          'socialMedias','phone', 'email','brandCategories',
//          'company_name','promotionProducts'
//          ,'promotionCategories','promotion'));

//     }
public function index(Request $request)
{
    $setting = new BusinessSetting();
    $web_header_logo = optional($setting->where('type', 'web_header_logo')->first())->value;
    $web_scroll_logo = optional($setting->where('type', 'web_scroll_logo')->first())->value;
    $phone = optional($setting->where('type', 'phone')->first())->value;
    $email = optional($setting->where('type', 'email')->first())->value;
    $company_name = optional($setting->where('type', 'company_name')->first())->value;
    
    // Fetch categories
    $categories = Category::with('brandCategories')->get();
    
    // Paginate products by category (use the page query parameter to paginate correctly)
    $categorySlug = $request->query('category', $categories->first()->slug); // Default to first category
    
    $productsByCategory = [];
    foreach ($categories as $category) {
        $productsByCategory[$category->slug] = Product::where('category_id', $category->id)
            ->whereHas('transaction_selllines', function ($query) {
                // Additional query logic
            })
            ->paginate(8); // 12 products per page
    }

    $brandCategories = BrandCategory::all();

    
//     $carts = auth()->guard('customer')->check() ? Cart::where('customer_id', auth()->guard('customer')->id())->get() : collect();
//     $productDates = DB::table('product_dates')->get(); // Assuming you have this table available

// // Filter the cart items based on the condition

    
//     $subtotal = $carts->sum(function ($cart) {
//         return $cart->qty * $cart->price;
//     });

$productDates = DB::table('product_dates')->get();

$carts = Cart::whereIn('product_id', $productDates->pluck('product_id'))->get();
$subtotal = $carts->sum('total');
   // Get the cart items for the authenticated customer
   // Get the cart items for the authenticated customer
  

    // Fetch sliders
    $sliders = Slider::all();

    // Fetch and decode social media links
    $socialMedia = optional($setting->where('type', 'social_media')->first())->value;
    $socialMedias = $socialMedia ? json_decode($socialMedia, true) : [];

    // Ensure all social media links have https://
    foreach ($socialMedias as &$social) {
        if (!str_starts_with($social['link'], 'http')) {
            $social['link'] = 'https://' . $social['link'];
        }
    }

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

    // Get active promotion
    $promotion = Promotion::where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->first();

        // Fetch the product dates by product_id (assuming you already have the carts object)



    return view('bookingwebsite.home', compact(
        'carts', 'subtotal', 'sliders', 'categories', 'productsByCategory', 
        'web_header_logo', 'web_scroll_logo', 'socialMedias', 'phone', 'email',
        'brandCategories', 'company_name', 'promotionProducts', 'promotionCategories', 'promotion', 'categorySlug','productDates'
    ));
}


    public function about()
    {
     
    
        $data = [];
        $setting = new BusinessSetting();
        $data['company_name'] = @$setting->where('type', 'company_name')->first()->value;
        $data['phone'] = @$setting->where('type', 'phone')->first()->value;
        $data['email'] = @$setting->where('type', 'email')->first()->value;
        $data['company_address'] = @$setting->where('type', 'company_address')->first()->value;
        $data['timezone'] = @$setting->where('type', 'timezone')->first()->value;
        $data['currency'] = @$setting->where('type', 'currency')->first()->value;
        $data['company_address'] = @$setting->where('type', 'company_address')->first()->value;
        $data['copy_right_text'] = @$setting->where('type', 'copy_right_text')->first()->value;
        $data['web_banner_logo'] = @$setting->where('type', 'web_banner_logo')->first()->value;
        
    
        // account info
        $data['account_holder'] = @$setting->where('type', 'account_holder')->first()->value;
        $data['account_number'] = @$setting->where('type', 'account_number')->first()->value;
        $data['bank'] = @$setting->where('type', 'bank')->first()->value;
        $data['swift_code'] = @$setting->where('type', 'swift_code')->first()->value;
        $data['bank_address'] = @$setting->where('type', 'bank_address')->first()->value;
        $data['account_holder_address'] = @$setting->where('type', 'account_holder_address')->first()->value;
    
        // $data['social_medias'] = [];
        // $social_media = $setting->where('type', 'social_media')->first();
        // if ($social_media) {
        //     $data['social_medias'] = $social_media->value;
        // }
    
        return view('bookingwebsite.aboutus.index', $data);
    }



    public function promotionProducts()
    {
        // $promotionProducts = DB::table('promotion_products')
        //     ->join('promotions', 'promotion_products.promotion_id', '=', 'promotions.id')
        //     ->join('products', 'promotion_products.product_id', '=', 'products.id')
        //     ->select('products.*', 'promotions.discount_value')
        //     ->where('promotions.discount_value', '>', 0)
        //     ->get();
        // Get all the promotion categories with their respective products that have promotions
        $promotionCategories = DB::table('promotion_categories')
        ->join('promotions', 'promotion_categories.promotion_id', '=', 'promotions.id')
        ->join('categories', 'promotion_categories.category_id', '=', 'categories.id')
        ->select('categories.*', 'promotions.discount_value', 'promotion_categories.category_id')
        ->where('promotions.discount_value', '>', 0)
        ->distinct()
        ->get();

    $promotionProducts = DB::table('products')
        ->join('promotion_products', 'products.id', '=', 'promotion_products.product_id')
        ->join('promotions', 'promotion_products.promotion_id', '=', 'promotions.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->join('brand_categories', 'products.brand_category_id', '=', 'brand_categories.id')
        ->select('products.*', 'promotions.discount_value', 'categories.name as category_name', 'brand_categories.name as brand_category_name')
        ->where('promotions.discount_value', '>', 0)
        ->distinct()
        ->get();

    return view('bookingwebsite.promotion.product', compact('promotionCategories', 'promotionProducts'));
    }
    
    public function promotionCategories()
    {
       
        $promotionCategories = DB::table('promotion_categories')
        ->join('promotions', 'promotion_categories.promotion_id', '=', 'promotions.id')
        ->join('categories', 'promotion_categories.category_id', '=', 'categories.id')
        ->select('categories.*', 'promotions.discount_value', 'promotion_categories.category_id')
        ->where('promotions.discount_value', '>', 0)
        ->distinct()
        ->get();

    $promotionProducts = DB::table('products')
        ->join('promotion_categories', 'products.category_id', '=', 'promotion_categories.category_id')
        ->join('promotions', 'promotion_categories.promotion_id', '=', 'promotions.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->join('brand_categories', 'products.brand_category_id', '=', 'brand_categories.id')
        ->select('products.*', 'promotions.discount_value', 'categories.name as category_name', 'brand_categories.name as brand_category_name')
        ->where('promotions.discount_value', '>', 0)
        ->distinct()
        ->get();
        return view('bookingwebsite.promotion.category', compact('promotionCategories','promotionProducts'));
    }





    public function editprofile()
    {
        $customer = auth()->guard('customer')->user();
        // $customer = Auth::guard('customer')->user();
        return view('bookingwebsite.customer.profile', compact('customer'));
    }

    // Update the customer profile
    public function updateprofile(Request $request)
    {
        // $customer = Auth::guard('customer')->user();
        $customer = auth()->guard('customer')->user();

        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update customer details
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;

        // Handle profile image upload
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

        return redirect()->route('customer.profile.edit')->with('success', 'Profile updated successfully.');
    }


    public function showProductsByCategory($categoryId)
    {
       // Find the category by ID
    $category = Category::findOrFail($categoryId);

    // Manually query the products that belong to this category
    $products = Product::where('category_id', $categoryId)->paginate(12); // You can adjust pagination

    // Fetch promotions for products and categories
    $promotionProducts = DB::table('promotion_products')
    ->join('promotions', 'promotion_products.promotion_id', '=', 'promotions.id')
    ->select('promotion_products.*', 'promotions.discount_value')
    ->get();
    
    // Fetch promotion categories with their promotions using a join
    $promotionCategories = DB::table('promotion_categories')
    ->join('promotions', 'promotion_categories.promotion_id', '=', 'promotions.id')
    ->select('promotion_categories.*', 'promotions.discount_value')
    ->get();
        return view('bookingwebsite.productbymodel.products_by_category', compact('category', 'products', 'promotionProducts', 'promotionCategories'));
    }
    

   
}