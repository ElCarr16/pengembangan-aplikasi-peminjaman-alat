@extends('layouts.app')

@section('content')

{{-- ================= PENDING ================= --}}
<h4 class="mb-3">Permintaan Peminjaman</h4>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-warning fw-semibold">
        Menunggu Persetujuan
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tgl Pinjam</th>
                        <th>Kembali</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->tool->nama_alat }}</td>
                            <td>{{ $loan->tanggal_pinjam }}</td>
                            <td>{{ $loan->tanggal_kembali_rencana }}</td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">

                                    {{-- APPROVE --}}
                                    <form action="{{ route('petugas.approve', $loan->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm">
                                            Setujui
                                        </button>
                                    </form>

                                    {{-- REJECT --}}
                                    <form action="{{ route('petugas.reject', $loan->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">
                                            Tolak
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                Tidak ada permintaan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= ACTIVE ================= --}}
<h4 class="mb-3">Sedang Dipinjam</h4>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-primary text-white fw-semibold">
        Peminjaman Aktif
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($activeLoans as $loan)
                    <tr>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->tool->nama_alat }}</td>
                        <td>
                            <span class="badge bg-primary">Dipinjam</span>
                        </td>

                        <td class="text-center">
                            <form action="{{ route('petugas.return', $loan->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-success btn-sm">
                                    Kembalikan
                                </button>
                            </form>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                            Tidak ada peminjaman aktif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================= RETURNED ================= --}}
<h4 class="mb-3">Riwayat Pengembalian</h4>

<div class="card shadow-sm border-0">
    <div class="card-header bg-success text-white fw-semibold">
        Sudah Dikembalikan
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($returnedLoans as $loan)
                    <tr>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->tool->nama_alat }}</td>
                        <td>
                            <span class="badge bg-success">Kembali</span>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-3">
                            Belum ada pengembalian
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection