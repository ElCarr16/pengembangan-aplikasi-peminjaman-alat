@extends('layouts.app')

@section('content')
    <!-- Breadcrumb -->
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('welcome') }}" class="text-decoration-none">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page" href="{{ route('dashboard') }}">
                Dashboard
            </li>
        </ol>
    </nav>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold text-dark mb-0">Manajemen Peminjaman</h2>
            <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill">
                Total: {{ $loans->count() + $activeLoans->count() + $returnedLoans->count() }} Data
            </span>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-pills mb-4 bg-white p-2 shadow-sm rounded" id="loanTab" role="tablist">
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link active w-100 fw-medium" id="pending-tab" data-bs-toggle="pill"
                    data-bs-target="#pending" type="button">
                    <i class="bi bi-clock-history me-2"></i>Permintaan
                </button>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100 fw-medium" id="active-tab" data-bs-toggle="pill" data-bs-target="#active"
                    type="button">
                    <i class="bi bi-play-circle me-2"></i>Aktif
                </button>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100 fw-medium" id="history-tab" data-bs-toggle="pill" data-bs-target="#history"
                    type="button">
                    <i class="bi bi-check2-all me-2"></i>Selesai
                </button>
            </li>
        </ul>

        <div class="tab-content" id="loanTabContent">

            <!-- SECTION: PENDING -->
            <div class="tab-pane fade show active" id="pending" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Peminjam</th>
                                    <th>Alat & Durasi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loans as $loan)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $loan->user->name }}</div>
                                            <small class="text-muted small">User ID: #{{ $loan->user->id }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-primary">{{ $loan->tool->nama_alat }}</div>
                                            <small class="text-muted">{{ $loan->tanggal_pinjam }} s/d
                                                {{ $loan->tanggal_kembali_rencana }}</small>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group shadow-sm">
                                                <form action="{{ route('petugas.approve', $loan->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-success btn-sm px-3">Setujui</button>
                                                </form>
                                                <form action="{{ route('petugas.reject', $loan->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-outline-danger btn-sm px-3">Tolak</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">Belum ada permintaan masuk
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile View (Pending) -->
                    <div class="d-md-none p-3">
                        @foreach ($loans as $loan)
                            <div class="card mb-3 border rounded-3 p-3 shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0">{{ $loan->user->name }}</h6>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </div>
                                <p class="mb-1 text-primary fw-medium">{{ $loan->tool->nama_alat }}</p>
                                <p class="small text-muted mb-3"><i class="bi bi-calendar-event me-1"></i>
                                    {{ $loan->tanggal_pinjam }} - {{ $loan->tanggal_kembali_rencana }}</p>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <form action="{{ route('petugas.approve', $loan->id) }}" method="POST"> @csrf
                                            <button class="btn btn-success w-100 rounded-pill">Setujui</button>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <form action="{{ route('petugas.reject', $loan->id) }}" method="POST"> @csrf
                                            <button class="btn btn-outline-danger w-100 rounded-pill">Tolak</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- SECTION: ACTIVE -->
            <div class="tab-pane fade" id="active" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="ps-4">Peminjam</th>
                                    <th>Alat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeLoans as $loan)
                                    <tr>
                                        <td class="ps-4"><strong>{{ $loan->user->name }}</strong></td>
                                        <td>{{ $loan->tool->nama_alat }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('petugas.return', $loan->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-outline-primary btn-sm rounded-pill px-4">Kembalikan
                                                    Alat</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">Tidak ada peminjaman aktif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECTION: HISTORY -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th class="ps-4">Peminjam</th>
                                    <th>Alat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($returnedLoans as $loan)
                                    <tr>
                                        <td class="ps-4">{{ $loan->user->name }}</td>
                                        <td>{{ $loan->tool->nama_alat }}</td>
                                        <td><span class="badge bg-soft-success text-success px-3">Selesai</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        /* Custom Styling untuk UX yang lebih Modern */
        .bg-soft-primary {
            background-color: #e7f1ff;
        }

        .bg-soft-success {
            background-color: #e1f7ec;
        }

        .nav-pills .nav-link {
            color: #6c757d;
            border-radius: 8px;
            transition: 0.3s;
        }

        .nav-pills .nav-link.active {
            background-color: #0d6efd;
            color: white;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .card {
            transition: transform 0.2s;
        }

        .table thead th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
    </style>
@endsection
