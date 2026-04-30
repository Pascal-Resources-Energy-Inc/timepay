@extends('layouts.header')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class='row grid-margin'>
      <div class='col-lg-2 mt-2'>
        <div class="card bg-info">
          <div class="card-body">
            <div class="media">                
              <div class="media-body">
                <h4 class="mb-4 text-white">For Processing</h4>
                <a href="/mta-process?payment_status=For Processing" class="h2 card-text text-white">{{$for_processing}}</a>
              </div>
            </div>
          </div>
        </div>
      </div> 
      <div class='col-lg-2 mt-2'>
        <div class="card bg-success">
          <div class="card-body">
            <div class="media">                
              <div class="media-body">
                <h4 class="mb-4 text-white">Processed for payment </h4>
                <a href="/mta-process?payment_status=Processed" class="h2 card-text text-white">{{$processed}}</a>
              </div>
            </div>
          </div>
        </div>
      </div> 
      <div class='col-lg-3 mt-2'>
        <div class="card bg-danger">
          <div class="card-body">
            <div class="media">                
              <div class="media-body">
                <h4 class="mb-4 text-white">Disapproved for Payment</h4>
                <a href="/mta-process?payment_status=Disapproved" class="h2 card-text text-white">{{$disapproved}}</a>
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
            <h4 class="card-title">For Process Monetized Transportation Allowance</h4>
            <form method='get' onsubmit='show();' enctype="multipart/form-data">
              <div class=row>
                <div class='col-md-2'>
                  <div class="form-group">
                    <label class="text-right">From</label>
                    <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from" onchange='get_min(this.value);' required />
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
                    <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payment_status' required>
                      <option value="">-- Select Status --</option>
                      <option value="Approved" @if ('Approved' == $payment_status) selected @endif>Approved</option>
                      <option value="For Processing" @if ('For Processing' == $payment_status) selected @endif>For Processing</option>
                      <option value="Processed" @if ('Processed' == $payment_status) selected @endif>Processed</option>
                      <option value="Disapproved" @if ('Disapproved ' == $payment_status) selected @endif>Disapproved</option>
                    </select>
                  </div>
                </div>
                <div class='col-md-2 mt-3'>
                  <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm mt-3">Filter</button>
                </div>
              </div>
            </form>
            @if($payment_status == 'Approved')
              <label>
                <input type="checkbox" id="selectAllProcess">
                <span id="labelSelectAll">Select All</span> 
              </label>
              <button class="btn btn-success btn-sm mb-2" id="processingAllBtn" style="display: none;">Processing</button>
              <button class="btn btn-danger btn-sm mb-2" id="disApproveAllBtn" style="display: none;">Disapproved</button>
            @endif
            @if($payment_status == 'For Processing')
              <label>
                <input type="checkbox" id="selectAllProcessed">
                <span id="processedSelectAll">Select All</span> 
              </label>
              <button class="btn btn-success btn-sm mb-2" id="processedAllBtn" style="display: none;">Processed</button>
            @endif

          <div class="table-responsive">
            <table id="mtaDataTable" class="table table-hover table-bordered tablewithSearch">
              <thead>
                <tr>
                  <th>Select</th>
                  <th>Action</th> 
                  <th>MTA Reference</th>
                  <th>Employee Name</th>
                  <th>Employee Number</th>
                  <th>Transaction Date</th>
                  <th>Work Location</th>
                  <th>Liters Loaded</th>
                  <th>Petron Price per Liter</th>
                  <th>MTA Amount</th>
                  <th>Attachment</th>
                  {{-- <th>Approver - Status</th> --}}
                  <th>Approver - Status</th>
                  <th>Payment Status</th>
                </tr>
              </thead>
              <tbody> 
                @foreach ($mtas as $form_approval)
                  <tr>
                    @if(!in_array($form_approval->payment_status, ['Approved', 'For Processing']))
                      <td></td>
                    @endif
                    @if($form_approval->payment_status == 'Approved')
                      <td align="center">
                        <input type="checkbox" class="checkbox-item" data-id="{{ $form_approval->id }}">
                      </td>
                    @endif
                    @if($form_approval->payment_status == 'For Processing')
                      <td align="center">
                        <input type="checkbox" class="checkbox-item-process" data-id="{{ $form_approval->id }}">
                      </td>
                    @endif
                    <td align="center">
                      @if(auth()->user()->role == 'Admin' && checkUserPrivilege('employees_mta', auth()->user()->id) == 'yes')
                        <button type="button" class="btn btn-primary btn-rounded btn-icon" data-toggle="modal" data-target="#view_mta{{ $form_approval->id }}">
                          <i class="ti ti-eye"></i>
                        </button>
                        <button type="button" id="edit{{ $form_approval->id }}" class="btn btn-warning btn-rounded btn-icon" data-target="#edit_mta{{ $form_approval->id }}" data-toggle="modal" title='Edit'>
                          <i class="ti-pencil-alt"></i>
                        </button>
                        @if($form_approval->payment_status == 'Approved')
                          <button type="button" class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-target="#mta-process-remarks-{{ $form_approval->id }}" title="For Processing">
                            <i class="ti ti-reload"></i>
                          </button>
                          <button type="button" class="btn btn-danger btn-rounded btn-icon" data-toggle="modal" data-target="#mta-disapproved-remarks-{{ $form_approval->id }}">
                            <i class="ti-close"></i>
                          </button>
                        @endif
                        @if($form_approval->payment_status == 'For Processing')
                          <button type="button" class="btn btn-success btn-rounded btn-icon" data-toggle="modal" data-target="#mta-processed-remarks-{{ $form_approval->id }}" title="Processed for Payment">
                            <i class="ti ti-upload"></i>
                          </button>
                          {{-- <button type="button" class="btn btn-danger btn-rounded btn-icon" data-toggle="modal" data-target="#mta-disapproved-remarks-{{ $form_approval->id }}">
                            <i class="ti-close"></i>
                          </button> --}}
                        @endif
                      @else 
                        <button type="button" class="btn btn-primary btn-rounded btn-icon" data-toggle="modal" data-target="#view_mta{{ $form_approval->id }}">
                          <i class="ti ti-eye"></i>
                        </button>
                      @endif
                    </td>
                    <td>{{ $form_approval->mta_reference }}</td>
                    <td>
                        <strong>{{$form_approval->user->name}}</strong> <br>
                        <small>Position: {{$form_approval->user->employee->position}}</small> <br>
                        <small>Location: {{$form_approval->user->employee->location}}</small> <br>
                        <small>Department: {{ $form_approval->user->employee->department ? $form_approval->user->employee->department->name : ""}}</small>
                    </td>
                    <td>{{$form_approval->user->employee->employee_number}}</td>
                    <td>{{date('m/d/Y', strtotime($form_approval->mta_date))}}</td>                      
                    <td>{{$form_approval->work_location}}</td>
                    <td>{{$form_approval->liters_loaded}}</td>
                    <td>{{number_format($form_approval->petron_price, 2)}}</td>
                    <td>{{number_format($form_approval->mta_amount, 2)}}</td>
                    <td>
                      @if($form_approval->attachment)
                      <a href="{{url($form_approval->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a>
                      @endif
                    </td>
                    <td>
                      @if ($form_approval->status == 'Pending')
                        <label class="badge badge-warning">{{ $form_approval->status }}</label>
                      @elseif($form_approval->status == 'Approved')
                        <i class="ti ti-user mr-1"></i>&nbsp;{{ $form_approval->approverMta->user->name ?? 'N/A' }}<br><br><label class="badge badge-success" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                      @elseif($form_approval->status == 'Declined' || $form_approval->status == 'Cancelled')
                        <label class="badge badge-danger" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                      @endif  
                    </td>
                    <td>
                      @if ($form_approval->payment_status == 'Approved')
                        <label class="badge badge-warning">Awaiting Process</label>
                      @elseif($form_approval->payment_status == 'For Processing')
                        <label class="badge badge-info" title="{{$form_approval->approval_remarks}}">{{ $form_approval->payment_status }}</label>
                      @elseif($form_approval->payment_status == 'Processed')
                        <label class="badge badge-success" title="{{$form_approval->approval_remarks}}">{{ $form_approval->payment_status }}</label>
                      @elseif($form_approval->payment_status == 'Disapproved')
                        <label class="badge badge-danger" title="{{$form_approval->approval_remarks}}">{{ $form_approval->payment_status }}</label>
                      @endif  
                    </td>
                  </tr>
                @endforeach                      
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="9" style="text-align:right;">Total MTA Amount:</th>
                  <th id="total_mta_amount">0.00</th>
                  <th colspan="3"></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
  $(document).ready(function() {
    // Process
    $('#selectAllProcess').on('click', function() {
      const isChecked = $(this).prop('checked');
      $('.checkbox-item').prop('checked', isChecked);
      updateSelectedCount();

      if ($(this).is(':checked')) {

        const selectedCount = $('.checkbox-item:checked').length;

        if(selectedCount > 0){
          
          // Checkbox is checked, show the button
          $('#processingAllBtn').show();
          $('#disApproveAllBtn').show();

        }
      } else {
        // Checkbox is unchecked, hide the button
        $('#processingAllBtn').hide();
        $('#disApproveAllBtn').hide();

        $('#labelSelectAll').text('Select All');
      }
    });

    $('.checkbox-item').on('click', function() {
        if ($(this).is(':checked')) {
            // Checkbox is checked, show the button
            $('#processingAllBtn').show();
            $('#disApproveAllBtn').show();
        } else {
            // Checkbox is unchecked, hide the button
            $('#processingAllBtn').hide();
            $('#disApproveAllBtn').hide();

            $('#labelSelectAll').text('Select All');
        }

        updateSelectedCount();
    });

    $('#processingAllBtn').on('click', function() {
      Swal.fire({
        title: "Are you sure?",
        text: "You want to process this Monetized Transportation Allowance?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"
      }).then((result) => {
        if (result.isConfirmed) {

          $('#loader').show(); // ✅ SAFE

          const selectedItems = [];

          $('.checkbox-item:checked').each(function () {
              selectedItems.push($(this).data('id')); // ✅ FIXED FORMAT
          });

          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              type: 'POST',
              url: '/process-mta-all',
              data: {
                  ids: JSON.stringify(selectedItems)
              },
              success: function (response) {

                  $('#loader').hide(); // ✅ SAFE

                  Swal.fire("Monetized Transportation Allowance has been Process (" + response + ")", "", "success")
                      .then(() => location.reload());
              },
              error: function () {
                  $('#loader').hide();
                  Swal.fire("Error occurred", "", "error");
              }
          });
        }
      });
    });

    //Submit button click event to perform the POST request
    $('#disApproveAllBtn').on('click', function() {
      Swal.fire({
        title: "Are you sure?",
        text: "You want to disapprove this Monetized Transportation Allowance?",
        icon: "warning",
        buttons: true,
      }).then((result) => {
        if (result.isConfirmed) {

            $('#loader').show(); // ✅ SAFE

            const selectedItems = [];

            $('.checkbox-item:checked').each(function () {
                selectedItems.push($(this).data('id')); // ✅ FIXED FORMAT
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/disapproved-processed-mta-all',
                data: {
                    ids: JSON.stringify(selectedItems)
                },
                success: function (response) {

                    $('#loader').hide(); // ✅ SAFE

                    Swal.fire("Monetized Transportation Allowance has been Disapproved <br> (" + response + ")", "", "success")
                        .then(() => location.reload());
                },
                error: function () {
                    $('#loader').hide();
                    Swal.fire("Error occurred", "", "error");
                }
            });
        }
      });
    });

    function updateSelectedCount() {
      const selectedCount = $('.checkbox-item:checked').length;
      $('#processingAllBtn').text( '('+ selectedCount + ') Processing');
      $('#disApproveAllBtn').text( '('+ selectedCount + ') Disapprove');
    }   
  
    // Processed 
    $('#selectAllProcessed').on('click', function() {
      const isChecked = $(this).prop('checked');
      $('.checkbox-item-process').prop('checked', isChecked);
      updateSelectedCount1();

      if ($(this).is(':checked')) {

        const selectedCount = $('.checkbox-item-process:checked').length;

        if(selectedCount > 0){
          
          // Checkbox is checked, show the button
          $('#processedAllBtn').show();
          // $('#disapproveProcessedAllBtn').show();

        }
      } else {
        // Checkbox is unchecked, hide the button
        $('#processedAllBtn').hide();
        // $('#disapproveProcessedAllBtn').hide();

        $('#processedSelectAll').text('Select All');
      }
    });

    $('.checkbox-item-process').on('click', function() {
      if ($(this).is(':checked')) {
        // Checkbox is checked, show the button
        $('#processedAllBtn').show();
        // $('#disapproveProcessedAllBtn').show();
      } else {
        // Checkbox is unchecked, hide the button
        $('#processedAllBtn').hide();
        // $('#disapproveProcessedAllBtn').hide();

        $('#processedSelectAll').text('Select All');
      }

      updateSelectedCount1();
    });

    $('#processedAllBtn').on('click', function() {
      Swal.fire({
        title: "Are you sure?",
        text: "You want to processed this Monetized Transportation Allowance?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"
      }).then((result) => {
        if (result.isConfirmed) {

          $('#loader').show(); // ✅ SAFE

          const selectedItems = [];

          $('.checkbox-item-process:checked').each(function () {
              selectedItems.push($(this).data('id')); // ✅ FIXED FORMAT
          });

          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/processed-mta-all',
            data: {
                ids: JSON.stringify(selectedItems)
            },
            success: function (response) {

              $('#loader').hide(); // ✅ SAFE

              Swal.fire("Monetized Transportation Allowance has been Processed (" + response + ")", "", "success")
                  .then(() => location.reload());
            },
            error: function () {
              $('#loader').hide();
              Swal.fire("Error occurred", "", "error");
            }
          });
        }
      });
    });

    function updateSelectedCount1() {
      const selectedCount = $('.checkbox-item-process:checked').length;
      $('#processedAllBtn').text( '('+ selectedCount + ') Processed');
      // $('#disapproveProcessedAllBtn').text( '('+ selectedCount + ') Disapproved');
    }

    function calculateTotalMTA() {
      let total = 0;

      $('#mtaDataTable tbody tr').each(function () {
        let amountText = $(this).find('td:nth-child(10)').text().replace(/,/g, '');
        let amount = parseFloat(amountText) || 0;
        total += amount;
      });

      $('#total_mta_amount').text(total.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }));
    }

    $(document).ready(function () {
        calculateTotalMTA();
    });
  });
</script>

@foreach ($mtas as $mta)
  @include('for-approval.remarks.mta_process_remarks')
  @include('for-approval.remarks.mta_processed_remarks')
  @include('for-approval.remarks.disapprove_processed_remarks')
  @include('forms.mta.edit')
  @include('for-approval.remarks.view-mta')
@endforeach

@endsection