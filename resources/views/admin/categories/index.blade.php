@extends('layouts.app')

@section('content')
    <!-- Breadcrumb Navigasi -->
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard
                    Admin</a></li>
            <li class="breadcrumb-item active">Daftar Kategori</li>
        </ol>
    </nav>

    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Kategori Alat</h3>
            <p class="text-muted small mb-0">Kelola klasifikasi dan pengelompokan inventaris alat.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-warning rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>

    <!-- NOTIFIKASI ALERT -->
    {{-- @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error') || $errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') ?? 'Terjadi kesalahan pada input data.' }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        <!-- DESKTOP VIEW -->
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
                                    <!-- Tombol Pemicu Modal Delete -->
                                    <button type="button" class="btn btn-white btn-sm px-3" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $cat->id }}" title="Hapus">
                                        <i class="bi bi-trash-fill text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-tags fs-1 text-muted opacity-25 d-block mb-3"></i>
                                <span class="text-muted">Belum ada data kategori.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- MOBILE VIEW -->
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
                    <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                        <a href="{{ route('admin.categories.edit', $cat->id) }}"
                            class="btn btn-white btn-sm px-2 border-end">
                            <i class="bi bi-pencil-fill text-warning"></i>
                        </a>
                        <button type="button" class="btn btn-white btn-sm px-2" data-bs-toggle="modal"
                            data-bs-target="#deleteModal{{ $cat->id }}">
                            <i class="bi bi-trash-fill text-danger"></i>
                        </button>
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

    <!---->
    @foreach ($categories as $cat)
        <div class="modal fade" id="deleteModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Hapus Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body py-4">
                            <p>Kategori <strong>{{ $cat->nama_kategori }}</strong> memiliki
                                <strong>{{ $cat->tools_count }}</strong> alat.
                            </p>

                            @if ($cat->tools_count > 0)
                                <div class="bg-light p-3 rounded-3 mb-3 text-start">
                                    <label class="form-label small fw-bold text-secondary">PILIH TINDAKAN:</label>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="delete_action"
                                            id="move{{ $cat->id }}" value="move" checked
                                            onclick="toggleSelect({{ $cat->id }}, true)">
                                        <label class="form-check-label" for="move{{ $cat->id }}">Pindahkan alat ke
                                            kategori lain</label>
                                        <select name="new_category_id" id="selectDest{{ $cat->id }}"
                                            class="form-select form-select-sm mt-2">
                                            <option value="" disabled selected>Pilih Kategori Tujuan...</option>
                                            @foreach ($categories as $otherCat)
                                                @if ($otherCat->id != $cat->id)
                                                    <option value="{{ $otherCat->id }}">{{ $otherCat->nama_kategori }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="delete_action"
                                            id="deleteAll{{ $cat->id }}" value="delete_all"
                                            onclick="toggleSelect({{ $cat->id }}, false)">
                                        <label class="form-check-label text-danger fw-medium"
                                            for="deleteAll{{ $cat->id }}">Hapus semua alat bersama kategori
                                            ini</label>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">Yakin ingin menghapus kategori ini? Tindakan ini tidak dapat
                                    dibatalkan.</p>
                                <input type="hidden" name="delete_action" value="delete_all">
                            @endif
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Konfirmasi
                                Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <style>
        .btn-group .btn-white {
            background: #fff;
            border: 1px solid #dee2e6;
        }

        .btn-group .btn-white:hover {
            background: #f8f9fa;
        }

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

    <script>
        function toggleSelect(id, status) {
            const select = document.getElementById('selectDest' + id);
            if (select) {
                select.disabled = !status;
                select.required = status;
            }
        }
    </script>
@endsection
