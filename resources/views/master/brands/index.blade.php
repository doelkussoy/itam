@extends('layouts.admin')

@section('title', __('messages.manage') . ' ' . __('messages.brand'))

@section('content')
<div class="row mb-3">
    <div class="col-12 mb-3">
        <form action="{{ route('brands.index') }}" method="GET">
            <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                <input type="text" name="search" class="form-control theme-input" placeholder="{{ __('messages.search') }}..." value="{{ request('search') }}" style="width: 250px;" >
                <button class="btn btn-outline-info" type="submit" ><i class="fas fa-search"></i></button>
                @if(request('search'))
                    <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary" ><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card theme-card">
    <div class="card-header border-0">
        <h3 class="card-title theme-text">{{ __('messages.list') }} {{ __('messages.brand') }}</h3>
        <div class="card-tools d-flex" style="gap: 10px;">
            <a href="{{ route('brands.export') }}" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Export</a>
            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#importModal"><i class="fas fa-file-upload"></i> Import</button>
            <a href="{{ route('brands.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> {{ __('messages.add_new') }}</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover m-0 theme-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.description') }}</th>
                        <th width="150">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $item)
                    <tr>
                        <td class="theme-text">{{ ($brands->currentPage() - 1) * $brands->perPage() + $loop->iteration }}</td>
                        <td class="theme-text">{{ $item->name }}</td>
                        <td class="theme-text">{{ $item->description }}</td>
                        <td class="theme-text">
                            <div class="d-flex" style="gap: 8px;">
                                <a href="{{ route('brands.edit', $item) }}" class="btn action-btn btn-edit-tech"  title="{{ __('messages.edit') }}"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('brands.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-delete action-btn btn-delete-tech"  title="{{ __('messages.delete') }}" data-confirm-message="{{ __('messages.confirm_delete') }}"><i class="fas fa-trash"></i></button>
                            </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted">{{ __('messages.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent">
        {{ $brands->links() }}
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content theme-card">
      <form action="{{ route('brands.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header border-0">
          <h5 class="modal-title theme-text" id="importModalLabel">Import Brands</h5>
          <button type="button" class="close theme-text" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="theme-text">Upload Excel/CSV File</label>
            <input type="file" name="file" class="form-control-file theme-text" required accept=".xlsx,.xls,.csv">
            <small class="form-text text-muted">Format: name, description (Ensure column headers exist).</small>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Import Data</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection