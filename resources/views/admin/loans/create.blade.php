@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <!-- Breadcrumb Navigasi -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item small"><a href="{{ route('admin.loans.index') }}"
                            class="text-decoration-none">Peminjaman</a></li>
                    <li class="breadcrumb-item small active" aria-current="page">Tambah Manual</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-journal-plus fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Input Peminjaman Manual</h5>
                            <p class="text-muted small mb-0">Catat transaksi peminjaman langsung dari meja admin.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 pt-md-3">
                    <form action="{{ route('admin.loans.store') }}" method="POST">
                        @csrf

                        <h6 class="text-uppercase small fw-bold text-muted mb-3 pb-2 border-bottom">Detail Peminjam & Alat
                        </h6>

                        <!-- Pilihan Peminjam -->
                        <div class="form-floating mb-3">
                            <select name="user_id" class="form-select rounded-3 @error('user_id') is-invalid @enderror"
                                id="selectUser" required>
                                <option value="" hidden>-- Pilih Nama Peminjam --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="selectUser">Peminjam (Siswa/Karyawan)</label>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pilihan Alat -->
                        <div class="form-floating mb-4">
                            <select name="tool_id" class="form-select rounded-3 @error('tool_id') is-invalid @enderror"
                                id="selectTool" required>
                                <option value="" hidden>-- Pilih Alat yang Tersedia --</option>
                                @foreach ($tools as $tool)
                                    <option value="{{ $tool->id }}" {{ old('tool_id') == $tool->id ? 'selected' : '' }}
                                        {{ $tool->stok <= 0 ? 'disabled' : '' }}> <!-- Disable jika stok habis -->
                                        {{ $tool->stok <= 0 ? '[HABIS] ' : '[Stok: ' . $tool->stok . '] ' }}
                                        {{ $tool->nama_alat }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="selectTool">Alat yang Dipinjam</label>
                            @error('tool_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h6 class="text-uppercase small fw-bold text-muted mb-3 pb-2 border-bottom mt-5">Durasi & Status
                        </h6>

                        <div class="row g-3 mb-3">
                            <!-- Tanggal Pinjam -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" name="tanggal_pinjam"
                                        class="form-control rounded-3 @error('tanggal_pinjam') is-invalid @enderror"
                                        id="tglPinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                                    <label for="tglPinjam">Tanggal Pinjam</label>
                                    @error('tanggal_pinjam')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Rencana Kembali -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" name="tanggal_kembali_rencana"
                                        class="form-control rounded-3 @error('tanggal_kembali_rencana') is-invalid @enderror"
                                        id="tglRencana" value="{{ old('tanggal_kembali_rencana') }}"
                                        min="{{ date('Y-m-d') }}" required> <!-- Validasi min date -->
                                    <label for="tglRencana">Rencana Kembali</label>
                                    @error('tanggal_kembali_rencana')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status Awal -->
                        <div class="form-floating mb-4">
                            <select name="status" class="form-select rounded-3 @error('status') is-invalid @enderror"
                                id="selectStatus" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending
                                    (Menunggu Persetujuan)</option>
                                <option value="disetujui"
                                    {{ old('status', 'disetujui') == 'disetujui' ? 'selected' : '' }}>Disetujui (Langsung
                                    Bawa)</option>
                                <option value="kembali" {{ old('status') == 'kembali' ? 'selected' : '' }}>Sudah Kembali
                                    (Hanya Catat Riwayat)</option>
                            </select>
                            <label for="selectStatus">Status Transaksi Awal</label>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column flex-md-row gap-2 mt-5">
                            <button type="submit"
                                class="btn btn-primary btn-lg rounded-pill px-5 fw-bold order-md-2 flex-grow-1 flex-md-grow-0 shadow-sm">
                                Simpan Data
                            </button>
                            <a href="{{ route('admin.loans.index') }}"
                                class="btn btn-light btn-lg rounded-pill px-5 order-md-1 flex-grow-1 flex-md-grow-0 border">
                                Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-primary-subtle {
            background-color: #e7f0ff !important;
        }

        .form-floating>.form-control:focus,
        .form-floating>.form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "›";
            font-size: 1.2rem;
            vertical-align: middle;
        }
    </style>
@endsection
