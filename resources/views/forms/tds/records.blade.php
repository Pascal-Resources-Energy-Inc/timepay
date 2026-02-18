@extends('layouts.header')

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .table-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .entries-control {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .entries-control label {
        margin: 0;
        margin-bottom: 0 !important;
        white-space: nowrap;
        line-height: 1;
        padding-top: 8px;
    }
    .entries-control select {
        width: 80px;
        display: inline-block;
        margin: 0;
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h4 class="card-title mb-0">All TDS Submissions</h4>
                                <p class="card-description mb-0">
                                    View all submitted sales records
                                </p>
                            </div>
                        </div>

                        <form method='get' action="{{ route('tds.records') }}" id="filterForm" class="mb-4">
                            <div class="row">
                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" value='{{ request("from") }}' class="form-control form-control-sm" name="from" id="fromDate" max='{{ date("Y-m-d") }}' />
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" value='{{ request("to") }}' class="form-control form-control-sm" name="to" id="toDate" max='{{ date("Y-m-d") }}' />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Lead Generator</label>
                                        <select class="form-control select2" name="lead_generator[]" id="lead_generator" multiple>
                                            <option value="FB" {{ collect(request('lead_generator'))->contains('FB') ? 'selected' : '' }}>FB</option>
                                            <option value="Shopee" {{ collect(request('lead_generator'))->contains('Shopee') ? 'selected' : '' }}>Shopee</option>
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

                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label>Search</label>
                                        <input type="text" value='{{ request("search") }}' class="form-control form-control-sm" name="search" id="searchInput" placeholder="Customer name or business name" />
                                    </div>
                                </div>
                                <div class='col-md-3' style="margin-top: -5px;">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary mb-2" style="height: 50px;">
                                                Filter
                                            </button>
                                            <button type="button" class="btn btn-success" id="exportBtn" style="margin-top: -10px; height: 50px;">
                                                <i class="ti-download"></i> Export to CSV
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="per_page" id="per_page_input" value="{{ request('per_page', 25) }}">
                        </form>

                        <div class="table-controls">
                            <div class="entries-control">
                                <label for="entries">Show</label>
                                <select class="form-control form-control-sm" id="entries" style="width: 80px;">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <label>entries</label>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
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
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($submissions as $record)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($record->date_of_registration)->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($record->created_at)->format('M d, Y h:i A') }}</td>
                                        <td>{{ $record->user->name ?? 'N/A' }}</td>
                                        <td>
                                            {{ optional($record->region)->region ? optional($record->region)->region . ' - ' . optional($record->region)->province . (optional($record->region)->district ? ' - ' . optional($record->region)->district : '') : 'N/A' }}
                                        </td>
                                        <td>{{ $record->customer_name }}</td>
                                        <td>{{ $record->business_name }}</td>
                                        <td>{{ $record->program_type }}</td>
                                        <td>{{ $record->lead_generator }}</td>
                                        <td>{{ $record->lead_reference }}</td>
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
                                                    data-target="#viewDetails{{ $record->id }}"
                                                    title="View Details">
                                                <i class="ti-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No submissions found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">
                                    Showing {{ $submissions->firstItem() ?? 0 }} to {{ $submissions->lastItem() ?? 0 }} of {{ $submissions->total() }} entries
                                </span>
                            </div>
                            <div>
                                {{ $submissions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- @include('forms/tds/view-details', ['tdsRecords' => $submissions]) --}}
@include('forms/tds/view-details', ['tdsRecords' => $tdsRecords])

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
    });
    document.getElementById('entries').addEventListener('change', function() {
        document.getElementById('per_page_input').value = this.value;
        document.getElementById('filterForm').submit();
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