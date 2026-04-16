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

            <div class="bg-light p-3 rounded mb-4">
                <table class="table table-borderless mb-0">
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
                        <td class="fw-bold">{{ \Carbon\Carbon::parse($loan->tanggal_kembali_rencana)->format('d M Y') }}
                        </td>
                    </tr>
                    @if($loan->status == 'kembali')
                    <tr>
                        <td class="text-muted">Dikembalikan Tgl</td>
                        <td>:</td>
                        <td class="fw-bold text-success">{{ \Carbon\Carbon::parse($loan->tanggal_kembali_aktual)->format('d M Y') }}</td>
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
                            @if($loan->status == 'kembali')
                                Rp {{ number_format($loan->total_harga, 0, ',', '.') }}
                            @else
                                @php
                                    $tglPinjam = \Carbon\Carbon::parse($loan->tanggal_pinjam);
                                    $tglKembaliRencana = \Carbon\Carbon::parse($loan->tanggal_kembali_rencana);
                                    $durasiHari = max($tglPinjam->diffInDays($tglKembaliRencana), 1);
                                    $estimasiTotal = $loan->tool->harga_perhari * $loan->jumlah * $durasiHari;
                                @endphp
                                Rp {{ number_format($estimasiTotal, 0, ',', '.') }} <small class="text-muted fw-normal">(Estimasi)</small>
                            @endif
                        </td>
                    </tr>
                    @if($loan->status == 'kembali' && $loan->denda > 0)
                    <tr>
                        <td class="text-muted text-danger">Denda</td>
                        <td>:</td>
                        <td class="fw-bold text-danger">Rp {{ number_format($loan->denda, 0, ',', '.') }}</td>
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
                Cetak / Download PDF
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary px-4 py-2">Kembali</a>
        </div>
    </div>

</body>

</html>
