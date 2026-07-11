@extends('layouts.admin')

@section('title', __('messages.system_overview'))

@push('styles')
    <style>
        /* Modern Dashboard Cards */
        .tech-box-1 {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.15), var(--tech-panel)) !important;
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-left: 4px solid #38bdf8;
            box-shadow: 0 8px 32px rgba(56, 189, 248, 0.1);
        }

        .tech-box-2 {
            background: linear-gradient(135deg, rgba(167, 139, 250, 0.15), var(--tech-panel)) !important;
            border: 1px solid rgba(167, 139, 250, 0.3);
            border-left: 4px solid #a78bfa;
            box-shadow: 0 8px 32px rgba(167, 139, 250, 0.1);
        }

        .tech-box-3 {
            background: linear-gradient(135deg, rgba(52, 211, 153, 0.15), var(--tech-panel)) !important;
            border: 1px solid rgba(52, 211, 153, 0.3);
            border-left: 4px solid #34d399;
            box-shadow: 0 8px 32px rgba(52, 211, 153, 0.1);
        }

        .tech-box-4 {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.15), var(--tech-panel)) !important;
            border: 1px solid rgba(251, 191, 36, 0.3);
            border-left: 4px solid #fbbf24;
            box-shadow: 0 8px 32px rgba(251, 191, 36, 0.1);
        }

        .tech-box-5 {
            background: linear-gradient(135deg, rgba(244, 114, 182, 0.15), var(--tech-panel)) !important;
            border: 1px solid rgba(244, 114, 182, 0.3);
            border-left: 4px solid #f472b6;
            box-shadow: 0 8px 32px rgba(244, 114, 182, 0.1);
        }

        .tech-box-6 {
            background: linear-gradient(135deg, rgba(96, 165, 250, 0.15), var(--tech-panel)) !important;
            border: 1px solid rgba(96, 165, 250, 0.3);
            border-left: 4px solid #60a5fa;
            box-shadow: 0 8px 32px rgba(96, 165, 250, 0.1);
        }

        .cat-mini-card {
            border-radius: 12px;
            background: var(--tech-panel);
            border: 1px solid var(--tech-border);
            transition: all 0.3s ease;
        }

        .cat-mini-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 240, 255, 0.1);
            border-color: rgba(0, 240, 255, 0.2);
        }
    </style>
@endpush

@section('content')


    <div class="row">
        <!-- Total Asset -->
        <div class="col-lg-3 col-6">
            <div class="small-box tech-box-1">
                <div class="inner">
                    <h3 class="theme-text">{{ $total_assets }}</h3>
                    <p class="theme-text">{{ __('messages.total_asset') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="{{ route('assets.index') }}" class="small-box-footer theme-text">{{ __('messages.more_info') }} <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Total User -->
        <div class="col-lg-3 col-6">
            <div class="small-box tech-box-2">
                <div class="inner">
                    <h3 class="theme-text">{{ $total_employees }}</h3>
                    <p class="theme-text">{{ __('messages.total_user') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('employees.index') }}" class="small-box-footer theme-text">{{ __('messages.more_info') }}
                    <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Asset Active -->
        <div class="col-lg-3 col-6">
            <div class="small-box tech-box-3">
                <div class="inner">
                    <h3 class="theme-text">{{ $active_assets }}</h3>
                    <p class="theme-text">{{ __('messages.asset_active') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('assets.index') }}" class="small-box-footer theme-text">{{ __('messages.more_info') }} <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="col-lg-3 col-6">
            <div class="small-box tech-box-4">
                <div class="inner">
                    <h3 class="theme-text">{{ $maintenance_assets }}</h3>
                    <p class="theme-text">{{ __('messages.maintenance') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
                <a href="{{ route('assets.index', ['status' => 'Maintenance']) }}"
                    class="small-box-footer theme-text">{{ __('messages.more_info') }} <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Detailed Asset Categories grid -->
    <h5 class="theme-text mb-3 mt-3"><i class="fas fa-th-large text-info mr-2"></i> {{ __('messages.it_categories') }}</h5>
    <div class="row mb-4">
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'Komputer']) }}" style="text-decoration: none; display: block;"
                class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="text-info font-weight-bold mb-1">{{ $computers_count }}</h3>
                    <span class="text-muted" style="font-size: 12px; font-weight: 500;">{{ __('messages.computer') }} /
                        {{ __('messages.laptop') }}</span>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'Printer']) }}" style="text-decoration: none; display: block;"
                class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="text-info font-weight-bold mb-1">{{ $printers_count }}</h3>
                    <span class="text-muted" style="font-size: 12px; font-weight: 500;">{{ __('messages.printer') }}</span>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'CCTV']) }}" style="text-decoration: none; display: block;"
                class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="text-info font-weight-bold mb-1">{{ $cctvs_count }}</h3>
                    <span class="text-muted" style="font-size: 12px; font-weight: 500;">{{ __('messages.cctv') }}</span>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'Switch']) }}" style="text-decoration: none; display: block;"
                class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="text-info font-weight-bold mb-1">{{ $switches_count }}</h3>
                    <span class="text-muted"
                        style="font-size: 12px; font-weight: 500;">{{ __('messages.switch_network') }}</span>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'Access Point']) }}"
                style="text-decoration: none; display: block;" class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="text-info font-weight-bold mb-1">{{ $ap_count }}</h3>
                    <span class="text-muted"
                        style="font-size: 12px; font-weight: 500;">{{ __('messages.access_point') }}</span>
                </div>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <a href="{{ route('assets.index', ['category' => 'Fingerprint']) }}"
                style="text-decoration: none; display: block;" class="h-100">
                <div class="card cat-mini-card p-3 text-center h-100">
                    <h3 class="text-info font-weight-bold mb-1">{{ $fingerprint_count }}</h3>
                    <span class="text-muted"
                        style="font-size: 12px; font-weight: 500;">{{ __('messages.fingerprint') }}</span>
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
        <!-- OS & RAM Distribution -->
        <div class="col-md-6">
            <div class="card theme-card">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold theme-text"><i class="fab fa-windows text-info mr-2"></i>
                        {{ __('messages.os_distribution') }}</h3>
                </div>
                <div class="card-body">
                    <div style="height: 250px;"><canvas id="osChart"></canvas></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card theme-card">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold theme-text"><i class="fas fa-memory text-purple mr-2"></i>
                        {{ __('messages.ram_distribution') }}</h3>
                </div>
                <div class="card-body">
                    <div style="height: 250px;"><canvas id="ramChart"></canvas></div>
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
    <script>
        $(document).ready(function () {
            var isDark = $('body').hasClass('dark-mode');
            var textClr = isDark ? '#e2e8f0' : '#1e293b';
            var gridClr = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.07)';

            // Chart default options
            Chart.defaults.color = textClr;
            Chart.defaults.font.family = 'Inter, sans-serif';

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
                            'rgba(0, 240, 255, 0.6)',
                            'rgba(176, 38, 255, 0.6)',
                            'rgba(52, 211, 153, 0.6)',
                            'rgba(248, 113, 113, 0.6)'
                        ],
                        borderColor: [
                            '#00f0ff', '#b026ff', '#34d399', '#f87171'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: getPercentTooltip()
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
                            'rgba(56, 189, 248, 0.7)',
                            'rgba(167, 139, 250, 0.7)',
                            'rgba(52, 211, 153, 0.7)',
                            'rgba(251, 191, 36, 0.7)',
                            'rgba(244, 114, 182, 0.7)',
                            'rgba(96, 165, 250, 0.7)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12 } },
                        tooltip: getPercentTooltip()
                    }
                }
            });

            // 3. OS Chart (Pie)
            var ctxOS = document.getElementById('osChart').getContext('2d');
            new Chart(ctxOS, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($os_data->pluck('os')) !!},
                    datasets: [{
                        data: {!! json_encode($os_data->pluck('count')) !!},
                        backgroundColor: [
                            'rgba(96, 165, 250, 0.7)',
                            'rgba(56, 189, 248, 0.7)',
                            'rgba(167, 139, 250, 0.7)',
                            'rgba(52, 211, 153, 0.7)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12 } },
                        tooltip: getPercentTooltip()
                    }
                }
            });

            // 4. RAM Chart (Polar Area)
            var ctxRAM = document.getElementById('ramChart').getContext('2d');
            new Chart(ctxRAM, {
                type: 'polarArea',
                data: {
                    labels: {!! json_encode($ram_data->pluck('ram')->map(fn($v) => $v . ' GB')) !!},
                    datasets: [{
                        data: {!! json_encode($ram_data->pluck('count')) !!},
                        backgroundColor: [
                            'rgba(167, 139, 250, 0.6)',
                            'rgba(56, 189, 248, 0.6)',
                            'rgba(52, 211, 153, 0.6)',
                            'rgba(244, 114, 182, 0.6)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12 } },
                        tooltip: getPercentTooltip()
                    },
                    scales: {
                        r: {
                            grid: { color: gridClr },
                            angleLines: { color: gridClr },
                            ticks: {
                                display: false
                            }
                        }
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
                                '<td class="theme-text">' +
                                '<i class="far fa-clock text-muted mr-1"></i> ' +
                                activity.timestamp +
                                '</td>' +
                                '<td class="text-info font-weight-bold">' + activity.operator + '</td>' +
                                '<td>' +
                                '<span class="badge badge-' + activity.badge + '" style="box-shadow: 0 0 10px rgba(0,0,0,0.2);">' +
                                activity.status_event +
                                '</span>' +
                                '</td>' +
                                '<td class="theme-text">' + activity.asset + '</td>' +
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