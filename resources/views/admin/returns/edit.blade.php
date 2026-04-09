@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h4 class="fw-bold mb-3">Koreksi Data Pengembalian</h4>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 rounded-4 small">
                        <strong>Peminjam:</strong> {{ $loan->user->name }} <br>
                        <strong>Alat:</strong> {{ $loan->tool->nama_alat }}
                    </div>

                    <form action="{{ route('admin.returns.update', $loan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold">Tanggal Kembali Aktual</label>
                            <input type="date" name="tanggal_kembali_aktual"
                                class="form-control @error('tanggal_kembali_aktual') is-invalid @enderror"
                                value="{{ old('tanggal_kembali_aktual', \Carbon\Carbon::parse($loan->tanggal_kembali_aktual)->format('Y-m-d')) }}"
                                required>
                            @error('tanggal_kembali_aktual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg rounded-pill fw-bold">Simpan
                                Perubahan</button>
                            <a href="{{ route('admin.returns.index') }}" class="btn btn-light btn-lg rounded-pill">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
