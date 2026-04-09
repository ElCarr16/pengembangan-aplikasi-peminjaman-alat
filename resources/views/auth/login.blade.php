@extends('layouts.app')

@section('content')
    <div class="row align-items-center g-lg-5 py-2">

        <!-- SISI KIRI: HERO TEXT -->
        <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
            <h1 class="display-3 fw-bold lh-1 mb-3 text-dark">
                RENT THE <br>
                <span class="text-primary text-gradient">TOOLS</span>
            </h1>
            <p class="col-lg-10 fs-5 text-muted">
                Solusi cerdas peminjaman alat konstruksi. Kelola inventaris, pantau peminjaman,
                dan tingkatkan efisiensi proyek Anda dalam satu platform terintegrasi.
            </p>
            <div class="d-none d-lg-block mt-4">
                <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                    <i class="bi bi-shield-check me-1"></i> Terverifikasi
                </span>
                <span class="badge rounded-pill bg-info-subtle text-info px-3 py-2 ms-2">
                    <i class="bi bi-speedometer2 me-1"></i> Cepat & Mudah
                </span>
            </div>
        </div>

        <!-- SISI KANAN: LOGIN CARD -->
        <div class="col-md-10 mx-auto col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark">Selamat Datang</h3>
                        <p class="text-muted small">Silakan masuk ke akun Anda</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <!-- Email -->
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control rounded-3" id="floatingInput"
                                placeholder="name@example.com" required>
                            <label for="floatingInput">Alamat Email</label>
                        </div>

                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control rounded-3" id="floatingPassword"
                                placeholder="Password" required>
                            <label for="floatingPassword">Password</label>
                        </div>

                        <!-- Checkbox & Notif -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="allow_notifications" value="1"
                                    id="notifCheck">
                                <label class="form-check-label small text-muted" for="notifCheck">
                                    Izinkan notifikasi
                                </label>
                            </div>
                            <a href="#" class="small text-primary text-decoration-none">Lupa password?</a>
                        </div>

                        <!-- Button Submit -->
                        <button class="w-100 btn btn-lg btn-primary rounded-pill shadow-sm fw-bold py-3" type="submit">
                            Login Sekarang <i class="bi bi-arrow-right ms-2"></i>
                        </button>

                        <hr class="my-4 text-muted opacity-25">

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="small text-muted mb-0">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar
                                    di sini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Gradient text effect */
        .text-gradient {
            background: linear-gradient(45deg, #0d6efd, #00d2ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Input Floating Label Adjustments */
        .form-floating>.form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

        /* Efek hover pada card */
        .card {
            transition: transform 0.3s ease;
        }

        /* Background light transparan untuk badges */
        .bg-primary-subtle {
            background-color: #e7f0ff !important;
        }

        .bg-info-subtle {
            background-color: #e0f7fa !important;
        }
    </style>
@endsection
