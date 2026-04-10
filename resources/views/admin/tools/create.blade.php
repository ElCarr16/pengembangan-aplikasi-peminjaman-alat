@extends('layouts.app')

@section('content')
    <!-- Breadcrumb Navigasi -->
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item small"><a href="{{ route('admin.tools.index') }}"
                    class="text-decoration-none">Inventaris</a></li>
            <li class="breadcrumb-item small active" aria-current="page">Tambah Alat Baru</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-tools fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Registrasi Alat Baru</h5>
                            <p class="text-muted small mb-0">Tambahkan aset baru ke dalam sistem inventaris.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 pt-md-2">
                    <form action="{{ route('admin.tools.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            <!-- Upload Gambar Section -->
                            <div class="col-md-4 order-md-2">
                                <label class="form-label fw-bold small text-uppercase text-muted">Foto Alat</label>
                                <div class="upload-placeholder rounded-4 border border-2 border-dashed d-flex flex-column align-items-center justify-content-center bg-light mb-3"
                                    style="min-height: 200px;">
                                    <div id="preview-container" class="d-none w-100 h-100 p-2">
                                        <img id="image-preview" src="#"
                                            class="img-fluid rounded-3 shadow-sm w-100 h-100 object-fit-cover"
                                            style="max-height: 185px;">
                                    </div>
                                    <div id="placeholder-content" class="text-center p-3">
                                        <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                        <p class="small text-muted mb-0">Pilih foto alat untuk melihat preview</p>
                                    </div>
                                </div>
                                <input type="file" name="gambar"
                                    class="form-control form-control-sm rounded-3 @error('gambar') is-invalid @enderror"
                                    id="inputGambar" accept="image/*" onchange="previewImg(this)">
                                <div class="mt-2">
                                    <span class="badge bg-light text-muted border fw-normal" style="font-size: 0.7rem;">JPG,
                                        PNG, WEBP (Maks 2MB)</span>
                                </div>
                                @error('gambar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Detail Alat Section -->
                            <div class="col-md-8 order-md-1">
                                <h6 class="text-uppercase small fw-bold text-muted mb-4 pb-2 border-bottom">Spesifikasi
                                    Detail</h6>

                                <!-- Nama Alat -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="nama_alat"
                                        class="form-control rounded-3 @error('nama_alat') is-invalid @enderror"
                                        id="addNama" placeholder="Nama Alat" value="{{ old('nama_alat') }}" required>
                                    <label for="addNama">Nama Alat</label>
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
                                                id="addCat" required>
                                                <option value="" hidden>Pilih Kategori...</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="addCat">Kategori</label>
                                        </div>
                                        @error('category_id')
                                            <small class="text-danger small">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <!-- Stok -->
                                    <div class="col-md-5">
                                        <div class="form-floating">
                                            <input type="number" name="stok"
                                                class="form-control rounded-3 @error('stok') is-invalid @enderror"
                                                id="addStok" placeholder="Stok" value="{{ old('stok', 1) }}"
                                                min="0" required>
                                            <label for="addStok">Jumlah Stok</label>
                                        </div>
                                        @error('stok')
                                            <small class="text-danger small">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Harga Perhari -->
                                <div class="form-floating mb-3">
                                    <input type="number" name="harga_perhari"
                                        class="form-control rounded-3 @error('harga_perhari') is-invalid @enderror"
                                        id="addHarga" placeholder="Harga Perhari" value="{{ old('harga_perhari', 0) }}"
                                        min="0" required>
                                    <label for="addHarga">Harga Sewa Perhari (Rp)</label>
                                    @error('harga_perhari')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="form-group mb-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase"
                                        for="addDesc">Deskripsi / Spesifikasi</label>
                                    <textarea name="deskripsi" class="form-control rounded-3" id="addDesc" rows="4"
                                        placeholder="Contoh: Merk, warna, kondisi barang, atau kelengkapan lainnya...">{{ old('deskripsi') }}</textarea>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex flex-column flex-md-row gap-2 mt-5">
                                    <button type="submit"
                                        class="btn btn-primary btn-lg rounded-pill px-5 fw-bold order-md-2 flex-grow-1 flex-md-grow-0">
                                        Simpan Alat <i class="bi bi-save2 ms-2"></i>
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
        function previewImg(input) {
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('preview-container');
            const placeholder = document.getElementById('placeholder-content');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('d-none');
                    placeholder.classList.add('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <style>
        .bg-primary-subtle {
            background-color: #e7f0ff !important;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .form-floating>.form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }
    </style>
@endsection
