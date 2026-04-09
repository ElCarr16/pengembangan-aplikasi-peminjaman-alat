@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item small"><a href="{{ route('admin.tools.index') }}"
                            class="text-decoration-none">Inventaris</a></li>
                    <li class="breadcrumb-item small active" aria-current="page">Edit Alat</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-pencil-square fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Edit Detail Alat</h5>
                            <p class="text-muted small mb-0">Perbarui informasi stok atau deskripsi item.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 pt-md-2">
                    <form action="{{ route('admin.tools.update', $tool->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Preview & Upload Gambar -->
                            <div class="col-md-4 order-md-2">
                                <label class="form-label fw-bold small text-uppercase text-muted">Visual Alat</label>
                                <div class="mb-3">
                                    <div class="rounded-4 border shadow-sm p-2 bg-light text-center">
                                        @if ($tool->gambar)
                                            <img src="{{ asset('storage/' . $tool->gambar) }}" alt="Preview"
                                                class="img-fluid rounded-3 mb-2" id="image-preview"
                                                style="max-height: 200px; object-fit: cover;">
                                        @else
                                            <div class="py-5 text-muted small" id="image-placeholder">
                                                <i class="bi bi-image fs-1 d-block mb-2"></i>
                                                Belum ada gambar
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="input-group input-group-sm">
                                    <input type="file" name="gambar"
                                        class="form-control rounded-3 @error('gambar') is-invalid @enderror"
                                        id="inputGambar" accept="image/*" onchange="previewImg()">
                                </div>
                                <small class="text-muted mt-2 d-block" style="font-size: 0.75rem;">
                                    <i class="bi bi-info-circle me-1"></i> Format: JPG, PNG, WEBP. Maks 2MB.
                                </small>
                                @error('gambar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Detail -->
                            <div class="col-md-8 order-md-1">
                                <h6 class="text-uppercase small fw-bold text-muted mb-4 pb-2 border-bottom">Informasi Utama
                                </h6>

                                <!-- Nama Alat -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="nama_alat"
                                        class="form-control rounded-3 @error('nama_alat') is-invalid @enderror"
                                        id="editNama" placeholder="Nama Alat"
                                        value="{{ old('nama_alat', $tool->nama_alat) }}" required>
                                    <label for="editNama">Nama Alat</label>
                                    @error('nama_alat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3 mb-3">
                                    <!-- Kategori -->
                                    <div class="col-md-7">
                                        <div class="form-floating">
                                            <select name="category_id"
                                                class="form-select rounded-3 @error('category_id') is-invalid @enderror"
                                                id="editCat" required>
                                                <option value="">Pilih Kategori...</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        {{ old('category_id', $tool->category_id) == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="editCat">Kategori</label>
                                        </div>
                                    </div>
                                    <!-- Stok -->
                                    <div class="col-md-5">
                                        <div class="form-floating">
                                            <input type="number" name="stok"
                                                class="form-control rounded-3 @error('stok') is-invalid @enderror"
                                                id="editStok" placeholder="Stok" value="{{ old('stok', $tool->stok) }}"
                                                min="0" required>
                                            <label for="editStok">Jumlah Stok</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="form-group mb-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase"
                                        for="editDesc">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control rounded-3" id="editDesc" rows="4"
                                        placeholder="Jelaskan kondisi atau spesifikasi alat...">{{ old('deskripsi', $tool->deskripsi) }}</textarea>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex flex-column flex-md-row gap-2 mt-5 pb-3">
                                    <button type="submit"
                                        class="btn btn-warning btn-lg rounded-pill px-5 fw-bold order-md-2 flex-grow-1 flex-md-grow-0">
                                        Update Alat
                                    </button>
                                    <a href="{{ route('admin.tools.index') }}"
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

    <script>
        // Fungsi sederhana untuk preview gambar sebelum upload
        function previewImg() {
            const input = document.getElementById('inputGambar');
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('image-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        // Jika sebelumnya tidak ada gambar, buat elemen img baru
                        location.reload(); // Untuk kemudahan refactor ini, jika rumit bisa pakai manipulasi DOM
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <style>
        .bg-warning-subtle {
            background-color: #fff9e6 !important;
        }

        .form-floating>.form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.1);
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "›";
            font-size: 1.2rem;
            vertical-align: middle;
        }
    </style>
@endsection
