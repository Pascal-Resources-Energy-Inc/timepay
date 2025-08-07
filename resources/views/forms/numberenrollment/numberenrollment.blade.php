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
                    <h2 class="card-text">{{ ($nes_all->where('status','Pending'))->count() }}</h2>
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
                    <h5 class="mb-4">Declined/Cancelled</h5>
                    <h2 class="card-text">{{($nes_all->where('status','Cancelled'))->count() + ($nes_all->where('status','Declined'))->count()}}</h2>
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
                    <h2 class="card-text">{{ ($nes_all->where('status','Approved'))->count() }}</h2>
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
                <h4 class="card-title">Number Of Enrollment</h4>
                <p class="card-description">
                 <!-- Button -->
                  <!-- Remove data-toggle and data-target -->
                  <button type="button" class="btn btn-outline-success btn-icon-text" onclick="showImportantNotes()">
                      <i class="ti-plus btn-icon-prepend"></i>
                      Apply Number of Enrolment
                  </button>


                </p>
                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                  <div class=row>
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
                          <th>Action </th>
                          <th>Date Filed</th>
                          <th>Employee Name</th>
                          <th>Phone Number</th>
                          <th>Network Provider</th>
                          <th>Enrollment Type</th>
                          <th>Approvers</th>  
                          <th>Status</th>                        
                          <!-- <th>Attachement</th>      -->
                        </tr>
                      </thead>
                    <tbody>
                        @foreach ($nes as $ne)
                        <tr>
                          <td>
                            @if ($ne->status == 'Pending' && $ne->level == 0)
                              <button type="button" class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-target="#view-ne-{{ $ne->id }}" title="View">
                                <i class="ti-eye btn-icon-prepend"></i>
                              </button>
                              <button type="button" class="btn btn-primary btn-rounded btn-icon" data-toggle="modal" data-target="#edit-ne-{{ $ne->id }}" title="Edit">
                                <i class="ti-pencil-alt"></i>
                              </button>
                              <button title="Cancel" onclick="cancel({{ $ne->id }})" class="btn btn-rounded btn-danger btn-icon">
                                <i class="fa fa-ban"></i>
                              </button>
                            @elseif ($ne->status == 'Pending' && $ne->level > 0)
                              <button type="button" class="btn btn-primary btn-rounded btn-icon" data-toggle="modal" data-target="#view-ne-{{ $ne->id }}" title="View">
                                <i class="ti-eye"></i>
                              </button>
                            @elseif ($ne->status == 'Approved')
                              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#view-ne-{{ $ne->id }}">
                                <i class="ti-eye"></i> View
                              </button>
                            @else
                              <button type="button" class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-target="#view-approved-{{ $ne->id }}" title="View">
                                <i class="ti-eye btn-icon-prepend"></i>
                              </button>
                            @endif
                          </td>

                          <td>{{ date('M. d, Y', strtotime($ne->created_at)) }}</td>
                          <td>{{ $ne->first_name ?? ''}} {{ $ne->last_name ?? ''}}</td>
                          <td>{{ $ne->cellphone_number ?? 'N/A' }}</td>
                          <td>{{ ucfirst(str_replace('_', ' ', $ne->network_provider ?? 'N/A')) }}</td>
                          <td>{{ ucfirst(str_replace('_', ' ', $ne->enrollment_type ?? 'N/A')) }}</td>
                           <td id="tdStatus{{ $ne->id }}">
                            @if ($ne->status == 'Pending')
                                <label class="badge badge-warning">{{ $ne->status }}</label>
                            @elseif ($ne->status == 'Approved')
                                <label class="badge badge-success">{{ $ne->status }}</label>
                            @elseif (in_array($ne->status, ['Declined', 'Cancelled']))
                                <label class="badge badge-danger">{{ $ne->status }}</label>
                            @endif
                        </td>
                          <td>
                          @php
                            $approver = $getApproverForEmployee($ne->user->employee);
                            $employee_company = $ne->user->employee->company_code ?? $ne->user->employee->company_id ?? null;
                          @endphp
                          
                          @if($approver)
                            <div>{{ $approver->user->name }}</div>
                          @else
                            <div class="text-danger">No NE approver available</div>
                          @endif
                        </td>
                        <!-- <td>
                           <button type="button" class="btn btn-primary btn-sm" title="Attachment" style="" data-toggle="modal" data-target="#view-ne-{{ $ne->id }}" title="View">
                            <i class="ti-folder"></i>
                          </button>
                        </td> -->
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


@endsection
@section('obScript')
	<script>
    function showImportantNotes() {
        Swal.fire({
            title: '<strong>Important Notes</strong>',
            icon: 'info',
            html: `
                <div style="text-align: left; line-height: 1.6; color: #2c3e50;">
                    <div style="margin-bottom: 15px;">
                        <strong>▶</strong> I understand that I am responsible to ensure that this 
                        elected cellphone number is personal and active.
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <strong>▶</strong> I understand that in case I am changing my account, I am 
                        responsible to submit another form to update company's file.
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <strong>▶</strong> I understand that aside from text and call, our company 
                        uses Viber as official communication channel and I am 
                        required to join Gaz Life Community Group Chat.
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <strong>▶</strong> I understand that failure to reply or answer phone call 
                        within working hours can lead to issuance of NTE.
                    </div>
                    
                    <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #007bff;">
                        <strong>By clicking I Understand & Agree</strong> - I am stating that I have read and 
                        understood the complete guidelines written in this form.
                        <br><br>
                        <strong>✓ I understand</strong>
                    </div>
                </div>
            `,
            width: 600,
            confirmButtonText: 'I Understand & Agree',
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancel',
            showCancelButton: true,
            reverseButtons: true,
            allowOutsideClick: false,
            customClass: {
                popup: 'swal-wide'
            }
        }).then((result) => {
        if (result.isConfirmed) {
            $('#numberenrollment').modal('show');
        }
    });
    }

		function cancel(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to cancel this Number Enrollment?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("loader").style.display = "block";
            
            $.ajax({
                url: "disable-ne/" + id,
                method: "GET",
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    document.getElementById("loader").style.display = "none";
                    
                    Swal.fire({
                        title: 'Cancelled!',
                        text: 'Number Enrollment has been cancelled!',
                        icon: 'success'
                    }).then(function() {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    document.getElementById("loader").style.display = "none";
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to cancel the enrollment. Please try again.',
                        icon: 'error'
                    });
                }
            });
        }
    });
}

	</script>
@endsection

@include('forms.numberenrollment.apply_ne')

@foreach ($nes as $ne)
   @include('for-approval.view-ne')
   @include('forms.numberenrollment.edit_ne')
@endforeach