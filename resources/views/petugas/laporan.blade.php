@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <!-- Header: Hidden saat print menggunakan class no-print -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <h2 class="fw-bold">Laporan Peminjaman Alat</h2>
            <div>
                <a href="{{ route('petugas.dashboard') }}" class="btn btn-secondary me-2">Kembali</a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Cetak Laporan (PDF)
                </button>
            </div>
        </div>

        <!-- Area yang akan di-print -->
        <div id="print-area" class="card shadow-sm">
            <div class="card-body p-5">
                <!-- Header Laporan (Hanya muncul saat print) -->
                <div class="text-center mb-4 d-none d-print-block">
                    <h3>LAPORAN PEMINJAMAN RENT THE TOOLS</h3>
                    <p class="mb-0">Periode: {{ date('F Y') }}</p>
                    <hr>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Peminjam</th>
                                <th>Nama Alat</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loans as $loan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $loan->user->name }}</strong></td>
                                    <td>{{ $loan->tool->nama_alat }}</td>
                                    <td>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('d M Y') }}</td>
                                    <td>
                                        @if ($loan->tanggal_kembali_aktual)
                                            {{ \Carbon\Carbon::parse($loan->tanggal_kembali_aktual)->translatedFormat('d M Y') }}
                                        @else
                                            <span class="text-muted small">Belum Kembali</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $loan->status == 'kembali' ? 'bg-success' : ($loan->status == 'pinjam' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data peminjaman untuk
                                        ditampilkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Tanda Tangan (Hanya muncul saat print) -->
                <div class="row mt-5 d-none d-print-flex">
                    <div class="col-8"></div>
                    <div class="col-4 text-center">
                        <p class="mb-5">Cimahi, {{ date('d F Y') }}<br>Petugas,</p>
                        <br>
                        <p class="fw-bold text-decoration-underline">( ____________________ )</p>
                        <p>NIP. ..........................</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* CSS Khusus Print */
        @media print {

            /* Sembunyikan semua elemen navigasi, tombol, dan footer website */
            .no-print,
            .main-footer,
            .navbar,
            .sidebar,
            .btn {
                display: none !important;
            }

            /* menghilangkan margin/padding bawaan browser */
            body {
                background-color: white !important;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* memastikan card tidak memiliki shadow atau border saat diprint */
            .card {
                border: none !important;
                shadow: none !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            /* Atur ukuran kertas ke A4 jika perlu */
            @page {
                size: A4;
                margin: 2cm;
            }
        }
    </style>
@endsection
