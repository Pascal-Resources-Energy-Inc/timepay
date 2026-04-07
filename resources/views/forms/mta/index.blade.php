@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      <div class='row grid-margin'>
        <div class='col-lg-2 '>
          <div class="card card-tale">
            <div class="card-body">
              <div class="media">                
                <div class="media-body">
                  <h4 class="mb-4">Pending</h4>
                  <h2 class="card-text">{{($mtas_all->where('status','Pending'))->count()}}</h2>
                </div>
              </div>
            </div>
          </div>
        </div> 
        <div class='col-lg-2'>
          <div class="card card-light-danger">
            <div class="card-body">
              <div class="media">
                <div class="media-body">
                  <h4 class="mb-4">Declined/Cancelled</h4>
                  <h2 class="card-text">{{($mtas_all->where('status','Cancelled'))->count() + ($mtas_all->where('status','Declined'))->count()}}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class='col-lg-2'>
          <div class="card text-success">
            <div class="card-body">
              <div class="media">                
                <div class="media-body">
                  <h4 class="mb-4">Approved</h4>
                  <h2 class="card-text">{{($mtas_all->where('status','Approved'))->count()}}</h2>
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
                <h4 class="card-title">Monetized Transportation Allowance</h4>
                <p class="card-description">
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#mtac">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Apply Monetized Transportation Allowance
                  </button>
                </p>
                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                  <div class="row">
                    <div class='col-md-2'>
                      <div class="form-group">
                        <label class="text-right">From</label>
                        <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
                            max='{{ date('Y-m-d') }}' onchange='get_min(this.value);' required />
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <div class="form-group">
                        <label class="text-right">To</label>
                        <input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to" required />
                      </div>
                    </div>
                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <label class="text-right">Status</label>
                        <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
                          <option value="">-- Select Status --</option>
                          <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                          <option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
                          <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                          <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <div class="form-group">
                        <label class="invisible">Filter</label>
                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Filter</button>
                      </div>
                    </div>
                  </div>
                </form>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Transaction Date</th>
                        <th>Work Location</th>
                        <th>Liters Loaded</th>
                        <th>Petron Price per Liter</th>
                        <th>MTA Amount</th>
                        <th>Sales Invoice Number</th>
                        <th>Status</th>
                        <th>Approvers</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($mtas as $mta)
                      <tr>
                        <td id="tdActionId{{ $mta->id }}" data-id="{{ $mta->id }}" align="center">
                          @if ($mta->status == 'Pending' and $mta->level == 0)
                            <button type="button" id="view{{ $mta->id }}" class="btn btn-primary btn-rounded btn-icon"
                                data-target="#view_mta{{ $mta->id }}" data-toggle="modal" title='View'>
                                <i class="ti-eye"></i>
                            </button>            
                            <button type="button" id="edit{{ $mta->id }}" class="btn btn-info btn-rounded btn-icon"
                              data-target="#edit_mta{{ $mta->id }}" data-toggle="modal" title='Edit'>
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button title='Cancel' 
                                id="cancel{{ $mta->id }}" 
                                data-id="{{ $mta->id }}"
                                onclick="cancelMTA({{ $mta->id }})"
                                class="btn btn-rounded btn-danger btn-icon">
                                <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($mta->status == 'Pending' and $mta->level > 0)
                            <button type="button" id="view{{ $mta->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_mta{{ $mta->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>            
                            <button title='Cancel' 
                                id="cancel{{ $mta->id }}" 
                                data-id="{{ $mta->id }}"
                                onclick="cancelMTA({{ $mta->id }})"
                                class="btn btn-rounded btn-danger btn-icon">
                                <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($mta->status == 'Approved')   
                            <button type="button" id="view{{ $mta->id }}" class="btn btn-primary btn-rounded btn-icon"
                                data-target="#view_mta{{ $mta->id }}" data-toggle="modal" title='View'>
                                <i class="ti-eye"></i>
                            </button>     
                          @else
                            <button type="button" id="view{{ $mta->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_mta{{ $mta->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>                                                                               
                          @endif
                        </td> 
                        <td> {{ date('M. d, Y h:i A', strtotime($mta->created_at)) }}</td>
                        <td> {{ $mta->work_location }}</td>
                        <td>{{ $mta->liters_loaded }}</td>
                        <td> {{ $mta->petron_price }}</td>
                        <td> {{ $mta->mta_amount }}</td>
                        <td> {{ $mta->sales_invoice_number }}</td>
                        <td id="tdStatus{{ $mta->id }}">
                          @if ($mta->status == 'Pending')
                            <label class="badge badge-warning">{{ $mta->status }}</label>
                          @elseif($mta->status == 'Approved')
                            <label class="badge badge-success">{{ $mta->status }}</label>
                          @elseif($mta->status == 'Declined' or $mta->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $mta->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdStatus{{ $mta->id }}">
                          @if(count($mta->approver) > 0)
                            @foreach($mta->approver as $approver)
                              @if($mta->level >= $approver->level)
                                @if ($mta->level == 0 && $mta->status == 'Declined')
                                {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @else
                                  {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                @endif
                              @else
                                @if ($mta->status == 'Declined')
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
@foreach ($mtas as $mta)
  @include('forms.mta.edit')
  @include('forms.mta.view_mta')
@endforeach 
@include('forms.mta.apply_mta') 
@endsection 

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('mtaScript')
<script>
function cancelMTA(id) {

    Swal.fire({
        title: "Are you sure?",
        text: "You want to cancel this Monetized Transportation Allowance?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, cancel it!",
        cancelButtonText: "No"
    }).then((result) => {

        if (result.isConfirmed) {

            $("#loader").show();

            $.ajax({
                url: "/disable-mta/" + id,
                type: "GET", // ✅ match your route
                success: function(response) {

                    $("#loader").hide();

                    Swal.fire(
                        "Cancelled!",
                        "Monetized Transportation Allowance has been cancelled.",
                        "success"
                    );

                    // ✅ Update UI
                    $("#tdStatus" + id).html(
                        "<label class='badge badge-danger'>Cancelled</label>"
                    );

                    $("#edit" + id).remove();
                    $("#cancel" + id).remove();
                },
                error: function() {
                    $("#loader").hide();

                    Swal.fire(
                        "Error!",
                        "Something went wrong.",
                        "error"
                    );
                }
            });

        }
    });
}
</script>
@endsection