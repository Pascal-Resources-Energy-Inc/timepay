<div class="modal fade" id="iur-declined-remarks-{{$form_approval->id}}" tabindex="-1" role="dialog" aria-labelledby="declinedIURremarks" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="declinedIURremarks">Decline Request</h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='decline-iur/{{$form_approval->id}}' onsubmit="btnApprove.disabled = true; return true;" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="badge badge-danger mt-1">Declined</h4>
                        </div>
                        <input type="hidden" name="status" value="Declined">
                        <div class='col-md-12 form-group'>
                            <span>Remarks: <span class="text-danger">*</span></span>
                            <textarea class="form-control" name="approval_remarks" id="" cols="30" rows="5" placeholder="State the reason for declining the request" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                    <button type="submit" name="btnApprove" class="btn btn-danger">Decline</button>
                </div>
            </form>
        </div>
    </div>
</div>
