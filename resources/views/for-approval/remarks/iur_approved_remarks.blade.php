<div class="modal fade" id="iur-approved-remarks-{{$form_approval->id}}" tabindex="-1" role="dialog" aria-labelledby="approvedIURremarks" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvedIURremarks">Update IUR Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='process-iur/{{$form_approval->id}}' onsubmit="btnApprove.disabled = true; return true;" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class='col-md-12 form-group'>
                            <label>Status:</label>
                            <select name="status" class="form-control" required>
                                <option value="">-- Select Status --</option>
                                <option value="Processing">Processing</option>
                                <option value="Partial">Partial</option>
                                <option value="Receive">Receive</option>
                            </select>
                        </div>
                        <div class='col-md-12 form-group'>
                            Remarks:
                            <textarea class="form-control" name="approval_remarks" id="" cols="30" rows="5" placeholder="Input Approval Remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="btnApprove" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
