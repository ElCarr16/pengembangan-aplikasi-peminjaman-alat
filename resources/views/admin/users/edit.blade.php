@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <!-- Breadcrumb / Back Link -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item small"><a href="{{ route('admin.users.index') }}"
                            class="text-decoration-none">Manajemen User</a></li>
                    <li class="breadcrumb-item small active" aria-current="page">Edit User</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-person-gear fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Edit Pengguna</h5>
                            <p class="text-muted small mb-0">ID: #USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 pt-md-4">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h6 class="text-uppercase small fw-bold text-muted mb-4 pb-2 border-bottom">Informasi Dasar</h6>

                        <!-- Nama Lengkap -->
                        <div class="form-floating mb-3">
                            <input type="text" name="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror" id="editName"
                                placeholder="Nama Lengkap" value="{{ old('name', $user->name) }}" required>
                            <label for="editName">Nama Lengkap</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="form-floating mb-3">
                            <input type="email" name="email"
                                class="form-control rounded-3 @error('email') is-invalid @enderror" id="editEmail"
                                placeholder="name@example.com" value="{{ old('email', $user->email) }}" required>
                            <label for="editEmail">Alamat Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role Selection -->
                        <div class="form-floating mb-4">
                            <select name="role" class="form-select rounded-3" id="editRole" required>
                                <option value="peminjam" {{ $user->role == 'peminjam' ? 'selected' : '' }}>Peminjam (Akses
                                    Dasar)</option>
                                <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas (Kelola
                                    Inventaris)</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator (Akses
                                    Penuh)</option>
                            </select>
                            <label for="editRole">Level Akses / Role</label>
                        </div>

                        <h6 class="text-uppercase small fw-bold text-muted mb-4 pb-2 border-bottom mt-5">Keamanan Akun</h6>

                        <!-- Password Baru -->
                        <div class="form-floating mb-2">
                            <input type="password" name="password"
                                class="form-control rounded-3 @error('password') is-invalid @enderror" id="editPassword"
                                placeholder="Password Baru" minlength="6">
                            <label for="editPassword">Password Baru</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <p class="text-muted small mb-4">
                            <i class="bi bi-info-circle me-1"></i> Kosongkan jika tidak ingin mengganti password.
                        </p>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column flex-md-row gap-2 mt-5">
                            <button type="submit"
                                class="btn btn-primary btn-lg rounded-pill px-5 fw-bold order-md-2 flex-grow-1 flex-md-grow-0">
                                Simpan Perubahan
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
        .bg-warning-subtle {
            background-color: #fff9e6 !important;
        }

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
