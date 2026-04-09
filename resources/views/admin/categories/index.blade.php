@extends('layouts.app')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Kategori Alat</h3>
            <p class="text-muted small mb-0">Kelola klasifikasi dan pengelompokan inventaris alat.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        <!-- ==========================================
             DESKTOP VIEW (TABEL)
             Sembunyi di HP, Muncul di Tablet & Desktop
        =========================================== -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr class="small text-uppercase fw-bold">
                        <th class="ps-4 py-3" width="5%">No</th>
                        <th>Nama Kategori</th>
                        <th width="20%">Jumlah Alat</th>
                        <th class="text-end pe-4" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $key => $cat)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $categories->firstItem() + $key }}</td>
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center">
                                    <i class="bi bi-tag-fill text-muted me-2 opacity-50"></i>
                                    {{ $cat->nama_kategori }}
                                </div>
                            </td>
                            <td>
                                <span
                                    class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-2 fw-medium">
                                    <i class="bi bi-box-seam me-1"></i> {{ $cat->tools_count ?? 0 }} Item
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                    <a href="{{ route('admin.categories.edit', $cat->id) }}"
                                        class="btn btn-white btn-sm border-end px-3" title="Edit">
                                        <i class="bi bi-pencil-fill text-warning"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus kategori ini? Pastikan tidak ada alat yang terikat.');">
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
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-tags fs-1 text-muted opacity-25 d-block mb-3"></i>
                                <span class="text-muted">Belum ada data kategori yang ditambahkan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ==========================================
             MOBILE VIEW (KARTU)
             Muncul di HP, Sembunyi di Tablet & Desktop
        =========================================== -->
        <div class="d-block d-md-none">
            @forelse($categories as $cat)
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">
                            <i class="bi bi-tag-fill text-muted me-1 opacity-50"></i> {{ $cat->nama_kategori }}
                        </h6>
                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1"
                            style="font-size: 0.7rem;">
                            <i class="bi bi-box-seam me-1"></i> {{ $cat->tools_count ?? 0 }} Item
                        </span>
                    </div>

                    <div class="btn-group">
                        <a href="{{ route('admin.categories.edit', $cat->id) }}"
                            class="btn btn-outline-warning btn-sm py-1 px-2">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST"
                            onsubmit="return confirm('Hapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm py-1 px-2"
                                style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">Belum ada kategori.</div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        @if ($categories->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <style>
        /* Desktop Button Group */
        .btn-group .btn-white {
            background: #fff;
            border: 1px solid #dee2e6;
        }

        .btn-group .btn-white:hover {
            background: #f8f9fa;
        }

        /* Utility Colors */
        .bg-info-subtle {
            background-color: #e0f2fe !important;
            color: #0284c7 !important;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .table-hover tbody tr:hover {
            background-color: #f8fafc;
            transition: 0.2s;
        }
    </style>
@endsection
