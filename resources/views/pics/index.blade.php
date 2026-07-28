@extends('layouts.admin')

@section('title', __('messages.pic_data'))

@section('content')
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addPicModal"><i class="fas fa-plus"></i> {{ __('messages.add_pic') }}</button>
    </div>
</div>

<div class="card theme-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 theme-table">
                <thead>
                    <tr>
                        <th width="50">{{ __('messages.no') }}</th>
                        <th>{{ __('messages.pic_name') }}</th>
                        <th width="150" class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pics as $pic)
                    <tr>
                        <td class="theme-text">{{ $loop->iteration }}</td>
                        <td class="font-weight-bold">{{ $pic->name }}</td>
                        <td class="theme-text">
                            <div class="d-flex justify-content-center" style="gap: 8px;">
                                <button type="button" class="btn action-btn btn-outline-info" style="border: 1px solid rgba(23, 162, 184, 0.3); background: rgba(23, 162, 184, 0.15); color: #17a2b8;" title="{{ __('messages.edit') }}" data-toggle="modal" data-target="#editPicModal-{{ $pic->id }}"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('pics.destroy', $pic->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-delete action-btn btn-outline-danger" style="border: 1px solid rgba(220, 53, 69, 0.3); background: rgba(220, 53, 69, 0.15); color: #dc3545;" title="{{ __('messages.delete') }}" data-confirm-message="{{ __('messages.confirm_delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">{{ __('messages.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah PIC -->
<div class="modal fade theme-modal" id="addPicModal" tabindex="-1" role="dialog" aria-labelledby="addPicModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('pics.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPicModalLabel">{{ __('messages.add_pic') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('messages.pic_name') }} *</label>
                        <input type="text" name="name" class="form-control" required placeholder="{{ __('messages.enter_pic_name') }}">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-transparent">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@foreach($pics as $pic)
<!-- Modal Edit PIC -->
<div class="modal fade theme-modal" id="editPicModal-{{ $pic->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('pics.update', $pic->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('messages.edit_pic') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('messages.pic_name') }} *</label>
                        <input type="text" name="name" class="form-control" value="{{ $pic->name }}" required placeholder="{{ __('messages.enter_pic_name') }}">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-transparent">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection
