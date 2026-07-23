@extends('layouts.admin')

@section('title', __('messages.edit_assignment'))

@section('content')
<div class="card theme-card">
    <form action="{{ route('assignments.update', array_merge([$assignment->id], request()->query())) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <h5 class="text-info mb-3"><i class="fas fa-edit"></i> {{ __('messages.edit_assignment') }}</h5>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.asset') }}</label>
                    <input type="text" class="form-control theme-input" value="{{ $assignment->asset->asset_tag ?? '' }} - {{ $assignment->asset->name ?? '' }}" readonly style="background-color: rgba(0,0,0,0.3); color: #ccc;">
                    <small class="text-muted">{{ __('messages.asset_cannot_be_changed') }}</small>
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.employee') }} *</label>
                    <select name="employee_id" class="form-control select2 @error('employee_id') is-invalid @enderror" required >
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"  {{ old('employee_id', $assignment->employee_id) == $employee->id ? 'selected' : '' }}>
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
                    <input type="date" name="assigned_date" class="form-control @error('assigned_date') is-invalid @enderror" value="{{ old('assigned_date', $assignment->assigned_date) }}" required >
                    @error('assigned_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.notes') }}</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2" >{{ old('notes', $assignment->notes) }}</textarea>
                    @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0">
            <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> {{ __('messages.update') }}</button>
            <a href="{{ route('assignments.index', request()->query()) }}" class="btn btn-outline-secondary ml-2" >{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
