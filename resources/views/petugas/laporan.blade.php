<!DOCTYPE html>
<html lang="id">

<head>
    <title>Laporan Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }

        .report-header {
            margin-bottom: 1.5rem;
        }

        .report-footer {
            margin-top: 3rem;
        }
    </style>
</head>

<body class="p-4">
    <div class="report-header d-flex justify-content-between align-items-center">
        <h2>Laporan Peminjaman Alat</h2>
        <button onclick="window.print()" class="btn btn-primary no-print">Cetak PDF / Print</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->tool->nama_alat }}</td>
                        <td>{{ $loan->tanggal_pinjam }}</td>
                        <td>{{ $loan->tanggal_kembali_aktual ?? '-' }}</td>
                        <td>{{ ucfirst($loan->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data peminjaman untuk ditampilkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="report-footer text-end">
        <p>Cimahi, {{ date('d F Y') }}</p>
        <br><br>
        <p>( Petugas Lab )</p>
    </div>
</body>

</html>