@extends('layouts.admin')

@section('title', __('messages.system_overview'))

@push('styles')
    <style>
        /* Hallmark Tally — Dashboard Cards */
        .tally-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
        }
        .tally-stat-icon.indigo  { background: var(--color-accent-tint);  color: var(--color-accent); }
        .tally-stat-icon.lime    { background: oklch(93% 0.060 130); color: oklch(42% 0.160 130); }
        .tally-stat-icon.success { background: oklch(93% 0.050 145); color: oklch(38% 0.120 145); }
        .tally-stat-icon.warning { background: oklch(95% 0.050 50);  color: oklch(42% 0.160 50);  }
    </style>
@endpush

@section('content')


    <div class="row">
        <!-- Asset Active -->
        <div class="col-lg-3 col-6">
            <div class="small-box accent-success">
                <div class="inner">
                    <h3>{{ $active_assets }}</h3>
                    <p>{{ __('messages.asset_active') }}</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <a href="{{ route('assets.index', ['status' => 'Available,Assigned']) }}" class="small-box-footer">{{ __('messages.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Handover -->
        <div class="col-lg-3 col-6">
            <div class="small-box accent-indigo">
                <div class="inner">
                    <h3>{{ $assigned_assets }}</h3>
                    <p>{{ __('messages.assignment') ?? 'Handover' }}</p>
                </div>
                <div class="icon"><i class="fas fa-people-carry"></i></div>
                <a href="{{ route('assets.index', ['status' => 'Assigned']) }}" class="small-box-footer">{{ __('messages.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Ticket -->
        <div class="col-lg-3 col-6">
            <div class="small-box accent-warning">
                <div class="inner">
                    <h3>{{ $open_tickets }}</h3>
                    <p>{{ __('messages.open_ticket') ?? 'Open Ticket' }}</p>
                </div>
                <div class="icon"><i class="fas fa-ticket-alt"></i></div>
                <a href="{{ route('tickets.index', ['status' => 'Open,In Progress']) }}" class="small-box-footer">{{ __('messages.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- IP Address Used -->
        <div class="col-lg-3 col-6">
            <div class="small-box accent-soft">
                <div class="inner">
                    <h3>{{ $used_ips }}</h3>
                    <p>{{ __('messages.ip_used') ?? 'IP Used' }}</p>
                </div>
                <div class="icon"><i class="fas fa-network-wired"></i></div>
                <a href="{{ route('ips.index', ['status' => 'Used']) }}" class="small-box-footer">{{ __('messages.more_info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Detailed Asset Categories grid -->
    <h5 class="mb-3 mt-3" style="font-weight: 600; letter-spacing: -0.02em; color: var(--color-ink-0);"><i class="fas fa-th-large mr-2" style="color: var(--color-accent);"></i> {{ __('messages.it_categories') }}</h5>
    <div class="row mb-4">
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'Accessories']) }}" style="text-decoration: none; display: block;"
                class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="cat-count">{{ $accessories_count }}</h3>
                    <span class="cat-label">Accessories</span>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'CCTV']) }}" style="text-decoration: none; display: block;"
                class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="cat-count">{{ $cctv_count }}</h3>
                    <span class="cat-label">CCTV</span>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'Computer']) }}" style="text-decoration: none; display: block;"
                class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="cat-count">{{ $computer_count }}</h3>
                    <span class="cat-label">Computer</span>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'Network']) }}" style="text-decoration: none; display: block;"
                class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="cat-count">{{ $network_count }}</h3>
                    <span class="cat-label">Network</span>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'Storage']) }}" style="text-decoration: none; display: block;"
                class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="cat-count">{{ $storage_count }}</h3>
                    <span class="cat-label">Storage</span>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'Other IT Asset']) }}"
                style="text-decoration: none; display: block;" class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="cat-count">{{ $other_it_asset_count }}</h3>
                    <span class="cat-label">Other IT Asset</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Graphs and Charts section -->
    <div class="row">
        <!-- Age & Location Distribution -->
        <div class="col-md-6">
            <div class="card theme-card">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold theme-text"><i class="fas fa-chart-pie text-cyan mr-2"></i>
                        {{ __('messages.asset_age') }}</h3>
                </div>
                <div class="card-body">
                    <div style="height: 250px;"><canvas id="ageChart"></canvas></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card theme-card">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold theme-text"><i
                            class="fas fa-map-marker-alt text-danger mr-2"></i> {{ __('messages.asset_location') }}</h3>
                </div>
                <div class="card-body">
                    <div style="height: 250px;"><canvas id="locationChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- IP & User Completeness Distribution -->
        <div class="col-md-6">
            <div class="card theme-card">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold theme-text"><i class="fas fa-network-wired text-info mr-2"></i>
                        {{ __('messages.ip_distribution') ?? 'IP Address Distribution' }}</h3>
                </div>
                <div class="card-body">
                    <div style="height: 250px;"><canvas id="ipChart"></canvas></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card theme-card">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold theme-text"><i class="fas fa-ticket-alt text-purple mr-2"></i>
                        {{ __('messages.ticket_status') ?? 'Ticket Status Distribution' }}</h3>
                </div>
                <div class="card-body">
                    <div style="height: 250px;"><canvas id="ticketChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Log Table -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card theme-card">
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bold theme-text"><i class="fas fa-bolt text-warning mr-2"></i>
                        {{ __('messages.real_time_log') }}</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0 theme-table">
                            <thead>
                                <tr class="text-uppercase text-muted" style="font-size: 12px; letter-spacing: 1px;">
                                    <th>{{ __('messages.timestamp') }}</th>
                                    <th>{{ __('messages.operator') }}</th>
                                    <th>{{ __('messages.status_event') }}</th>
                                    <th>{{ __('messages.target_asset') }}</th>
                                </tr>
                            </thead>
                            <tbody id="realtime-activity-tbody">
                                @forelse($recent_activities as $activity)
                                    <tr>
                                        <td class="theme-text">
                                            <i class="far fa-clock text-muted mr-1"></i>
                                            @if($activity->date->isToday())
                                                {{ __('messages.today') }}, {{ $activity->date->format('H:i') }}
                                            @elseif($activity->date->isYesterday())
                                                {{ __('messages.yesterday') }}, {{ $activity->date->format('H:i') }}
                                            @else
                                                {{ $activity->date->format('d M, H:i') }}
                                            @endif
                                        </td>
                                        <td class="text-info font-weight-bold">{{ $activity->operator }}</td>
                                        <td>
                                            <span class="badge badge-{{ $activity->badge }}"
                                                style="box-shadow: 0 0 10px rgba(0,0,0,0.2);">
                                                {{ $activity->status_event }}
                                            </span>
                                        </td>
                                        <td class="theme-text">{{ $activity->asset }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">{{ __('messages.no_data') }}</td>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        $(document).ready(function () {
            // Register DataLabels
            Chart.register(ChartDataLabels);

            // Tally theme chart palette — adapts to dark/light mode
            var isDark = $('body').hasClass('dark-mode');
            var textClr = isDark ? 'oklch(60% 0.015 258)' : 'oklch(52.0% 0.018 258)';
            var gridClr = isDark
                ? 'color-mix(in oklch, oklch(95% 0.005 258) 8%, transparent)'
                : 'color-mix(in oklch, oklch(18% 0.03 258) 8%, transparent)';

            // Chart defaults — Tally
            Chart.defaults.color = isDark ? '#8892b0' : '#6a7094';
            Chart.defaults.font.family = 'DM Sans, system-ui, sans-serif';
            
            var datalabelsConfig = {
                color: 'white',
                font: { weight: 'bold', size: 11 },
                display: function(context) {
                    var val = context.dataset.data[context.dataIndex];
                    var total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    return total > 0 && (val / total) >= 0.04; // Hide if less than 4%
                },
                formatter: function(value, context) {
                    var total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    if (total === 0 || value === 0) return '';
                    var percentage = Math.round((value / total) * 100) + '%';
                    return percentage;
                }
            };

            function getPercentTooltip() {
                return {
                    callbacks: {
                        label: function (context) {
                            var label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            var value = context.raw;
                            var total = context.chart.data.datasets[0].data.reduce(function (a, b) {
                                return a + b;
                            }, 0);
                            var percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            label += value + ' (' + percentage + '%)';
                            return label;
                        }
                    }
                };
            }

            // 1. Age Chart (Bar)
            var ctxAge = document.getElementById('ageChart').getContext('2d');
            new Chart(ctxAge, {
                type: 'bar',
                data: {
                    labels: [
                        '0-3 {{ __('messages.years') }}',
                        '4-6 {{ __('messages.years') }}',
                        '7-10 {{ __('messages.years') }}',
                        '>10 {{ __('messages.years') }}'
                    ],
                    datasets: [{
                        label: '{{ __("messages.total_asset") }}',
                        data: [
                        {{ $age_buckets['0_3'] }},
                        {{ $age_buckets['4_6'] }},
                        {{ $age_buckets['7_10'] }},
                            {{ $age_buckets['over_10'] }}
                        ],
                        backgroundColor: [
                            'oklch(54% 0.220 268 / 0.70)',
                            'oklch(82% 0.180 130 / 0.70)',
                            'oklch(68% 0.150 145 / 0.70)',
                            'oklch(74% 0.180 50  / 0.70)'
                        ],
                        borderColor: [
                            'oklch(54% 0.220 268)',
                            'oklch(65% 0.180 130)',
                            'oklch(55% 0.150 145)',
                            'oklch(60% 0.180 50)'
                        ],
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: getPercentTooltip(),
                        datalabels: datalabelsConfig
                    },
                    scales: {
                        y: {
                            grid: { color: gridClr },
                            ticks: { precision: 0 }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 2. Location Chart (Doughnut)
            var ctxLoc = document.getElementById('locationChart').getContext('2d');
            new Chart(ctxLoc, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($locations_data->pluck('name')) !!},
                    datasets: [{
                        data: {!! json_encode($locations_data->pluck('count')) !!},
                        backgroundColor: [
                            'oklch(54% 0.220 268 / 0.80)',
                            'oklch(82% 0.180 130 / 0.80)',
                            'oklch(68% 0.150 145 / 0.80)',
                            'oklch(74% 0.180 50  / 0.80)',
                            'oklch(58% 0.200 25  / 0.80)',
                            'oklch(72% 0.140 268 / 0.80)'
                        ],
                        borderWidth: 2,
                        borderColor: 'oklch(98.4% 0.005 258)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'right', 
                            labels: { 
                                boxWidth: 10,
                                font: { size: 10 }
                            }
                        },
                        tooltip: getPercentTooltip(),
                        datalabels: datalabelsConfig
                    }
                }
            });

            // 3. IP Chart (Pie)
            var ctxIP = document.getElementById('ipChart').getContext('2d');
            new Chart(ctxIP, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($ip_data->pluck('status')) !!},
                    datasets: [{
                        data: {!! json_encode($ip_data->pluck('count')) !!},
                        backgroundColor: [
                            'oklch(54% 0.220 268 / 0.80)',
                            'oklch(72% 0.140 268 / 0.80)',
                            'oklch(82% 0.180 130 / 0.80)',
                            'oklch(68% 0.150 145 / 0.80)'
                        ],
                        borderWidth: 2,
                        borderColor: 'oklch(98.4% 0.005 258)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12 } },
                        tooltip: getPercentTooltip(),
                        datalabels: datalabelsConfig
                    }
                }
            });

            // 4. Ticket Status Chart (Doughnut)
            var ctxTicket = document.getElementById('ticketChart').getContext('2d');
            new Chart(ctxTicket, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($ticket_data->pluck('status')) !!},
                    datasets: [{
                        data: {!! json_encode($ticket_data->pluck('count')) !!},
                        backgroundColor: [
                            'oklch(54% 0.220 268 / 0.80)', // blue
                            'oklch(74% 0.180 50  / 0.80)', // orange/warning
                            'oklch(68% 0.150 145 / 0.80)', // green
                            'oklch(82% 0.180 130 / 0.80)', // yellow/lighter
                            'oklch(58% 0.200 25  / 0.80)'  // red
                        ],
                        borderWidth: 2,
                        borderColor: 'oklch(98.4% 0.005 258)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12 } },
                        tooltip: getPercentTooltip(),
                        datalabels: datalabelsConfig
                    }
                }
            });

            function fetchActivities() {
                $.ajax({
                    url: "{{ route('dashboard.activities') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var tbody = $('#realtime-activity-tbody');
                        if (data.length === 0) {
                            tbody.html('<tr><td colspan="4" class="text-center text-muted">{{ __('messages.no_data') }}</td></tr>');
                            return;
                        }
                        var html = '';
                        data.forEach(function (activity) {
                            html += '<tr>' +
                                '<td>' +
                                '<span style="color: var(--color-ink-3); font-family: var(--font-mono); font-size: 0.75rem;">' + activity.timestamp + '</span>' +
                                '</td>' +
                                '<td style="font-weight: 600; color: var(--color-accent);">' + activity.operator + '</td>' +
                                '<td>' +
                                '<span class="badge badge-' + activity.badge + '">' +
                                activity.status_event +
                                '</span>' +
                                '</td>' +
                                '<td>' + activity.asset + '</td>' +
                                '</tr>';
                        });
                        tbody.html(html);
                    },
                    error: function (err) {
                        console.error("Error fetching activities:", err);
                    }
                });
            }

            // Poll every 5 seconds
            setInterval(fetchActivities, 5000);
        });
    </script>
@endpush