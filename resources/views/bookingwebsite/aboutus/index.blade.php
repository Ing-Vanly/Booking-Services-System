@extends('bookingwebsite.master')

@section('content')
<div class="container mt-5 space-4" style="margin-top: 100px;">
    <!-- Web Banner Logo at the Top -->
    <div class="web-banner-logo-container" style="width: 100%; max-height: 400px; overflow: hidden;">
        <img src="{{ asset('uploads/business_settings/' . $web_banner_logo) }}" 
             alt="Web Banner Logo" 
             class="img-fluid rounded" 
             style="width: 100%; height: auto; object-fit: cover;">
    </div>

    <!-- About Us Content -->
    <h1 class="text-center mb-4 mt-4">{{__('About Us')}}</h1>
    <div class="card mt-4">
        <div class="card-body">
            <h2 class="mb-3">{{__('Company Information')}}</h2>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>{{__('Company Name')}}:</strong> {{ $company_name }}</p>
                    <p><strong>{{__('Phone')}}:</strong> {{ $phone }}</p>
                    <p><strong>{{__('Email')}}:</strong> {{ $email }}</p>
                    <p><strong>{{__('Company Address')}}:</strong> {{ $company_address }}</p>
                </div>
                <div class="col-md-6">
                    {{-- <p><strong>Time Zone:</strong> {{ $timezone }}</p> --}}
                    <p><strong>{{__('Copyright Text')}}:</strong> {{ $copy_right_text }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection