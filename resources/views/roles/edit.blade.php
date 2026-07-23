@extends('layouts.admin')

@section('title', 'Atur Hak Akses: ' . $role->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>
        <h2 class="theme-text">Atur Hak Akses: <span class="text-primary">{{ $role->name }}</span></h2>
        <p class="theme-text text-muted">Centang menu yang boleh diakses oleh peran ini.</p>
    </div>
</div>

<div class="card theme-card">
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                @foreach($permissions as $permission)
                    <div class="col-md-4 mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                            <label class="custom-control-label theme-text" style="cursor:pointer;" for="perm_{{ $permission->id }}">
                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 text-right">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Hak Akses</button>
        </div>
    </form>
</div>
@endsection
