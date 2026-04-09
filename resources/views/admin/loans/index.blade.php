@extends('layouts.app')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Data Peminjaman</h3>
            <p class="text-muted small mb-0">Kelola seluruh pengajuan dan status peminjaman alat.</p>
        </div>
        <a href="{{ route('admin.loans.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-plus-circle me-1"></i> Tambah Peminjaman
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
                        <th class="ps-4 py-3" width="5%">No</th>
                        <th>Peminjam & Alat</th>
                        <th>Durasi Peminjaman</th>
                        <th>Status</th>
                        <th class="text-end pe-4" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $key => $loan)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $loans->firstItem() + $key }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $loan->user->name }}</div>
                                <div class="text-primary small"><i class="bi bi-tools me-1"></i>
                                    {{ $loan->tool->nama_alat }}</div>
                            </td>
                            <td>
                                <div class="small"><span class="text-muted">Pinjam:</span>
                                    {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</div>
                                <div class="small text-danger fw-medium"><span class="text-muted">Kembali:</span>
                                    {{ \Carbon\Carbon::parse($loan->tanggal_kembali_rencana)->format('d M Y') }}</div>
                            </td>
                            <td>
                                @php
                                    $statusConfig = match ($loan->status) {
                                        'pending' => [
                                            'class' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
                                            'icon' => 'bi-hourglass-split',
                                            'label' => 'Pending',
                                        ],
                                        'disetujui' => [
                                            'class' => 'bg-primary-subtle text-primary-emphasis border-primary-subtle',
                                            'icon' => 'bi-play-circle',
                                            'label' => 'Dipinjam',
                                        ],
                                        'kembali' => [
                                            'class' => 'bg-success-subtle text-success-emphasis border-success-subtle',
                                            'icon' => 'bi-check-all',
                                            'label' => 'Kembali',
                                        ],
                                        'ditolak' => [
                                            'class' => 'bg-danger-subtle text-danger-emphasis border-danger-subtle',
                                            'icon' => 'bi-x-circle',
                                            'label' => 'Ditolak',
                                        ],
                                        default => [
                                            'class' => 'bg-secondary-subtle text-secondary',
                                            'icon' => 'bi-question-circle',
                                            'label' => 'Unknown',
                                        ],
                                    };
                                @endphp
                                <span class="badge border {{ $statusConfig['class'] }} rounded-pill px-3 py-2 fw-medium">
                                    <i class="bi {{ $statusConfig['icon'] }} me-1"></i> {{ $statusConfig['label'] }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li>
                                            <a class="dropdown-item text-warning"
                                                href="{{ route('admin.loans.edit', $loan->id) }}">
                                                <i class="bi bi-pencil-square me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider opacity-50">
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?');">
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
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-journal-x fs-1 text-muted opacity-25 d-block mb-3"></i>
                                <span class="text-muted">Tidak ada data peminjaman.</span>
                            </td>
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
            @forelse($loans as $loan)
                <div class="p-3 border-bottom position-relative">

                    @php
                        $statusConfig = match ($loan->status) {
                            'pending' => ['class' => 'bg-warning text-dark', 'label' => 'Pending'],
                            'disetujui' => ['class' => 'bg-primary', 'label' => 'Dipinjam'],
                            'kembali' => ['class' => 'bg-success', 'label' => 'Kembali'],
                            'ditolak' => ['class' => 'bg-danger', 'label' => 'Ditolak'],
                            default => ['class' => 'bg-secondary', 'label' => 'Unknown'],
                        };
                    @endphp

                    <!-- Badge Status di Pojok -->
                    <div class="position-absolute top-0 end-0 mt-3 me-3">
                        <span class="badge {{ $statusConfig['class'] }}">{{ $statusConfig['label'] }}</span>
                    </div>

                    <h6 class="fw-bold mb-1 text-dark pe-5">{{ $loan->user->name }}</h6>
                    <p class="text-primary small mb-3 fw-medium"><i class="bi bi-tools me-1"></i>
                        {{ $loan->tool->nama_alat }}</p>

                    <div class="row g-2 mb-3 bg-light p-2 rounded-3">
                        <div class="col-6">
                            <label class="d-block small text-muted mb-0" style="font-size: 0.7rem;">Pinjam</label>
                            <span
                                class="small fw-medium">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</span>
                        </div>
                        <div class="col-6 border-start">
                            <label class="d-block small text-muted mb-0" style="font-size: 0.7rem;">Rencana Kembali</label>
                            <span
                                class="small text-danger fw-bold">{{ \Carbon\Carbon::parse($loan->tanggal_kembali_rencana)->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                        <div class="btn-group">
                            <a href="{{ route('admin.loans.edit', $loan->id) }}"
                                class="btn btn-outline-warning btn-sm py-1 px-3">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm py-1 px-3"
                                    style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">Tidak ada data peminjaman.</div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        @if ($loans->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $loans->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <style>
        /* Utility colors untuk Desktop Badges */
        .bg-warning-subtle {
            background-color: #fff3cd !important;
        }

        .bg-primary-subtle {
            background-color: #cfe2ff !important;
        }

        .bg-success-subtle {
            background-color: #d1e7dd !important;
        }

        .bg-danger-subtle {
            background-color: #f8d7da !important;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .table-hover tbody tr:hover {
            background-color: #f8fafc;
            transition: 0.2s;
        }
    </style>
@endsection
