{{-- <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Product') }}</th>
                <th>{{ __('stock Number') }}</th>
                <th>{{ __('Quantity') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Discount') }}</th>
                <th>{{ __('Sub Total') }}</th>
                <th>{{ __('Final Total') }}</th>
                <th>{{ __('Booking Date') }}</th>
                <th>{{ __('Action') }}</th>
                <th>{{ __('Select') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($carts as $cart)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $cart->product->name ?? __('Product not found') }}</td>
                    <td>
                        @php
                            $productDate = \App\Models\ProductDate::where('product_id', $cart->product_id)
                                ->where('start_date', $cart->start_date)
                                ->where('end_date', $cart->end_date)
                                ->first();
                        @endphp
                        {{ $productDate ? $productDate->number : __('No stock available') }}
                    </td>
                    <td>{{ $cart->qty }}</td>
                    <td>${{ number_format($cart->price, 2) }}</td>
                    <td>${{ number_format($cart->discount, 2) }}</td>
                    <td>${{ number_format($cart->total, 2) }}</td>
                    <td>${{ number_format($cart->grand_total, 2) }}</td>
                    <td>{{ $cart->start_date }} to {{ $cart->end_date }}</td>
                    <td>
                        <a href="{{ route('cart.edit', $cart->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="custom-control custom-checkbox text-center">
                            <input type="checkbox" class="custom-control-input switcher_input status"
                                id="status_{{ $cart->id }}_second" data-id="{{ $cart->id }}"
                                data-total="{{ $cart->total }}" data-qty="{{ $cart->qty }}"
                                data-number="{{ $productDate->number ?? 0 }}" data-grand_total="{{ $cart->grand_total }}" name="selected_carts[]">
                            <label class="custom-control-label" for="status_{{ $cart->id }}_second"></label>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Grand Total Display -->
<div class="grand-total-container">
    <strong>Grand Total: $<span id="grandTotal">0.00</span></strong>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Function to update the Grand Total
    function updateGrandTotal() {
        var grand_total = 0;
        $('.switcher_input:checked').each(function() {
            var itemTotal = parseFloat($(this).data('grand_total'));
            if (!isNaN(itemTotal)) {
                grand_total += itemTotal;
            }
        });

        $('#grandTotal').text(grand_total.toFixed(2));
    }

    // Function to update the freeze button color
    function updateFreezeButton() {
        var selectedCarts = $('.switcher_input:checked').length;
        var $freezeButton = $('#freezeButton'); // Target the settlement button

        if (selectedCarts > 0) {
            $freezeButton.removeClass('btn-secondary').addClass('btn-success'); // Change to green
        } else {
            $freezeButton.removeClass('btn-success').addClass('btn-secondary'); // Reset to gray
        }
    }

    // Function to check the stock availability
    $(document).ready(function() {
        updateFreezeButton();
        updateGrandTotal();

        $('.switcher_input').on('change', function() {
            var qty = $(this).data('qty');
            var number = $(this).data('number');

            if (qty > number) {
                // Show out of stock message
                toastr.error('Out of stock for this booking date.');
                $(this).prop('checked', false); // Uncheck the box if stock is not available
                return;
            }

            updateGrandTotal();
            updateFreezeButton();
        });

        // Handle freeze button click
        $('#freezeButton').on('click', function(e) {
            e.preventDefault();
            var selectedCarts = [];

            $('.switcher_input:checked').each(function() {
                selectedCarts.push($(this).data('id'));
            });

            if (selectedCarts.length === 0) {
                alert('Please select at least one cart item to proceed.');
                return;
            }

            $('#selected_carts').val(JSON.stringify(selectedCarts));
            $('#settlementForm').submit();
        });
    });

    // Display success or error messages using Toastr
    $(document).ready(function() {
        console.log('Session Message:', "{{ Session::get('msg') }}");
        console.log('Session Success:', "{{ Session::get('success') }}");

        @if (Session::has('msg'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            };

            @if (Session::get('success') == true)
                toastr.success("{{ Session::get('msg') }}");
            @else
                toastr.error("{{ Session::get('msg') }}");
            @endif
        @endif
    });

    // SweetAlert2 for delete confirmation
    $(document).ready(function() {
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault(); // Prevent default form submission

            // SweetAlert2 configuration for confirmation dialog
            const Confirmation = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            // Show confirmation dialog
            Confirmation.fire({
                title: '{{ __('Are you sure?') }}',
                text: @json(__('You won\'t be able to revert this!')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('Yes, delete it!') }}',
                cancelButtonText: '{{ __('No, cancel!') }}',
                reverseButtons: true
            }).then((result) => {
                // If confirmed, submit the form
                if (result.isConfirmed) {
                    var form = $(this).closest('form'); // Get the correct form associated with the button
                    form.submit(); // Submit the form
                }
            });
        });
    });
</script>

<style>
    /* Styling for the Grand Total section */
    .grand-total-container {
        display: flex;
        justify-content: end;
        align-items: center;
        margin-top: 15px;
        font-size: 18px;
    }

    /* Styling buttons */
    /* .btn {
        font-size: 15px;
        padding: 8px 14px;
        border-radius: 4px;
    } */

    .btn-danger {
        background-color: #dc3545;
        border: none;
        color: white;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        color: white;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
    }

    /* Styling the checkbox */
    .custom-control-label {
        cursor: pointer;
    }

    /* Ensure table is responsive */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on mobile */
    }

    /* Stack table rows on small screens */
    @media (max-width: 768px) {
        .table-responsive table {
            display: block;
            width: 100%;
        }

        .table-responsive thead,
        .table-responsive tbody,
        .table-responsive th,
        .table-responsive td,
        .table-responsive tr {
            display: block;
        }

        .table-responsive thead {
            display: none; /* Hide headers on small screens */
        }

        .table-responsive tr {
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        .table-responsive td {
            text-align: right;
            padding-left: 50%;
            position: relative;
            border-bottom: 1px solid #ddd;
        }

        .table-responsive td::before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            text-align: left;
            font-weight: bold;
        }

        /* Add data-label attributes to each td */
        @foreach ($carts as $cart)
            tr td:nth-child(1)::before { content: "#"; }
            tr td:nth-child(2)::before { content: "Product"; }
            tr td:nth-child(3)::before { content: "Product Date Number"; }
            tr td:nth-child(4)::before { content: "Quantity"; }
            tr td:nth-child(5)::before { content: "Price"; }
            tr td:nth-child(6)::before { content: "Discount"; }
            tr td:nth-child(7)::before { content: "Sub Total"; }
            tr td:nth-child(8)::before { content: "Final Total"; }
            tr td:nth-child(9)::before { content: "Booking Date"; }
            tr td:nth-child(10)::before { content: "Action"; }
            tr td:nth-child(11)::before { content: "Select"; }
        @endforeach
    }
</style> --}}




<div class="table-responsive" style="overflow-x: auto;">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Product') }}</th>
                <th>{{ __('Number Available') }}</th>
                <th>{{ __('Quantity') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Discount') }}</th>
                <th>{{ __('Sub Total') }}</th>
                <th>{{ __('Final Total') }}</th>
                <th>{{ __('Booking Date') }}</th>
                <th>{{ __('Action') }}</th>
                <th>{{ __('Select') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($carts as $cart)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $cart->product->name ?? __('Product not found') }}</td>
                    <td>
                        @php
                            $productDate = \App\Models\ProductDate::where('product_id', $cart->product_id)
                                ->where('start_date', $cart->start_date)
                                ->where('end_date', $cart->end_date)
                                ->first();
                        @endphp
                        {{ $productDate ? $productDate->number : __('No stock available') }}
                    </td>
                    <td>{{ $cart->qty }}</td>
                    <td>${{ number_format($cart->price, 2) }}</td>
                    <td>${{ number_format($cart->discount, 2) }}</td>
                    <td>${{ number_format($cart->total, 2) }}</td>
                    <td>${{ number_format($cart->grand_total, 2) }}</td>
                    <td>{{ $cart->start_date }} to {{ $cart->end_date }}</td>
                    <td>
                        <a href="{{ route('cart.edit', $cart->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="custom-control custom-checkbox text-center">
                            <input type="checkbox" class="custom-control-input switcher_input status"
                                id="status_{{ $cart->id }}_second" data-id="{{ $cart->id }}"
                                data-total="{{ $cart->total }}" data-qty="{{ $cart->qty }}"
                                data-number="{{ $productDate->number ?? 0 }}" data-grand_total="{{ $cart->grand_total }}" name="selected_carts[]">
                            <label class="custom-control-label" for="status_{{ $cart->id }}_second"></label>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Grand Total Display -->
{{-- <div class="grand-total-container">
    <strong>Grand Total: $<span id="grandTotal">0.00</span></strong>
</div> --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Function to update the Grand Total
    function updateGrandTotal() {
        var grand_total = 0;
        $('.switcher_input:checked').each(function() {
            var itemTotal = parseFloat($(this).data('grand_total'));
            if (!isNaN(itemTotal)) {
                grand_total += itemTotal;
            }
        });

        $('#grandTotal').text(grand_total.toFixed(2));
    }

    // Function to update the freeze button color
    function updateFreezeButton() {
        var selectedCarts = $('.switcher_input:checked').length;
        var $freezeButton = $('#freezeButton'); // Target the settlement button

        if (selectedCarts > 0) {
            $freezeButton.removeClass('btn-secondary').addClass('btn-success'); // Change to green
        } else {
            $freezeButton.removeClass('btn-success').addClass('btn-secondary'); // Reset to gray
        }
    }

    // Function to check the stock availability
    $(document).ready(function() {
        updateFreezeButton();
        updateGrandTotal();

        $('.switcher_input').on('change', function() {
            var qty = $(this).data('qty');
            var number = $(this).data('number');

            if (qty > number) {
                // Show out of stock message
                toastr.error('Out of stock for this booking date.');
                $(this).prop('checked', false); // Uncheck the box if stock is not available
                return;
            }

            updateGrandTotal();
            updateFreezeButton();
        });

        // Handle freeze button click
        $('#freezeButton').on('click', function(e) {
            e.preventDefault();
            var selectedCarts = [];

            $('.switcher_input:checked').each(function() {
                selectedCarts.push($(this).data('id'));
            });

            if (selectedCarts.length === 0) {
                alert('Please select at least one cart item to proceed.');
                return;
            }

            $('#selected_carts').val(JSON.stringify(selectedCarts));
            $('#settlementForm').submit();
        });
    });

    // Display success or error messages using Toastr
    $(document).ready(function() {
        console.log('Session Message:', "{{ Session::get('msg') }}");
        console.log('Session Success:', "{{ Session::get('success') }}");

        @if (Session::has('msg'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            };

            @if (Session::get('success') == true)
                toastr.success("{{ Session::get('msg') }}");
            @else
                toastr.error("{{ Session::get('msg') }}");
            @endif
        @endif
    });

    // SweetAlert2 for delete confirmation
    $(document).ready(function() {
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault(); // Prevent default form submission

            // SweetAlert2 configuration for confirmation dialog
            const Confirmation = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            // Show confirmation dialog
            Confirmation.fire({
                title: '{{ __('Are you sure?') }}',
                text: @json(__('You won\'t be able to revert this!')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('Yes, delete it!') }}',
                cancelButtonText: '{{ __('No, cancel!') }}',
                reverseButtons: true
            }).then((result) => {
                // If confirmed, submit the form
                if (result.isConfirmed) {
                    var form = $(this).closest('form'); // Get the correct form associated with the button
                    form.submit(); // Submit the form
                }
            });
        });
    });
</script>

<style>
    /* Styling for the Grand Total section */
    .grand-total-container {
        display: flex;
        justify-content: end;
        align-items: center;
        margin-top: 15px;
        font-size: 18px;
    }

    /* Styling buttons */
    /* .btn {
        font-size: 15px;
        padding: 8px 14px;
        border-radius: 4px;
    } */

    .btn-danger {
        background-color: #dc3545;
        border: none;
        color: white;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        color: white;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
    }

    /* Styling the checkbox */
    .custom-control-label {
        cursor: pointer;
    }

    /* Ensure table is responsive with horizontal scrolling */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on mobile */
    }

    .table-responsive table {
        min-width: 1000px; /* Set a minimum width for the table */
    }

    /* Optional: Add a shadow to indicate scrollability */
    .table-responsive {
        position: relative;
    }

    .table-responsive::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 20px;
        background: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
        pointer-events: none;
    }
</style>