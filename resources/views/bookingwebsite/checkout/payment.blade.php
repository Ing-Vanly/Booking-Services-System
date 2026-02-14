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

            .card-body {
                padding: 15px;
            }

            textarea {
                width: 100%;
            }

            .table td,
            .table th {
                font-size: 14px;
            }
        }

        .card {
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card h5 {
            color: #007bff;
        }

        .table th,
        .table td {
            padding: 10px;
            font-size: 16px;
        }

        .form-check-input {
            margin-right: 10px;
        }

        .form-check-label {
            font-size: 14px;
        }
    </style>

    <div class="container space-4">
        <div class="card mb-3 p-4">
            <div class="timeline">
                <div class="timeline-step">
                    <div class="timeline-icon done">✔</div>
                    <p>{{__('Your Selection')}}</p>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon done">✔</div>
                    <p>{{__('Your Details')}}</p>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon processing">3</div>
                    <p>{{__('Finish Booking')}}</p>
                </div>
            </div>
        </div>

        <form id="checkout-form" action="{{ route('checkout.completeBooking') }}" method="POST">
            @csrf
            <input type="hidden" id="selected_carts" name="selected_carts" value="{{ request()->query('selected_carts') }}">
        
            <div class="row">
                <div class="col-md-8 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="font-weight-bold">{{__('Additional Info')}}</h5>
                            <textarea class="form-control w-100" id="additional" name="additional" rows="5"></textarea>
                        </div>
                    </div>
        
                    <div class="card p-4 mt-4">
                        <h5>{{__('Select Payment Method')}}</h5>
                        <div class="card mb-2 p-2">
                            <div class="d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="cash" value="cash" required>
                                </div>
                                <img src="{{ asset('uploads/cash.webp') }}" alt="Payment Method" class="me-3" style="width: 50px; height: 50px;">
                                <label class="form-check-label" for="cash">Cash</label>
                            </div>
                        </div>
        
                        <div class="card mb-2 p-2">
                            <div class="d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="creditcard" value="credit_card" required>
                                </div>
                                <img src="{{ asset('uploads/creditcard.jpg') }}" alt="Payment Method" class="me-3" style="width: 50px; height: 50px;">
                                <label class="form-check-label" for="creditcard">Credit Card</label>
                            </div>
                        </div>
        
                        <div class="card mb-2 p-2">
                            <div class="d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="banktransfer" value="bank_transfer" required>
                                </div>
                                <img src="{{ asset('uploads/khoq.png') }}" alt="Payment Method" class="me-3" style="width: 50px; height: 50px;">
                                <label class="form-check-label" for="banktransfer">Bank Transfer</label>
                            </div>
                        </div>
        
                        <div class="card mb-2 p-2">
                            <div class="d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="digital_wallet" value="digitalwallet" required>
                                </div>
                                <img src="{{ asset('uploads/digitalwallet.png') }}" alt="Payment Method" class="me-3" style="width: 50px; height: 50px;">
                                <label class="form-check-label" for="digitalwallet">Digital Wallet</label>
                            </div>
                        </div>
        
                        <div class="m-3">
                            <input type="checkbox" class="form-check-input" name="policy" id="policy" required>
                            <label for="policy">{{__('I have read and agree to the terms and conditions')}}</label>
                        </div>
                    </div>
                </div>
        
                <div class="col-md-4 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="font-weight-bold mb-3">{{__('Your Summary')}}</h5>
                            <table class="table">
                                <tr>
                                    <th>{{__('Discount')}}:</th>
                                    <td class="font-weight-bold">{{ $discount }}$</td>
                                </tr>
                                <tr>
                                    <th>{{__('Sub Total')}}:</th>
                                    <td class="font-weight-bold">{{ $sub_total }}$</td>
                                </tr>
                                <tr>
                                    <th>{{__('Grand Total')}}:</th>
                                    <td class="font-weight-bold">{{ $grand_total }}$</td>
                                </tr>
                            </table>
        
                            <button type="submit" class="btn btn-success w-100">{{__('Complete Booking')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>        
    </div>
@endsection
