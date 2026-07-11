@extends('layouts.admin')

@section('title', __('messages.log_maintenance'))

@section('content')
<div class="card theme-card">
    <form action="{{ route('maintenances.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <h5 class="text-info mb-3"><i class="fas fa-tools"></i> {{ __('messages.maintenance_details') }}</h5>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.asset') }} *</label>
                    <select name="asset_id" class="form-control select2 @error('asset_id') is-invalid @enderror" required >
                        <option value="" style="color: #000;">{{ __('messages.select_asset') }}</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" style="color: #000;" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_tag }} - {{ $asset->name }} ({{ $asset->status }})
                            </option>
                        @endforeach
                    </select>
                    @error('asset_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.maintenance_type') }} *</label>
                    <select name="type" class="form-control @error('type') is-invalid @enderror" required >
                        <option value="Routine" style="color: #000;" {{ old('type') == 'Routine' ? 'selected' : '' }}>{{ __('messages.routine_maintenance') }}</option>
                        <option value="Repair" style="color: #000;" {{ old('type') == 'Repair' ? 'selected' : '' }}>{{ __('messages.repair_broken') }}</option>
                        <option value="Upgrade" style="color: #000;" {{ old('type') == 'Upgrade' ? 'selected' : '' }}>{{ __('messages.hardware_upgrade') }}</option>
                    </select>
                    @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.start_date') }} *</label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', date('Y-m-d')) }}" required >
                    @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.estimated_cost') }}</label>
                    <input type="number" name="cost" class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost') }}" step="0.01" >
                    @error('cost') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label  class="theme-text">{{ __('messages.description_issue') }} *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required >{{ old('description') }}</textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0">
            <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
            <a href="{{ route('maintenances.index') }}" class="btn btn-outline-secondary ml-2" >{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
