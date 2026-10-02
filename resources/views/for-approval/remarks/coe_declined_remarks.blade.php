<div class="modal fade" id="coe-declined-remarks-{{$coe->id}}" tabindex="-1" role="dialog" aria-labelledby="declinedCOEremarks" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="declinedCOEremarks">Decline this COE Request?</h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='decline-coe/{{$coe->id}}' onsubmit="btnApprove.disabled = true; return true;" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="status" value="Declined">
                        <div class='col-md-12 form-group'>
                            <span>Remarks: <span class="text-danger">*</span></span>
                            <textarea class="form-control" name="approval_remarks" id="" cols="30" rows="5" placeholder="Give valid reason for declining this request." required></textarea>
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
