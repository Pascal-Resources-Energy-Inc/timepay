@extends('layouts.header')

@section('content')
<style>
    .id-print-page .card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .id-print-page .page-subtitle {
        color: #64748b;
        font-size: 14px;
    }

    .id-print-page .search-wrap {
        position: relative;
        width: 100%;
    }

    .id-print-page .search-wrap i {
        color: #94a3b8;
        left: 14px;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }

    .id-print-page .search-wrap input {
        border-color: #dbe3ec;
        border-radius: 8px;
        height: 40px;
        padding-left: 40px;
    }

    .id-print-page .request-table thead th {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        border-top: 0;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .03em;
        padding: 12px 10px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .id-print-page .request-table tbody td {
        border-color: #eef2f7;
        padding: 12px 10px;
        vertical-align: middle;
    }

    .id-print-page .request-row {
        cursor: pointer;
        transition: background .15s ease;
    }

    .id-print-page .request-row:hover,
    .id-print-page .request-row.is-selected {
        background: #f0f7ff;
    }

    .id-print-page .employee-avatar {
        align-items: center;
        background: #e8f1ff;
        border: 1px solid #dbeafe;
        border-radius: 50%;
        color: #2563eb;
        display: flex;
        flex-shrink: 0;
        font-size: 12px;
        font-weight: 700;
        height: 38px;
        justify-content: center;
        overflow: hidden;
        width: 38px;
    }

    .id-print-page .employee-avatar img {
        height: 100%;
        object-fit: cover;
        width: 100%;
    }

    .id-print-page .selection-panel {
        position: sticky;
        top: 85px;
    }

    .id-print-page .selected-list {
        max-height: 390px;
        overflow-y: auto;
    }

    .id-print-page .selected-item {
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 11px;
    }

    .id-print-page .summary-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 12px;
    }

    .id-print-page .empty-selection {
        border: 1px dashed #cbd5e1;
        border-radius: 9px;
        color: #64748b;
        padding: 34px 18px;
        text-align: center;
    }

    .id-print-page .print-button {
        border-radius: 8px;
        font-weight: 600;
        min-height: 44px;
    }

    .id-print-page .filter-label {
        color: #64748b;
        display: block;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .id-print-page .filter-control {
        border-color: #dbe3ec;
        border-radius: 8px;
        height: 40px;
        font-size: 13px;
        max-width: 100%;
        min-width: 0;
        width: 100%;
    }

    .id-print-page .request-filter-row {
        align-items: end;
        display: flex;
        flex-wrap: nowrap;
        gap: 8px;
        max-width: 100%;
        width: 100%;
    }

    .id-print-page .filter-field {
        max-width: 100%;
        min-width: 0;
    }

    .id-print-page .search-filter-field {
        flex: 1 1 auto;
    }

    .id-print-page .date-filter-field {
        flex: 0 1 115px;
    }

    .id-print-page .status-filter-field {
        flex: 0 1 90px;
    }

    .id-print-page .filter-field input,
    .id-print-page .filter-field select {
        max-width: 100%;
        min-width: 0;
        width: 100%;
    }

    .id-print-page .filter-actions {
        display: flex;
        flex: 0 0 auto;
        gap: 6px;
    }

    .id-print-page .filter-action-btn {
        align-items: center;
        border-color: #dbe3ec;
        border-radius: 8px;
        display: inline-flex;
        height: 36px;
        justify-content: center;
        padding: 0;
        width: 36px;
    }

    .id-print-page .pagination .page-link {
        border-color: #e2e8f0;
        color: #2563eb;
        cursor: pointer;
        min-width: 34px;
        text-align: center;
    }

    .id-print-page .pagination .page-item.active .page-link {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    @media (max-width: 991.98px) {
        .id-print-page .selection-panel {
            margin-top: 18px;
            position: static;
        }
    }
</style>

<div class="main-panel">
    <div class="content-wrapper id-print-page">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
            <div>
                <a href="#" onclick="return returnToPreviousPage(event);" class="text-muted small d-inline-block mb-2">
                    <i class="ti-arrow-left mr-1"></i> Back to Previous Page
                </a>
                <h3 class="font-weight-bold mb-1">Print Employee IDs</h3>
                <p class="page-subtitle mb-0">Choose one request for individual printing or select several requests for a batch.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card rounded">
                    <div class="card-header rounded-top bg-white border-bottom py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="font-weight-bold mb-1">ID Requests</h5>
                            </div>
                            <!-- <span class="badge badge-primary px-3 py-2" id="table-selection-count">0 selected</span> -->
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="GET" action="{{ url('/batch-print-form') }}" id="request-filter-form">
                            <div class="request-filter-row mb-3">
                                <div class="filter-field search-filter-field">
                                    <label class="filter-label" for="request-search">Search</label>
                                    <div class="search-wrap">
                                        <i class="ti-search"></i>
                                        <input type="search" name="search" id="request-search" class="form-control" value="{{ request('search') }}" placeholder="Employee / Reference No.">
                                    </div>
                                </div>

                                <div class="filter-field date-filter-field">
                                    <label class="filter-label" for="filter-from">From</label>
                                    <input type="date" name="from" id="filter-from" class="form-control filter-control" value="{{ request('from') }}">
                                </div>

                                <div class="filter-field date-filter-field">
                                    <label class="filter-label" for="filter-to">To</label>
                                    <input type="date" name="to" id="filter-to" class="form-control filter-control" value="{{ request('to') }}">
                                </div>

                                <!-- status sorting -->
                                <div class="filter-field status-filter-field">
                                    <label class="filter-label" for="filter-progress">Status</label>
                                    <select name="progress" id="filter-progress" class="form-control filter-control">
                                        <option value="">All</option>
                                        <option value="from-import" {{ request('progress') === 'from-import' ? 'selected' : '' }}>From Import</option>
                                        <option value="not-printed" {{ request('progress') === 'not-printed' ? 'selected' : '' }}>Not Printed & Unreleased</option>
                                        <option value="released-not-printed" {{ request('progress') === 'released-not-printed' ? 'selected' : '' }}>Not Printed - Released</option>
                                        <option value="printed-awaiting-release" {{ request('progress') === 'printed-awaiting-release' ? 'selected' : '' }}>Printed - Awaiting for Release</option>
                                        <option value="printed-released" {{ request('progress') === 'printed-released' ? 'selected' : '' }}>Printed &amp; Released</option>
                                    </select>
                                </div>

                                <!-- apply & reset filters -->
                                <div class="filter-actions">
                                    <button type="submit" class="btn btn-light filter-action-btn" title="Apply filters" aria-label="Apply filters">
                                        <i class="ti-filter"></i>
                                    </button>
                                    <a href="{{ url('/batch-print-form') }}" id="clear-filters" class="btn btn-light filter-action-btn" title="Clear filters" aria-label="Clear filters">
                                        <i class="ti-reload"></i>
                                    </a>
                                </div>

                            </div>

                        </form>

                        <div class="table-responsive">
                            <table class="table request-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:42px;">
                                            <input type="checkbox" id="select-all-requests" aria-label="Select all filtered requests">
                                        </th>
                                        <th class="text-center" style="width:60px;">Edit</th>
                                        <th>Employee</th>
                                        <th>Ref No.</th>
                                        <th>Location</th>
                                        <th>Position / Department</th>
                                        <th>Status</th>
                                        <!-- <th>Status</th> -->
                                    </tr>
                                </thead>
                                <tbody id="request-table-body">
                                    @forelse ($iurs as $iur)
                                        @php
                                            $employee = $iur->user;
                                            $fullName = trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '')) ?: 'Unknown Employee';
                                            $department = $employee && $employee->department ? $employee->department->name : 'No department';
                                            $position = $employee->position ?? 'No position';
                                            $employeeCode = $employee->employee_code ?? 'No employee number';
                                            $isSelected = $selectedRefs->contains($iur->iur_reference);
                                            $isReleased = $iur->accountabilities->sum('released_id') > 0;
                                            $isPrinted = !is_null($iur->id_printed_at);
                                            $isImported = $iur->status === 'From Import';
                                            $hasPhoto = !empty($iur->id_picture);
                                            $progress = $isReleased && !$isPrinted
                                                ? 'released-not-printed'
                                                : ($isPrinted && !$isReleased
                                                    ? 'printed-awaiting-release'
                                                    : ($isPrinted && $isReleased ? 'printed-released' : 'not-printed'));
                                            $initials = strtoupper(substr($employee->first_name ?? 'E', 0, 1) . substr($employee->last_name ?? '', 0, 1));
                                            $searchText = strtolower($fullName . ' ' . $employeeCode . ' ' . $department . ' ' . $position . ' ' . $iur->iur_reference);
                                        @endphp
                                        <tr class="request-row {{ $isSelected ? 'is-selected' : '' }}"
                                            data-request-row
                                            data-reference="{{ $iur->iur_reference }}"
                                            data-name="{{ $fullName }}"
                                            data-employee-code="{{ $employeeCode }}"
                                            data-department="{{ $department }}"
                                            data-initials="{{ $initials }}"
                                            data-photo="{{ $hasPhoto ? asset($iur->id_picture) : '' }}">
                                            <td>
                                                <input type="checkbox" class="request-checkbox" value="{{ $iur->iur_reference }}" {{ $isSelected ? 'checked' : '' }} aria-label="Select {{ $fullName }}">
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ url('/account-setting-hr/' . $employee->user_id) . '?iur_id=' . $iur->id }}"
                                                   target="_blank"
                                                   class="btn btn-outline-secondary btn-sm border-0"
                                                   title="Edit {{ $fullName }}"
                                                   aria-label="Edit {{ $fullName }}">
                                                    <i class="ti-pencil"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center" style="min-width:190px;">
                                                    <span class="employee-avatar mr-2">
                                                        @if ($hasPhoto)
                                                            <img src="{{ asset($iur->id_picture) }}" alt="{{ $fullName }}">
                                                        @else
                                                            {{ $initials }}
                                                        @endif
                                                    </span>
                                                    <span>
                                                        <strong class="d-block text-dark" style="font-size:13px;">{{ $fullName }}</strong>
                                                        <small class="text-muted">{{ $employeeCode }}</small>
                                                    </span>
                                                </div>
                                            </td>
                                            <td><strong class="text-primary" style="font-size:12px;">{{ $iur->iur_reference }}</strong></td>
                                            <td>{{ $iur->work_location }}</td>
                                            <td>
                                                <span class="d-block text-dark" style="font-size:12px;">{{ $position }}</span>
                                                <small class="text-muted">{{ $department }}</small>
                                            </td>
                                            <td>
                                                @if ($isImported)
                                                    <span class="badge badge-primary">
                                                        From Import
                                                    </span>

                                                <!-- released but not printed yet -->
                                                @elseif ($isReleased && !$isPrinted)
                                                    <span class="badge badge-danger">
                                                        Not Printed & Released
                                                    </span>

                                                <!-- printed but not released yet -->
                                                @elseif ($isPrinted && !$isReleased)
                                                    <span class="badge badge-warning">
                                                        Awaiting for Release - Printed
                                                    </span>

                                                <!-- printed and released -->
                                                @elseif ($isPrinted && $isReleased)
                                                    <span class="badge badge-success">
                                                        Printed & Released
                                                    </span>

                                                    @if ($iur->id_print_count > 1)
                                                        <small class="d-block text-muted mt-1">
                                                            Printed {{ $iur->id_print_count }} times
                                                        </small>
                                                    @endif

                                                @else
                                                    <span class="badge badge-secondary text-white">
                                                        Not Printed & Unreleased
                                                    </span>
                                                @endif
                                            </td>
                                            <!-- <td> -->
                                            <!--     @if (!$hasPhoto) -->
                                            <!--         <span class="badge badge-warning">Missing photo</span> -->
                                            <!--     @elseif ($isReleased) -->
                                            <!--         <span class="badge badge-primary">Released</span> -->
                                            <!--     @else -->
                                            <!--         <span class="badge badge-warning">For Printing</span> -->
                                            <!--     @endif -->
                                            <!-- </td> -->
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">No ID requests are available for printing.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($iurs->total() > 0)
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between border-top pt-3 mt-3">
                            <small class="text-muted mb-2 mb-md-0">
                                Showing {{ $iurs->firstItem() }}–{{ $iurs->lastItem() }} of {{ $iurs->total() }} requests
                            </small>
                            <div class="d-flex align-items-center">
                                <label for="rows-per-page" class="small text-muted mb-0 mr-2 text-nowrap">Rows per page</label>
                                <select id="rows-per-page" class="form-control form-control-sm mr-3" style="width:72px;" form="request-filter-form" name="per_page">
                                    <option value="25" {{ (int) request('per_page', 25) === 25 ? 'selected' : '' }}>25</option>
                                    <option value="30" {{ (int) request('per_page', 25) === 30 ? 'selected' : '' }}>30</option>
                                    <option value="50" {{ (int) request('per_page', 25) === 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ (int) request('per_page', 25) === 100 ? 'selected' : '' }}>100</option>
                                </select>
                                {{ $iurs->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card rounded-top selection-panel">
                    <div class="card-header rounded-top bg-white border-bottom py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="font-weight-bold mb-1">Selected IUR References</h5>
                            </div>
                            <span class="badge badge-primary" id="selected-count-badge">0</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="empty-selection" class="empty-selection">
                            <i class="ti-id-badge d-block mb-2" style="font-size:30px;"></i>
                            <strong class="d-block text-dark mb-1">No IDs selected</strong>
                            <small>Select a row from the table to add it here.</small>
                        </div>

                        <div id="selected-list" class="selected-list"></div>

                        <!-- <hr> -->

                        <div class="row mx-n1 mb-3">
                            <!-- <div class="col-6 px-1"> -->
                            <!--     <div class="summary-box h-100"> -->
                            <!--         <small class="text-muted d-block">IDs selected</small> -->
                            <!--         <strong class="h4 mb-0" id="summary-selected-count">0</strong> -->
                            <!--     </div> -->
                            <!-- </div> -->
                            <!-- <div class="col-6 px-1"> -->
                            <!--     <div class="summary-box h-100"> -->
                            <!--         <small class="text-muted d-block">Estimated pages</small> -->
                            <!--         <strong class="h4 mb-0" id="summary-page-count">0</strong> -->
                            <!--     </div> -->
                            <!-- </div> -->
                        </div>

                        <div id="selection-error" class="alert alert-warning py-2 px-3 d-none" style="font-size:12px;">
                            Select at least one ID request before continuing.
                        </div>

                        <button type="button" id="btn-print" class="btn btn-primary btn-block print-button" disabled>
                            <i class="ti-printer mr-1"></i>
                            <span id="print-button-label">Select IDs to continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function returnToPreviousPage(event) {
    if (event) event.preventDefault();

    if (window.opener && !window.opener.closed) {
        window.opener.focus();
        window.close();
        return false;
    }

    if (window.history.length > 1) {
        window.history.back();
        return false;
    }

    window.location.href = '{{ url('iur-approval') }}';
    return false;
}

$(function() {
    var $checkboxes = $('.request-checkbox');
    var $selectAll = $('#select-all-requests');
    var $rowsPerPage = $('#rows-per-page');
    var storageKey = 'batchPrintSelectedIurs';
    var selectedItems = loadSelections();

    function loadSelections() {
        try {
            return JSON.parse(window.sessionStorage.getItem(storageKey)) || {};
        } catch (error) {
            return {};
        }
    }

    function saveSelections() {
        try {
            window.sessionStorage.setItem(storageKey, JSON.stringify(selectedItems));
        } catch (error) {
            // Selection still works for the current page when browser storage is unavailable.
        }
    }

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    function rowData($row) {
        return {
            reference: String($row.data('reference')),
            name: String($row.data('name') || 'Unknown Employee'),
            employeeCode: String($row.data('employee-code') || 'No employee number'),
            department: String($row.data('department') || 'No department'),
            initials: String($row.data('initials') || 'E'),
            photo: String($row.data('photo') || '')
        };
    }

    function selectedReferences() {
        return Object.keys(selectedItems);
    }

    function updateSelectAllState() {
        var checkedCount = $checkboxes.filter(':checked').length;
        $selectAll.prop('checked', $checkboxes.length > 0 && checkedCount === $checkboxes.length);
        $selectAll.prop('indeterminate', checkedCount > 0 && checkedCount < $checkboxes.length);
    }

    function renderSelectedItems() {
        var refs = selectedReferences();
        var $list = $('#selected-list').empty();

        refs.forEach(function(ref) {
            var item = selectedItems[ref];
            var avatar = item.photo
                ? '<img src="' + escapeHtml(item.photo) + '" alt="' + escapeHtml(item.name) + '">' 
                : escapeHtml(item.initials);

            $list.append(
                '<div class="selected-item mb-2" data-selected-card="' + escapeHtml(ref) + '">' +
                    '<div class="d-flex align-items-start">' +
                        '<span class="employee-avatar mr-2">' + avatar + '</span>' +
                        '<div class="flex-grow-1" style="min-width:0;">' +
                            '<strong class="d-block text-dark text-truncate" style="font-size:13px;">' + escapeHtml(item.name) + '</strong>' +
                            '<small class="text-muted d-block text-truncate">' + escapeHtml(item.employeeCode) + ' &middot; ' + escapeHtml(item.department) + '</small>' +
                            '<small class="text-primary font-weight-bold">' + escapeHtml(ref) + '</small>' +
                        '</div>' +
                        '<button type="button" class="btn btn-link text-danger p-1 ml-2 remove-selection" data-reference="' + escapeHtml(ref) + '" title="Remove ' + escapeHtml(item.name) + '">' +
                            '<i class="ti-close"></i>' +
                        '</button>' +
                    '</div>' +
                '</div>'
            );
        });

        var count = refs.length;
        var pages = count ? Math.ceil(count / 3) : 0;

        $('#selected-count-badge').text(count);
        $('#summary-selected-count').text(count);
        $('#summary-page-count').text(pages);
        $('#empty-selection').toggleClass('d-none', count > 0);
        $('#selection-error').addClass('d-none');
        $('#btn-print').prop('disabled', count === 0);
        $('#print-button-label').text(count === 0
            ? 'Select IDs to continue'
            : 'Preview');

        updateSelectAllState();
    }

    $rowsPerPage.on('change', function() {
        $('#request-filter-form').submit();
    });

    $(document).on('click', '[data-request-row]', function(event) {
        if ($(event.target).is('input, button, a') || $(event.target).closest('button, a').length) return;
        var $checkbox = $(this).find('.request-checkbox');
        $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
    });

    $checkboxes.on('change', function() {
        var $checkbox = $(this);
        var $row = $checkbox.closest('[data-request-row]');
        var item = rowData($row);

        if (this.checked) {
            selectedItems[item.reference] = item;
        } else {
            delete selectedItems[item.reference];
        }

        $row.toggleClass('is-selected', this.checked);
        saveSelections();
        renderSelectedItems();
    });

    $selectAll.on('change', function() {
        var shouldSelect = this.checked;

        $checkboxes.each(function() {
            var $checkbox = $(this);
            var item = rowData($checkbox.closest('[data-request-row]'));
            $checkbox.prop('checked', shouldSelect);

            if (shouldSelect) {
                selectedItems[item.reference] = item;
            } else {
                delete selectedItems[item.reference];
            }
        });

        saveSelections();
        renderSelectedItems();
    });

    $('#selected-list').on('click', '.remove-selection', function() {
        var ref = $(this).data('reference');
        delete selectedItems[ref];
        saveSelections();

        $checkboxes.filter(function() { return this.value === ref; })
            .prop('checked', false)
            .closest('[data-request-row]')
            .removeClass('is-selected');

        renderSelectedItems();
    });

    $('#btn-print').on('click', function() {
        var refs = selectedReferences();

        if (!refs.length) {
            $('#selection-error').removeClass('d-none');
            return;
        }

        var query = refs.map(function(ref) {
            return 'refs[]=' + encodeURIComponent(ref);
        }).join('&');

        window.location.href = '{{ url('/batch-print-ids') }}?' + query;
    });

    $('[data-request-row]').each(function() {
        var $row = $(this);
        var item = rowData($row);
        var isSelected = !!selectedItems[item.reference];

        if (isSelected) selectedItems[item.reference] = item;
        $row.toggleClass('is-selected', isSelected);
        $row.find('.request-checkbox').prop('checked', isSelected);
    });

    var referencesFromUrl = [];
    var queryParameters = new URLSearchParams(window.location.search);

    queryParameters.forEach(function(value, key) {
        var isReferenceParameter =
            key === 'refs[]' ||
            /^refs\[\d+\]$/.test(key);

        if (isReferenceParameter) {
            referencesFromUrl.push(value);
        }
    });

    referencesFromUrl.forEach(function(ref) {
        var $row = $('[data-request-row]').filter(function() {
            return String($(this).data('reference')) === ref;
        }).first();

        if ($row.length) {
            var item = rowData($row);
            selectedItems[item.reference] = item;
            $row.addClass('is-selected').find('.request-checkbox').prop('checked', true);
        } else if (!selectedItems[ref]) {
            selectedItems[ref] = {
                reference: ref,
                name: 'Selected ID request',
                employeeCode: '',
                department: '',
                initials: 'ID',
                photo: ''
            };
        }
    });

    saveSelections();
    renderSelectedItems();
});
</script>
@endsection
