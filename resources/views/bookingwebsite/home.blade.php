{{-- @extends('bookingwebsite.master')
@section('content')
    <div class="elementor-section elementor-top-section elementor-element elementor-element-59d7c44 elementor-section-full_width destinantion-block destinantion-v1 border-bottom border-color-8 elementor-section-height-default elementor-section-height-default"
        data-id="59d7c44" data-element_type="section">
        <div class="elementor-container elementor-column-gap-default container space-bottom-1 space-top-lg-3">
            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-5978915"
                data-id="5978915" data-element_type="column">
                <div class="p-0 elementor-widget-wrap elementor-element-populated">
                    <div class="elementor-element elementor-element-a37980d w-md-80 w-lg-50 text-center mx-md-auto my-3 mb-0 elementor-widget elementor-widget-highlighted-heading"
                        data-id="a37980d" data-element_type="widget" data-widget_type="highlighted-heading.default">
                        <div class="elementor-widget-container">
                            <h2 class="mytravel-elementor-highlighted-heading__title section-title text-black font-size-30 font-weight-bold mb-0">
                                {{__('Trending')}}
                            </h2>
                        </div>
                    </div>

                    {{-- trending --}}
                    {{-- <div class="elementor-element elementor-element-ddf99de mb-0 elementor-widget elementor-widget-myt-nav-tabs"
                        data-id="ddf99de" data-element_type="widget" data-widget_type="myt-nav-tabs.default">
                        <div class="elementor-widget-container">
                            <ul class="nav flex-nowrap tab-nav text-nowrap tab-nav-pill pb-4 pb-lg-5 justify-content-lg-center"
                                role="tablist">
                                @foreach ($categories as $index => $category)
                                    <li class="nav-item myt-elementor__tab">
                                        <a class="nav-link font-weight-medium {{ $index === 0 ? 'active' : '' }}"
                                            id="nav-tab-{{ $category->slug }}" data-toggle="pill"
                                            data-target="#tab-{{ $category->slug }}" href="#" role="tab"
                                            aria-controls="nav-tab-{{ $category->slug }}"
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                            <div class="d-flex flex-column flex-md-row position-relative align-items-center text-dark">
                                                <span class="tabtext font-weight-semi-bold">{{ $category->name }}</span>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <section class="elementor-section elementor-inner-section elementor-element elementor-element-a27e7ba elementor-section-full_width elementor-section-height-default elementor-section-height-default"
                        data-id="a27e7ba" data-element_type="section">
                        <div class="elementor-container elementor-column-gap-default flex-wrap tab-content row-margin">
                            @foreach ($categories as $index => $category)
                                <div class="elementor-column elementor-col-16 elementor-inner-column elementor-element elementor-element-21d27f5 col-12 tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                    data-id="21d27f5" data-element_type="column" id="tab-{{ $category->slug }}"
                                    aria-labelledby="tab-{{ $category->slug }}"> --}}

                                    {{-- Display Products in the selected category --}}
                                    {{-- <div class="row">
                                        @foreach ($productsByCategory[$category->slug] as $product)
                                            <div class="col-md-4 col-sm-6 mb-4">
                                                <div class="card position-relative">
                                                    @php
                                                        $promotionProduct = $promotionProducts->firstWhere('product_id', $product->id);
                                                        $promotionCategory = $promotionCategories->firstWhere('category_id', $product->category_id);
                                                        $finalPrice = $product->price;
                                                        $discountPercent = 0;

                                                        if ($promotionProduct && $promotionProduct->discount_value > 0) {
                                                            $discountPercent = $promotionProduct->discount_value;
                                                            $finalPrice -= ($discountPercent / 100) * $product->price;
                                                        } elseif ($promotionCategory && $promotionCategory->discount_value > 0) {
                                                            $discountPercent = $promotionCategory->discount_value;
                                                            $finalPrice -= ($discountPercent / 100) * $product->price;
                                                        } elseif ($product->discount) {
                                                            $discountPercent = $product->discount;
                                                            $finalPrice -= ($discountPercent / 100) * $product->price;
                                                        }
                                                    @endphp

                                                    @if ($discountPercent > 0)
                                                        <span class="badge badge-danger position-absolute" 
                                                              style="top: 10px; right: 10px; background-color: red; color: white; padding: 5px 10px; font-size: 14px; border-radius: 5px;">
                                                            -{{ $discountPercent }}%
                                                        </span>
                                                    @endif

                                                    <img src="{{ asset($product->image ? 'uploads/products/' . $product->image : 'uploads/image/default.png') }}"
                                                        alt="Product Image" class="card-img-top img-fluid"
                                                        style="max-height: 230px; object-fit: cover;">
                                                    <div class="card-body">
                                                        <h5 class="card-title">
                                                            <a href="{{ route('cart.create', $product->id) }}"
                                                                class="text-dark text-decoration-none">
                                                                {{ $product->name }}
                                                            </a>
                                                        </h5>
                                                        <p class="card-text font-weight-bold text-dark">{{__('Price')}} :
                                                             ${{ number_format($product->price, 2) }}</p>
                                                        {{-- <p class="card-text">Category : {{ $product->category->name }}</p>
                                                        <p class="text-muted">Brand Category : {{ optional($category->brandCategories->first())->name }}</p> --}}

                                                        {{-- @if ($discountPercent > 0)
                                                            <p class="card-text text-muted">
                                                                <del>${{ number_format($product->price, 2) }}</del>
                                                            </p>
                                                            <p class="card-text text-success fw-bold">
                                                                ${{ number_format($finalPrice, 2) }}
                                                            </p>
                                                        @else
                                                            <p class="card-text fw-bold">
                                                                ${{ number_format($product->price, 2) }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    @include('bookingwebsite.promotion.index', ['promotion' => $promotion])
@endsection  --}}



@extends('bookingwebsite.master')
@section('content')
    <div class="container space-bottom-1 space-top-lg-3">
        <div class="text-center my-3 mb-0">
            <h2 class="section-title text-black font-size-30 font-weight-bold">
                {{ __('Trending') }}
            </h2>
        </div>

        {{-- Category Tabs --}}
        <ul class="nav flex-nowrap tab-nav text-nowrap tab-nav-pill pb-4 pb-lg-5 justify-content-lg-center"
            role="tablist">
            @foreach ($categories as $index => $category)
                <li class="nav-item myt-elementor__tab">
                    <a class="nav-link font-weight-medium {{ request('category', $categories->first()->slug) == $category->slug ? 'active' : '' }}"
                        id="nav-tab-{{ $category->slug }}" data-toggle="pill"
                        data-target="#tab-{{ $category->slug }}" href="?category={{ $category->slug }}" role="tab"
                        aria-controls="nav-tab-{{ $category->slug }}"
                        aria-selected="{{ request('category', $categories->first()->slug) == $category->slug ? 'true' : 'false' }}">
                        <div class="d-flex flex-column flex-md-row position-relative align-items-center text-dark">
                            <span class="tabtext font-weight-semi-bold">{{ $category->name }}</span>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content">
            @foreach ($categories as $index => $category)
                <div class="tab-pane fade {{ request('category', $categories->first()->slug) == $category->slug ? 'show active' : '' }}"
                     id="tab-{{ $category->slug }}" role="tabpanel">

                    {{-- Product Cards --}}
                    <div class="row">
                        @foreach ($productsByCategory[$category->slug]->items() as $product)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="card position-relative">
                                    @php
                                        $promotionProduct = $promotionProducts->firstWhere('product_id', $product->id);
                                        $promotionCategory = $promotionCategories->firstWhere('category_id', $product->category_id);
                                        $finalPrice = $product->price;
                                        $discountPercent = 0;

                                        if ($promotionProduct && $promotionProduct->discount_value > 0) {
                                            $discountPercent = $promotionProduct->discount_value;
                                            $finalPrice -= ($discountPercent / 100) * $product->price;
                                        } elseif ($promotionCategory && $promotionCategory->discount_value > 0) {
                                            $discountPercent = $promotionCategory->discount_value;
                                            $finalPrice -= ($discountPercent / 100) * $product->price;
                                        } elseif ($product->discount) {
                                            $discountPercent = $product->discount;
                                            $finalPrice -= ($discountPercent / 100) * $product->price;
                                        }
                                    @endphp

                                    @if ($discountPercent > 0)
                                        <span class="badge badge-danger position-absolute"
                                              style="top: 10px; right: 10px; background-color: red; color: white; padding: 5px 10px; font-size: 14px; border-radius: 5px;">
                                            -{{ $discountPercent }}%
                                        </span>
                                    @endif

                                    <img src="{{ asset($product->image ? 'uploads/products/' . $product->image : 'uploads/image/default.png') }}"
                                    alt="Product Image"
                                    class="card-img-top img-fluid"
                                    style="width: 100%; height: 230px; object-fit: contain; background-color: #f8f8f8; padding: 5px;">
                               
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="{{ route('cart.create', $product->id) }}"
                                               class="text-dark text-decoration-none">
                                                {{ $product->name }}
                                            </a>
                                        </h5>
                                        <p class="card-text font-weight-bold text-dark">{{__('Price')}} :
                                            ${{ number_format($product->price, 2) }}</p>

                                        @if ($discountPercent > 0)
                                            <p class="card-text text-muted">
                                                <del>${{ number_format($product->price, 2) }}</del>
                                            </p>
                                            <p class="card-text text-success fw-bold">
                                                ${{ number_format($finalPrice, 2) }}
                                            </p>
                                        @else
                                            <p class="card-text fw-bold">
                                                ${{ number_format($product->price, 2) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center w-100 mt-3">
                        {{ $productsByCategory[$category->slug]->appends(['category' => $category->slug])->links() }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Keep Active Tab on Reload --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let urlParams = new URLSearchParams(window.location.search);
            let activeCategory = urlParams.get('category') || "{{ $categories->first()->slug }}";
            let activeTab = document.querySelector(`a[href="?category=${activeCategory}"]`);

            if (activeTab) {
                document.querySelectorAll('.nav-link').forEach(tab => tab.classList.remove('active'));
                activeTab.classList.add('active');
            }
        });
    </script>
     @include('bookingwebsite.promotion.index', ['promotion' => $promotion])
@endsection
