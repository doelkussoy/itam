@extends('layouts.admin')

@section('title', __('messages.ip_management'))

@section('content')
    <div class="row mb-3">
        <div class="col-12 mb-3">
            <form action="{{ route('ips.index') }}" method="GET">
                <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                    <input type="text" name="search" class="form-control theme-input"
                        placeholder="{{ __('messages.search_ip') }}" value="{{ request('search') }}" style="width: 250px;">

                    <select name="status" class="form-control select2 theme-input"
                        style="width: 150px; background-color: rgba(0,0,0,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.1);">
                        <option value="" style="color: #000;">{{ __('messages.all_status') }}</option>
                        <option value="Available" style="color: #000;" {{ request('status') == 'Available' ? 'selected' : '' }}>{{ __('messages.available') }}</option>
                        <option value="Used" style="color: #000;" {{ request('status') == 'Used' ? 'selected' : '' }}>
                            {{ __('messages.used') }}</option>
                        <option value="Reserved" style="color: #000;" {{ request('status') == 'Reserved' ? 'selected' : '' }}>
                            {{ __('messages.reserved') }}</option>
                    </select>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('ips.index') }}" class="btn btn-outline-secondary"><i class="fas fa-undo"></i>
                            {{ __('messages.reset') }}</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="col-12 d-flex justify-content-end" style="gap: 10px;">
            <a href="{{ route('ips.export') }}" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i>
                {{ __('messages.export') }}</a>
            <a href="{{ route('ips.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i>
                {{ __('messages.add_ip') }}</a>
        </div>
    </div>

    <div class="card theme-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 theme-table">
                    <thead>
                        <tr>
                            <th width="50">{{ __('messages.no') }}</th>
                            <th>{{ __('messages.ip_address') }}</th>
                            <th>{{ __('messages.assigned_asset') }}</th>
                            <th>{{ __('messages.vlans') }}</th>
                            <th>{{ __('messages.assigned_employee') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th width="180" class="text-center">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ips as $ip)
                            <tr>
                                <td class="theme-text">{{ ($ips->currentPage() - 1) * $ips->perPage() + $loop->iteration }}</td>
                                <td class="font-weight-bold"><i class="fas fa-network-wired text-muted"></i>
                                    {{ $ip->ip_address }}</td>
                                <td class="theme-text">
                                    @if($ip->asset)
                                        <a href="{{ route('assets.show', $ip->asset_id) }}"
                                            class="text-info font-weight-bold">{{ $ip->asset->name }}</a><br><small
                                            class="text-muted">{{ $ip->asset->asset_tag }}</small>
                                    @elseif($ip->notes)
                                        <span class="theme-text">{{ $ip->notes }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="theme-text">
                                    @if($ip->vlan)
                                        <span class="badge badge-info">VLAN {{ $ip->vlan->vlan_number }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="theme-text">
                                    @if($ip->employee)
                                        <a href="{{ route('employees.show', $ip->employee_id) }}"
                                            class="text-info font-weight-bold">{{ $ip->employee->name }}</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="theme-text">
                                    @if($ip->status == 'Available') <span class="badge badge-success"
                                        style="box-shadow: 0 0 8px rgba(40,167,69,0.5);">{{ __('messages.available') }}</span>
                                    @elseif($ip->status == 'Used') <span class="badge badge-primary"
                                        style="box-shadow: 0 0 8px rgba(0,123,255,0.5);">{{ __('messages.used') }}</span>
                                    @else <span class="badge badge-warning"
                                        style="box-shadow: 0 0 8px rgba(255,193,7,0.5);">{{ __('messages.reserved') }}</span>
                                    @endif
                                </td>
                                <td class="theme-text">
                                    <div class="d-flex justify-content-center" style="gap: 8px;">
                                        <button type="button" class="btn action-btn btn-outline-success ping-btn"
                                            data-ping-url="{{ route('ips.ping', $ip) }}" title="{{ __('messages.ping_device') ?? 'Ping Device' }}"
                                            style="border: 1px solid rgba(40, 167, 69, 0.3); background: rgba(40, 167, 69, 0.15); color: #28a745;"><i
                                                class="fas fa-play"></i></button>
                                        <a href="{{ route('ips.edit', $ip) }}" class="btn action-btn btn-outline-warning"
                                            style="border: 1px solid rgba(255, 193, 7, 0.3); background: rgba(255, 193, 7, 0.15); color: #ffc107;"
                                            title="{{ __('messages.edit') }}"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('ips.destroy', $ip) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-delete action-btn btn-outline-danger"
                                                style="border: 1px solid rgba(220, 53, 69, 0.3); background: rgba(220, 53, 69, 0.15); color: #dc3545;"
                                                title="{{ __('messages.delete') }}"
                                                data-confirm-message="{{ __('messages.confirm_delete') }}"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">{{ __('messages.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($ips->hasPages())
            <div class="card-footer border-0 bg-transparent">
                {{ $ips->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.ping-btn').click(function () {
                var button = $(this);
                var icon = button.find('i');
                var originalClass = icon.attr('class');
                var pingUrl = button.data('ping-url');

                // Show spinner
                icon.attr('class', 'fas fa-spinner fa-spin');
                button.prop('disabled', true);

                $.ajax({
                    url: pingUrl,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        button.prop('disabled', false);
                        if (response.online) {
                            icon.attr('class', 'fas fa-check-circle');
                            button.css({
                                'background': 'rgba(40, 167, 69, 0.4)',
                                'color': '#28a745',
                                'border-color': '#28a745'
                            });
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'success',
                                title: "{{ __('messages.ping_online') ?? 'Ping Successful! Device is Online.' }}",
                                background: 'rgba(30, 41, 59, 0.95)',
                                color: '#f8f9fa'
                            });
                        } else {
                            icon.attr('class', 'fas fa-times-circle');
                            button.css({
                                'background': 'rgba(220, 53, 69, 0.4)',
                                'color': '#dc3545',
                                'border-color': '#dc3545'
                            });
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'error',
                                title: "{{ __('messages.ping_offline') ?? 'Ping Failed! Device is Offline.' }}",
                                background: 'rgba(30, 41, 59, 0.95)',
                                color: '#f8f9fa'
                            });
                        }
                        setTimeout(function () {
                            icon.attr('class', originalClass);
                            button.removeAttr('style');
                        }, 3000);
                    },
                    error: function () {
                        button.prop('disabled', false);
                        icon.attr('class', 'fas fa-exclamation-triangle');
                        button.css({
                            'background': 'rgba(255, 193, 7, 0.4)',
                            'color': '#ffc107',
                            'border-color': '#ffc107'
                        });
                        setTimeout(function () {
                            icon.attr('class', originalClass);
                            button.removeAttr('style');
                        }, 3000);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            icon: 'warning',
                            title: "{{ __('messages.ping_error') ?? 'Error executing ping command.' }}",
                            background: 'rgba(30, 41, 59, 0.95)',
                            color: '#f8f9fa'
                        });
                    }
                });
            });
        });
    </script>
@endpush