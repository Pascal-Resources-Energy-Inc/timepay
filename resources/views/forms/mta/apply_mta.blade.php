<!-- Modal -->
<div class="modal fade" id="mtac" tabindex="-1" role="dialog" aria-labelledby="mtadata" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mtadata">Apply Monetized Transportation Allowance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
			<form method='POST' action='new-mta' onsubmit="btnDtr.disabled = true; return true;"  enctype="multipart/form-data">
        @csrf      
        <div class="modal-body modal-mta">
          <div class="form-group row">
            <div class='col-md-2'>Approver</div>
            <div class='col-md-9'>
              @foreach($all_approvers as $approvers)
                {{$approvers->approver_info->name}}<br>
              @endforeach
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>Transaction Date</div>
            <div class='col-md-4 mb-2'>
              <input type="date" name='mta_date' class="form-control" required>
            </div>
            <div class='col-md-2'>Work Location</div>
            <div class='col-md-4'>
              <select data-placeholder="Select Work Location" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' id="work_location" name='work_location' required>
                <option value="">-- Select Work Location --</option>
                <option value="Region 1-3">Region 1-3</option>                                    
                <option value="Region 4">Region 4</option>
                <option value="Region 5">Region 5</option>
                <option value="Region 6 - Panay Island">Region 6 - Panay Island</option>
                <option value="Region 8 - Bohol">Region 8 - Bohol</option>
                <option value="Region 18 - Negros Island Region">Region 18 - Negros Island Region</option>
                <option value="MDS - All Area">MDS - All Area</option>
              </select>
            </div>            
          </div>     
          <div class="form-group row">
            <div class='col-md-2'>Liters Loaded</div>
            <div class='col-md-4'>
              <select data-placeholder="Select Liters Loaded" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' id="liters_loaded" name='liters_loaded' required>
                <option value="">-- Select Liters Loaded --</option>
                <option value="1 ltr">1 ltr</option>                                    
                <option value="2 ltrs">2 ltrs</option>
                <option value="3 ltrs">3 ltrs</option>
              </select>
            </div>
            <div class='col-md-2'>Petron Price per Liter</div>
            <div class='col-md-4 mb-2'>
              <input type="number" step="0.01" name='petron_price' class="form-control" placeholder="Enter price per liter" required>
            </div>   
          </div>    
          <div class="form-group row">
            <div class='col-md-2'>MTA Amount</div>
            <div class='col-md-4 mb-2'>
              <input type="number" step="0.01" name='mta_amount' class="form-control" placeholder="Enter MTA amount" required>
            </div>  
            <div class='col-md-2'>Sales Invoice Number</div>
            <div class='col-md-4 mb-2'>
              <input type="text" name='sales_invoice_number' class="form-control" placeholder="Enter sales invoice number" required>
            </div>   
          </div>  
          <div class="form-group row">
            <div class='col-md-2'>Duty Notes</div>
            <div class='col-md-10'>
              <textarea name='notes' class="form-control" rows='4' placeholder="Field duty at Brgy XXX, and XXXX." required></textarea>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>Upload Receipt Picture</div>
            <div class='col-md-10'>
              <input type="file" name="attachment" class="form-control" placeholder="Upload Supporting Documents">
            </div>
          </div>
          <div class="modal-footer footer-mta">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button name="btnDtr" type="submit" class="btn btn-primary">Save</button>
          </div>
        </div>
      </form> 
    </div>    
  </div>
</div>

<style>
  .modal-mta {
    padding: 20px 25px 5px 25px !important;
  }
  .footer-mta {
    padding: 15px 0px !important;
  }
</style>