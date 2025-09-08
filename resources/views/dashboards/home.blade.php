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
    height: 306px !important;
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

.birthday-item:hover {
  background: #bbdefb !important;
  transform: scale(1.02);
  transition: all 0.2s ease;
}

.calendar-day:hover {
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: box-shadow 0.2s ease;
}

.birthday-detail-item:hover {
  background: #f5f5f5;
}

@media (max-width: 768px) {
  .calendar-grid {
    font-size: 10px !important;
  }
  
  .calendar-day {
    min-height: 50px !important;
  }
  
  .birthday-item span {
    font-size: 8px !important;
  }
}

.employee-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2) !important;
}

.edit-image-btn:hover {
    background: rgba(0,0,0,0.9) !important;
    transform: scale(1.1);
}

.new-hires-container::-webkit-scrollbar {
    height: 8px;
}

.new-hires-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.new-hires-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.new-hires-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

@media (max-width: 768px) {
    .employee-card {
        width: 150px !important;
        height: 240px !important;
    }
    
    .photo-section {
        height: 150px !important;
        padding: 15px !important;
    }
    
    .photo-section img {
        width: 120px !important;
        height: 120px !important;
    }
    
    .edit-image-btn {
        width: 28px !important;
        height: 28px !important;
        top: 8px !important;
        right: 8px !important;
    }
    
    .edit-image-btn svg {
        width: 12px !important;
        height: 12px !important;
    }
}
</style>
      </div>
    </div>
  </div>
</div>

<style>
   .employee-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    transition: all 0.2s ease;
    width: 240px;
    height: 320px;
    flex-shrink: 0;
    position: relative;
    border: 1px solid #f1f5f9;
}

.employee-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.new-hires-carousel-wrapper {
    position: relative;
    padding: 20px 50px;
}

.new-hires-container {
    display: flex;
    gap: 20px;
    padding: 0;
    overflow-x: auto;
    overflow-y: visible;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
    justify-content: center;
    min-width: 100%;
}

.new-hires-container:has(.employee-card:nth-child(-n+3)) {
    justify-content: center;
}

@supports not selector(:has(*)) {
    .new-hires-container {
        justify-content: center;
    }
    
    .new-hires-container:hover {
        justify-content: flex-start;
    }
}

.new-hires-container::-webkit-scrollbar {
    display: none;
}

.photo-section {
    background: #f8fafc;
    height: 160px;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    overflow: hidden;
    padding: 12px;
}

.photo-section img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    border-radius: 6px;
}

.employee-card:hover .photo-section img {
    transform: scale(1.02);
}

.edit-image-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(255,255,255,0.95);
    border: none;
    border-radius: 6px;
    width: 28px;
    height: 28px;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.edit-image-btn:hover {
    background: white;
    color: #3b82f6;
    transform: scale(1.05);
}

.initials-banner {
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
    color: white;
    text-align: center;
    padding: 12px 0;
    margin: 0;
    position: relative;
    width: 100%;
}

.initials-banner::before {
    display: none;
}

.initials-banner div {
    font-weight: 600;
    font-size: 12px;
    letter-spacing: 1px;
}

.details-section {
    background: white;
    padding: 16px 16px 20px 16px;
    text-align: center;
}

.employee-name {
    font-size: 15px;
    color: #1a202c;
    font-weight: 600;
    margin-bottom: 6px;
    line-height: 1.3;
}

.employee-position {
    font-size: 11px;
    color: #3b82f6;
    font-weight: 500;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.employee-department {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 6px;
    font-weight: 400;
}

.employee-hired-date {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 400;
}

.carousel-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1000;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}

.carousel-nav-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    transform: translateY(-50%) scale(1.05);
}

.carousel-prev {
    left: 10px;
}

.carousel-next {
    right: 10px;
}

.carousel-nav-btn i {
    font-size: 14px;
    color: #64748b;
}

.carousel-nav-btn:hover i {
    color: #3b82f6;
}

@media (max-width: 768px) {
    .new-hires-carousel-wrapper {
        padding: 20px 40px;
    }
    
    .carousel-nav-btn {
        width: 36px;
        height: 36px;
    }
    
    .carousel-nav-btn i {
        font-size: 12px;
    }
    
    .employee-card {
        width: 220px;
    }
}
</style>
@endsection

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0">{{ date('M d, Y') }}</h3>

                    <div class="attendance-buttons d-flex flex-wrap align-items-center gap-2" data-attendance-container>
                                {{-- Location status will be inserted here by JavaScript --}}
                                @if(($user_travel_orders_today) || (auth()->user()->login == 1))
                                    @if($attendance_now != null)
                                        <button style="height: 40px; width: 40px;" onclick="getLocation()" 
                                                type="button" 
                                                title="Time Out" 
                                                class="btn btn-danger btn-rounded btn-icon" 
                                                data-toggle="modal" 
                                                data-target="#timeOut"
                                                data-attendance-btn="true"
                                                disabled>
                                            <i class="ti-control-pause"></i>
                                        </button>
                                    @else
                                        <button style="height: 40px; width: 40px;" onclick="getLocation()" 
                                                type="button" 
                                                title="Time In" 
                                                class="btn btn-success btn-rounded btn-icon" 
                                                data-toggle="modal" 
                                                data-target="#timeIn"
                                                data-attendance-btn="true"
                                                disabled>
                                            <i class="ti-control-play"></i>
                                        </button>
                                    @endif
                                    
                                    <button style="height: 40px; width: 40px;" onclick="showDetailedLocationCheck()"
                                                type="button"
                                                title="Check Location Details"
                                                class="btn btn-info btn-rounded btn-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                @endif
                            </div>
                    </div>

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

                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-3 flex-wrap">
                            <h3 class="card-title mb-2 mb-md-0 me-md-3">{{ date('M d, Y') }}</h3>

                            <div class="attendance-buttons d-flex flex-wrap align-items-center gap-2" data-attendance-container>
                                {{-- Location status will be inserted here by JavaScript --}}
                                @if(($user_travel_orders_today) || (auth()->user()->login == 1))
                                    @if($attendance_now != null)
                                        <button style="height: 40px; width: 40px;" onclick="getLocation()" 
                                                type="button" 
                                                title="Time Out" 
                                                class="btn btn-danger btn-rounded btn-icon" 
                                                data-toggle="modal" 
                                                data-target="#timeOut"
                                                data-attendance-btn="true"
                                                disabled>
                                            <i class="ti-control-pause"></i>
                                        </button>
                                    @else
                                        <button style="height: 40px; width: 40px;" onclick="getLocation()" 
                                                type="button" 
                                                title="Time In" 
                                                class="btn btn-success btn-rounded btn-icon" 
                                                data-toggle="modal" 
                                                data-target="#timeIn"
                                                data-attendance-btn="true"
                                                disabled>
                                            <i class="ti-control-play"></i>
                                        </button>
                                    @endif
                                    
                                    <button style="height: 40px; width: 40px;" onclick="showDetailedLocationCheck()"
                                                type="button"
                                                title="Check Location Details"
                                                class="btn btn-info btn-rounded btn-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                @endif
                            </div>
                        </div>


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

                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-3 flex-wrap">
                                <h3 class="card-title mb-2 mb-md-0 me-md-3">{{ date('M d, Y') }}</h3>

                                <div class="attendance-buttons d-flex flex-wrap align-items-center gap-2" data-attendance-container>
                                    {{-- Location status will be inserted here by JavaScript --}}
                                    @if(($user_travel_orders_today) || (auth()->user()->login == 1))
                                        @if($attendance_now != null)
                                            <button style="height: 40px; width: 40px;" onclick="getLocation()" 
                                                    type="button" 
                                                    title="Time Out" 
                                                    class="btn btn-danger btn-rounded btn-icon" 
                                                    data-toggle="modal" 
                                                    data-target="#timeOut"
                                                    data-attendance-btn="true"
                                                    disabled>
                                                <i class="ti-control-pause"></i>
                                            </button>
                                        @else
                                            <button style="height: 40px; width: 40px;" onclick="getLocation()" 
                                                    type="button" 
                                                    title="Time In" 
                                                    class="btn btn-success btn-rounded btn-icon" 
                                                    data-toggle="modal" 
                                                    data-target="#timeIn"
                                                    data-attendance-btn="true"
                                                    disabled>
                                                <i class="ti-control-play"></i>
                                            </button>
                                        @endif
                                        
                                        <button style="height: 40px; width: 40px;" onclick="showDetailedLocationCheck()"
                                                type="button"
                                                title="Check Location Details"
                                                class="btn btn-info btn-rounded btn-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
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
                        <div class="card">
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
           

              <div class="col-md-8">
                <div class='row'>
                  <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                        <!-- Header with title and current month -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <p class="card-title" style="margin: 0;">Birthday Celebrants</p>
                            <div style="font-size: 14px; color: #666; font-weight: 500;">
                                @php echo date('F Y'); @endphp
                            </div>
                        </div>
                        
                        <div class="birthday-calendar" style="overflow-y: scroll; height:300px;">
                            <div class="calendar-grid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; font-size: 11px;">
                            
                            <div class="calendar-header" style="grid-column: 1 / -1; display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; margin-bottom: 5px;">
                                <div style="text-align: center; font-weight: bold; padding: 5px; background: #f8f9fa; border-radius: 3px;">Sun</div>
                                <div style="text-align: center; font-weight: bold; padding: 5px; background: #f8f9fa; border-radius: 3px;">Mon</div>
                                <div style="text-align: center; font-weight: bold; padding: 5px; background: #f8f9fa; border-radius: 3px;">Tue</div>
                                <div style="text-align: center; font-weight: bold; padding: 5px; background: #f8f9fa; border-radius: 3px;">Wed</div>
                                <div style="text-align: center; font-weight: bold; padding: 5px; background: #f8f9fa; border-radius: 3px;">Thu</div>
                                <div style="text-align: center; font-weight: bold; padding: 5px; background: #f8f9fa; border-radius: 3px;">Fri</div>
                                <div style="text-align: center; font-weight: bold; padding: 5px; background: #f8f9fa; border-radius: 3px;">Sat</div>
                            </div>

                            @php
                                $currentMonth = date('n');
                                $currentYear = date('Y');
                                $daysInMonth = date('t');
                                $firstDayOfMonth = date('w', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
                                
                                // Group birthdays by day
                                $birthdaysByDay = [];
                                foreach($employee_birthday_celebrants as $celebrant) {
                                $day = date('j', strtotime($celebrant->birth_date));
                                if (!isset($birthdaysByDay[$day])) {
                                    $birthdaysByDay[$day] = [];
                                }
                                $birthdaysByDay[$day][] = $celebrant;
                                }
                            @endphp

                            @for($i = 0; $i < $firstDayOfMonth; $i++)
                                <div class="calendar-day" style="min-height: 60px; border: 1px solid #e9ecef; background: #f8f9fa; border-radius: 3px;"></div>
                            @endfor

                            @for($day = 1; $day <= $daysInMonth; $day++)
                                @php $isToday = ($day == date('j') && $currentMonth == date('n') && $currentYear == date('Y')); @endphp
                                <div class="calendar-day" style="min-height: 60px; border: 1px solid #e9ecef; border-radius: 3px; padding: 2px; position: relative; background: {{ $isToday ? '#e8f5e8' : '#fff' }}; {{ $isToday ? 'border-color: #4caf50; box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);' : '' }}">
                                <div style="font-weight: bold; margin-bottom: 2px; font-size: 10px; {{ $isToday ? 'color: #2e7d32;' : '' }}">{{ $day }}</div>
                                
                                @if(isset($birthdaysByDay[$day]))
                                    @foreach($birthdaysByDay[$day] as $celebrant)
                                    <div class="birthday-item" style="background: #e3f2fd; border-radius: 2px; padding: 1px 2px; margin: 1px 0; position: relative; cursor: pointer;" 
                                        title="{{$celebrant->first_name}} {{$celebrant->last_name}} - {{$celebrant->position}} ({{$celebrant->location}})">
                                        <div style="display: flex; align-items: center; gap: 2px;">
                                        <img src="{{URL::asset($celebrant->avatar)}}" 
                                            onerror="this.src='{{URL::asset('/images/no_image.png')}}';" 
                                            alt="user" 
                                            style="width: 12px; height: 12px; border-radius: 50%; object-fit: cover;">
                                        <span style="font-size: 9px; color: #1976d2; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{substr($celebrant->first_name, 0, 8)}}{{strlen($celebrant->first_name) > 8 ? '...' : ''}}
                                        </span>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                                </div>
                            @endfor
                            </div>

                            <div class="birthday-details mt-3" style="border-top: 1px solid #e9ecef; padding-top: 10px;">
                            <h6 style="margin-bottom: 10px; font-size: 12px; color: #666;">This Month's Celebrants</h6>
                            <div style="max-height: 120px; overflow-y: auto;">
                                @foreach($employee_birthday_celebrants as $celebrant)
                                <div class="birthday-detail-item" style="display: flex; align-items: center; gap: 8px; padding: 4px 0; border-bottom: 1px solid #f0f0f0;">
                                    <img src="{{URL::asset($celebrant->avatar)}}" 
                                        onerror="this.src='{{URL::asset('/images/no_image.png')}}';" 
                                        alt="user" 
                                        style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover;">
                                    <div style="flex: 1;">
                                    <div style="font-size: 11px; color: #333; font-weight: 500;">
                                        {{$celebrant->first_name}} {{$celebrant->last_name}}
                                    </div>
                                    <div style="font-size: 10px; color: #666;">
                                        {{$celebrant->position}} - {{$celebrant->location}} | {{date('M d', strtotime($celebrant->birth_date))}}
                                    </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            </div>
                          </div>
                        </div>
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
                
            
            </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mt-4">
                            <div class="card-body">
                                <p class="card-title">Welcome New Hires</p>
                                
                                <div class="new-hires-carousel-wrapper position-relative">
                                    <button class="carousel-nav-btn carousel-prev" onclick="scrollNewHires('left')">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    
                                    <button class="carousel-nav-btn carousel-next" onclick="scrollNewHires('right')">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                    
                                    <div class="new-hires-container" id="newHiresContainer">
                                        @foreach($employees_new_hire as $employee)
                                            <div class="employee-card">
                                                
                                                <div class="photo-section">
                                                    <img src="{{ $employee->image ? URL::asset($employee->image) : URL::asset('/images/no_image.png') }}" 
                                                        onerror="this.src='{{ URL::asset('/images/no_image.png') }}';" 
                                                        alt="employee-{{ $employee->id }}" 
                                                        id="employee-img-{{ $employee->id }}">
                                                    
                                                    @if (auth()->user()->role == 'Admin')
                                                    <button type="button" 
                                                            class="edit-image-btn" 
                                                            onclick="openImageModal({{ $employee->id }})"
                                                            title="Edit Image">
                                                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                                            <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                                                        </svg>
                                                    </button>
                                                    @endif
                                                </div>
                                                
                                                <div class="initials-banner">
                                                    <div>
                                                        @php
                                                            $firstName = $employee->first_name;
                                                            $lastName = $employee->last_name;
                                                            $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                                        @endphp
                                                        {{ $initials }}
                                                    </div>
                                                </div>
                                                
                                                <div class="details-section">
                                                    <div class="employee-name">
                                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                                    </div>
                                                    <div class="employee-position">
                                                        {{ $employee->position }}
                                                    </div>
                                                    <div class="employee-department">
                                                        {{ optional($employee->department)->name ?? 'N/A' }}
                                                    </div>
                                                    <div class="employee-hired-date">
                                                        Hired: {{ date('M d, Y', strtotime($employee->original_date_hired)) }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Upload Modal -->
                <div class="modal fade" id="imageUploadModal" tabindex="-1" aria-labelledby="imageUploadModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageUploadModalLabel">Upload Employee Image</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="imageUploadForm" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" id="employee_id" name="employee_id">
                                    <div class="mb-3">
                                        <label for="employee_image" class="form-label">Select New Image</label>
                                        <input type="file" class="form-control" id="employee_image" name="image" accept="image/*" required>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                                    </div>
                                    <div id="imagePreview" style="display: none; text-align: center; margin-top: 10px;">
                                        <img id="previewImg" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #ddd;">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Upload Image</button>
                                </div>
                            </form>
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
$(document).ready(function() {
    $('.modal').on('hidden.bs.modal', function () {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('padding-right', '');
    });
});
</script>

<script>
function scrollNewHires(direction) {
    const container = document.getElementById('newHiresContainer');
    const scrollAmount = 250;
         
    if (direction === 'left') {
        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else if (direction === 'right') {
        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
}

function centerCards() {
    const container = document.getElementById('newHiresContainer');
    const cards = container.querySelectorAll('.employee-card');
    
    if (cards.length <= 3) {
        container.style.justifyContent = 'center';
        const prevBtn = document.querySelector('.carousel-prev');
        const nextBtn = document.querySelector('.carousel-next');
        
        if (prevBtn && nextBtn) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        }
    } else {
        container.style.justifyContent = 'flex-start';
        const prevBtn = document.querySelector('.carousel-prev');
        const nextBtn = document.querySelector('.carousel-next');
        
        if (prevBtn && nextBtn) {
            prevBtn.style.display = 'flex';
            nextBtn.style.display = 'flex';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('newHiresContainer');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
         
    function updateNavigationButtons() {
        const isAtStart = container.scrollLeft <= 0;
        const isAtEnd = container.scrollLeft >= (container.scrollWidth - container.clientWidth);
                 
        prevBtn.style.opacity = isAtStart ? '0.5' : '1';
        nextBtn.style.opacity = isAtEnd ? '0.5' : '1';
        prevBtn.style.pointerEvents = isAtStart ? 'none' : 'auto';
        nextBtn.style.pointerEvents = isAtEnd ? 'none' : 'auto';
    }
         
    container.addEventListener('scroll', updateNavigationButtons);
    
    updateNavigationButtons();
    centerCards();
    
    const observer = new MutationObserver(centerCards);
    observer.observe(container, { childList: true });
});
</script>

<script>
function openImageModal(employeeId) {
    console.log('Opening image modal for employee ID:', employeeId);
    
    document.getElementById('employee_id').value = employeeId;
    
    document.getElementById('imageUploadForm').reset();
    document.getElementById('imagePreview').style.display = 'none';
    
    const modal = new bootstrap.Modal(document.getElementById('imageUploadModal'));
    modal.show();
}

document.getElementById('employee_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('imageUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    submitButton.disabled = true;
    submitButton.textContent = 'Uploading...';
    
    fetch('{{ route("upload.employee.image") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const employeeImg = document.getElementById(`employee-img-${data.employee_id}`);
            if (employeeImg) {
                employeeImg.src = data.image_url;
            }
            
            showAlert('success', data.message);
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('imageUploadModal'));
            modal.hide();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while uploading the image');
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
});

function showAlert(type, message) {
    if (typeof Swal !== 'undefined') {
        if (type === 'success') {
            Swal.fire('Success!', message, 'success');
        } else {
            Swal.fire('Error!', message, 'error');
        }
    } else {
        alert(message);
    }
}
</script>


<script>
let userLocation = null;
let locationCheckPassed = false;
let cachedLocation = null;
let lastLocationTime = null;
const LOCATION_CACHE_DURATION = 5 * 60 * 1000;

function getCurrentLocation() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error('Geolocation is not supported by this browser.'));
            return;
        }
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                resolve({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy
                });
            },
            (error) => {
                let errorMessage = 'Unknown error occurred';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Location access denied by user.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Location information is unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Location request timed out.';
                        break;
                }
                reject(new Error(errorMessage));
            },
            {
                enableHighAccuracy: true,
                timeout: 8000,
                maximumAge: 60000
            }
        );
    });
}

function getCachedLocation() {
    if (cachedLocation && lastLocationTime) {
        const timeSinceCache = Date.now() - lastLocationTime;
        if (timeSinceCache < LOCATION_CACHE_DURATION) {
            console.log('Using cached location from', Math.round(timeSinceCache / 1000), 'seconds ago');
            return cachedLocation;
        }
    }
    return null;
}

function setCachedLocation(location) {
    cachedLocation = location;
    lastLocationTime = Date.now();
}

function showAttendanceLoading(message = '') {
    const attendanceButtons = document.querySelectorAll('[data-attendance-btn]');
    attendanceButtons.forEach(button => {
        button.disabled = true;
        button.style.opacity = '0.7';
        button.style.cursor = 'wait';
        
        const originalText = button.textContent;
        button.setAttribute('data-original-text', originalText);
        button.innerHTML = `
            <i class="fas fa-spinner fa-spin"></i> 
            <span>${message}</span>
        `;
        
        button.classList.add('btn-info');
        button.classList.remove('btn-success', 'btn-secondary');
    });
}

function showLocationCheckFeedback() {
    const existingFeedback = document.getElementById('location-check-feedback');
    if (existingFeedback) existingFeedback.remove();

    const feedbackDiv = document.createElement('div');
    feedbackDiv.id = 'location-check-feedback';
    feedbackDiv.className = 'alert alert-info alert-dismissible fade show';
    feedbackDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-location-arrow fa-spin mr-2"></i>
            <div>
                <div class="progress mt-2" style="height: 4px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         style="width: 100%; background-color: #17a2b8;"></div>
                </div>
            </div>
        </div>
    `;

    const attendanceContainer = document.querySelector('.attendance-buttons') || document.querySelector('[data-attendance-container]');
    if (attendanceContainer) {
        attendanceContainer.insertBefore(feedbackDiv, attendanceContainer.firstChild);
    }
}

async function checkForImmediateAccess() {
    try {
        console.log('Checking for immediate access...');
        
        const response = await fetch('{{ route("check.user.access") }}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const result = await response.json();
        console.log('User access check result:', result);

        if (result.success && result.hasImmediateAccess) {
            console.log('User has unrestricted access - enabling buttons immediately');
            locationCheckPassed = true;
            enableAttendanceButtons();
            showLocationStatus('You have unrestricted access. Camera attendance is available.', 'success');
            return true;
        } else if (result.success && result.accessType === 'no_access') {
            console.log('User has no camera access');
            disableAttendanceButtons();
            showLocationStatus('Camera access not available for your account.', 'error');
            return false;
        }
        
        return false;
    } catch (error) {
        console.error('User access check failed:', error);
        return false;
    }
}

async function checkLocationProximity() {
    try {
        console.log('Starting location proximity check...');
        
        showLocationCheckFeedback();
        showAttendanceLoading('');

        let location;
        
        const cached = getCachedLocation();
        if (cached) {
            location = cached;
            console.log('Using cached location');
            showAttendanceLoading('');
        } else {
            console.log('Getting fresh location...');
            try {
                location = await getCurrentLocation();
                setCachedLocation(location);
                showAttendanceLoading('');
            } catch (locationError) {
                console.error('Location access failed:', locationError);
                throw new Error('Could not access your location. Please enable location services.');
            }
        }
        
        userLocation = location;
        console.log('User location obtained:', location);

        const response = await fetch('{{ route("check.location.proximity") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                latitude: location.latitude,
                longitude: location.longitude
            })
        });

        const result = await response.json();
        console.log('Location check result:', result);

        const feedbackDiv = document.getElementById('location-check-feedback');
        if (feedbackDiv) feedbackDiv.remove();

        if (result.success) {
            locationCheckPassed = result.isNearHub;
            const shouldShowStatus = result.showLocationStatus !== false && result.accessType !== 'no_access';

            if (result.accessType === 'unrestricted_access') {
                // User has unrestricted access - always enable
                console.log('User has unrestricted camera access');
                locationCheckPassed = true;
                enableAttendanceButtons();
                if (shouldShowStatus) {
                    showLocationStatus(result.message || 'You have unrestricted access. Camera attendance is available.', 'success');
                }
            } else if (result.isNearHub) {
                // User is near assigned hub
                if (shouldShowStatus) {
                    showLocationStatus(result.message, 'success');
                }
                enableAttendanceButtons();

                if (result.nearbyHubs?.length > 0) {
                    let hubInfo = 'Nearby hubs:\n';
                    result.nearbyHubs.forEach(hub => {
                        hubInfo += `• ${hub.name} (${hub.code}) - ${hub.distance}m away - Status: ${hub.status}\n`;
                    });
                    console.log(hubInfo);
                }
            } else {
                // User is not near hub or has no access
                if (shouldShowStatus && result.message) {
                    showLocationStatus(result.message, 'error');
                }
                disableAttendanceButtons();

                if (shouldShowStatus && result.allDistances) {
                    console.log('All hub distances:', result.allDistances);
                }
            }

            if (result.accessType === 'no_access') {
                console.log('User has no camera access');
                disableAttendanceButtons();
                showLocationStatus('Camera access not available for your account.', 'error');
            }

        } else {
            throw new Error(result.message || 'Location verification failed');
        }

    } catch (error) {
        console.error('Location check failed:', error);

        const feedbackDiv = document.getElementById('location-check-feedback');
        if (feedbackDiv) feedbackDiv.remove();

        const attendanceButtons = document.querySelectorAll('[data-attendance-btn]');
        if (attendanceButtons.length > 0) {
            showLocationStatus('Location check failed: ' + error.message, 'error');
        }

        disableAttendanceButtons();
        locationCheckPassed = false;
    }
}

document.addEventListener('DOMContentLoaded', async function() {
    console.log('=== PAGE LOAD START ===');
    
    const timeInBtn = document.querySelector('[data-target="#timeIn"]');
    const timeOutBtn = document.querySelector('[data-target="#timeOut"]');
    
    if (timeInBtn) timeInBtn.setAttribute('data-attendance-btn', 'true');
    if (timeOutBtn) timeOutBtn.setAttribute('data-attendance-btn', 'true');
    
    console.log('Buttons found:', { timeInBtn: !!timeInBtn, timeOutBtn: !!timeOutBtn });
    
    // Initial loading state
    showAttendanceLoading('Checking access...');
    
    try {
        console.log('Step 1: Checking for immediate access...');
        const hasImmediateAccess = await checkForImmediateAccess();
        
        console.log('Step 1 Result:', hasImmediateAccess);
        
        if (!hasImmediateAccess) {
            console.log('Step 2: No immediate access, checking location proximity...');
            await checkLocationProximity();
        } else {
            console.log('Step 2: Skipped - user has immediate access');
        }
        
        console.log('=== PAGE LOAD COMPLETE ===');
        console.log('Final locationCheckPassed state:', locationCheckPassed);
        
    } catch (error) {
        console.error('=== PAGE LOAD ERROR ===', error);
        disableAttendanceButtons();
        showLocationStatus('Initialization failed: ' + error.message, 'error');
    }
    
    // Make functions globally available
    window.showDetailedLocationCheck = showDetailedLocationCheck;
    window.getLocation = getLocation;
    window.checkLocationProximity = checkLocationProximity;
    
    console.log('Global functions registered:', {
        showDetailedLocationCheck: typeof window.showDetailedLocationCheck,
        getLocation: typeof window.getLocation,
        checkLocationProximity: typeof window.checkLocationProximity
    });
});

function showLocationStatus(message, type) {
    if (!message?.trim()) return;

    const existingStatus = document.getElementById('location-status');
    if (existingStatus) existingStatus.remove();

    const statusDiv = document.createElement('div');
    statusDiv.id = 'location-status';
    statusDiv.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show`;

    let icon = 'info-circle';
    if (type === 'error') icon = 'exclamation-triangle';
    if (type === 'success') icon = 'check-circle';

    statusDiv.innerHTML = `
        <i class="fas fa-${icon}"></i>
        <strong>Hub Access:</strong> ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;

    const attendanceContainer = document.querySelector('.attendance-buttons') || document.querySelector('[data-attendance-container]');
    if (attendanceContainer) {
        attendanceContainer.insertBefore(statusDiv, attendanceContainer.firstChild);
    }

    if (type !== 'error') {
        setTimeout(() => {
            if (document.getElementById('location-status')) {
                statusDiv.style.transition = 'opacity 0.5s';
                statusDiv.style.opacity = '0';
                setTimeout(() => statusDiv.remove(), 500);
            }
        }, 8000);
    }
}

function enableAttendanceButtons() {
    console.log('=== ENABLING ATTENDANCE BUTTONS ===');
    
    const attendanceButtons = document.querySelectorAll('[data-attendance-btn]');
    console.log('Found attendance buttons:', attendanceButtons.length);
    
    attendanceButtons.forEach((button, index) => {
        console.log(`Enabling button ${index + 1}:`, button);
        
        button.disabled = false;
        button.style.opacity = '1';
        button.style.cursor = 'pointer';
        
        const originalText = button.getAttribute('data-original-text');
        if (originalText) {
            button.innerHTML = originalText;
            button.removeAttribute('data-original-text');
        }
        
        const playIcon = button.querySelector('i');
        if (playIcon) {
            if (button.getAttribute('title') === 'Time In') {
                playIcon.className = 'ti-control-play';
                button.classList.add('btn-success');
                button.classList.remove('btn-danger', 'btn-secondary', 'btn-info');
            }
            else if (button.getAttribute('title') === 'Time Out') {
                playIcon.className = 'ti-control-pause';
                button.classList.add('btn-danger');
                button.classList.remove('btn-success', 'btn-secondary', 'btn-info');
            }
        }
        
        button.classList.remove('btn-secondary', 'btn-disabled', 'btn-info');
    });
    
    console.log('Camera attendance buttons enabled - ready to use!');
}

function showAttendanceLoading(message = '') {
    const attendanceButtons = document.querySelectorAll('[data-attendance-btn]');
    attendanceButtons.forEach(button => {
        button.disabled = true;
        button.style.opacity = '0.7';
        button.style.cursor = 'wait';
        
        const originalContent = button.innerHTML;
        button.setAttribute('data-original-text', originalContent);
        
        button.innerHTML = `
            <i class="fas fa-spinner fa-spin"></i> 
            <span>${message}</span>
        `;
        
        button.classList.add('btn-success');
        button.classList.remove('btn-secondary', 'btn-info');
    });
}

function disableAttendanceButtons() {
    console.log('=== DISABLING ATTENDANCE BUTTONS ===');
    
    const attendanceButtons = document.querySelectorAll('[data-attendance-btn]');
    console.log('Found attendance buttons to disable:', attendanceButtons.length);
    
    attendanceButtons.forEach((button, index) => {
        console.log(`Disabling button ${index + 1}:`, button);
        
        button.disabled = true;
        button.style.opacity = '0.5';
        button.style.cursor = 'not-allowed';

        const originalText = button.getAttribute('data-original-text');
        if (originalText) {
            button.innerHTML = originalText;
            button.removeAttribute('data-original-text');
        }
        
        const playIcon = button.querySelector('i');
        if (playIcon) {
            playIcon.className = 'ti-control-play';
        }
        
        button.classList.add('btn-secondary');
        button.classList.remove('btn-success', 'btn-info', 'btn-danger');
    });
    
    console.log('Camera attendance buttons disabled');
}

function getLocation() {
    if (!locationCheckPassed) {
        const attendanceButtons = document.querySelectorAll('[data-attendance-btn]');
        if (attendanceButtons.length > 0) {
            Swal.fire({
                title: 'Not in Range',
                text: 'You need to be within hub range to use attendance features.',
                icon: 'error',
                confirmButtonText: 'OK',
                timer: 3000
            });
        }
        return;
    }

    proceedWithAttendance();
}

function proceedWithAttendance() {
    console.log('Opening camera directly - location verified');
    
    @if($attendance_now != null)
        $('#timeOut').modal('show');
    @else
        $('#timeIn').modal('show');
    @endif
}

async function showDetailedLocationCheck() {
    try {
        Swal.fire({
            title: 'Getting Your Location',
            html: 'Please wait while we determine your current location...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        let location = getCachedLocation();
        if (!location) {
            location = await getCurrentLocation();
            setCachedLocation(location);
        }
        userLocation = location;
        
        console.log('User location obtained for detailed check:', location);
        
        Swal.update({
            title: 'Checking Hub Proximity',
            html: 'Checking if you are near any hub locations...'
        });
        
        const response = await fetch('{{ route("check.location.proximity") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                latitude: location.latitude,
                longitude: location.longitude
            })
        });
        
        const result = await response.json();
        
        Swal.close();
        
        console.log('Detailed location check result:', result);
        
        if (result.success) {
            await showLocationSweetAlert(location, result);
        } else {
            throw new Error(result.message);
        }
        
    } catch (error) {
        console.error('Detailed location check failed:', error);
        
        Swal.close();

        await Swal.fire({
            title: 'Location Error',
            html: `
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                    <p><strong>Error:</strong> ${error.message}</p>
                    <div class="mt-3 alert alert-danger">
                        Please make sure location services are enabled and try again.
                    </div>
                </div>
            `,
            icon: 'error',
            confirmButtonText: 'Try Again',
            confirmButtonColor: '#dc3545'
        });
    }
}

function getAccurateLocation() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            return reject(new Error("Geolocation is not supported by your browser"));
        }

        navigator.geolocation.getCurrentPosition(
            position => {
                resolve({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy
                });
            },
            error => reject(error),
            {
                enableHighAccuracy: true,
                timeout: 8000,
                maximumAge: 0
            }
        );
    });
}

async function showLocationSweetAlert(userLocation, proximityResult) {
    let readableAddress = 'Fetching address...';
    try {
        const geocodeResponse = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${userLocation.latitude}&longitude=${userLocation.longitude}&localityLanguage=en`);
        if (geocodeResponse.ok) {
            const geocodeData = await geocodeResponse.json();
            readableAddress = geocodeData.display_name || geocodeData.locality || geocodeData.city || 'Address not found';
        }
    } catch {
        readableAddress = 'Unable to get address';
    }

    let hubsHtml = '';
    let alertType = 'error';
    let alertTitle = 'Location Check';
    let alertText = 'Location verification completed.';

    if (proximityResult.isNearHub && proximityResult.nearbyHubs.length > 0) {
        alertType = 'success';
        alertTitle = 'Location Verified!';
        alertText = 'You are within range of your assigned hub location. Attendance is available.';
        hubsHtml = '<div class="mt-3"><strong>Your Assigned Hub (In Range):</strong><ul class="text-left mt-2">';
        proximityResult.nearbyHubs.forEach(hub => {
            hubsHtml += `<li><strong>${hub.name}</strong> (${hub.code})<br>
                         <small class="text-success">✓ ${hub.distance}m away • Status: ${hub.status}</small><br>
                         <small class="text-info">Coordinates: ${proximityResult.assignedHub?.latitude || 'N/A'}°, ${proximityResult.assignedHub?.longitude || 'N/A'}°</small></li>`;
        });
        hubsHtml += '</ul></div>';
    } else if (proximityResult.assignedHub) {
        const hub = proximityResult.assignedHub;
        if (hub.status !== 'Open') {
            alertType = 'warning';
            alertTitle = 'Hub Closed';
            alertText = 'Your assigned hub is currently closed.';
        } else {
            alertType = 'warning';
            alertTitle = 'Move Closer to Hub';
            alertText = `You need to move within ${proximityResult.radius}m of your assigned hub to use attendance features.`;
        }
        hubsHtml = '<div class="mt-3"><strong>Your Assigned Hub:</strong><ul class="text-left mt-2">';
        hubsHtml += `<li><strong>${hub.name}</strong> (${hub.code})<br>
                     <small class="text-warning">⚠ ${hub.distance}m away • Status: ${hub.status}</small><br>
                     <small class="text-muted">${hub.address}</small><br>
                     <small class="text-info">Hub Coordinates: ${hub.latitude || 'N/A'}°, ${hub.longitude || 'N/A'}°</small></li>`;
        hubsHtml += '</ul></div>';
        hubsHtml += `<div class="mt-2 alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Required Distance:</strong> Within ${proximityResult.radius}m<br>
                        <strong>Current Distance:</strong> ${hub.distance}m<br>
                        <strong>Need to move:</strong> ${Math.max(0, hub.distance - proximityResult.radius)}m closer
                     </div>`;
    } else {
        alertType = 'success';
        alertTitle = 'No Assigned Hub';
        alertText = 'No hub has been assigned to your account.';
        hubsHtml = '<div class="mt-3 alert alert-danger">⚠ Please contact HR to assign a hub location to your account.</div>';
    }

    const htmlContent = `
        <div style="font-size: 14px; color: #333;">
        <div class="text-center mb-3">
            <i class="fas fa-map-marker-alt fa-2x" style="color: ${proximityResult.isNearHub ? '#28a745' : '#ffc107'};"></i>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 16px; justify-content: space-between;">
            <div style="flex: 1; min-width: 260px; background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #ddd;">
            <h6 style="margin-bottom: 10px;"><i class="fas fa-user-circle text-success"></i> Your Location</h6>
            <p><strong>Address:</strong><br>${readableAddress}</p>
            <p><strong>Coordinates:</strong><br>${userLocation.latitude.toFixed(6)}°, ${userLocation.longitude.toFixed(6)}°</p>
            <p><strong>Accuracy:</strong> ±${userLocation.accuracy || 'Unknown'}m</p>
            </div>

            ${proximityResult.assignedHub ? `
            <div style="flex: 1; min-width: 260px; background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
            <h6 style="margin-bottom: 10px;"><i class="fas fa-building"></i> Assigned Hub</h6>
            <p><strong>${proximityResult.assignedHub.name}</strong> (${proximityResult.assignedHub.code})</p>
            <p><strong>Status:</strong> <span style="color: ${proximityResult.assignedHub.status === 'Open' ? '#28a745' : '#dc3545'};">${proximityResult.assignedHub.status}</span></p>
            <p><strong>Distance:</strong> ${proximityResult.assignedHub.distance}m</p>
            <p><strong>Coordinates:</strong><br>${proximityResult.assignedHub.latitude.toFixed(6)}°, ${proximityResult.assignedHub.longitude.toFixed(6)}°</p>
            </div>` : ''}
        </div>

        <div style="margin-top: 20px; margin-bottom: 20px;">
            <div id="hubMap" style="width: 100%; height: 300px; border-radius: 8px; border: 1px solid #ccc;"></div>
        </div>

        <div style="background: #f8f9fa; border-radius: 8px; padding: 15px; border: 1px solid #e0e0e0; margin-bottom: 15px;">
            <h6 style="margin-bottom: 15px;"><i class="fas fa-info-circle"></i> Map Legend & Distance Info</h6>
            
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center;">
                <img src="{{ asset('images/location.png') }}" style="width: 24px; height: 24px; margin-right: 8px;" alt="Hub Location">
                <span><strong>Hub Location</strong> - Your assigned work hub</span>
            </div>
            <div style="display: flex; align-items: center;">
                <div style="width: 24px; height: 24px; background-color: #4CAF50; color: white; font-weight: bold; font-size: 12px; text-align: center; line-height: 24px; border-radius: 50%; margin-right: 8px;">Y</div>
                <span><strong>Your Location</strong> - Current GPS position</span>
            </div>
            </div>

            <div style="margin-top: 12px;">
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                <span style="display:inline-block; width: 20px; height: 12px; background-color: #FF0000; opacity: 0.4; border-radius: 2px; margin-right: 8px;"></span>
                <span><strong>Attendance Zone:</strong> ${proximityResult.radius}m radius from hub</span>
            </div>
            </div>

            ${proximityResult.assignedHub ? `
            <div style="border-top: 1px solid #ddd; padding-top: 12px; margin-top: 12px;">
            <p><i class="fas fa-map-marker-alt text-danger"></i> <strong>Hub Coordinates:</strong> ${proximityResult.assignedHub.latitude.toFixed(6)}°, ${proximityResult.assignedHub.longitude.toFixed(6)}°</p>
            <p><i class="fas fa-ruler-horizontal text-muted"></i> <strong>Current Distance:</strong> ${proximityResult.assignedHub.distance}m from hub</p>

            ${!proximityResult.isNearHub && proximityResult.assignedHub.status === 'Open' ? `
                <div style="background:#fff3cd; padding:10px; border-left:4px solid #ffc107; border-radius:4px; margin-top:10px;">
                <i class="fas fa-walking" style="margin-right:8px; color:#856404;"></i>
                Move ${Math.max(0, proximityResult.assignedHub.distance - proximityResult.radius)}m closer to enable attendance
                </div>
                <div style="margin-top: 8px;">
                <i class="fas fa-clock text-info"></i> Estimated Walk Time: ~${Math.ceil(Math.max(0, proximityResult.assignedHub.distance - proximityResult.radius) / 80)} minutes
                </div>
            ` : ''}
            ${proximityResult.isNearHub ? `
                <div style="background:#d1edff; padding:10px; border-left:4px solid #007bff; border-radius:4px; margin-top:10px;">
                <i class="fas fa-check-circle text-primary"></i> Within attendance zone – Camera access enabled!
                </div>
            ` : ''}
            </div>
            ` : ''}
        </div>

        ${proximityResult.isNearHub ? 
            `<div class="alert alert-success text-center" style="border-radius: 8px;">✓ Camera attendance is now accessible!</div>` : 
            proximityResult.assignedHub ? 
            `<div class="alert alert-warning text-center" style="border-radius: 8px;">⚠ Move closer to your assigned hub for camera access.</div>` :
            `<div class="alert alert-danger text-center" style="border-radius: 8px;">✗ No hub assigned to your account.</div>`}
        </div>

    `;

    await Swal.fire({
        title: alertTitle,
        html: htmlContent,
        icon: alertType,
        showConfirmButton: true,
        confirmButtonText: proximityResult.isNearHub ? 'Great!' : 'Got it',
        confirmButtonColor: proximityResult.isNearHub ? '#28a745' : alertType === 'warning' ? '#ffc107' : '#dc3545',
        width: '800px',
        didOpen: () => {
            let mapCenter;
            if (proximityResult.assignedHub) {
                mapCenter = { 
                    lat: parseFloat(proximityResult.assignedHub.latitude), 
                    lng: parseFloat(proximityResult.assignedHub.longitude) 
                };
            } else {
                mapCenter = { 
                    lat: userLocation.latitude, 
                    lng: userLocation.longitude 
                };
            }

            const map = new google.maps.Map(document.getElementById('hubMap'), {
                center: mapCenter,
                zoom: 18,
                mapTypeId: 'roadmap',
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
                mapTypeControl: false
            });

            const userIcon = {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: '#4CAF50',
                fillOpacity: 1,
                strokeColor: '#2E7D32',
                strokeWeight: 2,
                scale: 8
            };

            const userMarker = new google.maps.Marker({
                position: { lat: userLocation.latitude, lng: userLocation.longitude },
                map: map,
                icon: userIcon,
                title: `Your Current Location`,
                animation: google.maps.Animation.DROP
            });

            const userInfoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 8px; min-width: 200px;">
                        <h6 style="margin: 0 0 8px 0; color: #4CAF50;"><i class="fas fa-user-circle"></i> Your Location</h6>
                        <div style="font-size: 12px; line-height: 1.4;">
                            <strong>Coordinates:</strong><br>
                            Lat: ${userLocation.latitude.toFixed(6)}°<br>
                            Lng: ${userLocation.longitude.toFixed(6)}°<br>
                            <strong>Accuracy:</strong> ±${userLocation.accuracy || 'Unknown'}m<br>
                            <strong>Address:</strong><br>
                            <span style="color: #666;">${readableAddress}</span>
                        </div>
                    </div>
                `
            });

            userMarker.addListener('click', () => {
                userInfoWindow.open(map, userMarker);
            });

            if (proximityResult.assignedHub) {
                const hubLat = parseFloat(proximityResult.assignedHub.latitude);
                const hubLng = parseFloat(proximityResult.assignedHub.longitude);
                const distance = proximityResult.assignedHub.distance;
                const isInRange = proximityResult.isNearHub;
                const distanceToMove = Math.max(0, distance - proximityResult.radius);

                const hubIcon = {
                    url: '{{ asset("images/location.png") }}',
                    scaledSize: new google.maps.Size(50, 50),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(20, 40)
                };

                const hubMarker = new google.maps.Marker({
                    position: { lat: hubLat, lng: hubLng },
                    map: map,
                    icon: hubIcon,
                    title: `${proximityResult.assignedHub.name} Hub`,
                    animation: google.maps.Animation.BOUNCE
                });

                const hubInfoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 8px; min-width: 250px;">
                            <h6 style="margin: 0 0 8px 0; color: #FF4444;"><i class="fas fa-building"></i> ${proximityResult.assignedHub.name}</h6>
                            <div style="font-size: 12px; line-height: 1.5;">
                                <strong>Hub Code:</strong> ${proximityResult.assignedHub.code}<br>
                                <strong>Status:</strong> <span style="color: ${proximityResult.assignedHub.status === 'Open' ? '#4CAF50' : '#f44336'};">${proximityResult.assignedHub.status}</span><br>
                                <strong>Coordinates:</strong><br>
                                Lat: ${hubLat.toFixed(6)}°<br>
                                Lng: ${hubLng.toFixed(6)}°<br>
                                <hr style="margin: 8px 0;">
                                <strong>Distance Analysis:</strong><br>
                                Current: ${distance}m away<br>
                                Required: Within ${proximityResult.radius}m<br>
                                ${!isInRange && distanceToMove > 0 ? 
                                    `<span style="color: #FF8800;"><strong>Move closer:</strong> ${distanceToMove}m<br><strong>Walk time:</strong> ~${Math.ceil(distanceToMove / 80)} min</span>` : 
                                    isInRange ? 
                                        `<span style="color: #4CAF50;"><strong>✓ Within range!</strong> Attendance enabled</span>` :
                                        `<span style="color: #f44336;">Hub is closed</span>`
                                }<br>
                                <hr style="margin: 8px 0;">
                                <strong>Address:</strong><br>
                                <span style="color: #666;">${proximityResult.assignedHub.address || 'N/A'}</span>
                            </div>
                        </div>
                    `
                });

                hubMarker.addListener('click', () => {
                    hubInfoWindow.open(map, hubMarker);
                });

                const mainCircle = new google.maps.Circle({
                    strokeColor: isInRange ? "#4CAF50" : "#FF4444",
                    strokeOpacity: 0.8,
                    strokeWeight: 3,
                    fillColor: isInRange ? "#4CAF50" : "#FF4444",
                    fillOpacity: 0.15,
                    map: map,
                    center: { lat: hubLat, lng: hubLng },
                    radius: proximityResult.radius
                });

                const distanceLine = new google.maps.Polyline({
                    path: [
                        { lat: userLocation.latitude, lng: userLocation.longitude },
                        { lat: hubLat, lng: hubLng }
                    ],
                    geodesic: true,
                    strokeColor: isInRange ? '#4CAF50' : '#FF8800',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    icons: [{
                        icon: {
                            path: google.maps.SymbolPath.FORWARD_OPEN_ARROW,
                            scale: 3,
                            fillColor: isInRange ? '#4CAF50' : '#FF8800',
                            fillOpacity: 1
                        },
                        offset: '50%'
                    }]
                });

                distanceLine.setMap(map);

                const midLat = (userLocation.latitude + hubLat) / 2;
                const midLng = (userLocation.longitude + hubLng) / 2;

                const distanceLabel = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 4px; background: rgba(0,0,0,0.8); color: white; font-size: 11px; border-radius: 4px; text-align: center;">
                            <strong>${distance}m</strong><br>
                            ${!isInRange && distanceToMove > 0 ? 
                                `<span style="color: #FFD700;">Move ${distanceToMove}m closer</span>` : 
                                `<span style="color: #90EE90;">✓ In range</span>`
                            }
                        </div>
                    `,
                    position: { lat: midLat, lng: midLng },
                    disableAutoPan: true,
                    pixelOffset: new google.maps.Size(0, -10)
                });

                distanceLabel.open(map);

                const distance_num = proximityResult.assignedHub.distance;
                let zoomLevel;
                if (distance_num <= 50) {
                    zoomLevel = 19;
                } else if (distance_num <= 100) {
                    zoomLevel = 18;
                } else if (distance_num <= 200) {
                    zoomLevel = 17;
                } else if (distance_num <= 500) {
                    zoomLevel = 16;
                } else {
                    zoomLevel = 15;
                }
                
                map.setCenter({ lat: hubLat, lng: hubLng });
                map.setZoom(zoomLevel);

            } else {
                map.setCenter({ lat: userLocation.latitude, lng: userLocation.longitude });
                map.setZoom(18);
            }
        }
    });
}

async function checkLocationAndShowAlert() {
    try {
        const userLocation = await getAccurateLocation();
        const proximityResult = {
            isNearHub: false,
            radius: 10,
            assignedHub: {
                latitude: "14.599512",
                longitude: "120.984222",
                name: "Main Hub",
                code: "HUB001",
                distance: 150,
                status: "Open",
                address: "Sample Street, Manila"
            },
            nearbyHubs: []
        };

        await showLocationSweetAlert(userLocation, proximityResult);
    } catch (err) {
        Swal.fire("Error", "Could not get your location: " + err.message, "error");
    }
}

const locationAlertStyles = `
<style>
.location-sweet-alert {
    font-family: inherit;
}

.location-sweet-alert-content {
    text-align: left;
}

.location-sweet-alert .location-info,
.location-sweet-alert .proximity-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin: 10px 0;
}

.location-sweet-alert ul {
    margin: 0;
    padding-left: 20px;
}

.location-sweet-alert li {
    margin-bottom: 8px;
    list-style-type: disc;
}

.location-sweet-alert .alert {
    padding: 10px;
    border-radius: 5px;
    margin: 0;
}

.location-sweet-alert .alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.location-sweet-alert .alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
}

.location-sweet-alert .alert-info {
    background-color: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}

.location-sweet-alert .alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}
</style>
`;

if (!document.querySelector('#location-alert-styles')) {
    const styleElement = document.createElement('div');
    styleElement.id = 'location-alert-styles';
    styleElement.innerHTML = locationAlertStyles;
    document.head.appendChild(styleElement);
}

document.addEventListener('DOMContentLoaded', async function() {
    const timeInBtn = document.querySelector('[data-target="#timeIn"]');
    const timeOutBtn = document.querySelector('[data-target="#timeOut"]');
    
    if (timeInBtn) timeInBtn.setAttribute('data-attendance-btn', 'true');
    if (timeOutBtn) timeOutBtn.setAttribute('data-attendance-btn', 'true');
    
    showAttendanceLoading('');
    
    const hasImmediateAccess = await checkForImmediateAccess();
    
    if (!hasImmediateAccess) {
        await checkLocationProximity();
    }
    
    window.showDetailedLocationCheck = showDetailedLocationCheck;
    window.getLocation = getLocation;
    window.checkLocationProximity = checkLocationProximity;
    
    console.log('All functions loaded and accessible globally');
    console.log('Available functions:', {
        showDetailedLocationCheck: typeof window.showDetailedLocationCheck,
        getLocation: typeof window.getLocation,
        checkLocationProximity: typeof window.checkLocationProximity
    });
});

setInterval(async () => {
    if (locationCheckPassed) {
        const cached = getCachedLocation();
        if (cached) {
            try {
                const response = await fetch('{{ route("check.location.proximity") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: cached.latitude,
                        longitude: cached.longitude
                    })
                });
                
                const result = await response.json();
                
                if (!result.isNearHub && locationCheckPassed) {
                    locationCheckPassed = false;
                    disableAttendanceButtons();
                    showLocationStatus('You have moved out of range. Please return to your assigned hub.', 'warning');
                }
            } catch (error) {
                console.error('Periodic location check failed:', error);
            }
        } else {
            checkLocationProximity();
        }
    }
}, 300000);
</script>

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
    // Get leave balances from PHP variables (passed from controller)
    const vlBalance = {{ $vl_balance ?? 0 }};
    const slBalance = {{ $sl_balance ?? 0 }};
    
    // Show the modal with leave details
    Swal.fire({
      title: 'Leave Details',
      html: `
        <div style="font-size: 13px; max-height: 400px; overflow-y: auto;">
          <!-- Available Leave Balances Section -->
          <div style="margin-bottom: 20px;">
            <h4 style="color: #333; margin-bottom: 10px; font-size: 14px;">Available Leave Balances</h4>
            <div style="display: flex; justify-content: space-around; background-color: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
              <div style="text-align: center;">
                <strong style="color: #28a745;">VL (Vacation Leave)</strong>
                <div style="font-size: 16px; font-weight: bold; color: #28a745;" id="vl-balance">${vlBalance}</div>
              </div>
              <div style="text-align: center;">
                <strong style="color: #007bff;">SL (Sick Leave)</strong>
                <div style="font-size: 16px; font-weight: bold; color: #007bff;" id="sl-balance">${slBalance}</div>
              </div>
            </div>
          </div>

          <!-- Used Leave Details Section -->
          <div>
            <h4 style="color: #333; margin-bottom: 10px; font-size: 14px;">Used Leave History</h4>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
              <thead>
                <tr>
                  <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2; font-size: 12px;">No.</th>
                  <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2; font-size: 12px;">Type</th>
                  <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2; font-size: 12px;">Date From</th>
                  <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2; font-size: 12px;">Date To</th>
                  <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2; font-size: 12px;">Days</th>
                  <th style="border: 1px solid #ccc; padding: 6px; background-color: #f2f2f2; font-size: 12px;">Reason</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($usedLeaves as $index => $leave)
                  <tr>
                    <td style="border: 1px solid #ccc; padding: 6px; text-align: center; font-size: 11px;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #ccc; padding: 6px; text-align: center; font-size: 11px;">
                      @if($leave->leave_type == 1)
                        <span style="color: #28a745; font-weight: bold;">VL</span>
                      @elseif($leave->leave_type == 2)
                        <span style="color: #007bff; font-weight: bold;">SL</span>
                      @else
                        <span style="color: #6c757d;">Other</span>
                      @endif
                    </td>
                    <td style="border: 1px solid #ccc; padding: 6px; font-size: 11px;">{{ \Carbon\Carbon::parse($leave->date_from)->format('M d, Y') }}</td>
                    <td style="border: 1px solid #ccc; padding: 6px; font-size: 11px;">{{ \Carbon\Carbon::parse($leave->date_to)->format('M d, Y') }}</td>
                    <td style="border: 1px solid #ccc; padding: 6px; text-align: center; font-size: 11px;">
                      {{ \Carbon\Carbon::parse($leave->date_from)->diffInDays(\Carbon\Carbon::parse($leave->date_to)) + 1 }}
                    </td>
                    <td style="border: 1px solid #ccc; padding: 6px; font-size: 11px;">{{ $leave->reason ?? 'No reason provided' }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" style="padding: 10px; text-align: center; color: #6c757d; font-style: italic;">No used leaves found</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      `,
      icon: 'info',
      confirmButtonText: 'Close',
      width: '800px',
      customClass: {
        icon: 'custom-swal-icon-spacing',
        popup: 'custom-swal-popup'
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