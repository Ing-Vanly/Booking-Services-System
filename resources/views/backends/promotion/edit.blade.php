@extends('backends.master')
@section('contents')
    <!-- Content Wrapper. Contains page content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('Edit Promotion')}}</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <form action="{{ route('admin.promotion.update', $promotion->id) }}" class="submit-form"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="required_label">{{ __('Title') }}</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" value="{{ old('title', $promotion->title) }}"
                                            placeholder="{{ __('Enter Title') }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required_lable">{{__('Promotion Type')}}</label>
                                        <select name="promotion_type" id="promotion_type" class="form-control @error('promotion_type') is-invalid @enderror">
                                            <option value="category" {{ old('promotion_type', $promotion->promotion_type) == 'category' ? 'selected' : '' }}>
                                                {{__('Category')}}
                                            </option>
                                            <option value="product" {{ old('promotion_type', $promotion->promotion_type) == 'product' ? 'selected' : '' }}>
                                                {{__('Product')}}
                                            </option>
                                        </select>
                                        @error('promotion_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required_lable">{{__('Discount Type')}}</label>
                                        <select name="discount_type"
                                            class="form-control @error('discount_type') is-invalid @enderror">
                                            <option value="percent" {{ old('discount_type', $promotion->discount_type) == 'percent' ? 'selected' : '' }}>{{__('Percent')}}
                                            </option>
                                            <option value="fix" {{ old('discount_type', $promotion->discount_type) == 'fix' ? 'selected' : '' }}>{{__('Fix')}}</option>
                                        </select>
                                        @error('discount_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required_lable">{{__('Discount Value')}}</label>
                                        <input type="number"
                                            class="form-control @error('discount_value') is-invalid @enderror"
                                            name="discount_value"
                                            value="{{ old('discount_value', $promotion->discount_value) }}"
                                            placeholder="{{__('Enter Discount Value')}}">
                                        @error('discount_value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="required_lable">{{__('Description')}}</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" placeholder="{{__('Enter Description')}}">{{ old('description', $promotion->description) }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div id="product_form" class="col-md-12" style="display: none;">
                                        <div class="card card-secondary">
                                            <div class="card-header">
                                                <h3 class="card-title">{{__('Product Promotion Details')}}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <div class="form-group col-md-6">
                                                        <label class="required_lable" for="product_id">{{__('Product')}}</label>
                                                        <select name="product_id" id="product_id"
                                                            class="form-control select2 @error('product_id') is-invalid @enderror">
                                                            <option value="">{{ __('Select product') }}</option>
                                                            @foreach ($products as $id => $name)
                                                                <option value="{{ $id }}" {{ old('product_id', optional($promotion_product)->product_id) == $id ? 'selected' : '' }}>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('product_id')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="category_form" class="col-md-12" style="display: none;">
                                        <div class="card card-secondary">
                                            <div class="card-header">
                                                <h3 class="card-title">{{__('Category Promotion Details')}}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <div class="form-group col-md-6">
                                                        <label class="required_lable" for="category_id">{{__('Category')}}</label>
                                                        <select name="category_id" id="category_id"
                                                            class="form-control select2 @error('category_id') is-invalid @enderror">
                                                            <option value="">{{ __('Select category') }}</option>
                                                            @foreach ($categorys as $id => $name)
                                                                <option value="{{ $id }}" {{ old('category_id', optional($promotion_category)->category_id) == $id ? 'selected' : '' }}>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('category_id')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required_lable">{{__('Start Date')}}</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                            name="start_date" value="{{ old('start_date', $promotion->start_date) }}">
                                        @error('start_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required_lable">{{__('End Date')}}</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                            name="end_date" value="{{ old('end_date', $promotion->end_date) }}">
                                        @error('end_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="exampleInputFile">{{__('Image')}}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="exampleInputFile"
                                                    name="image">
                                                <label class="custom-file-label"
                                                    for="exampleInputFile">{{ $promotion->image ?? __('Choose file') }}</label>
                                            </div>
                                        </div>
                                        <div class="preview text-center border rounded mt-2" style="height: 150px">
                                            <img id="preview_img" src="
                                                @if ($promotion->image && file_exists(public_path('uploads/promotions/' . $promotion->image)))
                                                    {{ asset('uploads/promotions/' . $promotion->image) }}
                                                @else
                                                    {{ asset('images/no_image_available.jpg') }}
                                                @endif
                                                " alt="" height="100%">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            function toggleForms() {
                var selectedType = $('#promotion_type').val();
                $('#category_form').toggle(selectedType === 'category');
                $('#product_form').toggle(selectedType === 'product');
            }

                toggleForms();
        $('#promotion_type').change(toggleForms);

        $('.custom-file-input').change(function (e) {
                    var reader = new FileReader();
        reader.onload = function(e) {
            $('#preview_img').attr('src', e.target.result).show();
                    }
        reader.readAsDataURL(this.files[0]);
                });
            });
    </script>
    <script>
            $('.custom-file-input').change(function (e) {
                        var reader = new FileReader();
            var preview = $(this).closest('.form-group').find('.preview img');
            reader.onload = function (e) {
                preview.attr('src', e.target.result).show();
                        }
            reader.readAsDataURL(this.files[0]);
                    });

            $(document).on('click', '.nav-tabs .nav-link', function(e) {
                        if ($(this).data('lang') != 'en') {
                $('.no_translate_wrapper').addClass('d-none');
                        } else {
                $('.no_translate_wrapper').removeClass('d-none');
                        }
                    });
    </script>
    {{-- <script>
        $(document).ready(function () {
            $('#promotion_type').prop('disabled', true); // Disables the select field
        });
    </script> --}}
    <script>
            $(document).ready(function () {
                function toggleForms() {
                    var selectedType = $('#promotion_type').val();
                    $('#category_form').toggle(selectedType === 'category');
                    $('#product_form').toggle(selectedType === 'product');
                }

                toggleForms();
            $('#promotion_type').change(toggleForms);

            $('.custom-file-input').change(function (e) {
                    var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_img').attr('src', e.target.result).show();
                    }
            reader.readAsDataURL(this.files[0]);
                });
            });
    </script>
@endpush
