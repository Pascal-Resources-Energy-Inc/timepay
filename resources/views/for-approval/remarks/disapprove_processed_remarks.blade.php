<div class="modal fade" id="mta-disapproved-remarks-{{$mta->id}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Are you sure you want to Decline this Monetized Transportation Allowance?
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="POST" action="{{ route('mta.disapprove', $mta->id) }}">
                @csrf

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="badge badge-danger">Disapproved</span>
                        </div>

                        <input type="hidden" name="status" value="Declined">

                        <div class="col-md-12 form-group">
                            <label>Remarks:</label>
                            <textarea class="form-control"
                                name="approval_remarks"
                                rows="5"
                                placeholder="Input Approval Remarks"
                                required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Decline</button>
                </div>
            </form>

        </div>
    </div>
</div>