@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">

        <!-- Breadcrumb Navigasi -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item small"><a href="{{ route('admin.returns.index') }}" class="text-decoration-none">Data Pengembalian</a></li>
                <li class="breadcrumb-item small active" aria-current="page">Koreksi Tanggal</li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="bi bi-calendar-event fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Koreksi Data Pengembalian</h5>
                        <p class="text-muted small mb-0">Ubah tanggal aktual jika terdapat kesalahan input admin.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5 pt-md-3">

                <!-- PANEL INFO: READ ONLY -->
                <div class="bg-light rounded-4 p-3 p-md-4 mb-4 border">
                    <h6 class="fw-bold small text-muted text-uppercase mb-3">Detail Transaksi</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <span class="d-block small text-muted mb-1"><i class="bi bi-person me-1"></i> Peminjam</span>
                            <span class="fw-bold text-dark">{{ $loan->user->name }}</span>
                        </div>
                        <div class="col-6">
                            <span class="d-block small text-muted mb-1"><i class="bi bi-tools me-1"></i> Alat</span>
                            <span class="fw-bold text-primary">{{ $loan->tool->nama_alat }}</span>
                        </div>
                        <div class="col-12 mt-3 pt-3 border-top">
                            <span class="d-block small text-muted mb-1">Jadwal Rencana Kembali</span>
                            <span class="fw-medium text-danger">
                                <i class="bi bi-clock-history me-1"></i>
                                {{ \Carbon\Carbon::parse($loan->tanggal_kembali_rencana)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- FORM KOREKSI -->
                <form action="{{ route('admin.returns.update', $loan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="text-uppercase small fw-bold text-muted mb-3 pb-2 border-bottom">Form Koreksi</h6>

                    <div class="form-floating mb-2">
                        <input type="date" name="tanggal_kembali_aktual"
                               class="form-control rounded-3 @error('tanggal_kembali_aktual') is-invalid @enderror"
                               id="tglKembali"
                               value="{{ old('tanggal_kembali_aktual', $loan->tanggal_kembali_aktual) }}" required>
                        <label for="tglKembali">Tanggal Kembali Aktual</label>
                        @error('tanggal_kembali_aktual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <p class="text-muted small mb-4">
                        <i class="bi bi-info-circle me-1"></i> Sistem akan otomatis mengkalkulasi ulang status keterlambatan (Tepat/Telat) berdasarkan tanggal baru ini.
                    </p>

                    <!-- ACTION BUTTONS -->
                    <div class="d-flex flex-column flex-md-row gap-2 mt-4">
                        <button type="submit" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold order-md-2 flex-grow-1 flex-md-grow-0 shadow-sm">
                            Simpan Koreksi
                        </button>
                        <a href="{{ route('admin.returns.index') }}" class="btn btn-light btn-lg rounded-pill px-5 order-md-1 flex-grow-1 flex-md-grow-0 border">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-warning-subtle { background-color: #fff9e6 !important; }

    /* Fokus input disesuaikan dengan warna tema warning/koreksi */
    .form-floating > .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.1);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        font-size: 1.2rem;
        vertical-align: middle;
    }
</style>
@endsection
