
@extends('bookingwebsite.master')


@section('content')
<div class="container space-3">
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">{{__('Edit Profile')}}</h4>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="text-center mb-4">
                        @if($customer->image)
                            <img src="{{ asset('uploads/customers/' . $customer->image) }}" 
                                 class="rounded-circle border border-3 border-primary shadow-sm" 
                                 width="120" height="120" 
                                 style="object-fit: cover;" 
                                 alt="Profile Image">
                        @else
                            <img src="{{ asset('images/no_image_available.jpg') }}" 
                                 class="rounded-circle border border-3 border-primary shadow-sm" 
                                 width="120" height="120" 
                                 style="object-fit: cover;" 
                                 alt="Default Profile">
                        @endif
                    </div>

                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">{{__('First Name')}}</label>
                                <input type="text" name="first_name" class="form-control" 
                                       value="{{ old('first_name', $customer->first_name) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">{{__('Last Name')}}</label>
                                <input type="text" name="last_name" class="form-control" 
                                       value="{{ old('last_name', $customer->last_name) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{__('Phone')}}</label>
                            <input type="text" name="phone" class="form-control" 
                                   value="{{ old('phone', $customer->phone) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{__('Email')}}</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ old('email', $customer->email) }}" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="exampleInputFile">{{__('Image')}}</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile" name="image">
                                    <label class="custom-file-label" for="exampleInputFile">
                                        {{ $customer->image ?? __('Choose file') }}
                                    </label>
                                </div>
                            </div>
                        
                            <!-- Image Preview Section -->
                            <div class="preview text-center border rounded mt-2" style="width: 150px; height: 150px; overflow: hidden;">
                                <img src="
                                    @if ($customer->image && file_exists(public_path('uploads/customers/' . $customer->image)))
                                        {{ asset('uploads/customers/' . $customer->image) }}
                                    @else
                                        {{ asset('images/no_image_available.jpg') }}
                                    @endif
                                " alt="Preview Image" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> {{__('Save')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> {{__('Back to Home')}}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
