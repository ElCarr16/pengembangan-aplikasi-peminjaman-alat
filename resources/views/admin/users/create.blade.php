@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item small"><a href="{{ route('admin.users.index') }}"
                            class="text-decoration-none">Manajemen User</a></li>
                    <li class="breadcrumb-item small active" aria-current="page">Tambah User Baru</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-person-plus fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Buat Akun Baru</h5>
                            <p class="text-muted small mb-0">Silakan lengkapi formulir di bawah ini.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 pt-md-4">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <h6 class="text-uppercase small fw-bold text-muted mb-4 pb-2 border-bottom">Profil Pengguna</h6>

                        <!-- Nama Lengkap -->
                        <div class="form-floating mb-3">
                            <input type="text" name="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror" id="newName"
                                placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                            <label for="newName">Nama Lengkap</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="form-floating mb-3">
                            <input type="email" name="email"
                                class="form-control rounded-3 @error('email') is-invalid @enderror" id="newEmail"
                                placeholder="name@example.com" value="{{ old('email') }}" required>
                            <label for="newEmail">Alamat Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role Selection -->
                        <div class="form-floating mb-4">
                            <select name="role" class="form-select rounded-3 @error('role') is-invalid @enderror"
                                id="newRole" required>
                                <option value="" hidden>Pilih Role...</option>
                                <option value="peminjam" {{ old('role') == 'peminjam' ? 'selected' : '' }}>Peminjam (Akses
                                    Dasar)</option>
                                <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas (Kelola
                                    Inventaris)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (Akses
                                    Penuh)</option>
                            </select>
                            <label for="newRole">Level Akses / Role</label>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h6 class="text-uppercase small fw-bold text-muted mb-4 pb-2 border-bottom mt-5">Kredensial Login
                        </h6>

                        <div class="row g-3">
                            <!-- Password -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" name="password"
                                        class="form-control rounded-3 @error('password') is-invalid @enderror"
                                        id="newPassword" placeholder="Password" required minlength="6">
                                    <label for="newPassword">Password</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" name="password_confirmation" class="form-control rounded-3"
                                        id="confirmPassword" placeholder="Ulangi Password" required>
                                    <label for="confirmPassword">Konfirmasi Password</label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column flex-md-row gap-2 mt-5">
                            <button type="submit"
                                class="btn btn-primary btn-lg rounded-pill px-5 fw-bold order-md-2 flex-grow-1 flex-md-grow-0">
                                Simpan User <i class="bi bi-check-lg ms-2"></i>
                            </button>
                            <a href="{{ route('admin.users.index') }}"
                                class="btn btn-light btn-lg rounded-pill px-5 order-md-1 flex-grow-1 flex-md-grow-0">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-primary-subtle {
            background-color: #e7f0ff !important;
        }

        /* Layout styling konsisten */
        .form-floating>.form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "›";
            font-size: 1.2rem;
            line-height: 1;
            vertical-align: middle;
        }
    </style>
@endsection
