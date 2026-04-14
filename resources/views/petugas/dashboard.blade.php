@extends('layouts.app')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
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

            {{-- TAB PERMINTAAN (PENDING) --}}
            <div class="tab-pane fade show active" id="pending" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Peminjam</th>
                                    <th>Alat & Durasi</th>
                                    <th>Total Harga</th>
                                    <th class="text-center">Keterangan</th>
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
                                        <td>
                                            <div class="fw-bold text-success">Rp
                                                {{ number_format($loan->total_harga, 0, ',', '.') }}</div>
                                            <small class="text-muted">{{ $loan->jumlah }} Unit</small>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                                data-bs-toggle="modal" data-bs-target="#modalDetail{{ $loan->id }}">
                                                <i class="bi bi-card-text"></i> Cek Detail
                                            </a>
                                            <div class="modal fade" id="modalDetail{{ $loan->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header bg-light border-0">
                                                            <h5 class="modal-title fw-bold"><i
                                                                    class="bi bi-info-circle text-primary me-2"></i>Detail
                                                                Pinjaman</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-start p-4">
                                                            <ul class="list-group list-group-flush mb-0">
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                    <span class="text-muted">Nama Alat</span>
                                                                    <span
                                                                        class="fw-bold">{{ $loan->tool->nama_alat }}</span>
                                                                </li>
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                    <span class="text-muted">Jumlah Pinjam</span>
                                                                    <span class="fw-bold">{{ $loan->jumlah }}
                                                                        Unit</span>
                                                                </li>
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                    <span class="text-muted">Tanggal Pinjam</span>
                                                                    <span
                                                                        class="fw-bold">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('d M Y') }}</span>
                                                                </li>
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                    <span class="text-muted">Rencana Kembali</span>
                                                                    <span
                                                                        class="fw-bold">{{ \Carbon\Carbon::parse($loan->tanggal_kembali_rencana)->translatedFormat('d M Y') }}</span>
                                                                </li>
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                    <span
                                                                        class="text-muted">{{ $loan->status == 'kembali' ? 'Total Harga Sewa' : 'Estimasi Harga Sewa' }}</span>
                                                                    <span class="fw-bold text-success">
                                                                        Rp
                                                                        {{ number_format($loan->total_harga, 0, ',', '.') }}
                                                                    </span>
                                                                </li>
                                                                @if ($loan->status == 'kembali')
                                                                    <li
                                                                        class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                        <span class="text-muted">Dikembalikan
                                                                            Pada</span>
                                                                        <span
                                                                            class="fw-bold text-success">{{ \Carbon\Carbon::parse($loan->tanggal_kembali_aktual)->translatedFormat('d M Y') }}</span>
                                                                    </li>

                                                                    @if ($loan->denda > 0)
                                                                        <li
                                                                            class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                            <span class="text-muted">Denda</span>
                                                                            <span class="fw-bold text-danger">Rp
                                                                                {{ number_format($loan->denda, 0, ',', '.') }}</span>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                    <span class="text-muted">Status</span>
                                                                    <span
                                                                        class="badge bg-{{ $loan->status == 'kembali' ? 'success' : ($loan->status == 'disetujui' ? 'primary' : ($loan->status == 'ditolak' ? 'danger' : 'warning')) }} text-uppercase">{{ $loan->status }}</span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="modal-footer border-0 bg-light">
                                                            <button type="button"
                                                                class="btn btn-secondary rounded-pill px-4"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada permintaan masuk
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- MOBILE VIEW PENDING --}}
                    <div class="d-md-none p-3">
                        @foreach ($loans as $loan)
                            <div class="card mb-3 border rounded-3 p-3 shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0">{{ $loan->user->name }}</h6>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </div>
                                <p class="mb-1 text-primary fw-medium">{{ $loan->tool->nama_alat }} ({{ $loan->jumlah }}
                                    Unit)</p>
                                <p class="small text-muted mb-1"><i class="bi bi-calendar-event me-1"></i>
                                    {{ $loan->tanggal_pinjam }} - {{ $loan->tanggal_kembali_rencana }}</p>
                                <p class="small text-success fw-bold mb-3"><i class="bi bi-cash me-1"></i>
                                    Rp {{ number_format($loan->total_harga, 0, ',', '.') }}</p>
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

            {{-- TAB AKTIF --}}
            <div class="tab-pane fade" id="active" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-warning text-dark">
                                <tr>
                                    <th class="ps-4">Peminjam</th>
                                    <th>Alat</th>
                                    <th>Total Harga</th>
                                    <th>Bukti Pengambilan</th>
                                    <th>Status Alat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeLoans as $loan)
                                    <tr>
                                        <td class="ps-4"><strong>{{ $loan->user->name }}</strong></td>
                                        <td>{{ $loan->tool->nama_alat }} <br><small
                                                class="text-muted">{{ $loan->jumlah }}
                                                Unit</small></td>

                                        <td>
                                            <div class="fw-bold text-success">Rp
                                                {{ number_format($loan->total_harga, 0, ',', '.') }}</div>
                                        </td>

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
                                        <td colspan="6" class="text-center py-5 text-muted">Tidak ada peminjaman aktif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TAB SELESAI (HISTORY) --}}
            {{-- TAB SELESAI (HISTORY) --}}
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th class="ps-4">Peminjam</th>
                                    <th>Alat</th>
                                    <th>Total Tagihan</th>
                                    <th>Bukti Pengembalian</th>
                                    <th>Denda & Catatan</th>
                                    <th class="text-center">Status & Aksi</th> {{-- Header diubah --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($returnedLoans as $loan)
                                    <tr>
                                        <td class="ps-4">{{ $loan->user->name }}</td>
                                        <td>{{ $loan->tool->nama_alat }} <br><small
                                                class="text-muted">{{ $loan->jumlah }} Unit</small></td>

                                        <td>
                                            <div class="fw-bold text-success">Rp
                                                {{ number_format($loan->total_harga + $loan->denda, 0, ',', '.') }}</div>
                                            <small class="text-muted">Sewa: Rp
                                                {{ number_format($loan->total_harga, 0, ',', '.') }}</small>
                                        </td>

                                        <td>
                                            @if ($loan->gambar_return)
                                                <img src="{{ asset('storage/' . $loan->gambar_return) }}" width="80px"
                                                    class="rounded border shadow-sm">
                                            @else
                                                <span class="text-muted small italic">Tidak ada foto</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div
                                                class="small fw-bold {{ $loan->denda > 0 ? 'text-danger' : 'text-muted' }}">
                                                Rp {{ number_format($loan->denda, 0, ',', '.') }}</div>
                                            @if ($loan->deskripsi_denda)
                                                <div class="small text-muted fst-italic mt-1" style="font-size: 0.75rem;">
                                                    {{ $loan->deskripsi_denda }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center"> {{-- Tombol Cetak ditambahkan disini --}}
                                            <span
                                                class="badge bg-soft-success text-success px-3 mb-2 d-inline-block">Selesai</span>
                                            <a href="{{ route('petugas.petugas.print_struk', $loan->id) }}" target="_blank"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-3 d-block">
                                                <i class="bi bi-printer"></i> Cetak Struk
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Tidak ada histori</td>
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
                    <form action="{{ route('petugas.return', $loan->id) }}" method="POST" enctype="multipart/form-data"
                        id="formReturn{{ $loan->id }}">
                        @csrf
                        <div class="modal-header bg-light border-bottom-0">
                            <h5 class="modal-title fw-bold">Cek Kondisi Alat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                                    id="kondisi{{ $loan->id }}" onchange="hitungSemua({{ $loan->id }})"
                                    required>
                                    <option value="" selected disabled>-- Pilih Kondisi --</option>
                                    <option value="baik" data-harga="0">Baik (Tanpa Denda)</option>
                                    <option value="lecet_ringan" data-harga="25000">Lecet Ringan (Rp 25.000)</option>
                                    <option value="lecet_berat" data-harga="50000">Lecet Berat (Rp 50.000)</option>
                                    <option value="rusak" data-harga="75000">Rusak (Rp 75.000)</option>
                                    <option value="mati_total" data-harga="100000">Mati Total (Rp 100.000)</option>
                                    <option value="hilang" data-harga="150000">Hilang (Rp 150.000/unit)</option>
                                </select>
                            </div>

                            <div class="mb-2 d-none input-hilang-container" id="inputHilang{{ $loan->id }}">
                                <label class="form-label fw-bold text-danger small">Jumlah Alat Hilang</label>
                                <input type="number" class="form-control border-danger" name="jumlah_hilang"
                                    id="jumlahHilang{{ $loan->id }}" oninput="hitungSemua({{ $loan->id }})"
                                    min="1" max="{{ $loan->jumlah }}" value="1">
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi_denda" class="form-label">Deskripsi Denda</label>
                                <textarea class="form-control" name="deskripsi_denda" rows="2" placeholder="Rincian kerusakan..."></textarea>
                            </div>

                            <hr>

                            @php
                                // Hitung durasi sewa sampai hari ini
                                $durasiSewa = max(\Carbon\Carbon::parse($loan->tanggal_pinjam)->diffInDays(now()), 1);
                                // Hitung biaya sewa dinamis
                                $biayaSewa = $loan->tool->harga_perhari * $loan->jumlah * $durasiSewa;
                                // Jika di database sudah ada total_harga, pakai itu. Jika 0/null, pakai biayaSewa.
                                $totalSewaFinal = $loan->total_harga > 0 ? $loan->total_harga : $biayaSewa;
                            @endphp

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Total Biaya Sewa</label>
                                    <div class="input-group">
                                        <span class="input-group-text small">Rp</span>
                                        <input type="number" class="form-control bg-light fw-bold"
                                            id="totalSewa{{ $loan->id }}" value="{{ $totalSewaFinal }}" readonly>
                                    </div>
                                    <small class="text-muted">* {{ $loan->jumlah }} unit x {{ $durasiSewa }}
                                        hari</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Total Denda</label>
                                    <div class="input-group">
                                        <span class="input-group-text small">Rp</span>
                                        <input type="number" class="form-control bg-light fw-bold" name="total_denda"
                                            id="totalDenda{{ $loan->id }}" readonly value="0">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="p-2 bg-primary-subtle rounded-3 text-center border border-primary-subtle">
                                        <label class="form-label fw-bold mb-0">Total Tagihan (Sewa + Denda)</label>
                                        <h4 class="fw-bold text-primary mb-0">Rp <span
                                                id="textGrandTotal{{ $loan->id }}">{{ number_format($totalSewaFinal, 0, ',', '.') }}</span>
                                        </h4>
                                        <input type="hidden" id="grandTotal{{ $loan->id }}"
                                            value="{{ $totalSewaFinal }}">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Uang Bayar (Rp)</label>
                                    <input type="number" class="form-control border-primary form-control-lg"
                                        name="bayar" id="uangBayar{{ $loan->id }}"
                                        oninput="hitungKembalian({{ $loan->id }})" placeholder="0">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Uang Kembali (Rp)</label>
                                    <input type="number"
                                        class="form-control bg-light form-control-lg fw-bold text-success"
                                        id="uangKembali{{ $loan->id }}" readonly value="0">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Foto Bukti Pengembalian</label>
                                <input type="file" class="form-control" name="gambar_return" accept="image/*"
                                    required>
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
                    const loanId = this.id.replace('kondisi', ''); // Ambil ID dari elemen
                    const hilangContainer = document.getElementById('inputHilang' + loanId);
                    const hilangInput = document.getElementById('jumlahHilang' + loanId);

                    if (this.value === 'hilang') {
                        hilangContainer.classList.remove('d-none');
                        hilangInput.setAttribute('required', 'required');
                    } else {
                        hilangContainer.classList.add('d-none');
                        hilangInput.removeAttribute('required');
                        hilangInput.value = '1'; // Reset value
                    }
                });
            });
        });

        function hitungSemua(id) {
            const selectKondisi = document.getElementById('kondisi' + id);
            const inputJumlahHilang = document.getElementById('jumlahHilang' + id);
            const inputTotalDenda = document.getElementById('totalDenda' + id);
            const inputTotalSewa = document.getElementById('totalSewa' + id);
            const textGrandTotal = document.getElementById('textGrandTotal' + id);
            const hiddenGrandTotal = document.getElementById('grandTotal' + id);

            // Ambil harga dari atribut data-harga
            let hargaDenda = parseInt(selectKondisi.options[selectKondisi.selectedIndex].getAttribute('data-harga')) || 0;
            let kondisi = selectKondisi.value;
            let totalDenda = 0;

            // Kalkulasi denda
            if (kondisi === 'hilang') {
                let jumlahHilang = parseInt(inputJumlahHilang.value) || 1;
                totalDenda = hargaDenda * jumlahHilang;
            } else {
                totalDenda = hargaDenda;
            }

            // Tampilkan Total Denda
            inputTotalDenda.value = totalDenda;

            // Kalkulasi Grand Total (Sewa + Denda)
            let totalSewa = parseInt(inputTotalSewa.value) || 0;
            let grandTotal = totalSewa + totalDenda;

            // Update UI Grand Total
            textGrandTotal.innerText = new Intl.NumberFormat('id-ID').format(grandTotal);
            hiddenGrandTotal.value = grandTotal;

            // Trigger hitung kembalian setiap kali kondisi/denda berubah
            hitungKembalian(id);
        }

        function hitungKembalian(id) {
            // Gunakan Grand Total (Sewa + Denda) untuk perhitungan kembalian
            const grandTotal = parseInt(document.getElementById('grandTotal' + id).value) || 0;
            const bayar = parseInt(document.getElementById('uangBayar' + id).value) || 0;
            const inputKembali = document.getElementById('uangKembali' + id);
            const inputBayar = document.getElementById('uangBayar' + id);

            let kembali = bayar - grandTotal;

            if (kembali < 0 && bayar > 0) {
                inputBayar.classList.add('is-invalid');
                inputKembali.value = 0;
            } else {
                inputBayar.classList.remove('is-invalid');
                inputKembali.value = kembali < 0 ? 0 : kembali;
            }
        }
    </script>
@endsection
