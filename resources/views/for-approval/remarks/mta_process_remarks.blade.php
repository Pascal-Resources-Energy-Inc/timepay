<div class="modal fade" id="mta-process-remarks-{{$mta->id}}" tabindex="-1" role="dialog" aria-labelledby="processMtaRemarks" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="processMtaRemarks">Are you sure you want to Process this Monetized Transportation Allowance?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='process-mta/{{$mta->id}}' onsubmit="show()" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="badge badge-info mt-1">For Process</h4>
                        </div>
                        <input type="hidden" name="payment_status" value="For Processing">
                        <div class='col-md-12 form-group'>
                            Remarks:
                            <textarea class="form-control" name="payment_remarks" id="" cols="30" rows="5" placeholder="Input Process Remarks"></textarea>
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
