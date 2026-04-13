@extends('layouts.app')

@section('content')
    <!-- Breadcrumb Navigasi -->
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Inventaris</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Inventaris Alat</h3>
            <p class="text-muted small mb-0">Total {{ $tools->total() }} jenis alat terdaftar di sistem.</p>
        </div>
        <a href="{{ route('admin.tools.create') }}" class="btn btn-warning rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-circle-fill me-2"></i>Tambah Alat Baru
        </a>
    </div>

    {{-- DATA TABLE --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small fw-bold text-uppercase">
                        <th class="ps-4 py-3" width="5%">No</th>
                        <th width="10%">Visual</th>
                        <th>Detail Alat</th>
                        <th>Kategori</th>
                        <th>Status Stok</th>
                        <th>Harga Perhari</th>
                        <th class="text-end pe-4" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tools as $key => $tool)
                        <tr>
                            <td class="ps-4 text-muted small">
                                {{ $tools->firstItem() + $key }}
                            </td>
                            {{-- menampilkan gambar alat --}}
                            <td>
                                <div class="rounded-3 overflow-hidden border shadow-sm bg-white"
                                    style="width: 60px; height: 60px;">
                                    @if ($tool->gambar)
                                        <img src="{{ asset('storage/' . $tool->gambar) }}" alt="img"
                                            class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted"
                                            style="font-size: 0.7rem;">
                                            NO IMG
                                        </div>
                                    @endif
                                </div>
                            </td>
                            {{-- deskripsi --}}
                            <td>
                                <div class="fw-bold text-dark mb-0">{{ $tool->nama_alat }}</div>
                                <div class="text-muted small text-truncate" style="max-width: 250px;"
                                    title="{{ $tool->deskripsi }}">
                                    {{ $tool->deskripsi ?? 'Tidak ada deskripsi.' }}
                                </div>
                            </td>
                            {{-- kategori --}}
                            <td>
                                <span
                                    class="badge bg-secondary-subtle text-secondary border px-3 py-2 rounded-pill fw-medium">
                                    <i class="bi bi-tag-fill me-1"></i> {{ $tool->category->nama_kategori }}
                                </span>
                            </td>
                            {{-- stok --}}
                            <td>
                                @php
                                    $stockStatus = 'text-dark';
                                    if ($tool->stok <= 0) {
                                        $stockStatus = 'text-danger fw-bold';
                                    } elseif ($tool->stok <= 5) {
                                        $stockStatus = 'text-warning fw-bold';
                                    }
                                @endphp
                                <div class="{{ $stockStatus }}">
                                    {{ $tool->stok }} <small>Unit</small>
                                </div>
                                @if ($tool->stok <= 5)
                                    <span class="badge bg-danger p-1 rounded-circle" title="Stok Menipis"></span>
                                @endif
                            </td>
                            {{-- harga sewa barang --}}
                            <td class="text-end pe-4">
                                <div class="fw-bold text-dark mb-0">Rp. {{ $tool->harga_perhari }}</div>
                                <div class="text-muted small text-truncate" style="max-width: 250px;"
                                    title="{{ $tool->Harga_perhari }}">
                                    {{ $tool->Harga ?? 'perhari' }}
                                </div>
                            </td>
                            {{-- aksi update atau delete --}}
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                    <a href="{{ route('admin.tools.edit', $tool->id) }}"
                                        class="btn btn-white btn-sm border-end px-3" title="Edit">
                                        <i class="bi bi-pencil-fill text-warning"></i>
                                    </a>
                                    <form action="{{ route('admin.tools.destroy', $tool->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus alat ini? Data peminjaman terkait mungkin akan terdampak.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-white btn-sm px-3" title="Hapus">
                                            <i class="bi bi-trash-fill text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-tools fs-1 text-muted opacity-25 d-block mb-3"></i>
                                <span class="text-muted">Belum ada data alat yang tersedia.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white py-3 border-0">
            {{ $tools->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        .bg-secondary-subtle {
            background-color: #f8f9fa !important;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .table> :not(caption)>*>* {
            padding: 1.2rem 0.75rem;
        }

        .btn-group .btn-white {
            background: #fff;
            border: 1px solid #dee2e6;
        }

        .btn-group .btn-white:hover {
            background: #f8f9fa;
        }
    </style>
@endsection
