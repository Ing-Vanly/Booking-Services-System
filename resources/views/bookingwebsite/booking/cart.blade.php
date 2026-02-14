@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Your Cart') }}</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('purchase.store') }}" method="POST" id="cartForm">
        @csrf
        <input type="hidden" name="selected_carts" id="selected_carts">
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>{{ __('Product Name') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th>{{ __('Booking Date') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($carts as $cart)
                    <tr>
                        <td>
                            <input type="checkbox" class="cart-checkbox" name="selected_items[]" value="{{ $cart->id }}" data-total="{{ $cart->total }}">
                        </td>
                        <td>
                            @if($cart->product)
                                {{ $cart->product->name }}
                            @else
                                {{ __('Product not found') }}
                            @endif
                        </td>
                        <td>{{ $cart->qty }}</td>
                        <td>{{ $cart->price }}</td>
                        <td>{{ $cart->total }}</td>
                        <td>{{ $cart->start_date }} to {{ $cart->end_date }}</td>
                        <td>
                            <a href="{{ route('cart.edit', $cart->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">{{ __('Your cart is empty.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($carts->isNotEmpty())
            <div class="row">
                <div class="col-md-6">
                    <h4>{{ __('Grand Total:') }} <span id="grandTotal">0.00</span></h4>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" id="proceedToCheckout" class="btn btn-success">{{ __('Proceed to Checkout') }}</button>
                </div>
            </div>
        @endif
    </form>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to update the Grand Total
        function updateGrandTotal() {
            let grandTotal = 0;
            $('.cart-checkbox:checked').each(function () {
                grandTotal += parseFloat($(this).data('total'));
            });
            $('#grandTotal').text(grandTotal.toFixed(2));
        }

        // Select all checkboxes
        $('#selectAll').on('change', function () {
            $('.cart-checkbox').prop('checked', this.checked);
            updateGrandTotal();
        });

        // Update Grand Total when a checkbox is checked/unchecked
        $('.cart-checkbox').on('change', function () {
            updateGrandTotal();
        });

        // Proceed to Checkout button
        $('#proceedToCheckout').on('click', function () {
            const selectedCarts = [];
            $('.cart-checkbox:checked').each(function () {
                selectedCarts.push($(this).val());
            });

            if (selectedCarts.length === 0) {
                alert('Please select at least one item to proceed.');
                return;
            }

            // Set selected carts in the hidden input
            $('#selected_carts').val(JSON.stringify(selectedCarts));
            $('#cartForm').submit();
        });
    });
</script>
@endsection