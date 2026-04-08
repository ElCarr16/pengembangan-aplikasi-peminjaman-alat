@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">

            {{-- GAMBAR --}}
            <div class="col-md-5">
                <img src="{{ asset('storage/' . $tool->gambar) }}" class="img-fluid rounded shadow-sm">
            </div>

            {{-- DETAIL --}}
            <div class="col-md-7">
                <h4>{{ $tool->nama_alat }}</h4>

                <span class="badge bg-secondary">
                    {{ $tool->category->nama_kategori }}
                </span>

                <p class="mt-3">{{ $tool->deskripsi }}</p>

                <h5 class="text-success">Stok: {{ $tool->stok }}</h5>

                <hr>

                {{-- FORM PINJAM --}}
                <form action="{{ route('peminjam.ajukan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tool_id" value="{{ $tool->id }}">

                    <div class="mb-3">
                        <label>Tanggal Kembali</label>
                        <input type="date" name="tgl_kembali" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Jumlah Pinjam</label>
                        <input type="number" name="jumlah" class="form-control" min="1" max="{{ $tool->stok }}"
                            required>
                    </div>
                    <button class="btn btn-success w-100">
                        Ajukan Peminjaman
                    </button>
                    @if ($tool->stok == 0)
                        <button class="btn btn-secondary w-100" disabled>Stok Habis</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
