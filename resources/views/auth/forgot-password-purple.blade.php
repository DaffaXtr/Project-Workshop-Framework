@extends('layouts.login')

@section('title', 'Forgot Password')

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

                        <h4>Forgot Your Password?</h4>
                        <h6 class="font-weight-light">No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</h6>

                        <form method="POST" action="{{ route('password.email') }}" class="pt-3">
                            @csrf

                            <div class="form-group">
                                <input type="email"
                                       name="email"
                                       class="form-control form-control-lg"
                                       placeholder="Email"
                                       required>
                            </div>

                            <div class="mt-3 d-grid gap-2">
                                <button type="submit"
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    RESET PASSWORD
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection