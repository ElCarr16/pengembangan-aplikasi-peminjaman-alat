@extends('layouts.app')

@section('content')
    <!-- Breadcrumb Navigasi -->
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard Admin</li>
        </ol>
    </nav>
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Dashboard Administrator</h3>
        <p class="text-muted small">Ringkasan statistik dan aktivitas sistem terbaru.</p>
    </div>
    {{-- NOTIFIKASI LOGIN --}}
    {{-- @if (session('Login'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('Login') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif --}}
    <!-- STATS CARDS -->
    <div class="row g-4 mb-4">
        <!-- Total Pengguna -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Total Pengguna</p>
                            <h2 class="fw-bold mb-0">{{ $totalUser }}</h2>
                            <small class="text-muted">User terdaftar</small>
                        </div>
                        <div class="bg-warning-subtle text-warning p-3 rounded-3">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}"
                    class="card-footer bg-light border-0 py-2 text-decoration-none text-center small text-warning fw-bold">
                    Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- Data Alat -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Data Alat</p>
                            <h2 class="fw-bold mb-0">
                                {{ $totalAlat }}
                                <span class="fs-6 fw-normal text-muted">({{ $totalStok }} Unit)</span>
                            </h2>
                            <small class="text-muted">Jenis alat tersedia</small>
                        </div>
                        <div class="bg-success-subtle text-success p-3 rounded-3">
                            <i class="bi bi-tools fs-3"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.tools.index') }}"
                    class="card-footer bg-light border-0 py-2 text-decoration-none text-center small text-success fw-bold">
                    Kelola Inventaris <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- Kategori -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Kategori</p>
                            <h2 class="fw-bold mb-0">{{ $totalKategori }}</h2>
                            <small class="text-muted">Grup klasifikasi alat</small>
                        </div>
                        <div class="bg-warning-subtle text-warning p-3 rounded-3">
                            <i class="bi bi-grid fs-3"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.categories.index') }}"
                    class="card-footer bg-light border-0 py-2 text-decoration-none text-center small text-warning fw-bold">
                    Lihat Kategori <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- TRANSAKSI STATUS -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-danger-subtle border-start border-danger border-4">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="flex-grow-1">
                        <h5 class="text-danger fw-bold mb-1">Sedang Dipinjam</h5>
                        <h3 class="fw-bold mb-0 text-dark">{{ $sedangDipinjam }}</h3>
                        <p class="mb-0 small text-danger opacity-75">Transaksi aktif butuh pengawasan</p>
                    </div>
                    <a href="{{ route('admin.loans.index') }}"
                        class="btn btn-danger rounded-pill px-4 btn-sm fw-bold shadow-sm">Pantau</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-info-subtle border-start border-info border-4">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="flex-grow-1">
                        <h5 class="text-info fw-bold mb-1">Sudah Dikembalikan</h5>
                        <h3 class="fw-bold mb-0 text-dark">{{ $sudahDikembalikan }}</h3>
                        <p class="mb-0 small text-info opacity-75">Transaksi selesai hari ini</p>
                    </div>
                    <a href="{{ route('admin.returns.index') }}"
                        class="btn btn-info text-white rounded-pill px-4 btn-sm fw-bold shadow-sm">Riwayat</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Pendapatan Loan --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden"
                style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                <div class="card-body p-4 d-flex justify-content-between align-items-center position-relative">
                    <i class="bi bi-cash-coin position-absolute text-white opacity-25"
                        style="font-size: 8rem; right: -20px; top: -30px; transform: rotate(-15deg);"></i>

                    <div class="position-relative z-1">
                        <p class="text-white-50 fw-bold text-uppercase mb-1" style="letter-spacing: 1px;">Total Pendapatan
                            Bersih</p>
                        <h1 class="display-6 fw-bolder text-white mb-0">
                            Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                        </h1>
                        <p class="text-white-50 small mb-0 mt-2">
                            <i class="bi bi-info-circle me-1"></i> Akumulasi dari total harga sewa dan denda transaksi yang
                            sudah selesai.
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 position-relative z-1 d-none d-md-block">
                        <i class="bi bi-wallet2 text-white fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT LOGS -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-2 text-warning"></i>Aktivitas Sistem
                        Terakhir</h5>
                    <a href="{{ url('/admin/logs') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">Lihat
                        Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-uppercase text-muted fw-bold">
                                <th class="px-4">Waktu</th>
                                <th>User</th>
                                <th>Aksi</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                                <tr>
                                    <td class="px-4">
                                        <span class="small text-muted">{{ $log->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs bg-light rounded-circle p-1 me-2 text-center"
                                                style="width: 32px">
                                                <i class="bi bi-person text-secondary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold small">{{ $log->user->name }}</div>
                                                <span class="badge bg-secondary-subtle text-secondary"
                                                    style="font-size: 0.65em">{{ strtoupper($log->user->role) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $log->action == 'delete' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning' }} px-2 py-1">
                                            {{ strtoupper($log->action) }}
                                        </span>
                                    </td>
                                    <td class="text-muted small py-3">{{ Str::limit($log->description, 60) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <img src="https://illustrations.popsy.co/gray/empty-states.svg" alt="empty"
                                            style="width: 150px" class="mb-3 opacity-50">
                                        <p class="text-muted small">Belum ada aktivitas tercatat.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Utility colors for subtle backgrounds */
        .bg-warning-subtle {
            background-color: #e7f0ff !important;
        }

        .bg-success-subtle {
            background-color: #e6f9ed !important;
        }

        .bg-warning-subtle {
            background-color: #fff9e6 !important;
        }

        .bg-danger-subtle {
            background-color: #feecef !important;
        }

        .bg-info-subtle {
            background-color: #e7f8fb !important;
        }

        .bg-secondary-subtle {
            background-color: #f1f3f5 !important;
        }

        .card-footer:hover {
            background-color: #f8fafc !important;
            opacity: 0.8;
        }
    </style>
    <script>
        setTimeout(() => {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, 3000);
    </script>
@endsection
