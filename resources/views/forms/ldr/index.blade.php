@extends('layouts.header')
@section('css_header')
<style>
  .ldr-summary-card {
    height: 100%;
    color: inherit;
    background: #ffffff;
    border: 1px solid #e7ebf1;
    border-radius: 14px;
    box-shadow: 0 3px 10px rgba(30, 41, 59, 0.06);
    text-decoration: none !important;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
  }

  .ldr-summary-card:hover {
    color: inherit;
    border-color: #cbd5e1;
    box-shadow: 0 8px 18px rgba(30, 41, 59, 0.1);
    transform: translateY(-2px);
  }

  .ldr-summary-card.is-active {
    border-color: #6477d8;
    box-shadow: 0 0 0 2px rgba(100, 119, 216, 0.14), 0 8px 18px rgba(30, 41, 59, 0.09);
  }

  .ldr-summary-card .card-body {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    min-height: 108px;
    padding: 6px;
  }

  .ldr-summary-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    font-size: 18px;
  }

  .ldr-summary-content {
    min-width: 0;
    flex: 1;
  }

  .ldr-summary-label {
    overflow: hidden;
    color: #526987;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.045em;
    line-height: 1.25;
    text-overflow: ellipsis;
    text-transform: uppercase;
    white-space: nowrap;
  }

  .ldr-summary-value {
    margin-top: 5px;
    color: #0f172a;
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1;
  }

  .ldr-summary-caption {
    margin-top: 9px;
    overflow: hidden;
    color: #94a3b8;
    font-size: 0.78rem;
    font-weight: 600;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .ldr-icon-pending { background: #fff3cd; color: #a16207; }
  .ldr-icon-approved { background: #ede9fe; color: #6d4aff; }
  .ldr-icon-processed { background: #dcfce7; color: #15803d; }
  .ldr-icon-declined { background: #fee2e2; color: #b91c1c; }
  .ldr-icon-cancelled { background: #e2e8f0; color: #475569; }

  @media (max-width: 767.98px) {
    .ldr-summary-card .card-body {
      min-height: auto;
    }
  }
</style>
@endsection
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row grid-margin ldr-summary-cards">
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('layout-design-requests.index', ['status' => 'Pending']) }}" class="card ldr-summary-card {{ $status === 'Pending' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="ldr-summary-icon ldr-icon-pending"><i class="ti-time"></i></span>
            <div class="ldr-summary-content">
              <div class="ldr-summary-label" title="Pending">Pending</div>
              <div class="ldr-summary-value">{{ $pending_count }}</div>
              <div class="ldr-summary-caption">Awaiting review</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('layout-design-requests.index', ['status' => 'Approved']) }}" class="card ldr-summary-card {{ $status === 'Approved' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="ldr-summary-icon ldr-icon-approved"><i class="ti-check-box"></i></span>
            <div class="ldr-summary-content">
              <div class="ldr-summary-label" title="Approved">Approved</div>
              <div class="ldr-summary-value">{{ $approved_count }}</div>
              <div class="ldr-summary-caption">Ready for processing</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('layout-design-requests.index', ['status' => 'Processed']) }}" class="card ldr-summary-card {{ $status === 'Processed' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="ldr-summary-icon ldr-icon-processed"><i class="ti-check"></i></span>
            <div class="ldr-summary-content">
              <div class="ldr-summary-label" title="Processed">Processed</div>
              <div class="ldr-summary-value">{{ $processed_count }}</div>
              <div class="ldr-summary-caption">Processed requests</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('layout-design-requests.index', ['status' => 'Declined']) }}" class="card ldr-summary-card {{ $status === 'Declined' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="ldr-summary-icon ldr-icon-declined"><i class="ti-close"></i></span>
            <div class="ldr-summary-content">
              <div class="ldr-summary-label" title="Declined">Declined</div>
              <div class="ldr-summary-value">{{ $declined_count }}</div>
              <div class="ldr-summary-caption">Not approved</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('layout-design-requests.index', ['status' => 'Cancelled']) }}" class="card ldr-summary-card {{ $status === 'Cancelled' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="ldr-summary-icon ldr-icon-cancelled"><i class="ti-na"></i></span>
            <div class="ldr-summary-content">
              <div class="ldr-summary-label" title="Cancelled">Cancelled</div>
              <div class="ldr-summary-value">{{ $cancelled_count }}</div>
              <div class="ldr-summary-caption">Cancelled requests</div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <div class="d-flex flex-column">
              <h4 class="card-title">Layout Design Request</h4>
              </div>
              <a href="{{ route('layout-design-requests.create') }}" class="d-flex gap-1 align-items-center btn btn-outline-info btn-sm">
                <i class="ti-plus mr-1"></i>
                <span>New Request</span>
              </a>
            </div>

            <form method="get" action="{{ route('layout-design-requests.index') }}" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-4">
                  <div class="d-flex form-group flex-column">
                    <label class="mb-1">Search</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search ref no., requestor, department..." />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="mb-1">Status</label>
                    <select name="status" class="form-control form-control-sm">
                      <option value="">All</option>
                      <option value="Pending" @if($status == 'Pending') selected @endif>Pending</option>
                      <option value="Approved" @if($status == 'Approved') selected @endif>Approved</option>
                      <option value="Processed" @if($status == 'Processed') selected @endif>Processed</option>
                      <option value="Declined" @if($status == 'Declined') selected @endif>Declined</option>
                      <option value="Cancelled" @if($status == 'Cancelled') selected @endif>Cancelled</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="text-right mb-1">From</label>
                    <input type="date" value="{{ $from }}" class="form-control form-control-sm" name="from" />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="text-right mb-1">To</label>
                    <input type="date" value="{{ $to }}" class="form-control form-control-sm" name="to" />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="invisible mb-1">Filter</label>
                    <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Filter</button>
                  </div>
                </div>
              </div>
            </form>

            <div class="table-responsive">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th style="background-color: #e6e6e6; color: #666;">Action</th>
                    <th style="background-color: #e6e6e6; color: #666;">Ref No.</th>
                    <th style="background-color: #e6e6e6; color: #666;">Requestor</th>
                    <th style="background-color: #e6e6e6; color: #666;">Department</th>
                    <th style="background-color: #e6e6e6; color: #666;">Collateral Type</th>
                    <th style="background-color: #e6e6e6; color: #666;">Medium</th>
                    <th style="background-color: #e6e6e6; color: #666;">Preferred Date</th>
                    <th style="background-color: #e6e6e6; color: #666;">Priority</th>
                    <th style="background-color: #e6e6e6; color: #666;">Due Date</th>
                    <th style="background-color: #e6e6e6; color: #666;">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($requests as $request)
                  <tr class="align-middle">
                      <td class="text-center" style="white-space: nowrap;">
                        <div class="d-inline-flex align-items-center justify-content-center" style="gap: .1rem;">
                          @if ((int) $request->user_id === (int) auth()->id() && $request->status === 'Pending')
                          <a href="{{ route('layout-design-requests.edit', $request->id) }}" class="btn btn-warning btn-rounded btn-icon d-inline-flex align-items-center justify-content-center" title="Edit" aria-label="Edit request">
                              <i class="ti-pencil"></i>
                          </a>
                          <button type="button" class="btn btn-danger btn-rounded btn-icon btn-cancel d-inline-flex align-items-center justify-content-center" data-id="{{ $request->id }}" title="Cancel" aria-label="Cancel request">
                              <i class="fa fa-ban"></i>
                          </button>
                          @endif
                          <a href="{{ route('layout-design-requests.show', $request->id) }}" class="btn btn-info btn-rounded btn-icon d-inline-flex align-items-center justify-content-center" title="View" aria-label="View request">
                              <i class="ti-eye"></i>
                          </a>
                        </div>
                      </td>
                      <td style="white-space: nowrap;">
                        <span class="badge badge-primary badge-pill">{{ $request->reference_no }}</span>
                      </td>
                      <td style="white-space: nowrap;">
                          {{ $request->requestor_first_name }} {{ $request->requestor_last_name }}<br>
                          <small class="text-muted fw-bold">{{ $request->requestor_email }}</small>
                      </td>
                      <td>{{ $request->department }}</td>
                      <td>
                          @if (is_array($request->marketing_collateral_type))
                          @foreach ($request->marketing_collateral_type as $item)
                          <span class="badge badge-info d-block mb-1">{{ $item }}</span>
                          @endforeach
                          @else
                          <span class="badge badge-info">{{ $request->marketing_collateral_type }}</span>
                          @endif
                      </td>
                      <td>{{ $request->medium_type }}</td>
                      <td style="white-space: nowrap;">
                          {{ optional($request->date_needed)->format('M j, Y') ?? '-' }}<br>
                      </td>
                      <td>
                          @if($request->priority_level)
                              <span class="badge badge-{{ $request->priority_level === 'Major' ? 'danger' : 'secondary' }}">{{ $request->priority_level }}</span>
                          @else
                              <span>-</span>
                          @endif
                      </td>
                      <td style="white-space: nowrap;">
                          @if($request->due_date)
                              {{ optional($request->due_date)->format('M j, Y') }}
                          @else
                              <span>-</span>
                          @endif
                      </td>
                      <td>
                          @if($request->status == 'Processed')
                          <span class="badge badge-success">Processed</span>
                          @elseif($request->status == 'Approved')
                          <span class="badge badge-info">Approved</span>
                          @elseif($request->status == 'Declined')
                          <span class="badge badge-danger">Declined</span>
                          @elseif($request->status == 'Cancelled')
                          <span class="badge badge-danger">Cancelled</span>
                          @else
                          <span class="badge badge-warning">{{ $request->status }}</span>
                          @endif
                      </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="10" class="text-center text-muted py-4">No records found.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <div class="mt-3">{{ $requests->appends(request()->query())->links() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<form id="cancelForm" method="POST" style="display:none;">
    @csrf
</form>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $(document).on('click', '.btn-cancel', function() {
    let id = $(this).data('id');
    let url = "{{ route('layout-design-requests.cancel', ':id') }}".replace(':id', id);

    Swal.fire({
      title: 'Are you sure?',
      text: "You want to cancel this request?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, cancel it!',
      cancelButtonText: 'No'
    }).then((result) => {
      if (result.isConfirmed) {
        let form = $('#cancelForm');
        form.attr('action', url);
        form.submit();
      }
    });
  });
</script>
