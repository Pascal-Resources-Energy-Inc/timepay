@extends('layouts.header')

@section('content')
<style>
    .compenben-report-page .compenben-filter-panel {
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: .75rem;
    }
    .compenben-report-page .compenben-filter-panel .form-group {
        margin-bottom: 0;
    }
    .compenben-report-page .compenben-form-label {
        display: block;
        margin-bottom: .5rem;
        color: #475569;
        font-size: .875rem;
        font-weight: 600;
    }
    .compenben-report-page .compenben-filter-panel .form-control,
    .compenben-report-page .compenben-filter-action .btn {
        min-height: 42px;
    }
    .compenben-report-page .compenben-filter-action {
        display: flex;
        align-items: flex-end;
        gap: .5rem;
    }
    .compenben-report-page .compenben-filter-action .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .compenben-report-page .compenben-summary-card {
        border: 0;
        border-radius: .85rem;
        box-shadow: 0 .35rem 1rem rgba(31, 45, 86, .08);
    }
    .compenben-report-page .compenben-summary-card h5 {
        color: rgba(255, 255, 255, .9);
        font-size: .95rem;
        font-weight: 600;
    }
    .compenben-report-page .compenben-summary-count {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1;
    }
    .compenben-report-page .compenben-reference {
        color: #1d4ed8;
        font-weight: 700;
        white-space: nowrap;
    }
    .compenben-report-page .compenben-issue {
        max-width: 260px;
        white-space: normal;
        overflow-wrap: anywhere;
    }
    .compenben-report-page .compenben-view-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .compenben-report-page .compenben-empty-state {
        padding: 3rem 1rem !important;
        color: #64748b !important;
        text-align: center;
    }
    .compenben-report-page .compenben-empty-state i {
        display: block;
        margin-bottom: .75rem;
        color: #94a3b8;
        font-size: 1.75rem;
    }
    .compenben-report-page .dataTables_wrapper .dt-buttons {
        display: inline-flex;
        gap: .5rem;
        margin: 0;
    }
    .compenben-report-page .dataTables_wrapper .dt-button {
        padding: .45rem .85rem;
        margin: 0;
        color: #334155;
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: .375rem;
        box-shadow: none;
    }
    .compenben-report-page .dataTables_wrapper .dt-button:hover {
        color: #1d4ed8;
        background: #eff6ff;
        border-color: #93c5fd;
    }
    .compenben-report-page .dataTables_wrapper .dataTables_filter {
        margin: 0;
        color: #475569;
        font-size: .875rem;
    }
    .compenben-report-page .dataTables_wrapper .dataTables_filter input {
        min-height: 38px;
        margin-left: .5rem;
        border: 1px solid #cbd5e1;
        border-radius: .375rem;
    }
    .compenben-report-page .compenben-table-toolbar,
    .compenben-report-page .compenben-table-footer {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .compenben-report-page .compenben-table-toolbar {
        padding: .75rem 0;
    }
    .compenben-report-page .compenben-table-footer {
        padding-top: 1rem;
    }
    .compenben-report-page .compenben-table-footer .dataTables_info,
    .compenben-report-page .compenben-table-footer .dataTables_paginate {
        padding-top: 0;
    }

    @media (max-width: 767.98px) {
        .compenben-report-page .compenben-filter-action,
        .compenben-report-page .compenben-filter-action .btn,
        .compenben-report-page .dataTables_wrapper .dataTables_filter,
        .compenben-report-page .dataTables_wrapper .dataTables_filter input,
        .compenben-report-page .compenben-table-toolbar,
        .compenben-report-page .compenben-table-footer,
        .compenben-report-page .compenben-table-search,
        .compenben-report-page .compenben-table-actions,
        .compenben-report-page .dataTables_wrapper .dt-buttons {
            width: 100%;
        }
        .compenben-report-page .dataTables_wrapper .dataTables_filter input {
            margin-left: 0;
        }
        .compenben-report-page .dataTables_wrapper .dt-button {
            flex: 1;
        }
    }
</style>

<div class="main-panel compenben-report-page">
    <div class="content-wrapper">
        <div class="row mb-4">
            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                <div class="card card-tale compenben-summary-card">
                    <div class="card-body">
                        <h5 class="mb-4 text-nowrap">Total Reports</h5>
                        <span class="compenben-summary-count">
                            {{ count($requests) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Compensation and Benefits Report</h4>

                        <div class="compenben-filter-panel">
                            <form method="GET" action="{{ route('compenben.index') }}">
                                <div class="row">
                                    <div class="col-xl-3 col-md-5 mb-3">
                                        <div class="form-group">
                                            <label class="compenben-form-label" for="from">From</label>
                                            <input
                                                type="date"
                                                id="from"
                                                name="from"
                                                value="{{ $from }}"
                                                max="{{ date('Y-m-d') }}"
                                                class="form-control {{ $errors->has('from') ? 'is-invalid' : '' }}"
                                                onchange="setCompenbenMinimumDate(this.value);"
                                            >
                                            @if ($errors->has('from'))
                                                <div class="invalid-feedback">{{ $errors->first('from') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-5 mb-3">
                                        <div class="form-group">
                                            <label class="compenben-form-label" for="to">To</label>
                                            <input
                                                type="date"
                                                id="to"
                                                name="to"
                                                value="{{ $to }}"
                                                min="{{ $from }}"
                                                max="{{ date('Y-m-d') }}"
                                                class="form-control {{ $errors->has('to') ? 'is-invalid' : '' }}"
                                            >
                                            @if ($errors->has('to'))
                                                <div class="invalid-feedback">{{ $errors->first('to') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-2 mb-3 compenben-filter-action">
                                        <button type="submit" class="btn btn-primary">Apply filters</button>
                                        @if ($from || $to)
                                            <a href="{{ route('compenben.index') }}" class="btn btn-light border">Clear</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table id="compenbenReportTable" class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Reference</th>
                                        <th>Employee Code</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Work Location</th>
                                        <th>Issue Category</th>
                                        <th>Date Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($requests as $requestItem)
                                        <tr>
                                            <td>
                                                <a
                                                    href="{{ route('compenben.show', $requestItem->id) }}"
                                                    class="btn btn-sm btn-info compenben-view-button"
                                                    title="View complete request details"
                                                    aria-label="View request {{ $requestItem->reference_no }}"
                                                >
                                                    <i class="ti-eye" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <span class="compenben-reference">
                                                    {{ $requestItem->reference_no ?: '-' }}
                                                </span>
                                            </td>
                                            <td>{{ $requestItem->employee_code ?: '-' }}</td>
                                            <td>{{ trim($requestItem->first . ' ' . $requestItem->last) ?: '-' }}</td>
                                            <td>{{ $requestItem->email ?: '-' }}</td>
                                            <td>{{ $requestItem->work_location ?: '-' }}</td>
                                            <td class="compenben-issue">{{ $requestItem->issue ?: '-' }}</td>
                                            <td>{{ $requestItem->created_at ? $requestItem->created_at->format('M d, Y h:i A') : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="compenben-empty-state">
                                                <i class="ti-clipboard" aria-hidden="true"></i>
                                                @if ($from || $to)
                                                    No records found.
                                                @else
                                                    No records found.
                                                @endif
                                            </td>
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

<script>
    @if (count($requests) > 0)
    $(document).ready(function () {
        $('#compenbenReportTable').DataTable({
            dom: '<"compenben-table-toolbar"<"compenben-table-search"f><"compenben-table-actions"B>>rt<"compenben-table-footer"ip>',
            buttons: ['copy', 'excel'],
            order: [[1, 'desc']]
        });
    });
    @endif

    function setCompenbenMinimumDate(value) {
        var toInput = document.getElementById('to');

        if (!toInput) {
            return;
        }

        toInput.min = value;

        if (toInput.value && value && toInput.value < value) {
            toInput.value = value;
        }
    }
</script>
@endsection
