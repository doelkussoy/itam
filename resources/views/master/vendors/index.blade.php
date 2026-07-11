@extends('layouts.admin')

@section('title', __('messages.manage') . ' ' . __('messages.vendor'))

@section('content')
<div class="card theme-card">
    <div class="card-header border-0">
        <h3 class="card-title theme-text">{{ __('messages.list') }} {{ __('messages.vendor') }}</h3>
        <div class="card-tools">
            <a href="{{ route('vendors.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> {{ __('messages.add_new') }}</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover m-0 theme-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.contact_person') }}</th>
                        <th>{{ __('messages.email') }}</th>
                        <th>{{ __('messages.phone') }}</th>
                        <th>{{ __('messages.address') }}</th>
                        <th width="150">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $item)
                    <tr>
                        <td class="theme-text">{{ ($vendors->currentPage() - 1) * $vendors->perPage() + $loop->iteration }}</td>
                        <td class="theme-text">{{ $item->name }}</td>
                        <td class="theme-text">{{ $item->contact_person }}</td>
                        <td class="theme-text">{{ $item->email }}</td>
                        <td class="theme-text">{{ $item->phone }}</td>
                        <td class="theme-text">{{ $item->address }}</td>
                        <td class="theme-text">
                            <div class="d-flex" style="gap: 8px;">
                                <a href="{{ route('vendors.edit', $item) }}" class="btn action-btn btn-edit-tech"  title="{{ __('messages.edit') }}"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('vendors.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-delete action-btn btn-delete-tech"  title="{{ __('messages.delete') }}" data-confirm-message="{{ __('messages.confirm_delete') }}"><i class="fas fa-trash"></i></button>
                            </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted">{{ __('messages.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent">
        {{ $vendors->links() }}
    </div>
</div>
@endsection