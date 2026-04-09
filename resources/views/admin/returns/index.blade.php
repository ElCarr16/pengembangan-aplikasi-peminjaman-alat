@extends('layouts.app')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Data Pengembalian Alat</h3>
            <p class="text-muted small mb-0">Kelola dan pantau riwayat pengembalian serta status keterlambatan.</p>
        </div>
        <a href="{{ route('admin.returns.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-plus-circle me-1"></i> Proses Pengembalian
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        <!-- ==========================================
             DESKTOP VIEW (TABEL)
             Sembunyi di HP, Muncul di Tablet & Desktop
        =========================================== -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr class="small text-uppercase fw-bold">
                        <th class="ps-4 py-3">No</th>
                        <th>Peminjam & Alat</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Petugas</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $key => $r)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $returns->firstItem() + $key }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $r->user->name }}</div>
                                <div class="text-primary small"><i class="bi bi-tools me-1"></i> {{ $r->tool->nama_alat }}
                                </div>
                            </td>
                            <td>
                                <div class="small"><span class="text-muted">Pinjam:</span>
                                    {{ \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d/m/Y') }}</div>
                                <div class="small fw-medium"><span class="text-muted">Kembali:</span>
                                    {{ \Carbon\Carbon::parse($r->tanggal_kembali_aktual)->format('d/m/Y') }}</div>
                            </td>
                            <td>
                                @if ($r->tanggal_kembali_aktual > $r->tanggal_kembali_rencana)
                                    <span
                                        class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">
                                        <i class="bi bi-exclamation-circle me-1"></i> Telat
                                    </span>
                                @else
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i> Tepat
                                    </span>
                                @endif
                            </td>
                            <td><span class="small text-muted">{{ $r->petugas ? $r->petugas->name : 'Admin' }}</span></td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li>
                                            <a class="dropdown-item text-warning"
                                                href="{{ route('admin.returns.edit', $r->id) }}">
                                                <i class="bi bi-pencil-square me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider opacity-50">
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.returns.destroy', $r->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus riwayat ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data pengembalian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ==========================================
             MOBILE VIEW (KARTU)
             Muncul di HP, Sembunyi di Tablet & Desktop
        =========================================== -->
        <div class="d-block d-md-none">
            @forelse($returns as $r)
                <div class="p-3 border-bottom position-relative">
                    <!-- Badge Status di Pojok -->
                    <div class="position-absolute top-0 end-0 mt-3 me-3">
                        @if ($r->tanggal_kembali_aktual > $r->tanggal_kembali_rencana)
                            <span class="badge bg-danger">Telat</span>
                        @else
                            <span class="badge bg-success">Tepat Waktu</span>
                        @endif
                    </div>

                    <h6 class="fw-bold mb-1 text-dark">{{ $r->user->name }}</h6>
                    <p class="text-primary small mb-2 fw-medium"><i class="bi bi-tools me-1"></i> {{ $r->tool->nama_alat }}
                    </p>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="d-block small text-muted mb-0" style="font-size: 0.7rem;">Tgl Pinjam</label>
                            <span class="small">{{ \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d M Y') }}</span>
                        </div>
                        <div class="col-6">
                            <label class="d-block small text-muted mb-0" style="font-size: 0.7rem;">Tgl Kembali</label>
                            <span
                                class="small fw-bold">{{ \Carbon\Carbon::parse($r->tanggal_kembali_aktual)->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted"><i class="bi bi-person me-1"></i> Petugas:
                            {{ $r->petugas ? $r->petugas->name : 'Admin' }}</span>

                        <div class="btn-group">
                            <a href="{{ route('admin.returns.edit', $r->id) }}"
                                class="btn btn-outline-warning btn-sm py-1 px-2">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.returns.destroy', $r->id) }}" method="POST"
                                onsubmit="return confirm('Hapus riwayat ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm py-1 px-2"
                                    style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">Belum ada data pengembalian.</div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="card-footer bg-white border-0 py-3">
            {{ $returns->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        .bg-danger-subtle {
            background-color: #fee2e2 !important;
        }

        .bg-success-subtle {
            background-color: #dcfce7 !important;
        }

        /* Hover effect for desktop table */
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
            transition: 0.2s;
        }
    </style>
@endsection
