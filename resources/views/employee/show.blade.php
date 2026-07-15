@extends('layouts.admin')

@section('title', (__('messages.employee_profile') ?? 'Employee Profile') . ' - ' . $employee->name)

@section('content')
<div class="row">
    <!-- Profile General Card -->
    <div class="col-md-4">
        <div class="card theme-card text-center pb-3">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-center">
                    <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, var(--neon-purple), var(--neon-cyan)); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,240,255,0.25);">
                        <i class="fas fa-user-tie fa-4x text-white"></i>
                    </div>
                </div>
                <h4 class="font-weight-bold text-info mb-1">{{ $employee->name }}</h4>
                <p class="text-muted mb-2">NIK: <code>{{ $employee->employee_id }}</code></p>
                <div class="mb-3">
                    @if($employee->status == 'Active')
                        <span class="badge badge-success px-3 py-2" style="box-shadow: 0 0 8px rgba(40,167,69,0.4);">{{ __('messages.active') }}</span>
                    @else
                        <span class="badge badge-danger px-3 py-2" style="box-shadow: 0 0 8px rgba(220,53,69,0.4);">{{ __('messages.inactive') }}</span>
                    @endif
                </div>
                <hr style="border-top: 1px solid var(--tech-border);">
                
                <div class="text-left">
                    <p class="theme-text mb-2"><strong>{{ __('messages.department') }}:</strong> <span class="float-right text-info">{{ $employee->department->name ?? '-' }}</span></p>
                    <p class="theme-text mb-2"><strong>{{ __('messages.location') }}:</strong> <span class="float-right text-info">{{ $employee->location->name ?? '-' }}</span></p>
                    <p class="theme-text mb-2"><strong>{{ __('messages.supervisor') }}:</strong> <span class="float-right text-purple font-weight-bold">{{ $employee->supervisor->name ?? '-' }}</span></p>
                    <p class="theme-text mb-2"><strong>{{ __('messages.email') }}:</strong> <span class="float-right text-info" style="font-size: 13px;">{{ $employee->email ?? '-' }}</span></p>
                    <p class="theme-text mb-2"><strong>{{ __('messages.phone') }}:</strong> <span class="float-right text-info">{{ $employee->phone ?? '-' }}</span></p>
                </div>
            </div>
            <div class="px-3">
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-block btn-info mb-2"><i class="fas fa-user-edit"></i> {{ __('messages.edit_profile') ?? 'Edit Profile' }}</a>
                <a href="{{ route('employees.index') }}" class="btn btn-block btn-outline-secondary"><i class="fas fa-arrow-left"></i> {{ __('messages.back_to_list') ?? 'Back to List' }}</a>
            </div>
        </div>

        <!-- Connection & Tech Details Card -->
        <div class="card theme-card mt-3">
            <div class="card-header">
                <h5 class="card-title m-0 theme-text"><i class="fas fa-desktop text-purple mr-2"></i> {{ __('messages.tech_connection_params') ?? 'Tech Connection Parameters' }}</h5>
            </div>
            <div class="card-body">
                <p class="theme-text mb-2"><strong>{{ __('messages.anydesk_id') ?? 'AnyDesk ID' }}:</strong> <span class="float-right text-success font-weight-bold"><code>{{ $employee->anydesk_id ?? '-' }}</code></span></p>
                <p class="theme-text mb-2">
                    <strong>{{ __('messages.anydesk_password') ?? 'AnyDesk Password' }}:</strong> 
                    <span class="float-right font-weight-bold">
                        @if($employee->anydesk_password)
                            <input type="password" value="{{ $employee->anydesk_password }}" id="emp-anydesk-pass" class="border-0 bg-transparent theme-text text-right" readonly style="outline:none; width: 100px;">
                            <button class="btn btn-xs btn-outline-info toggle-anydesk-btn" type="button" style="padding: 1px 5px;"><i class="fas fa-eye"></i></button>
                        @else
                            -
                        @endif
                    </span>
                </p>
                <hr style="border-top: 1px solid var(--tech-border);">
                <p class="theme-text mb-2"><strong>PC Username:</strong> <span class="float-right text-warning font-weight-bold"><code>{{ $employee->login_username ?? '-' }}</code></span></p>
                <p class="theme-text mb-2">
                    <strong>PC Password:</strong> 
                    <span class="float-right font-weight-bold">
                        @if($employee->login_password)
                            <input type="password" value="{{ $employee->login_password }}" id="emp-login-pass" class="border-0 bg-transparent theme-text text-right" readonly style="outline:none; width: 100px;">
                            <button class="btn btn-xs btn-outline-warning toggle-login-btn" type="button" style="padding: 1px 5px;"><i class="fas fa-eye"></i></button>
                        @else
                            -
                        @endif
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Assets and Network Details Column -->
    <div class="col-md-8">
        <!-- Currently Assigned Assets -->
        <div class="card theme-card">
            <div class="card-header">
                <h5 class="card-title m-0 theme-text"><i class="fas fa-boxes text-info mr-2"></i> {{ __('messages.assigned_devices') ?? 'Assigned Devices' }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-0 theme-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.category') }}</th>
                                <th>{{ __('messages.asset_name') }}</th>
                                <th>{{ __('messages.asset_tag') }}</th>
                                <th>{{ __('messages.serial_number') }}</th>
                                <th>{{ __('messages.location') }}</th>
                                <th width="100" class="text-center">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $assignedAssignments = $employee->assignments->where('status', 'Assigned');
                            @endphp
                            @forelse($assignedAssignments as $assign)
                            <tr>
                                <td class="theme-text"><span class="badge badge-info">{{ $assign->asset->category->name ?? '-' }}</span></td>
                                <td class="theme-text font-weight-bold">
                                    <a href="{{ route('assets.show', $assign->asset_id) }}" class="text-info">
                                        {{ $assign->asset->name }}
                                    </a>
                                </td>
                                <td class="theme-text"><code>{{ $assign->asset->asset_tag }}</code></td>
                                <td class="theme-text">{{ $assign->asset->serial_number ?? '-' }}</td>
                                <td class="theme-text">{{ $assign->asset->location->name ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('assets.show', $assign->asset_id) }}" class="btn btn-xs btn-outline-info"><i class="fas fa-eye"></i> Details</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted p-4">{{ __('messages.no_devices_assigned') ?? 'No devices assigned to this employee.' }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- IP Address Links -->
        <div class="card theme-card mt-3">
            <div class="card-header">
                <h5 class="card-title m-0 theme-text"><i class="fas fa-network-wired text-success mr-2"></i> {{ __('messages.associated_ips') ?? 'Associated IP Addresses' }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-0 theme-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.ip_address') }}</th>
                                <th>{{ __('messages.mac_address') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.vlans') }}</th>
                                <th>{{ __('messages.gateway') }}</th>
                                <th>{{ __('messages.notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->ipAddresses as $ip)
                            <tr>
                                <td class="text-success font-weight-bold">{{ $ip->ip_address }}</td>
                                <td class="theme-text"><code>{{ $ip->mac_address ?? '-' }}</code></td>
                                <td class="theme-text"><span class="badge badge-success">{{ $ip->status }}</span></td>
                                <td class="theme-text">{{ $ip->vlan ? 'VLAN ' . $ip->vlan->vlan_number . ' (' . $ip->vlan->name . ')' : '-' }}</td>
                                <td class="theme-text">{{ $ip->gateway ?? '-' }}</td>
                                <td class="theme-text">{{ $ip->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted p-4">{{ __('messages.no_ips_mapped') ?? 'No IP addresses explicitly mapped to this employee.' }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Software Licenses managed by PIC -->
        <div class="card theme-card mt-3">
            <div class="card-header">
                <h5 class="card-title m-0 theme-text"><i class="fas fa-key text-warning mr-2"></i> {{ __('messages.managed_software') ?? 'Software Licenses (Under Charge)' }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-0 theme-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.license_key') }}</th>
                                <th>{{ __('messages.expiry_date') }}</th>
                                <th>{{ __('messages.total_seats') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->softwareLicenses as $lic)
                            <tr>
                                <td class="theme-text font-weight-bold text-info">{{ $lic->name }}</td>
                                <td class="theme-text"><code>{{ Str::limit($lic->license_key, 30) ?? '-' }}</code></td>
                                <td class="theme-text">{{ $lic->expiry_date ? $lic->expiry_date->format('d M Y') : 'Lifetime' }}</td>
                                <td class="theme-text">{{ $lic->total_seats }} {{ __('messages.seats') ?? 'Seats' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('software_licenses.edit', $lic) }}" class="btn btn-xs btn-outline-info"><i class="fas fa-edit"></i> Edit</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted p-4">{{ __('messages.no_software_pic') ?? 'No software licenses assigned as PIC for this employee.' }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-anydesk-btn').click(function() {
        var input = $('#emp-anydesk-pass');
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $('.toggle-login-btn').click(function() {
        var input = $('#emp-login-pass');
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
