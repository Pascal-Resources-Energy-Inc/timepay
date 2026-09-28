@extends('layouts.header')

@section('head')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
.kpi-table {
    border-collapse: collapse;
    width: 100%;
    font-size: 12px;
    min-width: 1400px;
    border: 2px solid #dee2e6;
}

.kpi-table thead tr:first-child th {
    background: #343a40;
    color: white;
    padding: 12px 8px;
    text-align: center;
    border: 2px solid #2c3237;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
}

.kpi-table thead tr:last-child th {
    background: #495057;
    color: white;
    padding: 10px 8px;
    text-align: center;
    border: 2px solid #3d4349;
    font-weight: 600;
    font-size: 11px;
}

.kpi-table tbody td {
    padding: 10px 8px;
    text-align: center;
    border: 1px solid #dee2e6;
    font-size: 12px;
}

.kpi-table tbody tr {
    border-bottom: 1px solid #dee2e6;
}

.kpi-table tbody tr:hover {
    background-color: #f8f9fa;
}

.employee-name {
    text-align: left !important;
    font-weight: 600;
    color: #2c3e50;
    padding-left: 15px !important;
    background: #f8f9fa;
}

.category-label {
    text-align: right !important;
    padding-right: 15px !important;
    color: #6c757d;
    font-weight: 600;
    font-size: 11px;
    background: #f8f9fa;
}

.month-achieved {
    background-color: #d4edda !important;
    color: #155724;
    font-weight: 700;
}

.target-cell {
    background-color: #fff3cd !important;
    font-weight: 700;
    color: #856404;
}

.actual-cell {
    background-color: #cfe2ff !important;
    font-weight: 700;
    color: #084298;
}

.achievement-cell {
    font-weight: 700;
    font-size: 12px;
}

.achievement-good {
    background-color: #d4edda;
    color: #155724;
}

.achievement-warning {
    background-color: #fff3cd;
    color: #856404;
}

.achievement-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.summary-row {
    background-color: #e9ecef !important;
}

.summary-row td {
    font-weight: 700;
    font-size: 12px;
}

.vacant-row td {
    background-color: #fff5f5 !important;
}

.vacant-row .employee-name {
    color: #dc3545 !important;
    background-color: #fff5f5 !important;
}

.chart-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.chart-card h4 {
    font-size: 16px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
}

.chart-container {
    position: relative;
    height: 300px;
}

.card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.grid-margin .card {
    min-height: 140px;
}

.grid-margin .card-body {
    justify-content: center;
}

.grid-margin .mb-3 {
    margin-bottom: 1rem !important;
}

.select2-container--bootstrap-5 .select2-selection {
    min-height: 38px;
}

.select2-container--bootstrap-5 .select2-selection--single {
    padding: 0.375rem 0.75rem;
}

.select2-container--bootstrap-5 .select2-dropdown {
    border-color: #ced4da;
}

.select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
        <div class='row grid-margin'>
            <div class='col-lg-3 col-md-6 mb-3'>
                <div class="card card-tale">
                    <div class="card-body">
                        <h4 class="mb-2">Total Target</h4>
                        <h2 class="mb-0" style="font-size: 25px;">₱{{ number_format($overview['total_target'], 2) }}</h2>
                        <small class="text-muted">Year {{ $selectedYear }}</small>
                    </div>
                </div>
            </div>
            
            <div class='col-lg-3 col-md-6 mb-3'>
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <h4 class="mb-2">Total Actual</h4>
                        <h2 class="mb-0" style="font-size: 25px;">₱{{ number_format($overview['total_actual'], 2) }}</h2>
                        <small class="text-muted">YTD Achievement</small>
                    </div>
                </div>
            </div>
            
            <div class='col-lg-3 col-md-6 mb-3'>
                <div class="card text-success">
                    <div class="card-body">
                        <h4 class="mb-2">Achievement Rate</h4>
                        <h2 class="mb-0" style="font-size: 25px;">{{ number_format($overview['achievement_rate'], 2) }}%</h2>
                        <small class="text-muted">Overall Performance</small>
                    </div>
                </div>
            </div>
            
            <div class='col-lg-3 col-md-6 mb-3'>
                <div class="card card-light-danger">
                    <div class="card-body">
                        <h4 class="mb-2">Active TDS</h4>
                        <h2 class="mb-0" style="font-size: 25px;">{{ $overview['active_tds'] }}</h2>
                        <small class="text-muted">Trade Development Specialists</small>
                    </div>
                </div>
            </div>
        </div>

        <div class='row mb-3'>
            <div class='col-lg-12'>
                <div class="card">
                    <div class="card-body">
                        <form method='get' action="{{ route('tds.dashboard') }}" id="filterForm">
                            <div class="row align-items-end">
                                <div class='col-md-2'>
                                    <div class="form-group mb-0">
                                        <label><strong>Year</strong></label>
                                        <select class="form-control" name="year" id="yearSelect">
                                            @for($y = date('Y'); $y >= 2020; $y--)
                                                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class="form-group mb-0">
                                        <label><strong>Region</strong></label>
                                        <select class="form-control" name="region" id="regionSelect">
                                            <option value="">All Regions</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->id }}" 
                                                    {{ $selectedRegion == $region->id ? 'selected' : '' }}>
                                                    {{ $region->region_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class='col-md-3'>
                                    <div class="form-group mb-0">
                                        <label><strong>Employee</strong></label>
                                        <select class="form-control" name="employee" id="employeeSelect">
                                            <option value="">All Employees</option>
                                            @if($selectedEmployee ?? null)
                                                @php
                                                    $user = \App\User::with('employee')->find($selectedEmployee);
                                                    $displayName = $user->name ?? 'Selected Employee';
                                                    if ($user && $user->employee && $user->employee->employee_number) {
                                                        $displayName = $user->employee->employee_number . ' - ' . $user->name;
                                                    }
                                                @endphp
                                                <option value="{{ $selectedEmployee }}" selected>
                                                    {{ $displayName }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="ti-filter"></i> Apply Filter
                                    </button>
                                </div>
                                <div class='col-md-2'>
                                    <a href="{{ route('tds.dashboard.export', request()->query()) }}" 
                                       class="btn btn-success btn-block">
                                        <i class="ti-download"></i> Export CSV
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(empty($regionData))
        <div class='row mb-4'>
            <div class='col-lg-12'>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ti-info-alt" style="font-size: 48px; color: #6c757d;"></i>
                        <h4 class="mt-3 mb-2">No Data Available</h4>
                        <p class="text-muted">
                            @if($selectedEmployee ?? null)
                                No records found for the selected employee in {{ $selectedYear }}.
                            @else
                                No records found for the selected filters.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @foreach($regionData as $regionName => $regionInfo)
        <div class='mb-4' style="font-size: 11px;">
            <div class='col-lg-12'>
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; margin: -20px -20px 20px -20px; border-radius: 8px 8px 0 0;">
                            <i class="ti-location-pin mr-2"></i>{{ $regionName }} - {{ $selectedYear }} KPI Performance
                        </h4>
                        
                        <div class="table-responsive">
                        <table class="kpi-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 180px;">Employee Name</th>
                                    <th rowspan="2" style="width: 140px;">Metric</th>
                                    <th colspan="12">Monthly Performance</th>
                                    <th rowspan="2" style="width: 110px;">Actual</th>
                                    <th rowspan="2" style="width: 110px;">Target</th>
                                    <th rowspan="2" style="width: 80px;">A/R %</th>
                                </tr>
                                <tr>
                                    <th style="width: 70px;">Jan</th>
                                    <th style="width: 70px;">Feb</th>
                                    <th style="width: 70px;">Mar</th>
                                    <th style="width: 70px;">Apr</th>
                                    <th style="width: 70px;">May</th>
                                    <th style="width: 70px;">Jun</th>
                                    <th style="width: 70px;">Jul</th>
                                    <th style="width: 70px;">Aug</th>
                                    <th style="width: 70px;">Sep</th>
                                    <th style="width: 70px;">Oct</th>
                                    <th style="width: 70px;">Nov</th>
                                    <th style="width: 70px;">Dec</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="summary-row">
                                    <td class="employee-name">SUMMARY ACQUISITION</td>
                                    <td class="category-label">Php</td>
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                        <td class="{{ isset($regionInfo['summary']['monthly'][$month]) && $regionInfo['summary']['monthly'][$month] > 0 ? 'month-achieved' : '' }}">
                                            {{ number_format($regionInfo['summary']['monthly'][$month] ?? 0, 2) }}
                                        </td>
                                    @endforeach
                                    <td class="actual-cell">{{ number_format($regionInfo['summary']['actual'], 2) }}</td>
                                    <td class="target-cell">{{ number_format($regionInfo['summary']['target'], 2) }}</td>
                                    <td class="achievement-cell {{ $regionInfo['summary']['achievement'] >= 100 ? 'achievement-good' : ($regionInfo['summary']['achievement'] >= 50 ? 'achievement-warning' : 'achievement-danger') }}">
                                        {{ number_format($regionInfo['summary']['achievement'], 2) }}%
                                    </td>
                                </tr>
                                
                                <tr class="summary-row">
                                    <td></td>
                                    <td class="category-label">Area Distributor</td>
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                        <td>{{ $regionInfo['summary']['ad_monthly'][$month] ?? 0 }}</td>
                                    @endforeach
                                    <td class="actual-cell">{{ $regionInfo['summary']['ad_actual'] ?? 0 }}</td>
                                    <td class="target-cell">{{ $regionInfo['summary']['ad_target'] ?? 0 }}</td>
                                    <td class="achievement-cell">
                                        {{ $regionInfo['summary']['ad_target'] > 0 ? number_format(($regionInfo['summary']['ad_actual'] / $regionInfo['summary']['ad_target']) * 100, 2) : 0 }}%
                                    </td>
                                </tr>
                                
                                <tr class="summary-row">
                                    <td></td>
                                    <td class="category-label">Dealer</td>
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                        <td>{{ $regionInfo['summary']['dealer_monthly'][$month] ?? 0 }}</td>
                                    @endforeach
                                    <td class="actual-cell">{{ $regionInfo['summary']['dealer_actual'] ?? 0 }}</td>
                                    <td class="target-cell">{{ $regionInfo['summary']['dealer_target'] ?? 0 }}</td>
                                    <td class="achievement-cell">
                                        {{ $regionInfo['summary']['dealer_target'] > 0 ? number_format(($regionInfo['summary']['dealer_actual'] / $regionInfo['summary']['dealer_target']) * 100, 2) : 0 }}%
                                    </td>
                                </tr>

                                @foreach($regionInfo['employees'] as $employee)
                                <tr>
                                    <td class="employee-name" rowspan="3">
                                        <i class="ti-user mr-1"></i>{{ $employee['name'] }}
                                    </td>
                                    <td class="category-label">Php</td>
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                        <td class="{{ isset($employee['monthly'][$month]) && $employee['monthly'][$month] > 0 ? 'month-achieved' : '' }}">
                                            {{ number_format($employee['monthly'][$month] ?? 0, 2) }}
                                        </td>
                                    @endforeach
                                    <td class="actual-cell">{{ number_format($employee['actual'], 2) }}</td>
                                    <td class="target-cell">{{ number_format($employee['target'], 2) }}</td>
                                    <td class="achievement-cell {{ $employee['achievement'] >= 100 ? 'achievement-good' : ($employee['achievement'] >= 50 ? 'achievement-warning' : 'achievement-danger') }}">
                                        {{ number_format($employee['achievement'], 2) }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td class="category-label">Area Distributor</td>
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                        <td>{{ $employee['ad_monthly'][$month] ?? 0 }}</td>
                                    @endforeach
                                    <td class="actual-cell">{{ $employee['ad_actual'] }}</td>
                                    <td class="target-cell">{{ $employee['ad_target'] }}</td>
                                    <td class="achievement-cell">
                                        {{ $employee['ad_target'] > 0 ? number_format(($employee['ad_actual'] / $employee['ad_target']) * 100, 2) : 0 }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td class="category-label">Dealer</td>
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                        <td>{{ $employee['dealer_monthly'][$month] ?? 0 }}</td>
                                    @endforeach
                                    <td class="actual-cell">{{ $employee['dealer_actual'] }}</td>
                                    <td class="target-cell">{{ $employee['dealer_target'] }}</td>
                                    <td class="achievement-cell">
                                        {{ $employee['dealer_target'] > 0 ? number_format(($employee['dealer_actual'] / $employee['dealer_target']) * 100, 2) : 0 }}%
                                    </td>
                                </tr>
                                @endforeach

                                @if($regionInfo['has_vacant'])
                                <tr class="vacant-row">
                                    <td class="employee-name" rowspan="3">
                                        <i class="ti-alert mr-1"></i>VACANT
                                    </td>
                                    <td class="category-label">Php</td>
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                        <td>-</td>
                                    @endforeach
                                    <td class="actual-cell">0.00</td>
                                    <td class="target-cell">{{ number_format($regionInfo['vacant_target'], 2) }}</td>
                                    <td class="achievement-cell achievement-danger">0.00%</td>
                                </tr>
                                <tr class="vacant-row">
                                    <td class="category-label">Area Distributor</td>
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                        <td>0</td>
                                    @endforeach
                                    <td class="actual-cell">0</td>
                                    <td class="target-cell">1</td>
                                    <td class="achievement-cell">0%</td>
                                </tr>
                                <tr class="vacant-row">
                                    <td class="category-label">Dealer</td>
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                        <td>0</td>
                                    @endforeach
                                    <td class="actual-cell">0</td>
                                    <td class="target-cell">40</td>
                                    <td class="achievement-cell">0%</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class='row mt-5'>
            <div class='col-lg-6 mb-3'>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><i class="ti-bar-chart mr-2"></i>Monthly Target vs Actual</h4>
                        <div class="chart-container">
                            <canvas id="targetActualChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class='col-lg-6 mb-3'>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><i class="ti-pie-chart mr-2"></i>Package Type Distribution</h4>
                        <div class="chart-container">
                            <canvas id="packageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(document).ready(function() {
    $('#employeeSelect').select2({
        placeholder: 'Type to search employee...',
        allowClear: true,
        width: '100%',
        ajax: {
            url: '{{ route("tds.employees.search") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term || '',
                    year: $('#yearSelect').val(),
                    region: $('#regionSelect').val(),
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                console.log('Search results:', data);
                return {
                    results: data.results || []
                };
            },
            cache: true
        },
        minimumInputLength: 1,
        language: {
            inputTooShort: function() {
                return 'Type at least 1 character to search...';
            },
            searching: function() {
                return 'Searching employees...';
            },
            noResults: function() {
                return 'No employees found';
            },
            errorLoading: function() {
                return 'Error loading results';
            }
        }
    });

    $('#yearSelect, #regionSelect').on('change', function() {
        $('#employeeSelect').val(null).trigger('change');
    });

    $('#resetFilters').on('click', function() {
        $('#yearSelect').val('{{ date("Y") }}');
        $('#regionSelect').val('');
        $('#employeeSelect').val(null).trigger('change');
        $('#filterForm').submit();
    });
});

var ctx1 = document.getElementById('targetActualChart').getContext('2d');
var targetActualChart = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($chartData['monthly'])) !!},
        datasets: [{
            label: 'Target',
            data: {!! json_encode(array_values($chartData['monthly_targets'])) !!},
            backgroundColor: 'rgba(255, 193, 7, 0.7)',
            borderColor: 'rgba(255, 193, 7, 1)',
            borderWidth: 2,
            borderRadius: 4
        }, {
            label: 'Actual',
            data: {!! json_encode(array_values($chartData['monthly'])) !!},
            backgroundColor: 'rgba(40, 167, 69, 0.7)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 2,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: { size: 12, weight: '600' },
                    padding: 15
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                padding: 10,
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 11, weight: '600' } }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.05)' },
                ticks: {
                    font: { size: 11 },
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

var ctx2 = document.getElementById('packageChart').getContext('2d');
var packageChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($chartData['packages'])) !!},
        datasets: [{
            data: {!! json_encode(array_values($chartData['packages'])) !!},
            backgroundColor: [
                'rgba(108, 117, 125, 0.85)',
                'rgba(23, 162, 184, 0.85)',
                'rgba(255, 193, 7, 0.85)',
                'rgba(102, 126, 234, 0.85)'
            ],
            borderColor: [
                'rgba(108, 117, 125, 1)',
                'rgba(23, 162, 184, 1)',
                'rgba(255, 193, 7, 1)',
                'rgba(102, 126, 234, 1)'
            ],
            borderWidth: 2,
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: { size: 11, weight: '600' },
                    padding: 12,
                    usePointStyle: true
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                padding: 10,
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});
</script>
@endsection