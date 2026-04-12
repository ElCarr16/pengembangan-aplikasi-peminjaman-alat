@extends('layouts.app')

@section('content')
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Daftar Alat</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profil</li>
        </ol>
    </nav>
    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Riwayat Peminjaman Saya</h3>
                <p class="text-muted small">Pantau status dan batas waktu pengembalian alat Anda</p>
            </div>
            <i class="bi bi-clock-history fs-2 text-warning opacity-25 d-none d-md-block"></i>
        </div>

        <div class="row g-3 mb-4 no-print">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <small class="text-muted d-block mb-1">Total Pinjam</small>
                    <span class="h5 fw-bold mb-0">{{ $loans->count() }}</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100 border-start border-warning border-4">
                    <small class="text-muted d-block mb-1">Sedang Aktif</small>
                    <span class="h5 fw-bold mb-0 text-warning">{{ $loans->where('status', 'disetujui')->count() }}</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-warning border-opacity-25 shadow-sm rounded-4 mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-warning text-dark py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Alat Yang Sedang Dipinjam</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            {{-- PERBAIKAN: Tampilkan SEMUA yang disetujui, lalu pisahkan tombolnya berdasarkan status is_diambil --}}
                            @forelse($loans->where('status', 'disetujui') as $activeLoan)
                                <li class="list-group-item p-3">
                                    <div class="mb-2">
                                        <h6 class="fw-bold text-dark mb-1">{{ $activeLoan->tool->nama_alat }}</h6>
                                        <small class="text-danger fw-medium d-block"><i
                                                class="bi bi-calendar-x me-1"></i>Batas:
                                            {{ \Carbon\Carbon::parse($activeLoan->tanggal_kembali_rencana)->translatedFormat('d M Y') }}</small>
                                    </div>

                                    <div class="mt-3">
                                        @if (!$activeLoan->is_diambil)
                                            <div
                                                class="alert alert-warning py-2 px-3 mb-0 small text-center rounded-pill text-dark fw-medium border-warning">
                                                <i class="bi bi-person-badge me-1"></i> Temui petugas untuk mengambil alat
                                            </div>
                                        @elseif($activeLoan->is_return_requested)
                                            <div class="alert alert-info py-2 px-3 mb-0 small text-center rounded-pill">
                                                <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi Petugas
                                            </div>
                                        @else
                                            <form action="{{ route('peminjam.request_return', $activeLoan->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-outline-warning btn-sm rounded-pill w-100 fw-bold shadow-sm">
                                                    Ajukan Pengembalian
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item p-4 text-center">
                                    <div class="text-muted small">
                                        <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                        Tidak ada alat fisik di tangan Anda saat ini.
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Alat</th>
                                        <th class="py-3">Waktu Pinjam</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3 pe-4">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($loans as $loan)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">{{ $loan->tool->nama_alat }}</div>
                                                <small class="text-muted">ID: #{{ $loan->id }}</small>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <i class="bi bi-calendar-check me-1"></i>
                                                    {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('d M Y') }}
                                                </div>
                                                <div class="small text-danger">
                                                    <i class="bi bi-calendar-x me-1"></i> Kembali:
                                                    {{ \Carbon\Carbon::parse($loan->tanggal_kembali_rencana)->translatedFormat('d M Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                {{-- PERBAIKAN: Logika status yang menyesuaikan kondisi is_diambil --}}
                                                @if ($loan->status == 'pending')
                                                    <span
                                                        class="badge border bg-warning-subtle text-warning-emphasis px-3 py-2 rounded-pill fw-medium">
                                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                                    </span>
                                                @elseif ($loan->status == 'disetujui' && !$loan->is_diambil)
                                                    <span
                                                        class="badge border bg-warning-subtle text-warning-emphasis px-3 py-2 rounded-pill fw-medium">
                                                        <i class="bi bi-box-seam me-1"></i> Menunggu Diambil
                                                    </span>
                                                @elseif ($loan->status == 'disetujui' && $loan->is_diambil)
                                                    <span
                                                        class="badge border bg-warning-subtle text-warning-emphasis px-3 py-2 rounded-pill fw-medium">
                                                        <i class="bi bi-tools me-1"></i> Sedang Dipakai
                                                    </span>
                                                @elseif ($loan->status == 'kembali')
                                                    <span
                                                        class="badge border bg-success-subtle text-success-emphasis px-3 py-2 rounded-pill fw-medium">
                                                        <i class="bi bi-check-all me-1"></i> Selesai
                                                    </span>
                                                @elseif ($loan->status == 'ditolak')
                                                    <span
                                                        class="badge border bg-danger-subtle text-danger-emphasis px-3 py-2 rounded-pill fw-medium">
                                                        <i class="bi bi-x-circle me-1"></i> Ditolak
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="pe-4">
                                                @if ($loan->status == 'disetujui' && !$loan->is_diambil)
                                                    <div
                                                        class="p-2 rounded bg-light small border-start border-3 border-warning text-dark">
                                                        Alat siap. Temui petugas dan tunjukkan halaman ini.
                                                    </div>
                                                @elseif ($loan->status == 'disetujui' && $loan->is_diambil)
                                                    <div
                                                        class="p-2 rounded bg-light small border-start border-3 border-warning">
                                                        Harap kembalikan tepat waktu untuk menghindari denda.
                                                    </div>
                                                @elseif($loan->status == 'kembali')
                                                    <div class="small text-success fw-medium">
                                                        <i class="bi bi-info-circle me-1"></i> Diterima:
                                                        {{ \Carbon\Carbon::parse($loan->tanggal_kembali_aktual)->translatedFormat('d M Y') }}
                                                    </div>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <i class="bi bi-inbox fs-1 text-muted opacity-25 d-block mb-2"></i>
                                                <p class="text-muted">Belum ada riwayat peminjaman.</p>
                                                <a href="{{ route('peminjam.dashboard') }}"
                                                    class="btn btn-warning btn-sm rounded-pill px-4">Pinjam Alat
                                                    Sekarang</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-md-none">
                            @forelse($loans as $loan)
                                <div class="p-3 border-bottom shadow-sm-hover position-relative">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0 text-dark">{{ $loan->tool->nama_alat }}</h6>
                                        <span
                                            class="small fw-bold {{ $loan->status == 'disetujui' ? 'text-warning' : 'text-muted' }}">#{{ $loan->id }}</span>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="d-block small text-muted">Tgl Pinjam</label>
                                            <span
                                                class="small">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('d M Y') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <label class="d-block small text-muted">Batas Kembali</label>
                                            <span
                                                class="small text-danger fw-medium">{{ \Carbon\Carbon::parse($loan->tanggal_kembali_rencana)->translatedFormat('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        @if ($loan->status == 'pending')
                                            <span
                                                class="badge border bg-warning-subtle text-warning-emphasis rounded-pill">Menunggu</span>
                                        @elseif ($loan->status == 'disetujui' && !$loan->is_diambil)
                                            <span
                                                class="badge border bg-warning-subtle text-warning-emphasis rounded-pill">Menunggu
                                                Diambil</span>
                                        @elseif ($loan->status == 'disetujui' && $loan->is_diambil)
                                            <span
                                                class="badge border bg-warning-subtle text-warning-emphasis rounded-pill">Sedang
                                                Dipakai</span>
                                        @elseif ($loan->status == 'kembali')
                                            <span
                                                class="badge border bg-success-subtle text-success-emphasis rounded-pill">Selesai</span>
                                        @elseif ($loan->status == 'ditolak')
                                            <span
                                                class="badge border bg-danger-subtle text-danger-emphasis rounded-pill">Ditolak</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <p class="text-muted">Belum ada riwayat peminjaman.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom Styling for Better Feel */
        .bg-warning-subtle {
            background-color: #fff3cd !important;
            color: #664d03 !important;
            border-color: #ffe69c !important;
        }

        .bg-warning-subtle {
            background-color: #cfe2ff !important;
            color: #084298 !important;
            border-color: #b6d4fe !important;
        }

        .bg-success-subtle {
            background-color: #d1e7dd !important;
            color: #0f5132 !important;
            border-color: #badbcc !important;
        }

        .bg-danger-subtle {
            background-color: #f8d7da !important;
            color: #842029 !important;
            border-color: #f5c2c7 !important;
        }

        .bg-info-subtle {
            background-color: #cff4fc !important;
            color: #055160 !important;
            border-color: #b6effb !important;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transition: 0.2s;
        }

        .shadow-sm-hover:active {
            background-color: #f8f9fa;
        }
    </style>
@endsection
