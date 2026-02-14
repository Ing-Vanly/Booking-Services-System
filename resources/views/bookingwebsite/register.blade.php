@extends('bookingwebsite.master')

@section('content')

<div id="content" class="site-content text-break" tabindex="-1">
    <div class="container pt-5 pt-xl-8 " >
<div class="container mt-5">
    <div class="d-flex justify-content-center mt-5">
       
    </div>
    <div class="d-flex justify-content-center mt-5">
       
    </div>
</div>
<div class="container space-2">
    
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
              
               
              
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card-header bg-primary text-white">
                        <h2 class="text-center">{{__('Customer Registration')}}</h2>
                    </div>
                    <form action="{{ route('customer.register') }}" method="post">
                        @csrf
                        
                        <!-- First Name -->
                        <div class="mb-3">
                            <label for="first_name" class="form-label">{{__('First Name')}}:</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                        </div>

                        <!-- Last Name -->
                        <div class="mb-3">
                            <label for="last_name" class="form-label">{{__('Last Name')}}:</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">{{__('Phone')}}:</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{__('Email')}}:</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <!-- Country -->
                        <div class="mb-3">
                            <label for="country" class="form-label">{{__('Country')}}:</label>
                            <input type="text" class="form-control" id="country" name="country" value="{{ old('country') }}" required>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{__('Password')}}:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{__('Confirm Password')}}:</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">{{__('Register')}}</button>
                        </div>
                    </form>

                    <p class="mt-3 text-center">{{__('Already have an account?')}} <a href="{{ route('customer.login') }}">{{__('Login here')}}</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script> --}}
@endsection
