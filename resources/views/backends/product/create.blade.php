@extends('backends.master')
@section('contents')
    <!-- Content Wrapper. Contains page content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('Create Product')}}</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                            @foreach (json_decode($language, true) as $lang)
                                                @if ($lang['status'] == 1)
                                                    <li class="nav-item">
                                                        <a class="nav-link text-capitalize {{ $lang['code'] == $default_lang ? 'active' : '' }}" id="lang_{{ $lang['code'] }}-tab" data-toggle="pill" href="#lang_{{ $lang['code'] }}" data-lang="{{ $lang['code'] }}" role="tab" aria-controls="lang_{{ $lang['code'] }}" aria-selected="false">{{ \App\helpers\AppHelper::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        <div class="tab-content" id="custom-content-below-tabContent">
                                            @foreach (json_decode($language, true) as $lang)
                                                @if ($lang['status'] == 1)
                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3" id="lang_{{ $lang['code'] }}" role="tabpanel" aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                        <div class="form-group">
                                                            <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                                            <label for="name_{{ $lang['code'] }}">{{ __('Name') }}({{ strtoupper($lang['code']) }})</label>
                                                            <input type="name" id="name_{{ $lang['code'] }}" class="form-control @error('name') is-invalid @enderror"
                                                                name="name[]" placeholder="{{__('Enter Name')}}" value="{{ old('name.' . $lang['code']) }}">
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
                                        <select name="category" id="category" class="form-control select2 @error('category') is-invalid @enderror">
                                            <option value="">{{ __('Select category') }}</option>
                                            @foreach ($categories as $id => $name)
                                                <option value="{{ $id }}" {{ old('category') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> 

                                    

                                    

                                    <!-- Price -->
                                    <div class="form-group col-md-6">
                                        <label class="required_lable" for="price">{{__('Price')}}</label>
                                        <input type="text" name="price" id="price" class="form-control @error('price') is-invalid @enderror" step="any" value="{{ old('price') }}">
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Discount Type -->
                                    <div class="form-group col-md-6">
                                        <label for="discount_type">{{__('Discount Type')}}</label>
                                        <select name="discount_type" id="discount_type" class="form-control">
                                            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>{{ __('Percent') }}</option>
                                            <option value="fix" {{ old('discount_type') == 'fix' ? 'selected' : '' }}>{{ __('Fix') }}</option>
                                        </select>
                                        @error('discount_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Discount -->
                                    <div class="form-group col-md-6">
                                        <label for="discount">{{__('Discount')}}</label>
                                        <input type="text" name="discount" id="discount" class="form-control" value="{{ old('discount') }}">
                                        @error('discount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Event ID -->
                                    <div class="form-group col-md-6">
                                        <label for="event_id">{{__('Event ID')}}</label>
                                        <input type="text" name="event_id" id="event_id" class="form-control @error('event_id') is-invalid @enderror" value="{{ old('event_id') }}">
                                        @error('event_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Brand Category -->
                                    <div class="form-group col-md-6">
                                        <label class="required_lable" for="brandCategory">{{__('Model')}}</label>
                                        <select name="brandCategory" id="brandCategory" class="form-control select2 @error('brandCategory') is-invalid @enderror">
                                            <option value="">{{ __('Select model') }}</option>
                                            @foreach ($brandCategories as $id => $name)
                                                <option value="{{ $id }}" {{ old('brandCategory') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('brandCategory')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Number Available -->
                                    <div class="form-group col-md-6">
                                        <label for="number_available">{{__('Number Available')}}</label>
                                        <input type="number" name="number_available" id="number_available" class="form-control @error('number_available') is-invalid @enderror" value="{{ old('number_available') }}">
                                        @error('number_available')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="form-group col-md-6">
                                        <label for="description">{{__('Description')}}</label>
                                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Partner ID -->
                                    <div class="form-group col-md-6">
                                        <label for="partner_id">{{__('Partner ID')}}</label>
                                        <input type="text" name="partner_id" id="partner_id" class="form-control @error('partner_id') is-invalid @enderror" value="{{ old('partner_id') }}">
                                        @error('partner_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div class="form-group col-md-6">
                                        <label for="status">{{__('Status')}}</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="0">{{ __('Inactive') }}</option>
                                        </select>
                                        @error('status')
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
                                                <input type="file" class="custom-file-input" id="exampleInputFile" name="image">
                                                <label class="custom-file-label" for="exampleInputFile">{{ __('Choose file') }}</label>
                                            </div>
                                        </div>
                                        <div class="preview text-center border rounded mt-2" style="height: 150px">
                                            <img src="{{ asset('uploads/image/default.png') }}" alt="" height="100%">
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
        </div><!-- /.container-fluid -->
    </section>
@endsection

@push('js')
    <script>
        $('.custom-file-input').change(function (e) {
            var reader = new FileReader();
            var preview = $(this).closest('.form-group').find('.preview img');
            reader.onload = function(e) {
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
    </script>
@endpush
