@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Form Pengembalian Alat</h4>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validasi error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('admin.returns.store') }}" method="POST">
                @csrf

                {{-- PILIH PINJAMAN --}}
                <div class="mb-3">
                    <label class="form-label">Pilih Peminjaman</label>
                    <select name="loan_id" class="form-control" required>
                        <option value="">-- Pilih Alat --</option>

                        @foreach($loans as $loan)
                            <option value="{{ $loan->id }}" {{ (isset($loanId) && $loanId == $loan->id) ? 'selected' : '' }}>
                                {{ $loan->tool->nama_alat }}
                                - {{ $loan->user->name }}
                                (Jumlah: {{ $loan->jumlah }})
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- DENDA --}}
                <div class="mb-3">
                    <label class="form-label">Denda (Opsional)</label>
                    <input type="number" name="denda" class="form-control" placeholder="Masukkan denda jika ada">
                </div>

                {{-- SUBMIT --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.returns.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Proses Pengembalian
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
