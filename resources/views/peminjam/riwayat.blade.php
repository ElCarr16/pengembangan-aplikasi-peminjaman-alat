@extends('layouts.app')

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Daftar Alat</a></li>
            <li class="breadcrumb-item"><a href="{{ route('peminjam.riwayat') }}" class="text-decoration-none">Riwayat Peminjaman</a></li>

        </ol>
    </nav>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Riwayat Peminjaman Saya</h3>
                <p class="text-muted small">Pantau status dan batas waktu pengembalian alat Anda</p>
            </div>
            <i class="bi bi-clock-history fs-2 text-primary opacity-25 d-none d-md-block"></i>
        </div>

        <!-- Stats Summary (Optional UX Enhancement) -->
        <div class="row g-3 mb-4 no-print">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <small class="text-muted d-block mb-1">Total Pinjam</small>
                    <span class="h5 fw-bold mb-0">{{ $loans->count() }}</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-primary border-4">
                    <small class="text-muted d-block mb-1">Aktif</small>
                    <span class="h5 fw-bold mb-0 text-primary">{{ $loans->where('status', 'disetujui')->count() }}</span>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <!-- Desktop View: Table -->
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
                                        @php
                                            $statusConfig = [
                                                'pending' => [
                                                    'bg' => 'bg-warning-subtle text-warning-emphasis',
                                                    'label' => 'Menunggu',
                                                    'icon' => 'bi-hourglass-split',
                                                ],
                                                'disetujui' => [
                                                    'bg' => 'bg-primary-subtle text-primary-emphasis',
                                                    'label' => 'Dipinjam',
                                                    'icon' => 'bi-play-circle',
                                                ],
                                                'kembali' => [
                                                    'bg' => 'bg-success-subtle text-success-emphasis',
                                                    'label' => 'Selesai',
                                                    'icon' => 'bi-check-all',
                                                ],
                                                'ditolak' => [
                                                    'bg' => 'bg-danger-subtle text-danger-emphasis',
                                                    'label' => 'Ditolak',
                                                    'icon' => 'bi-x-circle',
                                                ],
                                            ][$loan->status];
                                        @endphp
                                        <span
                                            class="badge border {{ $statusConfig['bg'] }} px-3 py-2 rounded-pill fw-medium">
                                            <i class="bi {{ $statusConfig['icon'] }} me-1"></i>
                                            {{ $statusConfig['label'] }}
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        @if ($loan->status == 'disetujui')
                                            <div class="p-2 rounded bg-light small border-start border-3 border-primary">
                                                Harap kembalikan tepat waktu untuk menghindari denda.
                                            </div>
                                        @elseif($loan->status == 'kembali')
                                            <div class="small text-success fw-medium">
                                                <i class="bi bi-info-circle me-1"></i> Diterima:
                                                {{ $loan->tanggal_kembali_aktual }}
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
                                            class="btn btn-primary btn-sm rounded-pill px-4">Pinjam Alat Sekarang</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View: Card List -->
                <div class="d-md-none">
                    @forelse($loans as $loan)
                        <div class="p-3 border-bottom shadow-sm-hover position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0 text-dark">{{ $loan->tool->nama_alat }}</h6>
                                <span
                                    class="small fw-bold {{ $loan->status == 'disetujui' ? 'text-primary' : 'text-muted' }}">#{{ $loan->id }}</span>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="d-block small text-muted">Tgl Pinjam</label>
                                    <span class="small">{{ $loan->tanggal_pinjam }}</span>
                                </div>
                                <div class="col-6">
                                    <label class="d-block small text-muted">Batas Kembali</label>
                                    <span class="small text-danger fw-medium">{{ $loan->tanggal_kembali_rencana }}</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge border {{ $statusConfig['bg'] }} rounded-pill">
                                    {{ $statusConfig['label'] }}
                                </span>

                                @if ($loan->status == 'disetujui')
                                    <button class="btn btn-outline-primary btn-sm rounded-pill py-0 px-3"
                                        style="font-size: 0.75rem;">Detail</button>
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

    <style>
        /* Custom Styling for Better Feel */
        .bg-warning-subtle {
            background-color: #fff3cd !important;
            color: #664d03 !important;
            border-color: #ffe69c !important;
        }

        .bg-primary-subtle {
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

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transition: 0.2s;
        }

        .shadow-sm-hover:active {
            background-color: #f8f9fa;
        }
    </style>
@endsection
