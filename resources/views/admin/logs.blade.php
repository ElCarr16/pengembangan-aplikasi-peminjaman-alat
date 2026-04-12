@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Log Aktivitas</h3>
            <p class="text-muted small mb-0">Rekam jejak seluruh aksi yang terjadi di dalam sistem.</p>
        </div>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-download me-1"></i> Export Log
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border-0">
            <thead class="bg-light text-secondary">
                <tr>
                    <th class="border-0 px-4 py-3 text-uppercase small fw-bold" style="width: 20%;">User</th>
                    <th class="border-0 py-3 text-uppercase small fw-bold" style="width: 15%;">Aksi</th>
                    <th class="border-0 py-3 text-uppercase small fw-bold">Deskripsi</th>
                    <th class="border-0 py-3 text-uppercase small fw-bold text-end px-4" style="width: 20%;">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr class="border-bottom">
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3 bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                    style="width: 35px; height: 35px; font-size: 0.8rem;">
                                    {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $log->user->name ?? 'System' }}</div>
                                    <div class="text-muted small">{{ $log->user->role ?? 'Bot' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $badgeClass = match (strtolower($log->action)) {
                                    'create', 'add' => 'bg-success-subtle text-success border-success',
                                    'update', 'edit' => 'bg-warning-subtle text-warning border-warning',
                                    'delete', 'remove' => 'bg-danger-subtle text-danger border-danger',
                                    'login' => 'bg-info-subtle text-info border-info',
                                    default => 'bg-secondary-subtle text-secondary border-secondary',
                                };
                            @endphp
                            <span class="badge border px-3 py-2 rounded-pill fw-medium {{ $badgeClass }}">
                                {{ strtoupper($log->action) }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $log->description }}
                        </td>
                        <td class="text-end px-4">
                            <div class="fw-medium text-dark">{{ $log->created_at->format('d M Y') }}</div>
                            <div class="text-muted small">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                            Belum ada aktivitas terekam.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{-- Pagination jika ada --}}
        @if (method_exists($logs, 'links'))
            {{ $logs->links() }}
        @endif
    </div>

    <style>
        /* Styling khusus untuk tabel log */
        .table> :not(caption)>*>* {
            padding: 1rem 0.5rem;
            background-color: transparent;
            box-shadow: none;
        }

        .bg-success-subtle {
            background-color: #d1e7dd !important;
        }

        .bg-warning-subtle {
            background-color: #fff3cd !important;
        }

        .bg-danger-subtle {
            background-color: #f8d7da !important;
        }

        .bg-info-subtle {
            background-color: #cff4fc !important;
        }

        .bg-warning-subtle {
            background-color: #cfe2ff !important;
        }

        .badge {
            font-size: 0.7rem;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: #fcfdfe !important;
        }
    </style>
@endsection
