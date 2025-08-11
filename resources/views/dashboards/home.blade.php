@extends('layouts.header')
@section('css_header')
<link rel="stylesheet" href="{{asset('./body_css/vendors/fullcalendar/fullcalendar.min.css')}}">
<link rel="stylesheet" href="{{asset('./body_css/vendors/owl-carousel-2/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('./body_css/vendors/owl-carousel-2/owl.theme.default.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
@if(($user_travel_orders_today) || (auth()->user()->login))
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
        
        @if (auth()->user()->role != 'Admin')

        <div class="row">
          <div class="col-md-3 mb-4 transparent">
              <div class="card">
                <div class="card-body">
                  <h3 class="card-title">{{date('M d, Y')}} 
                   

                      @if(($user_travel_orders_today) || (auth()->user()->login == 1))
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
        <!-- Tabs Navigation -->
        <div class="admin-tabs">
            <nav class="nav nav-tabs" id="adminTabs">
                <a class="nav-link active" data-bs-toggle="tab" href="#dashboard">
                    <i class="fas fa-tachometer-alt"></i> Admin Side
                </a>
                <a class="nav-link" data-bs-toggle="tab" href="#employees">
                    <i class="fas fa-users"></i> Employee Side
                </a>
            </nav>
        </div>


        <!-- Tabs Content -->
        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="dashboard">
                <div class="admin-dashboard-overview">
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

                            @if(($user_travel_orders_today) || (auth()->user()->login == 1))
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
                          <div class="number-badge employees-count" id="employee_admin" style="width: 35px; height: 35px; background-color: #00bfff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
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
                              <div class="number-badge present-count" id='present_admin' style="width: 35px; height: 35px; background-color: #00bfff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
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
                        <div class="number-badge absent-count" id="admin_absent" style="width: 35px; height: 35px; background-color: #00bfff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                             
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
                            <p class="mb-1" style="font-size: 14px; color: #000; margin: 0;" ><strong>Late</strong></p>
                            </div>
                        </div>
                        <div class="number-badge late-count" id='late_admin' style="width: 35px; height: 35px; background-color: #00bfff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
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
                            <h5 class="text-start mb-2"><strong>Absentees - By Last Month</strong></h5>
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
               
                </div>
            </div>

            <div class="tab-pane fade" id="employees">
                <div class="employee-management">
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
                    <span class="employee-count-badge" id="modalLateCount"></span>
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

  document.addEventListener('DOMContentLoaded', function() {
    
    loadAbsentEmployees();
    loadLateEmployees();
  });
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
                tooltip: { 
                    enabled: false 
                },
                // Disable any data labels on chart segments
                datalabels: {
                    display: false
                }
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
                label: 'Absentees Count',
                data: data.counts,
                backgroundColor: [
                    '#a3f7f7', '#ff0000', '#e0e0e0',
                    '#add8e6', '#87cefa', '#0097a7', '#00796b'
                ],
                borderWidth: 1
            }]
        };

        // Update legend configuration to prevent showing numbers
        absentPieChart.options.plugins.legend.labels.generateLabels = function(chart) {
            const data = chart.data;
            if (data.labels.length && data.datasets.length) {
                return data.labels.map((label, i) => {
                    const dataset = data.datasets[0];
                    const backgroundColor = dataset.backgroundColor[i];
                    return {
                        text: label, // Only label text
                        fillStyle: backgroundColor,
                        strokeStyle: backgroundColor,
                        lineWidth: 0,
                        hidden: false,
                        index: i
                    };
                });
            }
            return [];
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
    // === ALL EMPLOYEES MODAL FUNCTIONALITY ===
    let currentEmployeesLocation = '';
    let allEmployees = [];
    let employeesDataCache = new Map();
    let isLoadingEmployees = false;
    let isEmployeesFullyLoaded = false;

    const getEmployeesCacheKey = (location) => location || 'all_locations';

    function setEmployeesLocation(location) {
        const oldLocation = currentEmployeesLocation;
        currentEmployeesLocation = location || '';
        
        if (oldLocation !== currentEmployeesLocation) {
            employeesDataCache.delete(getEmployeesCacheKey(oldLocation));
            
            const newCacheKey = getEmployeesCacheKey(currentEmployeesLocation);
            if (employeesDataCache.has(newCacheKey)) {
                const cachedData = employeesDataCache.get(newCacheKey);
                allEmployees = cachedData.employees;
                isEmployeesFullyLoaded = true;
                isLoadingEmployees = false;
                updateEmployeesUI();
            } else {
                isEmployeesFullyLoaded = false;
                loadEmployees();
            }
            
            const modal = document.getElementById('employeesModal');
            if (modal?.classList.contains('show')) {
                loadEmployees();
            }
        }
    }

    function loadEmployees() {
        if (isLoadingEmployees) return;

        const cacheKey = getEmployeesCacheKey(currentEmployeesLocation);
        const employeesList = document.getElementById('employeesList');
        
        if (employeesDataCache.has(cacheKey)) {
            const cachedData = employeesDataCache.get(cacheKey);
            allEmployees = cachedData.employees;
            isEmployeesFullyLoaded = true;
            isLoadingEmployees = false;
            updateEmployeesUI();
            
            displayEmployees(cachedData.employees);
            return;
        }
        
        isLoadingEmployees = true;
        isEmployeesFullyLoaded = false;
        updateEmployeesUI();

        let url = '{{ url("/dashboard/get-employees") }}';
        if (currentEmployeesLocation) {
            url += `?location=${encodeURIComponent(currentEmployeesLocation)}`;
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

            const employees = data.employees || [];
            const cacheData = {
                employees: employees,
                timestamp: Date.now()
            };
            
            employeesDataCache.set(cacheKey, cacheData);
            allEmployees = employees;
            isLoadingEmployees = false;
            isEmployeesFullyLoaded = true;

            updateEmployeesUI();
            displayEmployees(employees);
        })
        .catch(error => {
            isLoadingEmployees = false;
            isEmployeesFullyLoaded = true;

            employeesList.innerHTML = `
                <div class="no-employees">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>Error Loading Employees</h5>
                    <p class="text-danger">${error.message}</p>
                    <br><br>
                    <button class="btn btn-primary btn-sm" onclick="loadEmployees()">
                        <i class="fas fa-redo"></i> Try Again
                    </button>
                </div>
            `;
        });
    }

    function updateEmployeesUI() {
        const employeesList = document.getElementById('employeesList');

        const spinnerSVG = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 50 50">
                    <path fill="#fff" d="M43.935,25.145c0-10.318-8.364-18.682-18.682-18.682
                        c-10.318,0-18.682,8.364-18.682,18.682h4.068
                        c0-8.064,6.55-14.614,14.614-14.614s14.614,6.55,14.614,14.614H43.935z">
                        <animateTransform attributeType="xml" attributeName="transform" type="rotate"
                            from="0 25 25" to="360 25 25" dur="0.8s" repeatCount="indefinite"/>
                    </path>
                </svg>
        `;

        // Show spinner if loading - this will run FIRST
        if (isLoadingEmployees) {
            employeesList.innerHTML = `
                <div class="loading-spinner text-center">
                    ${spinnerSVG}
                    <p class="mt-2">Loading employees...</p>
                </div>
            `;
            document.getElementById('modalEmployeeCount').innerHTML = spinnerSVG;
            document.getElementById('employee_admin').innerHTML = spinnerSVG;
            return;
        }

        // Only update count when data is fully loaded and not loading
        if (isEmployeesFullyLoaded && !isLoadingEmployees) {
            document.getElementById('modalEmployeeCount').textContent = allEmployees.length;
            document.getElementById('employee_admin').textContent = allEmployees.length;
            displayEmployees(allEmployees);
        }
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
                            <div>
                                <i class="fas fa-id-badge me-1 text-muted"></i>
                                <small class="text-muted">${employeeNumber}</small>
                            </div>
                            <div class="employee-location">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                ${location}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="employee-status">
                                <span class="text-muted" style="font-size: 14px;">Active</span>
                            </div>
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

    // Cache management
    function clearEmployeesCache() {
        employeesDataCache.clear();
        isEmployeesFullyLoaded = false;
        allEmployees = [];
    }

    // Background count update
    async function updateEmployeesCount(location = null, showLoading = true) {
        const targetLocation = location !== null ? location : currentEmployeesLocation;
        const cacheKey = getEmployeesCacheKey(targetLocation);
        
        // Use cached data immediately if available
        if (employeesDataCache.has(cacheKey)) {
            const cachedData = employeesDataCache.get(cacheKey);
            allEmployees = cachedData.employees;
            isEmployeesFullyLoaded = true;
            isLoadingEmployees = false;
            updateEmployeesUI();
            return cachedData.employees.length;
        }
        
        // Set loading state if requested
        if (showLoading) {
            isLoadingEmployees = true;
            isEmployeesFullyLoaded = false;
            updateEmployeesUI();
        }
        
        try {
            let url = '{{ url("/dashboard/get-employees") }}';
            if (targetLocation) {
                url += `?location=${encodeURIComponent(targetLocation)}`;
            }
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (!response.ok) {
                const text = await response.text();
                throw new Error(`HTTP ${response.status}: ${text}`);
            }
            
            const data = await response.json();
            
            if (data.success !== false) {
                const employees = data.employees || [];
                const cacheData = {
                    employees: employees,
                    timestamp: Date.now()
                };
                
                employeesDataCache.set(cacheKey, cacheData);
                allEmployees = employees;
                isLoadingEmployees = false;
                isEmployeesFullyLoaded = true;
                updateEmployeesUI();
                return employees.length;
            } else {
                throw new Error(data.error || 'API returned success: false');
            }
        } catch (error) {
            console.error('Error updating employees count:', error);
            isLoadingEmployees = false;
            isEmployeesFullyLoaded = true;
            updateEmployeesUI();
            return allEmployees.length;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize with loading state
        isLoadingEmployees = true;
        isEmployeesFullyLoaded = false;
        updateEmployeesUI();
        
        // Start background loading
        updateEmployeesCount(null, true);
        
        // Search functionality
        const employeeSearchInput = document.getElementById('employeeSearch');
        if (employeeSearchInput) {
            employeeSearchInput.addEventListener('input', function() {
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
        }

        // Modal handlers
        const employeesModal = document.getElementById('employeesModal');
        if (employeesModal) {
            employeesModal.addEventListener('shown.bs.modal', function () {
                const searchInput = document.getElementById('employeeSearch');
                if (searchInput) searchInput.value = '';
                
                loadEmployees();
            });

            employeesModal.addEventListener('hidden.bs.modal', function () {
                const searchInput = document.getElementById('employeeSearch');
                if (searchInput) searchInput.value = '';
            });
        }

        // Location filter integration
        const locationFilter = document.getElementById('locationFilter');
        if (locationFilter) {
            locationFilter.addEventListener('change', function() {
                const selectedLocation = this.value;
                setEmployeesLocation(selectedLocation);
            });
        }
    });

    // Cache cleanup - runs every minute to remove stale cache entries
    setInterval(() => {
        const now = Date.now();
        const fiveMinutes = 5 * 60 * 1000;
        
        for (let [key, data] of employeesDataCache.entries()) {
            if (now - data.timestamp > fiveMinutes) {
                employeesDataCache.delete(key);
            }
        }
    }, 60000);
    </script>
   <script>
      // === PRESENT EMPLOYEES MODAL FUNCTIONALITY ===
      let currentPresentLocation = '';
      let allPresentEmployees = [];
      let presentDataCache = new Map();
      let isLoadingPresentEmployees = false;
      let isPresentEmployeesFullyLoaded = false;

      const getPresentCacheKey = (location) => location || 'all_locations';

      function setPresentEmployeesLocation(location) {
          const oldLocation = currentPresentLocation;
          currentPresentLocation = location || '';
          
          if (oldLocation !== currentPresentLocation) {
              presentDataCache.delete(getPresentCacheKey(oldLocation));
              
              // Check if we have cached data for new location
              const newCacheKey = getPresentCacheKey(currentPresentLocation);
              if (presentDataCache.has(newCacheKey)) {
                  // Use cached data immediately - data is already loaded
                  const cachedData = presentDataCache.get(newCacheKey);
                  allPresentEmployees = cachedData.employees;
                  isPresentEmployeesFullyLoaded = true;
                  isLoadingPresentEmployees = false;
                  updatePresentEmployeesUI();
              } else {
                  // Need to load data
                  isPresentEmployeesFullyLoaded = false;
                  loadPresentEmployees();
              }
              
              // Reload modal if open
              const modal = document.getElementById('presentEmployeesModal');
              if (modal?.classList.contains('show')) {
                  loadPresentEmployees();
              }
          }
      }

      function loadPresentEmployees() {
          if (isLoadingPresentEmployees) return;

          const cacheKey = getPresentCacheKey(currentPresentLocation);
          const presentEmployeesList = document.getElementById('presentEmployeesList');
          
          // Check cache first - if data exists, use it immediately
          if (presentDataCache.has(cacheKey)) {
              const cachedData = presentDataCache.get(cacheKey);
              allPresentEmployees = cachedData.employees;
              isPresentEmployeesFullyLoaded = true;
              isLoadingPresentEmployees = false;
              updatePresentEmployeesUI();
              
              // Show cached results in modal immediately
              if (cachedData.message && cachedData.employees.length === 0) {
                  presentEmployeesList.innerHTML = createPresentNoEmployeesHTML('clock', 'Too Early', cachedData.message);
              } else {
                  displayPresentEmployees(cachedData.employees);
              }
              return;
          }
          
          // Set loading state IMMEDIATELY
          isLoadingPresentEmployees = true;
          isPresentEmployeesFullyLoaded = false;
          updatePresentEmployeesUI();

          let url = '{{ url("/dashboard/get-present-employees") }}';
          if (currentPresentLocation) {
              url += `?location=${encodeURIComponent(currentPresentLocation)}`;
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

              const employees = data.employees || [];
              const cacheData = {
                  employees: employees,
                  message: data.message,
                  timestamp: Date.now()
              };
              
              // Cache the data
              presentDataCache.set(cacheKey, cacheData);
              allPresentEmployees = employees;
              isLoadingPresentEmployees = false;
              isPresentEmployeesFullyLoaded = true;

              updatePresentEmployeesUI();
              
              // Update modal display
              if (data.message && employees.length === 0) {
                  document.getElementById('presentEmployeesList').innerHTML = createPresentNoEmployeesHTML('clock', 'Too Early', data.message);
              } else {
                  displayPresentEmployees(employees);
              }
          })
          .catch(error => {
              isLoadingPresentEmployees = false;
              isPresentEmployeesFullyLoaded = true;

              document.getElementById('presentEmployeesList').innerHTML = `
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

      function updatePresentEmployeesUI() {
          const presentEmployeesList = document.getElementById('presentEmployeesList');

          const spinnerSVG = `
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 50 50">
                      <path fill="#fff" d="M43.935,25.145c0-10.318-8.364-18.682-18.682-18.682
                          c-10.318,0-18.682,8.364-18.682,18.682h4.068
                          c0-8.064,6.55-14.614,14.614-14.614s14.614,6.55,14.614,14.614H43.935z">
                          <animateTransform attributeType="xml" attributeName="transform" type="rotate"
                              from="0 25 25" to="360 25 25" dur="0.8s" repeatCount="indefinite"/>
                      </path>
                  </svg>
          `;

          // Show spinner if loading - this will run FIRST
          if (isLoadingPresentEmployees) {
              presentEmployeesList.innerHTML = `
                  <div class="loading-spinner text-center">
                      ${spinnerSVG}
                      <p class="mt-2">Loading present employees...</p>
                  </div>
              `;
              document.getElementById('modalPresentCount').innerHTML = spinnerSVG;
              document.getElementById('present_admin').innerHTML = spinnerSVG;
              return;
          }

          // Only update count when data is fully loaded and not loading
          if (isPresentEmployeesFullyLoaded && !isLoadingPresentEmployees) {
              document.getElementById('modalPresentCount').textContent = allPresentEmployees.length;
              document.getElementById('present_admin').textContent = allPresentEmployees.length;
              displayPresentEmployees(allPresentEmployees);
          }
      }

      function displayPresentEmployees(employees) {
          const presentEmployeesList = document.getElementById('presentEmployeesList');

          if (!employees || employees.length === 0) {
              presentEmployeesList.innerHTML = `
                  <div class="no-employees">
                      <i class="fas fa-user-check fa-3x text-muted mb-3"></i>
                      <h5>No Present Employees Found</h5>
                      <p>No employees are present today${currentPresentLocation ? ` in ${currentPresentLocation}` : ''}.</p>
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
                                  <i class="fas fa-id-badge me-1 text-muted"></i>
                                  <small class="text-muted">${employeeNumber}</small>
                              </div>
                              <div class="employee-location">
                                  <i class="fas fa-map-marker-alt me-1"></i>
                                  ${location}
                              </div>
                          </div>
                          <div class="text-end">
                              <div class="present-badge" style="background: #28a745; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8em; margin-bottom: 4px;">
                                  <i class="fas fa-check me-1"></i>
                                  ${timeIn}
                              </div>
                          </div>
                      </div>
                  </div>
              `;
          });

          presentEmployeesList.innerHTML = html;
      }

      function getInitials(firstName, lastName) {
          const first = firstName ? firstName.charAt(0).toUpperCase() : '';
          const last = lastName ? lastName.charAt(0).toUpperCase() : '';
          return first + last || '??';
      }

      function createPresentNoEmployeesHTML(icon, title, message) {
          return `
              <div class="no-employees text-center">
                  <i class="fas fa-${icon} fa-3x text-info mb-3"></i>
                  <h5>${title}</h5>
                  <p>${message}</p>
              </div>
          `;
      }

      // Cache management
      function clearPresentEmployeesCache() {
          presentDataCache.clear();
          isPresentEmployeesFullyLoaded = false;
          allPresentEmployees = [];
      }

      // Background count update
      async function updatePresentCount(location = null, showLoading = true) {
          const targetLocation = location !== null ? location : currentPresentLocation;
          const cacheKey = getPresentCacheKey(targetLocation);
          
          // Use cached data immediately if available
          if (presentDataCache.has(cacheKey)) {
              const cachedData = presentDataCache.get(cacheKey);
              allPresentEmployees = cachedData.employees;
              isPresentEmployeesFullyLoaded = true;
              isLoadingPresentEmployees = false;
              updatePresentEmployeesUI();
              return cachedData.employees.length;
          }
          
          // Set loading state if requested
          if (showLoading) {
              isLoadingPresentEmployees = true;
              isPresentEmployeesFullyLoaded = false;
              updatePresentEmployeesUI();
          }
          
          try {
              let url = '{{ url("/dashboard/get-present-employees") }}';
              if (targetLocation) {
                  url += `?location=${encodeURIComponent(targetLocation)}`;
              }
              
              const response = await fetch(url, {
                  method: 'GET',
                  headers: {
                      'Accept': 'application/json',
                      'Content-Type': 'application/json',
                      'X-Requested-With': 'XMLHttpRequest',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                  }
              });
              
              if (!response.ok) {
                  const text = await response.text();
                  throw new Error(`HTTP ${response.status}: ${text}`);
              }
              
              const data = await response.json();
              
              if (data.success !== false) {
                  const employees = data.employees || [];
                  const cacheData = {
                      employees: employees,
                      message: data.message,
                      timestamp: Date.now()
                  };
                  
                  presentDataCache.set(cacheKey, cacheData);
                  allPresentEmployees = employees;
                  isLoadingPresentEmployees = false;
                  isPresentEmployeesFullyLoaded = true;
                  updatePresentEmployeesUI();
                  return employees.length;
              } else {
                  throw new Error(data.error || 'API returned success: false');
              }
          } catch (error) {
              console.error('Error updating present count:', error);
              isLoadingPresentEmployees = false;
              isPresentEmployeesFullyLoaded = true;
              updatePresentEmployeesUI();
              return allPresentEmployees.length;
          }
      }

      // Debounce utility
      function debouncePresentSearch(func, wait) {
          let timeout;
          return function executedFunction(...args) {
              const later = () => {
                  clearTimeout(timeout);
                  func(...args);
              };
              clearTimeout(timeout);
              timeout = setTimeout(later, wait);
          };
      }

      // Search functionality with debounce
      const debouncedPresentSearch = debouncePresentSearch(function(searchTerm) {
          if (searchTerm === '') {
              displayPresentEmployees(allPresentEmployees);
              return;
          }
          
          const lowerSearchTerm = searchTerm.toLowerCase();
          const filteredEmployees = allPresentEmployees.filter(employee => {
              const fullName = `${employee.first_name} ${employee.middle_name || ''} ${employee.last_name}`.toLowerCase();
              const location = (employee.location || '').toLowerCase();
              const employeeNumber = (employee.employee_number || '').toLowerCase();
              const timeIn = (employee.time_in || '').toLowerCase();
              
              return fullName.includes(lowerSearchTerm) || 
                    location.includes(lowerSearchTerm) || 
                    employeeNumber.includes(lowerSearchTerm) ||
                    timeIn.includes(lowerSearchTerm);
          });
          
          displayPresentEmployees(filteredEmployees);
      }, 300);

      document.addEventListener('DOMContentLoaded', function() {
          // Initialize with loading state
          isLoadingPresentEmployees = true;
          isPresentEmployeesFullyLoaded = false;
          updatePresentEmployeesUI();
          
          // Start background loading
          updatePresentCount(null, true);
          
          // Search functionality
          const presentSearchInput = document.getElementById('presentEmployeeSearch');
          if (presentSearchInput) {
              presentSearchInput.addEventListener('input', function() {
                  debouncedPresentSearch(this.value.trim());
              });
          }

          // Modal handlers
          const presentModal = document.getElementById('presentEmployeesModal');
          if (presentModal) {
              presentModal.addEventListener('shown.bs.modal', function () {
                  const searchInput = document.getElementById('presentEmployeeSearch');
                  if (searchInput) searchInput.value = '';
                  
                  loadPresentEmployees();
              });

              presentModal.addEventListener('hidden.bs.modal', function () {
                  const searchInput = document.getElementById('presentEmployeeSearch');
                  if (searchInput) searchInput.value = '';
              });
          }

          // Location filter integration
          const locationFilter = document.getElementById('locationFilter');
          if (locationFilter) {
              locationFilter.addEventListener('change', function() {
                  const selectedLocation = this.value;
                  setPresentEmployeesLocation(selectedLocation);
              });
          }
      });

      // Cache cleanup - runs every minute to remove stale cache entries
      setInterval(() => {
          const now = Date.now();
          const fiveMinutes = 5 * 60 * 1000;
          
          for (let [key, data] of presentDataCache.entries()) {
              if (now - data.timestamp > fiveMinutes) {
                  presentDataCache.delete(key);
              }
          }
      }, 60000);
  </script>
   
  <script>
        // === ABSENT EMPLOYEES MODAL FUNCTIONALITY ===
        let currentAbsentLocation = '';
        let allAbsentEmployees = [];
        let absentDataCache = new Map();
        let isLoadingAbsentEmployees = false;
        let currentAbsentCount = 0;

        function updateAbsentDisplay(count, isLoading = false, isDataFullyLoaded = false) {
            const absentElement = document.getElementById('admin_absent');
            if (!absentElement) return;
            
            currentAbsentCount = count;
            
            if (isLoading || !isDataFullyLoaded) {
                absentElement.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 50 50">
                        <path fill="#fff" d="M43.935,25.145c0-10.318-8.364-18.682-18.682-18.682
                            c-10.318,0-18.682,8.364-18.682,18.682h4.068
                            c0-8.064,6.55-14.614,14.614-14.614s14.614,6.55,14.614,14.614H43.935z">
                            <animateTransform attributeType="xml" attributeName="transform" type="rotate"
                                from="0 25 25" to="360 25 25" dur="0.8s" repeatCount="indefinite"/>
                        </path>
                    </svg>
                `;
            } else {
                absentElement.textContent = count;
                absentElement.style.fontSize = '18px';
                absentElement.style.fontWeight = 'bold';
            }
            
            const modalCount = document.getElementById('modalAbsentCount');
            if (modalCount) {
                modalCount.textContent = count;
            }
        }


        function debounceAbsent(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        const getAbsentCacheKey = (location) => location || 'all_locations';

        function setAbsentEmployeesLocation(location) {
            const oldLocation = currentAbsentLocation;
            currentAbsentLocation = location || '';
            
            if (oldLocation !== currentAbsentLocation) {
                absentDataCache.delete(getAbsentCacheKey(oldLocation));
                
                // Check if we have cached data for new location
                const newCacheKey = getAbsentCacheKey(currentAbsentLocation);
                if (absentDataCache.has(newCacheKey)) {
                    // Use cached data immediately - data is already loaded
                    const cachedData = absentDataCache.get(newCacheKey);
                    updateAbsentDisplay(cachedData.employees.length, false, true);
                } else {
                    // Show current count with loading indicator
                    updateAbsentDisplay(currentAbsentCount, true, false);
                    updateAbsentCount();
                }
                
                // Reload modal if open
                const modal = document.getElementById('absentEmployeesModal');
                if (modal?.classList.contains('show')) {
                    loadAbsentEmployees(true);
                }
            }
        }

        // Background count update - doesn't interfere with display
        async function updateAbsentCount(location = null, showLoading = true) {
            const targetLocation = location !== null ? location : currentAbsentLocation;
            const cacheKey = getAbsentCacheKey(targetLocation);
            
            // Use cached data immediately if available
            if (absentDataCache.has(cacheKey)) {
                const cachedData = absentDataCache.get(cacheKey);
                updateAbsentDisplay(cachedData.employees.length, false);
                return cachedData.employees.length;
            }
            
            // Show loading indicator
            if (showLoading) {
                updateAbsentDisplay(currentAbsentCount, true);
            }
            
            try {
                let url = '{{ url("/dashboard/get-absent-employees") }}';
                if (targetLocation) {
                    url += `?location=${encodeURIComponent(targetLocation)}`;
                }
                
                const response = await fetchAbsentWithTimeout(url, 5000);
                const data = await response.json();
                
                if (data.success !== false) {
                    const count = data.employees ? data.employees.length : 0;
                    const cacheData = {
                        employees: data.employees || [],
                        message: data.message,
                        timestamp: Date.now()
                    };
                    
                    absentDataCache.set(cacheKey, cacheData);
                    // Data is now fully loaded and cached
                    updateAbsentDisplay(count, false, true);
                    return count;
                } else {
                    throw new Error(data.error || 'API returned success: false');
                }
            } catch (error) {
                console.error('Error updating absent count:', error);
                // Keep current count, just remove loading indicator
                updateAbsentDisplay(currentAbsentCount, false);
                return currentAbsentCount;
            }
        }

        // Fast sync from external data (like location filter)
        function syncAbsentCountFromFilterData(absentCount, location = null) {
            const targetLocation = location !== null ? location : currentAbsentLocation;
            const cacheKey = getAbsentCacheKey(targetLocation);
            
            // Immediate update - but data not fully loaded until cached
            updateAbsentDisplay(absentCount, false, false);
            
            // Clear cache to ensure fresh data later
            absentDataCache.delete(cacheKey);
        }

        // Load absent employees function
        async function loadAbsentEmployees(forceReload = false) {
            if (isLoadingAbsentEmployees) return;

            const cacheKey = getAbsentCacheKey(currentAbsentLocation);
            const absentEmployeesList = document.getElementById('absentEmployeesList');
            
            // Use cache first
            if (!forceReload && absentDataCache.has(cacheKey)) {
                const cachedData = absentDataCache.get(cacheKey);
                allAbsentEmployees = cachedData.employees;
                // Data is from cache - fully loaded
                updateAbsentDisplay(cachedData.employees.length, false, true);
                
                if (cachedData.message && cachedData.employees.length === 0) {
                    absentEmployeesList.innerHTML = createAbsentNoEmployeesHTML('clock', 'Too Early', cachedData.message);
                } else {
                    displayAbsentEmployees(cachedData.employees);
                }
                return;
            }

            isLoadingAbsentEmployees = true;
            
            // Show modal loading
            if (absentEmployeesList) {
                absentEmployeesList.innerHTML = createAbsentLoadingHTML();
            }

            try {
                const url = buildAbsentURL();
                const response = await fetchAbsentWithTimeout(url, 10000);
                
                if (!response.ok) {
                    const text = await response.text();
                    throw new Error(`HTTP ${response.status}: ${text}`);
                }
                
                const data = await response.json();
                
                if (data.success === false) {
                    throw new Error(data.error || 'Unknown server error');
                }
                
                const employees = data.employees || [];
                const cacheData = {
                    employees,
                    message: data.message,
                    timestamp: Date.now()
                };
                
                absentDataCache.set(cacheKey, cacheData);
                allAbsentEmployees = employees;
                
                // Update count - data is now fully loaded and cached
                updateAbsentDisplay(employees.length, false, true);
                
                // Update modal display
                if (absentEmployeesList) {
                    if (data.message && employees.length === 0) {
                        absentEmployeesList.innerHTML = createAbsentNoEmployeesHTML('clock', 'Too Early', data.message);
                    } else {
                        displayAbsentEmployees(employees);
                    }
                }
                
            } catch (error) {
                console.error('Error loading absent employees:', error);
                updateAbsentDisplay(currentAbsentCount, false, false);
                
                if (absentEmployeesList) {
                    absentEmployeesList.innerHTML = createAbsentErrorHTML(error.message);
                }
            } finally {
                isLoadingAbsentEmployees = false;
            }
        }

        // Utility functions
        function buildAbsentURL() {
            let url = '{{ url("/dashboard/get-absent-employees") }}';
            if (currentAbsentLocation) {
                url += `?location=${encodeURIComponent(currentAbsentLocation)}`;
            }
            return url;
        }

        function fetchAbsentWithTimeout(url, timeout = 8000) {
            return Promise.race([
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                }),
                new Promise((_, reject) => 
                    setTimeout(() => reject(new Error('Request timeout')), timeout)
                )
            ]);
        }

        function createAbsentLoadingHTML() {
            return `
                <div class="loading-spinner text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                    <p class="mt-2">Loading absent employees...</p>
                </div>
            `;
        }

        function createAbsentNoEmployeesHTML(icon, title, message) {
            return `
                <div class="no-employees text-center">
                    <i class="fas fa-${icon} fa-3x text-info mb-3"></i>
                    <h5>${title}</h5>
                    <p>${message}</p>
                </div>
            `;
        }

        function createAbsentErrorHTML(message) {
            return `
                <div class="no-employees text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>Error Loading Absent Employees</h5>
                    <p class="text-danger">${message}</p>
                    <br>
                    <button class="btn btn-primary btn-sm" onclick="loadAbsentEmployees(true)">
                        <i class="fas fa-redo"></i> Try Again
                    </button>
                </div>
            `;
        }

        function displayAbsentEmployees(employees) {
            const absentEmployeesList = document.getElementById('absentEmployeesList');
            if (!absentEmployeesList) return;
            
            if (!employees || employees.length === 0) {
                absentEmployeesList.innerHTML = `
                    <div class="no-employees text-center">
                        <i class="fas fa-user-check fa-3x text-muted mb-3"></i>
                        <h5>Great! No Absent Employees</h5>
                        <p>All employees are present or on approved leave today${currentAbsentLocation ? ` in ${currentAbsentLocation}` : ''}.</p>
                    </div>
                `;
                return;
            }

            const html = employees.map(employee => {
                const initials = getInitials(employee.first_name, employee.last_name);
                const middleName = employee.middle_name ? employee.middle_name + ' ' : '';
                const location = employee.location || 'No location specified';
                const employeeNumber = employee.employee_number || 'N/A';
                
                return `
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
                                  <i class="fas fa-id-badge me-1 text-muted"></i>
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
            }).join('');
            
            absentEmployeesList.innerHTML = html;
        }

        // Search functionality with debounce
        const debouncedAbsentSearch = debounceAbsent(function(searchTerm) {
            if (searchTerm === '') {
                displayAbsentEmployees(allAbsentEmployees);
                return;
            }
            
            const lowerSearchTerm = searchTerm.toLowerCase();
            const filteredEmployees = allAbsentEmployees.filter(employee => {
                const fullName = `${employee.first_name} ${employee.middle_name || ''} ${employee.last_name}`.toLowerCase();
                const location = (employee.location || '').toLowerCase();
                const employeeNumber = (employee.employee_number || '').toLowerCase();
                
                return fullName.includes(lowerSearchTerm) || 
                      location.includes(lowerSearchTerm) || 
                      employeeNumber.includes(lowerSearchTerm);
            });
            
            displayAbsentEmployees(filteredEmployees);
        }, 300);

        // Cache management
        function clearAbsentEmployeesCache() {
            absentDataCache.clear();
        }

        // Event listeners and modal handlers
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize with loading state
            updateAbsentDisplay(0, true, false);
            
            // Start background loading to populate cache
            updateAbsentCount(null, true);
            
            // Search functionality
            const absentSearchInput = document.getElementById('absentEmployeeSearch');
            if (absentSearchInput) {
                absentSearchInput.addEventListener('input', function() {
                    debouncedAbsentSearch(this.value.trim());
                });
            }

            // Modal handlers
            const absentModal = document.getElementById('absentEmployeesModal');
            if (absentModal) {
                absentModal.addEventListener('shown.bs.modal', function () {
                    const searchInput = document.getElementById('absentEmployeeSearch');
                    if (searchInput) searchInput.value = '';
                    loadAbsentEmployees();
                });

                absentModal.addEventListener('hidden.bs.modal', function () {
                    const searchInput = document.getElementById('absentEmployeeSearch');
                    if (searchInput) searchInput.value = '';
                });
            }

            // Location filter integration
            const locationFilter = document.getElementById('locationFilter');
            if (locationFilter) {
                // Add event listener that specifically handles absent employees
                locationFilter.addEventListener('change', function() {
                    const selectedLocation = this.value;
                    
                    // Update absent location
                    setAbsentEmployeesLocation(selectedLocation);
                });
            }
        });

        // Cache cleanup - runs every minute to remove stale cache entries
        setInterval(() => {
            const now = Date.now();
            const fiveMinutes = 5 * 60 * 1000;
            
            for (let [key, data] of absentDataCache.entries()) {
                if (now - data.timestamp > fiveMinutes) {
                    absentDataCache.delete(key);
                }
            }
        }, 60000);

        // Integration with main location filter (to be called from the main location filter handler)
        function updateAbsentForLocationFilter(selectedLocation, absentCount) {
            // Set the location
            setAbsentEmployeesLocation(selectedLocation);
            
            // Sync the count immediately but keep loading state until cache is populated
            syncAbsentCountFromFilterData(absentCount, selectedLocation);
            
            // Trigger background loading to populate cache for instant modal access
            updateAbsentCount(selectedLocation, false);
        }

        // Helper function (assuming it exists globally, or define it here)
        function getInitials(firstName, lastName) {
            const first = firstName ? firstName.charAt(0).toUpperCase() : '';
            const last = lastName ? lastName.charAt(0).toUpperCase() : '';
            return first + last;
        }
    </script>
    <script>
        // === LATE EMPLOYEES MODAL FUNCTIONALITY ===
        let currentLateLocation = '';
        let allLateEmployees = [];
        let lateDataCache = new Map();
        let isLoadingLateEmployees = false;
        let isLateEmployeesFullyLoaded = false;

        const getLateCacheKey = (location) => location || 'all_locations';

        function setLateEmployeesLocation(location) {
            const oldLocation = currentLateLocation;
            currentLateLocation = location || '';
            
            if (oldLocation !== currentLateLocation) {
                lateDataCache.delete(getLateCacheKey(oldLocation));
                
                // Check if we have cached data for new location
                const newCacheKey = getLateCacheKey(currentLateLocation);
                if (lateDataCache.has(newCacheKey)) {
                    // Use cached data immediately - data is already loaded
                    const cachedData = lateDataCache.get(newCacheKey);
                    allLateEmployees = cachedData.employees;
                    isLateEmployeesFullyLoaded = true;
                    isLoadingLateEmployees = false;
                    updateLateEmployeesUI();
                } else {
                    // Need to load data
                    isLateEmployeesFullyLoaded = false;
                    loadLateEmployees();
                }
                
                // Reload modal if open
                const modal = document.getElementById('lateEmployeesModal');
                if (modal?.classList.contains('show')) {
                    loadLateEmployees();
                }
            }
        }

        function loadLateEmployees() {
            if (isLoadingLateEmployees) return;

            const cacheKey = getLateCacheKey(currentLateLocation);
            const lateEmployeesList = document.getElementById('lateEmployeesList');
            
            // Check cache first - if data exists, use it immediately
            if (lateDataCache.has(cacheKey)) {
                const cachedData = lateDataCache.get(cacheKey);
                allLateEmployees = cachedData.employees;
                isLateEmployeesFullyLoaded = true;
                isLoadingLateEmployees = false;
                updateLateEmployeesUI();
                
                // Show cached results in modal immediately
                displayLateEmployees(cachedData.employees);
                return;
            }
            
            // Set loading state IMMEDIATELY
            isLoadingLateEmployees = true;
            isLateEmployeesFullyLoaded = false;
            updateLateEmployeesUI();

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

                const employees = data.employees || [];
                const cacheData = {
                    employees: employees,
                    timestamp: Date.now()
                };
                
                // Cache the data
                lateDataCache.set(cacheKey, cacheData);
                allLateEmployees = employees;
                isLoadingLateEmployees = false;
                isLateEmployeesFullyLoaded = true;

                updateLateEmployeesUI();
                displayLateEmployees(employees);
            })
            .catch(error => {
                isLoadingLateEmployees = false;
                isLateEmployeesFullyLoaded = true;

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

        function updateLateEmployeesUI() {
            const lateEmployeesList = document.getElementById('lateEmployeesList');

            const spinnerSVG = `
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 50 50">
                        <path fill="#fff" d="M43.935,25.145c0-10.318-8.364-18.682-18.682-18.682
                            c-10.318,0-18.682,8.364-18.682,18.682h4.068
                            c0-8.064,6.55-14.614,14.614-14.614s14.614,6.55,14.614,14.614H43.935z">
                            <animateTransform attributeType="xml" attributeName="transform" type="rotate"
                                from="0 25 25" to="360 25 25" dur="0.8s" repeatCount="indefinite"/>
                        </path>
                    </svg>
            `;

            // Show spinner if loading - this will run FIRST
            if (isLoadingLateEmployees) {
                lateEmployeesList.innerHTML = `
                    <div class="loading-spinner text-center">
                        ${spinnerSVG}
                        <p class="mt-2">Loading late employees...</p>
                    </div>
                `;
                document.getElementById('modalLateCount').innerHTML = spinnerSVG;
                document.getElementById('late_admin').innerHTML = spinnerSVG;
                return;
            }

            // Only update count when data is fully loaded and not loading
            if (isLateEmployeesFullyLoaded && !isLoadingLateEmployees) {
                document.getElementById('modalLateCount').textContent = allLateEmployees.length;
                document.getElementById('late_admin').textContent = allLateEmployees.length;
                displayLateEmployees(allLateEmployees);
            }
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
                                    <i class="fas fa-id-badge me-1 text-muted"></i>
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

        // Cache management
        function clearLateEmployeesCache() {
            lateDataCache.clear();
            isLateEmployeesFullyLoaded = false;
            allLateEmployees = [];
        }

        // Background count update
        async function updateLateCount(location = null, showLoading = true) {
            const targetLocation = location !== null ? location : currentLateLocation;
            const cacheKey = getLateCacheKey(targetLocation);
            
            // Use cached data immediately if available
            if (lateDataCache.has(cacheKey)) {
                const cachedData = lateDataCache.get(cacheKey);
                allLateEmployees = cachedData.employees;
                isLateEmployeesFullyLoaded = true;
                isLoadingLateEmployees = false;
                updateLateEmployeesUI();
                return cachedData.employees.length;
            }
            
            // Set loading state if requested
            if (showLoading) {
                isLoadingLateEmployees = true;
                isLateEmployeesFullyLoaded = false;
                updateLateEmployeesUI();
            }
            
            try {
                let url = '{{ url("/dashboard/get-late-employees") }}';
                if (targetLocation) {
                    url += `?location=${encodeURIComponent(targetLocation)}`;
                }
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                
                if (!response.ok) {
                    const text = await response.text();
                    throw new Error(`HTTP ${response.status}: ${text}`);
                }
                
                const data = await response.json();
                
                if (data.success !== false) {
                    const employees = data.employees || [];
                    const cacheData = {
                        employees: employees,
                        timestamp: Date.now()
                    };
                    
                    lateDataCache.set(cacheKey, cacheData);
                    allLateEmployees = employees;
                    isLoadingLateEmployees = false;
                    isLateEmployeesFullyLoaded = true;
                    updateLateEmployeesUI();
                    return employees.length;
                } else {
                    throw new Error(data.error || 'API returned success: false');
                }
            } catch (error) {
                console.error('Error updating late count:', error);
                isLoadingLateEmployees = false;
                isLateEmployeesFullyLoaded = true;
                updateLateEmployeesUI();
                return allLateEmployees.length;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize with loading state
            isLoadingLateEmployees = true;
            isLateEmployeesFullyLoaded = false;
            updateLateEmployeesUI();
            
            // Start background loading
            updateLateCount(null, true);
            
            // Search functionality
            const lateSearchInput = document.getElementById('lateEmployeeSearch');
            if (lateSearchInput) {
                lateSearchInput.addEventListener('input', function() {
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
            }

            // Modal handlers
            const lateModal = document.getElementById('lateEmployeesModal');
            if (lateModal) {
                lateModal.addEventListener('shown.bs.modal', function () {
                    const searchInput = document.getElementById('lateEmployeeSearch');
                    if (searchInput) searchInput.value = '';
                    
                    loadLateEmployees();
                });

                lateModal.addEventListener('hidden.bs.modal', function () {
                    const searchInput = document.getElementById('lateEmployeeSearch');
                    if (searchInput) searchInput.value = '';
                });
            }

            // Location filter integration
            const locationFilter = document.getElementById('locationFilter');
            if (locationFilter) {
                locationFilter.addEventListener('change', function() {
                    const selectedLocation = this.value;
                    setLateEmployeesLocation(selectedLocation);
                });
            }
        });

        // Cache cleanup - runs every minute to remove stale cache entries
        setInterval(() => {
            const now = Date.now();
            const fiveMinutes = 5 * 60 * 1000;
            
            for (let [key, data] of lateDataCache.entries()) {
                if (now - data.timestamp > fiveMinutes) {
                    lateDataCache.delete(key);
                }
            }
        }, 60000);
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

    function formatDate(dateString) {
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    const dateObj = new Date(dateString);
    const month = months[dateObj.getMonth()];
    const day = dateObj.getDate();
    const year = dateObj.getFullYear();
    return `${month} ${day < 10 ? '0' + day : day}, ${year}`;
    }


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
                <td style="border: 1px solid #ccc; padding: 6px;">${formatDate(late.date)}</td>
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