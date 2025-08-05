@foreach($tos as $form_approval)
  <div class="modal fade" id="to-view-declined-{{ $form_approval->id }}" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declinedTOremarks">TO Remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='decline-to/{{$to->id}}' onsubmit="btnApprove.disabled = true; return true;" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="badge badge-danger mt-1">Declined</h4>
                        </div>
                        <input type="hidden" name="status" value="Declined">
                        <div class="col-md-12 form-group">
                            <label for="approval_remarks"><strong>Remarks by Immediate Supervisor:</strong></label>
                            <textarea class="form-control" name="approval_remarks" id="approval_remarks" rows="4" readonly>{{ $form_approval->approval_remarks }}</textarea>
                        </div>

                        @if(!empty($form_approval->approval_remarks2))
                            <div class="col-md-12 form-group">
                                <label for="approval_remarks2"><strong>Remarks by Head Division:</strong></label>
                                <textarea class="form-control" name="approval_remarks2" id="approval_remarks2" rows="4" readonly>{{ $form_approval->approval_remarks2 }}</textarea>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                   <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
    
@endforeach


