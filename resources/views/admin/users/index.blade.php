@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Kelola Data Pengguna</h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        + Tambah User
    </a>
</div>

{{-- SEARCH --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control"
                    placeholder="Cari nama atau email..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary">
                    Cari
                </button>
            </div>
            @if(request('search'))
                <div class="col-auto">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

{{-- TABLE --}}
<div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="18%" class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $key => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $key }}</td>

                            <td class="fw-semibold">
                                {{ $user->name }}
                            </td>

                            <td class="text-muted">
                                {{ $user->email }}
                            </td>

                            {{-- ROLE BADGE --}}
                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge bg-danger">Admin</span>
                                        @break
                                    @case('petugas')
                                        <span class="badge bg-primary">Petugas</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Peminjam</span>
                                @endswitch
                            </td>

                            {{-- ACTION --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form 
                                        action="{{ route('admin.users.destroy', $user->id) }}" 
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')

                                        <button 
                                            type="submit" 
                                            class="btn btn-sm btn-danger"
                                            {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Data user tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="card-footer bg-white">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection