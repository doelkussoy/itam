@extends('layouts.admin')

@section('title', __('messages.maintenance'))

@section('content')
<div class="row mb-3">
    <div class="col-12 mb-3">
        <form action="{{ route('maintenances.index') }}" method="GET">
            <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                <input type="text" name="search" class="form-control theme-input" placeholder="{{ __('messages.search') }}..." value="{{ request('search') }}" style="width: 250px;" >
                
                <select name="status" class="form-control select2 theme-input" style="width: 180px;" >
                    <option value="" style="color: #000;">{{ __('messages.all_status') }}</option>
                    <option value="Ongoing" style="color: #000;" {{ request('status') == 'Ongoing' ? 'selected' : '' }}>{{ __('messages.ongoing') }}</option>
                    <option value="Completed" style="color: #000;" {{ request('status') == 'Completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                    <option value="Cancelled" style="color: #000;" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                </select>
                
                <button type="submit" class="btn btn-primary" ><i class="fas fa-search"></i></button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('maintenances.index') }}" class="btn btn-outline-secondary" ><i class="fas fa-undo"></i> {{ __('messages.reset') }}</a>
                @endif
            </div>
        </form>
    </div>
    
    <div class="col-12 d-flex justify-content-end" style="gap: 10px;">
        <a href="{{ route('maintenances.export') }}" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> {{ __('messages.export') }}</a>
        <a href="{{ route('maintenances.create') }}" class="btn btn-sm btn-primary" ><i class="fas fa-tools"></i> {{ __('messages.log_maintenance') }}</a>
    </div>
</div>

<div class="card theme-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 theme-table">
                <thead>
                    <tr>
                        <th width="50">{{ __('messages.no') }}</th>
                        <th>{{ __('messages.asset') }}</th>
                        <th>{{ __('messages.type') }}</th>
                        <th>{{ __('messages.description') }}</th>
                        <th>{{ __('messages.dates') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th width="150" class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenances as $maintenance)
                    <tr>
                        <td class="theme-text">{{ ($maintenances->currentPage() - 1) * $maintenances->perPage() + $loop->iteration }}</td>
                        <td class="font-weight-bold" >
                            {{ $maintenance->asset->name ?? '-' }}<br>
                            <small class="text-muted">{{ $maintenance->asset->asset_tag ?? '-' }}</small>
                        </td>
                        <td class="theme-text">{{ $maintenance->type }}</td>
                        <td class="theme-text">{{ Str::limit($maintenance->description, 30) }}</td>
                        <td class="theme-text">
                            <small><i class="far fa-calendar-alt text-muted"></i> {{ $maintenance->start_date }}</small><br>
                            <small><i class="far fa-calendar-check text-muted"></i> {{ $maintenance->end_date ?? '...' }}</small>
                        </td>
                        <td class="theme-text">
                            @if($maintenance->status == 'Ongoing')
                                <span class="badge badge-warning" style="box-shadow: 0 0 8px rgba(255,193,7,0.5);">{{ __('messages.ongoing') }}</span>
                            @elseif($maintenance->status == 'Completed')
                                <span class="badge badge-success" style="box-shadow: 0 0 8px rgba(40,167,69,0.5);">{{ __('messages.completed') }}</span>
                            @else
                                <span class="badge badge-secondary">{{ __('messages.cancelled') }}</span>
                            @endif
                        </td>
                        <td class="theme-text">
                            <div class="d-flex justify-content-center" style="gap: 8px;">
                                @if($maintenance->status == 'Ongoing')
                                    <button type="button" class="btn btn-sm d-flex align-items-center justify-content-center" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 8px; width: 32px; height: 32px;" title="{{ __('messages.complete_maintenance') }}" data-toggle="modal" data-target="#completeModal{{ $maintenance->id }}"><i class="fas fa-check"></i></button>
                                @endif
                                <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn action-btn btn-outline-warning" style="border: 1px solid rgba(255, 193, 7, 0.3); background: rgba(255, 193, 7, 0.15); color: #ffc107;"  title="{{ __('messages.edit') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-delete action-btn btn-outline-danger" style="border: 1px solid rgba(220, 53, 69, 0.3); background: rgba(220, 53, 69, 0.15); color: #dc3545;"  title="{{ __('messages.delete') }}" data-confirm-message="{{ __('messages.confirm_delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">{{ __('messages.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($maintenances->hasPages())
    <div class="card-footer border-0 bg-transparent">
        {{ $maintenances->links() }}
    </div>
    @endif
</div>

<!-- Complete Modals placed outside of table-responsive to prevent clipping -->
@foreach($maintenances as $maintenance)
    @if($maintenance->status == 'Ongoing')
    <div class="modal fade" id="completeModal{{ $maintenance->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content theme-card">
                <form action="{{ route('maintenances.complete', $maintenance) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title theme-text">{{ __('messages.complete_maintenance') }}</h5>
                        <button type="button" class="close theme-text" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="theme-text">{{ __('messages.completing_maintenance_for') ?? 'Completing maintenance for' }} <strong class="text-info">{{ $maintenance->asset->asset_tag ?? '' }}</strong></p>
                        <div class="form-group">
                            <label class="theme-text">{{ __('messages.end_date') }} *</label>
                            <input type="date" name="end_date" class="form-control theme-input" value="{{ date('Y-m-d') }}" required >
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('messages.mark_completed') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection
