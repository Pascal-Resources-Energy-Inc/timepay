@extends('layouts.header')
@section('css_header')
<link rel="stylesheet" href="{{asset('./body_css/vendors/fullcalendar/fullcalendar.min.css')}}">
@endsection
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 mb-4  stretch-card transparent">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Form</h3>  
                        <form method='get' onsubmit='show();' enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-right">Company</label>
                                        <select data-placeholder="Select Company" onchange='clear();' class="form-control form-control-sm required js-example-basic-single" style="width:100%;" name="company" id="companySelect" required>
                                            <option value="">-- Select Company --</option>
                                            @foreach($companies as $comp)
                                            <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label class="text-right">From</label>
                                        <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from" required />
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label class="text-right">To</label>
                                        <input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to" required />
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class="form-group">
                                        <label class="text-right">Status</label>
                                        <select id="status" class="form-control form-control-sm" name="status">
                                            <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="All" {{ $status == 'All' ? 'selected' : '' }}>All</option>
                                          </select>
                                        {{-- <input type="text" value='{{$status}}' class="form-control form-control-sm" id='status' name="status" required /> --}}
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex  align-items-center">
                                    <div class="form-group ">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ url('/timekeeping-dashboard') }}" class="btn btn-warning">Reset Filter</a>
                                    </div>
                                </div>                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4  stretch-card transparent">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Leaves</h3>  
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch">
                              <thead>
                                <tr>
                                  <th>Employee</th>
                                  <th>Details</th>
                                  <th>Approver</th>
                                  <th>Date Filed</th>
                                  <th>Type of Leave</th>
                                </tr>
                              </thead>
                              <tbody> 
                                @foreach ($leaves as $item)
                                    <tr>
                                        <td>
                                            <strong>{{$item->user->name}}</strong> <br>
                                            {{-- <small>User ID : {{$item->user->id}}</small> <br> --}}
                                            <small>Employee Code: {{$item->employee->employee_code}}</small><br>
                                            <small>{{$item->user->employee->company->company_name}}</small>
                                            

                                            @if(isset($getLastCutOffDate) && $item->date_from >= $getLastCutOffDate->cut_off_date)

                                            <div class="buttons">
                                                @if ($item->status == 'Pending')
                                                <button type="button" class="btn btn-success btn-sm" id="{{ $item->id }}" data-target="#leave-approved-remarks-{{ $item->id }}" data-toggle="modal" title="Approve">
                                                    <i class="ti-check btn-icon-prepend"></i>                                                    
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" id="{{ $item->id }}" data-target="#leave-declined-remarks-{{ $item->id }}" data-toggle="modal" title="Decline">
                                                    <i class="ti-close btn-icon-prepend"></i>                                                    
                                                </button>
                                                @elseif ($item->status == 'Approved')
                                                <button type="button" class="btn btn-danger btn-sm" id="{{ $item->id }}" data-target="#leave-declined-remarks-{{ $item->id }}" data-toggle="modal" title="Decline">
                                                    <i class="ti-close btn-icon-prepend"></i>                                                    
                                                </button>
                                                <button type="button" id="edit{{ $item->id }}" class="btn btn-info btn-sm"
                                                    data-target="#edit_leave-{{ $item->id }}" data-toggle="modal" title='Edit'>
                                                    <i class="ti-pencil-alt"></i>
                                                </button>
                                                @endif
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            Date From: {{date('M d, Y', strtotime($item->date_from))}} <br>
                                            Date To:  {{date('M d, Y', strtotime($item->date_to))}} <br>
                                            @if ($item->status == 'Pending')
                                                <label class="badge badge-warning">{{ $item->status }}</label>
                                            @elseif($item->status == 'Approved')
                                                <label class="badge badge-success">{{ $item->status }}</label>
                                            @elseif($item->status == 'Rejected' || $item->status == 'Cancelled')
                                                <label class="badge badge-danger">{{ $item->status }}</label>
                                            @endif  
                                        </td>
                                        <td id="tdStatus{{ $item->id }}">
                                            @if(count($item->approver) > 0)
                                                @foreach($item->approver as $approver)
                                                    @if($item->status == 'Approved')
                                                        {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                                    @else
                                                        @if($item->level >= $approver->level)
                                                        @if ($item->level == 0 && $item->status == 'Declined')
                                                        {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                                        @else
                                                            {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                                        @endif
                                                        @else
                                                        @if ($item->status == 'Declined')
                                                            {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                                        @else
                                                            {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                                                        @endif
                                                        @endif<br>
                                                    @endif
                                                @endforeach

                                                {{-- @if($item->status == 'Pending' && $item->level == '1' && count($item->approver) == 1) --}}
                                                @if($item->status == 'Pending' && $item->level == '1')
                                                    <br>
                                                    <button onclick="reset({{ $item->id }},'leave')" class="btn btn-sm btn-primary mt-1">Reset Approval</button>
                                                @endif
                                                
                                            @else
                                            <label class="badge badge-danger mt-1">No Approver</label>
                                            @endif
                                        </td>
                                        <td>
                                            {{ date('M d, Y', strtotime($item->created_at)) }}
                                        </td>
                                        <td>
                                            {{$item->leave->leave_type}}
                                        </td>
                                    </tr>
                                @endforeach               
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-6 mb-4  stretch-card transparent">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Official Business</h3>  
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch">
                              <thead>
                                <tr>
                                  <th>Employee</th>
                                  <th>Details</th>
                                  <th>Approver</th>
                                </tr>
                              </thead>
                              <tbody> 
                                        

                                        @if(date('Y-m-d', strtotime($item->date_from)) >= $cut_date)
                                        <div class="buttons">
                                            @if ($item->status == 'Pending')
                                                <button type="button" class="btn btn-success btn-sm" id="{{ $item->id }}" data-target="#ob-approved-remarks-{{ $item->id }}" data-toggle="modal" title="Approve">
                                                <i class="ti-check btn-icon-prepend"></i>                                                    
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" id="{{ $item->id }}" data-target="#ob-declined-remarks-{{ $item->id }}" data-toggle="modal" title="Decline">
                                                    <i class="ti-close btn-icon-prepend"></i>                                                    
                                                </button>
                                            @elseif ($item->status == 'Approved')
                                                <button type="button" class="btn btn-danger btn-sm" id="{{ $item->id }}" data-target="#ob-declined-remarks-{{ $item->id }}" data-toggle="modal" title="Decline">
                                                <i class="ti-close btn-icon-prepend"></i>                                                    
                                                </button>
                                                <button type="button" id="edit{{ $item->id }}" class="btn btn-info btn-sm" data-target="#edit_ob-{{ $item->id }}" data-toggle="modal" title='Edit'>
                                                    <i class="ti-pencil-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        Date: {{date('M d, Y', strtotime($item->applied_date))}} <br>
                                        Time:  {{ date('H:i', strtotime($item->date_from)) }} - {{ date('H:i', strtotime($item->date_to)) }}<br>
                                        @if ($item->status == 'Pending')
                                            <label class="badge badge-warning">{{ $item->status }}</label>
                                        @elseif($item->status == 'Approved')
                                            <label class="badge badge-success">{{ $item->status }}</label>
                                        @elseif($item->status == 'Rejected' || $item->status == 'Cancelled')
                                            <label class="badge badge-danger">{{ $item->status }}</label>
                                        @endif  
                                    </td>
                                    <td id="tdStatus{{ $item->id }}">
                                        @if(count($item->approver) > 0)
                                            @foreach($item->approver as $approver)
                                                @if($item->status == 'Approved')
                                                    {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                                @else
                                                    @if($item->level >= $approver->level)
                                                    @if ($item->level == 0 && $item->status == 'Declined')
                                                    {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                                    @else
                                                        {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                                    @endif
                                                    @else
                                                    @if ($item->status == 'Declined')
                                                        {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                                    @else
                                                        {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                                                    @endif
                                                    @endif<br>
                                                @endif
                                            @endforeach

                                            @if($item->status == 'Pending' && $item->level == '1')
                                                <br>
                                                <button onclick="reset({{ $item->id }},'ob')" class="btn btn-sm btn-primary mt-1">Reset Approval</button>
                                            @endif
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
            </div> -- }}
            {{-- <div class="col-md-6 mb-4  stretch-card transparent">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Work From Home</h3>  
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch">
                              <thead>
                                <tr>
                                  <th>Employee</th>
                                  <th>Details</th>
                                  <th>Approver</th>
                                </tr>
                              </thead>
                              <tbody> 
                                @foreach ($wfhs as $item)
                                <tr>
                                    <td>
                                        <strong>{{$item->user->name}}</strong> <br>
                                        <small>Employee Code: {{$item->employee->employee_code}}</small><br>
                                        <small>{{$item->user->employee->company->company_name}}</small>
                                    
                                    </td>
                                    <td>
                                        Date: {{date('M d, Y', strtotime($item->applied_date))}} <br>
                                        Time:  {{ date('H:i', strtotime($item->date_from)) }} - {{ date('H:i', strtotime($item->date_to)) }}<br>
                                        @if ($item->status == 'Pending')
                                            <label class="badge badge-warning">{{ $item->status }}</label>
                                        @elseif($item->status == 'Approved')
                                            <label class="badge badge-success">{{ $item->status }}</label>
                                        @elseif($item->status == 'Rejected' || $item->status == 'Cancelled')
                                            <label class="badge badge-danger">{{ $item->status }}</label>
                                        @endif  
                                    </td>
                                    <td id="tdStatus{{ $item->id }}">
                                        @if(count($item->approver) > 0)
                                            @foreach($item->approver as $approver)
                                                @if($item->level >= $approver->level)
                                                @if ($item->level == 0 && $item->status == 'Declined')
                                                {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                                @else
                                                    {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                                @endif
                                                @else
                                                @if ($item->status == 'Declined')
                                                    {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                                @else
                                                    {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                                                @endif
                                                @endif<br>
                                            @endforeach

                                            @if($item->status == 'Pending' && $item->level == '1')
                                                <br>
                                                <button onclick="reset({{ $item->id }},'wfh')" class="btn btn-sm btn-primary mt-1">Reset Approval</button>
                                            @endif

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
            </div> --}}
            <div class="col-md-6 mb-4  stretch-card transparent">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Overtime</h3>  
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch">
                              <thead>
                                <tr>
                                  <th>Employee</th>
                                  <th>Details</th>
                                  <th>Approver</th>
                                  <th>Approved OT</th>
                                </tr>
                              </thead>
                              <tbody> 
                                @foreach ($overtimes as $item)
                                <tr>
                                    <td>
                                        <strong>{{$item->user->name}}</strong> <br>
                                        {{-- <small>User ID : {{$item->user->id}}</small> <br> --}}
                                        <small>Employee Code: {{$item->employee->employee_code}}</small><br>
                                        <small>{{$item->user->employee->company->company_name}}</small>

                                        @if(isset($getLastCutOffDate) && $item->date_from >= $getLastCutOffDate->cut_off_date)

                                        <div class="buttons">
                                            @if ($item->status == 'Pending')
                                                <button type="button" class="btn btn-success btn-sm" id="{{ $item->id }}" data-target="#approve-ot-hrs-{{ $item->id }}" data-toggle="modal" title="Approve">
                                                <i class="ti-check btn-icon-prepend"></i>                                                    
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" id="{{ $item->id }}" data-target="#overtime-declined-remarks-{{ $item->id }}" data-toggle="modal" title="Decline">
                                                    <i class="ti-close btn-icon-prepend"></i>                                                    
                                                </button>
                                            @elseif ($item->status == 'Approved')
                                                <button type="button" class="btn btn-danger btn-sm" id="{{ $item->id }}" data-target="#overtime-declined-remarks-{{ $item->id }}" data-toggle="modal" title="Decline">
                                                    <i class="ti-close btn-icon-prepend"></i>                                                    
                                                </button>
                                                <button type="button" class="btn btn-info btn-sm" id="{{ $item->id }}" data-target="#edit_time-{{ $item->id }}" data-toggle="modal" title="Edit">
                                                    <i class="ti-pencil-alt btn-icon-prepend"></i>                                                    
                                                </button>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        Date: {{date('M d, Y', strtotime($item->ot_date))}} <br>
                                        Time:  {{ date('H:i', strtotime($item->start_time)) }} - {{ date('H:i', strtotime($item->end_time)) }}<br>
                                        @if ($item->status == 'Pending')
                                            <label class="badge badge-warning">{{ $item->status }}</label>
                                        @elseif($item->status == 'Approved')
                                            <label class="badge badge-success">{{ $item->status }}</label>
                                        @elseif($item->status == 'Rejected' || $item->status == 'Cancelled')
                                            <label class="badge badge-danger">{{ $item->status }}</label>
                                        @endif  
                                    </td>
                                    <td id="tdStatus{{ $item->id }}">
                                        @if(count($item->approver) > 0)
                                            @foreach($item->approver as $approver)
                                                @if($item->status == 'Approved')
                                                    {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                                @else
                                                    @if($item->level >= $approver->level)
                                                    @if ($item->level == 0 && $item->status == 'Declined')
                                                    {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                                    @else
                                                        {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                                    @endif
                                                    @else
                                                    @if ($item->status == 'Declined')
                                                        {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                                    @else
                                                        {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                                                    @endif
                                                    @endif<br>
                                                @endif
                                            @endforeach

                                            @if($item->status == 'Pending' && $item->level == '1')
                                                <br>
                                                <button onclick="reset({{ $item->id }},'ot')" class="btn btn-sm btn-primary mt-1">Reset Approval</button>
                                            @endif
                                        @else
                                        <label class="badge badge-danger mt-1">No Approver</label>
                                        @endif
                                    </td>
                                    <td>
                                        {{$item->ot_approved_hrs - $item->break_hrs}}
                                    </td>
                                </tr>
                                @endforeach                      
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <div class="col-md-6 mb-4  stretch-card transparent">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Travel Order</h3>
                    <div class="table-responsive">
                       
                        <table class="table table-hover table-bordered tablewithSearch">
                            <thead>
                            <tr>
                                <th>Action </th> 
                                <th>Employee Name</th>
                                <th>Date Filed</th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Destination</th>
                                <th>Purpose</th>
                                <th>Approvers</th> 
                                <th>Status</th>
                                <th>Attachment</th>
                            </tr>
                            </thead>
                            
                            <tbody> 
                            @foreach ($tos as $form_approval)
                                <tr>
                               <td align="center" id="tdActionId{{ $form_approval->id }}">
                                    @if($form_approval->status == 'Approved')
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#to-view-approved-{{ $form_approval->id }}" title="View">
                                            <i class="ti-eye btn-icon-prepend"></i> View
                                        </button>
                                    @elseif($form_approval->status == 'Pending')
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#to-view-modal-{{ $form_approval->id }}" title="View">
                                            <i class="ti-eye btn-icon-prepend"></i> View
                                        </button>
                                    @elseif($form_approval->status == 'Declined')
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#to-view-declined-{{ $form_approval->id }}" title="View">
                                            <i class="ti-eye btn-icon-prepend"></i> View
                                        </button>
                                    @endif
                                </td>
                                
                                <td>
                                    <strong>{{ $form_approval->user->name }}</strong><br>
                                    <small>Position: {{ $form_approval->user->employee->position }}</small><br>
                                    <small>Location: {{ $form_approval->user->employee->location }}</small><br>
                                    <small>Department: {{ $form_approval->user->employee->department->name ?? '' }}</small>
                                </td>

                                <td>{{ date('M. d, Y', strtotime($form_approval->created_at)) }} - {{ date('h:i A', strtotime($form_approval->created_at)) }}</td>
                                <td>{{ date('M. d, Y', strtotime($form_approval->date_from)) }} - {{ date('M. d, Y', strtotime($form_approval->date_to)) }}</td>
                                <td>{{ date('h:i A', strtotime($form_approval->date_from)) }}</td>
                                <td>{{ date('h:i A', strtotime($form_approval->date_to)) }}</td>
                                <td>{{ $form_approval->destination }}</td>
                                <td>{{ $form_approval->purpose }}</td>

                                <td>
                                    @foreach($form_approval->approver as $approver)
                                    {{ $approver->approver_info->name }}<br>
                                    @endforeach
                                </td>

                                <td>
                                    @if ($form_approval->status == 'Pending')
                                    <label class="badge badge-warning">{{ $form_approval->status }}</label>
                                    @elseif ($form_approval->status == 'Approved')
                                    <label class="badge badge-success" title="{{ $form_approval->approval_remarks }}">{{ $form_approval->status }}</label>
                                    @elseif ($form_approval->status == 'Declined' || $form_approval->status == 'Cancelled')
                                    <label class="badge badge-danger" title="{{ $form_approval->approval_remarks }}">{{ $form_approval->status }}</label>
                                    @endif
                                </td>

                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" title="Attachment" data-toggle="modal" data-target="#view-modal-{{ $form_approval->id }}">
                                    <i class="ti-folder"></i>
                                    </button>
                                </td>
                                </tr>
                                @endforeach

                            
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

            {{-- <div class="col-md-6 mb-4  stretch-card transparent">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">DTR Approvals</h3>  
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch">
                              <thead>
                                <tr>
                                  <th>Employee</th>
                                  <th>Details</th>
                                  <th>Approver</th>
                                </tr>
                              </thead>
                              <tbody> 
                                @foreach ($dtrs as $item)
                                <tr>
                                    <td>
                                        <strong>{{$item->user->name}}</strong> <br>
                                        <small>Employee Code: {{$item->employee->employee_code}}</small><br>
                                        <small>{{$item->user->employee->company->company_name}}</small>
                                    
                                    </td>
                                    <td>
                                        Date: {{date('M d, Y', strtotime($item->dtr_date))}} <br>
                                        Time:  {{ date('H:i', strtotime($item->time_in)) }} - {{ date('H:i', strtotime($item->time_out)) }}<br>
                                        Correction:  {{ $item->correction }}<br>
                                        @if ($item->status == 'Pending')
                                            <label class="badge badge-warning">{{ $item->status }}</label>
                                        @elseif($item->status == 'Approved')
                                            <label class="badge badge-success">{{ $item->status }}</label>
                                        @elseif($item->status == 'Rejected' || $item->status == 'Cancelled')
                                            <label class="badge badge-danger">{{ $item->status }}</label>
                                        @endif  
                                    </td>
                                    <td id="tdStatus{{ $item->id }}">
                                        @if(count($item->approver) > 0)
                                            @foreach($item->approver as $approver)
                                                @if($item->level >= $approver->level)
                                                @if ($item->level == 0 && $item->status == 'Declined')
                                                {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                                @else
                                                    {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                                @endif
                                                @else
                                                @if ($item->status == 'Declined')
                                                    {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                                @else
                                                    {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                                                @endif
                                                @endif<br>
                                            @endforeach

                                            @if($item->status == 'Pending' && $item->level == '1')
                                                <br>
                                                <button onclick="reset({{ $item->id }},'dtr')" class="btn btn-sm btn-primary mt-1">Reset Approval</button>
                                            @endif
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
            </div> --}} 
            {{-- <div class="col-md-3 mb-4  stretch-card transparent">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Used Leaves</h3>  
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch">
                              <thead>
                                <tr>
                                  <th>Employee</th>
                                  <th>Count</th>
                                </tr>
                              </thead>
                              <tbody> 
                                                    
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-md-3 mb-4  stretch-card transparent">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Earned Leaves</h3>  
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch">
                              <thead>
                                <tr>
                                  <th>Employee</th>
                                  <th>Count</th>
                                </tr>
                              </thead>
                              <tbody> 
                                                    
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-md-6 mb-4  stretch-card transparent">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Employee Attendances</h3>  
                        <table class="table table-hover table-bordered tablewithSearch">
                            <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Lates</th>
                                <th>Undertime</th>
                            </tr>
                            </thead>
                        
                            <tbody> 
                                @foreach($emp_data as $emp)
                                <tr>
                                    <td>
                                        {{$emp->first_name . ' ' . $emp->last_name}} <br>
                                        <small>{{$emp->employee_number}}</small>
                                    </td>
                                    <td>
                                        {{getCountLates($date_range,$emp->attendances, $emp->schedule_id)}}
                                    </td>
                                    <td></td>
                                </tr>  
                                @endforeach
                            </tbody>
                           
                        </table>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection

<script>
    function reset(id,form) {
			swal({
					title: "Are you sure?",
					text: "You want to reset approval for this "+form+" ?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willApprove) => {
					if (willApprove) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "reset-"+form+"/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal(form + " has been reset!", {
									icon: "success",
								}).then(function() {
									location.reload();
								});
							}
						})

					} else {
            swal({text:"You stop the approval of leave.",icon:"success"});
					}
				});
		}
</script>

@foreach ($leaves as $leave)
  @include('for-approval.remarks.leave_approved_remarks')
  @include('for-approval.remarks.leave_declined_remarks')
  @include('for-approval.remarks.leave_approved_edit')
@endforeach

@foreach ($overtimes as $overtime)
  @include('for-approval.remarks.overtime_approved_remarks')
  @include('for-approval.remarks.overtime_declined_remarks')
  @include('for-approval.remarks.overtime_edit_time')
@endforeach 

@foreach ($tos as $to)
  @include('for-approval.remarks.view-toapproved')
  @include('for-approval.remarks.view-todeclined')
@endforeach


@include('for-approval.view-toManager') 
@include('for-approval.view-form') 
