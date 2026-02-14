@extends('bookingwebsite.master')

@section('content')
    <div class="container-fluid ">
        <div class="space-4">
            @include('bookingwebsite.layouts.filter')
        </div>
        <div id="content" class="site-content text-break" tabindex="-1">

            <div class="container pt-5 pt-xl-8">
                <div class="row mb-5 mb-lg-8 mt-xl-1">
                    <div class="main col-lg-8 col-xl-9 order-1 pb-5 pb-lg-0 order-lg-2">
                        <header class="woocommerce-products-header">

                        </header>
                        <div class="woocommerce-notices-wrapper"></div>
                        <div class="d-md-flex justify-content-between align-items-center mb-4">
                            <h3 class="font-size-21 font-weight-bold mb-0 text-lh-1">
                                <span class="page-title">Shop:</span> {{ $productCount }} results found
                            </h3>
                            <ul class="nav tab-nav-shop flex-nowrap mt-4 mt-md-0" id="pills-tab" role="tablist">
                                {{-- <li class="nav-item">
                                    <a class="nav-link font-size-22 p-0" id="list-view-tab" data-toggle="pill"
                                        href="#list-view" role="tab" aria-controls="list-view" aria-selected="true">
                                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                                            <i class="fa fa-list"></i>
                                        </div>
                                    </a>
                                </li> --}}
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
                                    <div class="add-to-wishlist-after_add_to_cart product type-product post-18 status-publish first instock product_cat-hotel product_cat-lodge has-post-thumbnail shipping-taxable product-type-grouped mb-3 mb-md-4 pb-1">
                                        <div class="card transition-3d-hover shadow-hover-2 h-100 tab-card">
                                            <div class="card-header position-relative p-0">
                                                <a href="{{ route('cart.create', $product->id) }}"
                                                    class="d-block gradient-overlay-half-bg-gradient-v5">
                                                    <img fetchpriority="high" width="300" height="225"
                                                        src="{{ asset('uploads/products/' . $product->image) }}"
                                                        class="min-height-230 bg-img-hero card-img-top" alt="">
                                                </a>
                                            </div>
                                            <div class="position-relative card-body pl-3 pr-4 pt-2 pb-3">
                                                <a href="{{ route('cart.create', $product->id) }}"
                                                    class="stretched-link card-title text-dark">
                                                    <h2 class="woocommerce-loop-product__title font-size-17 font-weight-medium">
                                                        {{ $product->name }}
                                                    </h2>
                                                </a>
                                                <div class="mt-2">
                                                    <span class="mr-1 font-size-14 text-gray-1">Category :</span>
                                                    <span class="mr-1 font-size-14 text-gray-1">{{ $product->category->name }}</span>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="mr-1 font-size-14 text-gray-1">Brand Category :</span>
                                                    <span class="mr-1 font-size-14 text-gray-1">{{ $product->brandCategory->name }}</span>
                                                </div>
                                                <div class="price-wrapper mt-2 mb-0">
                                                    <span class="price">
                                                        <span class="mr-1 font-size-14 text-gray-1">Price :</span>
                                                        <span class="woocommerce-Price-amount font-weight-bold amount"><bdi>
                                                                <span class="woocommerce-Price-currencySymbol">$</span>{{ $product->price }}</bdi></span>
                                                    </span>
                                                </div>
                                                <div class="mt-2 mb-3">
                                                    <span class="mr-1 font-size-14 text-gray-1">After Discount :</span>
                                                    <span class="badge badge-pill badge-success py-1 px-2 font-size-16 border-radius-3 font-weight-normal">
                                                        <bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ $product->after_discount }}</bdi></span>
                                                        {{-- Display Discount Values from Promotion Products --}}
{{-- @php
$promotionProduct = $promotionProducts->firstWhere('product_id', $product->id);
$promotionCategory = $promotionCategories->firstWhere('category_id', $product->category_id);
@endphp
@if ($promotionProduct && $promotionProduct->discount_value > 0)
<p class="card-text text-success">Promotion Discount: {{ $promotionProduct->discount_value }}%</p>
@elseif ($promotionCategory && $promotionCategory->discount_value > 0)
<p class="card-text text-success">Category Promotion Discount: {{ $promotionCategory->discount_value }}%</p>
@else
@if ($product->discount)
    <p class="card-text text-primary">Discounted Price: ${{ number_format($product->price * (1 - $product->discount / 100), 2) }}</p>
    <p class="card-text text-success">Discount Value: {{ $product->discount }}%</p>
@endif
@endif --}}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center text-md-left font-size-14 mb-3 text-lh-1">
                            <p class="woocommerce-result-count text-dark text-lh-1">
                                Showing all {{ $productCount }} results</p>
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
                                    {{-- @include('bookingwebsite.search.topfilter') --}}
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
    
@endsection    --}}
