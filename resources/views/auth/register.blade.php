@extends('layouts.app')

@section('content')
    <div class="row align-items-center g-lg-5 py-2">

        <!-- SISI KIRI: HERO TEXT -->
        <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
            <h1 class="display-3 fw-bold lh-1 mb-3 text-dark">
                JOIN US <br>
                <span class="text-warning text-gradient">NOW</span>
            </h1>
            <p class="col-lg-10 fs-5 text-muted">
                Daftar sekarang untuk mendapatkan akses ke berbagai alat konstruksi berkualitas.
                Proses verifikasi cepat, transparan, dan memudahkan manajemen proyek Anda.
            </p>
            <div class="mt-4">
                <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-2">
                    <i class="bi bi-check-circle-fill text-warning me-2"></i>
                    <span class="text-muted">Peminjaman Tanpa Ribet</span>
                </div>
                <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-2">
                    <i class="bi bi-check-circle-fill text-warning me-2"></i>
                    <span class="text-muted">Riwayat Terintegrasi</span>
                </div>
            </div>
        </div>

        <!-- SISI KANAN: REGISTER CARD -->
        <div class="col-md-10 mx-auto col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark">Daftar Akun</h3>
                        <p class="text-muted small">Lengkapi data diri Anda di bawah ini</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div class="form-floating mb-3">
                            <input type="text" name="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror" id="regName"
                                placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                            <label for="regName">Nama Lengkap</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-floating mb-3">
                            <input type="email" name="email"
                                class="form-control rounded-3 @error('email') is-invalid @enderror" id="regEmail"
                                placeholder="name@example.com" value="{{ old('email') }}" required>
                            <label for="regEmail">Alamat Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="form-floating mb-3">
                            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                                class="form-control @error('nomor_telepon') is-invalid @enderror" placeholder="0812xxxxxxxx"
                                required>
                            <label>Nomor Telepon (WhatsApp)</label>
                            @error('nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="password" name="password"
                                class="form-control rounded-3 @error('password') is-invalid @enderror" id="regPassword"
                                placeholder="Password" required>
                            <label for="regPassword">Password</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="form-floating mb-4">
                            <input type="password" name="password_confirmation" class="form-control rounded-3"
                                id="regConfirm" placeholder="Ulangi Password" required>
                            <label for="regConfirm">Konfirmasi Password</label>
                        </div>

                        <!-- Button Submit -->
                        <button class="w-100 btn btn-lg btn-warning rounded-pill shadow-sm fw-bold py-3" type="submit">
                            Daftar Akun <i class="bi bi-person-plus ms-2"></i>
                        </button>

                        <hr class="my-4 text-muted opacity-25">

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="small text-muted mb-0">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="text-warning fw-bold text-decoration-none">Login di
                                    sini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Gradient text effect agar senada dengan Login */
        .text-gradient {
            background: linear-gradient(45deg, #0d6efd, #00d2ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Form Styling */
        .form-floating>.form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }
    </style>
@endsection
