@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Riwayat Pengembalian</h3>
            <p class="text-muted small mb-0">Daftar alat yang telah selesai dipinjam.</p>
        </div>
        {{-- <a href="{{ route('admin.returns.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-plus-circle me-1"></i> Proses Pengembalian
        </a> --}}
    </div>

    {{-- @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            {{ session('success') }}
        </div>
    @endif --}}

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr class="small text-uppercase fw-bold">
                        <th class="ps-4 py-3">No</th>
                        <th>Peminjam & Alat</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $key => $r)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $returns->firstItem() + $key }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $r->user->name }}</div>
                                <div class="text-warning small">{{ $r->tool->nama_alat }} ({{ $r->jumlah }} unit)</div>
                            </td>
                            <td>
                                <div class="small fw-bold">
                                    {{ \Carbon\Carbon::parse($r->tanggal_kembali_aktual)->format('d M Y') }}</div>
                            </td>
                            <td>
                                @if (\Carbon\Carbon::parse($r->tanggal_kembali_aktual)->startOfDay() > \Carbon\Carbon::parse($r->tanggal_kembali_rencana)->startOfDay())
                                    <span class="badge bg-danger-subtle text-danger border rounded-pill px-3">Telat</span>
                                @else
                                    <span class="badge bg-success-subtle text-success border rounded-pill px-3">Tepat</span>
                                @endif
                            </td>
                            <td><span class="small fw-bold text-danger">Rp
                                    {{ number_format($r->denda, 0, ',', '.') }}</span></td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                        <li><a class="dropdown-item text-warning"
                                                href="{{ route('admin.returns.edit', $r->id) }}"><i
                                                    class="bi bi-pencil me-2"></i> Koreksi</a></li>
                                        <li>
                                            <hr class="dropdown-divider opacity-50">
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.returns.destroy', $r->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus riwayat ini?')">
                                                @csrf @method('DELETE')
                                                <button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>
                                                    Hapus</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat pengembalian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $returns->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
