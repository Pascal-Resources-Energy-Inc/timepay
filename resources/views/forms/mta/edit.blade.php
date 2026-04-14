<!-- Modal -->
<div class="modal fade" id="edit_mta{{ $mta->id }}" tabindex="-1" role="dialog" aria-labelledby="editmtalabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editmtalabel">Edit Monetized Transportation Allowance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method='POST' action='edit-mta/{{ $mta->id }}' onsubmit='show()' enctype="multipart/form-data">
        @csrf       
        <div class="modal-body">
          <div class="form-group row">
            <div class='col-md-2'>Approver</div>
            <div class='col-md-9'>{{$mta->approverMta->user->name}}</div>
            {{-- <div class='col-md-9'>
              @foreach($all_approvers as $approvers)
                {{$approvers->approver_info->name}}<br>
              @endforeach
            </div> --}}
          </div>
          <div class="form-group row">
            <div class='col-md-2'>Transaction Date</div>
            <div class='col-md-4 mb-2'>
              <input type="date" name='mta_date' class="form-control" min='{{date('Y-m-d', strtotime("-3 days"))}}' value="{{ $mta->mta_date }}" required>
            </div>
            <div class='col-md-2'>Work Location</div>
            <div class='col-md-4'>
              <select data-placeholder="Select Work Location" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' id="work_location" name='work_location' required>
                <option value="">-- Select Work Location --</option>
                <option value="Region 1-3" {{ $mta->work_location == 'Region 1-3' ? 'selected' : '' }}>Region 1-3</option>                                    
                <option value="Region 4" {{ $mta->work_location == 'Region 4' ? 'selected' : '' }}>Region 4</option>
                <option value="Region 5" {{ $mta->work_location == 'Region 5' ? 'selected' : '' }}>Region 5</option>
                <option value="Region 6 - Panay Island" {{ $mta->work_location == 'Region 6 - Panay Island' ? 'selected' : '' }}>Region 6 - Panay Island</option>
                <option value="Region 8 - Bohol" {{ $mta->work_location == 'Region 8 - Bohol' ? 'selected' : '' }}>Region 8 - Bohol</option>
                <option value="Region 18 - Negros Island Region" {{ $mta->work_location == 'Region 18 - Negros Island Region' ? 'selected' : '' }}>Region 18 - Negros Island Region</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>Liters Loaded</div>
            <div class='col-md-4'>
              <select data-placeholder="Select Liters Loaded" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' id="liters_loaded" name='liters_loaded' required>
                <option value="">-- Select Liters Loaded --</option>
                <option value="1 ltr" {{ $mta->liters_loaded == '1 ltr' ? 'selected' : '' }}>1 ltr</option>                                    
                <option value="2 ltrs" {{ $mta->liters_loaded == '2 ltrs' ? 'selected' : '' }}>2 ltrs</option>
                <option value="3 ltrs" {{ $mta->liters_loaded == '3 ltrs' ? 'selected' : '' }}>3 ltrs</option>
              </select>
            </div>
            <div class='col-md-2'>Petron Price per Liter</div>
            <div class='col-md-4 mb-2'>
              <input type="number" step="0.01" name='petron_price' class="form-control" value="{{ $mta->petron_price }}" required>
            </div>   
          </div>  
          <div class="form-group row">
            <div class='col-md-2'>MTA Amount</div>
            <div class='col-md-4 mb-2'>
              <input type="number" step="0.01" name='mta_amount' class="form-control" value="{{ $mta->mta_amount }}" required>
            </div>  
            <div class='col-md-2'>Sales Invoice Number</div>
            <div class='col-md-4 mb-2'>
              <input type="text" name='sales_invoice_number' class="form-control" value="{{ $mta->sales_invoice_number }}" required>
            </div>   
          </div>    
          <div class="form-group row">
            <div class='col-md-2'>Duty Notes</div>
            <div class='col-md-10'>
              <textarea  name='notes' class="form-control" rows='4' required>{{ $mta->notes }}</textarea>
            </div>
          </div>  
          <div class="form-group row">
            <div class='col-md-2'>Upload Receipt Picture</div>
            <div class='col-md-10'>
              <input type="file" name="attachment" class="form-control" placeholder="Upload Supporting Documents">
            </div>  
          </div>
        </div>

        <div class="modal-footer">
            @if($mta->attachment)
              <a href="{{url($mta->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
            @endif
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" {{ (auth()->user()->employee->immediate_sup_data != null) ? "" : 'disabled'}}>Save</button>
        </div>
      </form>      
    </div>
  </div>
</div>
