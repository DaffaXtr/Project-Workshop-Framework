@extends('layouts.login')

@section('title', 'Login')

@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                        
                        <div class="brand-logo">
                            <img src="{{ asset('assets/images/logo.svg') }}">
                        </div>

                        <h4>Hello! let's get started</h4>
                        <h6 class="font-weight-light">Sign in to continue.</h6>

                        <form method="POST" action="{{ route('login') }}" class="pt-3">
                            @csrf

                            <div class="form-group">
                                <input type="email"
                                       name="email"
                                       class="form-control form-control-lg"
                                       placeholder="Email"
                                       required>
                            </div>

                            <div class="form-group">
                                <input type="password"
                                       name="password"
                                       class="form-control form-control-lg"
                                       placeholder="Password"
                                       required>
                            </div>

                            <div class="mt-3 d-grid gap-2">
                                <button type="submit"
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    SIGN IN
                                </button>
                            </div>

                            <div class="my-2 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                <label class="form-check-label text-muted">
                                    <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="auth-link text-primary">{{ __('Forgot your password?') }}</a>
                                @endif
                            </div>

                            <div class="mb-2 d-grid gap-2">
                                <button type="button" class="btn btn-block btn-google auth-form-btn">
                                <i class="mdi mdi-google me-2"></i>Connect using google </button>
                            </div>

                            <div class="text-center mt-4 font-weight-light"> Don't have an account? 
                                <a href="{{ route('register') }}" class="text-primary">Create</a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
