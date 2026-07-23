@extends('layouts.admin')

@section('title', __('messages.add_ip'))

@section('content')
<div class="card theme-card">
    <form action="{{ route('ips.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <h5 class="text-info mb-3"><i class="fas fa-network-wired"></i> {{ __('messages.network_details') }}</h5>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.ip_address') }} *</label>
                    <input type="text" name="ip_address" class="form-control @error('ip_address') is-invalid @enderror" value="{{ old('ip_address') }}" required placeholder="192.168.1.100" >
                    @error('ip_address') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.status') }} *</label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror" required >
                        <option value="Available"  {{ old('status') == 'Available' ? 'selected' : '' }}>{{ __('messages.available') }}</option>
                        <option value="Used"  {{ old('status') == 'Used' ? 'selected' : '' }}>{{ __('messages.used') }}</option>
                        <option value="Reserved"  {{ old('status') == 'Reserved' ? 'selected' : '' }}>{{ __('messages.reserved') }}</option>
                    </select>
                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">

                <div class="col-md-6 form-group">
                    <label  class="theme-text">VLAN</label>
                    <select name="vlan_id" class="form-control select2 @error('vlan_id') is-invalid @enderror" >
                        <option value="" >None / Standard</option>
                        @foreach($vlans as $vlan)
                            <option value="{{ $vlan->id }}"  {{ old('vlan_id') == $vlan->id ? 'selected' : '' }}>
                                VLAN {{ $vlan->vlan_number }} - {{ $vlan->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vlan_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">Gateway</label>
                    <input type="text" name="gateway" class="form-control @error('gateway') is-invalid @enderror" value="{{ old('gateway') }}" placeholder="192.168.1.1" >
                    @error('gateway') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">DNS Server</label>
                    <input type="text" name="dns" class="form-control @error('dns') is-invalid @enderror" value="{{ old('dns') }}" placeholder="8.8.8.8, 1.1.1.1" >
                    @error('dns') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.assigned_employee') }} ({{ __('messages.optional') }})</label>
                    <select name="employee_id" class="form-control select2 @error('employee_id') is-invalid @enderror" >
                        <option value="" >{{ __('messages.none') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"  {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->employee_id }} - {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.assigned_asset') }} ({{ __('messages.optional') }})</label>
                    <select name="asset_id" class="form-control select2 @error('asset_id') is-invalid @enderror" >
                        <option value="" >{{ __('messages.none') }}</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}"  {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_tag }} - {{ $asset->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('asset_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label  class="theme-text">{{ __('messages.notes') }}</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2" >{{ old('notes') }}</textarea>
                    @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0">
            <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
            <a href="{{ route('ips.index') }}" class="btn btn-outline-secondary ml-2" >{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
