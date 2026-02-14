{{-- 
 
<style>
.shopping-cart {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    width: 500px;
    background-color: #fff;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    z-index: 1000;
    padding: 15px;

    font-family: "Arial", sans-serif;
    font-size: 12px; /* Default font size */
}



.shopping-cart:hover .dropdown-content {
    display: block;
} 

</style>
 
     


      <div class="shopping-cart position-relative pl-2 pl-md-4 ml-auto d-none d-xl-block">
        <a id="shoppingCartDropdownInvoker" class="py-4 position-relative btn-text-secondary" 
            href="{{ auth()->guard('customer')->check() ? route('cart.index') : route('customer.login') }}"
            role="button" aria-controls="shoppingCartDropdown" aria-haspopup="true" 
            aria-expanded="true">
            
            <span class="flaticon-shopping-basket font-size-25"></span>
            <span class="badge rounded-circle position-absolute right-end">
                <span class="cart-contents-count">
                    {{ auth()->guard('customer')->check() ? $carts->count() : 0 }}
                </span>
            </span>
        </a>
    
        <div id="shoppingCartDropdown" class="dropdown-content">
            <div class="mytravel-minicart card-body p-0" style="max-height: 400px; overflow-y: auto;">
                @if (isset($carts) && $carts->count() > 0)
                    <div class="cart"> 
                        <h3 class="p-3 border-bottom">{{__('Cart')}}</h3>
                        <ul class="cart-list">
                            @foreach ($carts as $cart)
                                <li class="cart-item d-flex align-items-center justify-content-between p-3 border flex-column flex-md-row">
                                    <!-- Hidden checkbox (still functional) -->
                                    <input type="checkbox" class="cart-checkbox" name="cart_ids[]" 
                                        value="{{ $cart->id }}" checked style="display: none;">
    
                                    <div class="cart-item__details d-flex justify-content-between flex-grow-1 flex-column flex-md-row">
                                        <span><strong>{{__('Product')}}:</strong> {{ optional($cart->product)->name ?? 'N/A' }}</span>
                                        <span><strong>{{__('Quantity')}}:</strong> {{ $cart->qty }}</span>
                                        <span><strong>{{__('Price')}}:</strong> ${{ $cart->price }}</span>
                                        <span><strong>{{__('Total')}}:</strong> ${{ $cart->total }}</span>
                                    </div>
    
                                    <div class="cart-item__actions d-flex align-items-center ml-3 mt-3 mt-md-0">
                                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST"
                                            class="form-delete-{{ $cart->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
    
                        <form id="settlementForm" action="{{ route('checkout.index') }}" method="GET">
                            <div class="d-flex justify-content-end align-items-center mt-3 p-3 flex-column flex-md-row">
                                <strong class="mr-3">{{__('Grand Total')}}: $<span>{{ $subtotal }}</span></strong>
                                <input type="hidden" name="selected_carts" id="selected_carts">
                                
                                <button class="btn btn-success m-3">{{__('Settlement')}}</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="cart-empty text-center p-7">
                        <span class="btn btn-icon btn-soft-primary rounded-circle mb-3">
                            <span class="flaticon-shopping-basket btn-icon__inner"></span>
                        </span>
                        <span class="d-block">Your Cart is Empty</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        function updateSelectedCarts() {
            let selectedCarts = [];
            document.querySelectorAll('.cart-checkbox:checked').forEach((checkbox) => {
                selectedCarts.push(checkbox.value);
            });
            document.getElementById('selected_carts').value = JSON.stringify(selectedCarts);
        }
    
        // Auto-select all carts on page load
        window.onload = function() {
            document.querySelectorAll('.cart-checkbox').forEach((checkbox) => {
                checkbox.checked = true;
            });
            updateSelectedCarts();
        };
    </script>
    
    <style>
        @media (max-width: 768px) {
            .cart-item__details {
                flex-direction: column;
                align-items: flex-start;
            }
            .cart-item__actions {
                margin-left: 0;
                margin-top: 10px;
            }
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .cart-item__details span {
                display: block;
                margin-bottom: 5px;
            }
            .shopping-cart {
                display: block !important;
            }
        }
    </style>
     --}}



     <style>
        .shopping-cart {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            width: 500px; /* Default width for larger screens */
            background-color: #fff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            z-index: 1000;
            padding: 15px;
            font-family: "Arial", sans-serif;
            font-size: 12px; /* Default font size */
        }
        
        .shopping-cart:hover .dropdown-content {
            display: block;
        }
    
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dropdown-content {
                width: 300px; /* Adjust width for smaller screens */
                right: -50px; /* Adjust position to fit within the viewport */
            }
    
            .cart-item__details {
                flex-direction: column;
                align-items: flex-start;
            }
    
            .cart-item__actions {
                margin-left: 0;
                margin-top: 10px;
            }
    
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
            }
    
            .cart-item__details span {
                display: block;
                margin-bottom: 5px;
            }
    
            .shopping-cart {
                display: block !important;
            }
    
            .flaticon-shopping-basket {
                font-size: 20px; /* Adjust icon size for smaller screens */
            }
    
            .badge {
                font-size: 10px; /* Adjust badge size for smaller screens */
            }
        }
    
        @media (max-width: 480px) {
            .dropdown-content {
                width: 250px; /* Further adjust width for very small screens */
                right: -80px; /* Adjust position to fit within the viewport */
            }
    
            .flaticon-shopping-basket {
                font-size: 18px; /* Further adjust icon size for very small screens */
            }
    
            .badge {
                font-size: 8px; /* Further adjust badge size for very small screens */
            }
        }
    </style>
    
    <div class="shopping-cart position-relative pl-2 pl-md-4 ml-auto d-none d-xl-block">
        <a id="shoppingCartDropdownInvoker" class="py-4 position-relative btn-text-secondary" 
            href="{{ auth()->guard('customer')->check() ? route('cart.index') : route('customer.login') }}"
            role="button" aria-controls="shoppingCartDropdown" aria-haspopup="true" 
            aria-expanded="true">
            
            <span class="flaticon-shopping-basket font-size-25"></span>
            <span class="badge rounded-circle position-absolute right-end">
                <span class="cart-contents-count">
                    {{ auth()->guard('customer')->check() ? $carts->count() : 0 }}
                </span>
            </span>
        </a>
    
        <div id="shoppingCartDropdown" class="dropdown-content">
            <div class="mytravel-minicart card-body p-0" style="max-height: 400px; overflow-y: auto;">
                @if (isset($carts) && $carts->count() > 0)
                    <div class="cart"> 
                        <h3 class="p-3 border-bottom">{{__('Cart')}}</h3>
                        <ul class="cart-list">
                            @foreach ($carts as $cart)
                                @php
                                    $productDate = \App\Models\ProductDate::where('product_id', $cart->product_id)
                                        ->where('start_date', $cart->start_date)
                                        ->where('end_date', $cart->end_date)
                                        ->first();
                                @endphp
                                @if ($productDate && $cart->qty <= $productDate->number)
                                    <li class="cart-item d-flex align-items-center justify-content-between p-3 border flex-column flex-md-row">
                                        <!-- Hidden checkbox (still functional) -->
                                        <input type="checkbox" class="cart-checkbox" name="cart_ids[]" 
                                            value="{{ $cart->id }}" checked>
    
                                        <div class="cart-item__details d-flex justify-content-between flex-grow-1 flex-column flex-md-row">
                                            <span><strong>{{__('Product')}}:</strong> {{ optional($cart->product)->name ?? 'N/A' }}</span>
                                            <span><strong>{{__('Quantity')}}:</strong> {{ $cart->qty }}</span>
                                            <span><strong>{{__('Price')}}:</strong> ${{ $cart->price }}</span>
                                            <span><strong>{{__('Total')}}:</strong> ${{ $cart->total }}</span>
                                            <span><strong>{{__('Stock')}}:</strong> {{ $productDate->number }}</span>
                                        </div>
    
                                        <div class="cart-item__actions d-flex align-items-center ml-3 mt-3 mt-md-0">
                                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST"
                                                class="form-delete-{{ $cart->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
    
                        <form id="settlementForm" action="{{ route('checkout.index') }}" method="GET">
                            <div class="d-flex justify-content-end align-items-center mt-3 p-3 flex-column flex-md-row">
                                {{-- <strong class="mr-3">{{__('Grand Total')}}: $<span>{{ $subtotal }}</span></strong> --}}
                                <input type="hidden" name="selected_carts" id="selected_carts">
                                
                                <button class="btn btn-success m-3">{{__('Settlement')}}</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="cart-empty text-center p-7">
                        <span class="btn btn-icon btn-soft-primary rounded-circle mb-3">
                            <span class="flaticon-shopping-basket btn-icon__inner"></span>
                        </span>
                        <span class="d-block">Your Cart is Empty</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        function updateSelectedCarts() {
            let selectedCarts = [];
            document.querySelectorAll('.cart-checkbox:checked').forEach((checkbox) => {
                selectedCarts.push(checkbox.value);
            });
            document.getElementById('selected_carts').value = JSON.stringify(selectedCarts);
    
            // Enable or disable the Settlement button and change its text based on selection
            const settlementButton = document.querySelector('#settlementForm button');
            if (selectedCarts.length > 0) {
                settlementButton.disabled = false;
                settlementButton.textContent = '{{__('Settlement')}}';
            } else {
                settlementButton.disabled = true;
                settlementButton.textContent = '{{__('Not Available to Settlement')}}';
            }
        }
    
        // Update selected carts when checkboxes are clicked
        document.querySelectorAll('.cart-checkbox').forEach((checkbox) => {
            checkbox.addEventListener('change', updateSelectedCarts);
        });
    
        // Auto-select all visible carts on page load and update button state
        window.onload = function() {
            document.querySelectorAll('.cart-checkbox').forEach((checkbox) => {
                checkbox.checked = true;
            });
            updateSelectedCarts();
        };
    </script>