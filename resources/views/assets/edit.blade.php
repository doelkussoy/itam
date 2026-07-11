@extends('layouts.admin')

@section('title', __('messages.edit') . ' ' . __('messages.asset'))

@section('content')
<div class="card theme-card">
    <form action="{{ route('assets.update', $asset) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <h5 class="text-info mb-3"><i class="fas fa-info-circle"></i> {{ __('messages.basic_info') }}</h5>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label  class="theme-text">{{ __('messages.asset_tag') }} *</label>
                    <input type="text" name="asset_tag" class="form-control @error('asset_tag') is-invalid @enderror" value="{{ old('asset_tag', $asset->asset_tag) }}" required >
                    @error('asset_tag') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label  class="theme-text">{{ __('messages.asset_name') }} *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $asset->name) }}" required >
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label  class="theme-text">{{ __('messages.serial_number') }}</label>
                    <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" value="{{ old('serial_number', $asset->serial_number) }}" >
                    @error('serial_number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <hr >
            <h5 class="text-info mb-3 mt-3"><i class="fas fa-tags"></i> {{ __('messages.classification_location') }}</h5>
            
            <div class="row">
                <div class="col-md-4 form-group">
                    <label  class="theme-text">{{ __('messages.category') }}</label>
                    <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" >
                        <option value="" style="color: #000;">{{ __('messages.select') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" style="color: #000;" {{ old('category_id', $asset->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label  class="theme-text">{{ __('messages.brand') }}</label>
                    <select name="brand_id" class="form-control @error('brand_id') is-invalid @enderror" >
                        <option value="" style="color: #000;">{{ __('messages.select') }}</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" style="color: #000;" {{ old('brand_id', $asset->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label  class="theme-text">{{ __('messages.location') }}</label>
                    <select name="location_id" class="form-control @error('location_id') is-invalid @enderror" >
                        <option value="" style="color: #000;">{{ __('messages.select') }}</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" style="color: #000;" {{ old('location_id', $asset->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr >
            <h5 class="text-info mb-3 mt-3"><i class="fas fa-truck-loading"></i> {{ __('messages.acquisition') }}</h5>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.date_received') }}</label>
                    <input type="date" name="date_received" class="form-control @error('date_received') is-invalid @enderror" value="{{ old('date_received', $asset->date_received ? \Carbon\Carbon::parse($asset->date_received)->format('Y-m-d') : '') }}" >
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.delivery_order') }}</label>
                    <input type="text" name="delivery_order_number" class="form-control @error('delivery_order_number') is-invalid @enderror" value="{{ old('delivery_order_number', $asset->delivery_order_number) }}" >
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label  class="theme-text">{{ __('messages.status') }} *</label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror" required >
                        <option value="Available" style="color: #000;" {{ old('status', $asset->status) == 'Available' ? 'selected' : '' }}>{{ __('messages.available') }}</option>
                        <option value="Assigned" style="color: #000;" {{ old('status', $asset->status) == 'Assigned' ? 'selected' : '' }}>{{ __('messages.assigned') }}</option>
                        <option value="Maintenance" style="color: #000;" {{ old('status', $asset->status) == 'Maintenance' ? 'selected' : '' }}>{{ __('messages.maintenance') }}</option>
                        <option value="Retired" style="color: #000;" {{ old('status', $asset->status) == 'Retired' ? 'selected' : '' }}>{{ __('messages.retired') }}</option>
                        <option value="Missing" style="color: #000;" {{ old('status', $asset->status) == 'Missing' ? 'selected' : '' }}>{{ __('messages.missing') }}</option>
                    </select>
                </div>
                <div class="col-md-8 form-group">
                    <label  class="theme-text">{{ __('messages.notes') }}</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="1" >{{ old('notes', $asset->notes) }}</textarea>
                </div>
            </div>

            <!-- Dynamic Specification Fields based on Category -->
            <div id="specifications-section" style="display: none;">
                <hr>
                <h5 class="text-info mb-3"><i class="fas fa-cogs"></i> {{ __('messages.category_specifications') }}</h5>
                
                <!-- Computer Specs -->
                <div class="spec-group" id="spec-computer" style="display: none;">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="theme-text">{{ __('messages.cpu_spec') }}</label>
                            <input type="text" name="cpu" class="form-control theme-input" value="{{ old('cpu', $asset->computer->cpu ?? '') }}" placeholder="e.g., Core i5-10400 / AMD Ryzen 5">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="theme-text">{{ __('messages.ram_size') }}</label>
                            <input type="number" name="ram" class="form-control theme-input" value="{{ old('ram', $asset->computer->ram ?? '') }}" placeholder="e.g. 8, 16, 32">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="theme-text">{{ __('messages.gpu_spec') }}</label>
                            <input type="text" name="gpu" class="form-control theme-input" value="{{ old('gpu', $asset->computer->gpu ?? '') }}" placeholder="e.g., Nvidia GTX 1650 / Intel UHD">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.ssd_size') }}</label>
                            <input type="number" name="ssd" class="form-control theme-input" value="{{ old('ssd', $asset->computer->ssd ?? '') }}" placeholder="e.g. 256, 512">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.hdd_size') }}</label>
                            <input type="number" name="hdd" class="form-control theme-input" value="{{ old('hdd', $asset->computer->hdd ?? '') }}" placeholder="e.g. 1000">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.os') }}</label>
                            <input type="text" name="os" class="form-control theme-input" value="{{ old('os', $asset->computer->os ?? '') }}" placeholder="e.g., Windows 11 Pro">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.office') }}</label>
                            <input type="text" name="office" class="form-control theme-input" value="{{ old('office', $asset->computer->office ?? '') }}" placeholder="e.g., Office Home & Business 2021">
                        </div>
                    </div>
                </div>

                <!-- Printer Specs -->
                <div class="spec-group" id="spec-printer" style="display: none;">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="theme-text">{{ __('messages.printer_type') }}</label>
                            <select name="printer_type" class="form-control theme-input">
                                <option value="Laser" {{ (old('printer_type', $asset->printer->type ?? '') == 'Laser') ? 'selected' : '' }}>Laser</option>
                                <option value="Inkjet" {{ (old('printer_type', $asset->printer->type ?? '') == 'Inkjet') ? 'selected' : '' }}>Inkjet</option>
                                <option value="Thermal" {{ (old('printer_type', $asset->printer->type ?? '') == 'Thermal') ? 'selected' : '' }}>Thermal</option>
                                <option value="DotMatrix" {{ (old('printer_type', $asset->printer->type ?? '') == 'DotMatrix') ? 'selected' : '' }}>Dot Matrix</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="theme-text">{{ __('messages.connection') }}</label>
                            <select name="connection_type" class="form-control theme-input">
                                <option value="USB" {{ (old('connection_type', $asset->printer->connection_type ?? '') == 'USB') ? 'selected' : '' }}>USB Only</option>
                                <option value="Network" {{ (old('connection_type', $asset->printer->connection_type ?? '') == 'Network') ? 'selected' : '' }}>Network (Ethernet/WiFi)</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group d-flex align-items-center" style="padding-top: 30px;">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="has_scanner" value="1" class="custom-control-input" id="has_scanner" {{ (old('has_scanner', $asset->printer->has_scanner ?? 0)) ? 'checked' : '' }}>
                                <label class="custom-control-label theme-text" for="has_scanner">{{ __('messages.has_scanner') }}</label>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="theme-text">{{ __('messages.initial_counter') }}</label>
                            <input type="number" name="counter_print" class="form-control theme-input" value="{{ old('counter_print', $asset->printer->counter_print ?? 0) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="theme-text">{{ __('messages.toner_status') }}</label>
                            <input type="text" name="toner_status" class="form-control theme-input" value="{{ old('toner_status', $asset->printer->toner_status ?? '') }}" placeholder="e.g., 80% / Baru">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="theme-text">{{ __('messages.drum_status') }}</label>
                            <input type="text" name="drum_status" class="form-control theme-input" value="{{ old('drum_status', $asset->printer->drum_status ?? '') }}" placeholder="e.g., Bagus / OK">
                        </div>
                    </div>
                </div>

                <!-- Monitor Specs -->
                <div class="spec-group" id="spec-monitor" style="display: none;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.monitor_size') }}</label>
                            <input type="number" step="0.1" name="monitor_size" class="form-control theme-input" value="{{ old('monitor_size', $asset->monitor->size ?? '') }}" placeholder="e.g., 21.5 / 24">
                        </div>
                    </div>
                </div>

                <!-- Network Detail Specs -->
                <div class="spec-group" id="spec-network" style="display: none;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.firmware_version') }}</label>
                            <input type="text" name="network_firmware" class="form-control theme-input" value="{{ old('network_firmware', $asset->networkDetail->firmware ?? '') }}" placeholder="e.g., RouterOS v7.2 / IOS 15.2">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.controller_cloud') }}</label>
                            <input type="text" name="controller" class="form-control theme-input" value="{{ old('controller', $asset->networkDetail->controller ?? '') }}" placeholder="e.g., UniFi Controller / standalone">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.total_ports') }}</label>
                            <input type="number" name="port_count" class="form-control theme-input" value="{{ old('port_count', $asset->networkDetail->port_count ?? '') }}" placeholder="e.g., 24 / 48">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.active_ports') }}</label>
                            <input type="number" name="active_ports" class="form-control theme-input" value="{{ old('active_ports', $asset->networkDetail->active_ports ?? '') }}" placeholder="e.g., 18">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.wifi_ssid') }}</label>
                            <input type="text" name="ssid" class="form-control theme-input" value="{{ old('ssid', $asset->networkDetail->ssid ?? '') }}" placeholder="SSID Name">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.wifi_password') }}</label>
                            <input type="text" name="wifi_password" class="form-control theme-input" value="{{ old('wifi_password', $asset->networkDetail->wifi_password ?? '') }}" placeholder="WiFi Password">
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="theme-text">{{ __('messages.backup_config') }}</label>
                            <input type="text" name="backup_config_path" class="form-control theme-input" value="{{ old('backup_config_path', $asset->networkDetail->backup_config_path ?? '') }}" placeholder="e.g., \\cba-nas\configs\switch-core.backup">
                        </div>
                    </div>
                </div>

                <!-- CCTV Specs -->
                <div class="spec-group" id="spec-cctv" style="display: none;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.nvr_channel') }}</label>
                            <input type="number" name="nvr_channel" class="form-control theme-input" value="{{ old('nvr_channel', $asset->cctv->nvr_channel ?? '') }}" placeholder="e.g., 32">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.firmware_version') }}</label>
                            <input type="text" name="cctv_firmware" class="form-control theme-input" value="{{ old('cctv_firmware', $asset->cctv->firmware ?? '') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.account_username') }}</label>
                            <input type="text" name="cctv_username" class="form-control theme-input" value="{{ old('cctv_username', $asset->cctv->username ?? '') }}" placeholder="admin">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="theme-text">{{ __('messages.account_password') }}</label>
                            <input type="text" name="cctv_password" class="form-control theme-input" value="{{ old('cctv_password', $asset->cctv->password ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer bg-transparent border-0">
            <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> {{ __('messages.update') }}</button>
            <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary ml-2" >{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var catMap = {
        'komputer': 'computer',
        'laptop': 'computer',
        'mini pc': 'computer',
        'thin client': 'computer',
        'server': 'computer',
        'printer': 'printer',
        'monitor': 'monitor',
        'switch': 'network',
        'router': 'network',
        'access point': 'network',
        'firewall': 'network',
        'cctv': 'cctv',
        'camera': 'cctv'
    };

    $('select[name="category_id"]').change(function() {
        var selectedText = $(this).find('option:selected').text().trim().toLowerCase();
        
        $('.spec-group').hide();
        $('#specifications-section').hide();

        var group = catMap[selectedText];
        if (group) {
            $('#specifications-section').show();
            $('#spec-' + group).show();
        }
    });

    $('select[name="category_id"]').trigger('change');
});
</script>
@endpush
