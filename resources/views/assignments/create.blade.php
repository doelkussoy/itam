@extends('layouts.admin')

@section('title', __('messages.assign_asset'))

@section('content')
<div class="card theme-card">
    <form action="{{ route('assignments.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <h5 class="text-info mb-3"><i class="fas fa-handshake"></i> {{ __('messages.assignment_details') }}</h5>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.asset') }} *</label>
                    <select name="asset_id" class="form-control select2 @error('asset_id') is-invalid @enderror" required >
                        <option value="" style="color: #000;">{{ __('messages.select_available_asset') }}</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" style="color: #000;" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_tag }} - {{ $asset->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('asset_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.employee') }} *</label>
                    <select name="employee_id" class="form-control select2 @error('employee_id') is-invalid @enderror" required >
                        <option value="" style="color: #000;">{{ __('messages.select_employee') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" style="color: #000;" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->employee_id }} - {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.assigned_date') }} *</label>
                    <input type="date" name="assigned_date" class="form-control @error('assigned_date') is-invalid @enderror" value="{{ old('assigned_date', date('Y-m-d')) }}" required >
                    @error('assigned_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.notes') }}</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2" >{{ old('notes') }}</textarea>
                    @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0">
            <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
            <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary ml-2" >{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
