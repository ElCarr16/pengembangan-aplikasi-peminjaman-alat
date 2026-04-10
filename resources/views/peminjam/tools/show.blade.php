@extends('layouts.app')

@section('content')
    <!-- Breadcrumb -->
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Daftar Alat</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $tool->nama_alat }}</li>
        </ol>
    </nav>
    <div class="container py-5">
        <div class="row g-5">
            {{-- BAGIAN KIRI: VISUAL ALAT --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                    <div class="p-4 bg-light rounded-4">
                        <img src="{{ $tool->gambar ? asset('storage/' . $tool->gambar) : 'https://via.placeholder.com/600x400?text=No+Image' }}"
                            class="img-fluid rounded-3 shadow-sm mx-auto d-block tool-preview" alt="{{ $tool->nama_alat }}">
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-3">Informasi Alat</h6>
                        <div class="d-flex align-items-center mb-2 text-muted">
                            <i class="bi bi-tag-fill me-2 text-primary"></i>
                            <span>Kategori: <strong>{{ $tool->category->nama_kategori }}</strong></span>
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-check2-square me-2 text-success"></i>
                            <span>Kondisi: <strong>Siap Pakai (Grade A)</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BAGIAN KANAN: DETAIL & FORM --}}
            <div class="col-lg-7">
                <div class="ps-lg-3">
                    <h1 class="display-6 fw-bold text-dark mb-2">{{ $tool->nama_alat }}</h1>

                    <div class="mb-4">
                        @if ($tool->stok > 0)
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill border border-success">
                                <i class="bi bi-box-seam me-1"></i> Stok Tersedia: {{ $tool->stok }} Unit
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill border border-danger">
                                <i class="bi bi-exclamation-triangle me-1"></i> Stok Habis
                            </span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h3 class="fw-bold text-primary mb-0">
                            Rp {{ number_format($tool->harga_perhari, 0, ',', '.') }} <span
                                class="fs-6 text-muted fw-normal">/ hari</span>
                        </h3>
                    </div>

                    <div class="mb-4 p-4 bg-white rounded-4 shadow-sm border">
                        <h5 class="fw-bold mb-3"><i class="bi bi-justify-left me-2"></i>Deskripsi Alat</h5>
                        <p class="text-muted leading-relaxed" style="line-height: 1.8;">
                            {{ $tool->deskripsi ?: 'Tidak ada deskripsi tambahan untuk alat ini.' }}
                        </p>
                    </div>

                    {{-- CARD FORM PINJAM --}}
                    <div class="card border-primary border-opacity-25 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Form Peminjaman</h5>
                        </div>
                        <div class="card-body p-4">
                            @if ($tool->stok > 0)
                                <form action="{{ route('peminjam.ajukan') }}" method="POST" id="loanForm">
                                    @csrf
                                    <input type="hidden" name="tool_id" value="{{ $tool->id }}">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Jumlah
                                                Pinjam</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0"><i
                                                        class="bi bi-hash"></i></span>
                                                <input type="number" name="jumlah"
                                                    class="form-control border-start-0 ps-0 py-2" min="1"
                                                    max="{{ $tool->stok }}" value="1" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Tanggal
                                                Kembali</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0"><i
                                                        class="bi bi-calendar-event"></i></span>
                                                <input type="date" name="tgl_kembali"
                                                    class="form-control border-start-0 ps-0 py-2" id="tgl_kembali" required
                                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-light p-3 rounded-3 mb-4">
                                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Catatan:</small>
                                        <p class="small mb-0 mt-1">Peminjaman akan diverifikasi oleh admin maksimal 1x24
                                            jam. Pastikan Anda mengembalikan alat sesuai tanggal rencana.</p>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow">
                                        Ajukan Peminjaman Sekarang
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-clock-history fs-1 text-muted opacity-50 mb-3"></i>
                                    <h5 class="text-muted">Maaf, alat sedang tidak tersedia.</h5>
                                    <p class="small text-muted mb-0">Silakan cek kembali secara berkala atau hubungi admin.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none text-muted p-0">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Alat
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tool-preview {
            max-height: 400px;
            object-fit: contain;
            transition: transform 0.5s ease;
        }

        .tool-preview:hover {
            transform: scale(1.05);
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }

        .input-group-text {
            color: #0d6efd;
        }
    </style>
@endsection
