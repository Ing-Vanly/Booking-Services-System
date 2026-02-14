@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Purchase Products') }}</h1>
    <form action="{{ route('purchase.store') }}" method="POST">
        @csrf
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Product Name') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th>{{ __('Booking Date') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
                        <td>
                            <input type="number" name="quantity[{{ $product->id }}]" class="form-control quantity" data-id="{{ $product->id }}" data-price="{{ $product->price }}" min="1" max="{{ $product->product_dates->number }}" required>
                        </td>
                        <td>{{ $product->price }}</td>
                        <td class="total">{{ $product->price }}</td>
                        <td>
                            <input type="date" name="start_date[{{ $product->id }}]" class="form-control" required>
                            <input type="date" name="end_date[{{ $product->id }}]" class="form-control" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm add-to-cart" data-id="{{ $product->id }}">{{ __('Add to Cart') }}</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">{{ __('Proceed to Checkout') }}</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.quantity').on('input', function () {
            var quantity = $(this).val();
            var price = $(this).data('price');
            var total = quantity * price;
            $(this).closest('tr').find('.total').text(total.toFixed(2));
        });

        $('.add-to-cart').on('click', function () {
            var productId = $(this).data('id');
            var quantity = $('input[name="quantity[' + productId + ']"]').val();
            var startDate = $('input[name="start_date[' + productId + ']"]').val();
            var endDate = $('input[name="end_date[' + productId + ']"]').val();

            if (quantity > 0 && startDate && endDate) {
                $.ajax({
                    url: '{{ route("cart.add") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId,
                        quantity: quantity,
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function (response) {
                        alert('Product added to cart successfully!');
                    },
                    error: function (response) {
                        alert('Error adding product to cart.');
                    }
                });
            } else {
                alert('Please fill in all fields correctly.');
            }
        });
    });
</script>
@endsection