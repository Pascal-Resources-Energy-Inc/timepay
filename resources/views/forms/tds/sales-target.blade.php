<div class="modal fade" id="setSalesTarget" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ route('tds.update-target') }}" method="POST" id="salesTargetForm">
        @csrf
        <div class="modal-header text-black">
          <h5 class="modal-title">Set Employee Sales Target</h5>
          <button type="button" class="close text-white" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="type">Type <span class="text-danger">*</span></label>
                <select class="form-control select2" name="type" id="type" required>
                  <option value="">-- Select Type --</option>
                  <option value="New">New</option>
                  <option value="Existing">Existing</option>
                </select>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="employee_select">Employee <span class="text-danger">*</span></label>
                <select class="form-control select2-employee" 
                        name="user_id" 
                        id="employee_select" 
                        required>
                  <option value="">-- Type to search employee --</option>
                </select>
                <small class="form-text text-muted">Start typing to search for employees (minimum 2 characters)</small>
              </div>
            </div>
            <div class="col-lg-6" id="show_started" style="display: none">
              <div class="form-group">
                <label for="date_started">Date of Joining <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="date_started" id="date_started" required>
              </div>
            </div>
            <div class="col-lg-6" id="show_prorate" style="display: none">
              <div class="form-group">
                <label for="prorate_amount">Prorate Target Amount <span class="text-danger">*</span></label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">₱</span>
                  </div>
                  <input type="number" class="form-control" name="prorate_amount" id="prorate_amount" value="" readonly>
                </div>
                <small class="form-text text-muted" id="current_target_info"></small>
              </div>
            </div>
            <div class="col-lg-6" id="show_month" style="display: none">
              <div class="form-group">
                <label for="target_month">Month <span class="text-danger">*</span></label>
                <input type="month" class="form-control" name="month" id="target_month" value="{{ date('Y-m') }}" required>
              </div>
            </div>
            <div class="col-lg-6" id="show_target" style="display: none">
              <div class="form-group">
                <label for="target_amount">Target Amount <span class="text-danger">*</span></label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">₱</span>
                  </div>
                  <input type="number" class="form-control" name="target_amount" id="target_amount" min="0" step="0.01" required>
                </div>
                <small class="form-text text-muted" id="current_target_info"></small>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="form-group">
                <label for="target_notes">Notes (Optional)</label>
                <textarea class="form-control" name="notes"  id="target_notes" rows="3" placeholder="Any notes or comments about this target..."></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Set Target</button>
        </div>
      </form>
    </div>
  </div>
</div>