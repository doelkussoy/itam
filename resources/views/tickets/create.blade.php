@extends('layouts.admin')

@section('title', __('messages.create_ticket'))

@section('content')
<div class="card theme-card">
    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <h5 class="text-info mb-3"><i class="fas fa-ticket-alt"></i> {{ __('messages.ticket_details') }}</h5>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label  class="theme-text">{{ __('messages.title_subject') }} *</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required >
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.reporter_employee') }} *</label>
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
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.related_asset_optional') }}</label>
                    <select name="asset_id" class="form-control select2 @error('asset_id') is-invalid @enderror" >
                        <option value="" style="color: #000;">{{ __('messages.none') }}</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" style="color: #000;" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_tag }} - {{ $asset->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('asset_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.priority') }} *</label>
                    <select name="priority" class="form-control @error('priority') is-invalid @enderror" required >
                        <option value="Low" style="color: #000;" {{ old('priority') == 'Low' ? 'selected' : '' }}>{{ __('messages.low') }}</option>
                        <option value="Medium" style="color: #000;" {{ old('priority') == 'Medium' ? 'selected' : '' }}>{{ __('messages.medium') }}</option>
                        <option value="High" style="color: #000;" {{ old('priority') == 'High' ? 'selected' : '' }}>{{ __('messages.high') }}</option>
                        <option value="Critical" style="color: #000;" {{ old('priority') == 'Critical' ? 'selected' : '' }}>{{ __('messages.critical') }}</option>
                    </select>
                    @error('priority') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label  class="theme-text">{{ __('messages.description_details') }} *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required >{{ old('description') }}</textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0">
            <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary ml-2" >{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
