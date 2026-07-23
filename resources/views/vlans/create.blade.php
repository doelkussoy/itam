@extends('layouts.admin')

@section('title', __('messages.add_vlan') ?? 'Add VLAN')

@section('content')
<div class="card theme-card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('vlans.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label class="theme-text">{{ __('messages.vlan_number') }} *</label>
                <input type="number" name="vlan_number" class="form-control theme-input @error('vlan_number') is-invalid @enderror" value="{{ old('vlan_number') }}" required>
                @error('vlan_number') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label class="theme-text">{{ __('messages.name') }} *</label>
                <input type="text" name="name" class="form-control theme-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.subnet') }}</label>
                <input type="text" name="subnet" class="form-control theme-input @error('subnet') is-invalid @enderror" value="{{ old('subnet') }}" placeholder="e.g., 192.168.2.0/24">
                @error('subnet') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.gateway') }}</label>
                <input type="text" name="gateway" class="form-control theme-input @error('gateway') is-invalid @enderror" value="{{ old('gateway') }}" placeholder="e.g., 192.168.2.1">
                @error('gateway') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.status') }} *</label>
                <select name="status" class="form-control theme-input @error('status') is-invalid @enderror" required>
                    <option value="Active" >{{ __('messages.active') }}</option>
                    <option value="Inactive" >{{ __('messages.inactive') }}</option>
                </select>
                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.notes') }}</label>
                <textarea name="notes" class="form-control theme-input @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="card-footer" style="background-color: transparent; border-top: 1px solid var(--tech-border);">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ __('messages.save') }}</button>
            <a href="{{ route('vlans.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times mr-1"></i> {{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
