@extends('layouts.header')
@section('css_header')
<link rel="stylesheet" href="{{asset('./body_css/vendors/fullcalendar/fullcalendar.min.css')}}">
<link rel="stylesheet" href="{{asset('./body_css/vendors/owl-carousel-2/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('./body_css/vendors/owl-carousel-2/owl.theme.default.min.css')}}">
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>
.custom-swal-icon-spacing {
  margin-top: 20px !important;
}

.sideCharts {
   max-width: 100&;
   height: 350px;
}

.chart-wrapper {
    position: relative;
    width: 100%;
    margin: 0 auto;
}

.centerChart {
    width: 100% !important;
    height: 307px !important;
}


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
                  <!-- <h3 class="font-weight-bold ">Welcome {{auth()->user()->employee->first_name}}</h3> -->
                </div>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="col-md-3 mb-4 transparent">
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
                      </div>
          <div class="col-md-3 mb-3">
            <div class="card show-used-leave-days" style="border: 2px solid rgba(0, 191, 255, 0.67); border-radius: 8px; height: 110px; cursor: pointer;">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <div class="icon-container me-3" style="position: relative; width: 60px; height: 40px;">
                    <i class="fas fa-user-friends" style="font-size: 24px; color: #ff4444; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                  </div>
                  <div class="text-content">
                    <p class="mb-1" style="font-size: 14px; color: #000;"><strong>Used Leave</strong></p>
                  </div>
                </div>
                <div class="number-badge" style="width: 35px; height: 35px; background-color: #00bfff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                  {{ $totalUsedLeaveDays }}
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3 mb-3">
            <div class="card show-late-records" style="cursor:pointer; border: 2px solid rgba(0, 191, 255, 0.67); border-radius: 8px; height: 110px;">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <div class="icon-container me-3" style="position: relative; width: 60px; height: 40px;">
                    <i class="fas fa-clock" style="font-size: 24px; color: #ff4444; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                  </div>
                  <div class="text-content">
                    <p class="mb-1" style="font-size: 14px; color: #000; margin: 0;"><strong>Late</strong></p>
                  </div>
                </div>
                <div class="number-badge" style="width: 35px; height: 35px; background-color: #00bfff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                  {{ count($lateRecords) ?? 0 }}
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3 mb-3">
            <div class="card show-absent-dates" style="border: 2px solid rgba(0, 191, 255, 0.67); border-radius: 8px; height: 110px; cursor: pointer;">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <div class="icon-container me-3" style="position: relative; width: 60px; height: 40px;">
                    <i class="fas fa-user-times" style="font-size: 24px; color: #ff4444; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                  </div>
                  <div class="text-content">
                    <p class="mb-1" style="font-size: 14px; color: #000;"><strong>Absent Days</strong></p>
                  </div>
                </div>
                <div class="number-badge" style="width: 35px; height: 35px; background-color: #00bfff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                  {{ count($absentDates) ?? 0 }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card shadow-sm mb-4 position-relative">
            <div class="position-absolute bg-light text-dark px-2 py-1 rounded small fw-bold" style="top: 20px; left: 20px;">
                <strong>Late – Current Cutoff</strong>
            </div>
            <br><br>
            <div class="card-body">
                <div class="chart-container" style="position: relative; width: 100%; height: 350px; overflow-x: auto; overflow-y: hidden;">
                   <div style="min-width: 600px; height: 335px;">
                      <canvas id="userLateChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @if (auth()->user()->role == 'Admin')
          <div class="row">
            <div class="col-md-3 mb-2">
              <div class="card" style="border: 2px solid rgba(0, 191, 255, 0.67); border-radius: 8px; height: 110px;">
                <div class="card-body d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    <div class="icon-container me-3" style="position: relative; width: 60px; height: 40px;">
                      <i class="fas fa-user-friends" style="font-size: 24px; color: #ff4444; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                    </div>
                    <div class="text-content">
                      <p class="mb-1" style="font-size: 14px; color: #000;"><strong>Employees</strong></p>
                    </div>
                  </div>
                  <div class="number-badge employees-count" style="width: 35px; height: 35px; background-color: #00bfff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                    {{ $total_employees }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="card" style="border: 2px solid rgba(0, 191, 255, 0.67); border-radius: 8px; height: 110px;">
                <div class="card-body d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    <div class="icon-container me-3" style="position: relative; width: 60px; height: 40px;">
                      <i class="fas fa-user-check" style="font-size: 24px; color: #ff4444; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                    </div>
                    <div class="text-content">
                      <p class="mb-1" style="font-size: 14px; color: #000;"><strong>Present</strong></p>
                    </div>
                  </div>
                  <div class="number-badge present-count" style="width: 35px; height: 35px; background-color: #00bfff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                    {{ $present_today_count ?? 0 }}
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-md-3 mb-3">
              <div class="card" style="border: 2px solid rgba(0, 191, 255, 0.67); border-radius: 8px; height: 110px;">
                <div class="card-body d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    <div class="icon-container me-3" style="position: relative; width: 60px; height: 40px;">
                      <i class="fas fa-user-times" style="font-size: 24px; color: #ff4444; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                    </div>
                    <div class="text-content">
                      <p class="mb-1" style="font-size: 14px; color: #000;"><strong>Absent</strong></p>
                    </div>
                  </div>
                  <div class="number-badge absent-count" style="width: 35px; height: 35px; background-color: #00bfff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                          {{ $absent_today_count ?? 0 }}
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3 mb-3">
              <div class="card" style="border: 2px solid rgba(0, 191, 255, 0.67); border-radius: 8px; height: 110px;">
                <div class="card-body d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    <div class="icon-container me-3" style="position: relative; width: 60px; height: 40px;">
                      <i class="fas fa-clock" style="font-size: 24px; color: #ff4444; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                    </div>
                    <div class="text-content">
                      <p class="mb-1" style="font-size: 14px; color: #000; margin: 0;"><strong>Late</strong></p>
                    </div>
                  </div>
                  <div class="number-badge late-count" style="width: 35px; height: 35px; background-color: #00bfff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                    {{ $late_comers_count ?? 0 }}
                  </div>
                </div>
              </div>  
            </div>
          </div>
          <br>
          <div class="card shadow-sm mb-5">
            <div class="card-body">
                <div class="col-md-6 mb-9">
                    <div class="d-flex align-items-center">
                        <label for="locationFilter" class="form-label mb-0 me-3 flex-shrink-0" style="min-width: 150px;">
                          <strong>Select Location:</strong>
                        </label>
                        <select class="form-control" id="locationFilter" name="location">
                          <option value="">All Locations</option>
                          @foreach($locations as $location)
                            <option value="{{ $location }}">{{ $location }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                <br><br>
                <div class="row justify-content-center">
                  <div class="col-md-4 mb-4">
                      <h5 class="text-start mb-2"><strong>Absentees - Last 7 Days</strong></h5>
                      <div class="d-flex justify-content-center">
                          <div class="chart-wrapper">
                              <canvas class="sideCharts" id="absentPieChart"></canvas>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 mb-4">
                      <h5 class="text-start mb-2"><strong>Absentees - by Month</strong></h5>
                      <div class="d-flex justify-content-center">
                          <div class="chart-wrapper loading">
                              <canvas class="centerChart" id="absentMonthlyPieChart"></canvas>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 mb-4">
                      <h5 class="text-start mb-2"><strong>Late Concerns - Last 7 Days</strong></h5>
                      <div class="d-flex justify-content-center">
                          <div class="chart-wrapper">
                              <canvas class="sideCharts" id="latePieChart"></canvas>
                          </div>
                      </div>
                  </div>
                </div>
            </div>
          </div>
          @endif
          <div class="row">
              <div class="col-md-4 transparent">
                <div class="row">
                    <div class="col-md-12 transparent">
                        <!-- <div class="card">
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
                        </div> -->
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
                @if (auth()->user()->role != 'Admin')
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-body">
                        <p class="card-title">
                          Holidays:&nbsp;<i style="font-weight: normal"><small>{{ date('M 01') }} - {{ date('M t') }}</small></i>
                        </p>
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
                                <td>{{ $holiday->holiday_name }}</td>
                                <td>{{ $holiday->location }}</td>
                                <td class="font-weight-medium">
                                  <div class="badge badge-success">{{ date('M d', strtotime($holiday->holiday_date)) }}</div>
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                     <br>
                  </div>
                </div>
                @endif
              </div>
           

              <div class="col-md-5">
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
                              <p class="mb-0"><small>{{$emp->company->company_code}}</small> - <small>{{ optional($employee->department)->name ?? 'N/A' }}</small></p>
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
                          
                              <p class="mb-0"><small>{{$employee->position}}</small> - <small>{{ optional($employee->department)->name ?? 'N/A' }}</small></p>
                             
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

      document.addEventListener('DOMContentLoaded', function () {
        const labels = @json(collect($latePerDay)->pluck('date'));
        const data = @json(collect($latePerDay)->pluck('late_minutes'));

        const ctx = document.getElementById('userLateChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Late Minutes',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            boxWidth: 12
                        }
                    }
                }
            }
        });
    });


    let absentPieChart;
    let absentMonthlyPieChart;
    let latePieChart;

    document.addEventListener('DOMContentLoaded', function () {
        initEmptyCharts();
        fetchAllCharts(); 

        const locationFilter = document.getElementById('locationFilter');
        locationFilter.addEventListener('change', function () {
            fetchAllCharts(this.value);
        });
    });

    function initEmptyCharts() {
        const dummyData = {
            labels: ['Loading'],
            datasets: [{
                data: [100],
                backgroundColor: ['#e0e0e0'],
                borderWidth: 1
            }]
        };

        const baseOptions = {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '50%',
            plugins: {
                legend: {
                    position: 'bottom',
                    display: true
                },
                tooltip: { enabled: false }
            }
        };

        absentPieChart = new Chart(document.getElementById('absentPieChart').getContext('2d'), {
            type: 'doughnut',
            data: dummyData,
            options: baseOptions
        });

        absentMonthlyPieChart = new Chart(document.getElementById('absentMonthlyPieChart').getContext('2d'), {
            type: 'doughnut',
            data: dummyData,
            options: baseOptions
        });

        latePieChart = new Chart(document.getElementById('latePieChart').getContext('2d'), {
            type: 'doughnut',
            data: dummyData,
            options: baseOptions
        });
    }

    async function fetchAllCharts(location = '') {
        await Promise.all([
            fetchAbsentChart(location),
            fetchAbsentMonthlyChart(location),
            fetchLateChart(location)
        ]);
    }

    async function fetchAbsentChart(location = '') {
        const response = await fetch(`{{ url('/dashboard/absentees-pie') }}?location=${location}`);
        const data = await response.json();

        absentPieChart.data = {
            labels: data.labels,
            datasets: [{
                label: 'Absentees %',
                data: data.percentages,
                backgroundColor: [
                    '#a3f7f7', '#ff0000', '#e0e0e0',
                    '#add8e6', '#87cefa', '#0097a7', '#00796b'
                ],
                borderWidth: 1
            }]
        };

        absentPieChart.options.plugins.tooltip.enabled = true;
        absentPieChart.options.plugins.legend.display = true;
        absentPieChart.options.plugins.legend.position = 'bottom';
        absentPieChart.update();
    }

    async function fetchAbsentMonthlyChart(location = '') {
        const response = await fetch(`{{ url('/dashboard/absentees-monthly-pie') }}?location=${location}`);
        const data = await response.json();
        
        absentMonthlyPieChart.data = {
            labels: data.labels,
            datasets: [{
                label: 'Monthly Absentees %',
                data: data.percentages,
                backgroundColor: ['#a3f7f7', '#0097a7', '#87cefa'],
                borderWidth: 1
            }]
        };

        absentMonthlyPieChart.options.plugins.tooltip.enabled = true;
        absentMonthlyPieChart.options.plugins.legend.display = true;
        absentMonthlyPieChart.options.plugins.legend.position = 'bottom';
        absentMonthlyPieChart.update();
    }

    async function fetchLateChart(location = '') {
        const response = await fetch(`{{ url('/dashboard/late-pie') }}?location=${location}`);
        const data = await response.json();

        latePieChart.data = {
            labels: data.labels,
            datasets: [{
                label: 'Late Employees',
                data: data.counts,
                backgroundColor: [
                    '#a3f7f7', '#ff0000', '#e0e0e0',
                    '#add8e6', '#87cefa', '#0097a7', '#00796b'
                ],
                borderWidth: 1
            }]
        };

        latePieChart.options.plugins.tooltip.enabled = true;
        latePieChart.options.plugins.legend.display = true;
        latePieChart.options.plugins.legend.position = 'bottom';
        latePieChart.update();
    }
    </script>


    <script>
    document.getElementById('locationFilter').addEventListener('change', function () {
    const selectedLocation = this.value;

        fetch(`{{ url('/dashboard/filter-by-location') }}?location=${encodeURIComponent(selectedLocation)}`)
            .then(response => response.json())
            .then(data => {
                document.querySelector('.employees-count').innerText = data.total_employees;
                document.querySelector('.present-count').innerText = data.present_today_count;
                document.querySelector('.absent-count').innerText = data.absent_today_count;
                document.querySelector('.late-count').innerText = data.late_comers_count;
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    });
    </script>
    <script>

    document.addEventListener('DOMContentLoaded', function () {
      document.querySelector('.show-used-leave-days').addEventListener('click', function () {
        Swal.fire({
          title: 'Used Leave Details',
          html: `
            <div style="font-size: 13px; max-height: 300px; overflow-y: auto;">
              <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                  <tr>
                    <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2;">Number of Leaves</th>
                    <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2;">Date From</th>
                    <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2;">Date To</th>
                    <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2;">Reason</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($usedLeaves as $index => $leave)
                    <tr>
                      <td style="border: 1px solid #ccc; padding: 6px;">{{ $index + 1 }}</td>
                      <td style="border: 1px solid #ccc; padding: 6px;">{{ \Carbon\Carbon::parse($leave->date_from)->format('M d, Y') }}</td>
                      <td style="border: 1px solid #ccc; padding: 6px;">{{ \Carbon\Carbon::parse($leave->date_to)->format('M d, Y') }}</td>
                      <td style="border: 1px solid #ccc; padding: 6px;">{{ $leave->reason ?? 'No reason provided' }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" style="padding: 6px; text-align: center;">No used leaves</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          `,
          icon: 'info',
          confirmButtonText: 'Close',
          customClass: {
            icon: 'custom-swal-icon-spacing'
          }
        });
      });
    });


    const lateRecords = @json($lateRecords);

    document.addEventListener('DOMContentLoaded', function () {
      document.querySelector('.show-late-records').addEventListener('click', function () {
        let htmlContent = `<div style="font-size: 13px;">
          <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
              <tr>
                <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2;">Number of Late</th>
                <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2;">Date</th>
                <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2;">Time In</th>
                <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2;">Late</th>
              </tr>
            </thead>
            <tbody>`;

        if (lateRecords.length > 0) {
          lateRecords.forEach((late, index) => {
            htmlContent += `
              <tr>
                <td style="border: 1px solid #ccc; padding: 6px;">${index + 1}</td>
                <td style="border: 1px solid #ccc; padding: 6px;">${late.date}</td>
                <td style="border: 1px solid #ccc; padding: 6px;">${late.time}</td>
                <td style="border: 1px solid #ccc; padding: 6px;">${late.late_minutes} min${late.late_minutes != 1 ? 's' : ''}</td>
              </tr>`;
          });
        } else {
          htmlContent += `
            <tr>
              <td colspan="4" style="padding: 6px; text-align: center;">No late records this month.</td>
            </tr>`;
        }

        htmlContent += `</tbody></table></div>`;

        Swal.fire({
          title: 'Late Records This Month',
          html: htmlContent,
          icon: 'info',
          confirmButtonText: 'Close',
          customClass: {
                icon: 'custom-swal-icon-spacing'
              }
        });
      });
    });



     document.addEventListener('DOMContentLoaded', function () {
          document.querySelector('.show-absent-dates').addEventListener('click', function () {
            Swal.fire({
              title: 'Absent Dates',
              html: `
                <div style="font-size: 13px;">
                  <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                      <tr>
                        <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2;">Number of Absent</th>
                        <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2;">Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($absentDates as $index => $date)
                        <tr>
                          <td style="border: 1px solid #ccc; padding: 6px;">{{ $index + 1 }}</td>
                          <td style="border: 1px solid #ccc; padding: 6px;">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="2" style="padding: 6px; text-align: center;">No absences</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              `,
              icon: 'info',
              confirmButtonText: 'Close',
              customClass: {
                icon: 'custom-swal-icon-spacing'
              }
            });
          });
        });




      

    </script>
    
@foreach ($probationary_employee as $prob_emp)
@include('dashboards_modal.edit_prob')
@endforeach

@endsection
