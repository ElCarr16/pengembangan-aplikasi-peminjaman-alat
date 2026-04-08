@extends('layouts.app')

@section('content')
<section class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- HERO TEXT -->
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">
                    RENT THE <br>
                    <span class="text-primary">TOOLS</span>
                </h1>
                <p class="text-muted">
                    Aplikasi peminjaman alat konstruksi berbasis web yang memudahkan
                    manajemen alat, peminjaman, dan pengembalian dengan sistem yang
                    efisien dan terorganisir.
                </p>
            </div>

            <!-- LOGIN CARD -->
            <div class="col-lg-5 offset-lg-1">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">

                        <h4 class="mb-4 text-center">Login Akun</h4>

                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    class="form-control" 
                                    placeholder="Masukkan email"
                                    required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="form-control" 
                                    placeholder="Masukkan password"
                                    required>
                            </div>

                            <!-- Checkbox -->
                            <div class="form-check mb-3">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    name="allow_notifications" 
                                    value="1" 
                                    id="notifCheck">
                                <label class="form-check-label" for="notifCheck">
                                    Izinkan notifikasi
                                </label>
                            </div>

                            <!-- Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                            </div>

                            <!-- Register -->
                            <div class="text-center mt-3">
                                <small>
                                    Belum punya akun? 
                                    <a href="{{ route('register') }}">Daftar di sini</a>
                                </small>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection