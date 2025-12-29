<div class="modal fade" id="registerDealer" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ route('tds.store') }}" method="POST" id="dealerForm" enctype="multipart/form-data">
        @csrf
        <div class="modal-header text-black">
          <h5 class="modal-title">Register New Dealer</h5>
          <button type="button" class="close text-white" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h5 class="mb-3 text-primary">General Details</h5>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Date of Registration <span class="text-danger">*</span></label>
                <input type="date" class="form-control" 
                       name="date_registered" 
                       value="{{ old('date_registered', date('Y-m-d')) }}" 
                       max="{{ date('Y-m-d') }}" 
                       required>
                <small class="form-text text-muted">When the dealer signed-up</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Employee Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="employee_name" value="{{ old('employee_name', Auth::user()->name) }}" 
                       placeholder="Who acquired the dealer?" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Area <span class="text-danger">*</span></label>
                <select class="form-control" name="area" required onclick="event.stopPropagation();">
                  <option value="">-- Select Area --</option>
                  @foreach($regions as $region)
                    <option value="{{ $region->id }}" {{ old('area') == $region->id ? 'selected' : '' }}>
                      {{ $region->region }} - {{ $region->province }}{{ $region->district ? ' - ' . $region->district : '' }}
                    </option>
                  @endforeach
                </select>
                <small class="form-text text-muted">Select region and province</small>
              </div>
            </div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3 text-primary">Customer Information</h5>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Customer Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="customer_name" value="{{ old('customer_name') }}" 
                       placeholder="Full name of the customer" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Contact Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="contact_no" value="{{ old('contact_no') }}" 
                       placeholder="09453658795" required>
                <small class="form-text text-muted">Mobile number</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Business Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="business_name" value="{{ old('business_name') }}" 
                       placeholder="e.g., Justin's Store" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Business Type <span class="text-danger">*</span></label>
                <select class="form-control" name="business_type" required onclick="event.stopPropagation();">
                  <option value="">-- Select Business Type --</option>
                  <option value="Sari-Sari Store" {{ old('business_type') == 'Sari-Sari Store' ? 'selected' : '' }}>Sari-Sari Store</option>
                  <option value="Mini Mart" {{ old('business_type') == 'Mini Mart' ? 'selected' : '' }}>Mini Mart</option>
                  <option value="Retail Shop" {{ old('business_type') == 'Retail Shop' ? 'selected' : '' }}>Retail Shop</option>
                  <option value="Wholesale" {{ old('business_type') == 'Wholesale' ? 'selected' : '' }}>Wholesale</option>
                  <option value="Grocery" {{ old('business_type') == 'Grocery' ? 'selected' : '' }}>Grocery</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Awarded Area</label>
                <input type="text" class="form-control" 
                       name="awarded_area" value="{{ old('awarded_area') }}" 
                       placeholder="For Area Distributors">
                <small class="form-text text-muted">Only applicable for Area Distributors</small>
              </div>
            </div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3 text-primary">Package & Program Details</h5>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Package Type <span class="text-danger">*</span></label>
                <select class="form-control" name="package_type" required onclick="event.stopPropagation();">
                  <option value="">-- Select Package --</option>
                  <option value="EU" {{ old('package_type') == 'EU' ? 'selected' : '' }}>EU - End User</option>
                  <option value="D" {{ old('package_type') == 'D' ? 'selected' : '' }}>D - Dealer</option>
                  <option value="MD" {{ old('package_type') == 'MD' ? 'selected' : '' }}>MD - Mega Dealer</option>
                  <option value="AD" {{ old('package_type') == 'AD' ? 'selected' : '' }}>AD - Area Distributor</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Purchase Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" 
                       name="purchase_amount" value="{{ old('purchase_amount') }}" 
                       placeholder="25000" min="0" step="0.01" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Lead Generator <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="lead_generator" value="{{ old('lead_generator') }}" 
                       placeholder="e.g., Other Accounts" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Program Type <span class="text-danger">*</span></label>
                <select class="form-control" name="program_type" id="program_type" onclick="event.stopPropagation();" required>
                  <option value="">-- Select Program Type --</option>
                  <option value="Roadshow" {{ old('program_type') == 'Roadshow' ? 'selected' : '' }}>Roadshow</option>
                  <option value="Mini-Roadshow" {{ old('program_type') == 'Mini-Roadshow' ? 'selected' : '' }}>Mini-Roadshow</option>
                  <option value="Non-Roadshow" {{ old('program_type') == 'Non-Roadshow' ? 'selected' : '' }}>Non-Roadshow</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Program Area <span class="text-danger program-area-required" style="display: none;">*</span></label>
                <input type="text" class="form-control" 
                       name="program_area" id="program_area" value="{{ old('program_area') }}" 
                       placeholder="Specify area for Roadshow/Mini-Roadshow">
                <small class="form-text text-muted">Required for Roadshow and Mini-Roadshow</small>
              </div>
            </div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3 text-primary">Delivery Details</h5>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Supplier Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       name="supplier_name" value="{{ old('supplier_name') }}" 
                       placeholder="e.g., MD Monicarl, AD Gorospe, PDI" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select class="form-control" name="status" required onclick="event.stopPropagation();">
                  <option value="">-- Select Status --</option>
                  <option value="Decline" {{ old('status') == 'Decline' ? 'selected' : '' }}>Decline</option>
                  <option value="Interested" {{ old('status') == 'Interested' ? 'selected' : '' }}>Interested</option>
                  <option value="For Delivery" {{ old('status') == 'For Delivery' ? 'selected' : '' }}>For Delivery</option>
                  <option value="Delivered" {{ old('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Target Timeline</label>
                <input type="date" class="form-control" 
                       name="timeline" value="{{ old('timeline') }}">
                <small class="form-text text-muted">Expected timeline</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Delivery Date</label>
                <input type="date" class="form-control" 
                       name="delivery_date" value="{{ old('delivery_date') }}">
                <small class="form-text text-muted">Actual delivery date</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Document Attachment</label>
                <input type="file" class="form-control-file" 
                       name="document_attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <small class="form-text text-muted">Upload supporting documents (PDF, DOC, DOCX, JPG, PNG - Max 5MB)</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Additional Notes</label>
                <textarea class="form-control" 
                          name="additional_notes" rows="3" 
                          placeholder="Any additional information...">{{ old('additional_notes') }}</textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Register Dealer</button>
        </div>
      </form>
    </div>
  </div>
</div>