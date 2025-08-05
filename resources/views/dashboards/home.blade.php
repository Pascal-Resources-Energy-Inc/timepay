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
   max-width: 100%;
   height: 350px;
}

.chart-wrapper {
    position: relative;
    width: 100%;
    margin: 0 auto;
}

.centerChart {
    width: 100% !important;
    height: 332px !important;
}

.col-md-2-25 {
  flex: 0 0 auto;
  width: calc(19.43% - 12px);
}

.row.g-3 > [class*="col-"] {
  padding-left: 6px;
  padding-right: 6px;
}

.row.g-3 {
  margin-left: -6px;
  margin-right: -6px;
}

.grid-container {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
  gap: 15px;
  margin-bottom: 20px;
}

.grid-container .grid-item:first-child {
  grid-column: 1 / 2;
}

.grid-container .grid-item:nth-child(2) {
  grid-column: 2 / 3;
}

.grid-container .grid-item:nth-child(3) {
  grid-column: 3 / 4;
}

.grid-container .grid-item:nth-child(4) {
  grid-column: 4 / 5;
}

.grid-container .grid-item:nth-child(5) {
  grid-column: 5 / 6;
}

/* Alternative approach using flexbox */
.balanced-row {
  display: flex;
  flex-wrap: wrap;
  margin-right: -15px;
  margin-left: -15px;
}

.balanced-row .col-md-3 {
  flex: 0 0 25%;
  max-width: 25%;
  padding-right: 15px;
  padding-left: 15px;
}

.balanced-row .flex-fill {
  flex: 1;
  padding-right: 15px;
  padding-left: 15px;
}

/* Media queries for responsive design */
@media (max-width: 768px) {
  .col-md-2-25 {
    width: 50%;
  }
}

@media (max-width: 576px) {
  .col-md-2-25 {
    width: 100%;
  }
}

/* Employee Modal Styles - Updated to match dashboard color scheme */
.employee-modal .modal-body {
    max-height: 60vh;
    overflow-y: auto;
    padding: 0;
}

.employee-list-item {
    border-bottom: 1px solid #e3f2fd;
    padding: 12px 20px;
    transition: background-color 0.2s;
}

.employee-list-item:hover {
    background-color: #f1f8ff;
}

.employee-list-item:last-child {
    border-bottom: none;
}

.employee-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 4px;
}

.employee-location {
    color: #6c757d;
    font-size: 0.9em;
}

.employee-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #76c7e7ff 0%, #4FC3F7 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(135, 206, 235, 0.4);
}

/* Modal header with vibrant sky blue colors */
.modal-header {
    background: linear-gradient(135deg, #54abe6ff 0%, #3498DB 100%);
    color: white;
    border-bottom: none;
    
}

/* Custom close button styling */
.modal-header .btn-close {
    filter: none;
    background: none !important;
    border: none;
    font-size: 24px;
    font-weight: bold;
    color: black !important;
    opacity: 1;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0;
    box-shadow: none;
}

.modal-header .btn-close:hover {
    color: #333 !important;
    background: none !important;
    transform: scale(1.1);
    transition: all 0.2s ease;
}

.modal-header .btn-close:focus {
    box-shadow: none !important;
    outline: none !important;
}

.modal-header .btn-close::before {
    content: "×";
    font-size: 28px;
    line-height: 1;
}

.employee-count-badge {
    background: rgba(255, 255, 255, 0.25);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.9em;
    margin-left: 10px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.no-employees {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.no-employees i {
    color: #87CEEB;
}

.loading-spinner {
    text-align: center;
    padding: 40px 20px;
}

.loading-spinner .spinner-border {
    color: #87CEEB !important;
}

.search-box {
    padding: 15px 20px;
    background: linear-gradient(135deg, #f8fbff 0%, #e3f2fd 100%);
    border-bottom: 1px solid #dee2e6;
}

.search-input {
    border: 1px solid #bbdefb;
    background: white;
    border-radius: 25px;
    padding: 10px 40px 10px 15px;
    width: 100%;
    box-shadow: 0 2px 4px rgba(135, 206, 235, 0.15);
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #87CEEB;
    box-shadow: 0 2px 8px rgba(135, 206, 235, 0.3);
}

.search-icon {
    position: absolute;
    right: 35px;
    top: 50%;
    transform: translateY(-50%);
    color: #87CEEB;
}

/* Modal content styling */
.modal-content {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(55, 87, 100, 0.74);
}

/* Error state styling */
.text-danger {
    color: #dc3545 !important;
}

.btn-primary {
    background: linear-gradient(135deg, #87CEEB 0%, #4FC3F7 100%);
    border-color: #87CEEB;
    border-radius: 20px;
    padding: 6px 16px;
    font-size: 0.875rem;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5dade2 0%, #3498db 100%);
    border-color: #5dade2;
    transform: translateY(-1px);
}

/* Employee number styling */
.employee-list-item .text-end small {
    color: #87CEEB;
    font-weight: 500;
}

/* Scrollbar styling for modal body */
.employee-modal .modal-body::-webkit-scrollbar {
    width: 6px;
}

.employee-modal .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.employee-modal .modal-body::-webkit-scrollbar-thumb {
    background: #000000ff;
    border-radius: 3px;
}

.employee-modal .modal-body::-webkit-scrollbar-thumb:hover {
    background: #5dade2;
}

</style>
@endsection

@section('content')
@if($attendance_now != null)
@include('employees.timeout')
@else
@include('employees.timein')
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
        
        @if (auth()->user()->role != 'Admin')
        <div class="row">
          <div class="col-md-3 mb-4 transparent">
              <div class="card">
                <div class="card-body">
                  <h3 class="card-title">{{date('M d, Y')}} 
                    @php
                        // Check if user has an approved travel order for today
                        $today = date('Y-m-d');
                        $user_travel_orders_today = \App\EmployeeTo::where('user_id', auth()->user()->id)
                          ->whereIn('status', ['Approved', 'Partially Approved'])
                          ->where(function($query) use ($today) {
                            $query->whereDate('date_from', '<=', $today)
                                  ->whereDate('date_to', '>=', $today);
                          })
                          ->exists();
                      @endphp

                      @if($user_travel_orders_today)
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
        @endif
        
        @if (auth()->user()->role == 'Admin')
         <div class="row g-3">
            <div class="col-md-3 mb-2 transparent">
              <div class="card">
                <div class="card-body">
                  <h3 class="card-title">{{date('M d, Y')}} 
                    @php
                        // Check if user has an approved travel order for today
                        $today = date('Y-m-d');
                        $user_travel_orders_today = \App\EmployeeTo::where('user_id', auth()->user()->id)
                          ->whereIn('status', ['Approved', 'Partially Approved'])
                          ->where(function($query) use ($today) {
                            $query->whereDate('date_from', '<=', $today)
                                  ->whereDate('date_to', '>=', $today);
                          })
                          ->exists();
                      @endphp

                    @if($user_travel_orders_today)
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
            <div class="col-md-2-25 mb-2">
              <div class="card employees-card" style="border: 2px solid rgba(0, 191, 255, 0.67); border-radius: 8px; height: 110px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#employeesModal">
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
            <div class="col-md-2-25 mb-2">
              <div class="card present-card" style="border: 2px solid rgba(0, 191, 255, 0.67); border-radius: 8px; height: 110px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#presentEmployeesModal">
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
            
            <div class="col-md-2-25 mb-2">
              <div class="card absent-card" style="border: 2px solid rgba(0, 191, 255, 0.67); border-radius: 8px; height: 110px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#absentEmployeesModal">
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

            <div class="col-md-2-25 mb-2">
              <div class="card late-card" style="border: 2px solid rgba(0, 191, 255, 0.67); border-radius: 8px; height: 110px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#lateEmployeesModal">
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
          <div class="card shadow-sm mb-3">
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

          <!-- Employee's list Modal -->
          <div class="modal fade" id="employeesModal" tabindex="-1" aria-labelledby="employeesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg employee-modal">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="employeesModalLabel">
                    <i class="fas fa-users me-2"></i>
                    Employees List
                    <span class="employee-count-badge" id="modalEmployeeCount">{{ $total_employees }}</span>
                  </h5>
                 
                  <button type="button" class="btn-close btn-danger" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                
                <div class="search-box">
                  <div class="position-relative">
                    <input type="text" class="search-input" id="employeeSearch" placeholder="Search employees...">
                    <i class="fas fa-search search-icon"></i>
                  </div>
                </div>
                
                <div class="modal-body" id="employeesList">
                  <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden"></span>
                    </div>
                    <p class="mt-2">Loading employees...</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Present Employees Modal -->
          <div class="modal fade" id="presentEmployeesModal" tabindex="-1" aria-labelledby="presentEmployeesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg employee-modal">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="presentEmployeesModalLabel">
                    <i class="fas fa-user-check me-2"></i>
                    Present Employees Today
                    <span class="employee-count-badge" id="modalPresentCount">{{ $present_today_count ?? 0 }}</span>
                  </h5>
                  <button type="button" class="btn-close btn-danger" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                
                <div class="search-box">
                  <div class="position-relative">
                    <input type="text" class="search-input" id="presentEmployeeSearch" placeholder="Search present employees...">
                    <i class="fas fa-search search-icon"></i>
                  </div>
                </div>
                
                <div class="modal-body" id="presentEmployeesList">
                  <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden"></span>
                    </div>
                    <p class="mt-2">Loading present employees...</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Absent Employees Modal -->
          <div class="modal fade" id="absentEmployeesModal" tabindex="-1" aria-labelledby="absentEmployeesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg employee-modal">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="absentEmployeesModalLabel">
                    <i class="fas fa-user-times me-2"></i>
                    Absent Employees Today
                    <span class="employee-count-badge" id="modalAbsentCount">{{ $absent_today_count ?? 0 }}</span>
                  </h5>
                  <button type="button" class="btn-close btn-danger" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                
                <div class="search-box">
                  <div class="position-relative">
                    <input type="text" class="search-input" id="absentEmployeeSearch" placeholder="Search absent employees...">
                    <i class="fas fa-search search-icon"></i>
                  </div>
                </div>
                
                <div class="modal-body" id="absentEmployeesList">
                  <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden"></span>
                    </div>
                    <p class="mt-2">Loading absent employees...</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Late Employees Modal -->
          <div class="modal fade" id="lateEmployeesModal" tabindex="-1" aria-labelledby="lateEmployeesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg employee-modal">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="lateEmployeesModalLabel">
                    <i class="fas fa-clock me-2"></i>
                    Late Employees Today
                    <span class="employee-count-badge" id="modalLateCount">{{ $late_comers_count ?? 0 }}</span>
                  </h5>
                  <button type="button" class="btn-close btn-danger" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                
                <div class="search-box">
                  <div class="position-relative">
                    <input type="text" class="search-input" id="lateEmployeeSearch" placeholder="Search late employees...">
                    <i class="fas fa-search search-icon"></i>
                  </div>
                </div>
                
                <div class="modal-body" id="lateEmployeesList">
                  <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden"></span>
                    </div>
                    <p class="mt-2">Loading late employees...</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

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
                    <div class="card mt-2">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>



<script>

     document.addEventListener('DOMContentLoaded', () => {
        const chartData = @json($latePerDay);
        const labels = [];
        const lateMinutes = [];
        const absenceValues = [];
        const absenceColors = [];

        let maxLate = 0;

        for (const item of chartData) {
          labels.push(item.date);

          // Late minutes only if not absent or no-attendance
          const late = item.status === 'Present' ? item.late_minutes : 0;
          lateMinutes.push(late);
          if (late > maxLate) maxLate = late;

          // Absence visual bar
          if (item.status === 'Absent') {
            absenceValues.push(1); // scaled later
            absenceColors.push('rgba(255, 99, 132, 0.6)'); // red
          } else if (item.status === 'No Attendance') {
            absenceValues.push(1); // slightly lower bar
            absenceColors.push('rgba(255, 185, 86, 0.62)'); // yellow
          } else {
            absenceValues.push(0);
            absenceColors.push('rgba(0,0,0,0)');
          }
        }

        const chartMaxValue = Math.max(10, maxLate + 2);
        const scaledAbsences = absenceValues.map(v => v * chartMaxValue);

        const ctx = document.getElementById('userLateChart').getContext('2d');

        Chart.register(ChartDataLabels);

        new Chart(ctx, {
          type: 'bar',
          data: {
            labels,
            datasets: [
              {
                label: 'Late Minutes',
                data: lateMinutes,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                yAxisID: 'y',
                barThickness: 50,
                grouped: false
              },
              {
                label: 'Attendance Status',
                data: scaledAbsences,
                backgroundColor: absenceColors,
                borderColor: absenceColors.map(c => c.replace(/0\.6/, '1')),
                borderWidth: 1,
                yAxisID: 'y',
                barThickness: 50,
                grouped: false
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
              duration: 200,
              easing: 'easeOutQuad'
            },
            scales: {
              x: {
                title: {
                  display: true,
                  text: 'Days of Month',
                  color: '#333',
                  font: { size: 14, weight: 'bold' }
                }
              },
              y: {
                beginAtZero: true,
                max: chartMaxValue,
                ticks: {
                  stepSize: 1,
                  callback: v => (Number.isInteger(v) ? v : null)
                },
                title: {
                  display: true,
                  text: 'Minutes / Days',
                  color: '#333',
                  font: { size: 14, weight: 'bold' }
                }
              }
            },
            plugins: {
              legend: {
                display: true,
                position: 'top',
                labels: { boxWidth: 12 }
              },
              tooltip: {
                callbacks: {
                  label(ctx) {
                    const item = chartData[ctx.dataIndex];
                    if (ctx.datasetIndex === 0) {
                      return item.status === 'Present' && item.late_minutes > 0
                        ? `Late: ${item.late_minutes} minutes` : '';
                    }
                    if (ctx.datasetIndex === 1) {
                      return item.status === 'Absent'
                        ? 'Status: Absent'
                        : item.status === 'No Attendance'
                          ? 'Status: No attendance'
                          : '';
                    }
                    return '';
                  },
                  title(ctx) {
                    const item = chartData[ctx[0].dataIndex];
                    return `${item.date} - Expected: ${item.expected_time}`;
                  }
                }
              },
              datalabels: {
                color: 'white',
                anchor: 'center',
                align: 'center',
                font: { weight: 'bold', size: 14 },
                rotation: 270,
                formatter(value, ctx) {
                  const item = chartData[ctx.dataIndex];
                  if (ctx.datasetIndex === 0)
                    return item.status === 'Present' && value > 0 ? `${value} minutes` : '';
                  if (ctx.datasetIndex === 1) {
                    if (item.status === 'Absent') return 'Absent';
                    if (item.status === 'No Attendance') return 'No Attendance';
                  }
                  return '';
                }
              }
            }
          },
          plugins: [ChartDataLabels]
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
                label: 'Absentees Count', // Changed label to reflect counts
                data: data.counts, // Changed from data.percentages to data.counts
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
        let currentLocation = '';
        let allEmployees = [];

            document.getElementById('locationFilter').addEventListener('change', function () {
                const selectedLocation = this.value;
                currentLocation = selectedLocation;

                fetch(`{{ url('/dashboard/filter-by-location') }}?location=${encodeURIComponent(selectedLocation)}`)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('.employees-count').innerText = data.total_employees;
                        document.querySelector('.present-count').innerText = data.present_today_count;
                        document.querySelector('.absent-count').innerText = data.absent_today_count;
                        document.querySelector('.late-count').innerText = data.late_comers_count;
                        
                        document.getElementById('modalEmployeeCount').innerText = data.total_employees;
                        
                        const modal = document.getElementById('employeesModal');
                        if (modal.classList.contains('show')) {
                            loadEmployees();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            });

            function loadEmployees() {
                const employeesList = document.getElementById('employeesList');
                employeesList.innerHTML = `
                    <div class="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                        <p class="mt-2">Loading employees...</p>
                    </div>
                `;

                let url = '{{ url("/dashboard/get-employees") }}';
                if (currentLocation) {
                    url += `?location=${encodeURIComponent(currentLocation)}`;
                }

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`HTTP ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success === false) {
                        throw new Error(data.error || 'Unknown server error');
                    }
                    
                    allEmployees = data.employees || [];
                    displayEmployees(allEmployees);
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    employeesList.innerHTML = `
                        <div class="no-employees">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5>Error Loading Employees</h5>
                            <p class="text-danger">${error.message}</p>
                            <button class="btn btn-primary btn-sm" onclick="loadEmployees()">
                                <i class="fas fa-redo"></i> Try Again
                            </button>
                        </div>
                    `;
                });
            }

            function displayEmployees(employees) {
                const employeesList = document.getElementById('employeesList');
                
                if (!employees || employees.length === 0) {
                    employeesList.innerHTML = `
                        <div class="no-employees">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5>No Employees Found</h5>
                            <p>No employees match the current filter criteria.</p>
                        </div>
                    `;
                    return;
                }

                let html = '';
                employees.forEach(employee => {
                    const initials = getInitials(employee.first_name, employee.last_name);
                    const middleName = employee.middle_name ? employee.middle_name + ' ' : '';
                    const location = employee.location || 'No location specified';
                    const employeeNumber = employee.employee_number || 'N/A';
                    
                    html += `
                        <div class="employee-list-item">
                            <div class="d-flex align-items-center">
                                <div class="employee-avatar me-3">
                                    ${initials}
                                </div>
                                &nbsp;&nbsp;
                                <div class="flex-grow-1">
                                    <div class="employee-name">
                                        ${employee.first_name} ${middleName}${employee.last_name}
                                    </div>
                                    <div class="employee-location">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        ${location}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">${employeeNumber}</small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                employeesList.innerHTML = html;
            }

            function getInitials(firstName, lastName) {
                const first = firstName ? firstName.charAt(0).toUpperCase() : '';
                const last = lastName ? lastName.charAt(0).toUpperCase() : '';
                return first + last || '??';
            }

            document.getElementById('employeeSearch').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                if (searchTerm === '') {
                    displayEmployees(allEmployees);
                    return;
                }
                
                const filteredEmployees = allEmployees.filter(employee => {
                    const fullName = `${employee.first_name} ${employee.middle_name || ''} ${employee.last_name}`.toLowerCase();
                    const location = (employee.location || '').toLowerCase();
                    const employeeNumber = (employee.employee_number || '').toLowerCase();
                    
                    return fullName.includes(searchTerm) || 
                          location.includes(searchTerm) || 
                          employeeNumber.includes(searchTerm);
                });
                
                displayEmployees(filteredEmployees);
            });

            document.getElementById('employeesModal').addEventListener('shown.bs.modal', function () {
                loadEmployees();
                document.getElementById('employeeSearch').value = '';
            });

            document.getElementById('employeesModal').addEventListener('hidden.bs.modal', function () {
                document.getElementById('employeeSearch').value = '';
                allEmployees = [];
            });
    </script>

    <script>
      let currentPresentLocation = '';
      let allPresentEmployees = [];

      function loadPresentEmployees() {
          const presentEmployeesList = document.getElementById('presentEmployeesList');
          presentEmployeesList.innerHTML = `
              <div class="loading-spinner">
                  <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden"></span>
                  </div>
                  <p class="mt-2">Loading present employees...</p>
              </div>
          `;

          let url = '{{ url("/dashboard/get-present-employees") }}';
          if (currentLocation) {
              url += `?location=${encodeURIComponent(currentLocation)}`;
          }

          fetch(url, {
              method: 'GET',
              headers: {
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
              }
          })
          .then(response => {
              if (!response.ok) {
                  return response.text().then(text => {
                      throw new Error(`HTTP ${response.status}: ${text}`);
                  });
              }
              return response.json();
          })
          .then(data => {
              if (data.success === false) {
                  throw new Error(data.error || 'Unknown server error');
              }
              
              allPresentEmployees = data.employees || [];
              displayPresentEmployees(allPresentEmployees);
          })
          .catch(error => {
              presentEmployeesList.innerHTML = `
                  <div class="no-employees">
                      <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                      <h5>Error Loading Present Employees</h5>
                      <p class="text-danger">${error.message}</p>
                      <br><br>
                      <button class="btn btn-primary btn-sm" onclick="loadPresentEmployees()">
                          <i class="fas fa-redo"></i> Try Again
                      </button>
                  </div>
              `;
          });
      }

      function displayPresentEmployees(employees) {
          const presentEmployeesList = document.getElementById('presentEmployeesList');
          
          if (!employees || employees.length === 0) {
              presentEmployeesList.innerHTML = `
                  <div class="no-employees">
                      <i class="fas fa-user-check fa-3x text-muted mb-3"></i>
                      <h5>No Present Employees Found</h5>
                      <p>No employees are present today</p>
                  </div>
              `;
              return;
          }

          let html = '';
          employees.forEach(employee => {
              const initials = getInitials(employee.first_name, employee.last_name);
              const middleName = employee.middle_name ? employee.middle_name + ' ' : '';
              const location = employee.location || 'No location specified';
              const employeeNumber = employee.employee_number || 'N/A';
              const timeIn = employee.time_in || 'N/A';
              
              html += `
                  <div class="employee-list-item">
                      <div class="d-flex align-items-center">
                          <div class="employee-avatar me-3">
                              ${initials}
                          </div>
                          &nbsp;&nbsp;
                          <div class="flex-grow-1">
                              <div class="employee-name">
                                  ${employee.first_name} ${middleName}${employee.last_name}
                              </div>
                              <div>
                                <i class="fas fa-id-badge me-1 text-muted""></i>
                                <small class="text-muted">${employeeNumber}</small>
                              </div>
                              <div class="employee-location">
                                  <i class="fas fa-map-marker-alt me-1"></i>
                                  ${location}
                              </div>
                          </div>
                          <div class="text-end">
                              <div class="time-in-badge" style="background: #28a745; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8em; margin-bottom: 4px;">
                                  <i class="fas fa-clock me-1"></i>
                                  ${timeIn}
                              </div>
                          </div>
                      </div>
                  </div>
              `;
          });
          
          presentEmployeesList.innerHTML = html;
      }

      document.getElementById('presentEmployeeSearch').addEventListener('input', function() {
          const searchTerm = this.value.toLowerCase().trim();
          
          if (searchTerm === '') {
              displayPresentEmployees(allPresentEmployees);
              return;
          }
          
          const filteredEmployees = allPresentEmployees.filter(employee => {
              const fullName = `${employee.first_name} ${employee.middle_name || ''} ${employee.last_name}`.toLowerCase();
              const location = (employee.location || '').toLowerCase();
              const employeeNumber = (employee.employee_number || '').toLowerCase();
              const timeIn = (employee.time_in || '').toLowerCase();
              
              return fullName.includes(searchTerm) || 
                    location.includes(searchTerm) || 
                    employeeNumber.includes(searchTerm) ||
                    timeIn.includes(searchTerm);
          });
          
          displayPresentEmployees(filteredEmployees);
      });

      document.getElementById('presentEmployeesModal').addEventListener('shown.bs.modal', function () {
          loadPresentEmployees();
          document.getElementById('presentEmployeeSearch').value = '';
      });

      document.getElementById('presentEmployeesModal').addEventListener('hidden.bs.modal', function () {
          document.getElementById('presentEmployeeSearch').value = '';
          allPresentEmployees = [];
      });

      // Update the location filter to also refresh present employees modal if open
      document.getElementById('locationFilter').addEventListener('change', function () {
          const selectedLocation = this.value;
          currentLocation = selectedLocation;
          currentPresentLocation = selectedLocation;

          fetch(`{{ url('/dashboard/filter-by-location') }}?location=${encodeURIComponent(selectedLocation)}`)
              .then(response => response.json())
              .then(data => {
                  document.querySelector('.employees-count').innerText = data.total_employees;
                  document.querySelector('.present-count').innerText = data.present_today_count;
                  document.querySelector('.absent-count').innerText = data.absent_today_count;
                  document.querySelector('.late-count').innerText = data.late_comers_count;
                  
                  document.getElementById('modalEmployeeCount').innerText = data.total_employees;
                  document.getElementById('modalPresentCount').innerText = data.present_today_count;
                  
                  const presentModal = document.getElementById('presentEmployeesModal');
                  if (presentModal.classList.contains('show')) {
                      loadPresentEmployees();
                  }
                  
                  const modal = document.getElementById('employeesModal');
                  if (modal.classList.contains('show')) {
                      loadEmployees();
                  }
              })
              .catch(error => {
                  console.error('Error fetching data:', error);
              });
      });
      </script>

    <script>
    // === ABSENT EMPLOYEES MODAL FUNCTIONALITY ===
    let currentAbsentLocation = '';
    let allAbsentEmployees = [];

          // Load absent employees function
          function loadAbsentEmployees() {
              const absentEmployeesList = document.getElementById('absentEmployeesList');
              absentEmployeesList.innerHTML = `
                  <div class="loading-spinner">
                      <div class="spinner-border text-primary" role="status">
                          <span class="visually-hidden"></span>
                      </div>
                      <p class="mt-2">Loading absent employees...</p>
                  </div>
              `;

              let url = '{{ url("/dashboard/get-absent-employees") }}';
              if (currentAbsentLocation) {
                  url += `?location=${encodeURIComponent(currentAbsentLocation)}`;
              }

              fetch(url, {
                  method: 'GET',
                  headers: {
                      'Accept': 'application/json',
                      'Content-Type': 'application/json',
                      'X-Requested-With': 'XMLHttpRequest',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                  }
              })
              .then(response => {
                  if (!response.ok) {
                      return response.text().then(text => {
                          throw new Error(`HTTP ${response.status}: ${text}`);
                      });
                  }
                  return response.json();
              })
              .then(data => {
                  if (data.success === false) {
                      throw new Error(data.error || 'Unknown server error');
                  }
                  
                  allAbsentEmployees = data.employees || [];
                  
                  // Check if there's a message (like before 10 AM)
                  if (data.message && allAbsentEmployees.length === 0) {
                      absentEmployeesList.innerHTML = `
                          <div class="no-employees">
                              <i class="fas fa-clock fa-3x text-info mb-3"></i>
                              <h5>Too Early</h5>
                              <p>${data.message}</p>
                          </div>
                      `;
                      return;
                  }
                  
                  displayAbsentEmployees(allAbsentEmployees);
              })
              .catch(error => {
                  absentEmployeesList.innerHTML = `
                      <div class="no-employees">
                          <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                          <h5>Error Loading Absent Employees</h5>
                          <p class="text-danger">${error.message}</p>
                          <br><br>
                          <button class="btn btn-primary btn-sm" onclick="loadAbsentEmployees()">
                              <i class="fas fa-redo"></i> Try Again
                          </button>
                      </div>
                  `;
              });
          }

          function displayAbsentEmployees(employees) {
              const absentEmployeesList = document.getElementById('absentEmployeesList');
              
              if (!employees || employees.length === 0) {
                  absentEmployeesList.innerHTML = `
                      <div class="no-employees">
                          <i class="fas fa-user-check fa-3x text-muted mb-3"></i>
                          <h5>Great! No Absent Employees</h5>
                          <p>All employees are present or on approved leave today.</p>
                      </div>
                  `;
                  return;
              }

              let html = '';
              employees.forEach(employee => {
                  const initials = getInitials(employee.first_name, employee.last_name);
                  const middleName = employee.middle_name ? employee.middle_name + ' ' : '';
                  const location = employee.location || 'No location specified';
                  const employeeNumber = employee.employee_number || 'N/A';
                  
                  html += `
                      <div class="employee-list-item">
                          <div class="d-flex align-items-center">
                              <div class="employee-avatar me-3">
                                  ${initials}
                              </div>
                              &nbsp;&nbsp;
                              <div class="flex-grow-1">
                                  <div class="employee-name">
                                      ${employee.first_name} ${middleName}${employee.last_name}
                                  </div>
                                  <div>
                                    <i class="fas fa-id-badge me-1 text-muted""></i>
                                    <small class="text-muted">${employeeNumber}</small>
                                  </div>
                                  <div class="employee-location">
                                      <i class="fas fa-map-marker-alt me-1"></i>
                                      ${location}
                                  </div>
                              </div>
                              <div class="text-end">
                                  <div class="absent-badge" style="background: #dc3545; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8em; margin-bottom: 4px;">
                                      <i class="fas fa-times me-1"></i>
                                      ABSENT
                                  </div>
                              </div>
                          </div>
                      </div>
                  `;
              });
              
              absentEmployeesList.innerHTML = html;
          }

          document.getElementById('absentEmployeeSearch').addEventListener('input', function() {
              const searchTerm = this.value.toLowerCase().trim();
              
              if (searchTerm === '') {
                  displayAbsentEmployees(allAbsentEmployees);
                  return;
              }
              
              const filteredEmployees = allAbsentEmployees.filter(employee => {
                  const fullName = `${employee.first_name} ${employee.middle_name || ''} ${employee.last_name}`.toLowerCase();
                  const location = (employee.location || '').toLowerCase();
                  const employeeNumber = (employee.employee_number || '').toLowerCase();
                  
                  return fullName.includes(searchTerm) || 
                        location.includes(searchTerm) || 
                        employeeNumber.includes(searchTerm);
              });
              
              displayAbsentEmployees(filteredEmployees);
          });

          document.getElementById('absentEmployeesModal').addEventListener('shown.bs.modal', function () {
              loadAbsentEmployees();
              document.getElementById('absentEmployeeSearch').value = '';
          });

          document.getElementById('absentEmployeesModal').addEventListener('hidden.bs.modal', function () {
              document.getElementById('absentEmployeeSearch').value = '';
              allAbsentEmployees = [];
          });
    </script>

    <script>
      // === LATE EMPLOYEES MODAL FUNCTIONALITY ===
      let currentLateLocation = '';
      let allLateEmployees = [];

        function loadLateEmployees() {
            const lateEmployeesList = document.getElementById('lateEmployeesList');
            lateEmployeesList.innerHTML = `
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                    <p class="mt-2">Loading late employees...</p>
                </div>
            `;

            let url = '{{ url("/dashboard/get-late-employees") }}';
            if (currentLateLocation) {
                url += `?location=${encodeURIComponent(currentLateLocation)}`;
            }

            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success === false) {
                    throw new Error(data.error || 'Unknown server error');
                }
                
                allLateEmployees = data.employees || [];
                displayLateEmployees(allLateEmployees);
            })
            .catch(error => {
                lateEmployeesList.innerHTML = `
                    <div class="no-employees">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5>Error Loading Late Employees</h5>
                        <p class="text-danger">${error.message}</p>
                        <br><br>
                        <button class="btn btn-primary btn-sm" onclick="loadLateEmployees()">
                            <i class="fas fa-redo"></i> Try Again
                        </button>
                    </div>
                `;
            });
        }

        function displayLateEmployees(employees) {
            const lateEmployeesList = document.getElementById('lateEmployeesList');
            
            if (!employees || employees.length === 0) {
                lateEmployeesList.innerHTML = `
                    <div class="no-employees">
                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                        <h5>Excellent! No Late Employees</h5>
                        <p>All employees arrived on time today.</p>
                    </div>
                `;
                return;
            }

            let html = '';
            employees.forEach(employee => {
                const initials = getInitials(employee.first_name, employee.last_name);
                const middleName = employee.middle_name ? employee.middle_name + ' ' : '';
                const location = employee.location || 'No location specified';
                const employeeNumber = employee.employee_number || 'N/A';
                
                let badgeColor = '#ffc107';
                if (employee.late_minutes > 60) {
                    badgeColor = '#dc3545';
                } else if (employee.late_minutes > 30) {
                    badgeColor = '#fd7e14';
                }
                
                html += `
                    <div class="employee-list-item">
                        <div class="d-flex align-items-center">
                            <div class="employee-avatar me-3">
                                ${initials}
                            </div>
                            &nbsp;&nbsp;
                            <div class="flex-grow-1">
                                <div class="employee-name">
                                    ${employee.first_name} ${middleName}${employee.last_name}
                                </div>
                                <div>
                                    <i class="fas fa-id-badge me-1 text-muted""></i>
                                    <small class="text-muted">${employeeNumber}</small>
                                </div>
                                <div class="employee-location">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    ${location}
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="late-info mb-2">
                                    <div class="late-badge" style="background: ${badgeColor}; color: white; padding: 3px 6px; border-radius: 10px; font-size: 0.75em; margin-bottom: 2px;">
                                        <i class="fas fa-clock me-1"></i>
                                        ${employee.late_duration}
                                    </div>
                                    <div class="time-info" style="font-size: 0.7em; color: #6c757d;">
                                        Expected: ${employee.expected_time}<br>
                                        Actual: ${employee.actual_time_in}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            lateEmployeesList.innerHTML = html;
        }

        document.getElementById('lateEmployeeSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            if (searchTerm === '') {
                displayLateEmployees(allLateEmployees);
                return;
            }
            
            const filteredEmployees = allLateEmployees.filter(employee => {
                const fullName = `${employee.first_name} ${employee.middle_name || ''} ${employee.last_name}`.toLowerCase();
                const location = (employee.location || '').toLowerCase();
                const employeeNumber = (employee.employee_number || '').toLowerCase();
                const expectedTime = (employee.expected_time || '').toLowerCase();
                const actualTime = (employee.actual_time_in || '').toLowerCase();
                const lateDuration = (employee.late_duration || '').toLowerCase();
                
                return fullName.includes(searchTerm) || 
                      location.includes(searchTerm) || 
                      employeeNumber.includes(searchTerm) ||
                      expectedTime.includes(searchTerm) ||
                      actualTime.includes(searchTerm) ||
                      lateDuration.includes(searchTerm);
            });
            
            displayLateEmployees(filteredEmployees);
        });

        document.getElementById('lateEmployeesModal').addEventListener('shown.bs.modal', function () {
            loadLateEmployees();
            document.getElementById('lateEmployeeSearch').value = '';
        });

        document.getElementById('lateEmployeesModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('lateEmployeeSearch').value = '';
            allLateEmployees = [];
        });
    </script>

    <script>
        // === LOCATION FILTER (affects all four modals) ===
        document.getElementById('locationFilter').addEventListener('change', function () {
            const selectedLocation = this.value;
            
            currentLocation = selectedLocation;
            currentPresentLocation = selectedLocation;
            currentAbsentLocation = selectedLocation;
            currentLateLocation = selectedLocation;

            fetch(`{{ url('/dashboard/filter-by-location') }}?location=${encodeURIComponent(selectedLocation)}`)
                .then(response => response.json())
                .then(data => {
                 
                    document.querySelector('.employees-count').innerText = data.total_employees;
                    document.querySelector('.present-count').innerText = data.present_today_count;
                    document.querySelector('.absent-count').innerText = data.absent_today_count;
                    document.querySelector('.late-count').innerText = data.late_comers_count;
                    
                    document.getElementById('modalEmployeeCount').innerText = data.total_employees;
                    document.getElementById('modalPresentCount').innerText = data.present_today_count;
                    document.getElementById('modalAbsentCount').innerText = data.absent_today_count;
                    document.getElementById('modalLateCount').innerText = data.late_comers_count;
                    
                    const employeesModal = document.getElementById('employeesModal');
                    if (employeesModal.classList.contains('show')) {
                        loadEmployees();
                    }
                    
                    const presentModal = document.getElementById('presentEmployeesModal');
                    if (presentModal.classList.contains('show')) {
                        loadPresentEmployees();
                    }
                    
                    const absentModal = document.getElementById('absentEmployeesModal');
                    if (absentModal.classList.contains('show')) {
                        loadAbsentEmployees();
                    }
                    
                    const lateModal = document.getElementById('lateEmployeesModal');
                    if (lateModal.classList.contains('show')) {
                        loadLateEmployees();
                    }
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
