<div class="modal fade" id="view_mta{{ $mta->id }}" tabindex="-1" role="dialog" aria-labelledby="viewmtalabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewmtalabel">View Monetized Transportation Allowance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>    
      <div class="modal-body">
        <div class="form-group row">
          <div class='col-md-2'>Approver </div>
          <div class='col-md-9'>{{$mta->approverMta->user->name}}</div>
        </div>
        <div class="form-group row">
          <div class='col-md-2'>Transaction Date </div>
          <div class='col-md-4'>
            <input type="date" name='dtr_date' class="form-control" min='{{date('Y-m-d', strtotime("-3 days"))}}' value="{{ $mta->mta_date }}" disabled>
          </div>
          <div class='col-md-2'>Work Location</div>
          <div class='col-md-4'>
            <select data-placeholder="Select Work Location" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' id="work_location" name='work_location' disabled>
              <option value="">-- Select Work Location --</option>
              <option value="Region 1-3" {{ $mta->work_location == 'Region 1-3' ? 'selected' : '' }}>Region 1-3</option>                                    
              <option value="Region 4" {{ $mta->work_location == 'Region 4' ? 'selected' : '' }}>Region 4</option>
              <option value="Region 5" {{ $mta->work_location == 'Region 5' ? 'selected' : '' }}>Region 5</option>
              <option value="Region 6 - Panay Island" {{ $mta->work_location == 'Region 6 - Panay Island' ? 'selected' : '' }}>Region 6 - Panay Island</option>
              <option value="Region 8 - Bohol" {{ $mta->work_location == 'Region 8 - Bohol' ? 'selected' : '' }}>Region 8 - Bohol</option>
              <option value="Region 18 - Negros Island Region" {{ $mta->work_location == 'Region 18 - Negros Island Region' ? 'selected' : '' }}>Region 18 - Negros Island Region</option>
              <option value="MDS - All Area" {{ $mta->work_location == 'MDS - All Area' ? 'selected' : '' }}>MDS - All Area</option>
            </select>
          </div>        
        </div> 
        <div class="form-group row">
          <div class='col-md-2'>Liters Loaded</div>
          <div class='col-md-4'>
            <select data-placeholder="Select Liters Loaded" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' id="liters_loaded" name='liters_loaded' disabled>
              <option value="1" {{ $mta->liters_loaded == '1' ? 'selected' : '' }}>1 ltr</option>                                    
              <option value="2" {{ $mta->liters_loaded == '2' ? 'selected' : '' }}>2 ltrs</option>
              <option value="3" {{ $mta->liters_loaded == '3' ? 'selected' : '' }}>3 ltrs</option>
            </select>
          </div>
          <div class='col-md-2'>Petron Price per Liter</div>
          <div class='col-md-4 mb-2'>
            <input type="number" step="0.01" name='petron_price' class="form-control" value="{{ $mta->petron_price }}" disabled>
          </div>   
        </div>  
        <div class="form-group row">
          <div class='col-md-2'>MTA Amount</div>
          <div class='col-md-4 mb-2'>
            <input type="number" step="0.01" name='mta_amount' class="form-control" value="{{ $mta->mta_amount }}" disabled>
          </div>  
          <div class='col-md-2'>Sales Invoice Number</div>
          <div class='col-md-4 mb-2'>
            <input type="text" name='sales_invoice_number' class="form-control" value="{{ $mta->sales_invoice_number }}" disabled>
          </div>   
        </div>    
        <div class="form-group row">
          <div class='col-md-2'>Reason</div>
          <div class='col-md-10'>
            <textarea  name='remarks' class="form-control" rows='4' disabled>{{ $mta->notes }}</textarea>
          </div>
        </div>  
        <div class="form-group row align-items-center">
          <div class="col-md-2 font-weight-bold">
            <i class="fa fa-paperclip"></i>&nbsp;Attachment
          </div>
          <div class="col-md-10">
            @if($mta->attachment)
              <div class="d-flex align-items-center justify-content-between border rounded p-2 bg-light">
                <div class="d-flex align-items-center">
                  <i class="fa fa-file text-primary mr-2" style="font-size:18px;"></i>
                  <span class="text-truncate" style="max-width: 250px;">
                    {{ basename($mta->attachment) }}
                  </span>
                </div>
                <div>
                  <a href="{{ url($mta->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1">
                    <i class="fa fa-eye"></i> View
                  </a>
                  <a href="{{ url($mta->attachment) }}" download class="btn btn-sm btn-outline-success">
                    <i class="fa fa-download"></i> Download
                  </a>
                </div>
              </div>
            @else
              <div class="text-muted">
                <i class="fa fa-ban"></i> No attachment available
              </div>
            @endif
          </div>
        </div>
        <hr>
        <div class="form-group">
          <h6 class="mb-3"><i class="fa fa-comment"></i>&nbsp;Remarks</h6>
          <div class="row">
            <div class="col-md-6">
              <label>
                <span class="badge badge-primary">Approval Remarks</span>
              </label>
              <div class="p-2 border rounded bg-light" style="min-height: 80px;">
                {{ $mta->approval_remarks ?? 'No approval remarks' }}
              </div>
            </div>
            <div class="col-md-6">
              <label>
                <span class="badge badge-success">Payment Remarks</span>
              </label>
              <div class="p-2 border rounded bg-light" style="min-height: 80px;">
                {{ $mta->payment_remarks ?? 'No payment remarks' }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        {{-- <button type="submit" class="btn btn-primary" {{ (auth()->user()->employee->immediate_sup_data != null) ? "" : 'disabled'}}>Save</button> --}}
      </div>     
    </div>
  </div>
</div>