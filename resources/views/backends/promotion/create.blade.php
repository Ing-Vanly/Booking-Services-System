@extends('backends.master')
@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('Create Promotion')}}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <form method="POST" action="{{ route('admin.promotion.store',) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 ">
                                        <label class="required_lable">{{__('Title')}}</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                            name="title" placeholder="{{__('Enter Title')}}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required_lable">{{__('Promotion Type')}}</label>
                                        <select name="promotion_type" id="promotion_type" class="form-control @error('promotion_type') is-invalid @enderror">
                                            <option value="category" {{ old('promotion_type') == 'category' ? 'selected' : '' }}>{{__('Category')}}</option>
                                            <option value="product" {{ old('promotion_type') == 'product' ? 'selected' : '' }}>{{__('Product')}}</option>
                                        </select>
                                        @error('promotion_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required_lable">{{__('Discount Type')}}</label>
                                        <select name="discount_type" class="form-control @error('discount_type') is-invalid @enderror">
                                            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>{{__('Percent')}}</option>
                                            <option value="fix" {{ old('discount_type') == 'fix' ? 'selected' : '' }}>{{__('Fix')}}</option>
                                        </select>
                                        @error('discount_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required_lable">{{__('Discount Value')}}</label>
                                        <input type="number" class="form-control @error('discount_value') is-invalid @enderror" name="discount_value" value="{{ old('discount_value') }}" placeholder="{{__('Enter Discount Value')}}">
                                        @error('discount_value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="required_lable">{{__('Description')}}</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" placeholder="{{__('Enter Description')}}">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div id="category_form" class="col-md-12" style="display: none;">
                                        <div class="card card-secondary">
                                            <div class="card-header">
                                                <h3 class="card-title">{{__('Category Promotion Details')}}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <div class="form-group col-md-6">
                                                        <label class="required_lable" for="category">{{__('Category')}}</label>
                                                        <select name="category_id" id="category_id" class="form-control select2 @error('category') is-invalid @enderror">
                                                            <option value="">{{ __('Select category') }}</option>
                                                            @foreach ($category as $id => $name)
                                                                <option value="{{ $id }}" {{ old('product') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('category')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="product_form" class="col-md-12" style="display: none;">
                                        <div class="card card-secondary">
                                            <div class="card-header">
                                                <h3 class="card-title">{{__('Product Promotion Details')}}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <div class="form-group col-md-6">
                                                        <label class="required_lable" for="category">{{__('Product')}}</label>
                                                        <select name="product_id" id="product_id" class="form-control select2 @error('product') is-invalid @enderror">
                                                            <option value="">{{ __('Select category') }}</option>
                                                            @foreach ($product as $id => $name)
                                                                <option value="{{ $id }}" {{ old('category') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('category')
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
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}">
                                        @error('start_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required_lable">{{__('End Date')}}</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date') }}">
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
                                                <input type="file" class="custom-file-input" id="exampleInputFile" name="image">
                                                <label class="custom-file-label" for="exampleInputFile">{{ __('Choose file') }}</label>
                                            </div>
                                        </div>
                                        <div class="preview text-center border rounded mt-2" style="height: 150px">
                                            <img id="preview_img" src="{{ asset('images/no_image_available.jpg') }}" alt="" height="100%">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit"  class="btn btn-primary">{{__('Submit')}}</button>
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
@endpush
