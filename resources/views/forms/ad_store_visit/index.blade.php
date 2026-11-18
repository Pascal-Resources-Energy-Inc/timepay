@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper ad-visit-index">
        <div class="row grid-margin">
            <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                <div class="card card-tale h-100 summary-card">
                    <div class="card-body">
                        <p class="summary-label">Total Reports</p>
                        <span class="summary-value">{{ number_format($totalReports) }}</span>
                    </div>
                </div>
            </div>

            <!-- <div class="col-lg-4 col-md-6 mb-3 mb-lg-0"> -->
            <!--     <div class="card card-dark-blue h-100 summary-card"> -->
            <!--         <div class="card-body"> -->
            <!--             <p class="summary-label">Visits This Month</p> -->
            <!--             <span class="summary-value">{{ number_format($thisMonthReports) }}</span> -->
            <!--         </div> -->
            <!--     </div> -->
            <!-- </div> -->

            <!-- <div class="col-lg-4 col-md-6"> -->
            <!--     <div class="card card-light-blue h-100 summary-card"> -->
            <!--         <div class="card-body"> -->
            <!--             <p class="summary-label">Area Distributors</p> -->
            <!--             <span class="summary-value">{{ number_format($uniqueDistributors) }}</span> -->
            <!--         </div> -->
            <!--     </div> -->
            <!-- </div> -->
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="page-heading">
                            <div>
                                <h1>AD Store Visit Reports</h1>
                            </div>

                            <a href="{{ route('ad-store-visit-reports.create') }}" class="btn btn-sm btn-outline-info d-flex align-items-center new-report-button">
                                <i class="ti-plus mr-2" aria-hidden="true"></i>
                                New
                            </a>
                        </div>

                        <form method="get" action="{{ route('ad-store-visit-reports.index') }}" class="filter-panel">
                            <div class="row align-items-end">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label for="search">Search reports</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ti-search" aria-hidden="true"></i></span>
                                            </div>
                                            <input
                                                id="search"
                                                type="search"
                                                name="search"
                                                value="{{ $search }}"
                                                class="form-control"
                                                placeholder="Reference no, distributor or email"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="from">From</label>
                                        <input id="from" type="date" name="from" value="{{ $from }}" class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="to">To</label>
                                        <input id="to" type="date" name="to" value="{{ $to }}" class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-4">
                                    <div class="form-group filter-actions">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti-filter mr-1" aria-hidden="true"></i> Filter
                                        </button>
                                        @if (request()->hasAny(['search', 'from', 'to']))
                                            <a href="{{ route('ad-store-visit-reports.index') }}" class="clear-filter" title="Clear filters" aria-label="Clear filters">
                                                <i class="ti-close" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive report-table-wrap">
                            <table class="table table-hover report-table">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Visit Date</th>
                                        <th>Area Distributor</th>
                                        <th>AD Contact</th>
                                        <th>Visit Time</th>
                                        <th>Submitted By</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reports as $report)
                                        <tr>
                                            <td data-label="Reference">
                                                <span class="reference-number">{{ $report->reference_no }}</span>
                                            </td>
                                            <td data-label="Visit Date">
                                                <strong>{{ optional($report->visit_date)->format('M j, Y') }}</strong>
                                            </td>
                                            <td data-label="Area Distributor" class="distributor-cell">
                                                {{ $report->area_distributor }}
                                            </td>
                                            <td data-label="AD Contact">
                                                <a class="email-link" href="mailto:{{ $report->area_distributor_email }}">
                                                    {{ $report->area_distributor_email }}
                                                </a>
                                                @if ($report->representative_email)
                                                    <small class="d-block text-muted mt-1">
                                                        Rep: {{ $report->representative_email }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td data-label="Visit Time" class="text-nowrap">
                                                {{ date('g:i A', strtotime($report->time_in)) }}
                                                <span class="text-muted mx-1">–</span>
                                                {{ $report->time_out ? date('g:i A', strtotime($report->time_out)) : 'N/A' }}
                                            </td>
                                            <td data-label="Submitted By">
                                                <strong>{{ $report->submitted_by_name }}</strong>
                                                <small class="d-block text-muted mt-1">{{ $report->submitted_by_designation }}</small>
                                            </td>
                                            <td data-label="Action" class="text-center">
                                                <a href="{{ route('ad-store-visit-reports.show', $report->id) }}" class="btn btn-outline-info btn-sm view-report-button" data-url="{{ route('ad-store-visit-reports.show', $report->id) }}" data-reference="{{ $report->reference_no }}" title="View details" aria-label="View {{ $report->reference_no }} details">
                                                    <i class="ti-eye" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="empty-row">
                                            <td colspan="8">
                                                <div class="empty-state">
                                                    <i class="ti-clipboard" aria-hidden="true"></i>
                                                    <h3>No reports found</h3>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($reports->total() > 0)
                            <div class="list-footer">
                                <small class="text-muted">
                                    Showing {{ $reports->firstItem() ?: 0 }}–{{ $reports->lastItem() ?: 0 }} of {{ $reports->total() }} reports
                                </small>
                                <div class="pagination-wrap">{{ $reports->links() }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade ad-visit-details-modal" id="adVisitDetailsModal" tabindex="-1" role="dialog" aria-labelledby="adVisitDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title text-white" id="adVisitDetailsModalLabel">AD Store Visit Details</h5>
                    <small class="modal-reference"></small>
                </div>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-loading" role="status" aria-live="polite">
                    <span class="spinner-border spinner-border-sm mr-2" aria-hidden="true"></span>
                    Loading report details...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .ad-visit-index .summary-card {
        border: 0;
        border-radius: 12px;
        overflow: hidden;
    }

    .ad-visit-index .summary-label {
        margin-bottom: 1rem;
        color: rgba(255, 255, 255, .88);
        font-size: .82rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .ad-visit-index .summary-value {
        color: #fff;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }

    .ad-visit-index .page-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .ad-visit-index .page-heading h1 {
        margin-bottom: .35rem;
        color: #172b4d;
        font-size: 1.45rem;
        font-weight: 700;
    }

    .ad-visit-index .new-report-button {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .ad-visit-index .filter-panel {
        margin-bottom: 1.25rem;
        padding: 1rem 1rem 0;
        border: 1px solid #e5e9f2;
        border-radius: 10px;
        background: #f8fafc;
    }

    .ad-visit-index .filter-panel label {
        margin-bottom: .4rem;
        color: #52616f;
        font-size: .78rem;
        font-weight: 600;
    }

    .ad-visit-index .filter-panel .form-control,
    .ad-visit-index .filter-panel .input-group-text {
        min-height: 42px;
        border-color: #d8dfeb;
        background: #fff;
    }

    .ad-visit-index .filter-actions {
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .ad-visit-index .filter-actions .btn {
        min-height: 42px;
        flex: 1;
    }

    .ad-visit-index .clear-filter {
        display: inline-flex;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        align-items: center;
        justify-content: center;
        border: 1px solid #d8dfeb;
        border-radius: 4px;
        background: #fff;
        color: #6c757d;
    }

    .ad-visit-index .report-table-wrap {
        border: 1px solid #e7ebf2;
        border-radius: 10px;
    }

    .ad-visit-index .report-table {
        margin-bottom: 0;
    }

    .ad-visit-index .report-table thead th {
        border-top: 0;
        border-bottom: 1px solid #e7ebf2;
        background: #f4f6f9;
        color: #5c677d;
        font-size: .73rem;
        font-weight: 700;
        letter-spacing: .035em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .ad-visit-index .report-table td {
        border-color: #edf0f5;
        color: #27364b;
        font-size: .85rem;
        vertical-align: middle;
    }

    .ad-visit-index .reference-number {
        display: inline-block;
        padding: .3rem .55rem;
        border-radius: 6px;
        background: #edf4ff;
        color: #2864b7;
        font-family: monospace;
        font-size: .8rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .ad-visit-index .distributor-cell {
        min-width: 220px;
        max-width: 330px;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .ad-visit-index .email-link {
        display: inline-block;
        max-width: 250px;
        overflow-wrap: anywhere;
    }

    .ad-visit-index .view-report-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .ad-visit-details-modal .modal-dialog {
        width: calc(100% - 2rem);
        max-width: 1180px;
        margin: 1rem auto;
    }

    .ad-visit-details-modal .modal-content {
        overflow: hidden;
        border: 0;
        border-radius: 12px;
    }

    .ad-visit-details-modal .modal-header {
        align-items: flex-start;
        padding: 1rem 1.25rem;
        border-bottom-color: #e7ebf2;
    }

    .ad-visit-details-modal .modal-title {
        color: #172b4d;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .ad-visit-details-modal .modal-body {
        max-height: calc(100vh - 165px);
        padding: 1.25rem;
        overflow-y: auto;
        background: #f5f7fa;
    }

    .ad-visit-details-modal .modal-loading,
    .ad-visit-details-modal .modal-error {
        display: flex;
        min-height: 220px;
        align-items: center;
        justify-content: center;
        color: #6b778c;
        text-align: center;
    }

    .ad-visit-details-modal .modal-error {
        flex-direction: column;
    }

    .ad-visit-details-modal .modal-error i {
        margin-bottom: .75rem;
        color: #dc3545;
        font-size: 2rem;
    }

    .ad-visit-details-modal .detail-hero,
    .ad-visit-details-modal .detail-section {
        margin-bottom: 1rem;
        border: 1px solid #e1e7ef;
        border-radius: 10px;
        background: #fff;
    }

    .ad-visit-details-modal .detail-hero {
        padding: 1.1rem;
    }

    .ad-visit-details-modal .detail-hero h2 {
        margin-bottom: .35rem;
        color: #172b4d;
        font-size: 1.15rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .ad-visit-details-modal .detail-meta-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: .85rem;
        margin-top: 1rem;
    }

    .ad-visit-details-modal .detail-meta-item {
        min-width: 0;
        padding: .75rem;
        border-radius: 8px;
        background: #f7f9fc;
    }

    .ad-visit-details-modal .detail-label {
        display: block;
        margin-bottom: .3rem;
        color: #6b778c;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .03em;
        text-transform: uppercase;
    }

    .ad-visit-details-modal .detail-value {
        display: block;
        color: #27364b;
        font-size: .86rem;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .ad-visit-details-modal .detail-section-header {
        display: flex;
        align-items: center;
        gap: .65rem;
        padding: .85rem 1rem;
        border-bottom: 1px solid #e7ebf2;
        background: #fafbfd;
    }

    .ad-visit-details-modal .detail-section-header i {
        color: #3478c5;
    }

    .ad-visit-details-modal .detail-section-header h3 {
        margin: 0;
        color: #344054;
        font-size: .93rem;
        font-weight: 700;
    }

    .ad-visit-details-modal .detail-section-body {
        padding: 1rem;
    }

    .ad-visit-details-modal .detail-table-wrap {
        overflow-x: auto;
        border: 1px solid #e7ebf2;
        border-radius: 8px;
    }

    .ad-visit-details-modal .detail-table {
        min-width: 700px;
        margin-bottom: 0;
    }

    .ad-visit-details-modal .detail-table th {
        border-top: 0;
        background: #f4f6f9;
        color: #5c677d;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .025em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .ad-visit-details-modal .detail-table td {
        border-color: #edf0f5;
        color: #344054;
        font-size: .82rem;
        vertical-align: middle;
        white-space: normal;
    }

    .ad-visit-details-modal .answer-chip {
        display: inline-flex;
        min-width: 48px;
        align-items: center;
        justify-content: center;
        padding: .25rem .5rem;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 700;
    }

    .ad-visit-details-modal .answer-yes {
        background: #e6f7ed;
        color: #1e7e45;
    }

    .ad-visit-details-modal .answer-no {
        background: #fbeaec;
        color: #b02a37;
    }

    .ad-visit-details-modal .answer-empty {
        background: #eef1f5;
        color: #6c757d;
    }

    .ad-visit-details-modal .narrative-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .ad-visit-details-modal .narrative-box {
        padding: .9rem;
        border: 1px solid #e4e9f0;
        border-radius: 8px;
        background: #fbfcfe;
    }

    .ad-visit-details-modal .narrative-box p {
        margin-bottom: 0;
        color: #344054;
        font-size: .85rem;
        line-height: 1.6;
        overflow-wrap: anywhere;
        white-space: pre-line;
    }

    .ad-visit-details-modal .attachment-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .75rem;
    }

    .ad-visit-details-modal .attachment-item {
        display: flex;
        min-width: 0;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        padding: .75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
    }

    .ad-visit-details-modal .attachment-info {
        display: flex;
        min-width: 0;
        align-items: center;
        gap: .65rem;
    }

    .ad-visit-details-modal .attachment-icon {
        display: inline-flex;
        width: 36px;
        height: 36px;
        flex: 0 0 36px;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        background: #e9f2ff;
        color: #3478c5;
    }

    .ad-visit-details-modal .attachment-name {
        display: block;
        color: #344054;
        font-size: .8rem;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .ad-visit-details-modal .attachment-meta {
        display: block;
        margin-top: .15rem;
        color: #7b8794;
        font-size: .7rem;
    }

    .ad-visit-index .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .ad-visit-index .empty-state > i {
        display: block;
        margin-bottom: .8rem;
        color: #aab4c3;
        font-size: 2.2rem;
    }

    .ad-visit-index .empty-state h3 {
        margin-bottom: .4rem;
        color: #344054;
        font-size: 1rem;
        font-weight: 700;
    }

    .ad-visit-index .empty-state p {
        margin-bottom: 1rem;
        color: #7b8794;
    }

    .ad-visit-index .list-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 1rem;
    }

    .ad-visit-index .pagination-wrap .pagination {
        margin-bottom: 0;
    }

    @media (max-width: 991.98px) {
        .ad-visit-details-modal .detail-meta-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .ad-visit-details-modal .attachment-list {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767.98px) {
        .ad-visit-index .page-heading {
            align-items: stretch;
            flex-direction: column;
        }

        .ad-visit-index .new-report-button {
            width: 100%;
        }

        .ad-visit-index .filter-panel {
            padding: .9rem .9rem 0;
        }

        .ad-visit-index .report-table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .ad-visit-index .report-table {
            min-width: 950px;
        }

        .ad-visit-index .list-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .ad-visit-index .pagination-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .ad-visit-details-modal .modal-dialog {
            width: calc(100% - 1rem);
            margin: .5rem auto;
        }

        .ad-visit-details-modal .modal-body {
            max-height: calc(100vh - 145px);
            padding: .75rem;
        }

        .ad-visit-details-modal .detail-meta-grid,
        .ad-visit-details-modal .narrative-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 479.98px) {
        .content-wrapper.ad-visit-index {
            padding-left: .75rem;
            padding-right: .75rem;
        }

    }
</style>

@php($reportShowUrl = route('ad-store-visit-reports.show', ['id' => '__report_id__']))
<script>
    $(function () {
        var detailsModal = $('#adVisitDetailsModal');
        var detailsBody = detailsModal.find('.modal-body');
        var detailsReference = detailsModal.find('.modal-reference');
        var loadingContent = detailsBody.html();

        function openReport(url, reference) {
            detailsBody.html(loadingContent);
            detailsReference.text(reference || '');
            detailsModal.modal('show');

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'html'
            }).done(function (content) {
                detailsBody.html(content);
            }).fail(function () {
                detailsBody.html(
                    '<div class="modal-error">' +
                        '<i class="ti-alert"></i>' +
                        '<strong>Unable to load this report.</strong>' +
                        '<span class="text-muted mt-1">It may no longer be available or you may not have access.</span>' +
                    '</div>'
                );
            });
        }

        $(document).on('click', '.view-report-button', function (event) {
            event.preventDefault();
            var button = $(this);
            openReport(button.data('url'), button.data('reference'));
        });

        var reportId = new URLSearchParams(window.location.search).get('report');
        if (reportId && /^[1-9]\d*$/.test(reportId)) {
            var reportUrl = @json($reportShowUrl);
            openReport(reportUrl.replace('__report_id__', reportId), '');
        }

        detailsModal.on('hidden.bs.modal', function () {
            detailsBody.html(loadingContent);
            detailsReference.text('');
        });
    });
</script>
@endsection
