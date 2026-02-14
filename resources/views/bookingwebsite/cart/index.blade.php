@extends('bookingwebsite.master')
@push('css')
    <style>
        .preview {
            margin-block: 12px;
            text-align: center;
        }

        .tab-pane {
            margin-top: 20px
        }




        
    </style>
@endpush

@section('content')
    <!-- Main content -->
    <section class="content space-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg border rounded-lg">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h3 class="card-title">{{ __('Cart') }}</h3>
                                </div>
                            </div>
                        </div>
                        @include('bookingwebsite.cart.table')
                    </div>
                    <form id="settlementForm" action="{{ route('checkout.index') }}">
                        <div class="d-flex justify-content-end align-items-center mt-3">
                            <div class="grand-total-container me-3">
                                <strong>{{__('Grand Total')}}: $<span id="grandTotal">0.00</span></strong>
                            </div>
                            <input type="hidden" name="selected_carts" id="selected_carts">
                            <button id="freezeButton" class="btn btn-secondary">{{__('Settlement')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade modal_form" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
   
@endsection
@push('js')
    <script>
        $(document).on('click', '.btn-edit', function() {
            $("div.modal_form").load($(this).data('href'), function() {

                $(this).modal('show');

            });
        });

        $('.custom-file-input').change(function(e) {
            var reader = new FileReader();
            var preview = $(this).closest('.form-group').find('.preview img');
            console.log(preview);
            reader.onload = function(e) {
                preview.attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });
    </script>
@endpush
