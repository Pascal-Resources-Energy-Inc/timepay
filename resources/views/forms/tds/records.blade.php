@extends('layouts.header')

@section('css_header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .pagination {
        margin-bottom: 0;
    }

    .tds-filter-panel {
        --tds-filter-control-height: 42px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: .75rem;
    }

    .tds-filter-panel .form-group {
        margin-bottom: 0;
    }

    .tds-filter-panel .form-label {
        display: block;
        margin-bottom: .5rem;
        color: #475569;
        font-size: .875rem;
        font-weight: 600;
    }

    .tds-filter-panel .form-control,
    .tds-filter-panel .select2-container .select2-selection--multiple {
        height: var(--tds-filter-control-height) !important;
        min-height: var(--tds-filter-control-height) !important;
    }

    .tds-filter-panel .select2-container .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        align-items: center;
        align-content: center;
        min-height: calc(var(--tds-filter-control-height) - 2px);
        padding-top: 0;
        padding-bottom: 0;
        overflow-y: auto;
    }

    .tds-filter-panel .select2-container .select2-search--inline .select2-search__field {
        margin-top: 0;
    }

    .tds-filter-grid {
        display: grid;
        grid-template-columns:
            minmax(0, 2.2fr)
            minmax(0, 1.15fr)
            minmax(0, 1.15fr)
            minmax(0, .75fr)
            minmax(0, 1.25fr)
            minmax(0, 1.6fr);
        gap: 1rem;
        align-items: end;
    }

    .tds-filter-actions {
        display: flex !important;
        flex-direction: row !important;
        align-items: flex-end;
        gap: .5rem;
    }

    .tds-filter-actions .btn {
        flex: 1;
        height: var(--tds-filter-control-height);
        min-height: var(--tds-filter-control-height);
        padding-right: .85rem;
        padding-left: .85rem;
        white-space: nowrap;
    }

    .tds-filter-actions .btn-primary {
        flex-grow: 1.4;
    }

    .tds-filter-actions .btn-outline-secondary {
        flex-grow: .8;
    }

    .tds-table-toolbar {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        padding: .25rem 0 1rem;
    }

    .tds-table-search {
        display: inline-flex;
        flex-direction: column;
        align-items: flex-start;
        gap: .5rem;
        min-width: 0;
    }

    .tds-table-search label {
        margin: 0;
        color: #334155;
        white-space: nowrap;
    }

    .tds-table-search .form-control {
        flex: none;
        width: 350px !important;
        max-width: 100%;
        height: 38px;
        min-height: 38px;
    }

    .tds-records-table thead th {
        color: #334155;
        background: #f8fafc;
        border-bottom-width: 1px;
        font-weight: 600;
        white-space: nowrap;
    }

    .tds-records-table td {
        vertical-align: middle;
    }

    @media (max-width: 575.98px) {
        .tds-filter-panel {
            padding: 1rem;
        }

        .tds-filter-grid {
            grid-template-columns: 1fr;
        }

        .tds-filter-actions {
            width: 100%;
        }

        .tds-filter-actions .btn {
            flex: 1;
        }

        .tds-table-toolbar {
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .tds-table-search {
            width: 100%;
        }

        .tds-table-search .form-control {
            flex: none;
            width: 100% !important;
            min-width: 0;
        }
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-1">All TDS Submissions</h4>
                        <p class="text-muted mb-4">Review submitted sales records, apply filters, and open complete submission details.</p>

                        <div class="tds-filter-panel">
                            <form method="get" action="{{ route('tds.records') }}" id="filterForm">
                                <div class="tds-filter-grid">
                                    <div>
                                        <div class="form-group">
                                            <label class="form-label" for="lead_generator">Lead Generator</label>
                                            <select class="form-control select2" name="lead_generator[]" id="lead_generator" multiple>
                                                <option value="FB" {{ collect(request('lead_generator'))->contains('FB') ? 'selected' : '' }}>FB</option>
                                                <option value="Shopee" {{ collect(request('lead_generator'))->contains('Shopee') ? 'selected' : '' }}>Shopee</option>
                                                <option value="Lazada" {{ collect(request('lead_generator'))->contains('Lazada') ? 'selected' : '' }}>Lazada</option>
                                                <option value="Gaz Lite Website" {{ collect(request('lead_generator'))->contains('Gaz Lite Website') ? 'selected' : '' }}>Gaz Lite Website</option>
                                                <option value="Events" {{ collect(request('lead_generator'))->contains('Events') ? 'selected' : '' }}>Events</option>
                                                <option value="Kaagapay" {{ collect(request('lead_generator'))->contains('Kaagapay') ? 'selected' : '' }}>Kaagapay</option>
                                                <option value="Referral" {{ collect(request('lead_generator'))->contains('Referral') ? 'selected' : '' }}>Referral</option>
                                                <option value="MFI" {{ collect(request('lead_generator'))->contains('MFI') ? 'selected' : '' }}>MFI</option>
                                                <option value="MD" {{ collect(request('lead_generator'))->contains('MD') ? 'selected' : '' }}>MD</option>
                                                <option value="PD" {{ collect(request('lead_generator'))->contains('PD') ? 'selected' : '' }}>PD</option>
                                                <option value="AD" {{ collect(request('lead_generator'))->contains('AD') ? 'selected' : '' }}>AD</option>
                                                <option value="Own Accounts" {{ collect(request('lead_generator'))->contains('Own Accounts') ? 'selected' : '' }}>Own Accounts</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group">
                                            <label class="form-label" for="fromDate">From</label>
                                            <input type="date" value='{{ request("from") }}' class="form-control" name="from" id="fromDate" max='{{ date("Y-m-d") }}' />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group">
                                            <label class="form-label" for="toDate">To</label>
                                            <input type="date" value='{{ request("to") }}' class="form-control" name="to" id="toDate" max='{{ date("Y-m-d") }}' />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group">
                                            <label class="form-label" for="perPage">Per page</label>
                                            <select class="form-control" id="perPage" name="per_page">
                                                @foreach([10, 25, 50, 100] as $pageSize)
                                                    <option value="{{ $pageSize }}" {{ (int) request('per_page', 25) === $pageSize ? 'selected' : '' }}>{{ $pageSize }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group">
                                            <label class="form-label" for="status">Status</label>
                                            <select class="form-control select2" name="status[]" id="status" multiple>
                                                <option value="Interested" {{ collect(request('status'))->contains('Interested') ? 'selected' : '' }}>Interested</option>
                                                <option value="Decline" {{ collect(request('status'))->contains('Decline') ? 'selected' : '' }}>Decline</option>
                                                <option value="For Delivery" {{ collect(request('status'))->contains('For Delivery') ? 'selected' : '' }}>For Delivery</option>
                                                <option value="Delivered" {{ collect(request('status'))->contains('Delivered') ? 'selected' : '' }}>Delivered</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tds-filter-actions">
                                        <button type="submit" class="btn btn-primary btn-sm">Apply filters</button>
                                        <a href="{{ route('tds.records') }}" class="d-flex align-items-center justify-content-center btn btn-info btn-sm">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tds-table-toolbar">
                            <div class="tds-table-search">
                                <label for="searchInput">Search:</label>
                                <input type="text"
                                       value='{{ request("search") }}'
                                       class="form-control"
                                       name="search"
                                       id="searchInput"
                                       form="filterForm"
                                       placeholder="Customer, business, or employee" />
                            </div>
                            <button type="button" class="btn btn-success btn-sm d-flex align-items-center justify-content-center" id="exportBtn">
                                <i class="ti-download mr-1"></i> Export CSV
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table border="1" class="table table-hover table-bordered table-detailed tds-records-table">
                                <thead>
                                    <tr>
                                        <th>Date Registered</th>
                                        <th>Submitted At</th>
                                        <th>Employee Name</th>
                                        <th>Area</th>
                                        <th>Customer Name</th>
                                        <th>Business Name</th>
                                        <th>Program Type</th>
                                        <th>Lead Generator</th>
                                        <th>Lead Reference</th>
                                        <th>Purchase Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($submissions as $record)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($record->date_of_registration)->format('M. d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($record->created_at)->format('M. d, Y h:i A') }}</td>
                                        <td>{{ $record->user->name ?? 'N/A' }}</td>
                                        <td>
                                            {{ optional($record->region)->region ? optional($record->region)->region . ' - ' . optional($record->region)->province . (optional($record->region)->district ? ' - ' . optional($record->region)->district : '') : 'N/A' }}
                                        </td>
                                        <td>{{ $record->customer_name }}</td>
                                        <td>{{ $record->business_name ?? '-' }}</td>
                                        <td>{{ $record->program_type ?? '-' }}</td>
                                        <td>{{ $record->lead_generator }}</td>
                                        <td>{{ $record->lead_reference ?? '-' }}</td>
                                        <td>
                                            {{ $record->purchase_amount !== null 
                                            ? number_format($record->purchase_amount, 2) 
                                            : '-' }}
                                        </td>
                                        <td>
                                            @if($record->status == 'Delivered')
                                                <span class="badge badge-success">Delivered</span>
                                            @elseif($record->status == 'For Delivery')
                                                <span class="badge badge-warning">For Delivery</span>
                                            @elseif($record->status == 'Interested')
                                                <span class="badge badge-info">Interested</span>
                                            @elseif($record->status == 'Decline')
                                                <span class="badge badge-danger">Decline</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary view-details-btn" 
                                                    data-toggle="modal" 
                                                    data-target="#viewDetailsModal"
                                                    data-url="{{ route('tds.show', $record->id) }}"
                                                    title="View Details">
                                                <i class="ti-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-4 text-muted">No submissions found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($submissions->total())
                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between border-top mt-3 pt-3">
                                <p class="text-muted mb-3 mb-md-0">
                                    Showing {{ $submissions->firstItem() }}–{{ $submissions->lastItem() }} of {{ $submissions->total() }} submission records
                                </p>
                                @if($submissions->hasPages())
                                    <div>
                                        {{ $submissions->links() }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="viewDetailsContent">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="text-muted mt-3 mb-0">Loading submission details...</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#lead_generator').select2({
            placeholder: "Filter Lead Generator",
            allowClear: true,
            width: '100%'
        });
        $('#status').select2({
            placeholder: "Filter Status",
            allowClear: true,
            width: '100%'
        });

        $('.view-details-btn').on('click', function() {
            const detailsUrl = $(this).data('url');
            const detailsContent = $('#viewDetailsContent');

            detailsContent.html(`
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="text-muted mt-3 mb-0">Loading submission details...</p>
                </div>
            `);

            $.ajax({
                url: detailsUrl,
                method: 'GET',
                success: function(html) {
                    const response = $('<div>').html(html);
                    const loadedContent = response.find('.modal-content').first().html();

                    if (!loadedContent) {
                        showDetailsLoadError(detailsContent);
                        return;
                    }

                    detailsContent.html(loadedContent);
                },
                error: function() {
                    showDetailsLoadError(detailsContent);
                }
            });
        });

        function showDetailsLoadError(detailsContent) {
            detailsContent.html(`
                <div class="modal-header">
                    <h5 class="modal-title">Unable to Load Details</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger mb-0">
                        The submission details could not be loaded. Please try again.
                    </div>
                </div>
            `);
        }
    });
    document.getElementById('exportBtn').addEventListener('click', function() {

        const form = document.getElementById('filterForm');
        const formData = new FormData(form);

        let exportUrl = '{{ route("tds.records.export") }}?';
        const params = new URLSearchParams(formData).toString();

        window.location.href = exportUrl + params;
    });


//   document.getElementById('exportBtn').addEventListener('click', function() {
//       const from = document.getElementById('fromDate').value;
//       const to = document.getElementById('toDate').value;
//       const search = document.getElementById('searchInput').value;
      
//       let exportUrl = '{{ route("tds.records.export") }}?';
//       const params = [];
      
//       if (from) params.push('from=' + from);
//       if (to) params.push('to=' + to);
//       if (search) params.push('search=' + encodeURIComponent(search));
      
//       exportUrl += params.join('&');
      
//       window.location.href = exportUrl;
//   });

  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '{{ session('success') }}',
      timer: 3000,
      showConfirmButton: true,
      confirmButtonColor: '#28a745'
    });
  @endif

  @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: '{{ session('error') }}',
      confirmButtonColor: '#dc3545'
    });
  @endif
</script>
@endsection
