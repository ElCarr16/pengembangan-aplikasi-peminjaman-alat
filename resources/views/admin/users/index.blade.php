@extends('layouts.app')

@section('content')
    <!-- Breadcrumb -->
    <nav class="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Manajemen User</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Manajemen Pengguna</h3>
            <p class="text-muted small mb-0">Total {{ $users->total() }} akun terdaftar dalam sistem.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i>Tambah User
        </a>
    </div>

    {{-- SEARCH & FILTER --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted ps-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-1 py-2"
                        placeholder="Cari berdasarkan nama atau email..." value="{{ request('search') }}">

                    <button type="submit" class="btn btn-dark px-4">Cari</button>

                    @if (request('search'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-3">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- DATA TABLE --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small fw-bold text-uppercase">
                        <th class="ps-4 py-3" width="80">No</th>
                        <th>Informasi User</th>
                        <th>Tipe Akun</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $key => $user)
                        <tr>
                            <td class="ps-4 text-muted small">
                                {{ $users->firstItem() + $key }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center text-secondary fw-bold"
                                        style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0">{{ $user->name }}</div>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match ($user->role) {
                                        'admin' => 'bg-danger-subtle text-danger border-danger',
                                        'petugas' => 'bg-primary-subtle text-primary border-primary',
                                        default => 'bg-secondary-subtle text-secondary border-secondary',
                                    };
                                @endphp
                                <span class="badge border px-3 py-2 rounded-pill fw-medium {{ $badgeClass }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li>
                                            <a class="dropdown-item text-warning"
                                                href="{{ route('admin.users.edit', $user->id) }}">
                                                <i class="bi bi-pencil-square me-2"></i> Edit
                                            </a>
                                        </li>
                                        @if ($user->id != auth()->id())
                                            <li>
                                                <hr class="dropdown-divider opacity-50">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus user ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i> Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-people fs-1 text-muted opacity-25 d-block mb-3"></i>
                                <span class="text-muted">Tidak ada data pengguna yang ditemukan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white py-3 border-0">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        .bg-danger-subtle {
            background-color: #feecef !important;
        }

        .bg-primary-subtle {
            background-color: #e7f0ff !important;
        }

        .bg-secondary-subtle {
            background-color: #f1f3f5 !important;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .avatar-sm {
            font-size: 0.9rem;
            border: 1px solid #dee2e6;
        }
    </style>
@endsection
