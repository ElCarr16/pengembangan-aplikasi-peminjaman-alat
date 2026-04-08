@extends('layouts.app')

@section('content')
<section class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- HERO -->
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">
                    JOIN US <br>
                    <span class="text-primary">NOW</span>
                </h1>
                <p class="text-muted">
                    Daftar sebagai peminjam untuk meminjam alat konstruksi dengan mudah.
                    Proses cepat, aman, dan terintegrasi dengan sistem manajemen alat.
                </p>
            </div>

            <!-- REGISTER FORM -->
            <div class="col-lg-5 offset-lg-1">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">

                        <h4 class="text-center mb-4">Daftar Akun</h4>

                        <!-- GLOBAL ERROR -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            <!-- Nama -->
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="Masukkan nama lengkap"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="Masukkan email"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password"
                                    required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    class="form-control"
                                    placeholder="Ulangi password"
                                    required>
                            </div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Daftar
                                </button>
                            </div>

                            <!-- Login -->
                            <div class="text-center mt-3">
                                <small>
                                    Sudah punya akun? 
                                    <a href="{{ route('login') }}">Login di sini</a>
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