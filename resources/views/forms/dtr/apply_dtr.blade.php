<style>
  #dtrc .modal-dialog {
    width: auto;
    max-width: 860px;
    margin: 1rem auto;
  }

  #dtrc .modal-content {
    overflow: hidden;
    border-radius: 0.75rem;
    box-shadow: 0 1rem 3rem rgba(15, 23, 42, 0.18);
  }

  #dtrc .dtr-modal-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
  }

  #dtrc .dtr-modal-title {
    margin-bottom: 0.2rem;
    color: #1f2937;
    font-size: 1.1rem;
    font-weight: 600;
  }

  #dtrc .dtr-modal-subtitle {
    margin: 0;
    color: #6b7280;
    font-size: 0.8rem;
    line-height: 1.45;
  }

  #dtrc .dtr-modal-close {
    flex: 0 0 auto;
    margin-left: 1rem;
  }

  #dtrc .dtr-modal-body {
    max-height: calc(100vh - 190px);
    padding: 1.5rem;
    overflow-y: auto;
  }

  #dtrc .dtr-approver-panel {
    display: flex;
    align-items: flex-start;
    gap: 0.8rem;
    padding: 1rem;
    border: 1px solid #dbe4ee;
    border-radius: 0.65rem;
    background: #f8fafc;
  }

  #dtrc .dtr-approver-details {
    flex: 1 1 auto;
    min-width: 0;
  }

  #dtrc .dtr-approver-icon {
    display: inline-flex;
    width: 2.25rem;
    height: 2.25rem;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    border-radius: 0.55rem;
    background: #e8edff;
    color: #4b49ac;
  }

  #dtrc .dtr-approver-title {
    margin: 0 0 0.2rem;
    color: #374151;
    font-size: 0.82rem;
    font-weight: 600;
    line-height: 1.3;
  }

  #dtrc .dtr-approval-name {
    display: block;
    overflow-wrap: anywhere;
    color: #374151;
    font-size: 0.78rem;
    font-weight: 600;
    line-height: 1.35;
  }

  #dtrc .dtr-approval-name + .dtr-approval-name {
    margin-top: 0.2rem;
  }

  #dtrc .dtr-approver-empty {
    padding: 0.35rem 0;
    color: #b42318;
    font-size: 0.75rem;
    line-height: 1.4;
  }

  #dtrc .dtr-form-section + .dtr-form-section {
    margin-top: 1.4rem;
    padding-top: 1.4rem;
    border-top: 1px solid #edf0f4;
  }

  #dtrc .dtr-section-title {
    margin-bottom: 1rem;
    color: #374151;
    font-size: 0.88rem;
    font-weight: 600;
  }

  #dtrc .form-group {
    margin-bottom: 1rem;
  }

  #dtrc .dtr-label {
    display: block;
    margin-bottom: 0.4rem;
    color: #374151;
    font-size: 0.8rem;
    font-weight: 500;
  }

  #dtrc .form-control {
    min-height: 42px;
    border-color: #d6dce5;
    border-radius: 0.4rem;
  }

  #dtrc textarea.form-control {
    min-height: 110px;
    resize: vertical;
  }

  #dtrc .dtr-impact-note {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    margin-top: 0.55rem;
    padding: 0.65rem 0.75rem;
    border: 1px solid #fde7b0;
    border-radius: 0.4rem;
    background: #fffbeb;
    color: #854d0e;
    font-size: 0.75rem;
    line-height: 1.45;
  }

  #dtrc .dtr-field-help {
    display: block;
    margin-top: 0.4rem;
    color: #6b7280;
    font-size: 0.72rem;
    line-height: 1.4;
  }

  #dtrc .dtr-modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e5e7eb;
    background: #f8fafc;
  }

  #dtrc .dtr-modal-footer .btn {
    min-width: 110px;
  }

  @media (max-width: 767.98px) {
    #dtrc .modal-dialog {
      margin: 0.5rem;
    }

    #dtrc .dtr-modal-header,
    #dtrc .dtr-modal-body,
    #dtrc .dtr-modal-footer {
      padding-right: 1rem;
      padding-left: 1rem;
    }

    #dtrc .dtr-modal-body {
      max-height: calc(100vh - 165px);
    }

    #dtrc .form-control {
      font-size: 16px;
    }
  }

  @media (max-width: 575.98px) {
    #dtrc .dtr-modal-footer {
      display: flex;
      flex-direction: column-reverse;
      align-items: stretch;
    }

    #dtrc .dtr-modal-footer .btn {
      width: 100%;
      margin: 0.25rem 0 !important;
    }
  }
</style>

<!-- Modal -->
<div class="modal fade" id="dtrc" tabindex="-1" role="dialog" aria-labelledby="dtrdata" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content border-0">
      <div class="modal-header dtr-modal-header align-items-start">
        <div>
          <h5 class="modal-title" id="dtrdata">Request DTR Correction</h5>
        </div>
        <button type="button" class="btn-close btn-danger dtr-modal-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="POST" action="new-dtr" onsubmit="btnDtr.disabled = true; return true;" enctype="multipart/form-data">
        @csrf
        <div class="modal-body dtr-modal-body">
          <div class="dtr-approver-panel mb-4">
            <span class="dtr-approver-icon" aria-hidden="true"><i class="ti-user"></i></span>
            <div class="dtr-approver-details">
              <h6 class="dtr-approver-title">Assigned Approver</h6>
              @forelse($all_approvers as $approvers)
                <span class="dtr-approval-name">{{ optional($approvers->approver_info)->name ?? 'Approver unavailable' }}</span>
              @empty
                <span class="dtr-approver-empty">No approver assigned. Please contact HR.</span>
              @endforelse
            </div>
          </div>

          <div id="appDTR">
            <section class="dtr-form-section" aria-labelledby="dtr-correction-details">
              <h6 class="dtr-section-title" id="dtr-correction-details">Correction details</h6>
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="dtr_date" class="dtr-label">Choose date <span class="text-danger">*</span></label>
                    <input type="date" id="dtr_date" name="dtr_date" class="form-control" v-model="dtr_date" @change="validateDates" required>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="correction" class="dtr-label">Correction type <span class="text-danger">*</span></label>
                    <select class="form-control" id="correction" name="correction" onchange="AdddtrType(this)" required>
                      <option value="Both">Both Time-In and Time-Out</option>
                      <option value="Time-in">Time-In only</option>
                      <option value="Time-out">Time-Out only</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <label for="adjustment_type" class="dtr-label">Adjustment type <span class="text-danger">*</span></label>
                <select class="form-control" id="adjustment_type" name="adjustment_type" required>
                  <option value="">Select an adjustment type</option>
                  @foreach(\App\EmployeeDtr::adjustmentTypes() as $adjustment_type => $impact)
                    @php
                      $hasPenalty = $impact !== 'No penalty time';
                    @endphp
                    <option value="{{ $adjustment_type }}" style="color: {{ $hasPenalty ? '#92400e' : '#166534' }};">
                      {{ $adjustment_type }} ({{ $impact }})
                    </option>
                  @endforeach
                </select>
                <div class="dtr-impact-note">
                  <i class="ti-info-alt mt-1" aria-hidden="true"></i>
                  <span>Adjustments marked <strong>+/- 15 mins</strong> automatically modify the encoded time after submission.</span>
                </div>
              </div>
            </section>

            <section class="dtr-form-section" aria-labelledby="dtr-corrected-time">
              <h6 class="dtr-section-title" id="dtr-corrected-time">Corrected time</h6>
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group mb-md-0">
                    <label for="time_in" class="dtr-label">Time-In <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="time_in" id="time_in" v-model="time_in" :min="min_date" :max="dtr_max_date" @change="validateDates" class="form-control" required>
                    <small class="dtr-field-help">Enter the correct time-in.</small>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group mb-0">
                    <label for="time_out" class="dtr-label">Time-Out <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="time_out" id="time_out" class="form-control" v-model="time_out" :min="time_in" :max="max_date" @change="validateDates" required>
                    <small class="dtr-field-help">Enter the correct time-out.</small>
                  </div>
                </div>
              </div>
            </section>

            <section class="dtr-form-section" aria-labelledby="dtr-supporting-details">
              <h6 class="dtr-section-title" id="dtr-supporting-details">Supporting details</h6>
              <div class="form-group">
                <label for="dtr_remarks" class="dtr-label">Reason for correction <span class="text-danger">*</span></label>
                <textarea name="remarks" id="dtr_remarks" class="form-control" style="resize: none;" rows="4" placeholder="Briefly explain why this DTR correction is needed." required></textarea>
              </div>
              <div class="form-group mb-0">
                <label for="dtr_attachment" class="dtr-label">Supporting documents <span class="text-muted font-weight-normal">(optional)</span></label>
                <input
                  type="file"
                  id="dtr_attachment"
                  name="attachments[]"
                  class="form-control"
                  accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                  multiple
                  onchange="validateDtrAttachmentSelection(this, 3, 'dtr-attachment-feedback')"
                >
                <small class="dtr-field-help">Select up to 3 images. Maximum file size is 5 MB each. Accepted formats: JPG, JPEG, and PNG.</small>
                <div id="dtr-attachment-feedback" class="small mt-2" aria-live="polite"></div>
                @if($errors->has('attachments') || $errors->has('attachments.*'))
                  <div class="text-danger small mt-2">
                    {{ $errors->first('attachments') ?: $errors->first('attachments.*') }}
                  </div>
                @endif
              </div>
            </section>
          </div>
        </div>

        <div class="modal-footer dtr-modal-footer">
          <button type="button" class="btn btn-light border" data-dismiss="modal">Cancel</button>
          <button name="btnDtr" type="submit" class="btn btn-primary">
            Submit correction
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function validateDtrAttachmentSelection(input, maxFiles, feedbackId) {
    var feedback = document.getElementById(feedbackId);
    var files = Array.prototype.slice.call(input.files || []);

    input.setCustomValidity('');

    if (files.length > maxFiles) {
      input.value = '';
      input.setCustomValidity('You may select a maximum of ' + maxFiles + ' attachment(s).');

      if (feedback) {
        feedback.className = 'small mt-2 text-danger';
        feedback.textContent = 'You may select a maximum of ' + maxFiles + ' attachment(s).';
      }

      input.reportValidity();
      return;
    }

    if (!feedback) {
      return;
    }

    if (!files.length) {
      feedback.className = 'small mt-2';
      feedback.textContent = '';
      return;
    }

    feedback.className = 'small mt-2 text-muted';
    feedback.textContent = files.length + ' file(s) selected: ' + files.map(function (file) {
      return file.name;
    }).join(', ');
  }

  var app = new Vue({
      el: '#appDTR',
      data() {
        return {
          time_in: '',
          time_out: '',
          dtr_date: '',
          dtr_max_date: '',
          min_date: '',
          max_date: '',
        };
      },
      methods: {
        validateDates() {
          if (this.dtr_date) {
            const obDate = new Date(this.dtr_date);
            obDate.setDate(obDate.getDate() + 1);
            this.min_date = this.dtr_date + 'T00:00';
            this.dtr_max_date = this.dtr_date + 'T23:00';
            this.max_date = obDate.toISOString().split('T')[0] + 'T23:00';
          }
        }
      },
  });
</script>
