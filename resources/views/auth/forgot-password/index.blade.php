@extends('layouts.app')

@section('content')
    <div class="row align-items-center g-lg-5 py-5">
        <!-- SISI KIRI: HERO TEXT -->
        <div class="col-lg-7 text-center text-lg-start">
            <h1 class="display-4 fw-bold lh-1 mb-3 text-dark">
                LUPA <span class="text-warning">PASSWORD?</span>
            </h1>
            <p class="col-lg-10 fs-5 text-muted">
                Jangan khawatir! Masukkan alamat email yang terdaftar pada akun Anda.
                Kami akan membantu Anda memulihkan akses ke platform <strong>RENT THE TOOLS</strong> dengan aman.
            </p>
        </div>

        <!-- SISI KANAN: FORM CARD -->
        <div class="col-md-10 mx-auto col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark">Cari Akun</h3>
                        <p class="text-muted small">Masukkan email untuk memulihkan akun</p>
                    </div>

                    <form action="{{ route('password.check') }}" method="POST">
                        @csrf
                        <!-- Input Email -->
                        <div class="form-floating mb-3">
                            <input type="email" name="email"
                                class="form-control rounded-3 @error('email') is-invalid @enderror" id="floatingEmail"
                                placeholder="name@example.com" value="{{ old('email') }}" required>
                            <label for="floatingEmail">Alamat Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Button Submit -->
                        <button class="w-100 btn btn-lg btn-warning rounded-pill shadow-sm fw-bold py-3 mb-3"
                            type="submit">
                            Cari Akun <i class="bi bi-search ms-2"></i>
                        </button>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
