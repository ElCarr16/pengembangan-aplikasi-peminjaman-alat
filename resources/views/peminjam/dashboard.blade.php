@extends('layouts.app')

@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp

    <h3>Daftar Alat Tersedia</h3>

    <div class="row mt-4">
        @foreach ($tools as $tool)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">

                    <img src="{{ $tool->gambar ? asset('storage/' . $tool->gambar) : 'https://via.placeholder.com/300' }}"
                        class="card-img-top" style="height:200px; object-fit: contain;">

                    <div class="card-body">
                        <h5 class="card-title">{{ $tool->nama_alat }}</h5>

                        <span class="badge bg-secondary mb-2">
                            {{ $tool->category->nama_kategori }}
                        </span>

                        <p class="card-text text-muted">
                            {{ Str::limit($tool->deskripsi, 80) }}
                        </p>

                        <p class="fw-bold">Sisa Stok: {{ $tool->stok }}</p>

                        @if ($tool->stok > 0)
                            <a href="{{ route('peminjam.tools.show', $tool->id) }}" class="btn btn-primary w-100">
                                Pinjam Alat
                            </a>
                        @else
                            <button class="btn btn-secondary w-100" disabled>
                                Stok Habis
                            </button>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
