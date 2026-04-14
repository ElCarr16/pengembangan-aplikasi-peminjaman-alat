<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Peminjaman - {{ $loan->receipt_code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS ini bertugas menyembunyikan tombol saat struk di-print ke kertas/PDF */
        @media print {
            .no-print {
                display: none !important;
            }
        }

        .kertas-struk {
            max-width: 600px;
            margin: 40px auto;
            border: 2px dashed #4a4a4a;
            padding: 30px;
            border-radius: 15px;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container">
        <div class="card bg-white shadow-sm kertas-struk">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-uppercase">Rent The Tools</h3>
                <p class="text-muted mb-0">Tanda Terima Peminjaman Alat</p>
            </div>

            <hr style="border-top: 2px solid #000;">

            <div class="row mb-4 mt-4">
                <div class="col-6">
                    <p class="mb-1 text-muted" style="font-size: 0.9rem;">Kode Struk:</p>
                    <h5 class="fw-bold">{{ $loan->receipt_code }}</h5>
                </div>
                <div class="col-6 text-end">
                    <p class="mb-1 text-muted" style="font-size: 0.9rem;">Tanggal Disetujui:</p>
                    <h5 class="fw-bold">{{ \Carbon\Carbon::parse($loan->updated_at)->format('d M Y') }}</h5>
                </div>
            </div>

            @php
                // Kalkulasi Dinamis untuk Struk
                $tglPinjam = \Carbon\Carbon::parse($loan->tanggal_pinjam);
                $tglKembaliRencana = \Carbon\Carbon::parse($loan->tanggal_kembali_rencana);
                $durasiHari = max($tglPinjam->diffInDays($tglKembaliRencana), 1);

                $estimasiSewa = $loan->tool->harga_perhari * $loan->jumlah * $durasiHari;

                // Set nilai total sewa dan grand total berdasarkan status
                $totalSewa = $loan->status == 'kembali' ? $loan->total_harga : $estimasiSewa;
                $grandTotal = $loan->status == 'kembali' ? $loan->total_harga + $loan->denda : $estimasiSewa;
            @endphp

            <div class="bg-light p-3 rounded mb-4">
                <table class="table table-borderless mb-0 align-middle">
                    <tr>
                        <td width="35%" class="text-muted">Nama Peminjam</td>
                        <td width="5%">:</td>
                        <td class="fw-bold">{{ $loan->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alat yang Dipinjam</td>
                        <td>:</td>
                        <td class="fw-bold">{{ $loan->tool->nama_alat }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jumlah Alat</td>
                        <td>:</td>
                        <td class="fw-bold">{{ $loan->jumlah }} Unit</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Rencana Kembali</td>
                        <td>:</td>
                        <td class="fw-bold">{{ $tglKembaliRencana->format('d M Y') }}</td>
                    </tr>

                    @if ($loan->status == 'kembali')
                        <tr>
                            <td class="text-muted">Dikembalikan Tgl</td>
                            <td>:</td>
                            <td class="fw-bold text-success">
                                {{ \Carbon\Carbon::parse($loan->tanggal_kembali_aktual)->format('d M Y') }}</td>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-muted">Harga Sewa / Hari</td>
                        <td>:</td>
                        <td class="fw-bold">Rp {{ number_format($loan->tool->harga_perhari, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Harga Sewa</td>
                        <td>:</td>
                        <td class="fw-bold">
                            Rp {{ number_format($totalSewa, 0, ',', '.') }}
                            @if ($loan->status != 'kembali')
                                <small class="text-muted fw-normal">(Estimasi)</small>
                            @endif
                        </td>
                    </tr>

                    @if ($loan->status == 'kembali' && $loan->denda > 0)
                        <tr>
                            <td class="text-muted text-danger">Denda</td>
                            <td>:</td>
                            <td class="fw-bold text-danger">Rp {{ number_format($loan->denda, 0, ',', '.') }}</td>
                        </tr>
                        @if ($loan->deskripsi_denda)
                            <tr>
                                <td colspan="3" class="text-muted fst-italic"
                                    style="font-size: 0.8rem; padding-top: 0;">
                                    *Catatan Denda: {{ $loan->deskripsi_denda }}
                                </td>
                            </tr>
                        @endif
                    @endif

                    {{-- Baris Grand Total --}}
                    <tr style="border-top: 2px dashed #ccc;">
                        <td class="text-dark fw-bold pt-3 h6 mb-0">
                            {{ $loan->status == 'kembali' ? 'Grand Total' : 'Estimasi Total' }}
                        </td>
                        <td class="pt-3">:</td>
                        <td class="fw-bold text-success fs-5 pt-3">
                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- Baris Tunai & Kembali (Muncul saat status 'kembali') --}}
                    @if ($loan->status == 'kembali' && isset($loan->bayar) && $loan->bayar > 0)
                        <tr>
                            <td class="text-muted pt-2">Tunai (Bayar)</td>
                            <td class="pt-2">:</td>
                            <td class="fw-bold text-dark pt-2">
                                Rp {{ number_format($loan->bayar, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted pb-3">Kembali</td>
                            <td class="pb-3">:</td>
                            <td class="fw-bold text-dark pb-3">
                                Rp {{ number_format($loan->kembalian, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>

            <hr style="border-top: 2px solid #000;">

            <div class="text-center mt-4">
                <p class="small text-muted mb-0">
                    Tunjukkan struk ini kepada petugas untuk mengambil alat.<br>
                    Harap jaga kondisi alat dan kembalikan tepat waktu!
                </p>
            </div>
        </div>

        <div class="text-center mt-3 mb-5 no-print">
            <button onclick="window.print()" class="btn btn-primary px-4 py-2 me-2">
                <i class="bi bi-printer me-1"></i> Cetak / Download PDF
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary px-4 py-2">Kembali</a>
        </div>
    </div>

</body>

</html>
