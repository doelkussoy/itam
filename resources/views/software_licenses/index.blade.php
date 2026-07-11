@extends('layouts.admin')

@section('title', __('messages.software_licenses') ?? 'Software Licenses')

@section('content')
<div class="row mb-3">
    <div class="col-sm-6">
        <form action="{{ route('software_licenses.index') }}" method="GET">
            <div class="d-flex" style="gap: 10px;">
                <input type="text" name="search" class="form-control theme-input" placeholder="{{ __('messages.search_license') ?? 'Search...' }}" value="{{ request('search') }}" style="width: 250px;">
                <button class="btn btn-outline-info" type="submit"><i class="fas fa-search"></i></button>
                @if(request('search'))
                    <a href="{{ route('software_licenses.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('software_licenses.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.add_license') }}
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
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.license_key') }}</th>
                        <th>{{ __('messages.expiry_date') }}</th>
                        <th>{{ __('messages.total_seats') }}</th>
                        <th>{{ __('messages.pic') }}</th>
                        <th>{{ __('messages.notes') }}</th>
                        <th width="150" class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($software_licenses as $lic)
                    <tr>
                        <td class="theme-text">{{ ($software_licenses->currentPage() - 1) * $software_licenses->perPage() + $loop->iteration }}</td>
                        <td class="text-info font-weight-bold">{{ $lic->name }}</td>
                        <td class="theme-text"><code>{{ Str::limit($lic->license_key, 25) ?? '-' }}</code></td>
                        <td class="theme-text">
                            @if($lic->expiry_date)
                                <span class="{{ $lic->expiry_date->isPast() ? 'text-danger font-weight-bold' : ($lic->expiry_date->diffInDays(now()) < 30 ? 'text-warning font-weight-bold' : '') }}">
                                    {{ $lic->expiry_date->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-muted">{{ __('messages.lifetime') ?? 'Lifetime / No Expiry' }}</span>
                            @endif
                        </td>
                        <td class="theme-text">{{ $lic->total_seats }} {{ __('messages.seats') ?? 'Seats' }}</td>
                        <td class="theme-text">{{ $lic->pic->name ?? '-' }}</td>
                        <td class="theme-text">{{ Str::limit($lic->notes, 35) }}</td>
                        <td class="theme-text">
                            <div class="d-flex justify-content-center" style="gap: 8px;">
                                <a href="{{ route('software_licenses.edit', $lic) }}" class="btn action-btn btn-edit-tech" title="{{ __('messages.edit') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('software_licenses.destroy', $lic) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn action-btn btn-delete-tech btn-delete" title="{{ __('messages.delete') }}" data-confirm-message="{{ __('messages.confirm_delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted p-4">{{ __('messages.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($software_licenses->hasPages())
    <div class="card-footer clearfix" style="background-color: transparent; border-top: 1px solid var(--tech-border);">
        <div class="float-right">
            {{ $software_licenses->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
