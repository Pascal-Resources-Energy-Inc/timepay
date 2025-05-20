@extends('layouts.header')

@section('content')
<style>
.thumbnail:hover {
      transform: scale(4);
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
         
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Attendances</h4>
                <p class="card-description">
                  <form method='get' onsubmit='show();'  enctype="multipart/form-data">
                  <div class=row>
                    <div class='col-md-3'>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">Location</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Select Location" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='location' required>
                                <option value="">-- Select Location --</option>
                                @foreach($locations as $location)
                                    <option value="{{$location->location}}" @if($loc == $location->location) selected @endif>{{$location->location}}</option>
                                @endforeach
                              </select>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-3'>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">From</label>
                        <div class="col-sm-8">
                          <input type="date" value='{{$from_date}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required/>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-3'>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">To</label>
                        <div class="col-sm-8">
                          <input type="date" value='{{$to_date}}'  class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required/>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-3'>
                      <button type="submit" class="btn btn-primary mb-2">Submit</button>
                    </div>
                  </div>
                  </form>
                </p>
                @if($from_date)
                    <a class='btn btn-info mb-2' href="/bio-per-location-export?location={{$location}}&from={{$from_date}}&to={{$to_date}}">Export</a>
                @endif
                <div class="table-responsive">
                  <table border="1" class="table table-hover table-bordered table-detailed" id='employee_attendance'>
                    <thead>
                      <tr>
                        <th>Full Name</th>
                        <th>Emp Code</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
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
      dom: 'Bfrtip',
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
