@extends('backends.master')
@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('Create Product')}}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                            @foreach (json_decode($language, true) as $lang)
                                                @if ($lang['status'] == 1)
                                                    <li class="nav-item">
                                                        <a class="nav-link text-capitalize {{ $lang['code'] == $default_lang ? 'active' : '' }}"
                                                            id="lang_{{ $lang['code'] }}-tab" data-toggle="pill"
                                                            href="#lang_{{ $lang['code'] }}" data-lang="{{ $lang['code'] }}"
                                                            role="tab" aria-controls="lang_{{ $lang['code'] }}"
                                                            aria-selected="false">{{ \App\helpers\AppHelper::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        <div class="tab-content" id="custom-content-below-tabContent">
                                            @foreach (json_decode($language, true) as $lang)
                                                @if ($lang['status'] == 1)
                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                        id="lang_{{ $lang['code'] }}" role="tabpanel"
                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                        <div class="form-group">
                                                            <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                                            <label
                                                                for="name_{{ $lang['code'] }}">{{ __('Name') }}({{ strtoupper($lang['code']) }})</label>
                                                            <input type="name" id="name_{{ $lang['code'] }}"
                                                                class="form-control @error('name') is-invalid @enderror"
                                                                name="name[]" placeholder="{{__('Enter Name')}}"
                                                                value="{{ old('name.' . $lang['code']) }}">
                                                            @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('name') }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card no_translate_wrapper">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('General Info') }}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Category -->
                                        <div class="form-group col-md-6">
                                            <label class="required_lable" for="category">{{__('Category')}}</label>
                                            <select name="category" id="category"
                                                class="form-control select2 @error('category') is-invalid @enderror">
                                                <option value="">{{ __('Select category') }}</option>
                                                @foreach ($categories as $id => $name)
                                                    <option value="{{ $id }}" {{ old('category') == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Brand Category -->
                                        <div class="form-group col-md-6">
                                            <label class="required_lable" for="brandCategory">{{__('Model')}}</label>
                                            <select name="brandCategory" id="brandCategory"
                                                class="form-control select2 @error('brandCategory') is-invalid @enderror">
                                                <option value="">{{ __('Select model') }}</option>
                                                @foreach ($brandCategories as $id => $name)
                                                    <option value="{{ $id }}" {{ old('brandCategory') == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('brandCategory')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Price -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sub_total" class="font-weight-bold">{{ __('Sub Total') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="text" name="price" id="price"
                                                        class="form-control @error('price') is-invalid @enderror"
                                                        step="any">
                                                    @error('price')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Discount Type -->
                                        <div class="form-group col-md-3">
                                            <label for="discount_type">{{__('Discount Type')}}</label>
                                            <select name="discount_type" id="discount_type" class="form-control">
                                                <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>
                                                    {{ __('Percent') }}</option>
                                                <option value="fix" {{ old('discount_type') == 'fix' ? 'selected' : '' }}>
                                                    {{ __('Fix') }}</option>
                                            </select>
                                            @error('discount_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Discount -->
                                        <div class="form-group col-md-3">
                                            <label for="discount">{{__('Discount')}}</label>
                                            <input type="text" name="discount" id="discount" class="form-control"
                                                value="{{ old('discount') }}">
                                            @error('discount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Discount Price -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sub_total" class="font-weight-bold">{{ __('Sub Total') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="text" name="discount_price" id="discount_price"
                                                        class="form-control @error('discount_price') is-invalid @enderror"
                                                        step="any" readonly>
                                                    @error('discount_price')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Event ID -->
                                        <div class="form-group col-md-6">
                                            <label for="event_id">{{__('Event ID')}}</label>
                                            <input type="text" name="event_id" id="event_id"
                                                class="form-control @error('event_id') is-invalid @enderror"
                                                value="{{ old('event_id') }}">
                                            @error('event_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Number Available -->
                                        <div class="form-group col-md-6">
                                            <label for="number_available">{{__('Number Available')}}</label>
                                            <input type="number" name="number_available" id="number_available"
                                                class="form-control @error('number_available') is-invalid @enderror"
                                                value="{{ old('number_available') }}">
                                            @error('number_available')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div class="form-group col-md-6">
                                            <label for="description">{{__('Description')}}</label>
                                            <textarea name="description" id="description"
                                                class="form-control @error('description') is-invalid @enderror"
                                                rows="3">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Partner ID -->
                                        <div class="form-group col-md-6">
                                            <label for="partner_id">{{__('Partner ID')}}</label>
                                            <input type="text" name="partner_id" id="partner_id"
                                                class="form-control @error('partner_id') is-invalid @enderror"
                                                value="{{ old('partner_id') }}">
                                            @error('partner_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Image -->
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputFile">{{__('Image')}}</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="exampleInputFile"
                                                        name="image">
                                                    <label class="custom-file-label"
                                                        for="exampleInputFile">{{ __('Choose file') }}</label>
                                                </div>
                                            </div>
                                            <div class="preview text-center border rounded mt-2" style="height: 150px">
                                                <img src="{{ asset('images/no_image_available.jpg') }}" alt="" height="100%">
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="form-group col-md-6">
                                            <label for="description">{{__('Description')}}</label>
                                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="7">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 form-group">
                                <button type="submit" class="btn btn-primary float-right">
                                    <i class="fa fa-save"></i>
                                    {{__('Save')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $('.custom-file-input').change(function (e) {
            var reader = new FileReader();
            var preview = $(this).closest('.form-group').find('.preview img');
            reader.onload = function (e) {
                preview.attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        $(document).on('click', '.nav-tabs .nav-link', function (e) {
            if ($(this).data('lang') != 'en') {
                $('.no_translate_wrapper').addClass('d-none');
            } else {
                $('.no_translate_wrapper').removeClass('d-none');
            }
        });
        $(document).ready(function () {
            // Function to calculate discount price
            function calculateDiscountPrice() {
                const price = parseFloat($('#price').val()) || 0;
                const discountType = $('#discount_type').val();
                const discount = parseFloat($('#discount').val()) || 0;

                let discountPrice = price;

                if (discountType === 'percent') {
                    discountPrice = price - (price * (discount / 100));
                } else if (discountType === 'fix') {
                    discountPrice = price - discount;
                }

                // Update the discount price field
                $('#discount_price').val(discountPrice.toFixed(2));
            }

            // Trigger calculation when price, discount type, or discount changes
            $('#price, #discount_type, #discount').on('input change', function () {
                calculateDiscountPrice();
            });

            // Initial calculation on page load
            calculateDiscountPrice();
        });
    </script>
@endpush