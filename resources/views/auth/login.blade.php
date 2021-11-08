@extends('layouts.master')

@section('title', 'Vehicle Care | Login')

@section('content')
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    @if (session()->get('error'))
                                        <div class="alert alert-danger">
                                            <ul>
                                                <li>
                                                    <p class="text-xs">{{ session()->get('error') }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                    @if (session()->get('register'))
                                        <div class="alert alert-success">
                                            <p class="text-xs">
                                                {{ 'Successfully Registered! Please wait for approval.' }}</p>
                                        </div>
                                    @endif
                                    <form class="user" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text"
                                                class="form-control form-control-user @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" name="email" id="email"
                                                aria-describedby="emailHelp"
                                                placeholder="Enter Username or Email Address..." required autofocus
                                                autocomplete="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password"
                                                class="form-control form-control-user @error('password') is-invalid @enderror"
                                                id="password" placeholder="Password" required
                                                autocomplete="current-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="form-check-input" id="remember"
                                                    name="remember">
                                                <label class="form-check-label" for="remember">Remember Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            {{ __('Login') }}
                                        </button>
                                        <hr>
                                        <a href="{{ url('/') }}" class="btn btn-info btn-user btn-block">
                                            <i class="fas fa-reply fa-fw"></i> Back to Main Menu
                                        </a>
                                    </form>
                                    <hr>
                                    {{-- @if (Route::has('password.request'))
                    <div class="text-center">
                        <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                    </div>
                    @endif --}}
                                    <div class="text-center">
                                        <a class="small" href="{{ route('register') }}">Register a company!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
