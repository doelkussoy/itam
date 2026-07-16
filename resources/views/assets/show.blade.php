@extends('layouts.admin')

@section('title', 'Asset Details - ' . $asset->asset_tag)

@section('content')
<div class="row">
    <!-- QR & Quick Info Card -->
    <div class="col-md-4">
        <div class="card theme-card text-center pb-3">
            <div class="card-body">
                <div class="d-flex justify-content-center mb-3">
                    <div id="qrcode" style="padding: 10px; background: white; border-radius: 8px; display: inline-block; box-shadow: 0 4px 15px rgba(0,0,0,0.3);"></div>
                </div>
                <h4 class="font-weight-bold text-info mb-1">{{ $asset->name }}</h4>
                <p class="text-muted mb-2">Tag: <code>{{ $asset->asset_tag }}</code></p>
                <div class="mb-3">
                    @php
                        $badgeMap = [
                            'Available' => 'success',
                            'Assigned' => 'primary',
                            'Maintenance' => 'warning',
                            'Retired' => 'danger',
                            'Missing' => 'secondary'
                        ];
                        $badgeClass = $badgeMap[$asset->status] ?? 'info';
                    @endphp
                    <span class="badge badge-{{ $badgeClass }} px-3 py-2" style="font-size: 14px; box-shadow: 0 0 10px rgba(0,0,0,0.3);">
                        {{ $asset->status }}
                    </span>
                </div>
                <hr style="border-top: 1px solid var(--tech-border);">
                
                <div class="text-left">
                    <p class="theme-text mb-2"><strong>Category:</strong> <span class="float-right text-info">{{ $asset->category->name ?? '-' }}</span></p>
                    <p class="theme-text mb-2"><strong>Brand:</strong> <span class="float-right text-info">{{ $asset->brand->name ?? '-' }}</span></p>
                    <p class="theme-text mb-2"><strong>Current Location:</strong> <span class="float-right text-info">{{ $asset->location->name ?? '-' }}</span></p>
                    <p class="theme-text mb-2">
                        <strong>Assigned To:</strong> 
                        <span class="float-right font-weight-bold text-purple">
                            @if($asset->currentAssignment)
                                <a href="{{ route('employees.show', $asset->currentAssignment->employee_id) }}" class="text-purple">
                                    {{ $asset->currentAssignment->employee->name }}
                                </a>
                            @else
                                -
                            @endif
                        </span>
                    </p>
                </div>
            </div>
            <div class="px-3">
                @auth
                <a href="{{ route('assets.edit', $asset) }}" class="btn btn-block btn-info mb-2"><i class="fas fa-edit"></i> Edit Asset</a>
                <a href="{{ route('assets.index') }}" class="btn btn-block btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back to Catalog</a>
                @endauth
            </div>
        </div>

        <!-- IP Address Management for this Asset -->
        <div class="card theme-card mt-3">
            <div class="card-header">
                <h5 class="card-title m-0 theme-text"><i class="fas fa-network-wired text-success mr-2"></i> Network IPs</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush theme-list" style="background: transparent;">
                    @forelse($asset->ipAddresses as $ip)
                        <li class="list-group-item d-flex justify-content-between align-items-center" style="background: transparent; border-bottom: 1px solid var(--tech-border);">
                            <div>
                                <span class="font-weight-bold text-success">{{ $ip->ip_address }}</span>
                                <br>
                                <small class="text-muted">MAC: {{ $ip->mac_address ?? '-' }}</small>
                            </div>
                            <span class="badge badge-success">{{ $ip->status }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted" style="background: transparent; border: none;">No IP Addresses linked.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Details and Specs Column -->
    <div class="col-md-8">
        <!-- Basic & Receiving details -->
        <div class="card theme-card">
            <div class="card-header">
                <h5 class="card-title m-0 theme-text"><i class="fas fa-info-circle text-info mr-2"></i> Specifications & Info</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <h6 class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 1px;">{{ __('messages.serial_number') }}</h6>
                        <p class="theme-text font-weight-bold">{{ $asset->serial_number ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 1px;">{{ __('messages.date_received') }}</h6>
                        <p class="theme-text font-weight-bold">
                            @if($asset->date_received)
                                {{ \Carbon\Carbon::parse($asset->date_received)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <h6 class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 1px;">{{ __('messages.delivery_order') }}</h6>
                        <p class="theme-text">{{ $asset->delivery_order_number ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 1px;">{{ __('messages.notes') }}</h6>
                        <p class="theme-text">{{ $asset->notes ?? '-' }}</p>
                    </div>
                </div>

                <!-- Specifications Panel (Conditional based on Category) -->
                @php
                    $catName = strtolower($asset->category->name ?? '');
                @endphp

                <!-- 1. Computers & Laptops -->
                @if(in_array($catName, ['komputer', 'laptop', 'mini pc', 'thin client', 'server']) && $asset->computer)
                    <hr style="border-top: 1px solid var(--tech-border);">
                    <h5 class="text-info mb-3"><i class="fas fa-microchip mr-2"></i> Hardware Specs</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-microchip mr-2 text-info" style="width: 20px;"></i>CPU</span>
                                <span class="theme-text font-weight-bold text-right" style="max-width: 70%; word-break: break-word;">{{ $asset->computer->cpu ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-tv mr-2 text-info" style="width: 20px;"></i>GPU</span>
                                <span class="theme-text font-weight-bold text-right" style="max-width: 70%; word-break: break-word;">{{ $asset->computer->gpu ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-memory mr-2 text-info" style="width: 20px;"></i>RAM</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->computer->ram ? $asset->computer->ram . ' GB' : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-hdd mr-2 text-info" style="width: 20px;"></i>SSD</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->computer->ssd ? $asset->computer->ssd . ' GB' : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-database mr-2 text-info" style="width: 20px;"></i>HDD</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->computer->hdd ? $asset->computer->hdd . ' GB' : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fab fa-windows mr-2 text-info" style="width: 20px;"></i>OS Version</span>
                                <span class="theme-text font-weight-bold text-right" style="max-width: 70%; word-break: break-word;">{{ $asset->computer->os ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="far fa-file-word mr-2 text-info" style="width: 20px;"></i>Office Version</span>
                                <span class="theme-text font-weight-bold text-right" style="max-width: 70%; word-break: break-word;">{{ $asset->computer->office ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 2. Printers -->
                @if($catName == 'printer' && $asset->printer)
                    <hr style="border-top: 1px solid var(--tech-border);">
                    <h5 class="text-info mb-3"><i class="fas fa-print mr-2"></i> Printer Parameters</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-print mr-2 text-info" style="width: 20px;"></i>Printer Type</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->printer->type }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-link mr-2 text-info" style="width: 20px;"></i>Connection Type</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->printer->connection_type }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-scanner mr-2 text-info" style="width: 20px;"></i>Has Scanner</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->printer->has_scanner ? 'Yes' : 'No' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-calculator mr-2 text-info" style="width: 20px;"></i>Print Counter</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->printer->counter_print }} prints</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-tint mr-2 text-info" style="width: 20px;"></i>Toner Status</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->printer->toner_status ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-compact-disc mr-2 text-info" style="width: 20px;"></i>Drum Status</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->printer->drum_status ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 3. Monitors -->
                @if($catName == 'monitor' && $asset->monitor)
                    <hr style="border-top: 1px solid var(--tech-border);">
                    <h5 class="text-info mb-3"><i class="fas fa-desktop mr-2"></i> Monitor Properties</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-expand-arrows-alt mr-2 text-info" style="width: 20px;"></i>Display Size</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->monitor->size ? $asset->monitor->size . ' inches' : '-' }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 4. Network Details (Switch, Router, AP) -->
                @if(in_array($catName, ['switch', 'router', 'access point', 'firewall']) && $asset->networkDetail)
                    <hr style="border-top: 1px solid var(--tech-border);">
                    <h5 class="text-info mb-3"><i class="fas fa-network-wired mr-2"></i> Network Parameters</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-code-branch mr-2 text-info" style="width: 20px;"></i>Firmware Version</span>
                                <span class="theme-text font-weight-bold text-right" style="max-width: 70%; word-break: break-word;">{{ $asset->networkDetail->firmware ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-server mr-2 text-info" style="width: 20px;"></i>AP Controller</span>
                                <span class="theme-text font-weight-bold text-right" style="max-width: 70%; word-break: break-word;">{{ $asset->networkDetail->controller ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-ethernet mr-2 text-info" style="width: 20px;"></i>Port Count</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->networkDetail->port_count ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-check-circle mr-2 text-info" style="width: 20px;"></i>Active Ports</span>
                                <span class="theme-text font-weight-bold text-right">{{ $asset->networkDetail->active_ports ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-wifi mr-2 text-info" style="width: 20px;"></i>SSID</span>
                                <span class="theme-text font-weight-bold text-right" style="max-width: 70%; word-break: break-word;">{{ $asset->networkDetail->ssid ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-key mr-2 text-info" style="width: 20px;"></i>Wi-Fi Password</span>
                                <span class="theme-text font-weight-bold text-right d-flex align-items-center">
                                    @if($asset->networkDetail->wifi_password)
                                        <input type="password" value="{{ $asset->networkDetail->wifi_password }}" id="network-wifi-pass" class="border-0 bg-transparent theme-text text-right" readonly style="outline:none; width: 120px;">
                                        <button class="btn btn-xs btn-outline-info toggle-wifi-btn ml-1" type="button" style="padding: 1px 5px;"><i class="fas fa-eye"></i></button>
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-folder-open mr-2 text-info" style="width: 20px;"></i>Config Backup Path</span>
                                <span class="theme-text text-right" style="max-width: 70%; word-break: break-all;"><code>{{ $asset->networkDetail->backup_config_path ?? '-' }}</code></span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 5. CCTV Camera -->
                @if(in_array($catName, ['cctv', 'camera']) && $asset->cctv)
                    <hr style="border-top: 1px solid var(--tech-border);">
                    <h5 class="text-info mb-3"><i class="fas fa-video mr-2"></i> CCTV Config</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-hashtag mr-2 text-info" style="width: 20px;"></i>NVR Channel</span>
                                <span class="theme-text font-weight-bold text-right">Channel {{ $asset->cctv->nvr_channel ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-code-branch mr-2 text-info" style="width: 20px;"></i>Firmware Version</span>
                                <span class="theme-text font-weight-bold text-right" style="max-width: 70%; word-break: break-word;">{{ $asset->cctv->firmware ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-user mr-2 text-info" style="width: 20px;"></i>NVR Account User</span>
                                <span class="theme-text font-weight-bold text-right" style="max-width: 70%; word-break: break-word;">{{ $asset->cctv->username ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2" style="border-bottom: 1px solid var(--tech-border) !important;">
                                <span class="text-muted"><i class="fas fa-key mr-2 text-info" style="width: 20px;"></i>Account Password</span>
                                <span class="theme-text font-weight-bold text-right d-flex align-items-center">
                                    @if($asset->cctv->password)
                                        <input type="password" value="{{ $asset->cctv->password }}" id="cctv-acc-pass" class="border-0 bg-transparent theme-text text-right" readonly style="outline:none; width: 120px;">
                                        <button class="btn btn-xs btn-outline-info toggle-cctv-btn ml-1" type="button" style="padding: 1px 5px;"><i class="fas fa-eye"></i></button>
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabs Container (History & Logs) -->
        <div class="card theme-card mt-3">
            <div class="card-header p-2 border-bottom-0">
                <ul class="nav nav-tabs theme-tabs" role="tablist" style="border-bottom: 1px solid var(--tech-border);">
                    <li class="nav-item">
                        <a class="nav-link active theme-text" id="assignments-tab" data-toggle="tab" href="#assignments" role="tab" aria-selected="true"><i class="fas fa-history mr-2 text-warning"></i> Assignment History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link theme-text" id="maintenances-tab" data-toggle="tab" href="#maintenances" role="tab" aria-selected="false"><i class="fas fa-tools mr-2 text-danger"></i> Maintenance Logs</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Assignment Tab -->
                    <div class="tab-pane fade show active" id="assignments" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover m-0 theme-table">
                                <thead>
                                    <tr>
                                        <th>Date Assigned</th>
                                        <th>Employee</th>
                                        <th>Date Returned</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($asset->assignments as $assign)
                                    <tr>
                                        <td class="theme-text">{{ \Carbon\Carbon::parse($assign->assigned_date)->format('d M Y') }}</td>
                                        <td class="theme-text">
                                            <a href="{{ route('employees.show', $assign->employee_id) }}" class="text-info font-weight-bold">
                                                {{ $assign->employee->name }}
                                            </a>
                                        </td>
                                        <td class="theme-text">
                                            {{ $assign->return_date ? \Carbon\Carbon::parse($assign->return_date)->format('d M Y') : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $assign->status == 'Returned' ? 'info' : 'success' }}">
                                                {{ $assign->status }}
                                            </span>
                                        </td>
                                        <td class="theme-text">{{ $assign->notes ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No assignment records.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Maintenance Tab -->
                    <div class="tab-pane fade" id="maintenances" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover m-0 theme-table">
                                <thead>
                                    <tr>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Cost</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($asset->maintenances as $maint)
                                    <tr>
                                        <td class="theme-text">{{ \Carbon\Carbon::parse($maint->start_date)->format('d M Y') }}</td>
                                        <td class="theme-text">{{ $maint->end_date ? \Carbon\Carbon::parse($maint->end_date)->format('d M Y') : '-' }}</td>
                                        <td class="theme-text font-weight-bold text-info">{{ $maint->type }}</td>
                                        <td class="theme-text">{{ $maint->description }}</td>
                                        <td class="theme-text text-danger">
                                            {{ $maint->cost ? 'Rp ' . number_format($maint->cost, 0, ',', '.') : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $maint->status == 'Completed' ? 'success' : ($maint->status == 'Ongoing' ? 'warning' : 'secondary') }}">
                                                {{ $maint->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No maintenance records.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
$(document).ready(function() {
    // Generate QR Code client-side
    var qrElement = document.getElementById("qrcode");
    if(qrElement) {
        new QRCode(qrElement, {
            text: "https://itam.cbapabrik.com/assets/{{ $asset->id }}",
            width: 130,
            height: 130,
            colorDark : "#111827",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    }

    // Toggle Wifi Password View
    $('.toggle-wifi-btn').click(function() {
        var input = $('#network-wifi-pass');
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Toggle CCTV Password View
    $('.toggle-cctv-btn').click(function() {
        var input = $('#cctv-acc-pass');
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});
</script>
@endpush
