@extends('layouts.admin')

@section('title', __('messages.add_license') ?? 'Add Software License')

@section('content')
<div class="card theme-card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('software_licenses.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label class="theme-text">{{ __('messages.name') }} *</label>
                <input type="text" name="name" class="form-control theme-input @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g., Office Home & Business 2021" required>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.license_key') }}</label>
                <textarea name="license_key" class="form-control theme-input @error('license_key') is-invalid @enderror" rows="3" placeholder="XXXXX-XXXXX-XXXXX-XXXXX-XXXXX">{{ old('license_key') }}</textarea>
                @error('license_key') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.expiry_date') }}</label>
                <input type="date" name="expiry_date" class="form-control theme-input @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date') }}">
                <small class="text-muted">{{ __('messages.leave_empty_lifetime') ?? 'Leave empty for lifetime license' }}</small>
                @error('expiry_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.total_seats') }} *</label>
                <input type="number" name="total_seats" class="form-control theme-input @error('total_seats') is-invalid @enderror" value="{{ old('total_seats', 1) }}" min="1" required>
                @error('total_seats') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.pic') }}</label>
                <select name="pic_id" class="form-control select2 theme-input @error('pic_id') is-invalid @enderror">
                    <option value="" >{{ __('messages.select_employee') }}</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}"  {{ old('pic_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }} ({{ $emp->employee_id }})</option>
                    @endforeach
                </select>
                @error('pic_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.notes') }}</label>
                <textarea name="notes" class="form-control theme-input @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="card-footer" style="background-color: transparent; border-top: 1px solid var(--tech-border);">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ __('messages.save') }}</button>
            <a href="{{ route('software_licenses.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times mr-1"></i> {{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
