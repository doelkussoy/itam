@extends('layouts.admin')

@section('title', __('messages.asset_assignment') ?? 'Asset Assignment')

@section('content')
<div class="row mb-3">
    <div class="col-12 mb-3">
        <form action="{{ route('assignments.index') }}" method="GET">
            <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                <input type="text" name="search" class="form-control theme-input" placeholder="{{ __('messages.search') }}..." value="{{ request('search') }}" style="width: 250px;" >
                <input type="date" name="date" class="form-control theme-input" value="{{ request('date') }}" style="width: 150px;" >
                
                <select name="status" class="form-control select2 theme-input" style="width: 180px;" >
                    <option value="" style="color: #000;">{{ __('messages.all_status') }}</option>
                    <option value="Assigned" style="color: #000;" {{ request('status') == 'Assigned' ? 'selected' : '' }}>{{ __('messages.assigned') }}</option>
                    <option value="Returned" style="color: #000;" {{ request('status') == 'Returned' ? 'selected' : '' }}>{{ __('messages.returned') ?? 'Returned' }}</option>
                </select>
                
                <button type="submit" class="btn btn-primary" ><i class="fas fa-search"></i></button>
                @if(request()->anyFilled(['search', 'status', 'date']))
                    <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary" ><i class="fas fa-undo"></i> {{ __('messages.reset') }}</a>
                @endif
            </div>
        </form>
    </div>
    
    <div class="col-12 d-flex justify-content-end">
        <a href="{{ route('assignments.create') }}" class="btn btn-sm btn-primary" ><i class="fas fa-handshake"></i> {{ __('messages.assign_asset') }}</a>
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
                        <th>{{ __('messages.employee') }}</th>
                        <th>{{ __('messages.assigned_date') }}</th>
                        <th>{{ __('messages.return_date') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th width="150" class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                    <tr>
                        <td class="theme-text">{{ ($assignments->currentPage() - 1) * $assignments->perPage() + $loop->iteration }}</td>
                        <td class="font-weight-bold" >
                            {{ $assignment->asset->name ?? '-' }}<br>
                            <small class="text-muted">{{ $assignment->asset->asset_tag ?? '-' }}</small>
                        </td>
                        <td class="theme-text">{{ $assignment->employee->name ?? '-' }}</td>
                        <td class="theme-text">{{ \Carbon\Carbon::parse($assignment->assigned_date)->locale(app()->getLocale())->translatedFormat('l, d F Y') }}</td>
                        <td class="theme-text">{{ $assignment->return_date ? \Carbon\Carbon::parse($assignment->return_date)->locale(app()->getLocale())->translatedFormat('l, d F Y') : '-' }}</td>
                        <td class="theme-text">
                            @if($assignment->status == 'Assigned')
                                <span class="badge badge-primary" style="box-shadow: 0 0 8px rgba(0,123,255,0.5);">{{ __('messages.assigned') }}</span>
                            @else
                                <span class="badge badge-success" style="box-shadow: 0 0 8px rgba(40,167,69,0.5);">{{ __('messages.returned') ?? 'Returned' }}</span>
                            @endif
                        </td>
                        <td class="theme-text">
                            <div class="d-flex justify-content-center" style="gap: 8px;">
                                @if($assignment->status == 'Assigned')
                                    <button type="button" class="btn action-btn btn-return-tech" title="{{ __('messages.return_asset') }}" data-toggle="modal" data-target="#returnModal{{ $assignment->id }}"><i class="fas fa-undo"></i></button>
                                @else
                                    <button type="button" class="btn action-btn" style="visibility: hidden;"><i class="fas fa-undo"></i></button>
                                @endif
                                <a href="{{ route('assignments.edit', $assignment) }}" class="btn action-btn btn-outline-warning" style="border: 1px solid rgba(255, 193, 7, 0.3); background: rgba(255, 193, 7, 0.15); color: #ffc107;"  title="{{ __('messages.edit') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-delete action-btn btn-outline-danger" style="border: 1px solid rgba(220, 53, 69, 0.3); background: rgba(220, 53, 69, 0.15); color: #dc3545;"  title="{{ __('messages.delete') }}" data-confirm-message="{{ __('messages.confirm_delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">{{ __('messages.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($assignments->hasPages())
    <div class="card-footer border-0 bg-transparent">
        {{ $assignments->links() }}
    </div>
    @endif
</div>

<!-- Return Modals placed outside of table-responsive to prevent clipping -->
@foreach($assignments as $assignment)
    @if($assignment->status == 'Assigned')
    <div class="modal fade" id="returnModal{{ $assignment->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content theme-card">
                <form action="{{ route('assignments.return', $assignment) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title theme-text">{{ __('messages.return_asset') }}</h5>
                        <button type="button" class="close theme-text" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="theme-text">{{ __('messages.return_asset') }} <strong>{{ $assignment->asset->name ?? '' }}</strong></p>
                        <div class="form-group">
                            <label class="theme-text">{{ __('messages.return_date') }} *</label>
                            <input type="date" name="return_date" class="form-control theme-input" value="{{ date('Y-m-d') }}" required >
                        </div>
                        <div class="form-group">
                            <label class="theme-text">{{ __('messages.return_notes') }}</label>
                            <textarea name="return_notes" class="form-control theme-input" rows="2"  ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('messages.mark_returned') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection
