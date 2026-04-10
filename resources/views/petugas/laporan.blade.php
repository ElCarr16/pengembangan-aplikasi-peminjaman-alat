@extends('layouts.app')

@section('content')
    <!-- Breadcrumb -->
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('petugas.laporan') }}" class="text-decoration-none">Laporan</a>
            </li>
        </ol>
    </nav>
    <div class="container my-lg-5 my-3">
        <!-- Action Bar: Tetap bersih dan responsif -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 no-print">
            <div>
                <h2 class="fw-bold text-dark mb-1">Laporan Peminjaman Alat</h2>
                <p class="text-muted mb-0">Kelola dan ekspor data peminjaman resmi</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('petugas.dashboard') }}" class="btn btn-light border px-4">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <button onclick="window.print()" class="btn btn-primary px-4 shadow-sm">
                    <i class="bi bi-printer-fill me-1"></i> Cetak PDF
                </button>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card border-0 shadow-sm overflow-hidden overflow-print-visible">
            <div class="card-body p-md-5 p-4">

                <!-- Kop Surat: Hanya muncul saat print -->
                <div class="d-none d-print-block mb-5">
                    <div class="row align-items-center">
                        <div class="col-2 text-center">
                            <!-- Ganti dengan Logo Instansi jika ada -->
                            <i class="bi bi-tools fs-1 text-primary"></i>
                        </div>
                        <div class="col-10 text-center pr-5">
                            <h2 class="fw-bold mb-0">RENT THE TOOLS INDONESIA</h2>
                            <p class="mb-0">Jl. Jend. Sudirman No. 123, Kota Cimahi - Jawa Barat</p>
                            <p class="small text-muted small">Email: support@renttools.id | Telp: (022) 1234567</p>
                        </div>
                    </div>
                    <hr class="border-dark border-2 opacity-100">
                    <div class="text-center mt-4">
                        <h4 class="text-uppercase fw-bold">Laporan Rekapitulasi Peminjaman</h4>
                        <p>Periode Laporan: <strong>{{ date('F Y') }}</strong></p>
                    </div>
                </div>

                <!-- Tabel Dinamis: Mobile (Card) & Desktop (Table) -->
                <div class="table-responsive">
                    <table class="table table-hover border-print align-middle">
                        <thead class="bg-light text-secondary small text-uppercase fw-bold">
                            <tr>
                                <th class="py-3 ps-3" width="50">No</th>
                                <th class="py-3">Peminjam</th>
                                <th class="py-3">Detail Alat</th>
                                <th class="py-3">Waktu Pinjam</th>
                                <th class="py-3">Waktu Kembali</th>
                                <th class="py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loans as $loan)
                                <tr>
                                    <td class="ps-3">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $loan->user->name }}</div>
                                        <div class="small text-muted d-print-none">ID: #{{ $loan->user->id }}</div>
                                    </td>
                                    <td>
                                        <div class="text-primary fw-medium">{{ $loan->tool->nama_alat }}</div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('d M Y') }}</td>
                                    <td>
                                        @if ($loan->tanggal_kembali_aktual)
                                            <span
                                                class="text-dark">{{ \Carbon\Carbon::parse($loan->tanggal_kembali_aktual)->translatedFormat('d M Y') }}</span>
                                        @else
                                            <span class="badge bg-light text-muted border fw-normal">Progress</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusClass =
                                                [
                                                    'kembali' => 'bg-success-subtle text-success border-success',
                                                    'pinjam' => 'bg-warning-subtle text-dark border-warning',
                                                ][$loan->status] ??
                                                'bg-secondary-subtle text-secondary border-secondary';
                                        @endphp
                                        <span class="badge border px-3 py-2 {{ $statusClass }}">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-folder-x fs-1 d-block mb-2 opacity-25"></i>
                                        Belum ada data peminjaman di periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Tanda Tangan: Hanya muncul saat print -->
                <div class="d-none d-print-block mt-5 pt-4">
                    <div class="d-flex justify-content-between text-center">
                        <div style="width: 200px">
                            <p class="mb-5">Mengetahui,<br>Kepala Gudang Alat</p>
                            <div class="mt-5 fw-bold text-decoration-underline">( ____________________ )</div>
                            <p class="small text-muted">NIP. ..........................</p>
                        </div>
                        <div style="width: 250px">
                            <p class="mb-5">Cimahi, {{ date('d F Y') }}<br>Petugas Administrasi,</p>
                            <div class="mt-5 fw-bold text-decoration-underline">
                                {{ auth()->user()->name ?? '____________________' }}</div>
                            <p class="small text-muted">Dicetak pada: {{ date('H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Styling Dasar Dashboard */
        .bg-success-subtle {
            background-color: #d1e7dd;
        }

        .bg-warning-subtle {
            background-color: #fff3cd;
        }

        .bg-secondary-subtle {
            background-color: #f8f9fa;
        }

        .table-hover tbody tr:hover {
            background-color: #fbfbfb;
        }

        /* CSS Khusus Print */
        @media print {
            @page {
                size: A4;
                margin: 1.5cm;
            }

            body {
                background: white !important;
                font-size: 11pt;
            }

            .container {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            .table td,
            .table th {
                border: 1px solid #dee2e6 !important;
                padding: 8px !important;
            }

            .badge {
                color: black !important;
                border: none !important;
                padding: 0 !important;
                background: transparent !important;
            }

            .no-print {
                display: none !important;
            }

            .overflow-print-visible {
                overflow: visible !important;
            }
        }
    </style>
@endsection
