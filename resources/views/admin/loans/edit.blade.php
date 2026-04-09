@extends('layouts.app')

@section('content')
    <!-- Breadcrumb Navigasi -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item small"><a href="{{ route('admin.loans.index') }}"
                    class="text-decoration-none">Peminjaman</a></li>
            <li class="breadcrumb-item small active" aria-current="page">Edit Data Peminjaman</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-pencil-square fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Edit Peminjaman</h5>
                            <p class="text-muted small mb-0">ID Transaksi:
                                #TRX-{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 pt-md-3">
                    <form action="{{ route('admin.loans.update', $loan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h6 class="text-uppercase small fw-bold text-muted mb-3 pb-2 border-bottom">Detail Peminjam & Alat
                        </h6>

                        <!-- Pilihan Peminjam -->
                        <div class="form-floating mb-3">
                            <select name="user_id" class="form-select rounded-3 @error('user_id') is-invalid @enderror"
                                id="editUser" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $loan->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="editUser">Peminjam (Siswa/Karyawan)</label>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pilihan Alat -->
                        <div class="form-floating mb-4">
                            <select name="tool_id" class="form-select rounded-3 @error('tool_id') is-invalid @enderror"
                                id="editTool" required>
                                @foreach ($tools as $tool)
                                    <option value="{{ $tool->id }}"
                                        {{ old('tool_id', $loan->tool_id) == $tool->id ? 'selected' : '' }}>
                                        {{ $tool->nama_alat }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="editTool">Alat yang Dipinjam</label>
                            @error('tool_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Input Jumlah yang disesuaikan -->
                        <div class="form-floating mb-4">
                            <input type="number" name="jumlah"
                                class="form-control rounded-3 @error('jumlah') is-invalid @enderror" id="inputJumlah"
                                placeholder="1" min="1" value="{{ old('jumlah', 1) }}" required>
                            <label for="inputJumlah">Jumlah yang Dipinjam</label>
                            <div id="stokInfo" class="form-text text-primary ms-2"></div>
                            <!-- Tempat info stok real-time -->
                            @error('jumlah')
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
                                        id="editTglPinjam" value="{{ old('tanggal_pinjam', $loan->tanggal_pinjam) }}"
                                        required>
                                    <label for="editTglPinjam">Tanggal Pinjam</label>
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
                                        id="editTglRencana"
                                        value="{{ old('tanggal_kembali_rencana', $loan->tanggal_kembali_rencana) }}"
                                        required>
                                    <label for="editTglRencana">Rencana Kembali</label>
                                    @error('tanggal_kembali_rencana')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-floating mb-3">
                            <select name="status" class="form-select rounded-3 @error('status') is-invalid @enderror"
                                id="editStatus" required>
                                <option value="pending" {{ old('status', $loan->status) == 'pending' ? 'selected' : '' }}>
                                    Pending (Menunggu Persetujuan)</option>
                                <option value="disetujui"
                                    {{ old('status', $loan->status) == 'disetujui' ? 'selected' : '' }}>Disetujui (Sedang
                                    Dipinjam)</option>
                                <option value="kembali" {{ old('status', $loan->status) == 'kembali' ? 'selected' : '' }}>
                                    Sudah Kembali</option>
                                <option value="ditolak" {{ old('status', $loan->status) == 'ditolak' ? 'selected' : '' }}>
                                    Ditolak</option>
                            </select>
                            <label for="editStatus">Status Transaksi</label>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Info Alert -->
                        <div class="alert alert-warning border-0 shadow-sm rounded-3 d-flex align-items-start p-3 mb-4">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2 fs-5 lh-1"></i>
                            <div class="small text-dark">
                                <strong>Perhatian:</strong> Mengubah status dari <em>'Disetujui'</em> menjadi
                                <em>'Kembali'</em> atau <em>'Ditolak'</em> akan otomatis menyesuaikan jumlah stok alat di
                                inventaris.
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column flex-md-row gap-2 mt-5">
                            <button type="submit"
                                class="btn btn-warning btn-lg rounded-pill px-5 fw-bold order-md-2 flex-grow-1 flex-md-grow-0 shadow-sm">
                                Update Peminjaman
                            </button>
                            <a href="{{ route('admin.loans.index') }}"
                                class="btn btn-light btn-lg rounded-pill px-5 order-md-1 flex-grow-1 flex-md-grow-0 border">
                                Batal
                            </a>
                        </div>
                    </form>
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-warning-subtle {
            background-color: #fff9e6 !important;
        }

        .form-floating>.form-control:focus,
        .form-floating>.form-select:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.1);
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "›";
            font-size: 1.2rem;
            vertical-align: middle;
        }
    </style>
@endsection
<script>
    const selectTool = document.getElementById('selectTool');
    const inputJumlah = document.getElementById('inputJumlah');
    const stokInfo = document.getElementById('stokInfo');

    selectTool.addEventListener('change', function() {
        // Ambil teks dari option yang dipilih (contoh: "[Stok: 5] Palu")
        const selectedOption = this.options[this.selectedIndex];
        const text = selectedOption.text;

        // Ekstrak angka stok menggunakan Regex
        const match = text.match(/\[Stok: (\d+)\]/);

        if (match) {
            const stokTersedia = parseInt(match[1]);
            inputJumlah.max = stokTersedia; // Set batas maksimal input
            stokInfo.innerText = `Maksimal stok yang tersedia: ${stokTersedia}`;

            // Jika input saat ini melebihi stok baru, turunkan otomatis
            if (parseInt(inputJumlah.value) > stokTersedia) {
                inputJumlah.value = stokTersedia;
            }
        } else {
            stokInfo.innerText = "";
        }
    });
</script>
