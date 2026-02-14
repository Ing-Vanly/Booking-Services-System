@extends('backends.master')
@section('contents')
    <!-- Content Wrapper. Contains page content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Create Customer') }}</h1>
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
                    <form method="POST" action="{{ route('admin.customer.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- general form elements -->
                        <div class="card no_translate_wrapper">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('General Info') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- First name -->
                                    <div class="form-group col-md-6">
                                        <label for="first_name">{{ __('First Name') }}</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Last name -->
                                    <div class="form-group col-md-6">
                                        <label for="last_name">{{ __('Last Name') }}</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control"
                                            value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div class="form-group col-md-6">
                                        <label for="phone">{{ __('Phone') }}</label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group col-md-6">
                                        <label for="email">{{ __('Email') }}</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                            value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="password">{{ __('Password') }}</label>
                                        <input type="text" name="password" id="password" class="form-control"
                                            value="{{ old('password') }}" required>
                                        @error('pasword')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Image -->
                                    <div class="form-group col-md-6">
                                        <label for="image">{{ __('Image') }}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="image"
                                                    name="image">
                                                <label class="custom-file-label"
                                                    for="image">{{ __('Choose file') }}</label>
                                            </div>
                                        </div>
                                        <div class="preview text-center border rounded mt-2" style="height: 150px">
                                            <img src="{{ asset('images/no_image_available.jpg') }}" alt="" height="100%">
                                        </div>
                                    </div>

                                    <!-- Country Dropdown -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country" class="required">{{__('Country')}}</label>
                                            <select name="country" id="country"
                                                class="form-control select2 country_code_select">
                                                @foreach ($countryNames as $countryCode => $countryName)
                                                    <option value="{{ $countryName }}">
                                                        {{ $countryName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group">
                                <button type="submit" class="btn btn-primary float-right">
                                    <i class="fa fa-save"></i>
                                    {{ __('Save') }}
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
        $('.custom-file-input').change(function(e) {
            var reader = new FileReader();
            var preview = $(this).closest('.form-group').find('.preview img');
            reader.onload = function(e) {
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
@endpush
