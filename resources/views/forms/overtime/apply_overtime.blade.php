<div class="modal fade" id="applyovertime" tabindex="-1" role="dialog" aria-labelledby="otData" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="otData">Apply Overtime</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <form method='POST' action='new-ot' onsubmit="btnOT.disabled = true; return true;"  enctype="multipart/form-data">
          @csrf      
          <div class="modal-body">
            {{-- <i class="text-danger">*Note: To file Overtime please check if you have attendance detected.</i> <br><br> --}}
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
            <div id="appOT">
              <div class="form-group row">
                  <div class='col-md-2'>
                    Date
                  </div>
                  <div class='col-md-6'>
                    <input v-model="ot_date" type="date" name='ot_date' class="form-control" @change="validateDates" required>
                    {{-- <button v-if="ot_date" name="ot_date" @click="validateOvertimeDate" :disabled="btnDisable" class="btn btn-outline-success btn-sm mt-1">Check Attendance</button>
                    <div class="mt-2">
                        Start Time : <span id="startTime"></span> <br>
                        End Time : <span id="endTime"></span> <br>
                        Allowed Overtime (Hrs) : <span id="allowedOvertime"></span> <br>
                        <span id="errorMessage" class="text-danger"></span>
                    </div> --}}
                </div>
              </div>

              <div class="form-group row">
                <div class='col-md-2'>
                   Start Time
                </div>
                <div class='col-md-4'>
                  <input id="start_time" v-model="start_time" type="datetime-local" name='start_time' :min="min_date" :max="ot_max_date" class="form-control" @change="validateDates" required>
                </div>
                <div class='col-md-2'>
                   End Time
                </div>
                <div class='col-md-4'>
                  <input id="end_time" v-model="end_time" type="datetime-local" name='end_time' class="form-control" :min="start_time" :max="max_date" @change="validateDates" required>
                </div>
              </div>

              <input type="hidden" name="time_compensation_type" value="overtime">
              
              <div class="form-group row">
                <div class='col-md-2'>
                  Break (Hrs)
                </div>
                <div class='col-md-4'>
                  <input type="number" step="0.01" name='break_hrs' min="0" max="3" class="form-control" placeholder="0.00">
                </div>
              </div>

              <div class="form-group row">
                <div class='col-md-2'>
                  Detailed Description for Request
                </div>
                <div class='col-md-10'>
                  <textarea  name='remarks' class="form-control" rows='4' required></textarea>
                </div>
              
              </div>
              <div class="form-group row">
                <div class='col-md-2'>
                  Proof of OTAR
                </div>
                <div class='col-md-10'>
                  <input type="file" name="attachment" class="form-control"  placeholder="Upload Supporting Documents">
                </div>
              
              </div>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button id="btnOT" type="submit" name="btnOT" class="btn btn-primary">Save</button>
          </div>
        </form>      

    </div>
  </div>
</div>
