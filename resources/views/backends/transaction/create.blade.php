{{-- filepath: /c:/laragon/www/booking-service-system/resources/views/backends/transaction/create.blade.php --}}
@extends('backends.master')
@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-dark font-weight-bold">{{ __('Create Transaction') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Input Section - 8 columns -->
                <div class="col-md-8">
                    <form id="createForm" method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        <!-- Products Card -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary">
                                <h3 class="card-title text-white mb-0">{{ __('Products') }}</h3>
                            </div>
                            <div class="card-body bg-light">
                                <!-- Hidden product row template -->
                                <template id="product-template">
                                    <div class="product-item row mb-3">
                                        <div class="col-md-5">
                                            <select name="product_id[]" class="form-control product-select"
                                                onchange="calculateTotals()" required>
                                                <option value="">{{__('Please Select')}}</option>
                                                @foreach ($products as $product)
                                                    @if ($product->status == 1)
                                                        <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                                            data-price="{{ $product->price }}">
                                                            {{ $product->name }} - ${{ $product->price }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('product_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="qty[]" class="form-control" placeholder="{{__('Quantity')}}"
                                                oninput="calculateTotals()" required>
                                            @error('qty')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <select name="discount_type[]" class="form-control" onchange="calculateTotals()"
                                                required>
                                                <option value="percent">{{__('Percent')}}</option>
                                                <option value="fix">{{__('Fix')}}</option>
                                            </select>
                                            @error('discount_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="discount[]" class="form-control" placeholder="Discount"
                                                oninput="calculateTotals()" value="0" required>
                                            @error('discount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-sm remove-product">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <!-- Product list container -->
                                <div id="product-list">
                                    <!-- Initial, static product row -->
                                    <div class="product-item row mb-3">
                                        <div class="col-md-5">
                                            <select name="product_id[]" class="form-control product-select"
                                                onchange="calculateTotals()" required>
                                                <option value="">{{__('Please Select')}}</option>
                                                @foreach ($products as $product)
                                                    @if ($product->status == 1)
                                                        <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                                            data-price="{{ $product->price }}">
                                                            {{ $product->name }} - ${{ $product->price }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('product_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="qty[]" class="form-control" placeholder="{{__('Quantity')}}"
                                                oninput="calculateTotals()" required>
                                            @error('qty')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <select name="discount_type[]" class="form-control" onchange="calculateTotals()"
                                                required>
                                                <option value="percent">{{__('Percent')}}</option>
                                                <option value="fix">{{__('Fix')}}</option>
                                            </select>
                                            @error('discount_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="discount[]" class="form-control" placeholder="{{__('Discount')}}"
                                                oninput="calculateTotals()" value="0" required>
                                            @error('discount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-sm remove-product">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="add-product" class="btn btn-success mt-2">
                                    <i class="fas fa-plus mr-1"></i> {{__('Add Product')}}
                                </button>
                            </div>
                        </div>
                        <!-- Transaction Details Card -->
                        <div class="card shadow-sm mt-4">
                            <div class="card-header bg-primary">
                                <h3 class="card-title text-white mb-0">{{ __('Transaction Details') }}</h3>
                            </div>
                            <div class="card-body bg-light">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="required_lable" for="customer_id">{{ __('Customer') }}</label>
                                        <div class="input-group">
                                            <select name="customer_id" id="customer_id"
                                                class="form-control select2 @error('customer_id') is-invalid @enderror"
                                                onchange="updateReport()" required>
                                                <option value="">{{ __('N/A') }}</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        data-first-name="{{ $customer->first_name }}"
                                                        data-last-name="{{ $customer->last_name }}"
                                                        data-email="{{ $customer->email }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->first_name . ' ' . $customer->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" data-toggle="modal"
                                                    data-target="#addCustomerModal">
                                                    <i class="fas fa-plus"></i> {{ __('Add New') }}
                                                </button>
                                            </div>
                                        </div>
                                        @error('customer_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_method"
                                                class="font-weight-bold">{{ __('Payment Method') }}</label>
                                            <select name="payment_method" id="payment_method" class="form-control"
                                                onchange="updateReport()" required>
                                                <option value="">{{__('Select Payment Method')}}</option>
                                                <option value="cash">Cash</option>
                                                <option value="credit_card">Credit Card</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="digital_wallet">Digital Wallet</option>
                                            </select>
                                            @error('payment_method')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date"
                                                class="font-weight-bold">{{ __('Start Date') }}</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control"
                                                oninput="updateReport()" required>
                                            @error('start_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date"
                                                class="font-weight-bold">{{ __('End Date') }}</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control"
                                                oninput="updateReport()" required>
                                            @error('end_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sub_total" class="font-weight-bold">{{ __('Sub Total') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" name="sub_total" id="sub_total" class="form-control"
                                                    oninput="updateReport()" readonly required>
                                                @error('sub_total')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="final_total"
                                                class="font-weight-bold">{{ __('Final Total') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" name="final_total" id="final_total" class="form-control"
                                                    oninput="updateReport()" readonly required>
                                                @error('final_total')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> {{__('Save Transaction')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Transaction Report - 4 columns -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success">
                            <h3 class="card-title text-white mb-0">
                                <i class="fas fa-file-alt mr-2"></i>{{__('Transaction Report')}}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <div class="mb-3">
                                        <span class="text-muted">{{__('Invoice Number')}}</span>
                                        <h5 id="report-invoice-number" class="mb-0">INV-{{ strtoupper(uniqid()) }}</h5>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted">{{__('Customer')}}</span>
                                        <h5 id="report-customer" class="mb-0">N/A</h5>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted">{{__('Start Date')}}</span>
                                        <h5 id="report-start-date" class="mb-0">N/A</h5>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted">{{__('End Date')}}</span>
                                        <h5 id="report-end-date" class="mb-0">N/A</h5>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted">{{__('Payment Method')}}</span>
                                        <h5 id="report-payment-method" class="mb-0">N/A</h5>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted">{{__('Sub Total')}}</span>
                                        <h5 class="mb-0">$<span id="report-sub-total">0.00</span></h5>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted">{{__('Final Total')}}</span>
                                        <h5 class="mb-0">$<span id="report-final-total">0.00</span></h5>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3 font-weight-bold">{{__('Products')}}</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{__('Product')}}</th>
                                            <th>{{__('Qty')}}</th>
                                            <th>{{__('Discount')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="report-products"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addCustomerModalLabel">{{__('Create Customer')}}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addCustomerForm" method="POST" action="{{ route('admin.customer.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="redirect_to" value="transaction_create">

                            <!-- First Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name" class="required">{{__('First Name')}}</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name" class="required">{{__('Last Name')}}</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="required">{{__('Email')}}</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="required">{{__('Phone')}}</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                            </div>

                            <!-- Country Dropdown -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country" class="required">{{__('Country')}}</label>
                                    <select name="country" id="country" class="form-control select2 country_code_select">
                                        @foreach($countryNames as $countryCode => $countryName)
                                            <option value="{{ $countryName }}">
                                                {{ $countryName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">{{__('Status')}}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">{{__('Active')}}</option>
                                        <option value="0">{{__('Inactive')}}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Profile Image -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="image">{{__('Image')}}</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" name="image"
                                            accept="image/*">
                                        <label class="custom-file-label" for="image">{{__('Choose file')}}</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary" id="saveCustomer">
                            <i class="fas fa-save mr-1"></i> {{__('Save Customer')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Add new product row when clicking "Add Product"
        document.getElementById('add-product').addEventListener('click', function () {
            let productList = document.getElementById('product-list');
            let template = document.getElementById('product-template').content.cloneNode(true);
            productList.appendChild(template);
        });

        // Update transaction report details
        function updateReport() {
            const customerSelect = document.getElementById('customer_id');
            const customerText = customerSelect.options[customerSelect.selectedIndex]?.text || 'N/A';
            document.getElementById('report-customer').textContent = customerText;

            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const paymentMethod = document.getElementById('payment_method').value;
            const subTotal = document.getElementById('sub_total').value;
            const finalTotal = document.getElementById('final_total').value;

            document.getElementById('report-start-date').textContent = startDate || 'N/A';
            document.getElementById('report-end-date').textContent = endDate || 'N/A';
            document.getElementById('report-payment-method').textContent = paymentMethod || 'N/A';
            document.getElementById('report-sub-total').textContent = subTotal || '0.00';
            document.getElementById('report-final-total').textContent = finalTotal || '0.00';

            const productRows = document.querySelectorAll('.product-item');
            const reportProducts = document.getElementById('report-products');
            reportProducts.innerHTML = '';

            productRows.forEach(product => {
                const productSelect = product.querySelector('.product-select');
                const productName = productSelect.options[productSelect.selectedIndex]?.dataset?.name || 'N/A';
                const qty = product.querySelector('[name="qty[]"]').value;
                const discountType = product.querySelector('[name="discount_type[]"]').value;
                const discount = product.querySelector('[name="discount[]"]').value;

                const row = `<tr>
                                        <td>${productName}</td>
                                        <td>${qty}</td>
                                        <td>${discount} ${discountType === 'percent' ? '%' : '$'}</td>
                                     </tr>`;
                reportProducts.insertAdjacentHTML('beforeend', row);
            });
        }

        // Remove product row with confirmation
        document.getElementById('product-list').addEventListener('click', function (e) {
            const target = e.target;
            const removeButton = target.closest('.remove-product');
            if (removeButton) {
                e.preventDefault();
                Swal.fire({
                    title: '{{ __('Are you sure?') }}',
                    text: "{{ __('You won\'t be able to revert this!') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('Yes, delete it!') }}',
                    cancelButtonText: '{{ __('No, cancel!') }}',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        const productItem = removeButton.closest('.product-item');
                        if (productItem) {
                            productItem.remove();
                            calculateTotals();
                            Swal.fire({
                                title: '{{__('Deleted!')}}',
                                text: '{{__('Product has been removed.')}}',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            }
        });

        $(document).on('focus', 'input[name="discount[]"]', function () {
            $(this).select();
        });

        // Calculate totals for transaction based on product rows
        function calculateTotals() {
            let subTotal = 0;
            let finalTotal = 0;
            const products = document.querySelectorAll('.product-item');

            products.forEach(product => {
                const selectedOption = product.querySelector('.product-select option:checked');
                const basePrice = selectedOption && selectedOption.dataset.price ? parseFloat(selectedOption.dataset.price) : 0;
                const qty = parseFloat(product.querySelector('[name="qty[]"]').value) || 0;
                const discountType = product.querySelector('[name="discount_type[]"]').value;
                const discount = parseFloat(product.querySelector('[name="discount[]"]').value) || 0;

                const productTotal = qty * basePrice;
                let discountAmount = 0;
                if (discountType === 'percent') {
                    discountAmount = (productTotal * discount) / 100;
                } else if (discountType === 'fix') {
                    discountAmount = discount * qty;
                }

                subTotal += productTotal;
                finalTotal += productTotal - discountAmount;
            });

            document.getElementById('sub_total').value = subTotal.toFixed(2);
            document.getElementById('final_total').value = finalTotal.toFixed(2);
            updateReport();
        }

        // Form submission event with product row validation
        document.getElementById('createForm').addEventListener('submit', function (e) {
            // Validate each product row: if a product is selected, quantity must be filled and >0.
            let missingQty = false;
            const productRows = document.querySelectorAll('.product-item');
            productRows.forEach(row => {
                const productSelect = row.querySelector('.product-select');
                const productId = productSelect.value;
                const qtyInput = row.querySelector('[name="qty[]"]');
                const qty = qtyInput ? qtyInput.value : '';
                if (productId && (qty.trim() === '' || parseFloat(qty) <= 0)) {
                    missingQty = true;
                    row.style.border = '1px solid red';
                }
            });
            if (missingQty) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Quantity',
                    text: 'Please fill in the quantity for the selected product or remove the product row.',
                });
                e.preventDefault();
                return;
            }

            // If validation passes, proceed to gather data and submit the form via fetch...
            e.preventDefault();
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            // Retrieve customer information
            const customerSelect = document.getElementById('customer_id');
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            const customerId = parseInt(customerSelect.value) || null;
            const guestInfo = customerId ? [{
                first_name: selectedOption.getAttribute('data-first-name') || 'N/A',
                last_name: selectedOption.getAttribute('data-last-name') || 'N/A',
                email: selectedOption.getAttribute('data-email') || 'N/A'
            }] : [];

            // Gather totals and other fields
            const subTotal = parseFloat(document.getElementById('sub_total').value) || 0;
            const finalTotal = parseFloat(document.getElementById('final_total').value) || 0;
            const startDate = document.getElementById('start_date').value || '';
            const endDate = document.getElementById('end_date').value || '';
            const paymentMethod = document.getElementById('payment_method').value || '';

            // Gather product sell lines
            const sellLines = [];
            document.querySelectorAll('.product-item').forEach(product => {
                const productSelect = product.querySelector('.product-select');
                const productId = productSelect.value;
                if (productId) {
                    const qty = parseFloat(product.querySelector('[name="qty[]"]').value) || 0;
                    const discountType = product.querySelector('[name="discount_type[]"]').value;
                    const discountAmount = parseFloat(product.querySelector('[name="discount[]"]').value) || 0;
                    const selectedOpt = productSelect.options[productSelect.selectedIndex];
                    const basePrice = parseFloat(selectedOpt.dataset.price) || 0;
                    const lineSubTotal = qty * basePrice;
                    let lineFinalTotal = lineSubTotal;
                    if (discountType === 'percent') {
                        lineFinalTotal = lineSubTotal - ((lineSubTotal * discountAmount) / 100);
                    } else if (discountType === 'fix') {
                        lineFinalTotal = lineSubTotal - (discountAmount * qty);
                    }
                    sellLines.push({
                        product_id: parseInt(productId),
                        qty,
                        discount_type: discountType,
                        discount_amount: discountAmount,
                        sub_total: lineSubTotal.toFixed(2),
                        final_total: lineFinalTotal.toFixed(2)
                    });
                }
            });

            const requestData = {
                customer_id: customerId,
                sub_total: subTotal.toFixed(2),
                final_total: finalTotal.toFixed(2),
                payment_status: "unpaid",
                status: "request",
                start_date: startDate,
                end_date: endDate,
                payment_method: paymentMethod,
                guest_info: guestInfo,
                sell_lines: sellLines
            };

            fetch("{{ route('admin.transaction.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(requestData)
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Success', data);
                    if (data.status === 1) {
                        window.location.href = "{{ route('admin.transaction.index') }}" + "?msg=" + encodeURIComponent(data.msg);
                    } else {
                        toastr.error(data.msg);
                        submitButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitButton.disabled = false;
                    toastr.error('An error occurred. Please try again.');
                });
        });
    </script>
@endpush
