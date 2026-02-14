<div class="container mt-5">
    <h2 class="mb-4">{{ __('Related Products') }}</h2>
    <div class="row">
        @foreach($relatedProducts as $related)
            <div class="col-md-3 mb-4">
                <div class="card h-100 d-flex flex-column">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 250px; display: flex; align-items: center; justify-content: center; background-color: #f8f8f8; padding: 10px;">
                        @php
                            $promotionProduct = $promotionProducts->firstWhere('product_id', $related->id);
                            $promotionCategory = $promotionCategories->firstWhere('category_id', $related->category_id);
                            $finalPrice = $related->price;
                            $discountPercent = 0;
                            $showDiscount = false;

                            if ($promotionProduct && $promotionProduct->discount_value > 0) {
                                $discountPercent = $promotionProduct->discount_value;
                                $finalPrice -= ($discountPercent / 100) * $related->price;
                                $showDiscount = true;
                            } elseif ($promotionCategory && $promotionCategory->discount_value > 0) {
                                $discountPercent = $promotionCategory->discount_value;
                                $finalPrice -= ($discountPercent / 100) * $related->price;
                                $showDiscount = true;
                            } elseif ($related->discount > 0) {
                                $discountPercent = $related->discount;
                                $finalPrice -= ($discountPercent / 100) * $related->price;
                                $showDiscount = true;
                            }
                        @endphp

                        @if ($showDiscount)
                            <span style="position: absolute; top: 10px; left: 10px; background-color: red; color: white; padding: 5px 10px; font-size: 14px; font-weight: bold; border-radius: 5px;">
                                -{{ $discountPercent }}%
                            </span>
                        @endif

                        <a href="{{ route('cart.create', $related->id) }}" class="d-block">
                            <img src="{{ asset('uploads/products/' . $related->image) }}"
                                 alt="{{ $related->name }}"
                                 style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </a>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title">{{ $related->name }}</h5>

                        @if ($showDiscount)
                            <p class="card-text text-muted"><del>${{ number_format($related->price, 2) }}</del></p>
                            <p class="card-text text-success fw-bold">${{ number_format($finalPrice, 2) }}</p>
                        @else
                            <p class="card-text fw-bold">${{ number_format($related->price, 2) }}</p>
                        @endif

                        <a href="{{ route('cart.create', $related->id) }}" class="btn btn-primary">{{ __('View') }}</a>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- Pagination links -->
<div class="d-flex justify-content-center">
    {{ $relatedProducts->links() }}
</div>
    </div>
</div>
