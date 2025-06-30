<div class="modal fade" id="view_attandance{{$date_r}}{{ $emp->employee_code }}" tabindex="-1" role="dialog" aria-labelledby="view_attandancedata" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="view_attandancedata">Attendance Logs</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                     <div class="table-responsive">
                  <table border="1" class="table table-hover table-bordered table-detailed" id='employee_attendance'>
                    <thead>
                      <tr>
                        <th>Full Name</th>
                        <th>Emp Code</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Image</th>
                        <th>Location</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                        @foreach(($emp->attendance_logs)->where('date',date('Y-m-d',strtotime($date_r))) as $attendance)
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
                                {{-- <div class=""> --}}
                                  @if($attendance->image)
                                  <a href='{{url($attendance->image)}}' target="_blank"><img style='border-radius: 0% !important;' src="{{asset($attendance->image)}}" alt="Image" class="square-img img-fluid float-left thumbnail"></a>
                                  @endif
                                  {{-- </div> --}}
                              </td>
                              @if($attendance->location == "System")
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
</div>
