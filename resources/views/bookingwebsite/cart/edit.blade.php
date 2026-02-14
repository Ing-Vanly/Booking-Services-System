@extends('bookingwebsite.master')

@section('content')
    <style>
        .product-image { 
            display: block; 
            margin: auto; 
            width: 100%; 
            max-width: 600px; 
            height: auto; 
            object-fit: contain; 
            border-radius: 8px; 
        }

        .card-custom { 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
            border: none; 
            transition: transform 0.2s, opacity 0.2s; 
            opacity: 0.95; 
        }

        .card-custom:hover { 
            transform: translateY(-3px); 
            opacity: 1; 
        }

        .card-header-custom { 
            background: #f8f9fa; 
            font-weight: bold; 
            border-bottom: 1px solid #e9ecef; 
        }

        .btn-custom { 
            border-radius: 6px; 
            padding: 8px 16px; 
        }

        .price { 
            font-size: 1.5rem; 
            color: #28a745; 
        }

        .original-price { 
            font-size: 1rem; 
            color: #6c757d; 
            text-decoration: line-through; 
        }

        .discount { 
            font-size: 1.2rem; 
            color: #dc3545; 
        }

        .half-width { 
            width: 50%; 
        }

        /* Centering the content */
        .center-content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }

        .center-column {
            width: 100%;
            max-width: 1100px;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <div class="container center-content">
        <div class="center-column">
            <div class="space-4">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card card-custom mb-3">
                            <div class="card-header card-header-custom">
                                <h1>{{ $product->name }}</h1>
                            </div>
                            <div class="card-body">
                                <img fetchpriority="high" src="{{ asset('/uploads/products/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                            </div>
                        </div>
                    </div>

                    <!-- Adjusted filter column width to col-md-6 -->
                    <div class="col-md-6 mb-4">
                        <div class="card card-custom">
                            <div class="card-header card-header-custom">
                                {{-- <h1>Filter</h1> --}}
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title">{{__('Product')}}: <span class="fw-bold">{{ $product->name }}</span></h5>
                                <p class="card-text">{{__('Category')}}: <span>{{ $product->category->name }}</span></p>
                                <p class="card-text">{{__('Brand Category')}}: <span>{{ $product->brandCategory->name }}</span></p>
                                <p class="card-text">{{__('Price')}}: <span class="price">${{ number_format($product->price, 2) }}</span></p>

                                @php
                                    $promotionProduct = $promotionProducts->firstWhere('product_id', $product->id);
                                    $promotionCategory = $promotionCategories->firstWhere('category_id', $product->category_id);
                                    $finalPrice = $product->price;
                                    $showDiscount = false;
                                    if ($promotionProduct && $promotionProduct->discount_value > 0) {
                                        $finalPrice = $product->price - ($promotionProduct->discount_value / 100) * $product->price;
                                        $showDiscount = true;
                                    } elseif ($promotionCategory && $promotionCategory->discount_value > 0) {
                                        $finalPrice = $product->price - ($promotionCategory->discount_value / 100) * $product->price;
                                        $showDiscount = true;
                                    } elseif ($product->discount > 0) {
                                        $finalPrice = $product->price - ($product->discount / 100) * $product->price;
                                        $showDiscount = true;
                                    }
                                @endphp

                                @if ($showDiscount)
                                    <p class="card-text original-price">${{ number_format($product->price, 2) }}</p>
                                    <p class="card-text discount">${{ number_format($finalPrice, 2) }}</p>
                                @else
                                    <p class="card-text price">${{ number_format($product->price, 2) }}</p>
                                @endif
                                
                                <form action="{{ route('cart.update', $cart->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                
                                    <input type="hidden" name="cart_id" value="{{ $cart->id }}">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="start_date">{{__('Start Date')}} <span class="text-danger">*</span></label>
                                            <input type="date" name="start_date" id="start_date" class="form-control" 
                                                value="{{ old('start_date', $cart->start_date) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="end_date">{{__('End Date')}} <span class="text-danger">*</span></label>
                                            <input type="date" name="end_date" id="end_date" class="form-control" 
                                                value="{{ old('end_date', $cart->end_date) }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="qty">{{__('Quantity')}} <span class="text-danger">*</span></label>
                                            <input type="number" name="qty" id="qty" class="form-control" 
                                                value="{{ old('qty', $cart->qty) }}" oninput="calculateTotal()" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total">{{__('Total')}}</label>
                                            <input type="text" name="total" id="total" class="form-control" disabled 
                                                value="{{ old('qty', $cart->qty) * $finalPrice }}">
                                        </div>
                                    </div>
                                
                                    <input type="hidden" id="discount" name="discount" value="{{ $finalPrice }}">
                                
                                    <div class="mb-3 d-flex justify-content-end btn-group">
                                        <button type="submit" class="btn btn-primary btn-custom">{{__('Update Cart')}}</button>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h1>{{__('Description')}}</h1>
            <div class="card-text">{{__('Description')}}: <span>{{ $product->description }}</span></div>
            @include('bookingwebsite.cart.related')
        </div>
    </div>

    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        function submitForm(route) {
            document.getElementById('productForm').action = route === 'cart.checkAvailability' ? '{{ route('cart.checkAvailability') }}' : '{{ route('cart.checkBookingAvailability') }}';
            document.getElementById('productForm').submit();
        }

        $(document).ready(function() {
            @if (Session::has('msg'))
                toastr.options = { "closeButton": true, "progressBar": true };
                @if (Session::get('success'))
                    toastr.success("{{ Session::get('msg') }}");
                @else
                    toastr.error("{{ Session::get('msg') }}");
                @endif
            @endif
        });

        const productPrice = {{ $finalPrice }};
        function calculateTotal() {
            const qty = document.getElementById('qty').value;
            document.getElementById('total').value = isNaN(qty * productPrice) ? '' : (qty * productPrice).toFixed(2);
        }
    </script>
@endsection