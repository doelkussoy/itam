@extends('layouts.admin')

@section('title', __('messages.edit_ip'))

@section('content')
<div class="card theme-card">
    <form action="{{ route('ips.update', $ip) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <h5 class="text-info mb-3"><i class="fas fa-edit"></i> {{ __('messages.edit') }} {{ __('messages.network_details') }}</h5>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.ip_address') }} *</label>
                    <input type="text" name="ip_address" class="form-control @error('ip_address') is-invalid @enderror" value="{{ old('ip_address', $ip->ip_address) }}" required >
                    @error('ip_address') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.status') }} *</label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror" required >
                        <option value="Available" style="color: #000;" {{ old('status', $ip->status) == 'Available' ? 'selected' : '' }}>{{ __('messages.available') }}</option>
                        <option value="Used" style="color: #000;" {{ old('status', $ip->status) == 'Used' ? 'selected' : '' }}>{{ __('messages.used') }}</option>
                        <option value="Reserved" style="color: #000;" {{ old('status', $ip->status) == 'Reserved' ? 'selected' : '' }}>{{ __('messages.reserved') }}</option>
                    </select>
                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">MAC Address</label>
                    <input type="text" name="mac_address" class="form-control @error('mac_address') is-invalid @enderror" value="{{ old('mac_address', $ip->mac_address) }}" placeholder="e.g. AA:BB:CC:DD:EE:FF" >
                    @error('mac_address') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">VLAN</label>
                    <select name="vlan_id" class="form-control select2 @error('vlan_id') is-invalid @enderror" >
                        <option value="" style="color: #000;">None / Standard</option>
                        @foreach($vlans as $vlan)
                            <option value="{{ $vlan->id }}" style="color: #000;" {{ old('vlan_id', $ip->vlan_id) == $vlan->id ? 'selected' : '' }}>
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
                    <input type="text" name="gateway" class="form-control @error('gateway') is-invalid @enderror" value="{{ old('gateway', $ip->gateway) }}" placeholder="192.168.1.1" >
                    @error('gateway') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">DNS Server</label>
                    <input type="text" name="dns" class="form-control @error('dns') is-invalid @enderror" value="{{ old('dns', $ip->dns) }}" placeholder="8.8.8.8, 1.1.1.1" >
                    @error('dns') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.assigned_employee') }} ({{ __('messages.optional') }})</label>
                    <select name="employee_id" class="form-control select2 @error('employee_id') is-invalid @enderror" >
                        <option value="" style="color: #000;">{{ __('messages.none') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" style="color: #000;" {{ old('employee_id', $ip->employee_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->employee_id }} - {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.assigned_asset') }} ({{ __('messages.optional') }})</label>
                    <select name="asset_id" class="form-control select2 @error('asset_id') is-invalid @enderror" >
                        <option value="" style="color: #000;">{{ __('messages.none') }}</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" style="color: #000;" {{ old('asset_id', $ip->asset_id) == $asset->id ? 'selected' : '' }}>
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
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2" >{{ old('notes', $ip->notes) }}</textarea>
                    @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0">
            <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> {{ __('messages.update') }}</button>
            <a href="{{ route('ips.index') }}" class="btn btn-outline-secondary ml-2" >{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
