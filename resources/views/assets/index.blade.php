@extends('layouts.admin')

@section('title', __('messages.manage') . ' ' . __('messages.asset'))

@section('content')
<div class="row mb-3">
    <div class="col-12 mb-3">
        <form action="{{ route('assets.index') }}" method="GET">
            <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                <input type="text" name="search" class="form-control theme-input" placeholder="{{ __('messages.search_asset') }}" value="{{ request('search') }}" style="width: 200px; background-color: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #fff;" >
                
                <select name="category_id" class="form-control select2 theme-input" style="width: 150px; background-color: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #fff;" onchange="this.form.submit()" >
                    <option value="" style="color: #000;">{{ __('messages.all_status') }} {{ __('messages.category') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" style="color: #000;" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="brand_id" class="form-control select2 theme-input" style="width: 150px; background-color: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #fff;" onchange="this.form.submit()" >
                    <option value="" style="color: #000;">{{ __('messages.all_status') }} {{ __('messages.brand') }}</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" style="color: #000;" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
                
                <select name="location_id" class="form-control select2 theme-input" style="width: 150px; background-color: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #fff;" onchange="this.form.submit()" >
                    <option value="" style="color: #000;">{{ __('messages.all_status') }} {{ __('messages.location') }}</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" style="color: #000;" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>

                <select name="status" class="form-control select2 theme-input" style="width: 150px; background-color: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #fff;" onchange="this.form.submit()" >
                    <option value="" style="color: #000;">{{ __('messages.all_status') }}</option>
                    <option value="Available" style="color: #000;" {{ request('status') == 'Available' ? 'selected' : '' }}>{{ __('messages.available') }}</option>
                    <option value="Assigned" style="color: #000;" {{ request('status') == 'Assigned' ? 'selected' : '' }}>{{ __('messages.assigned') }}</option>
                    <option value="Maintenance" style="color: #000;" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>{{ __('messages.maintenance') }}</option>
                    <option value="Retired" style="color: #000;" {{ request('status') == 'Retired' ? 'selected' : '' }}>{{ __('messages.retired') }}</option>
                    <option value="Missing" style="color: #000;" {{ request('status') == 'Missing' ? 'selected' : '' }}>{{ __('messages.missing') }}</option>
                </select>

                <button class="btn btn-outline-info" type="submit" ><i class="fas fa-search"></i></button>
                @if(request('search') || request('category_id') || request('brand_id') || request('location_id') || request('status'))
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary" ><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>
    </div>
    <div class="col-12 text-right mt-2 mt-md-0">
        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#importModal" style="border: none; margin-right: 5px;">
            <i class="fas fa-file-import"></i> {{ __('messages.import_excel') }}
        </button>
        <a href="{{ route('assets.export') }}" class="btn btn-sm btn-success" style="border: none; margin-right: 5px;">
            <i class="fas fa-file-export"></i> {{ __('messages.export') }}
        </a>
        <a href="{{ route('assets.create') }}" class="btn btn-sm btn-primary" >
            <i class="fas fa-laptop-medical"></i> {{ __('messages.add_new') }} {{ __('messages.asset') }}
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" style="background: rgba(40,167,69,0.2); border: 1px solid rgba(40,167,69,0.5); color: #28a745; backdrop-filter: blur(10px);">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fas fa-check"></i> {{ session('success') }}
    </div>
@endif

<div class="card theme-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover m-0 theme-table">
                <thead>
                    <tr >
                        <th width="50">No</th>
                        <th>{{ __('messages.asset_tag') }}</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.category') }}</th>
                        <th>{{ __('messages.location') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th width="150" class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td class="theme-text">{{ ($assets->currentPage() - 1) * $assets->perPage() + $loop->iteration }}</td>
                        <td class="font-weight-bold"><a href="{{ route('assets.show', $asset) }}" class="text-info font-weight-bold">{{ $asset->asset_tag }}</a></td>
                        <td class="theme-text"><a href="{{ route('assets.show', $asset) }}" class="theme-text">{{ $asset->name }}</a></td>
                        <td class="theme-text">{{ $asset->category->name ?? '-' }}</td>
                        <td class="theme-text">{{ $asset->location->name ?? '-' }}</td>
                        <td class="theme-text">
                            @switch($asset->status)
                                @case('Available')
                                    <span class="badge badge-success" style="box-shadow: 0 0 8px rgba(40,167,69,0.5);">{{ __('messages.available') }}</span>
                                    @break
                                @case('Assigned')
                                    <span class="badge badge-primary" style="box-shadow: 0 0 8px rgba(0,123,255,0.5);">{{ __('messages.assigned') }}</span>
                                    @break
                                @case('Maintenance')
                                    <span class="badge badge-warning" style="box-shadow: 0 0 8px rgba(255,193,7,0.5);">{{ __('messages.maintenance') }}</span>
                                    @break
                                @case('Retired')
                                    <span class="badge badge-secondary">{{ __('messages.retired') }}</span>
                                    @break
                                @case('Missing')
                                    <span class="badge badge-danger" style="box-shadow: 0 0 8px rgba(220,53,69,0.5);">{{ __('messages.missing') }}</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="theme-text">
                            <div class="d-flex" style="gap: 8px;">
                                <a href="{{ route('assets.show', $asset) }}" class="btn action-btn btn-outline-info" title="View Details" style="border: 1px solid rgba(23, 162, 184, 0.3); background: rgba(23, 162, 184, 0.15); color: #17a2b8;"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('assets.edit', $asset) }}" class="btn action-btn btn-edit-tech"  title="{{ __('messages.edit') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-delete action-btn btn-delete-tech"  title="{{ __('messages.delete') }}" data-confirm-message="{{ __('messages.confirm_delete') }}"><i class="fas fa-trash"></i></button>
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
    <div class="card-footer bg-transparent">
        {{ $assets->appends(request()->query())->links() }}
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('assets.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title "><i class="fas fa-file-import text-info"></i> Import Assets</h5>
                    <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label  class="theme-text">Select Excel File (.xlsx, .xls, .csv)</label>
                        <input type="file" name="file" class="form-control-file " accept=".xlsx,.xls,.csv" required>
                    </div>
                    <small class="text-muted">Columns needed: asset_code, name, serial_number, category, brand, location, date_received, delivery_order_number, warranty_months, status, notes.</small>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" >Close</button>
                    <button type="submit" class="btn btn-info">Upload & Import</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
