@extends('layouts.admin')

@section('title', __('messages.manage_permissions') . ': ' . $role->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> {{ __('messages.back') }}</a>
        <p class="theme-text text-muted">{{ __('messages.permissions_desc') }}</p>
    </div>
</div>

<div class="card theme-card">
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @php
                $menuPermissions = $permissions->filter(function($p) { return str_starts_with($p->name, 'menu_'); })->sortBy('name');
                $actionPermissions = $permissions->filter(function($p) { return !str_starts_with($p->name, 'menu_'); })->sortBy('name');
            @endphp

            <h5 class="theme-text mb-3 pb-2" style="border-bottom: 1px solid var(--rule-soft);"><i class="fas fa-bars mr-2 text-info"></i> {{ __('messages.menu_access') }}</h5>
            <div class="row mb-4">
                @foreach($menuPermissions as $permission)
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                            <label class="custom-control-label theme-text" style="cursor:pointer;" for="perm_{{ $permission->id }}">
                                {{ ucwords(str_replace('_', ' ', str_replace('menu_', '', $permission->name))) }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <h5 class="theme-text mb-3 pb-2" style="border-bottom: 1px solid var(--rule-soft);"><i class="fas fa-cogs mr-2 text-warning"></i> {{ __('messages.action_access') }}</h5>
            <div class="row">
                @foreach($actionPermissions as $permission)
                    <div class="col-md-3 col-sm-6 mb-3">
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
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save mr-1"></i> {{ __('messages.save_permissions') }}</button>
        </div>
    </form>
</div>
@endsection
