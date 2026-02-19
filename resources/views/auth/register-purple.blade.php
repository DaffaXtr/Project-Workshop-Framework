@extends('layouts.login')

@section('title', 'Register')

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
                        <h6 class="font-weight-light">Sign up to continue.</h6>

                        <form method="POST" action="{{ route('register') }}" class="pt-3">
                            @csrf

                            <div class="form-group">
                                <input type="text"
                                        name="name"
                                        :value="old('name')"
                                        id="name"
                                        class="form-control form-control-lg"
                                        placeholder="Name"
                                        required>
                            </div>

                            <div class="form-group">
                                <input type="email"
                                        name="email"
                                        :value="old('email')"
                                        id="email"
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

                            <div class="form-group">
                                <input type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="form-control form-control-lg"
                                        placeholder="Confirm Password"
                                        required>
                            </div>

                            <div class="mt-3 d-grid gap-2">
                                <button type="submit"
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    SIGN UP
                                </button>
                            </div>

                            <div class="my-2 d-flex justify-content-end align-items-end">
                                <a href="{{ route('login') }}" class="auth-link text-primary">
                                    {{ __('Already have an account?') }}</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
