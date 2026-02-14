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

.card-img-top {
    width: 100%;
    height: 150px; /* Set a smaller fixed height for the images */
    object-fit: cover; /* Ensure the image covers the entire area */
    border-radius: 8px 8px 0 0; /* Match the card's border radius */
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
    <h2 class="mb-4 text-center">{{__('Promotion Products')}}</h2>
    <div class="container">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($promotionProducts as $product)
            @php
                // Calculate the final price after applying the discount
                $finalPrice = $product->discount_price ?? $product->price;
                if (!$product->discount_price && $product->discount_value > 0) {
                    $discountAmount = ($product->discount_value / 100) * $product->price;
                    $finalPrice = $product->price - $discountAmount;
                }
            @endphp

            <div class="col-md-4 mb-4">
                <div class="card card-custom shadow-sm h-100">
                    <img src="{{ asset('/uploads/products/' . $product->image) }}" 
                        class="card-img-top" 
                        alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p>{{__('Price')}}:<strong>{{ $product->price }}$</strong></p>
                       {{-- Category: <strong>{{ $product->category_name }}</strong></p>
                        <p class="card-text">Brand: <strong>{{ $product->brand_category_name }}</strong></p> --}}
                        <p class="card-text"><strong>{{ $product->discount_value }}%</strong></p>

                        <!-- Price Calculation -->
                        @if($product->discount_price)
                            <!-- If product has its own discount -->
                                                           <span class="text-muted"><del>${{ number_format($product->price, 2) }}</del></span>
                                <span class="text-success fw-bold">${{ number_format($product->discount_price, 2) }}</span>
                                <span class="badge bg-danger">
                                    -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% Off
                                </span>
                            </p>
                        @elseif($product->discount_value > 0)
                            <!-- If no product discount, apply promotion discount -->
                            <p class="card-text">
                                <span class="text-muted"><del>${{ number_format($product->price, 2) }}</del></span>
                                <span class="text-success fw-bold">${{ number_format($finalPrice, 2) }}</span>
                                <span class="badge bg-danger">
                                    -{{ $product->discount_value }}% Off
                                </span>
                            </p>
                        @else
                            <!-- No discount applied -->
                            <p class="card-text"><span class="fw-bold">${{ number_format($product->price, 2) }}</span></p>
                        @endif

                        <a href="{{ route('cart.create', $product->id) }}" class="btn btn-primary btn-custom">{{__('View')}}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection