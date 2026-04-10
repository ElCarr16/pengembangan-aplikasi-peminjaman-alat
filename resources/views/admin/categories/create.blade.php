@extends('layouts.app')

@section('content')
    <!-- Breadcrumb Navigasi -->
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard
                    Admin</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}"
                    class="text-decoration-none">Daftar Kategori</a></li>
            <li class="breadcrumb-item active " aria-current="page">Tambah Category</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-lg-6"> <!-- Dibuat lebih ramping (col-6) karena inputnya sedikit -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-tag fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Buat Kategori Baru</h5>
                            <p class="text-muted small mb-0">Kelompokkan alat-alat Anda dengan kategori baru.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 pt-md-2">
                    <!-- Route diarahkan ke admin.categories.store -->
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">
                            <div class="col-12">
                                <h6 class="text-uppercase small fw-bold text-muted mb-4 pb-2 border-bottom">Data Kategori
                                </h6>

                                <!-- Input Nama Kategori -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="nama_kategori"
                                        class="form-control rounded-3 @error('nama_kategori') is-invalid @enderror"
                                        id="addNamaKategori" placeholder="Nama Kategori" value="{{ old('nama_kategori') }}"
                                        required autofocus>
                                    <label for="addNamaKategori">Nama Kategori</label>
                                    @error('nama_kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mt-2 small text-muted">
                                        Contoh: Elektronik, Alat Tukang, Konsumsi, dsb.
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex flex-column flex-md-row gap-2 mt-4">
                                    <button type="submit"
                                        class="btn btn-success btn-lg rounded-pill px-5 fw-bold order-md-2 flex-grow-1 flex-md-grow-0 shadow-sm">
                                        Simpan Kategori <i class="bi bi-check-circle ms-2"></i>
                                    </button>
                                    <a href="{{ route('admin.categories.index') }}"
                                        class="btn btn-light btn-lg rounded-pill px-5 order-md-1 flex-grow-1 flex-md-grow-0 border">
                                        Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-success-subtle {
            background-color: #d1e7dd !important;
        }

        .form-floating>.form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
        }
    </style>
@endsection
