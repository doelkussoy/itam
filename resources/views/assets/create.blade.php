@extends('layouts.admin')

@section('title', __('messages.add') . ' ' . __('messages.asset'))

@section('content')

<div class="asset-form-card">
    @if(session('error'))
        <div class="alert alert-danger mx-3 mt-3 mb-0" style="background: rgba(220,53,69,0.2); border: 1px solid rgba(220,53,69,0.5); color: #ff6b6b; border-radius: 10px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mx-3 mt-3 mb-0" style="background: rgba(220,53,69,0.2); border: 1px solid rgba(220,53,69,0.5); color: #ff6b6b; border-radius: 10px;">
            <i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan:
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('assets.store') }}" method="POST">
        @csrf

        @if(session('error'))
            <div class="alert alert-danger mx-4 mt-4 mb-0" style="background: rgba(220,53,69,0.2); border: 1px solid rgba(220,53,69,0.5); color: #ff6b6b; border-radius: 10px;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mx-4 mt-4 mb-0" style="background: rgba(220,53,69,0.2); border: 1px solid rgba(220,53,69,0.5); color: #ff6b6b; border-radius: 10px;">
                <i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan:
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ===== BASIC INFORMATION ===== --}}
        <div class="form-section">
            <div class="section-title section-cyan">
                <span class="section-badge badge-cyan"><i class="fas fa-info-circle"></i></span>
                <div>
                    <div class="section-name">{{ __('messages.basic_info') }}</div>
                    <div class="section-sub">{{ __('messages.asset_tag') }}, {{ __('messages.asset_name') }}, Serial, Qty</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 form-group">
                    <label class="field-label">{{ __('messages.asset_tag') }} <span class="text-danger">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon"><i class="fas fa-qrcode"></i></span>
                        <input type="text" name="asset_tag"
                            class="field-input @error('asset_tag') is-invalid @enderror"
                            value="{{ old('asset_tag') }}" required
                            placeholder="e.g., IT-2024-001">
                    </div>
                    @error('asset_tag')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3 form-group">
                    <label class="field-label">{{ __('messages.asset_name') }} <span class="text-danger">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon"><i class="fas fa-laptop"></i></span>
                        <input type="text" name="name"
                            class="field-input @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required
                            placeholder="e.g., Lenovo ThinkPad E14">
                    </div>
                    @error('name')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3 form-group">
                    <label class="field-label">{{ __('messages.serial_number') }}</label>
                    <div class="input-wrap">
                        <span class="input-icon"><i class="fas fa-barcode"></i></span>
                        <input type="text" name="serial_number"
                            class="field-input @error('serial_number') is-invalid @enderror"
                            value="{{ old('serial_number') }}"
                            placeholder="e.g., SN-XXXX-XXXX">
                    </div>
                    @error('serial_number')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3 form-group">
                    <label class="field-label">{{ __('messages.quantity') }} <small class="text-muted">({{ __('messages.bulk_add') }})</small></label>
                    <div class="input-wrap">
                        <span class="input-icon"><i class="fas fa-layer-group"></i></span>
                        <input type="number" name="quantity"
                            class="field-input @error('quantity') is-invalid @enderror"
                            value="{{ old('quantity', 1) }}" min="1" required>
                    </div>
                    <div class="field-hint"><i class="fas fa-info-circle"></i> {{ __('messages.bulk_add_hint') }}</div>
                    @error('quantity')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- ===== CLASSIFICATION & LOCATION ===== --}}
        <div class="form-section">
            <div class="section-title section-purple">
                <span class="section-badge badge-purple"><i class="fas fa-tags"></i></span>
                <div>
                    <div class="section-name" style="color:#b026ff;">{{ __('messages.classification_location') }}</div>
                    <div class="section-sub">{{ __('messages.category') }}, {{ __('messages.brand') }}, {{ __('messages.location') }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label class="field-label">{{ __('messages.category') }}</label>
                    <select id="select-category" name="category_id"
                        class="field-select select2-field @error('category_id') is-invalid @enderror">
                        <option value="">{{ __('messages.select') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label class="field-label">{{ __('messages.brand') }}</label>
                    <select id="select-brand" name="brand_id"
                        class="field-select select2-field @error('brand_id') is-invalid @enderror">
                        <option value="">{{ __('messages.select') }}</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label class="field-label">{{ __('messages.location') }}</label>
                    <select id="select-location" name="location_id"
                        class="field-select select2-field @error('location_id') is-invalid @enderror">
                        <option value="">{{ __('messages.select') }}</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ===== ACQUISITION ===== --}}
        <div class="form-section">
            <div class="section-title section-green">
                <span class="section-badge badge-green"><i class="fas fa-truck"></i></span>
                <div>
                    <div class="section-name" style="color:#10b981;">{{ __('messages.acquisition') }}</div>
                    <div class="section-sub">{{ __('messages.date_received') }}, DO, {{ __('messages.status') }}, {{ __('messages.notes') }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 form-group">
                    <label class="field-label">{{ __('messages.date_received') }}</label>
                    <input type="date" name="date_received"
                        class="field-input @error('date_received') is-invalid @enderror"
                        value="{{ old('date_received') }}">
                </div>

                <div class="col-md-3 form-group">
                    <label class="field-label">{{ __('messages.delivery_order') }}</label>
                    <div class="input-wrap">
                        <span class="input-icon"><i class="fas fa-file-invoice"></i></span>
                        <input type="text" name="delivery_order_number"
                            class="field-input @error('delivery_order_number') is-invalid @enderror"
                            value="{{ old('delivery_order_number') }}"
                            placeholder="e.g., DO/2024/001">
                    </div>
                </div>

                <div class="col-md-2 form-group">
                    <label class="field-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                    <select name="status" class="field-select @error('status') is-invalid @enderror" required>
                        <option value="Available" {{ old('status','Available') == 'Available' ? 'selected' : '' }}>{{ __('messages.available') }}</option>
                        <option value="Assigned" {{ old('status') == 'Assigned' ? 'selected' : '' }}>{{ __('messages.assigned') }}</option>
                        <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>{{ __('messages.maintenance') }}</option>
                        <option value="Retired" {{ old('status') == 'Retired' ? 'selected' : '' }}>{{ __('messages.retired') }}</option>
                        <option value="Missing" {{ old('status') == 'Missing' ? 'selected' : '' }}>{{ __('messages.missing') }}</option>
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label class="field-label">{{ __('messages.notes') }}</label>
                    <textarea name="notes" class="field-input @error('notes') is-invalid @enderror"
                        rows="1" placeholder="{{ __('messages.notes') }}...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ===== DYNAMIC SPECIFICATIONS ===== --}}
        <div id="specifications-section" style="display:none;">
            <div class="form-section">
                <div class="section-title section-amber">
                    <span class="section-badge badge-amber"><i class="fas fa-cogs"></i></span>
                    <div>
                        <div class="section-name" style="color:#f59e0b;">{{ __('messages.category_specifications') }}</div>
                        <div class="section-sub">{{ __('messages.spec_based_on_category') ?? 'Spesifikasi sesuai kategori yang dipilih' }}</div>
                    </div>
                </div>

                {{-- Computer/Laptop --}}
                <div class="spec-group" id="spec-computer" style="display:none;">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="field-label">{{ __('messages.cpu_spec') }}</label>
                            <input type="text" name="cpu" class="field-input" placeholder="e.g., Core i5-10400 / AMD Ryzen 5">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="field-label">{{ __('messages.ram_size') }} (GB)</label>
                            <input type="number" name="ram" class="field-input" placeholder="e.g. 8, 16, 32">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="field-label">{{ __('messages.gpu_spec') }}</label>
                            <input type="text" name="gpu" class="field-input" placeholder="e.g., Nvidia GTX 1650 / Intel UHD">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="field-label">{{ __('messages.ssd_size') }} (GB)</label>
                            <input type="number" name="ssd" class="field-input" placeholder="e.g. 256, 512">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="field-label">{{ __('messages.hdd_size') }} (GB)</label>
                            <input type="number" name="hdd" class="field-input" placeholder="e.g. 1000">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="field-label">{{ __('messages.os') }}</label>
                            <input type="text" name="os" class="field-input" placeholder="e.g., Windows 11 Pro">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="field-label">{{ __('messages.office') }}</label>
                            <input type="text" name="office" class="field-input" placeholder="e.g., Office Home & Business 2021">
                        </div>
                    </div>
                </div>

                {{-- Printer --}}
                <div class="spec-group" id="spec-printer" style="display:none;">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="field-label">{{ __('messages.printer_type') }}</label>
                            <select name="printer_type" class="field-select">
                                <option value="Laser">Laser</option>
                                <option value="Inkjet">Inkjet</option>
                                <option value="Thermal">Thermal</option>
                                <option value="DotMatrix">Dot Matrix</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="field-label">{{ __('messages.connection') }}</label>
                            <select name="connection_type" class="field-select">
                                <option value="USB">USB Only</option>
                                <option value="Network">Network (Ethernet/WiFi)</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group d-flex align-items-end pb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="has_scanner" value="1" class="custom-control-input" id="has_scanner">
                                <label class="custom-control-label theme-text" for="has_scanner">{{ __('messages.has_scanner') }}</label>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="field-label">{{ __('messages.initial_counter') }}</label>
                            <input type="number" name="counter_print" class="field-input" value="0">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="field-label">{{ __('messages.toner_status') }}</label>
                            <input type="text" name="toner_status" class="field-input" placeholder="e.g., 80% / Baru">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="field-label">{{ __('messages.drum_status') }}</label>
                            <input type="text" name="drum_status" class="field-input" placeholder="e.g., Bagus / OK">
                        </div>
                    </div>
                </div>

                {{-- Monitor --}}
                <div class="spec-group" id="spec-monitor" style="display:none;">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="field-label">{{ __('messages.monitor_size') }} (inch)</label>
                            <input type="number" step="0.1" name="monitor_size" class="field-input" placeholder="e.g., 21.5 / 24">
                        </div>
                    </div>
                </div>

                {{-- Network --}}
                <div class="spec-group" id="spec-network" style="display:none;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="field-label">{{ __('messages.firmware_version') }}</label>
                            <input type="text" name="network_firmware" class="field-input" placeholder="e.g., RouterOS v7.2 / IOS 15.2">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="field-label">{{ __('messages.controller_cloud') }}</label>
                            <input type="text" name="controller" class="field-input" placeholder="e.g., UniFi Controller / standalone">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="field-label">{{ __('messages.total_ports') }}</label>
                            <input type="number" name="port_count" class="field-input" placeholder="e.g., 24 / 48">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="field-label">{{ __('messages.active_ports') }}</label>
                            <input type="number" name="active_ports" class="field-input" placeholder="e.g., 18">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="field-label">{{ __('messages.wifi_ssid') }}</label>
                            <input type="text" name="ssid" class="field-input" placeholder="SSID Name">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="field-label">{{ __('messages.wifi_password') }}</label>
                            <input type="text" name="wifi_password" class="field-input" placeholder="WiFi Password">
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="field-label">{{ __('messages.backup_config') }}</label>
                            <input type="text" name="backup_config_path" class="field-input" placeholder="e.g., \\cba-nas\configs\switch-core.backup">
                        </div>
                    </div>
                </div>

                {{-- CCTV --}}
                <div class="spec-group" id="spec-cctv" style="display:none;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="field-label">{{ __('messages.nvr_channel') }}</label>
                            <input type="number" name="nvr_channel" class="field-input" placeholder="e.g., 32">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="field-label">{{ __('messages.firmware_version') }}</label>
                            <input type="text" name="cctv_firmware" class="field-input" placeholder="e.g., v4.31.000">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="field-label">{{ __('messages.account_username') }}</label>
                            <input type="text" name="cctv_username" class="field-input" placeholder="admin">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="field-label">{{ __('messages.account_password') }}</label>
                            <input type="text" name="cctv_password" class="field-input" placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== FOOTER ===== --}}
        <div class="form-footer">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> {{ __('messages.save') }}
            </button>
            <a href="{{ route('assets.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> {{ __('messages.cancel') }}
            </a>
        </div>
    </form>
</div>

<style>
/* ─── Card Wrapper ─────────────────────────────────────── */
.asset-form-card {
    background: var(--tech-panel);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    border: 1px solid var(--tech-border);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 30px rgba(0,0,0,0.3);
}

/* ─── Section ──────────────────────────────────────────── */
.form-section {
    padding: 20px 24px 8px;
    border-bottom: 1px solid var(--tech-border);
}
.form-section:last-of-type { border-bottom: none; }

/* ─── Section Title ────────────────────────────────────── */
.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}
.section-badge {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
    color: #fff;
}
.badge-cyan   { background: linear-gradient(135deg,#00b4d8,#0284c7); box-shadow: 0 4px 12px rgba(0,180,216,0.35); }
.badge-purple { background: linear-gradient(135deg,#b026ff,#7c3aed); box-shadow: 0 4px 12px rgba(176,38,255,0.35); }
.badge-green  { background: linear-gradient(135deg,#10b981,#059669); box-shadow: 0 4px 12px rgba(16,185,129,0.35); }
.badge-amber  { background: linear-gradient(135deg,#f59e0b,#d97706); box-shadow: 0 4px 12px rgba(245,158,11,0.35); }
.section-name {
    font-size: 14px; font-weight: 700; color: var(--neon-cyan);
    letter-spacing: .3px; line-height: 1.2;
}
.section-sub {
    font-size: 11px; color: var(--text-muted); margin-top: 1px;
}

/* ─── Field Labels ─────────────────────────────────────── */
.field-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: var(--text-muted);
    margin-bottom: 5px;
}

/* ─── Input Wrap (icon + field) ────────────────────────── */
.input-wrap {
    position: relative;
}
.input-wrap .input-icon {
    position: absolute;
    left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--neon-cyan);
    font-size: 12px;
    pointer-events: none;
    z-index: 4;
}
.input-wrap .field-input {
    padding-left: 32px !important;
}
textarea.field-input {
    padding-left: 12px !important;
}

/* ─── Field Input / Select ─────────────────────────────── */
.field-input,
.field-select {
    width: 100%;
    background: var(--input-bg) !important;
    border: 1px solid var(--tech-border) !important;
    border-radius: 9px !important;
    color: var(--text-main) !important;
    padding: 8px 12px;
    font-size: 13px;
    transition: border-color .2s, box-shadow .2s;
    -webkit-appearance: none;
    appearance: none;
}
.field-input:focus,
.field-select:focus {
    outline: none;
    border-color: var(--neon-cyan) !important;
    box-shadow: 0 0 0 3px rgba(0,180,216,0.15) !important;
    background: var(--input-bg) !important;
    color: var(--text-main) !important;
}
.field-input::placeholder { color: var(--text-muted); font-size: 12px; }

/* Custom arrow for select */
.field-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2394a3b8' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 12px center !important;
    background-size: 11px !important;
    padding-right: 32px !important;
}
/* Style native <option> elements dark */
.field-select option {
    background: #1e293b !important;
    color: #e2e8f0 !important;
}
body.light-mode .field-select option {
    background: #ffffff !important;
    color: #1e293b !important;
}

/* ─── Error & Hint ─────────────────────────────────────── */
.field-error { font-size: 11px; color: #f87171; margin-top: 3px; }
.field-hint  { font-size: 11px; color: var(--text-muted); margin-top: 3px; }

/* ─── Select2 Overrides ────────────────────────────────── */
.select2-container { width: 100% !important; }
.select2-container--default .select2-selection--single {
    background: var(--input-bg) !important;
    border: 1px solid var(--tech-border) !important;
    border-radius: 9px !important;
    height: 36px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--text-main) !important;
    line-height: 34px !important;
    padding-left: 12px !important;
    font-size: 13px !important;
}
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: var(--text-muted) !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 34px !important;
}
.select2-container--default.select2-container--open .select2-selection--single {
    border-color: var(--neon-cyan) !important;
    box-shadow: 0 0 0 3px rgba(0,180,216,0.15) !important;
}
.select2-dropdown {
    background: #1a2740 !important;
    border: 1px solid var(--tech-border) !important;
    border-radius: 10px !important;
    box-shadow: 0 12px 40px rgba(0,0,0,0.5) !important;
    margin-top: 4px;
}
.select2-search--dropdown { padding: 8px 10px; }
.select2-search--dropdown .select2-search__field {
    background: rgba(255,255,255,0.05) !important;
    border: 1px solid var(--tech-border) !important;
    border-radius: 7px !important;
    color: var(--text-main) !important;
    padding: 6px 10px !important;
    font-size: 12px !important;
}
.select2-results__options { max-height: 220px; }
.select2-container--default .select2-results__option {
    color: var(--text-main) !important;
    background: transparent !important;
    padding: 8px 14px !important;
    font-size: 13px !important;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: rgba(0,180,216,0.12) !important;
    color: var(--neon-cyan) !important;
}
.select2-container--default .select2-results__option[aria-selected=true] {
    background: rgba(0,180,216,0.2) !important;
    color: var(--neon-cyan) !important;
}
/* Light mode Select2 */
body.light-mode .select2-dropdown { background: #ffffff !important; }
body.light-mode .select2-search--dropdown .select2-search__field {
    background: #f8fafc !important; color: #1e293b !important;
}
body.light-mode .select2-container--default .select2-results__option { color: #1e293b !important; }
body.light-mode .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: rgba(2,132,199,0.1) !important; color: #0284c7 !important;
}

/* ─── Footer ───────────────────────────────────────────── */
.form-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--tech-border);
    display: flex;
    gap: 10px;
    align-items: center;
}
.btn-save {
    display: inline-flex; align-items: center; gap: 7px;
    background: linear-gradient(135deg, #0284c7, #00b4d8);
    border: none;
    color: #fff;
    font-weight: 600;
    font-size: 13px;
    padding: 9px 26px;
    border-radius: 9px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0,180,216,0.3);
    transition: opacity .2s, transform .1s;
}
.btn-save:hover { opacity: .9; transform: translateY(-1px); }
.btn-cancel {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,0.06);
    border: 1px solid var(--tech-border);
    color: var(--text-muted);
    font-size: 13px;
    padding: 9px 22px;
    border-radius: 9px;
    text-decoration: none;
    transition: background .2s, color .2s;
}
.btn-cancel:hover { background: rgba(255,255,255,0.1); color: var(--text-main); text-decoration: none; }

/* ─── Row spacing ──────────────────────────────────────── */
.form-section .row { row-gap: 14px; }
.form-group { margin-bottom: 0; }
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // Init Select2 only on classification selects (not Status)
    $('.select2-field').select2({
        width: '100%',
        allowClear: true,
        placeholder: '{{ __("messages.select") }}'
    });

    // Category → spec section switcher
    var catMap = {
        'komputer'     : 'computer',
        'laptop'       : 'computer',
        'mini pc'      : 'computer',
        'thin client'  : 'computer',
        'server'       : 'computer',
        'printer'      : 'printer',
        'monitor'      : 'monitor',
        'switch'       : 'network',
        'switch network': 'network',
        'router'       : 'network',
        'access point' : 'network',
        'firewall'     : 'network',
        'cctv'         : 'cctv',
        'camera'       : 'cctv'
    };

    $('select[name="category_id"]').on('change', function () {
        var selectedOpt = $(this).find('option:selected');
        var selected = selectedOpt.text().trim().toLowerCase();
        var categoryId = $(this).val();

        $('.spec-group').hide();
        $('#specifications-section').hide();

        var group = catMap[selected];
        if (group) {
            $('#specifications-section').slideDown(200);
            $('#spec-' + group).show();
        }

        // Generate asset tag automatically based on category
        // Only fetch if triggered by user (isTrigger will be undefined) or if asset_tag is empty
        var currentTag = $('input[name="asset_tag"]').val();
        
        if (categoryId && (!currentTag || $(this).data('user-changed'))) {
            $.ajax({
                url: '{{ route("assets.generate-tag") }}',
                type: 'GET',
                data: { category_id: categoryId },
                success: function(response) {
                    if (response.tag) {
                        $('input[name="asset_tag"]').val(response.tag);
                    }
                },
                error: function() {
                    console.error('Failed to fetch generated tag');
                }
            });
        } else if (!categoryId && $(this).data('user-changed')) {
            $('input[name="asset_tag"]').val('');
        }
        
        // Reset the flag
        $(this).data('user-changed', false);
    });

    // Mark as user changed when actually clicked/changed by user
    $('select[name="category_id"]').on('select2:select select2:clear', function () {
        $(this).data('user-changed', true);
        $(this).trigger('change');
    });

    $('select[name="category_id"]').trigger('change');
});
</script>
@endpush
