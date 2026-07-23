@extends('layouts.admin')

@section('title', __('messages.edit_maintenance'))

@section('content')
<div class="card theme-card">
    <form action="{{ route('maintenances.update', array_merge([$maintenance->id], request()->query())) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <h5 class="text-info mb-3"><i class="fas fa-edit"></i> {{ __('messages.edit_maintenance') }}</h5>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.asset') }}</label>
                    <input type="text" class="form-control theme-input" value="{{ $maintenance->asset->asset_tag ?? '' }} - {{ $maintenance->asset->name ?? '' }}" readonly style="background-color: rgba(0,0,0,0.3); color: #ccc;">
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.status') }} *</label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror" required >
                        <option value="Ongoing"  {{ old('status', $maintenance->status) == 'Ongoing' ? 'selected' : '' }}>{{ __('messages.ongoing') }}</option>
                        <option value="Completed"  {{ old('status', $maintenance->status) == 'Completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                        <option value="Cancelled"  {{ old('status', $maintenance->status) == 'Cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                    </select>
                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.maintenance_type') }} *</label>
                    <select name="type" class="form-control @error('type') is-invalid @enderror" required >
                        <option value="Routine"  {{ old('type', $maintenance->type) == 'Routine' ? 'selected' : '' }}>{{ __('messages.routine_maintenance') }}</option>
                        <option value="Repair"  {{ old('type', $maintenance->type) == 'Repair' ? 'selected' : '' }}>{{ __('messages.repair_broken') }}</option>
                        <option value="Upgrade"  {{ old('type', $maintenance->type) == 'Upgrade' ? 'selected' : '' }}>{{ __('messages.hardware_upgrade') }}</option>
                    </select>
                    @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.start_date') }} *</label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $maintenance->start_date) }}" required >
                    @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.estimated_cost') }}</label>
                    <input type="number" name="cost" class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost', $maintenance->cost) }}" step="0.01" >
                    @error('cost') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.end_date') }}</label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $maintenance->end_date) }}" >
                    @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label  class="theme-text">{{ __('messages.description_issue') }} *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required >{{ old('description', $maintenance->description) }}</textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0">
            <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> {{ __('messages.update') }}</button>
            <a href="{{ route('maintenances.index', request()->query()) }}" class="btn btn-outline-secondary ml-2" >{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
