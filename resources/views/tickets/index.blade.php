@extends('layouts.admin')

@section('title', __('messages.ticket') ?? 'Helpdesk Tickets')

@section('content')
<div class="row mb-3">
    <div class="col-12 mb-3">
        <form action="{{ route('tickets.index') }}" method="GET">
            <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                <input type="text" name="search" class="form-control theme-input" placeholder="{{ __('messages.search_ticket') }}" value="{{ request('search') }}" style="width: 250px;" >
                
                <select name="status" class="form-control select2 theme-input" style="width: 150px; background-color: rgba(0,0,0,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.1);" >
                    <option value="" style="color: #000;">{{ __('messages.all_status') }}</option>
                    <option value="Open" style="color: #000;" {{ request('status') == 'Open' ? 'selected' : '' }}>{{ __('messages.open') }}</option>
                    <option value="In Progress" style="color: #000;" {{ request('status') == 'In Progress' ? 'selected' : '' }}>{{ __('messages.in_progress') }}</option>
                    <option value="Resolved" style="color: #000;" {{ request('status') == 'Resolved' ? 'selected' : '' }}>{{ __('messages.resolved') }}</option>
                    <option value="Closed" style="color: #000;" {{ request('status') == 'Closed' ? 'selected' : '' }}>{{ __('messages.closed') }}</option>
                </select>

                <select name="priority" class="form-control select2 theme-input" style="width: 150px; background-color: rgba(0,0,0,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.1);" >
                    <option value="" style="color: #000;">{{ __('messages.all_priority') }}</option>
                    <option value="Low" style="color: #000;" {{ request('priority') == 'Low' ? 'selected' : '' }}>{{ __('messages.low') }}</option>
                    <option value="Medium" style="color: #000;" {{ request('priority') == 'Medium' ? 'selected' : '' }}>{{ __('messages.medium') }}</option>
                    <option value="High" style="color: #000;" {{ request('priority') == 'High' ? 'selected' : '' }}>{{ __('messages.high') }}</option>
                    <option value="Critical" style="color: #000;" {{ request('priority') == 'Critical' ? 'selected' : '' }}>{{ __('messages.critical') }}</option>
                </select>
                
                <button type="submit" class="btn btn-primary" ><i class="fas fa-search"></i></button>
                @if(request()->anyFilled(['search', 'status', 'priority']))
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary" ><i class="fas fa-undo"></i> {{ __('messages.reset') }}</a>
                @endif
            </div>
        </form>
    </div>
    
    <div class="col-12 d-flex justify-content-end" style="gap: 10px;">
        <a href="{{ route('tickets.export') }}" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> {{ __('messages.export') }}</a>
        <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-primary" ><i class="fas fa-plus"></i> {{ __('messages.create_ticket') }}</a>
    </div>
</div>

<div class="card theme-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 theme-table">
                <thead>
                    <tr>
                        <th width="50">{{ __('messages.no') }}</th>
                        <th>{{ __('messages.ticket_number') }}</th>
                        <th>{{ __('messages.title') }}</th>
                        <th>{{ __('messages.reporter') }}</th>
                        <th>{{ __('messages.priority') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.created') }}</th>
                        <th width="150" class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td class="theme-text">{{ ($tickets->currentPage() - 1) * $tickets->perPage() + $loop->iteration }}</td>
                        <td class="font-weight-bold" >{{ $ticket->ticket_number }}</td>
                        <td class="theme-text">
                            {{ $ticket->title }}
                            @if($ticket->asset)
                            <br><small class="text-muted"><i class="fas fa-laptop"></i> {{ $ticket->asset->asset_tag }}</small>
                            @endif
                        </td>
                        <td class="theme-text">{{ $ticket->employee->name ?? '-' }}</td>
                        <td class="theme-text">
                            @switch($ticket->priority)
                                @case('Low') <span class="badge badge-info">{{ __('messages.low') }}</span> @break
                                @case('Medium') <span class="badge badge-warning">{{ __('messages.medium') }}</span> @break
                                @case('High') <span class="badge badge-danger">{{ __('messages.high') }}</span> @break
                                @case('Critical') <span class="badge badge-dark">{{ __('messages.critical') }}</span> @break
                            @endswitch
                        </td>
                        <td class="theme-text">
                            @switch($ticket->status)
                                @case('Open') <span class="badge badge-primary" style="box-shadow: 0 0 8px rgba(0,123,255,0.5);">{{ __('messages.open') }}</span> @break
                                @case('In Progress') <span class="badge badge-warning" style="box-shadow: 0 0 8px rgba(255,193,7,0.5);">{{ __('messages.in_progress') }}</span> @break
                                @case('Resolved') <span class="badge badge-success" style="box-shadow: 0 0 8px rgba(40,167,69,0.5);">{{ __('messages.resolved') }}</span> @break
                                @case('Closed') <span class="badge badge-secondary">{{ __('messages.closed') }}</span> @break
                            @endswitch
                        </td>
                        <td class="theme-text"><small>{{ $ticket->created_at->format('Y-m-d H:i') }}</small></td>
                        <td class="theme-text">
                            <div class="d-flex justify-content-center" style="gap: 8px;">
                                <a href="{{ route('tickets.edit', $ticket) }}" class="btn action-btn btn-outline-warning" style="border: 1px solid rgba(255, 193, 7, 0.3); background: rgba(255, 193, 7, 0.15); color: #ffc107;"  title="{{ __('messages.edit') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="d-inline">
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
    @if($tickets->hasPages())
    <div class="card-footer border-0 bg-transparent">
        {{ $tickets->links() }}
    </div>
    @endif
</div>
@endsection
