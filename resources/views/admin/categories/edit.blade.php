@extends('layouts.app')

@section('content')
    <!-- Breadcrumb Navigasi -->
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item small"><a href="{{ route('admin.categories.index') }}"
                    class="text-decoration-none">Daftar Kategori</a></li>
            <li class="breadcrumb-item small active" aria-current="page">Edit Kategori</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-tags fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Edit Kategori</h5>
                            <p class="text-muted small mb-0">Perbarui nama kategori klasifikasi alat.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 pt-md-3">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Kategori -->
                        <div class="form-floating mb-4">
                            <input type="text" name="nama_kategori"
                                class="form-control rounded-3 @error('nama_kategori') is-invalid @enderror"
                                id="editNamaKategori" placeholder="Nama Kategori"
                                value="{{ old('nama_kategori', $category->nama_kategori) }}" required autofocus>
                            <label for="editNamaKategori">Nama Kategori</label>
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column flex-md-row gap-2 mt-4">
                            <button type="submit"
                                class="btn btn-warning btn-lg rounded-pill px-5 fw-bold order-md-2 flex-grow-1 flex-md-grow-0 shadow-sm">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.categories.index') }}"
                                class="btn btn-light btn-lg rounded-pill px-5 order-md-1 flex-grow-1 flex-md-grow-0 border">
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

        /* Fokus input disesuaikan dengan warna tema warning/edit */
        .form-floating>.form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.1);
        }

    </style>
@endsection
