<div class="modal fade" id="iur-received-remarks-{{$form_approval->id}}" tabindex="-1" role="dialog" aria-labelledby="receivedIURremarks" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receivedIURremarks">Mark this request as Released?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='receive-iur/{{$form_approval->id}}' onsubmit="btnApprove.disabled = true; return true;" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="badge badge-success mt-1">Released</h4>
                        </div>
                        <div class='col-md-12 form-group'>
                            Remarks:
                            <textarea class="form-control" name="approval_remarks" id="" cols="30" rows="5" placeholder="Enter Remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="btnApprove" class="btn btn-success">Mark as Released</button>
                </div>
            </form>
        </div>
    </div>
</div>
