@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Log Aktivitas</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Aksi</th>
                <th>Deskripsi</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->user->name ?? '-' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection