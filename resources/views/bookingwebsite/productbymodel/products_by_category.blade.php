@extends('bookingwebsite.master')

@section('content')
    <div class="container-fluid">
        <div class="space-4">
            @include('bookingwebsite.layouts.filter', ['categories' => $categories])
        </div>
        <div id="content" class="site-content text-break" tabindex="-1">
            <div class="container pt-5 pt-xl-8">
                <div class="row mb-5 mb-lg-8 mt-xl-1">
                    <div class="main col-lg-8 col-xl-9 order-1 pb-5 pb-lg-0 order-lg-2">
                        <header class="woocommerce-products-header"></header>
                        <div class="woocommerce-notices-wrapper"></div>
                        <div class="d-md-flex justify-content-between align-items-center mb-4">
                            <h3 class="font-size-21 font-weight-bold mb-0 text-lh-1">
                                <span class="page-title">Shop:</span> {{ $products->count() }} results found
                            </h3>
                            <ul class="nav tab-nav-shop flex-nowrap mt-4 mt-md-0" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link font-size-22 p-0 ml-2 active" id="grid-view-tab" data-toggle="pill"
                                        href="#grid-view" role="tab" aria-controls="grid-view" aria-selected="false">
                                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                                            <i class="fa fa-th"></i>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @include('bookingwebsite.search.topfilter')
                        <div class="row" id="products-container">
                            @foreach ($products as $product)
                                <div class="col-12 col-md-6 col-lg-4 mb-4">
                                    <div class="product-card card h-100 shadow-sm">
                                        <div class="card-img-top position-relative overflow-hidden">
                                            <a href="{{ route('cart.create', $product->id) }}" class="d-block">
                                                <img src="{{ asset('uploads/products/' . $product->image) }}"
                                                    class="img-fluid w-100" alt="{{ $product->name }}"
                                                    style="height: 200px; object-fit: cover;">
                                            </a>
                                        </div>
                                        <div class="card-body p-3">
                                            <a href="{{ route('cart.create', $product->id) }}" class="text-decoration-none">
                                                <h5 class="card-title font-size-17 font-weight-medium mb-2">
                                                    {{ $product->name }}
                                                </h5>
                                            </a>
                                            <div class="mb-2">
                                                <span class="text-muted font-size-14">Category:</span>
                                                <span class="font-size-14">{{ $product->category->name }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-muted font-size-14">Brand Category:</span>
                                                <span class="font-size-14">{{ $product->brandCategory->name }}</span>
                                            </div>
                                            <div class="price-wrapper mb-2">
                                                <span class="text-muted font-size-14">Price:</span>
                                                <span class="font-weight-bold text-dark">${{ $product->price }}</span>
                                            </div>

                                            @php
                                            $promotionProduct = $promotionProducts->firstWhere('product_id', $product->id);
                                            $promotionCategory = $promotionCategories->firstWhere('category_id', $product->category_id);
                                            $finalPrice = $product->price;
                                            $discountAmount = 0;
                                            $showDiscount = false;

                                            if ($promotionProduct && $promotionProduct->discount_value > 0) {
                                                $discountAmount = ($promotionProduct->discount_value / 100) * $product->price;
                                                $finalPrice = $product->price - $discountAmount;
                                                $showDiscount = true;
                                            } elseif ($promotionCategory && $promotionCategory->discount_value > 0) {
                                                $discountAmount = ($promotionCategory->discount_value / 100) * $product->price;
                                                $finalPrice = $product->price - $discountAmount;
                                                $showDiscount = true;
                                            } elseif ($product->discount > 0) {
                                                $discountAmount = ($product->discount / 100) * $product->price;
                                                $finalPrice = $product->price - $discountAmount;
                                                $showDiscount = true;
                                            }
                                            @endphp
                                            @if ($showDiscount)
                                                <p class="card-text text-muted"><del>${{ number_format($product->price, 2) }}</del></p>
                                                <p class="card-text text-success">Discounted Price: ${{ number_format($finalPrice, 2) }}</p>
                                            @else
                                                <p class="card-text fw-bold">${{ number_format($product->price, 2) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center text-md-left font-size-14 mb-3 text-lh-1">
                            <p class="woocommerce-result-count text-dark text-lh-1">
                                Showing all {{ $products->count() }} results
                            </p>
                        </div>
                    </div>
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
                    </div><!-- /.sidebar -->
                </div><!-- /.row -->
            </div><!-- /.container -->
            <div class="border-bottom border-gray-33"></div>
        </div>
    </div>
@endsection
