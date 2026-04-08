@extends('layouts.app')

@section('content')
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Kelola Data Peminjaman</h4>
        <a href="{{ route('admin.loans.create') }}" class="btn btn-primary">
            + Tambah Peminjaman
        </a>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th width="18%" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($loans as $key => $loan)
                            <tr>

                                <td>{{ $loans->firstItem() + $key }}</td>

                                <td class="fw-semibold">
                                    {{ $loan->user->name }}
                                </td>

                                <td>
                                    {{ $loan->tool->nama_alat }}
                                </td>

                                <td>
                                    <div>{{ $loan->tanggal_pinjam }}</div>
                                    <small class="text-muted">
                                        Kembali: {{ $loan->tanggal_kembali_rencana }}
                                    </small>
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @switch($loan->status)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @break

                                        @case('disetujui')
                                            <span class="badge bg-primary">Dipinjam</span>
                                        @break

                                        @case('kembali')
                                            <span class="badge bg-success">Kembali</span>
                                        @break

                                        @case('ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">Unknown</span>
                                    @endswitch
                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('admin.loans.edit', $loan->id) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus data ini?');">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Tidak ada data peminjaman
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>

            {{-- PAGINATION --}}
            <div class="card-footer bg-white">
                {{ $loans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endsection
