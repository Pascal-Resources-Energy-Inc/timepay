@extends('layouts.header')

@section('content')
<style>
.thumbnail:hover {
      transform: scale(4);
    }

.pagination {
      margin-bottom: 0;
    }

.attendance-filter-panel {
      padding: 1.25rem;
      margin-bottom: 1.25rem;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: .75rem;
    }

.attendance-filter-panel .form-group {
      margin-bottom: 0;
    }

.attendance-filter-panel .form-label {
      display: block;
      margin-bottom: .5rem;
      font-size: .875rem;
      font-weight: 600;
      color: #475569;
    }

.attendance-filter-panel .form-control {
      min-height: 42px;
    }

.attendance-filter-actions {
      display: flex;
      align-items: flex-end;
    }

.attendance-filter-actions .btn {
      width: 100%;
      min-height: 42px;
    }

.attendance-report-toolbar,
.attendance-table-toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

.attendance-report-toolbar {
      padding-bottom: 1rem;
      margin-bottom: 1rem;
      border-bottom: 1px solid #e2e8f0;
    }

.attendance-report-toolbar h6 {
      margin: 0 0 .25rem;
      font-weight: 600;
    }

.attendance-report-toolbar p {
      margin: 0;
      color: #64748b;
      font-size: .875rem;
    }

.attendance-table-toolbar {
      padding: .75rem 0;
    }

.attendance-table-toolbar .dt-buttons {
      display: flex;
      gap: .5rem;
      margin: 0;
    }

.attendance-table-toolbar .dt-button {
      padding: .45rem .85rem !important;
      margin: 0 !important;
      color: #334155 !important;
      background: #fff !important;
      border: 1px solid #cbd5e1 !important;
      border-radius: .375rem !important;
      box-shadow: none !important;
    }

.attendance-table-toolbar .dt-button:hover {
      color: #1d4ed8 !important;
      background: #eff6ff !important;
      border-color: #93c5fd !important;
    }

.attendance-table-toolbar .dt-search {
      display: flex;
      align-items: center;
      gap: .5rem;
      margin: 0;
      color: #475569;
      font-size: .875rem;
    }

.attendance-table-toolbar .dt-search input {
      width: 280px;
      max-width: 100%;
      min-height: 38px;
      margin: 0 !important;
      border: 1px solid #cbd5e1;
      border-radius: .375rem;
    }

@media (max-width: 767.98px) {
    .attendance-filter-panel {
      padding: 1rem;
    }

    .attendance-filter-actions .btn,
    .attendance-report-toolbar .btn {
      width: 100%;
    }

    .attendance-report-toolbar,
    .attendance-table-toolbar,
    .attendance-table-toolbar .dt-search {
      align-items: stretch;
    }

    .attendance-table-toolbar .dt-search input {
      flex: 1;
      min-width: 0;
    }
}
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
         
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title mb-1">Attendance logs</h4>
                <p class="text-muted mb-4">Review attendance records by location and date range.</p>
                <div class="attendance-filter-panel">
                  <form method='get' onsubmit='show();'  enctype="multipart/form-data">
                    <div class="row">
                      <div class='col-xl-4 col-md-6 mb-3'>
                        <div class="form-group">
                          <label class="form-label" for="location">Location</label>
                          <select id="location" data-placeholder="Select Location" class="form-control required js-example-basic-single" style='width:100%;' name='location' required>
                            <option value="">-- Select Location --</option>
                            @foreach($locations as $location)
                              <option value="{{$location->location}}" @if($loc == $location->location) selected @endif>{{$location->location}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class='col-xl-2 col-md-6 mb-3'>
                        <div class="form-group">
                          <label class="form-label" for="from">From</label>
                          <input type="date" value='{{$from_date}}' class="form-control" name="from" id="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required/>
                        </div>
                      </div>
                      <div class='col-xl-2 col-md-6 mb-3'>
                        <div class="form-group">
                          <label class="form-label" for="to">To</label>
                          <input type="date" value='{{$to_date}}'  class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required/>
                        </div>
                      </div>
                      <div class='col-xl-2 col-md-6 mb-3'>
                        <div class="form-group">
                          <label class="form-label" for="per-page">Per page</label>
                          <select id="per-page" class="form-control" name="per_page">
                            @foreach([30, 50, 100, 150] as $pageSize)
                              <option value="{{ $pageSize }}" {{ (int) request('per_page', 30) === $pageSize ? 'selected' : '' }}>{{ $pageSize }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class='col-xl-2 col-md-6 mb-3 attendance-filter-actions'>
                        <button type="submit" class="btn btn-primary">Apply filters</button>
                      </div>
                    </div>
                  </form>
                </div>
                <!-- <a class='btn btn-info' href="/bio-per-location-export?location={{$location}}&from={{$from_date}}&to={{$to_date}}">Export</a> -->
                <div class="table-responsive">
                  <table border="1" class="table table-hover table-bordered table-detailed" id='employee_attendance'>
                    <thead>
                      <tr>
                        <th>Full Name</th>
                        <th>Emp Code</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Attendance Source</th>
                        {{-- {{dd($loc)}} --}}
                        @if($loc === "System")
                        
                        <th>Image</th>
                        <th>Location</th>
                        @else
                        
                        <th>Location</th>
                        @endif
                        
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                          <tr>
                              <td>@if($attendance->employee){{$attendance->employee->first_name}} {{$attendance->employee->last_name}}@endif</td>
                              <td>{{$attendance->emp_code}}</td>
                              <td>{{date('Y-m-d',strtotime($attendance->datetime))}}</td>
                              <td>{{date('h:i A',strtotime($attendance->datetime))}}</td>
                              <td>
                                {{-- {{($attendance->type == 0) ? "Time In" : "Time Out"}} --}}

                                @if($attendance->type == 0)
                                Time In
                                @elseif($attendance->type == 1)
                                Time Out
                                @elseif($attendance->type == 4)
                                Break Out
                                @elseif($attendance->type == 5)
                                Break In
                                @endif
                              </td>
                              <td>
                                  {{ $attendance->location == 'System' ? 'Web' : 'Biometrics' }}
                              </td>
                              
                              {{-- <td>{{$attendance->ip_address}}</td> --}}
                              @if($loc == "System")
                        
                              <td>
                                {{-- <div class=""> --}}
                                  @if($attendance->image)
                                  <a href='{{url($attendance->image)}}' target="_blank"><img style='border-radius: 0% !important;' src="{{asset($attendance->image)}}" alt="Image" class="square-img img-fluid float-left thumbnail"></a>
                                  @endif
                                  {{-- </div> --}}
                              </td>
                              <td><a href='https://maps.google.com/?q={{$attendance->lat}},{{$attendance->long}}' target="_blank">{{$attendance->location_maps}}</a></td>
                              @else
                              
                              <td>{{$attendance->ip_address}}</td>
                              @endif
                          </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
                @if($attendances->total())
                  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between border-top mt-3 pt-3">
                    <p class="text-muted mb-3 mb-md-0">
                      Showing {{ $attendances->firstItem() }}–{{ $attendances->lastItem() }} of {{ $attendances->total() }} attendance records
                    </p>
                    @if($attendances->hasPages())
                      <div>
                        {{ $attendances->links() }}
                      </div>
                    @endif
                  </div>
                @endif
              </div>
            </div>
          </div>
        
        </div>
    </div>
</div>
<!-- DataTables CSS and JS includes -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

<script>
  $(document).ready(function() {
    new DataTable('.table-detailed', {
      // pagelenth:25,
      paginate:false,
      dom: '<"attendance-table-toolbar"<"attendance-table-search"f><"attendance-table-actions"B>>rt',
      buttons: [
          'copy', 'excel'
      ],
      columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
      order: [] 
    });
  });
</script>
<script>
    function get_min(value)
    {
      document.getElementById("to").min = value;
    }
</script>
@endsection
