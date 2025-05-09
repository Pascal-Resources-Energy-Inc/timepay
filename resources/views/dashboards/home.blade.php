@extends('layouts.header')
@section('css_header')
<link rel="stylesheet" href="{{asset('./body_css/vendors/fullcalendar/fullcalendar.min.css')}}">
<link rel="stylesheet" href="{{asset('./body_css/vendors/owl-carousel-2/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('./body_css/vendors/owl-carousel-2/owl.theme.default.min.css')}}">
<style>
  /* p { line-height: .75rem !important; } */

</style>
{{-- <style>
 
.card-img
{
  position: relative;
}

  .card-text, .card-text p {
    font-size: clamp(0.9rem, 1.5vw, 1.2rem);
    margin-bottom: 0;
  }
  @media (min-width: 768px) {
    .inner-image {
  position: absolute;
  top: 70px;
  right:50px;
  width:200px !important;
  height:240px !important;
  background-color:  white;
}
.name
    {
      margin-top:1rem;
      font-size:18px;
    }
    .date-hired
    {
      font-size:12px;
    }
.renz
    {
      margin: 1rem;
    }
    .name-center
    {
      /* margin-top:5px; */
      margin-left: 80px;
      margin-top:20px;
      
    }
  }
  @media (max-width: 768px) {
    p { line-height: .2rem !important; }
    .card-text, .card-text p {
      text-align: left;
    }
    .inner-image {
  position: absolute;
  top: 30px;
  right:30px;
  width:100px !important;
  height:120px !important;
  background-color:  white;
}
    .renz
    {
      font-size:12px;
      margin-top: 6px;
    }
    .name
    {
      
      font-size:8px;
    }
    .name-center
    {
      /* margin-top:5px; */
      margin-left: 45px;
      
    }
    .date-hired
    {
      font-size:5px;
    }
  }

  .card-img-overlay {
    /* padding: 1rem; */
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: flex-start;
  }
</style> --}}
@endsection
@section('content')
@if(auth()->user()->login)
@if($attendance_now != null)
@include('employees.timeout')
@else
@include('employees.timein')
@endif
@endif
<div class="main-panel">
  @if(auth()->user()->employee->status != "Inactive")
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold ">Welcome {{auth()->user()->employee->first_name}},</h3>
                </div>
              </div>
            </div>
        </div>
          <div class="row">
              <div class="col-md-4 transparent">
                <div class="row">
                    <div class="col-md-12 mb-4 transparent">
                        <div class="card">
                          <div class="card-body">
                            <h3 class="card-title">{{date('M d, Y')}} 
                              @if(auth()->user()->login)
                                @if($attendance_now != null)
                                  <button onclick="getLocation()" type="button" Title='Time Out' class="btn btn-danger btn-rounded btn-icon" data-toggle="modal" data-target="#timeOut">
                                    <i class="ti-control-pause" ></i>
                                  </button>
                                  @else
                                  <button onclick="getLocation()" type="button" Title='Time In' class="btn btn-success btn-rounded btn-icon" data-toggle="modal" data-target="#timeIn">
                                  <i class="ti-control-play" ></i>
                                </button>
                              @endif
                            @endif
                          </h3>
                            <div class="media">
                                <i class="ti-time icon-md text-info d-flex align-self-center mr-3"></i>
                                <div class="media-body">
                                  <p class="card-text">Time In : 
                                    @if($attendance_now != null){{date('h:i A',strtotime($attendance_now->time_in))}} <br>
                                    @php
                                          $employee_schedule = employeeSchedule($schedules,$attendance_now->time_in,$schedules[0]->schedule_id);
                                          $estimated_out = "";
                                          $halfday_out = "";
                                          $schedule_hours = 0;
                                          if($employee_schedule != null)
                                          {
                                            $schedule_out = strtotime(date('Y-m-d')." ".$employee_schedule->time_out_to);
                                            $schedule_in = strtotime(date('Y-m-d')." ".$employee_schedule->time_in_to);
                                            if(($schedule_out) < ($schedule_in))
                                            {
                                                
                                                $schedule_out = strtotime($date_r." ".$employee_schedule->time_out_to)+86400;
                                                // dd(date('Y-m-d H:i',$schedule_out)." ".date('Y-m-d H:i',$schedule_in));
                                            }
                                            $schedule_hours = ((($schedule_out)-($schedule_in))/3600);
                                            // dd(date('Y-m-d',strtotime($date_r)));
                                            if($schedule_hours > 8)
                                            {
                                                $schedule_hours =  $schedule_hours-1;
                                                
                                                
                                            }
                                            if(strtotime(date('h:i A',strtotime($attendance_now->time_in))) < strtotime(date('h:i A',strtotime(date('Y-m-d')." ".$employee_schedule['time_in_from']))))
                                            {
                                          
                                                $halfday_out = date("h:i A", strtotime('+'.intval(($schedule_hours/2)*60).' minutes', strtotime(date('Y-m-d')." ".$employee_schedule['time_in_from'])));
                                                $estimated_out = date('h:i A',strtotime($employee_schedule['time_out_from']));
                                            }
                                            else
                                            {
                                              // dd($schedule_hours/2);
                                           
                                              $halfday_out = date("h:i A", strtotime('+'.intval(($schedule_hours/2)*60).' minutes', strtotime($attendance_now->time_in)));
                                              // dd($halfday_out);
                                                $hours = intval($employee_schedule['working_hours']);
                                                $minutes = ($employee_schedule['working_hours']-$hours)*60;
                                                $estimated_out = date('h:i A', strtotime("+".$hours." hours",strtotime($attendance_now->time_in)));
                                                $estimated_out = date('h:i A', strtotime("+".$minutes." minutes",strtotime($estimated_out)));
                                            }
                                            if(strtotime(date('h:i A',strtotime($attendance_now->time_in))) > strtotime(date('h:i A',strtotime($employee_schedule['time_in_to']))))
                                            {
                                                $estimated_out = date('h:i A',strtotime($employee_schedule['time_out_to']));
                                                $halfday_out = date("h:i A", strtotime('+'.intval(($schedule_hours/2)*60).' minutes', strtotime($attendance_now->time_in)));
                                            }

                                          }
                                          else {
                                            $estimated_out = "No Schedule";
                                            $halfday_out = "No Schedule";
                                          }
                                          
                                        @endphp
                                    @if($attendance_now->time_out == null )
                                        {{-- <hr>
                                        <small>
                                        Estimated Halfday Out : {{$halfday_out}} <br>
                                        Estimated Out : {{$estimated_out}} 
                                      </small> --}}
                                    @else
                                    Time Out : {{date('h:i A',strtotime($attendance_now->time_out))}} <br>
                                    {{-- <hr>
                                    <small> --}}
                                    {{-- Estimated Halfday Out : {{$halfday_out}} <br>
                                    Estimated Out : {{$estimated_out}}  --}}
                                  </small>
                                    @endif
                                  @else NO TIME IN 
                                  @endif</p>
                                  {{-- <button type="button" class="btn btn-outline-danger btn-fw btn-sm">Time Out</button> --}}
                                </div>
                              </div>
                          </div>
                        </div>
                        @if(count(auth()->user()->subbordinates) > 0)
                        <div class="card mt-2">
                          <div class="card-body">
                            <p class="card-title ">Subordinates </p>
                              <div class="table-responsive" >
                                <table class="table table-hover table-bordered tablewithSearchonly" >
                                  <thead>
                                    <tr>
                                      <th>Name</th>
                                      <th>In</th>
                                      <th>Out</th>
                                      <th>Leave Balances</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                      
                                    @foreach(auth()->user()->subbordinates as $emp)
                                    <tr>
                                      <td>{{$emp->first_name}} {{$emp->last_name}} </td>
                                      @php
                                          // dd($attendance_employees);
                                          $time_in = $attendance_employees->where('employee_code',$emp->employee_number)->where('time_in','!=',null)->first();
                                          $leave_with_pay = $emp ? $emp->approved_leaves_with_pay->where('date_from', date('Y-m-d'))->first() : null;
                                      @endphp
                                      <td>
                                        @if($leave_with_pay)
                                            Leave-With-Pay
                                        @elseif($time_in && $time_in->time_in)
                                            {{ date('h:i A', strtotime($time_in->time_in)) }}
                                        @else
                                            No Data
                                        @endif
                                      </td>
                                      <td>
                                        @if($time_in)
                                            @if($time_in->time_out)
                                                {{ date('h:i a', strtotime($time_in->time_out)) }}
                                            @else
                                            No Data
                                            @endif
                                        @else
                                        No Data
                                        @endif
                                    </td> 
                                        <td>
                                            @php
                                                $vl_balance = 0;
                                                $sl_balance = 0;
                                                
                                                $vl_leave = ($emp->employee_leave_credits)->where('leave_type', 1)->first();

                                                if(!empty($vl_leave))
                                                {
                                                    $earned_vl = checkEarnedLeave($emp->user_id,1,$emp->original_date_hired);
                                                    $used_vl = checkUsedSLVLSILLeave($emp->user_id,1,$emp->original_date_hired,$emp->ScheduleData);
                                                    $vl_beginning_balance =  $vl_leave->count;
    
                                                    $vl_balance = ($vl_beginning_balance + $earned_vl) - $used_vl;
                                                }

                                                $sl_leave = ($emp->employee_leave_credits)->where('leave_type', 2)->first();
                                                if (!empty($sl_leave))
                                                {
                                                    $earned_sl = checkEarnedLeave($emp->user_id,2,$emp->original_date_hired);
                                                    $used_sl = checkUsedSLVLSILLeave($emp->user_id,2,$emp->original_date_hired,$emp->ScheduleData);

                                                    $sl_beginning_balance = $sl_leave->count;
                                                    $sl_balance = ($sl_beginning_balance + $earned_sl) - $used_sl;
                                                }
                                            @endphp
                                            VL = {{$vl_balance}}
                                            <br>
                                            SL = {{$sl_balance}}
                                        </td>
                                    </tr>
                                    @endforeach
                    
                                  </tbody>
                              </table>
                              </div>
                          </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-body">
                        <p class="card-title ">Holidays:&nbsp;<i style="font-weight: normal"><small>{{date('M 01')}} - {{date('M t')}}</small></i></p>
                        <div class="table-responsive">
                          <table class="table table-striped table-borderless">
                            <thead>
                              <tr>
                                <th>Holiday Name</th>
                                <th>Location</th>
                                <th>Date</th>
                              </tr>  
                            </thead>
                            <tbody>
                              @foreach($holidays as $holiday)
                              <tr>
                                <td>{{$holiday->holiday_name}}</td>
                                <td>{{$holiday->location}}</td>
                                <td class="font-weight-medium"><div class="badge badge-success">{{date('M d',strtotime($holiday->holiday_date))}}</div></td>
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
              <div class="col-md-5">
                {{-- <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Welcome new Hires</h4>
                    <div class="owl-carousel owl-theme full-width">
                      @foreach($employees_new_hire as $employee)
                        <div class="item">
                          <div class="card text-white">
                            <img class="card-img" src="{{ asset('images/new_hire.png') }}" alt="Card image">
                            
                            <img src='{{ URL::asset($employee->avatar) }}' onerror="this.src='{{ URL::asset('/images/no_image.png') }}';"   alt="" class="inner-image"/>
                            <div class="card-img-overlay">
                              <h6 class="card-text text-dark renz">
                                Welcome to PREI, <strong>{{ $employee->nick_name }}!</strong>
                                <br><br>
                                  <p class="text-dark name-center">
                                    <strong class='name'>{{ $employee->first_name }} {{ $employee->last_name }}</strong><br>
                                    <small class='date-hired'>Date of Joining: {{ date('d-M-Y', strtotime($employee->original_date_hired)) }}</small><br>
                                    <small class='date-hired'>Native of <strong>{{ $employee->birth_place }}</strong></small>

                                  
                                  </p>
                                  <p class="text-dark name-center mt-2">
                                    <strong class='name'>{{ $employee->position }} </strong><br>
                                    <small class='date-hired'>{{ $employee->location }} </small><br>
                                  </p>
                                <p class="text-dark name-center mt-3">
                                  <br>
                                  <strong class='name'>{{ $employee->personal_number }} </strong><br>
                                </p>
                                <p class="text-dark name-center mt-3">
                                  <br>
                                  <span class='date-hired'>{{ $employee->user_info->email }} </span><br>
                                </p>
                              </h6>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div> --}}
              </div>
            <div class="col-md-3 ">
              {{-- <div class="row">
                <div class="col-md-12">
                  <div class="card" >
                    <div class="card-body">
                      <div class="card-title">
                        Employee Anniversaries
                      </div>
                      <ul class="icon-data-list w-100" style="overflow-y: scroll; height:300px;" >
                        @foreach($employee_anniversaries->sortBy('original_date_hired') as $emp)
                        @php
                          $original_date_hired = new DateTime($emp->original_date_hired);
                          $current_date = new DateTime();
                          $current_anniversary = new DateTime($current_date->format('Y') . '-' . $original_date_hired->format('m-d'));
                          $s = $current_date->diff($original_date_hired)->format('%y') > 1 ? 's' : '';
                          
                          if ($current_anniversary >= $current_date) {
                            $anniv_year = $current_date->diff($original_date_hired)->y + 1;
                          }
                          else {
                            $anniv_year = $current_date->diff($original_date_hired)->y;
                          }
                          
                        @endphp
                        <li>
                          <div class="d-flex">
                            <img src="{{URL::asset($emp->avatar)}}"  onerror="this.src='{{URL::asset('/images/no_image.png')}}';" alt="user">
                            <div>
                              <p class="text-info mb-1"><small>{{$emp->first_name}} {{$emp->last_name}}</small> <i>(<small class='text-danger'>{{$anniv_year.' year'.$s.' of service'}}</small>)</i></p>
                              <p class="mb-0"><small>{{$emp->company->company_code}}</small> - <small>{{$emp->department->name}}</small></p>
                            </div>
                          </div>
                        </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
              </div> --}}
              <div class='row'>
                <div class="col-md-12">
                  <div class="card ">
                    <div class="card-body " >
                      <p class="card-title">Birthday Celebrants</p>
                      <ul class="icon-data-list w-100"  style="overflow-y: scroll; height:300px;">
                        @foreach($employee_birthday_celebrants as $celebrant)
                        <li>
                          <div class="d-flex">
                            <img src="{{URL::asset($celebrant->avatar)}}"  onerror="this.src='{{URL::asset('/images/no_image.png')}}';" alt="user">
                            <div>
                              <p class="text-info mb-1"><small>{{$celebrant->first_name}} {{$celebrant->last_name}} - ({{$celebrant->location}})</small></p>
                              
                              <p class="mb-0"><small>{{$celebrant->position}}</small> - 
                                <small>{{date('M d',strtotime($celebrant->birth_date))}}</small></p>
                            </div>
                          </div>
                        </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class='row'>
                <div class="col-md-12">
                  <div class="card mt-2">
                    <div class="card-body " >
                      <p class="card-title">Welcome new Hires</p>
                      <ul class="icon-data-list w-100"  style="overflow-y: scroll; height:300px;">
                        @foreach($employees_new_hire as $employee)
                        <li>
                          <div class="d-flex">
                            <img src="{{URL::asset($employee->avatar)}}"  onerror="this.src='{{URL::asset('/images/no_image.png')}}';" alt="user">
                            <div>
                              <p class="text-info mb-1"><small>{{$employee->first_name}} {{$employee->last_name}}</small> <i>(<small>{{date('M. d',strtotime($employee->original_date_hired))}}</small>)</i> - <small>{{$employee->company->company_code}}</small></p>
                          
                              <p class="mb-0"><small>{{$employee->position}}</small> - <small>{{$employee->department->name}}</small></p>
                             
                            </div>
                          </div>
                        </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
                
              @if (checkUserPrivilege('allow_prob',auth()->user()->id) == 'yes')
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card mt-2">
                        <div class="card-body">
                          <div class="card-title">Probationary Employee</div>
                          <ul class="icon-data-list w-100"  style="overflow-y: scroll; height:300px;">
                            @foreach($probationary_employee as $prob_emp)
                            <li>
                              <div class="d-flex">
                                <img src="{{URL::asset($prob_emp->avatar)}}"  onerror="this.src='{{URL::asset('/images/no_image.png')}}';" alt="user">
                                <div>
                                  <div>
                                    <p class="text-info mb-1"><small>{{$prob_emp->first_name}} {{$prob_emp->last_name}}</small><a id="edit{{ $prob_emp->id }}" 
                                      data-target="#edit_prob{{ $prob_emp->id }}" data-toggle="modal" title='Edit'>
                                      <i class="ti-pencil-alt"></i>
                                    </a></p>
                                  </div>
                                  <p class="mb-0"><small>{{$prob_emp->company->company_name}}</small></p>
                                  <p class="mb-0"><small>{{$prob_emp->position}}</small></p>
                                  <p class="mb-0"><small>{{date('M d, Y',strtotime($prob_emp->original_date_hired))}}</small></p>
                                  <p class="mb-0"><small>
                                    @php
                                      $date_from = new DateTime($prob_emp->original_date_hired);
                                      $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                                      $y_s = $date_diff->format('%y') > 1 ? 's' : '';
                                      $m_s = $date_diff->format('%m') > 1 ? 's' : '';
                                      $d_s = $date_diff->format('%d') > 1 ? 's' : '';
                                    @endphp
                                    {{$date_diff->format('%y Year'.$y_s.' %m month'.$m_s.' %d day'.$d_s.'')}}</small>
                                  </p>
                                </div>
                              </div>
                            </li>
                            @endforeach
                          </ul>
                        </div>
                      </div>
                  </div>
                </div>
              @endif
            </div>
          </div>    
          <div class='row'>
          </div>
    </div>
    @endif
</div>
<div class="modal fade" id="event_data" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         Description : <span id='modalBody'>
        </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection
@section('footer')
<script src="{{asset('./body_css/vendors/owl-carousel-2/owl.carousel.min.js')}}"></script>
<script src="{{asset('./body_css/js/tooltips.js')}}"></script>
<script src="{{asset('./body_css/js/popover.js')}}"></script>
<script src="{{asset('./body_css/vendors/moment/moment.min.js')}}"></script>
<script src="{{asset('./body_css/js/owl-carousel.js')}}"></script>

@foreach ($probationary_employee as $prob_emp)
@include('dashboards_modal.edit_prob')
@endforeach
@endsection
