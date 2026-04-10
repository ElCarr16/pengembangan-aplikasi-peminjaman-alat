@extends('layouts.app')

@section('content')
    <!-- Breadcrumb -->
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Daftar Alat</a>
            </li>
        </ol>
    </nav>
    <div class="container py-4">
        <!-- Header Section: Judul & Pencarian -->
        <div class="row align-items-center mb-5 no-print">
            <div class="col-md-6 mb-3 mb-md-0">
                <h2 class="fw-bold text-dark mb-1">Daftar Alat Tersedia</h2>
                <p class="text-muted">Pilih dan pinjam peralatan proyek Anda dengan mudah.</p>
            </div>
            <div class="col-md-6">
                <form action="{{ request('peminjam.dashboard')}}" method="GET" class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" name="search" class="form-control ps-5 py-3 shadow-sm border-0 rounded-4"
                        placeholder="Cari bor, gergaji, atau alat lainnya..." value="{{ request('search') }}">
                </form>
            </div>
        </div>

        <!-- Filter Kategori: Quick Chips -->
        <div class="d-flex gap-2 overflow-x-auto pb-3 mb-4 no-print shadow-none"
            style="white-space: nowrap; -webkit-overflow-scrolling: touch;">
            <a href="{{ request()->fullUrlWithQuery(['category' => '']) }}"
                class="btn {{ request('category') == '' ? 'btn-primary' : 'btn-white border' }} rounded-pill px-4 shadow-sm">
                Semua
            </a>
            <!-- Contoh Loop Kategori (Sesuaikan dengan data Anda) -->
            @foreach ($categories ?? [] as $cat)
                <a href="{{ request()->fullUrlWithQuery(['category' => $cat->id]) }}"
                    class="btn {{ request('category') == $cat->id ? 'btn-primary' : 'btn-white border' }} rounded-pill px-4 shadow-sm">
                    {{ $cat->nama_kategori }}
                </a>
            @endforeach
        </div>

        <!-- Grid Alat -->
        <div class="row g-3 g-md-4">
            @forelse ($tools as $tool)
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden tool-card">
                        <!-- Status Badge -->
                        <div class="position-absolute top-0 end-0 m-2">
                            @if ($tool->stok > 0)
                                <span
                                    class="badge bg-success-subtle text-success px-3 py-2 rounded-pill border border-success">
                                    Ready
                                </span>
                            @else
                                <span
                                    class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill border border-danger">
                                    Empty
                                </span>
                            @endif
                        </div>

                        <!-- Image Section -->
                        <div class="bg-light d-flex align-items-center justify-content-center p-3" style="height: 180px;">
                            <img src="{{ $tool->gambar ? asset('storage/' . $tool->gambar) : 'https://via.placeholder.com/300?text=No+Image' }}"
                                class="img-fluid" style="max-height: 100%; object-fit: contain;">
                        </div>

                        <div class="card-body d-flex flex-column p-3 p-md-4">
                            <small class="text-primary fw-bold text-uppercase mb-1"
                                style="font-size: 0.7rem; letter-spacing: 1px;">
                                {{ $tool->category->nama_kategori }}
                            </small>
                            <h5 class="card-title fw-bold text-dark mb-2 text-truncate-2"
                                style="height: 3rem; line-height: 1.5rem;">
                                {{ $tool->nama_alat }}
                            </h5>

                            <p class="card-text text-muted small mb-3 text-truncate-3 d-none d-md-block">
                                {{ Str::limit($tool->deskripsi, 60) }}
                            </p>

                            <div class="mb-3 text-primary fw-bold fs-5">
                                Rp {{ number_format($tool->harga_perhari, 0, ',', '.') }} <span class="text-muted small fw-normal">/ hari</span>
                            </div>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="small text-muted">Stok tersedia</span>
                                    <span
                                        class="fw-bold {{ $tool->stok > 0 ? 'text-dark' : 'text-danger' }}">{{ $tool->stok }}
                                        unit</span>
                                </div>

                                @if ($tool->stok > 0)
                                    <a href="{{ route('peminjam.tools.show', $tool->id) }}"
                                        class="btn btn-primary w-100 py-2 rounded-3 shadow-sm fw-bold">
                                        Pinjam Alat
                                    </a>
                                @else
                                    <button class="btn btn-light w-100 py-2 rounded-3 border text-muted fw-bold" disabled>
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="mb-3 display-1 text-muted opacity-25">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h4 class="text-muted">Alat tidak ditemukan</h4>
                    <p>Coba gunakan kata kunci lain atau bersihkan filter.</p>
                    <a href="{{ route('peminjam.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4">Reset
                        Pencarian</a>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .tool-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .tool-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .text-truncate-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Sembunyikan Scrollbar tapi tetap bisa scroll */
        .overflow-x-auto::-webkit-scrollbar {
            display: none;
        }

        .overflow-x-auto {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .bg-success-subtle {
            background-color: #e1f7ec !important;
        }

        .bg-danger-subtle {
            background-color: #fee2e2 !important;
        }
    </style>
@endsection
