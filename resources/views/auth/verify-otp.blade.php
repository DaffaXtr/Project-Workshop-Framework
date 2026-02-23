@extends('layouts.login')

@section('title', 'Verify OTP')

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

                        <h4>Verifikasi Kode OTP</h4>
                        <h6 class="font-weight-light">
                            Masukkan 6 digit kode OTP yang telah dikirim ke email Anda.
                        </h6>

                        <form method="POST" action="{{ route('otp.verify') }}" class="pt-4">
                            @csrf

                            <!-- Hidden input untuk kirim OTP -->
                            <input type="hidden" name="otp" id="otp">

                            <div class="d-flex justify-content-between mb-3">
                                @for ($i = 0; $i < 6; $i++)
                                    <input type="text"
                                           maxlength="1"
                                           class="form-control text-center otp-input"
                                           style="width: 50px; height: 55px; font-size: 20px; padding: 0;"
                                           required>
                                @endfor
                            </div>

                            @error('otp')
                                <div class="text-danger mb-2 text-center">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="mt-4 d-grid gap-2">
                                <button type="submit"
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    VERIFIKASI OTP
                                </button>
                            </div>

                            <div class="text-center mt-4 font-weight-light">
                                Tidak menerima kode?
                                <a href="#" class="text-primary">Kirim ulang</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script OTP -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputs = document.querySelectorAll(".otp-input");
        const hiddenInput = document.getElementById("otp");

        inputs.forEach((input, index) => {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^0-9]/g, "");

                if (this.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                updateHiddenInput();
            });

            input.addEventListener("keydown", function (e) {
                if (e.key === "Backspace" && !this.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        function updateHiddenInput() {
            let otp = "";
            inputs.forEach(input => otp += input.value);
            hiddenInput.value = otp;
        }
    });
</script>
@endsection