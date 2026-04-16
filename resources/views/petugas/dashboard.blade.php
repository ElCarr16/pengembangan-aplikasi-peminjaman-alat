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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold text-dark mb-0">Manajemen Peminjaman</h2>
            <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill">
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

            <div class="tab-pane fade" id="active" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-warning text-dark">
                                <tr>
                                    <th class="ps-4">Peminjam</th>
                                    <th>Alat</th>
                                    <th>Bukti Pengambilan</th>
                                    <th>Status Alat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeLoans as $loan)
                                    <tr>
                                        <td class="ps-4"><strong>{{ $loan->user->name }}</strong></td>
                                        <td>{{ $loan->tool->nama_alat }} <br><small class="text-muted">{{ $loan->jumlah }}
                                                Unit</small></td>

                                        {{-- KOLOM BUKTI PENGAMBILAN --}}
                                        <td class="ps-4">
                                            @if ($loan->gambar_pickup)
                                                <img src="{{ asset('storage/' . $loan->gambar_pickup) }}" width="80px"
                                                    class="rounded border img-thumbnail">
                                            @else
                                                <span class="text-muted small italic">Foto belum tersedia</span>
                                            @endif
                                        </td>

                                        {{-- KOLOM STATUS --}}
                                        <td>
                                            @if ($loan->is_return_requested)
                                                <span
                                                    class="badge bg-info-subtle text-info border border-info px-3 py-2 rounded-pill">Ingin
                                                    Dikembalikan</span>
                                            @elseif($loan->is_diambil)
                                                <span
                                                    class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">Sedang
                                                    Dipakai</span>
                                            @else
                                                <span
                                                    class="badge bg-warning-subtle text-dark border border-warning px-3 py-2 rounded-pill">Menunggu
                                                    Diambil</span>
                                            @endif
                                        </td>

                                        {{-- KOLOM AKSI (Membuka Modal) --}}
                                        <td class="text-center">
                                            @if ($loan->is_return_requested)
                                                <button type="button"
                                                    class="btn btn-info btn-sm text-white rounded-pill px-4 fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalReturn{{ $loan->id }}">Verifikasi
                                                    Return</button>
                                            @elseif(!$loan->is_diambil)
                                                <button type="button"
                                                    class="btn btn-warning btn-sm rounded-pill px-4 fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalPickup{{ $loan->id }}">Verifikasi
                                                    Ambil</button>
                                            @else
                                                <span class="text-muted small">Menunggu User</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Tidak ada peminjaman aktif
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
                                    <th>Bukti Pengembalian</th>
                                    <th>Denda & Catatan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($returnedLoans as $loan)
                                    <tr>
                                        <td class="ps-4">{{ $loan->user->name }}</td>
                                        <td>{{ $loan->tool->nama_alat }}</td>
                                        <td>
                                            @if ($loan->gambar_return)
                                                <img src="{{ asset('storage/' . $loan->gambar_return) }}" width="80px"
                                                    class="rounded border shadow-sm">
                                            @else
                                                <span class="text-muted small italic">Tidak ada foto</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small fw-bold {{ $loan->denda > 0 ? 'text-danger' : 'text-muted' }}">Rp {{ number_format($loan->denda, 0, ',', '.') }}</div>
                                            @if($loan->deskripsi_denda)
                                                <div class="small text-muted fst-italic mt-1" style="font-size: 0.75rem;">{{ $loan->deskripsi_denda }}</div>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-soft-success text-success px-3">Selesai</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">Tidak ada histori</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @foreach ($activeLoans as $loan)
        {{-- VERIFIKASI PENGAMBILAN --}}
        <div class="modal fade" id="modalPickup{{ $loan->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('petugas.verify_pickup', $loan->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-light border-bottom-0">
                            <h5 class="modal-title fw-bold">Bukti Pengambilan</h5><button type="button"
                                class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4 text-center">
                            <label class="form-label fw-bold small text-muted text-uppercase text-start w-100">Upload Foto
                                Bukti</label>
                            <input type="file" class="form-control" name="gambar_pickup" accept="image/*" required>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-secondary rounded-pill"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning rounded-pill fw-bold">Verifikasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- VERIFIKASI PENGEMBALIAN --}}
        <div class="modal fade" id="modalReturn{{ $loan->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('petugas.return', $loan->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-light border-bottom-0">
                            <h5 class="modal-title fw-bold">Cek Kondisi Alat</h5><button type="button" class="btn-close"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3 p-3 bg-warning-subtle rounded-3 text-center">
                                <div class="fw-bold">{{ $loan->user->name }}</div>
                                <div class="text-muted small">{{ $loan->tool->nama_alat }} ({{ $loan->jumlah }} Unit)
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Kondisi Alat Saat Ini</label>
                                <select class="form-select kondisi-select" name="kondisi"
                                    data-loan-id="{{ $loan->id }}" required>
                                    <option value="" selected disabled>-- Pilih Kondisi --</option>
                                    <option value="baik">Baik (Tanpa Denda)</option>
                                    <option value="lecet_ringan">Lecet Ringan (Rp 25.000)</option>
                                    <option value="lecet_berat">Lecet Berat (Rp 50.000)</option>
                                    <option value="rusak">Rusak (Rp 75.000)</option>
                                    <option value="mati_total">Mati Total (Rp 100.000)</option>
                                    <option value="hilang">Hilang (Rp 150.000/unit)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi_denda" class="form-label">Deskripsi Denda</label>
                                <textarea class="form-control" name="deskripsi_denda" id="deskripsi_denda" rows="3"
                                    placeholder="Tulis rincian kerusakan atau alasan denda (opsional)"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Foto Bukti Pengembalian</label>
                                <input type="file" class="form-control" name="gambar_return" accept="image/*"
                                    required>
                            </div>
                            <div class="mb-2 d-none input-hilang-container" id="inputHilang{{ $loan->id }}">
                                <label class="form-label fw-bold text-danger small">Jumlah Alat Hilang</label>
                                <input type="number" class="form-control border-danger" name="jumlah_hilang"
                                    min="1" max="{{ $loan->jumlah }}">
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-secondary rounded-pill"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning rounded-pill fw-bold">Konfirmasi
                                Selesai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

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
                        hilangInput.value = '';
                    }
                });
            });
        });
    </script>
@endsection
