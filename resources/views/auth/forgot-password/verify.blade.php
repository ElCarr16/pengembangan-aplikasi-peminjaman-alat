@extends('layouts.app')

@section('content')
    <div class="row justify-content-center py-5">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-sm-5 text-center">
                    <i class="bi bi-shield-lock-fill text-primary display-4"></i>
                    <h3 class="fw-bold text-dark mt-3">Verifikasi OTP</h3>
                    <p class="text-muted small">Masukkan 6 digit kode yang kami kirim ke email Anda</p>

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 small py-2">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('password.verify') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <input type="text" name="otp"
                                class="form-control form-control-lg text-center fw-bold rounded-3" placeholder="000000"
                                maxlength="6" style="letter-spacing: 10px; font-size: 2rem;" required autofocus>
                        </div>

                        <button class="w-100 btn btn-lg btn-primary rounded-pill shadow-sm fw-bold py-3 mb-3"
                            type="submit">
                            Verifikasi Kode <i class="bi bi-check2-all ms-2"></i>
                        </button>

                        <p class="text-muted small">
                            Tidak menerima kode?
                            <a href="{{ route('password.request') }}"
                                class="text-primary text-decoration-none fw-bold">Kirim Ulang</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
