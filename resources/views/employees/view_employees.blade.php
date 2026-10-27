@extends('layouts.header')
@section('content')
<style>
    .employee-overview {
        margin-bottom: 0.25rem;
    }

    .employee-overview .card {
        border: 0;
    }

    .employee-breakdown-card {
        width: 100%;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(31, 45, 61, 0.08);
    }

    .employee-breakdown-card .card-body {
        padding: 1.5rem;
    }

    .employee-overview-heading {
        display: flex;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .employee-overview-heading-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        margin-right: 0.75rem;
        border-radius: 12px;
        background: #edf2ff;
        color: #4f6fe7;
        font-size: 1.2rem;
    }

    .employee-overview-title {
        margin: 0 0 0.2rem;
        color: #1f2937;
        font-size: 1.05rem;
        font-weight: 700;
    }

    .employee-overview-subtitle {
        margin: 0;
        color: #8a94a6;
        font-size: 0.8rem;
    }

    .employee-breakdown-panel {
        height: 100%;
        padding: 1rem;
        border: 1px solid #e8edf5;
        border-radius: 12px;
        background: #f8fafc;
    }

    .employee-breakdown-title {
        display: flex;
        align-items: center;
        margin: 0 0 0.55rem;
        color: #354052;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .employee-breakdown-title i {
        margin-right: 0.45rem;
        color: #667eea;
        font-size: 1rem;
    }

    .employee-breakdown-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 42px;
        padding: 0.55rem 0.45rem;
        border-bottom: 1px solid #e5eaf2;
        color: #364152;
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    .employee-breakdown-row:last-child {
        border-bottom: 0;
    }

    .employee-breakdown-row:hover {
        border-radius: 8px;
        background: #eef3ff;
        color: #3156d3;
        text-decoration: none;
    }

    .employee-breakdown-label {
        min-width: 0;
        padding-right: 0.75rem;
        overflow: hidden;
        font-size: 0.88rem;
        font-weight: 500;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .employee-breakdown-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        padding: 0.25rem 0.55rem;
        border-radius: 999px;
        background: #e5ecff;
        color: #3156d3;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .employee-breakdown-empty {
        padding: 1rem 0.45rem 0.5rem;
        color: #9aa4b2;
        font-size: 0.82rem;
    }

    .employee-stat-column {
        display: flex;
    }

    .employee-stat-card {
        position: relative;
        width: 100%;
        min-height: 185px;
        overflow: hidden;
        border-radius: 16px;
        color: #fff;
        box-shadow: 0 8px 22px rgba(31, 45, 61, 0.12);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .employee-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(31, 45, 61, 0.17);
    }

    .employee-stat-card::after {
        position: absolute;
        right: -34px;
        bottom: -52px;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
        content: "";
    }

    .employee-stat-card .card-body {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1.35rem 1.2rem;
    }

    .employee-stat-card--clearance {
        background: linear-gradient(145deg, #5577f2, #7d9aff);
    }

    .employee-stat-card--cleared {
        background: linear-gradient(145deg, #ef6675, #f58a93);
    }

    .employee-stat-card--active {
        background: linear-gradient(145deg, #20a86c, #55c98b);
    }

    .employee-stat-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .employee-stat-label {
        max-width: 92px;
        font-size: 0.92rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .employee-stat-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.18);
        font-size: 1rem;
    }

    .employee-stat-value {
        margin-top: 1.25rem;
        font-size: 2.35rem;
        font-weight: 500;
        line-height: 1;
    }

    .employee-stat-caption {
        margin-top: 0.35rem;
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.75rem;
    }

    @media (max-width: 1199.98px) {
        .employee-stat-card {
            min-height: 155px;
        }
    }

    @media (max-width: 767.98px) {
        .employee-breakdown-column + .employee-breakdown-column,
        .employee-stat-column + .employee-stat-column {
            margin-top: 1rem;
        }

        .employee-stat-card {
            min-height: 145px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .employee-stat-card,
        .employee-breakdown-row {
            transition: none;
        }
    }

    .employee-directory-card {
        width: 100%;
        border: 0;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(31, 45, 61, 0.08);
    }

    .employee-directory-card .card-body {
        padding: 1.5rem;
    }

    .employee-report-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding-bottom: 1rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .employee-report-toolbar h4 {
        margin: 0 0 0.25rem;
        color: #1f2937;
        font-size: 1.15rem;
        font-weight: 700;
    }

    .employee-report-toolbar p {
        margin: 0;
        color: #64748b;
        font-size: 0.85rem;
    }

    .employee-header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .employee-header-actions .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        padding: 0;
        border-radius: 10px;
    }

    .employee-filter-panel {
        padding: 1.25rem 1.25rem 0.25rem;
        margin-bottom: 1.25rem;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
    }

    .employee-filter-panel .form-group {
        margin-bottom: 0;
    }

    .employee-filter-panel .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .employee-filter-panel .form-control {
        min-height: 42px;
        border-color: #cbd5e1;
        border-radius: 6px;
        background-color: #fff;
    }

    .employee-filter-panel .form-control:focus {
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .employee-filter-panel .select2-container {
        display: block;
        width: 100% !important;
    }

    .employee-filter-panel .select2-container .select2-selection--single {
        display: flex;
        align-items: center;
        width: 100%;
        height: 42px !important;
        padding: 0 !important;
        border-color: #cbd5e1;
        border-radius: 6px;
        background: #fff;
    }

    .employee-filter-panel .select2-container .select2-selection--single .select2-selection__rendered {
        width: 100%;
        padding: 0 2.5rem 0 0.875rem !important;
        overflow: hidden;
        color: #334155;
        font-size: 0.875rem;
        line-height: 1.25 !important;
        text-align: left;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .employee-filter-panel .select2-container .select2-selection--single .select2-selection__placeholder {
        color: #94a3b8;
    }

    .employee-filter-panel .select2-container .select2-selection--single .select2-selection__arrow {
        top: 0 !important;
        right: 0 !important;
        width: 2.25rem !important;
        height: 40px !important;
    }

    .employee-filter-actions {
        display: flex;
        align-items: flex-end;
        gap: 0.5rem;
    }

    .employee-filter-actions .btn {
        flex: 1;
        min-height: 42px;
        padding-right: 0.75rem;
        padding-left: 0.75rem;
        border-radius: 8px;
    }

    .employee-table {
        min-width: 1180px;
        margin-bottom: 0;
    }

    .employee-table thead th {
        padding: 0.85rem 0.75rem;
        border-color: #dbe2ea;
        background: #f8fafc;
        color: #334155;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        white-space: nowrap;
    }

    .employee-table tbody td {
        padding: 0.75rem;
        border-color: #e2e8f0;
        color: #334155;
        font-size: 0.84rem;
        vertical-align: middle;
    }

    .employee-table tbody tr:hover {
        background: #f8fbff;
    }

    .employee-code-cell,
    .employee-person {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .employee-edit-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        border-radius: 8px;
        background: #ecfdf5;
        color: #16a34a;
    }

    .employee-edit-link:hover {
        background: #dcfce7;
        color: #15803d;
        text-decoration: none;
    }

    .employee-table-avatar {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        border: 2px solid #fff;
        border-radius: 50%;
        background: #e5e7eb;
        box-shadow: 0 2px 7px rgba(15, 23, 42, 0.12);
        object-fit: cover;
    }

    .employee-person-name {
        font-weight: 600;
        white-space: nowrap;
    }

    .employee-status {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.65rem;
        border-radius: 999px;
        background: #f1f5f9;
        color: #475569;
        font-size: 0.73rem;
        font-weight: 700;
    }

    .employee-status--active {
        background: #dcfce7;
        color: #15803d;
    }

    .employee-status--inactive,
    .employee-status--resigned,
    .employee-status--terminated {
        background: #fee2e2;
        color: #b91c1c;
    }

    .employee-status--hbu {
        background: #fef3c7;
        color: #a16207;
    }

    .employee-empty-state {
        padding: 2.75rem 1rem !important;
        color: #94a3b8 !important;
        text-align: center;
    }

    .employee-pagination {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding-top: 1rem;
        margin-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .employee-pagination p {
        margin: 0;
        color: #64748b;
        font-size: 0.84rem;
    }

    .employee-pagination .pagination {
        margin-bottom: 0;
    }

    @media (max-width: 767.98px) {
        .employee-directory-card .card-body,
        .employee-filter-panel {
            padding: 1rem;
        }

        .employee-report-toolbar {
            align-items: flex-start;
        }

        .employee-header-actions {
            width: 100%;
        }

        .employee-filter-actions,
        .employee-pagination {
            align-items: stretch;
        }

        .employee-pagination {
            flex-direction: column;
        }
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row employee-overview">
            <div class="col-xl-7 grid-margin stretch-card">
                <div class="card employee-breakdown-card">
                    <div class="card-body">
                        <div class="employee-overview-heading">
                            <span class="employee-overview-heading-icon" aria-hidden="true">
                                <i class="fa fa-users"></i>
                            </span>
                            <div>
                                <h4 class="employee-overview-title">Employee overview</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 employee-breakdown-column">
                                <section class="employee-breakdown-panel" aria-labelledby="classification-summary-title">
                                    <h5 class="employee-breakdown-title" id="classification-summary-title">
                                        Classification
                                    </h5>
                                    @forelse($employees_classification as $item)
                                        <a class="employee-breakdown-row" href="{{url('/employees?classification=' . ($item->classification_info ? $item->classification_info->id : 'N/A'))}}">
                                            <span class="employee-breakdown-label">
                                                {{$item->classification_info ? $item->classification_info->name : ($item->classification ?: 'Unspecified')}}
                                            </span>
                                            <span class="employee-breakdown-count">{{$item->total}}</span>
                                        </a>
                                    @empty
                                        <div class="employee-breakdown-empty">No classification data available.</div>
                                    @endforelse
                                </section>
                            </div>

                            <div class="col-md-6 employee-breakdown-column">
                                <section class="employee-breakdown-panel" aria-labelledby="gender-summary-title">
                                    <h5 class="employee-breakdown-title" id="gender-summary-title">
                                        Gender (Active)
                                    </h5>
                                    @forelse($employees_gender as $item)
                                        <a class="employee-breakdown-row" href="{{url('/employees?gender=' . ($item->gender ?: 'N/A'))}}">
                                            <span class="employee-breakdown-label">{{$item->gender ?: 'Unspecified'}}</span>
                                            <span class="employee-breakdown-count">{{$item->total}}</span>
                                        </a>
                                    @empty
                                        <div class="employee-breakdown-empty">No gender data available.</div>
                                    @endforelse
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 grid-margin">
                <div class="row">
                    <div class="col-md-4 employee-stat-column">
                        <div class="card employee-stat-card employee-stat-card--clearance">
                            <div class="card-body">
                                <div class="employee-stat-header">
                                    <span class="employee-stat-label">For clearance</span>
                                    <span class="employee-stat-icon" aria-hidden="true"><i class="fa fa-clock-o"></i></span>
                                </div>
                                <div>
                                    <div class="employee-stat-value">0</div>
                                    <div class="employee-stat-caption">Employees</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 employee-stat-column">
                        <div class="card employee-stat-card employee-stat-card--cleared">
                            <div class="card-body">
                                <div class="employee-stat-header">
                                    <span class="employee-stat-label">Cleared</span>
                                    <span class="employee-stat-icon" aria-hidden="true"><i class="fa fa-check"></i></span>
                                </div>
                                <div>
                                    <div class="employee-stat-value">0</div>
                                    <div class="employee-stat-caption">Employees</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 employee-stat-column">
                        <div class="card employee-stat-card employee-stat-card--active">
                            <div class="card-body">
                                <div class="employee-stat-header">
                                    <span class="employee-stat-label">Active</span>
                                    <span class="employee-stat-icon" aria-hidden="true"><i class="fa fa-users"></i></span>
                                </div>
                                <div>
                                    <div class="employee-stat-value">{{$employees_active}}</div>
                                    <div class="employee-stat-caption">Employees</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card employee-directory-card">
                    <div class="card-body">
                        <div class="employee-report-toolbar">
                            <div>
                                <h4>Employees</h4>
                                <p>Search, filter, and manage employee records.</p>
                            </div>
                            <div class="employee-header-actions">
                                @if (checkUserPrivilege('employees_add',auth()->user()->id) == 'yes')
                                    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#newEmployee" title="Add employee" aria-label="Add employee">
                                        <i class="ti-plus"></i>
                                    </button>
                                    @if(auth()->user()->id == '856' || auth()->user()->id == '786')
                                        <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#uploadEmployee" title="Upload employees" aria-label="Upload employees">
                                            <i class="ti-arrow-up"></i>
                                        </button>
                                    @endif
                                    @if(auth()->user()->id === '856')
                                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#uploadEmployeeRevertRate" title="Upload employee rates" aria-label="Upload employee rates">
                                            <i class="ti-upload"></i>
                                        </button>
                                    @endif
                                @endif
                                @if (checkUserPrivilege('employees_export',auth()->user()->id) == 'yes')
                                    <a href="/employees-export?company={{$company}}&department={{$department}}&status={{$status}}" class="btn btn-outline-danger" title="Export OTPMS" aria-label="Export OTPMS">
                                        <i class="ti-download"></i>
                                    </a>
                                    @if(auth()->user()->id == '660' || auth()->user()->id == '1' || auth()->user()->id == '1202')
                                        <a href="/associate-employees-export?company={{$company}}&department={{$department}}&status={{$status}}" class="btn btn-outline-warning" title="Export employee associates" aria-label="Export employee associates">
                                            <i class="ti-download"></i>
                                        </a>
                                    @endif
                                @endif
                                @if (checkUserPrivilege('employees_export_hr',auth()->user()->id) == 'yes')
                                    <a href="/employees-export-hr?company={{$company}}&department={{$department}}&status={{$status}}" class="btn btn-outline-primary" title="Export HR details" aria-label="Export HR details">
                                        <i class="ti-download"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="employee-filter-panel">
                            <form method="get" onsubmit="show();">
                                <div class="row">
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="employee-search">Search</label>
                                            <input id="employee-search" type="text" class="form-control" name="search" placeholder="Search Name / Biometric Code" value="{{$search}}">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="employee-company">Company</label>
                                            <select id="employee-company" data-placeholder="Select Company" class="form-control required js-example-basic-single" style="width:100%;" name="company">
                                                <option value="">All companies</option>
                                                @foreach($companies as $comp)
                                                    <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="employee-department">Department</label>
                                            <select id="employee-department" data-placeholder="Select Department" class="form-control required js-example-basic-single" style="width:100%;" name="department">
                                                <option value="">All departments</option>
                                                @foreach($departments as $dep)
                                                    <option value="{{$dep->id}}" @if ($dep->id == $department) selected @endif>{{$dep->name}} - {{$dep->code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="employee-status">Status</label>
                                            <select id="employee-status" data-placeholder="Select Status" class="form-control required js-example-basic-single" style="width:100%;" name="status">
                                                <option value="">All statuses</option>
                                                <option value="Active" @if ($status == 'Active') selected @endif>Active</option>
                                                <option value="Inactive" @if ($status == 'Inactive') selected @endif>Inactive</option>
                                                <option value="Resigned" @if ($status == 'Resigned') selected @endif>Resigned</option>
                                                <option value="Terminated" @if ($status == 'Terminated') selected @endif>Terminated</option>
                                                <option value="HBU" @if ($status == 'HBU') selected @endif>HBU</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-1 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="entriesSelect">Per page</label>
                                            <select id="entriesSelect" class="form-control" name="entries" onchange="updateEntries()">
                                                <option value="10" {{ request('entries', 10) == 10 ? 'selected' : '' }}>10</option>
                                                <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                                                <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                                                <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                                                <option value="500" {{ request('entries') == 500 ? 'selected' : '' }}>500</option>
                                                <option value="1000" {{ request('entries') == 1000 ? 'selected' : '' }}>1000</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-6 mb-3 employee-filter-actions">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="/employees" class="btn btn-warning">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered employee-table" aria-label="Employee records">
                                <thead>
                                    <tr>
                                        <th>Employee Code</th>
                                        <th>User ID</th>
                                        <th>Employee</th>
                                        <th>Company</th>
                                        <th>Department</th>
                                        <th>Location</th>
                                        <th>Classification</th>
                                        <th>Immediate Supervisor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employees as $employee)
                                        <tr>
                                            <td>
                                                <div class="employee-code-cell">
                                                    @if (checkUserPrivilege('employees_view',auth()->user()->id) == 'yes')
                                                        <a href="/account-setting-hr/{{$employee->user_id}}" class="employee-edit-link" title="View employee" aria-label="View {{$employee->first_name}} {{$employee->last_name}}">
                                                            <i class="ti-pencil"></i>
                                                        </a>
                                                    @endif
                                                    <span>{{$employee->employee_code}}</span>
                                                </div>
                                            </td>
                                            <td>{{$employee->user_id}}</td>
                                            <td>
                                                <div class="employee-person">
                                                    <img class="employee-table-avatar" src="{{URL::asset($employee->avatar)}}" alt="" onerror="this.src='{{URL::asset('/images/no_image.png')}}';">
                                                    <span class="employee-person-name">{{$employee->last_name}}, {{$employee->first_name}}</span>
                                                </div>
                                            </td>
                                            <td>{{$employee->company ? $employee->company->company_code : '-'}}</td>
                                            <td>{{$employee->department ? $employee->department->name : '-'}}</td>
                                            <td>{{$employee->location ?: '-'}}</td>
                                            <td>{{$employee->classification_info ? $employee->classification_info->name : 'Unspecified'}}</td>
                                            <td>
                                                @if($employee->immediate_sup_data)
                                                    <div class="employee-person">
                                                        <img class="employee-table-avatar" src="{{URL::asset($employee->immediate_sup_data->employee->avatar)}}" alt="" onerror="this.src='{{URL::asset('/images/no_image.png')}}';">
                                                        <span class="employee-person-name">{{$employee->immediate_sup_data->name}}</span>
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span class="employee-status employee-status--{{strtolower($employee->status)}}">{{$employee->status}}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="employee-empty-state">No employee records match the selected filters.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($employees->total())
                            <div class="employee-pagination">
                                <p>Showing {{$employees->firstItem()}}-{{$employees->lastItem()}} of {{$employees->total()}} employee records</p>
                                @if($employees->hasPages())
                                    <div>{{$employees->links()}}</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('employees.updateEmployeeRate')
@include('employees.uploadEmployee')
@include('employees.newEmployee')
{{-- @include('employees.capture_image') --}}


@endsection
@section('footer')
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/js/inputmask.js') }}"></script>
<script>
    function updateEntries() {
        const entries = document.getElementById('entriesSelect').value;
        const url = new URL(window.location.href);
        url.searchParams.set('entries', entries);
        window.location.href = url.toString();
    }
</script>

@endsection
