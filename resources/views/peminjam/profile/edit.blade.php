@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-sm-5">

                        <!-- HEADER -->
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-dark">Lengkapi Profil Peminjam</h3>
                            <p class="text-muted small">Data ini diperlukan untuk validasi peminjaman alat konstruksi</p>
                        </div>

                        @if (session('warning'))
                            <div class="alert alert-warning border-0 rounded-3 mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
                            </div>
                        @endif

                        <form action="{{ route('peminjam.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Nama Lengkap (Read Only jika tidak ingin diubah, atau biarkan jika boleh) -->
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="name"
                                            class="form-control rounded-3 @error('name') is-invalid @enderror"
                                            id="profName" placeholder="Nama Lengkap" value="{{ old('name', $user->name) }}"
                                            required>
                                        <label for="profName">Nama Lengkap</label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nomor Telepon -->
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="nomor_telepon"
                                            class="form-control rounded-3 @error('nomor_telepon') is-invalid @enderror"
                                            id="profPhone" placeholder="0812..."
                                            value="{{ old('nomor_telepon', $user->nomor_telepon) }}" required>
                                        <label for="profPhone">Nomor Telepon (WhatsApp)</label>
                                        @error('nomor_telepon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="date" name="tanggal_lahir"
                                            class="form-control rounded-3 @error('tanggal_lahir') is-invalid @enderror"
                                            id="profDate" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}"
                                            required>
                                        <label for="profDate">Tanggal Lahir</label>
                                        @error('tanggal_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Alamat Lengkap -->
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea name="alamat" class="form-control rounded-3 @error('alamat') is-invalid @enderror" id="profAddress"
                                            placeholder="Alamat" style="height: 100px" required>{{ old('alamat', $user->alamat) }}</textarea>
                                        <label for="profAddress">Alamat Lengkap</label>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kota -->
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="kota"
                                            class="form-control rounded-3 @error('kota') is-invalid @enderror"
                                            id="profCity" placeholder="Kota" value="{{ old('kota', $user->kota) }}"
                                            required>
                                        <label for="profCity">Kota</label>
                                        @error('kota')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Provinsi -->
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="provinsi"
                                            class="form-control rounded-3 @error('provinsi') is-invalid @enderror"
                                            id="profProv" placeholder="Provinsi"
                                            value="{{ old('provinsi', $user->provinsi) }}" required>
                                        <label for="profProv">Provinsi</label>
                                        @error('provinsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kode Pos -->
                                <div class="col-md-4">
                                    <div class="form-floating mb-4">
                                        <input type="text" name="kode_pos"
                                            class="form-control rounded-3 @error('kode_pos') is-invalid @enderror"
                                            id="profZip" placeholder="Kode Pos"
                                            value="{{ old('kode_pos', $user->kode_pos) }}" required>
                                        <label for="profZip">Kode Pos</label>
                                        @error('kode_pos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- BUTTON SUBMIT -->
                            <button class="w-100 btn btn-lg btn-warning rounded-pill shadow-sm fw-bold py-3" type="submit">
                                Simpan Perubahan Profil <i class="bi bi-check-circle ms-2"></i>
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Menyesuaikan dengan UI Register Anda */
        .form-floating>.form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

        body {
            background-color: #f8f9fa;
            /* Background abu-abu muda agar card terlihat menonjol */
        }

        .card {
            border: none;
        }
    </style>
@endsection
