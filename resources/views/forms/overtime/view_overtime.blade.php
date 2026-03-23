<div class="modal fade" id="view_overtime{{ $overtime->id }}" tabindex="-1" role="dialog" aria-labelledby="viewOt" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewOt">View Overtime</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>    
      <div class="modal-body">
        <div class="form-group row">
          <div class='col-md-2'>Approver:</div>
          <div class='col-md-9'>
            @foreach($all_approvers as $approvers)
              {{$approvers->approver_info->name}}<br>
            @endforeach
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="ot_date" class="form-label">Date</label>
            <input type="date" name='ot_date' value="{{$overtime->ot_date}}" class="form-control" min='{{date('Y-m-d', strtotime("-3 days"))}}' readonly>
          </div>
          <div class="col-md-6 mb-3">
            <label for="break_hrs" class="form-label">Break (Hrs)</label>
            <input type="number" name='break_hrs' value="{{ $overtime->break_hrs ?? '0'}}" class="form-control" readonly>
          </div>
          <div class="col-md-6 mb-3">
            <label for="ot_date" class="form-label">Start Time</label>
            <input type="time" name='start_time' class="form-control" value="{{ date('H:i', strtotime($overtime->start_time)) }}" readonly>
          </div>
          <div class="col-md-6 mb-3">
            <label for="break_hrs" class="form-label">End Time</label>
            <input type="time" name='end_time' class="form-control" value="{{ date('H:i', strtotime($overtime->end_time)) }}" readonly>
          </div>
          <div class="col-md-12 mb-3">
            <label for="remarks" class="form-label">Detailed Description for Request <span class="text-danger">*</span></label>
            <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Provide detail description for this transaction." readonly>{{$overtime->remarks}}</textarea>
          </div>
        </div>
        <div class="form-group row">
          <div class='col-md-2' style="line-height: 3">Proof of OTAR:</div>
          <div class='col-md-9'>
            @if($overtime->attachment)
              <a href="{{url($overtime->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
            @endif
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>    
    </div>
  </div>
</div>