{{-- 
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __('Product Name') }}</th>
            <th>{{ __('Quantity') }}</th>
            <th>{{ __('Price') }}</th>
            <th>{{ __('Discount') }}</th>
            <th>{{ __('Total') }}</th>
            <th>{{ __('Grand Total') }}</th>
            <th>{{ __('Booking Date') }}</th>
            <th>{{ __('Action') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($carts as $cart)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if ($cart->product)
                    {{ $cart->product->name }}
                    @else
                        {{ __('Product not found') }}
                    @endif
                </td>
                <td>{{ $cart->qty }}</td>
                <td>{{ $cart->price }}</td>
                <td>{{ $cart->discount }}</td>
                <td>{{ $cart->total }}</td>
                <td>{{ $cart->grand_total }}</td>
                <td>{{ $cart->start_date }} to {{ $cart->end_date }}</td>
                <td>
                    <a href="{{ route('cart.edit', $cart->id) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="d-inline-block form-delete-{{ $cart->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm btn-delete">
                            <i class="fa fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $cart->id }}_second" data-id="{{ $cart->id }}" data-total="{{ $cart->total }}" name="selected_carts[]">
                        <label class="custom-control-label" for="status_{{ $cart->id }}_second"></label>
                    </div>
                </td>                
            </tr>
        @endforeach
    </tbody>
</table>

<style>
    .btn-secondary {
        background-color: #6c757d;
    }

    .btn-success {
        background-color: #28a745;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Function to update the Grand Total
    function updateGrandTotal() {
        var total = 0;
        $('.switcher_input:checked').each(function () {
            var itemTotal = parseFloat($(this).data('total'));

            // Debugging: Log data-total value to the console
            console.log($(this).data('total'));  // Check the value of data-total
            
            // Check if the value is a valid number
            if (!isNaN(itemTotal)) {
                total += itemTotal;
            }
        });

        // Update the Grand Total text
        $('#grandTotal').text(total.toFixed(2));
    }

    // Function to update the button color based on the selected carts
    function updateButtonColor() {
        var selectedCarts = $('.switcher_input:checked').length;
        if (selectedCarts > 0) {
            $('#freezeButton').removeClass('btn-secondary').addClass('btn-success');
        } else {
            $('#freezeButton').removeClass('btn-success').addClass('btn-secondary');
        }
    }

    $(document).ready(function () {
        updateButtonColor();
        updateGrandTotal();

        // Listen for changes in the checkbox selection
        $('.switcher_input').on('change', function () {
            updateGrandTotal();
            updateButtonColor();
        });

        // Handle settlement form submission
        $('#freezeButton').on('click', function (e) {
            e.preventDefault();
            var selectedCarts = [];
            $('.switcher_input:checked').each(function () {
                selectedCarts.push($(this).data('id'));
            });

            if (selectedCarts.length === 0) {
                alert('Please select at least one cart item to proceed.');
                return;
            }

            // Set selected carts in the hidden input
            $('#selected_carts').val(JSON.stringify(selectedCarts));
            $('#settlementForm').submit();
        });
    });
</script>  
 
 --}}







<style>
    /* Responsive Table Container */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table th,
    .table td {
        white-space: nowrap;
        font-size: 16px;
        /* Restored original size */
        text-align: center;
        vertical-align: middle;
        padding: 12px;
    }

    /* Styling for smaller screens */
    @media (max-width: 992px) {

        .table th,
        .table td {
            font-size: 15px;
            padding: 10px;
        }

        .btn-sm {
            font-size: 14px;
            padding: 6px 8px;
        }
    }

    @media (max-width: 768px) {

        .table th,
        .table td {
            font-size: 14px;
            padding: 8px;
        }

        .btn {
            font-size: 14px;
            padding: 6px 10px;
        }

        .custom-control {
            text-align: center;
        }
    }

    /* Styling buttons */
    .btn {
        font-size: 15px;
        /* Restored original button size */
        padding: 8px 14px;
        border-radius: 4px;
    }

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

    /* Grand Total Section */
    .grand-total-container {
        display: flex;
        justify-content: end;
        align-items: center;
        margin-top: 15px;
        font-size: 18px;
        /* Restored original size */
    }

    /* Responsive Grand Total */
    @media (max-width: 768px) {
        .grand-total-container {
            justify-content: center;
            font-size: 16px;
        }
    }
</style>


<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Product') }}</th>
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
                                data-number="{{ $productDates[$cart->product_id]->number ?? 0 }}" data-grand_total="{{ $cart->grand_total }}" name="selected_carts[]">
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

    function updateFreezeButton() {
        var selectedCarts = $('.switcher_input:checked').length;
        var $freezeButton = $('#freezeButton'); // Target the settlement button

        if (selectedCarts > 0) {
            $freezeButton.removeClass('btn-secondary').addClass('btn-success'); // Change to green
       ('btn-success').addClass('btn-secondary'); // Reset to gray
        }
    }

    function updateStockAvailability() {
        $('.switcher_input').each(function() {
            var cartId = $(this).data('id');
            var $checkbox = $(this);

            $.ajax({
                url: '/check-stock-availability', // Your endpoint to check stock
                type: 'POST',
                data: {
                    cart_id: cartId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $checkbox.data('number', response.number);
                }
            });
        });
    }

    $(document).ready(function() {
        updateFreezeButton();
        updateGrandTotal();
        updateStockAvailability();

        $('.switcher_input').on('change', function() {
            var qty = $(this).data('qty');
            var number = $(this).data('number');

            if (qty > number) {
                alert('Run out of stock');
                $(this).prop('checked', false);
                return;
            }

            updateGrandTotal();
            updateFreezeButton();
        });

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

    $(document).ready(function() {
        // Attach click event listener to .btn-delete
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