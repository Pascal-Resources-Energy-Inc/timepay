<!-- Modal -->
<div class="modal fade" id="edit_dtr{{ $dtr->id }}" tabindex="-1" role="dialog" aria-labelledby="editdtrslabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editdtrslabel">Edit DTR Correction</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method='POST' action='edit-dtr/{{ $dtr->id }}' onsubmit='show()' enctype="multipart/form-data">
                  @csrf       
        <div class="modal-body">
          <div class="form-group row">
            <div class='col-md-2'>
              Approver 
            </div>
            <div class='col-md-9'>
              <div class='col-md-9'>
                @foreach($all_approvers as $approvers)
                  {{$approvers->approver_info->name}}<br>
                @endforeach
              </div>
            </div>
            
          </div>
          <div id="appDTR{{$dtr->id}}">
            <div class="form-group row">
              <div class='col-md-2'>
                 Date
              </div>
              <div class='col-md-4'>
                <input type="date" name='dtr_date' class="form-control" value="{{ $dtr->dtr_date }}" v-model="dtr_date"  @change="validateDates" required>
              </div>
              <div class='col-md-2'>
                DTR Type
             </div>
             <div class='col-md-4'>
              <select class="form-control"  id="crction" name='correction' onchange="editDtr(this,{{$dtr->id}})" required>
                <option value="Both" {{ $dtr->correction == 'Both' ? 'selected' : ''}}>Both Time-In and Time-Out</option>                                    
                <option value="Time-in" {{ $dtr->correction == 'Time-in' ? 'selected' : ''}}>Time-in Only</option>
                <option value="Time-out" {{ $dtr->correction == 'Time-out' ? 'selected' : ''}}>Time-out Only</option>
              </select>
             </div>            
            </div>           
            <div class="form-group row">
              <div class='col-md-2'>
                Adjustment Type
              </div>
              <div class='col-md-10'>
                <select class="form-control" name='adjustment_type' required>
                  <option value="">-- Select Adjustment Type --</option>
                  @foreach(\App\EmployeeDtr::adjustmentTypes() as $adjustment_type => $impact)
                    <option value="{{ $adjustment_type }}" {{ $dtr->adjustment_type == $adjustment_type ? 'selected' : '' }}>{{ $adjustment_type }} - {{ $impact }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row" >
                  <div class='col-md-2'>
                    Time-In
                  </div>
                  <div class='col-md-4'>
                    <input type="datetime-local" name='time_in' id='timein{{$dtr->id}}' class="form-control" value="{{ \App\EmployeeDtr::encodedTimeForEdit($dtr->time_in, $dtr->adjustment_type, 'time_in') }}" v-model="start_time" :min="min_date" :max="dtr_max_date" class="form-control" @change="validateDates" :value="start_time" required>
                  </div>
                  <div class='col-md-2'>
                    Time-out
                  </div>
                  <div class='col-md-4'>
                    <input type="datetime-local" name='time_out' id='timeout{{$dtr->id}}' class="form-control" value="{{ \App\EmployeeDtr::encodedTimeForEdit($dtr->time_out, $dtr->adjustment_type, 'time_out') }}" v-model="end_time"  :min="start_time" :max="max_date" @change="validateDates"  class="form-control" :value="end_time" required>
                  </div>
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Reason
              </div>
              <div class='col-md-10'>
                <textarea  name='remarks' class="form-control" rows='4' required>{{ $dtr->remarks }}</textarea>
              </div>
            
            </div>
            @php
              $availableAttachments = $dtr->available_attachments;
              $remainingAttachmentSlots = max(0, 3 - $availableAttachments->count());
            @endphp
            <div class="form-group row">
              <div class='col-md-2'>
                 Attachments
              </div>
              <div class='col-md-10'>
                @if($availableAttachments->isNotEmpty())
                  <div class="border rounded p-2 mb-2 bg-light">
                    <small class="d-block font-weight-bold text-dark mb-1">Current supporting documents</small>
                    <small class="d-block text-muted mb-2">Choose a replacement only for the file you want to change.</small>
                    @foreach($availableAttachments as $index => $attachment)
                      @php
                        $attachmentKey = $attachment['legacy'] ? 'legacy' : $attachment['id'];
                        $replacementInputId = 'dtr-replacement-'.$dtr->id.'-'.$attachmentKey;
                      @endphp
                      <div class="border rounded bg-white p-2 mb-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                          <a href="{{ url($attachment['path']) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-info mr-2">
                            <i class="ti-clip mr-1"></i> View attachment {{ $index + 1 }}
                          </a>
                          <span class="badge badge-light">Current file</span>
                        </div>
                        <label for="{{ $replacementInputId }}" class="small font-weight-bold mb-1">Replace attachment {{ $index + 1 }}</label>
                        <input
                          type="file"
                          id="{{ $replacementInputId }}"
                          name="replacement_attachments[{{ $attachmentKey }}]"
                          class="form-control-file"
                          accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                        >
                      </div>
                    @endforeach
                  </div>
                @endif

                @if($remainingAttachmentSlots > 0)
                  <label class="small font-weight-bold mb-1">Add supporting documents</label>
                  <input
                    type="file"
                    name="attachments[]"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                    multiple
                    onchange="validateDtrAttachmentSelection(this, {{ $remainingAttachmentSlots }}, 'dtr-edit-attachment-feedback-{{ $dtr->id }}')"
                  >
                  <small class="text-muted d-block mt-1">
                    You may add {{ $remainingAttachmentSlots }} more attachment(s), up to 5 MB each.
                  </small>
                @else
                  <div class="alert alert-light border mb-0 py-2" role="status">
                    <small class="text-muted">Maximum of 3 attachments reached. You may still replace any current file above.</small>
                  </div>
                @endif
                @if($errors->has('replacement_attachments') || $errors->has('replacement_attachments.*'))
                  <div class="text-danger small mt-2">
                    {{ $errors->first('replacement_attachments') ?: $errors->first('replacement_attachments.*') }}
                  </div>
                @endif
                <div id="dtr-edit-attachment-feedback-{{ $dtr->id }}" class="small mt-2" aria-live="polite"></div>
              </div>
            </div>
          </div>
        </div>
  
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" {{ (auth()->user()->employee->immediate_sup_data != null) ? "" : 'disabled'}}>Save</button>
        </div>
      </form>      
      </div>
    </div>
  </div>

  <script>
    var app = new Vue({
        el: '#appDTR' + '<?php echo $dtr->id; ?>',
        data() {
          return {
            btnDisable: false,
            isDisabled: true,
            allowed_overtime_hrs: '',
            start_time: '<?php echo \App\EmployeeDtr::encodedTimeForEdit($dtr->time_in, $dtr->adjustment_type, 'time_in'); ?>',
            end_time: '<?php echo \App\EmployeeDtr::encodedTimeForEdit($dtr->time_out, $dtr->adjustment_type, 'time_out'); ?>',
            dtr_date: '<?php echo $dtr->dtr_date; ?>',
            dtr_max_date: '',
            min_date: '',
            max_date: '',
            // employee_number: '21000849',
            employee_number: '<?php echo auth()->user()->employee->employee_number; ?>',
  
            error : ''
          };
        },
        created () {
          this.validateDates();
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
