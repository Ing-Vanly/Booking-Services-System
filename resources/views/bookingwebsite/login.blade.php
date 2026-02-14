{{-- 
    @extends('bookingwebsite.master')
    <h2>Customer Login</h2>
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('customer.login') }}" method="post">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form> 
--}}@extends('bookingwebsite.master')

@section('content')
<div id="content" class="site-content text-break" tabindex="-1">
    <div class="container pt-5 pt-xl-8">

<div class="container space-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
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
                        <h2 class="text-center">{{__('Login')}}</h2>
                    </div>
                    <form action="{{ route('customer.login') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">{{__('Email')}}:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{__('Password')}}:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">{{__('Login')}}</button>
                        </div>

                        <p class="mt-3 text-center">{{ __('Dont have an account?') }}  <a href="{{ route('customer.register') }}">{{__('register here')}}</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script> --}}
@endsection