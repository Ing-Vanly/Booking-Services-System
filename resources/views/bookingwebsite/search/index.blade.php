@extends('bookingwebsite.master')

@section('content')
    <style>
        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: red;
            color: white;
            padding: 5px 10px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
        }
    </style>

    <div class="container-fluid">
        <div class="space-4">
            @include('bookingwebsite.layouts.filter')
        </div>
        <div id="content" class="site-content text-break" tabindex="-1">
            <div class="container pt-5 pt-xl-8">
                <div class="row mb-5 mb-lg-8 mt-xl-1">
                    <!-- Main Content -->
                    <div class="main col-lg-8 col-xl-9 order-1 pb-5 pb-lg-0 order-lg-2">
                        <header class="woocommerce-products-header"></header>
                        <div class="woocommerce-notices-wrapper"></div>
                        <div class="d-md-flex justify-content-between align-items-center mb-4">
                            <h3 class="font-size-21 font-weight-bold mb-0 text-lh-1">
                                <span class="page-title">{{ __('shop_results_found', ['productCount' => $productCount]) }}</span>
                            </h3>
                        </div>
                        @include('bookingwebsite.search.topfilter')
                        <div class="row" id="products-container">
                            @foreach ($products as $product)
                                @php
                                    $promotionProduct = $promotionProducts->firstWhere('product_id', $product->id);
                                    $promotionCategory = $promotionCategories->firstWhere('category_id', $product->category_id);
                                    $finalPrice = $product->price;
                                    $discountPercent = 0;
                                    $showDiscount = false;

                                    if ($promotionProduct && $promotionProduct->discount_value > 0) {
                                        $discountPercent = $promotionProduct->discount_value;
                                        $finalPrice = $product->price - ($discountPercent / 100 * $product->price);
                                        $showDiscount = true;
                                    } elseif ($promotionCategory && $promotionCategory->discount_value > 0) {
                                        $discountPercent = $promotionCategory->discount_value;
                                        $finalPrice = $product->price - ($discountPercent / 100 * $product->price);
                                        $showDiscount = true;
                                    } elseif ($product->discount > 0) {
                                        $discountPercent = $product->discount;
                                        $finalPrice = $product->price - ($discountPercent / 100 * $product->price);
                                        $showDiscount = true;
                                    }
                                @endphp

                                <div class="col-12 col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <!-- Product Image -->
                                        <div class="card-img-top position-relative overflow-hidden">
                                            @if ($showDiscount)
                                                <span class="discount-badge">-{{ $discountPercent }}%</span>
                                            @endif
                                            <a href="{{ route('cart.create', $product->id) }}" class="d-block">
                                                {{-- <img src="{{ asset('uploads/products/' . $product->image) }}"
                                                    class="img-fluid w-100" alt="{{ $product->name }}"
                                                    style="height: 200px; object-fit: cover;"> --}}
                                                    <img src="{{ asset($product->image ? 'uploads/products/' . $product->image : 'uploads/image/default.png') }}"
                                                    alt="Product Image"
                                                    class="card-img-top img-fluid"
                                                    style="width: 100%; height: 230px; object-fit: contain; background-color: #f8f8f8; padding: 5px;">
                                               
                                            </a>
                                        </div>
                                        <!-- Product Details -->
                                        <div class="card-body p-3">
                                            <a href="{{ route('cart.create', $product->id) }}" class="text-decoration-none">
                                                <h5 class="card-title font-size-17 font-weight-medium mb-2">
                                                    {{ $product->name }}
                                                </h5>
                                            </a>
                                            <div class="price-wrapper mb-2">
                                                <span class="text-muted font-size-14">{{__('Price')}}:</span>
                                                <span class="font-weight-bold text-dark">${{ number_format($product->price, 2) }}</span>
                                            </div>
                                            
                                            @if ($showDiscount)
                                                <p class="card-text text-muted"><del>${{ number_format($product->price, 2) }}</del></p>
                                                <p class="card-text text-success fw-bold">${{ number_format($finalPrice, 2) }}</p>
                                            @else
                                                <p class="card-text fw-bold">${{ number_format($product->price, 2) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination Links with Filters -->
                        <div class="text-center mt-4">
                            {{ $products->appends(request()->query())->links() }}
                        </div>

                        <!-- Result Count -->
                        <div class="text-center text-md-left font-size-14 mb-3 text-lh-1">
                            <p class="woocommerce-result-count text-dark text-lh-1">
                                {{ __('showing_results', ['productCount' => $productCount]) }}
                            </p>
                        </div>
                    </div>
                    <!-- Sidebar -->
                    <div class="shop-sidebar col-lg-4 col-xl-3 order-lg-1 width-md-50">
                        <div class="navbar-expand-lg navbar-expand-lg-collapse-block">
                            <button class="btn d-lg-none mb-5 p-0 collapsed" type="button" data-toggle="collapse"
                                data-target="#shop-sidebar" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="far fa-caret-square-down text-primary font-size-20 card-btn-arrow ml-0"></i>
                                <span class="text-primary ml-2">Sidebar</span>
                            </button>
                            <div id="shop-sidebar" class="collapse navbar-collapse">
                                <div class="mb-6 w-100">
                                    @include('bookingwebsite.search.leftfilter')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-bottom border-gray-33"></div>
        </div>
    </div>
@endsection
