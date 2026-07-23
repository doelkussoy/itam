@extends('layouts.admin')

@section('title', 'Manajemen Hak Akses')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="theme-text">Manajemen Hak Akses (Roles & Permissions)</h2>
        <p class="theme-text text-muted">Atur menu apa saja yang dapat diakses oleh peran tertentu.</p>
    </div>
</div>

<div class="card theme-card">
    <div class="card-body p-0">
        <table class="table theme-table mb-0">
            <thead>
                <tr>
                    <th>Peran (Role)</th>
                    <th>Jumlah Izin</th>
                    <th width="150" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td class="theme-text font-weight-bold">{{ $role->name }}</td>
                    <td>
                        <span class="badge badge-info">{{ $role->permissions->count() }} Menu</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary" title="Atur Hak Akses">
                            <i class="fas fa-user-shield"></i> Atur Akses
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
