<!-- Modal -->
<div class="modal fade" id="view_to{{ $to->id }}" tabindex="-1" role="dialog" aria-labelledby="viewTOslabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewTOslabel">View Travel Order</h5>
        <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method='POST' action='view-to/{{ $to->id }}' onsubmit='show()' enctype="multipart/form-data">
        @csrf        
        <div class="modal-body text-right">
          <div class="form-group row">
            <div class='col-md-2'>
              Approver 
            </div>
            <div class='col-md-10 text-left'>
              @foreach($to->approver as $approver)
                {{$approver->approver_info->name}}<br>
              @endforeach
            </div>
          </div>
          <div class="form-group row">
            <div class='align-self-center col-md-2 text-right'>
              Date
            </div>
            <div class='col-md-4'>
              <input type="date" name='applied_date' class="form-control" value="{{ $to->applied_date }}" disabled>
            </div>
          </div>
          <div class="form-group row">
            <div class='align-self-center col-md-2 text-right'>
              Time In
            </div>
            <div class='col-md-4'>
              <input type="datetime" name='date_from' class="form-control" value="{{ date('h:i A', strtotime($to->date_from)) }}" disabled>
            </div>
            <div class='align-self-center col-md-2 text-right'>
              Time Out
            </div>
            <div class='col-md-4'>
              <input type="datetime" name='date_to' class="form-control" value="{{ date('h:i A', strtotime($to->date_to)) }}" disabled>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
              Destination
            </div>
            <div class='col-md-10'>
              <input type='text' name='destination' class="form-control" value="{{ $to->destination }}" disabled>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
              Remarks
            </div>
            <div class='col-md-10'>
              <textarea name='remarks' class="form-control" rows='4' disabled> {{ $to->approval_remarks }}</textarea>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
