@extends('layouts.admin')

@section('title', __('messages.password_vault') ?? 'Password Vault')

@section('content')
<div class="row mb-3">
    <div class="col-sm-8">
        <form action="{{ route('password_vaults.index') }}" method="GET">
            <div class="d-flex flex-wrap" style="gap: 10px;">
                <input type="text" name="search" class="form-control theme-input" placeholder="{{ __('messages.search_vault') ?? 'Search...' }}" value="{{ request('search') }}" style="width: 250px;">
                
                <select name="category" class="form-control select2 theme-input" style="width: 180px; background-color: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #fff;" onchange="this.form.submit()">
                    <option value="" style="color: #000;">{{ __('messages.all_category') ?? 'All Categories' }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" style="color: #000;" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <button class="btn btn-outline-info" type="submit"><i class="fas fa-search"></i></button>
                @if(request('search') || request('category'))
                    <a href="{{ route('password_vaults.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>
    </div>
    <div class="col-sm-4 text-right">
        <a href="{{ route('password_vaults.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.add_vault') }}
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" style="background: rgba(40,167,69,0.2); border: 1px solid rgba(40,167,69,0.5); color: #28a745; backdrop-filter: blur(10px);">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fas fa-check"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible" style="background: rgba(220,53,69,0.2); border: 1px solid rgba(220,53,69,0.5); color: #dc3545; backdrop-filter: blur(10px);">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fas fa-ban"></i> {{ session('error') }}
    </div>
@endif

<div class="card theme-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover m-0 theme-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>{{ __('messages.device_name') }}</th>
                        <th>{{ __('messages.username') }}</th>
                        <th>{{ __('messages.password') }}</th>
                        <th>{{ __('messages.category') }}</th>
                        <th>{{ __('messages.notes') }}</th>
                        <th width="180" class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($passwords as $pwd)
                    <tr>
                        <td class="theme-text">{{ ($passwords->currentPage() - 1) * $passwords->perPage() + $loop->iteration }}</td>
                        <td class="text-info font-weight-bold">{{ $pwd->device_name }}</td>
                        <td class="theme-text"><code>{{ $pwd->username }}</code></td>
                        <td>
                            <div class="d-flex align-items-center" style="gap: 5px;">
                                <input type="password" value="{{ $pwd->encrypted_password }}" class="password-field border-0 bg-transparent theme-text" style="outline: none; width: 100px;" readonly>
                                <button type="button" class="btn btn-xs btn-outline-info toggle-password-btn" style="padding: 2px 6px;" title="Toggle Password"><i class="fas fa-eye"></i></button>
                                <button type="button" class="btn btn-xs btn-outline-success copy-password-btn" style="padding: 2px 6px;" title="Copy" data-password="{{ $pwd->encrypted_password }}"><i class="fas fa-copy"></i></button>
                            </div>
                        </td>
                        <td class="theme-text">
                            <span class="badge badge-secondary" style="box-shadow: 0 0 5px rgba(255,255,255,0.1);">{{ $pwd->category }}</span>
                        </td>
                        <td class="theme-text">{{ Str::limit($pwd->notes, 30) }}</td>
                        <td class="theme-text">
                            <div class="d-flex justify-content-center" style="gap: 8px;">
                                <a href="{{ route('password_vaults.edit', $pwd) }}" class="btn action-btn btn-edit-tech" title="{{ __('messages.edit') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('password_vaults.destroy', $pwd) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn action-btn btn-delete-tech btn-delete" title="{{ __('messages.delete') }}" data-confirm-message="{{ __('messages.confirm_delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted p-4">{{ __('messages.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($passwords->hasPages())
    <div class="card-footer clearfix" style="background-color: transparent; border-top: 1px solid var(--tech-border);">
        <div class="float-right">
            {{ $passwords->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle Password Visibility
    $('.toggle-password-btn').click(function() {
        var input = $(this).siblings('.password-field');
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Copy Password to Clipboard
    $('.copy-password-btn').click(function() {
        var pwd = $(this).data('password');
        var button = $(this);
        var icon = button.find('i');
        
        navigator.clipboard.writeText(pwd).then(function() {
            icon.removeClass('fa-copy').addClass('fa-check');
            button.removeClass('btn-outline-success').addClass('btn-success');
            
            setTimeout(function() {
                icon.removeClass('fa-check').addClass('fa-copy');
                button.removeClass('btn-success').addClass('btn-outline-success');
            }, 1500);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                icon: 'success',
                title: "{{ __('messages.copy_password_success') ?? 'Password copied!' }}",
                background: 'rgba(30, 41, 59, 0.95)',
                color: '#f8f9fa'
            });
        }, function(err) {
            console.error('Could not copy password: ', err);
        });
    });
});
</script>
@endpush
