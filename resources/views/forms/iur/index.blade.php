@extends('layouts.header')
@section('content')

<style>
    .dataTables_wrapper .dataTables_filter input.form-control-sm {
        height: calc(1.5em + 0.5rem + 2px);
        padding: 0.25rem 0.5rem;
    }

    .iur-closed-card .media,
    .iur-closed-card .media-body {
        min-width: 0;
        width: 100%;
    }

    .iur-closed-card-title {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class='row grid-margin'>

      <div class='col-lg-2 mb-2'>
        <div class="card card-tale">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h4 class="mb-4">Pending</h4>
                <a href="/iur?status=Pending" class="h4 card-text text-white text-truncate d-block">{{($iur_all->where('status','Pending'))->count()}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class='col-lg-2 mb-2'>
        <div class="card card-light-blue text-white">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h4 class="mb-4">Processing</h4>
                <a href="/iur?status=Processing" class="h4 card-text text-white text-truncate d-block">{{($iur_all->where('status','Processing'))->count()}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class='col-lg-2 mb-2'>
        <div class="card card-dark-blue text-white">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h4 class="mb-4">Partial</h4>
                <a href="/iur?status=Partial" class="h4 card-text text-white text-truncate d-block">{{($iur_all->where('status','Partial'))->count()}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class='col-lg-2 mb-2'>
        <div class="card bg-success text-white">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h4 class="mb-4">Released</h4>
                <a href="/iur?status=Released" class="h4 card-text text-white text-truncate d-block">{{($iur_all->where('status','Released'))->count()}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class='col-lg-2 mb-2'>
        <div class="card card-light-danger iur-closed-card">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h4 class="mb-4 iur-closed-card-title" title="Declined / Cancelled">Declined / Cancelled</h4>
                <a href="/iur?status=Closed" class="h4 card-text text-white text-truncate d-block">{{ $iur_all->whereIn('status', ['Declined', 'Cancelled'])->count() }}</a>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>
    <div class='row'>
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                  <h4>Uniform & ID Request</h4>
                  <button type="button" class="d-flex gap-1 align-items-center btn btn-outline-info btn-sm" data-toggle="modal" data-target="#createIurModal">
                      <i class="ti-plus mr-1"></i>
                      <span>New Request</span>
                  </button>
              </div>
            <form method='get' onsubmit='show();' enctype="multipart/form-data" class="border rounded bg-light p-3 mt-3 mb-4">
              <div class="row align-items-end">
                <div class='col-12 col-md-6 col-xl-3'>
                  <div class="form-group mb-3 mb-xl-0">
                    <label class="text-right">From</label>
                    <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
                      max='{{ date('Y-m-d') }}' onchange='get_min(this.value);' required />
                  </div>
                </div>
                <div class='col-12 col-md-6 col-xl-3'>
                  <div class="form-group mb-3 mb-xl-0">
                    <label class="text-right">To</label>
                    <input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to" required />
                  </div>
                </div>
                <div class='col-12 col-md-6 col-xl-3 pr-sm-1'>
                  <div class="form-group mb-3 mb-xl-0">
                    <label class="text-right">Status</label>
                    <select data-placeholder="Select Status" class="form-control form-control-sm" name='status' required>
                      <option value="">-- Select Status --</option>
                      <option value="Pending" @if ('Pending'==$status) selected @endif>Pending</option>
                      <option value="Partial" @if ('Partial'==$status) selected @endif>Partial</option>
                      <option value="Processing" @if ('Processing'==$status) selected @endif>Processing</option>
                      <option value="Released" @if ('Released'==$status) selected @endif>Released</option>
                      <option value="Closed" @if ('Closed'==$status) selected @endif>Declined / Cancelled</option>
                    </select>
                  </div>
                </div>
                <div class='col-12 col-sm-auto pl-sm-3'>
                  <div class="form-group mb-3 mb-xl-0">
                    <button type="submit" class="d-flex align-items-center form-control-sm filter-btn btn btn-primary btn-sm" title="Apply Filters">
                        <i class="ti-filter"></i>
                    </button>
                  </div>
                </div>
              </div>
            </form>
            <div class="table-responsive">
              <table id="iurRequestTable" class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>IUR Reference</th>
                    @if ($status !== 'Pending')
                    <th>Items</th>
                    @endif
                    @if ($iurs->contains(fn($i) => in_array($i->status, ['Partial', 'Released'])))
                    <th>Signed</th>
                    @endif
                    <th>Request Date</th>
                    <th>Request For</th>
                    <th>Type</th>
                    <th>Work Location</th>
                    <th>Details of Work</th>
                    <th>Notes</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($iurs as $iur)
                  <tr>
                    <td>
                      <a href="{{ route('iur.show', $iur->id) }}">
                        <button type="button" class="btn btn-info btn-rounded btn-icon" title="View and Sign"><i class="ti-eye"></i></button>
                      </a>

                      @if($iur->status == 'Pending')
                      <a href="{{ route('iur.edit', $iur->id) }}">
                        <button type="button" class="btn btn-primary btn-rounded btn-icon" title="Edit ID and Uniform"><i class="ti-pencil-alt btn-icon-prepend"></i></button>
                      </a>
                      @endif

                      @if($iur->status == 'Pending')
                      <button type="button" class="btn btn-danger btn-rounded btn-icon btn-cancel" data-id="{{ $iur->id }}" title="Cancel ID and Uniform"><i class="fa fa-ban"></i></button>
                      @endif
                    </td>

                    <div id="accountability-data-{{ $iur->id }}"
                      data-employee-name="{{ $iur->user->first_name }} {{ $iur->user->last_name }}"
                      data-branch="{{ $iur->work_location }}"
                      data-position="{{ $iur->user->position }}"
                      data-date="{{ date('M d, Y', strtotime($iur->created_at)) }}"
                      data-department="{{ $iur->user->department->name ?? '' }}"
                      data-reference="{{ $iur->iur_reference }}"
                      data-request-for="{{ $iur->request_for }}"
                      data-size="{{ $iur->size }}"
                      data-id-request="{{ $iur->id_request ?? '' }}"
                      data-issued="{{ $iur->issued ?? '' }}"
                      data-issued-remarks="{{ $iur->issued_remarks ?? '' }}"
                      data-employee-code="{{ $iur->user->employee_code ?? '' }}"
                      data-signature="{{ $iur->e_signature ? asset($iur->e_signature) : '' }}"
                      style="display:none;"></div>
                    <td>{{ $iur->iur_reference }}</td>

                    <!-- items quantity -->
                     @if ($status !== 'Pending')
                    <td style="font-size: 10px; line-height: 1.6; white-space: nowrap;">
                      @php $items = []; @endphp
                      @if(in_array($iur->request_for, ['Uniform', 'Uniform/Partial', 'Both']))
                      @if($iur->white_qty > 0) @php $items[] = ['label' => 'White', 'rec' => $iur->received_white_qty, 'req' => $iur->white_qty]; @endphp @endif
                      @if($iur->black_qty > 0) @php $items[] = ['label' => 'Black', 'rec' => $iur->received_black_qty, 'req' => $iur->black_qty]; @endphp @endif
                      @if($iur->collar_qty > 0) @php $items[] = ['label' => 'Polo', 'rec' => $iur->received_collar_qty, 'req' => $iur->collar_qty]; @endphp @endif
                      @if($iur->prod_qty > 0) @php $items[] = ['label' => 'Prod', 'rec' => $iur->received_prod_qty, 'req' => $iur->prod_qty]; @endphp @endif
                      @endif
                      @foreach($items as $i)
                      <div style="display:flex; justify-content:space-between; font-weight:bold;">  
                        <span>{{ $i['label'] }}:</span>
                        <span style="color:{{ $i['rec'] >= $i['req'] ? 'blue' : 'red' }};">{{ $i['rec'] }}/{{ $i['req'] }}</span>
                      </div>
                      @endforeach
                      @if(in_array($iur->request_for, ['ID', 'Both']))
                      @php $idRel = $iur->accountabilities->sum('released_id') > 0; @endphp
                      <div style="display:flex; justify-content:space-between; font-weight:bold;">
                        <span>ID:</span>
                        <span style="color:{{ $idRel ? 'blue' : 'red' }}; font-weight: bold;">{{ $idRel ? '✓' : '✗' }}</span>
                      </div>
                      @endif
                    </td>
                    @endif
                    
                    <!-- signed batch items -->
                     @if ($iurs->contains(fn($i) => $i->accountabilities->count() > 0))
                    <td style="font-size: 10px; line-height: 1.6; white-space: nowrap;">
                      @forelse($iur->accountabilities as $acc)
                      <div style="display:flex; justify-content:space-between;">
                        <span style="font-size: 12px; font-weight: bold;">{{ $acc->accountability_ref }}: </span>
                        <span style="font-size: 12px; font-weight: bolder; color:{{ $acc->employee_signature ? 'blue' : 'red' }}; margin-left: 6px;">{{ $acc->employee_signature ? '✓' : '✗' }}</span>
                      </div>
                      @empty
                      <span style="color: #999;">—</span>
                      @endforelse
                    </td>
                    @endif
                    <td>{{ date('M. d, Y h:i A', strtotime($iur->created_at)) }}</td>
                    <td>{{ $iur->request_for }}</td>
                    <td>{{ $iur->type }}</td>
                    <td>{{ $iur->work_location }}</td>
                    <td>{{ $iur->details }}</td>
                    <td>{{ $iur->approval_remarks ?? '-' }}</td>
                    <td id="tdStatus{{ $iur->id }}">
                      @if ($iur->status == 'Pending')
                      <label class="badge badge-warning">{{ $iur->status }}</label>
                      @elseif($iur->status == 'Partial')
                      <label class="badge badge-info">{{ $iur->status }}</label>
                      @elseif($iur->status == 'Released')
                      <label class="badge badge-success">{{ $iur->status }}</label>
                      @elseif($iur->status == 'Declined' or $iur->status == 'Cancelled')
                      <label class="badge badge-danger">{{ $iur->status }}</label>
                      @elseif($iur->status == 'Processing')
                      <label class="badge badge-primary">{{ $iur->status }}</label>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <form id="cancelForm" method="POST" style="display:none;">
                @csrf
                @method('PUT')
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@include('forms.iur.create')

@foreach ($iurs as $iur)
@include('for-approval.remarks.iur_signature_modal', ['form_approval' => $iur])
@include('for-approval.print-accountability-iur', ['form_approval' => $iur])
@endforeach

<script>
  $(window).on('load', function() {
    $('#iurRequestTable').DataTable({
      dom: 'Bfrtip',
      language: {
        search: 'Search:'
      }
    });
  });

  $(document).on('click', '.btn-cancel', function() {
    let id = $(this).data('id');
    let url = "{{ route('iur.cancel', ':id') }}".replace(':id', id);

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

@endsection
