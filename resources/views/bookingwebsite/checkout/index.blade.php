@extends('bookingwebsite.master')

@section('content')
    <style>
        .timeline {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .timeline::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 4px;
            background: #007bff;
            top: 50%;
            z-index: 0;
        }

        .timeline-step {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            line-height: 40px;
            background: #007bff;
            color: #fff;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 10px;
        }

        .done {
            background: #28a745;
            color: #fff;
        }

        .processing {
            background: #007bff;
            color: #fff;
        }

        .not-done {
            background: #6c757d;
            color: #fff;
        }

        @media (max-width: 768px) {
            .timeline {
                flex-direction: column;
            }

            .timeline::before {
                display: none;
            }
        }

        .card {
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card h5 {
            color: #007bff;
        }

        .custom-btn {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .custom-btn:hover {
            background-color: #0069d9;
            /* Slightly darker blue */
            transform: scale(1.05);
            /* Slightly enlarge on hover */
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .custom-btn:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(38, 143, 255, 0.8);
            /* Blue glow on focus */
        }
    </style>

    <div class="container space-4">
        <div class="card  mb-3 p-4">
            <div class="timeline">
                <div class="timeline-step">
                    <div class="timeline-icon done">✔</div>
                    <p>{{ __('Your Selection') }}</p>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon processing">2</div>
                    <p>{{ __('Your Details') }}</p>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon not-done">3</div>
                    <p>{{ __('Finish Booking') }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card p-4">
                    <h5 class="font-weight-bold">{{ $company_name }}</h5>
                    <p>{{ __('Phone') }}:{{ $phone }}</p>
                    <p>{{ __('Email') }}:{{ $email }}</p>
                    <p>{{ __('Company Address') }}: {{ $company_address }}</p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card p-4 mb-4">
                    <div class="d-flex align-items-center">
                        <!-- Check if the customer has an image -->
                        <img src="{{ Auth::guard('customer')->user()->image ? asset('uploads/customers/' . Auth::guard('customer')->user()->image) : asset('uploads/customers/default-profile.png') }}"
                            alt="Profile Picture" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                        <div>
                            <h5 class="mb-0">{{ __('You are signed in') }}</h5>
                            <strong>{{ __('Email') }}:</strong> {{ Auth::guard('customer')->user()->email }}
                        </div>
                    </div>
                </div>

                <form id="customer-form" action="{{ route('customer.update', Auth::guard('customer')->user()->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card p-4">
                        <h5 class="font-weight-bold">{{ __('Enter Your Details') }}</h5>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <p>{{ __('First Name') }}:</p>
                                    <input type="text" name="first_name" id="first_name" class="form-control"
                                        value="{{ Auth::guard('customer')->user()->first_name }}">
                                </div>
                                <div class="mb-3">
                                    <p>{{ __('Phone') }}</p>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        value="{{ Auth::guard('customer')->user()->phone }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <p>{{ __('Last Name') }}:</p>
                                    <input type="text" name="last_name" id="last_name" class="form-control"
                                        value="{{ Auth::guard('customer')->user()->last_name }}">
                                </div>
                                <div class="mb-3">
                                    <p>{{ __('Country') }}</p>
                                    <input type="text" name="country" id="country" class="form-control"
                                        value="{{ Auth::guard('customer')->user()->country }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <p>{{ __('Email') }}:</p>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ Auth::guard('customer')->user()->email }}">
                        </div>
                    </div>
                </form>

                <div class="card p-4 mt-4">
                    <h5 class="font-weight-bold">{{ __('Your Booking Details') }}</h5>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Product') }}</th>
                                <th>{{ __('Qty') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Total Days') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($cartsSelected->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">No selected carts found.</td>
                                </tr>
                            @else
                                @foreach ($cartsSelected as $cart)
                                    <tr>
                                        <td>{{ $cart->start_date }}</td>
                                        <td>{{ $cart->end_date }}</td>
                                        <td>{{ $cart->product->name }}</td>
                                        <td>{{ $cart->qty }}</td>
                                        <td>{{ $cart->price }}</td>
                                        <td>
                                            @php
                                                $startDate = trim($cart->start_date);
                                                $endDate = trim($cart->end_date);

                                                try {
                                                    $start = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate);
                                                    $end = \Carbon\Carbon::createFromFormat('Y-m-d', $endDate);
                                                    $totalDays = $start->diffInDays($end) + 1;
                                                } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                                                    \Log::error('Invalid date format', [
                                                        'start_date' => $startDate,
                                                        'end_date' => $endDate,
                                                    ]);
                                                    $totalDays = 'Invalid date format';
                                                }
                                            @endphp
                                            {{ $totalDays }} {{ __('days') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <a href="{{ route('cart.index') }}"
                        class="btn btn-primary btn-sm">{{ __('Change your selection') }}</a>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button id="next-btn" class="btn btn-primary custom-btn">
                        {{ __('Next: Final Details') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('next-btn').addEventListener('click', function(event) {
            event.preventDefault();

            let formData = new FormData(document.getElementById('customer-form'));

            fetch("{{ route('customer.update', Auth::guard('customer')->user()->id) }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    },
                })
                .then(response => {
                    return response.text(); // Get raw text first
                })
                .then(text => {
                    try {
                        let data = JSON.parse(text); // Try to parse JSON
                        console.log("Server response:", data); // Debugging

                        if (data.success) {
                            const currentUrl = new URL(window.location.href);
                            const selectedCarts = currentUrl.searchParams.get('selected_carts');
                            const selectedCartsArray = selectedCarts ? JSON.parse(decodeURIComponent(
                                selectedCarts)) : [];
                            const updatedSelectedCarts = encodeURIComponent(JSON.stringify(selectedCartsArray));

                            const nextUrl =
                                `{{ route('checkout.payment') }}?selected_carts=${updatedSelectedCarts}`;
                            window.location.href = nextUrl;
                        } else {
                            alert(data.msg || 'Failed to update customer details.');
                        }
                    } catch (error) {
                        console.error("Invalid JSON response:", text);
                        alert("An error occurred while updating customer details. Check console for details.");
                    }
                })
                .catch(error => {
                    console.error("Error updating customer:", error);
                    alert("An error occurred while updating customer details.");
                });
        });
    </script>
@endsection
