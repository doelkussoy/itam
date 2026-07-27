@extends('layouts.admin')

@section('title', __('messages.roles_permissions'))

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <p class="theme-text text-muted mb-0">{{ __('messages.roles_desc') }}</p>
    </div>
    <div class="col-md-4 text-md-right mt-3 mt-md-0">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRoleModal">
            <i class="fas fa-plus mr-1"></i> {{ __('messages.add_role') }}
        </button>
    </div>
</div>

<div class="card theme-card">
    <div class="card-body p-0">
        <table class="table theme-table mb-0">
            <thead>
                <tr>
                    <th>{{ __('messages.role') }}</th>
                    <th>{{ __('messages.total_permissions') }}</th>
                    <th width="150" class="text-center">{{ __('messages.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td class="theme-text font-weight-bold">{{ $role->name }}</td>
                    <td>
                        <span class="badge badge-info">{{ $role->permissions->count() }} {{ __('messages.permissions') }}</span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary" title="{{ __('messages.manage_permissions') }}">
                                <i class="fas fa-user-shield"></i>
                            </a>
                            @if(!in_array($role->name, ['Super Admin', 'Admin', 'User']))
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.delete_role_confirm') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('messages.delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Peran -->
<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content theme-card">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title theme-text font-weight-bold" id="addRoleModalLabel">{{ __('messages.add_role') }}</h5>
                <button type="button" class="close theme-text" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="theme-text">{{ __('messages.role_name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="{{ __('messages.role_name_placeholder') }}">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
