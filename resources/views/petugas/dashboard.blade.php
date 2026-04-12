@extends('layouts.app')

@section('content')
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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold text-dark mb-0">Manajemen Peminjaman</h2>
            <span class="badge bg-soft-warning text-warning px-3 py-2 rounded-pill">
                Total: {{ $loans->count() + $activeLoans->count() + $returnedLoans->count() }} Data
            </span>
        </div>

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
                                            <div class="fw-semibold text-warning">{{ $loan->tool->nama_alat }}</div>
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

                    <div class="d-md-none p-3">
                        @foreach ($loans as $loan)
                            <div class="card mb-3 border rounded-3 p-3 shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0">{{ $loan->user->name }}</h6>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </div>
                                <p class="mb-1 text-warning fw-medium">{{ $loan->tool->nama_alat }}</p>
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

            <div class="tab-pane fade" id="active" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-warning text-dark">
                                <tr>
                                    <th class="ps-4">Peminjam</th>
                                    <th>Alat</th>
                                    <th>Status Alat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeLoans as $loan)
                                    <tr>
                                        <td class="ps-4"><strong>{{ $loan->user->name }}</strong></td>
                                        <td>
                                            {{ $loan->tool->nama_alat }} <br>
                                            <small class="text-muted">{{ $loan->jumlah }} Unit</small>
                                        </td>
                                        <td>
                                            @if ($loan->is_return_requested)
                                                <span
                                                    class="badge bg-info-subtle text-info border border-info px-3 py-2 rounded-pill">
                                                    <i class="bi bi-arrow-return-left me-1"></i> Ingin Dikembalikan
                                                </span>
                                            @elseif($loan->is_diambil)
                                                <span
                                                    class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">
                                                    <i class="bi bi-check-circle me-1"></i> Sedang Dipakai
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-warning-subtle text-dark border border-warning px-3 py-2 rounded-pill">
                                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Diambil
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($loan->is_return_requested)
                                                <button type="button"
                                                    class="btn btn-info btn-sm text-white rounded-pill px-4 fw-bold shadow-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalReturn{{ $loan->id }}">
                                                    Verifikasi Pengembalian
                                                </button>

                                                <div class="modal fade text-start" id="modalReturn{{ $loan->id }}"
                                                    tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content border-0 shadow">
                                                            <form action="{{ route('petugas.return', $loan->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-header bg-light border-bottom-0">
                                                                    <h5 class="modal-title fw-bold"><i
                                                                            class="bi bi-clipboard-check me-2"></i>Cek
                                                                        Kondisi Alat</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body p-4">
                                                                    <div class="mb-3 p-3 bg-warning-subtle rounded-3">
                                                                        <small
                                                                            class="d-block text-muted mb-1">Peminjam:</small>
                                                                        <div class="fw-bold">{{ $loan->user->name }}</div>
                                                                        <hr class="my-2 border-warning opacity-25">
                                                                        <small class="d-block text-muted mb-1">Alat
                                                                            Dirental:</small>
                                                                        <div class="fw-bold">{{ $loan->tool->nama_alat }}
                                                                            <span
                                                                                class="text-warning">({{ $loan->jumlah }}
                                                                                Unit)</span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label
                                                                            class="form-label fw-bold small text-uppercase text-muted">Kondisi
                                                                            Alat Saat Dikembalikan</label>
                                                                        <select
                                                                            class="form-select border-2 shadow-sm kondisi-select"
                                                                            name="kondisi"
                                                                            data-loan-id="{{ $loan->id }}" required>
                                                                            <option value="" selected disabled>--
                                                                                Pilih Kondisi Alat --</option>
                                                                            <option value="baik">Baik / Normal (Tanpa
                                                                                Denda)</option>
                                                                            <option value="lecet_ringan">Lecet Ringan
                                                                                (Denda Rp 25.000)
                                                                            </option>
                                                                            <option value="lecet_berat">Lecet Berat (Denda
                                                                                Rp 50.000)</option>
                                                                            <option value="rusak">Rusak (Denda Rp 75.000)
                                                                            </option>
                                                                            <option value="mati_total">Mati Total (Denda Rp
                                                                                100.000)</option>
                                                                            <option value="hilang">Hilang (Denda Rp 150.000
                                                                                / unit)</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="mb-2 d-none input-hilang-container"
                                                                        id="inputHilang{{ $loan->id }}">
                                                                        <label
                                                                            class="form-label fw-bold text-danger small text-uppercase">Jumlah
                                                                            Alat Hilang</label>
                                                                        <div class="input-group shadow-sm">
                                                                            <input type="number"
                                                                                class="form-control border-danger"
                                                                                name="jumlah_hilang" min="1"
                                                                                max="{{ $loan->jumlah }}"
                                                                                placeholder="Maks: {{ $loan->jumlah }} unit">
                                                                            <span
                                                                                class="input-group-text bg-danger text-white border-danger">Unit</span>
                                                                        </div>
                                                                        <small class="text-muted d-block mt-1"><i
                                                                                class="bi bi-info-circle me-1"></i>Stok
                                                                            hanya akan dikembalikan sejumlah: (Total Pinjam
                                                                            - Total Hilang).</small>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-top-0 bg-light">
                                                                    <button type="button"
                                                                        class="btn btn-secondary rounded-pill px-4"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-warning rounded-pill px-4 fw-bold">Konfirmasi
                                                                        Selesai</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($loan->is_diambil)
                                                <span class="text-muted small fw-medium"><i
                                                        class="bi bi-clock-history me-1"></i> Menunggu User</span>
                                            @else
                                                <form action="{{ route('petugas.verify_pickup', $loan->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button
                                                        class="btn btn-warning btn-sm rounded-pill px-4 fw-bold shadow-sm">Verifikasi
                                                        Pengambilan</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">Tidak ada peminjaman aktif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

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
                                @forelse ($returnedLoans as $loan)
                                    <tr>
                                        <td class="ps-4">{{ $loan->user->name }}</td>
                                        <td>{{ $loan->tool->nama_alat }}</td>
                                        <td><span class="badge bg-soft-success text-success px-3">Selesai</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">Tidak ada histori
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        /* Custom Styling untuk UX yang lebih Modern */
        .bg-soft-warning {
            background-color: #fff3cd;
        }

        .bg-soft-success {
            background-color: #e1f7ec;
        }

        .bg-success-subtle {
            background-color: #d1e7dd !important;
        }

        .bg-warning-subtle {
            background-color: #fff3cd !important;
        }

        /* Tambahan untuk warna Info (Return Requested) */
        .bg-info-subtle {
            background-color: #cff4fc !important;
        }

        .text-info {
            color: #055160 !important;
        }

        .border-info {
            border-color: #9eeaf9 !important;
        }

        .nav-pills .nav-link {
            color: #6c757d;
            border-radius: 8px;
            transition: 0.3s;
        }

        .nav-pills .nav-link.active {
            background-color: #ffc107;
            color: #000;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kondisiSelects = document.querySelectorAll('.kondisi-select');

            kondisiSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const loanId = this.getAttribute('data-loan-id');
                    const hilangContainer = document.getElementById('inputHilang' + loanId);
                    const hilangInput = hilangContainer.querySelector('input');

                    if (this.value === 'hilang') {
                        hilangContainer.classList.remove('d-none');
                        hilangInput.setAttribute('required', 'required');
                    } else {
                        hilangContainer.classList.add('d-none');
                        hilangInput.removeAttribute('required');
                        hilangInput.value = ''; // Reset nilai
                    }
                });
            });
        });
    </script>
@endsection
