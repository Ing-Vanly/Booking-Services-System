@extends('bookingwebsite.master')
@section('content')
    @push('css')
        <style>
            .card-custom {
                border-radius: 8px;
                overflow: hidden;
                transition: transform 0.3s;
            }

            .card-custom:hover {
                transform: scale(1.05);
            }

            .btn-custom {
                background-color: #007bff;
                border-color: #007bff;
                color: #fff;
            }

            .btn-custom:hover {
                background-color: #0056b3;
                border-color: #004085;
            }
        </style>
    @endpush

    <div class="container space-4">
        <h2 class="mb-4 text-center">{{ __('Promotion Categories') }}</h2>
        <div class="row">
            @foreach ($promotionCategories->unique('id') as $category)
                <div class="col-md-12 mb-4">
                    <div class="card card-custom shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text">{{ __('Discount Value') }}:
                                <strong>{{ $category->discount_value }}%</strong></p>

                            <div class="container mt-4">
                                <h4 class="mb-3">{{ __('Products in this Category') }}</h4>
                                <div class="row g-4">
                                    @foreach ($promotionProducts->where('category_id', $category->id)->whereNull('deleted_at')->unique('id') as $product)
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <div class="card card-custom shadow-sm h-100">
                                                <img src="{{ asset('/uploads/products/' . $product->image) }}"
                                                    class="card-img-top" alt="{{ $product->name }}"
                                                    style="object-fit: cover; border-radius: 8px;">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $product->name }}</h5>
                                                    <p class="card-text">{{ __('Price') }}:
                                                        <strong>${{ number_format($product->price, 2) }}</strong></p>

                                                    <a href="{{ route('cart.create', $product->id) }}"
                                                        class="btn btn-primary btn-custom">
                                                        {{ __('View') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach 
        </div>
    </div>
@endsection
