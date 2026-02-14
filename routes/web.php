<?php

use App\Models\Category;
use App\Models\MenuCategory;
use App\helpers\ImageManager;
use App\Models\BoothCategory;
use App\Models\BusinessSetting;
use App\Models\PartnerCategory;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\Backends\PromotionController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\VisitorController;
use App\Http\Controllers\Backends\BlogController;
use App\Http\Controllers\Backends\RoleController;
use App\Http\Controllers\Backends\UserController;
use App\Http\Controllers\Web\ExhibitorController;
use App\Http\Controllers\Backends\BoothController;
use App\Http\Controllers\Backends\EventController;
use App\Http\Controllers\Backends\MediaController;
use App\Http\Controllers\Backends\MovieController;
use App\Http\Controllers\Web\ExhibitionController;
use App\Http\Controllers\Backends\NoticeController;
use App\Http\Controllers\Backends\SliderController;
use App\Http\Controllers\Backends\BookingController;
use App\Http\Controllers\Backends\ProductController;
use App\Http\Controllers\Backends\CategoryController;
use App\Http\Controllers\Backends\LanguageController;
use App\Http\Controllers\Backends\DashboardController;
use App\Http\Controllers\Backends\NewsletterController;
use App\Http\Controllers\Backends\EventDetailController;
use App\Http\Controllers\Backends\FileManagerController;
use App\Http\Controllers\Backends\TransactionController;
use App\Http\Controllers\Backends\MenuCategoryController;
use App\Http\Controllers\Backends\PhotoGalleryController;
use App\Http\Controllers\Backends\BoothCategoryController;
use App\Http\Controllers\Backends\BrandCategoryController;
use App\Http\Controllers\Backends\BusinessSettingController;
use App\Http\Controllers\Backends\PartnerCategoryController;
use App\Http\Controllers\Backends\ServiceForVisitorController;
use App\Http\Controllers\Backends\CustomerController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\bookingwebsite\MasterController;
use App\Http\Controllers\bookingwebsite\CartController;
use App\Http\Controllers\bookingwebsite\CheckoutController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\bookingwebsite\SearchController;
use App\Http\Controllers\bookingwebsite\ContactController;
use App\Http\Controllers\bookingwebsite\BookingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// change language
Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    $language = \App\Models\BusinessSetting::where('type', 'language')->first();
    session()->put('language_settings', $language);
    return redirect()->back();
})->name('change_language');



Auth::routes();

// save temp file
Route::post('save_temp_file', [FileManagerController::class, 'saveTempFile'])->name('save_temp_file');

// Route::redirect('/', '/admin/dashboard');

Route::post('save_temp_file', [FileManagerController::class, 'saveTempFile'])->name('save_temp_file');
Route::get('remove_temp_file', [FileManagerController::class, 'removeTempFile'])->name('remove_temp_file');

// back-end
Route::middleware(['auth', 'CheckUserLogin', 'SetSessionData'])->group(function () {

    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // setting
        Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
            Route::get('/', [BusinessSettingController::class, 'index'])->name('index');
            Route::put('/update', [BusinessSettingController::class, 'update'])->name('update');

            // language setup
            Route::group(['prefix' => 'language', 'as' => 'language.'], function () {
                Route::get('/', [LanguageController::class, 'index'])->name('index');
                Route::get('/create', [LanguageController::class, 'create'])->name('create');
                Route::post('/', [LanguageController::class, 'store'])->name('store');
                Route::get('/edit', [LanguageController::class, 'edit'])->name('edit');
                Route::put('/update', [LanguageController::class, 'update'])->name('update');
                Route::delete('delete/', [LanguageController::class, 'delete'])->name('delete');

                Route::get('/update-status', [LanguageController::class, 'updateStatus'])->name('update-status');
                Route::get('/update-default-status', [LanguageController::class, 'update_default_status'])->name('update-default-status');
                Route::get('/translate', [LanguageController::class, 'translate'])->name('translate');
                Route::post('translate-submit/{lang}', [LanguageController::class, 'translate_submit'])->name('translate.submit');
            });
        });

        Route::resource('user', UserController::class);

        Route::get('product-category/update_status', [CategoryController::class, 'updateStatus'])->name('product-category.update_status');
        Route::resource('product-category', CategoryController::class);

        Route::get('product/update_status', [ProductController::class, 'updateStatus'])->name('product.update_status');
        Route::resource('product', ProductController::class);


        // slider
        Route::resource('slider', SliderController::class);
        Route::resource('contacts', ContactController::class)->except(['create', 'edit', 'show','delete']);

        Route::resource('promotion', PromotionController::class);

        Route::get('brand-category/update_status', [BrandCategoryController::class, 'updateStatus'])->name('brand-category.update_status');
        Route::resource('brand-category', BrandCategoryController::class);

        Route::get('transaction/update_status', [TransactionController::class, 'updateStatus'])->name('transaction.update_status');
        Route::get('transaction/', [TransactionController::class, 'index'])->name('transaction.index');
        Route::get('transaction/create', [TransactionController::class, 'create'])->name('transaction.create');

        //Edit Profile
        // Route::resource('profile', ProfileController::class);
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');


        Route::get('/transaction/{id}/edit', [TransactionController::class, 'edit'])->name('transaction.edit');
        Route::put('/transaction/{id}/update', [TransactionController::class, 'update'])->name('transaction.update');
        Route::post('/transaction', [TransactionController::class, 'store'])->name('transaction.store');
        Route::delete('/transaction/{id}/destroy', [TransactionController::class, 'destroy'])->name('transaction.destroy');
        //Booking
        Route::get('/booking-date/loaddate', [BookingController::class, 'loadDates'])->name('booking.loaddate');
        Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
        Route::post('booking/store', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/booking/{id}/edit', [CustomerController::class, 'edit'])->name('booking.edit');

        Route::resource('roles', RoleController::class);
        //customer
        Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
        Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
        Route::put('/customer/{id}/update', [CustomerController::class, 'update'])->name('customer.update');
        Route::get('/customer/update-status', [CustomerController::class, 'updateStatus'])->name('customer.update_status');
        Route::delete('/customer/{id}/destroy', [CustomerController::class, 'destroy'])->name('customer.destroy');
        
    });

});


Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});




//Frontend
Route::get('/frontends', [FrontController::class, 'index'])->name('front.index');


//frontend
// Route::get('/', [FrontController::class, 'index'])->name('front.home');

Route::get('/home', [FrontController::class, 'index'])->name('front.home');

Route::middleware('guest:customer')->group(function () {
 
});

Route::middleware('auth:customer')->group(function () {
    Route::get('customer/dashboard', function () {
        return 'Customer Dashboard';
    })->name('customer.dashboard');

    Route::post('customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
});


Route::get('customer/register', [CustomerAuthController::class, 'showRegistrationForm'])->name('customer.register');
Route::post('customer/register', [CustomerAuthController::class, 'register']);
Route::get('customer/login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
Route::post('customer/login', [CustomerAuthController::class, 'login']);

Route::get('/customer/profile', [CustomerAuthController::class, 'profile'])->name('customer.profile')->middleware('auth:customer');

Route::middleware(['customer'])->group(function () {
    
    Route::get('/customer/profile/edit', [MasterController::class, 'editprofile'])->name('customer.profile.edit');
    Route::put('/customer/profile/update', [MasterController::class, 'updateprofile'])->name('customer.profile.update');

    
});
Route::get('/', [MasterController::class, 'index'])->name('front.home');

//Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/{id}/create', [CartController::class, 'create'])->name('cart.create');
Route::post('/product/check-availability', [CartController::class, 'checkAvailability'])->name('product.isAvailableAt');
Route::post('/cart/check-availability', [CartController::class, 'checkAvailability'])->name('cart.checkAvailability');
Route::post('/cart/check-booking-Availability', [CartController::class, 'checkBookingAvailability'])->name('cart.checkBookingAvailability');
Route::get('/cart/{id}/edit', [CartController::class, 'edit'])->name('cart.edit');
Route::put('/cart/{id}/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}/destroy', [CartController::class, 'destroy'])->name('cart.destroy');



//checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::put('/customer/{id}/update', [CheckoutController::class, 'update'])->name('customer.update');
Route::post('/checkout/complete-booking', [CheckoutController::class, 'completeBooking'])->name('checkout.completeBooking');


//search
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/about', [MasterController::class, 'about'])->name('about');

// Route::get('/contact', [ContactController::class, 'contact'])->name('contact.create');
// Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/contact', function () {
    return view('bookingwebsite.contact.contact');
})->name('contact.create');


Route::get('/detail', function () {
    return view('bookingwebsite.details.detail');
});


Route::get('/product/{id}', [MasterController::class, 'show'])->name('product.show');


Route::get('/brand/{id}', [BrandCategoryController::class, 'show'])->name('brand.show');



Route::get('/brand-category/{id}', [BrandCategoryController::class, 'show'])->name('brandCategory.show');



Route::get('/brand-category/{id}/products', [MasterController::class, 'showProducts'])->name('brandCategory.products');
Route::get('/category/{id}/products', [MasterController::class, 'showProducts'])->name('Category.products');






    Route::post('/cart/add', [BookingsController::class, 'add'])->name('cart.add');
    Route::post('/purchase', [BookingsController::class, 'store'])->name('purchase.store');


    Route::get('/promotions/products', [MasterController::class, 'promotionProducts'])->name('promotions.products');
    Route::get('/promotions/categories', [MasterController::class, 'promotionCategories'])->name('promotions.categories');




    Route::get('/category/{id}/products', [MasterController::class, 'showProductsByCategory'])
    ->name('category.products');

    // Route::post('/check-stock-availability', [CartController::class, 'checkStockAvailability']);

