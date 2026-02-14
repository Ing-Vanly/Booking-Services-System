


@extends('bookingwebsite.master')


@section('content')

<div id="content" class="site-content text-break" tabindex="-1">
    <div class="container pt-5 pt-xl-8">
        <div class="row mb-5 mb-lg-8 mt-xl-1">
<div class="container mt-5 space-3">
    <h1 class="text-center mb-4"></h1>
    <h1 class="text-center mb-4"></h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">{{__('Name')}}:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">{{__('Email')}}:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">{{__('Phone')}}:</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">{{__('Message')}}:</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">{{__('Send Message')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection