@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Book a Purchase - Add Customer Details</h2>

    <form action="{{ route('bookings.store') }}" method="POST">
        @csrf

        <!-- Customer Selection -->
        <div class="form-group">
            <label for="customer_id">Select Customer</label>
            <select name="customer_id" id="customer_id" class="form-control">
                <option value="">Select Existing Customer</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Guest Info -->
        <div class="form-group">
            <label for="guest_info">Guest Name (if no customer ID)</label>
            <input type="text" name="guest_info" id="guest_info" class="form-control">
        </div>

        <!-- Booking Date -->
        <div class="form-group">
            <label for="booking_date">Booking Date</label>
            <input type="date" name="booking_date" id="booking_date" class="form-control" required>
        </div>

        <!-- Payment Details -->
        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-control">
                <option value="cash">Cash</option>
                <option value="credit_card">Credit Card</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>
        </div>

        <div class="form-group">
            <label for="payment_status">Payment Status</label>
            <select name="payment_status" id="payment_status" class="form-control">
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
            </select>
        </div>

        <!-- Cart Items Table -->
        <h4>Selected Cart Items</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach ($selectedCarts as $cart) {{-- Only show selected carts --}}
                    @php $grandTotal += $cart->total; @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cart->product->name ?? 'N/A' }}</td>
                        <td>{{ $cart->qty }}</td>
                        <td>${{ number_format($cart->price, 2) }}</td>
                        <td>${{ number_format($cart->total, 2) }}</td>
                        <input type="hidden" name="selected_carts[]" value="{{ $cart->id }}">
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Grand Total -->
        <div class="d-flex justify-content-end">
            <h4>Grand Total: ${{ number_format($grandTotal, 2) }}</h4>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success mt-3">Confirm Booking</button>
    </form>
</div>
@endsection
