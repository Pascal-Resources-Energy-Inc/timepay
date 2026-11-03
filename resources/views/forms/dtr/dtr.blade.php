@extends('layouts.header')
@section('content')
<style>
  .dtr-summary-card {
    height: 100%;
    border: 1px solid #e5e7eb;
    border-left-width: 4px;
    border-radius: 0.65rem;
    background: #fff;
    box-shadow: 0 0.2rem 0.75rem rgba(15, 23, 42, 0.05);
  }

  .dtr-summary-card .card-body {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.15rem 1.25rem;
  }

  .dtr-summary-icon {
    display: inline-flex;
    width: 2.75rem;
    height: 2.75rem;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    border-radius: 0.65rem;
    font-size: 1.1rem;
  }

  .dtr-summary-content {
    min-width: 0;
  }

  .dtr-summary-label {
    margin-bottom: 0.15rem;
    color: #4b5563;
    font-size: 0.78rem;
    font-weight: 600;
    line-height: 1.3;
  }

  .dtr-summary-count {
    margin: 0;
    color: #1f2937;
    font-size: 1.65rem;
    font-weight: 700;
    line-height: 1.2;
  }

  .dtr-summary-description {
    display: block;
    margin-top: 0.2rem;
    color: #6b7280;
    font-size: 0.7rem;
    line-height: 1.35;
  }

  .dtr-summary-card--pending { border-left-color: #d69e2e; }
  .dtr-summary-card--pending .dtr-summary-icon {
    background: #fffbeb;
    color: #a16207;
  }

  .dtr-summary-card--approved { border-left-color: #22a06b; }
  .dtr-summary-card--approved .dtr-summary-icon {
    background: #ecfdf3;
    color: #198754;
  }

  .dtr-summary-card--declined { border-left-color: #dc3545; }
  .dtr-summary-card--declined .dtr-summary-icon {
    background: #fff1f2;
    color: #c92a3a;
  }

  #dtrTable_wrapper .dataTables_filter {
    float: left !important;
    width: 100%;
    text-align: left !important;
  }

  #dtrTable_wrapper .dataTables_filter label {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }

  #dtrTable_wrapper .dataTables_filter input {
    min-width: 300px;
    margin: 0.2rem 0 0 !important;
  }

  @media (max-width: 575.98px) {
    #dtrTable_wrapper .dataTables_filter input {
      width: 100%;
      min-width: 0;
    }
  }
</style>
<div class="main-panel">
    <div class="content-wrapper">
      <div class="row mb-4">
        <div class="col-12 col-md-4 mb-3 mb-md-0">
          <div class="card dtr-summary-card dtr-summary-card--pending">
            <div class="card-body">
              <div class="dtr-summary-content">
                <div class="dtr-summary-label">Pending</div>
                <h2 class="dtr-summary-count">{{ ($dtrs_all->where('status', 'Pending'))->count() }}</h2>
                <small class="dtr-summary-description">Awaiting approval</small>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4 mb-3 mb-md-0">
          <div class="card dtr-summary-card dtr-summary-card--approved">
            <div class="card-body">
              <div class="dtr-summary-content">
                <div class="dtr-summary-label">Approved</div>
                <h2 class="dtr-summary-count">{{ ($dtrs_all->where('status', 'Approved'))->count() }}</h2>
                <small class="dtr-summary-description">Completed corrections</small>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4">
          <div class="card dtr-summary-card dtr-summary-card--declined">
            <div class="card-body">
              <div class="dtr-summary-content">
                <div class="dtr-summary-label">Declined / Cancelled</div>
                <h2 class="dtr-summary-count">{{ ($dtrs_all->where('status', 'Cancelled'))->count() + ($dtrs_all->where('status', 'Declined'))->count() }}</h2>
                <small class="dtr-summary-description">Not processed</small>
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
                  <h4 class="card-title mb-0">DTR Correction</h4>
                  <button type="button" class="btn btn-sm btn-outline-info btn-icon-text d-flex align-items-center" data-toggle="modal" data-target="#dtrc">
                    <i class="ti-plus btn-icon-prepend mr-2"></i>
                    Request
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
                          <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                          <option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
                          <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                          <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-12 col-sm-auto pl-sm-3'>
                      <div class="form-group mb-3 mb-xl-0">
                        <button type="submit" class="d-flex align-items-center form-control-sm filter-btn btn btn-primary btn-sm" title="Apply Filters" aria-label="Apply filters">
                          <i class="ti-filter"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </form>

                <div class="table-responsive">
                  <table id="dtrTable" class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Date Filed</th>
                        <th>DTR Date </th>
                        <th>Correction</th>
                        <th>Adjustment Type</th>
                        <th>Time-in</th>
                        <th>Time-Out</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Approvers</th>
                        <th>Approval Remarks</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($dtrs as $dtr)
                      <tr>
                        <td> {{ date('M. d, Y h:i A', strtotime($dtr->created_at)) }}</td>
                        <td> {{ date('M. d, Y ', strtotime($dtr->dtr_date)) }}</td>
                        <td>{{ $dtr->correction }}</td>
                        <td>{{ $dtr->adjustment_type ?? '----' }}</td>
                        <td> {{(isset($dtr->time_in)) ? date('M. d, Y h:i A', strtotime($dtr->time_in)) : '----'}}</td>
                        <td> {{(isset($dtr->time_out)) ? date('M. d, Y h:i A', strtotime($dtr->time_out)) : '----'}}</td>
                        <td>
                          <p title="{{ $dtr->remarks }}" style="width: 250px;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                            {{ $dtr->remarks }}
                          </p>
                        </td>
                        <td id="tdStatus{{ $dtr->id }}">
                          @if ($dtr->status == 'Pending')
                            <label class="badge badge-warning">{{ $dtr->status }}</label>
                          @elseif($dtr->status == 'Approved')
                            <label class="badge badge-success">{{ $dtr->status }}</label>
                          @elseif($dtr->status == 'Declined' or $dtr->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $dtr->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdStatus{{ $dtr->id }}">
                          @if(count($dtr->approver) > 0)
                            @foreach($dtr->approver as $approver)
                              @if($dtr->level >= $approver->level)
                                @if ($dtr->level == 0 && $dtr->status == 'Declined')
                                {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @else
                                  {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                @endif
                              @else
                                @if ($dtr->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @else
                                  {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                                @endif
                              @endif<br>
                            @endforeach
                          @else
                            <label class="badge badge-danger mt-1">No Approver</label>
                          @endif
                        </td>
                        <td style="min-width: 220px; max-width: 320px;">
                          <div style="white-space: pre-wrap; overflow-wrap: anywhere; word-break: break-word; line-height: 1.4;">{{ $dtr->approval_remarks ?? '-' }}</div>
                        </td>
                        <td id="tdActionId{{ $dtr->id }}" data-id="{{ $dtr->id }}">
                          @if ($dtr->status == 'Pending' and $dtr->level == 0)
                          <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>            
                            <button type="button" id="edit{{ $dtr->id }}" class="btn btn-info btn-rounded btn-icon"
                              data-target="#edit_dtr{{ $dtr->id }}" data-toggle="modal" title='Edit'>
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button title='Cancel' id="{{ $dtr->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($dtr->status == 'Pending' and $dtr->level > 0)
                            <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>            
                              <button title='Cancel' id="{{ $dtr->id }}" onclick="cancel(this.id)"
                                class="btn btn-rounded btn-danger btn-icon">
                                <i class="fa fa-ban"></i>
                              </button>
                          @elseif ($dtr->status == 'Approved')   
                          <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>                            
                            <button title='Cancel' id="{{ $dtr->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>  
                          @else
                            <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>                                                                               
                          @endif
                        </td> 
                      </tr>
                    @endforeach  
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@foreach ($dtrs as $dtr)
  @include('forms.dtr.edit_dtr')
  @include('forms.dtr.view_dtr')
@endforeach  
@include('forms.dtr.apply_dtr') 
@endsection
@section('dtrScript')
	<script>
		function cancel(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			Swal.fire({
					title: "Are you sure?",
					text: "You want to cancel this DTR Correction?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#d33",
					dangerMode: true,
				})
				.then((result) => {
					if (result.isConfirmed) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disable-dtr/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								Swal.fire("DTR Correction has been cancelled!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdStatus" + id).innerHTML =
										"<label class='badge badge-danger'>Cancelled</label>";
                  document.getElementById("edit" + dataID).remove();
                  document.getElementById(dataID).remove();
								});
							}
						})

					} else {
            Swal.fire({text:"You stop the cancelation of DTR Correction.",icon:"success"});
					}
				});
		}

  function AdddtrType(sel) {

     if(sel.value === 'Both'){
        document.getElementById("time_in").required = true;
        document.getElementById("time_out").required = true;
        document.getElementById("time_in").disabled = false;
        document.getElementById("time_out").disabled = false;
     }else if(sel.value === 'Time-in'){
        document.getElementById("time_in").required = true;
        document.getElementById("time_out").required = false;
        document.getElementById("time_in").disabled = false;
        document.getElementById("time_out").disabled = true;
     }else{
        document.getElementById("time_in").required = false;
        document.getElementById("time_out").required = true;
        document.getElementById("time_in").disabled = true;
        document.getElementById("time_out").disabled = false;   
     }
  }

  function editDtr(sel,id) {
   var ti = document.getElementById("timein"+id);
   var to = document.getElementById("timeout"+id);
    
    if(sel.value === 'Both'){
      ti.required = true;
      to.required = true;
      ti.disabled = false;
      to.disabled = false;
      ti.value = ti.defaultValue;
      to.value = to.defaultValue;
     }else if(sel.value === 'Time-in'){
      ti.required = true;
      to.required = false;
      ti.disabled = false;
      to.disabled = true;
      to.value = '';
      ti.value = ti.defaultValue;
     }else{
      ti.required = false;
      to.required = true;
      ti.disabled = true;
      to.disabled = false;   
      ti.value = '';
      to.value = to.defaultValue;
     }
}

	</script>
@endsection
