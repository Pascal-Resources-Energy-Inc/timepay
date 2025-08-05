<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      

<!-- Modal -->
<div class="modal fade" id="ob" tabindex="-1" role="dialog" aria-labelledby="OBDATA" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 1200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="OBDATA">Travel Order Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method='POST' action='new-ob' onsubmit="btnOB.disabled = true; return true;" enctype="multipart/form-data">
        @csrf
       <div class="modal-body">
        <!-- Form Content -->
        <div class="container mt-2">
          <div class="row mb-2">
            <div class="col-md-8 col-sm-12">
              <h3 class="fw-bold">PASCAL RESOURCES ENERGY, INC.</h3>
              <small>PLEASE ACCOMPLISH IN TRIPLICATE</small>
            </div>
            <div class="col-md-4 col-sm-12 text-md-end">
              <h4 class="fw-bold">TRAVEL ORDER</h4>
              <div class="border border-dark p-1">
                <div class="row">
                  <div class="col-6 border-end border-dark p-0 text-center">
                    <div class="d-flex align-items-center justify-content-center">
                      <label for="applied_date" class="me-2">Date:&nbsp;</label>
                      <input type="date" id="applied_date" name="applied_date"
                          class="form-control"
                          style="width: 100px; height: 40px; font-size: 0.7rem; padding: 0.25rem 0.5rem;"
                          value="{{ old('applied_date', date('Y-m-d')) }}">
                    </div>
                  </div>
                  <div class="col-2 p-0 text-center">T.O. No. {{ $toNumber ?? '' }}</div>
                  <input type="text"
                      class="form-control"
                      style="background: transparent; width: 100px; height: 40px; font-size: 0.7rem; padding: 0.25rem 0.5rem; border: none; border-bottom: 1px solid #0000; box-shadow: none;"
                      name=""
                      value=""
                      placeholder="">
                </div>
              </div>
            </div>
          </div>
            
            <div class="form-sections" style="display: flex; align-items: flex-start;">
              <div class="Itinerary-section">
          <!-- Itinerary Section -->
          <div class="border-left border-top border-bottom border-dark">
            <div class="row m-0"> 
              <div class="p-0 text-center" style="width:645.2px; color: white; background-color: #3490dc;">
                <div class="border-bottom border-dark p-1 fw-bold">ITINERARY</div>
              </div>
            </div>
            <div class="row h m-0" style="height: 61px;">
              <div class="col-md-4 border-right border-dark p-3 text-center">
                DESTINATION
              </div>
              <div class="col-md-4 border-right border-dark p-0 text-center">
                <div class="p-1 border-bottom border-dark">DEPARTURE</div>
                <div class="row hm-0">
                  <div class="col-6 border-dark p-1" style="height: 32px">DATE</div>
                  <div class="col-6 p-1 border-left border-dark">TIME</div>
                </div>
              </div>
              <div class="col-md-4 p-0 text-center">
                <div class="p-1 border-bottom border-dark">EXP. ARRIVAL</div>
                <div class="row h m-0">
                  <div class="col-6 border-right border-dark p-1" style="height: 32px">DATE</div>
                  <div class="col-6 p-1 border-dark">TIME</div>
                </div>
              </div>
            </div>

            <!-- Container for Itinerary Rows -->
            <div id="itinerary-rows">
              <!-- Initial Row of Data -->
                <div class="row border-top border-dark m-0 itinerary-row">
                  <div class="col-md-4 border-right border-dark p-1">
                    <input type="text" class="form-control form-control-sm" style="height: 30px;" name="destination" value="{{ old('destination') }}" placeholder="Destination" required>
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="date" class="form-control form-control-sm" style="height: 30px; font-size: 0.7rem; padding: 0.25rem 0.4rem;" name="date_from" value="{{ old('date_from') }}" required>
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="time" class="form-control form-control-sm" style="height: 30px; font-size: 0.9rem; padding: 0.25rem 0.4rem;" name="departure_time" value="{{ old('departure_time') }}" required>
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="date" class="form-control form-control-sm" style="height: 30px; font-size: 0.7rem; padding: 0.25rem 0.4rem;" name="date_to" value="{{ old('date_to') }}" required>
                  </div>
                  <div class="col-md-2 p-1">
                    <input type="time" class="form-control form-control-sm" style="height: 30px; font-size: 0.9rem; padding: 0.25rem 0.4rem;" name="arrival_time" value="{{ old('arrival_time') }}" required>
                  </div>
                </div>


                <div class="row border-top border-dark m-0 itinerary-row">
                  <div class="col-md-4 border-right border-dark p-1">
                    <input type="text" class="form-control form-control-sm" style="height: 30px;" name="destination_2" value="{{ old('destination_2') }}" placeholder="Destination">
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="date" class="form-control form-control-sm" style="height: 30px; font-size: 0.7rem; padding: 0.25rem 0.4rem;" name="date_from_2" value="{{ old('date_from_2') }}">
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="time" class="form-control form-control-sm" style="height: 30px; font-size: 0.9rem; padding: 0.25rem 0.4rem;" name="departure_time_2" value="{{ old('departure_time_2') }}">
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="date" class="form-control form-control-sm" style="height: 30px; font-size: 0.7rem; padding: 0.25rem 0.4rem;" name="date_to_2" value="{{ old('date_to_2') }}">
                  </div>
                  <div class="col-md-2 p-1">
                    <input type="time" class="form-control form-control-sm" style="height: 30px; font-size: 0.9rem; padding: 0.25rem 0.4rem;" name="arrival_time_2" value="{{ old('arrival_time_2') }}">
                  </div>
                </div>


                <div class="row border-top border-dark m-0 itinerary-row">
                  <div class="col-md-4 border-right border-dark p-1">
                    <input type="text" class="form-control form-control-sm" style="height: 44.4px;" name="destination_3" value="{{ old('destination_3') }}" placeholder="Destination">
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="date" class="form-control form-control-sm" style="height: 44.4px; font-size: 0.7rem; padding: 0.25rem 0.4rem;" name="date_from_3" value="{{ old('date_from_3') }}">
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="time" class="form-control form-control-sm" style="height: 44.4px; font-size: 0.9rem; padding: 0.25rem 0.4rem;" name="departure_time_3" value="{{ old('departure_time_3') }}">
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="date" class="form-control form-control-sm" style="height: 44.4px; font-size: 0.7rem; padding: 0.25rem 0.4rem;" name="date_to_3" value="{{ old('date_to_3') }}">
                  </div>
                  <div class="col-md-2 p-1">
                    <input type="time" class="form-control form-control-sm" style="height: 44.4px; font-size: 0.9rem; padding: 0.25rem 0.4rem;" name="arrival_time_3" vvalue="{{ old('arrival_time_3') }}">
                  </div>
                </div>
                

            </div>
            
            <div class="text-center col-1 p-3" style="height: 76px;">
              <button type="button" id="add-row-btn" class="btn rounded-circle p-2" style="background-color: #3490dc; color: white;">
               <i class="bi bi-plus" style="font-size: 1.5rem;"></i>
              </button>
          </div>

        </div>
      </div>
              
              <div class="cash-advance">
                <div class="border-left border-right border-bottom border-dark" >
                  <div class="row m-0">
                    <div class="p-0 text-center" style="width: 463px; color: white; background-color: #3490dc; border-color: #3490dc;">
                      <div class="border-right border-top border-dark p-1 fw-bold">CASH ADVANCE COMPUTATION</div>
                    </div>
                  </div>
                  <div class="row border-top border-dark m-0">
                    <div class="border-right border-dark text-center" style="width: 140px; padding: 6px 5px 12px 0;">
                      <div class="">PARTICULARS (ALLOWANCES)</div>
                    </div>
                    <div class="border-right border-dark p-2 text-center" style="width: 100px;">
                      <div class="">AMOUNT / LIMIT</div>
                    </div>
                    <div class="border-right border-dark p-2 text-center" style="width: 100px;">
                      <div class="">NUMBER OF DAYS</div>
                    </div>
                    <div class="p-3 text-center" style="width: 122px;">
                      <div class="">TOTAL</div>
                    </div>
                  </div>

                  <!-- First Row of Data -->
                  <div class="row border-top border-dark m-0">
                    <div class="border-right border-dark p-1" style="width: 140px;">
                      <div class="">PER DIEM/FIXED</div>
                    </div>
                    <div class="border-right border-dark p-1" style="width: 100px;">
                      <input type="number" style="height: 30px;" class="form-control form-control-sm amount-input" name="perdiem_amount" value="{{ old('perdiem_amount') }}" data-row="1" required>
                    </div>
                    <div class="border-right border-dark p-1" style="width: 100px;">
                      <input type="number"  style="height: 30px;"  class="form-control form-control-sm days-input" name="perdiem_numofday" value="{{ old('perdiem_numofday') }}" data-row="1" required>
                    </div>
                    <div class="border-dark p-1" style="width: 122px;">
                      <input type="number"  style="height: 30px;"  class="form-control form-control-sm total-field" name="perdiem_total" value="{{ old('perdiem_total') }}" data-row="1" readonly>
                    </div>
                  </div>

                  <!-- Second Row of Data -->
                  <div class="row border-top border-dark m-0">
                    <div class="border-right border-dark p-1" style="width: 140px;">
                      <div class="">HOTEL/LODGING</div>
                    </div>
                    <div class="border-right border-dark p-1" style="width: 100px;">
                      <input type="number"  style="height: 30px;" class="form-control form-control-sm amount-input" name="hotellodging_amount" value="{{ old('hotellodging_amount') }}" data-row="2">
                    </div>
                    <div class="border-right border-dark p-1" style="width: 100px;">
                      <input type="number"  style="height: 30px;" class="form-control form-control-sm days-input" name="hotellodging_numofday" value="{{ old('hotellodging_numofday') }}" data-row="2">
                    </div>
                    <div class="border-dark p-1" style="width: 122px;">
                      <input type="number"  style="height: 30px;" class="form-control form-control-sm total-field" name="hotellodging_total" value="{{ old('hotellodging_total') }}" data-row="2" readonly>
                    </div>
                  </div>

                  <!-- Third Row of Data -->
                  <div class="row border-top border-dark m-0" style="height: 53px;">
                    <div class="border-right border-dark p-1" style="width: 140px;">
                      <div class="" style="font-size: 14px;">TRANSPORTATION / GASOLINE</div>
                    </div>
                    <div class="border-right border-dark p-1" style="width: 100px;">
                      <input type="text" style="height: 45px;" class="form-control form-control-sm amount-input" name="transpo_amount" value="{{ old('transpo_amount') }}" data-row="3" required>
                    </div>
                    <div class="border-right border-dark p-1" style="width: 100px;">
                      <input type="text" style="height: 45px;" class="form-control form-control-sm days-input" name="transpo_numofday" value="{{ old('transpo_numofday') }}" data-row="3" required>
                    </div>
                    <div class="border-dark p-1" style="width: 122px;">
                      <input type="text" style="height: 45px;" class="no-calc form-control form-control-sm total-field" name="transpo_total" value="{{ old('transpo_total') }}" data-row="3" readonly>
                    </div>
                  </div>

                  <!-- Fourth Row of Data -->
                  <div class="row border-top border-dark m-0" style="height: 39px;">
                    <div class="border-right border-dark p-1" style="width: 140px;">
                      <div class="">TOTAL FEES</div>
                    </div>
                    <div class="border-right border-dark p-1" style="width: 100px;">
                      <input type="number"  style="height: 30px;" class="form-control form-control-sm amount-input" name="totalfees_amount" value="{{ old('totalfees_amount') }}" data-row="4">
                    </div>
                    <div class="border-right border-dark p-1" style="width: 100px;">
                      <input type="number"  style="height: 30px;" class="form-control form-control-sm days-input" name="totalfees_numofday" value="{{ old('totalfees_numofday') }}" data-row="4">
                    </div>
                    <div class="border-dark p-1" style="width: 122px;">
                      <input type="number"  style="height: 30px;" class="form-control form-control-sm total-field" name="totalfees_total" value="{{ old('totalfees_total') }}" data-row="4" readonly>
                    </div>
                  </div>

                  <!-- Fifth Row of Data -->
                  <div class="row border-top border-dark m-0" style="height: 37px;">
                    <div class="border-right border-dark p-1" style="width: 140px;">
                      <div class="">TOTAL AMOUNT</div>
                    </div>
                     <div class="border-right border-dark p-1" style="width: 100px;">
                      <input type="number"  style="height: 30px;" class="form-control form-control-sm fw-bold text-end" placeholder="0" name="totalamount_amount" value="{{ old('totalamount_amount') }}" readonly>
                    </div>
                    <div class="border-right border-dark p-1" style="width: 100px;">
                      <input type="number"  style="height: 30px;" class="form-control form-control-sm fw-bold text-end" name="totalamount_numofday" value="{{ old('totalamount_numofday') }}" readonly>
                    </div>
                    <div class="border-dark p-1" style="width: 122px;">
                      <input type="number"  style="height: 30px;" class="form-control form-control-sm total-field" name="totalamount_total" value="{{ old('totalamount_total') }}" data-row="5" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Purpose Section -->
           <div class="row m-0 border-bottom border-left border-right border-dark mt-0">
              <div class="col-lg-7 col-md-12 border-right border-dark p-2">       
                &nbsp;&nbsp;PURPOSE
                <textarea class="form-control" name="purpose" rows="10" required>{{ old('purpose') }}</textarea>
              </div>
              <div class="col-lg-5 p-0 text-left">
                <div class="col-md-12 p-0">
                  <div class="form-check d-flex align-items-center px-5 py-0">
                    <input class="form-check-input me-2" type="radio" name="mode_payment" id="cash" value="cash" required>
                    <label class="form-check-label mb-0" for="cash">CASH</label>
                  </div>
                </div>
                <div class="border-top border-dark p-0">
                  <div class="form-check d-flex align-items-center px-5 py-0">
                    <input class="form-check-input me-2" type="radio" name="mode_payment" id="check" value="check">
                    <label class="form-check-label mb-0" for="check">CHECK</label>
                  </div>
                </div>
                <div class="border-top border-dark p-0">
                  <div class="form-check d-flex align-items-center px-5 py-0">
                    <input class="form-check-input me-2" type="radio" name="mode_payment" id="payroll" value="payroll">
                    <label class="form-check-label mb-0" for="payroll">CREDIT TO EMPLOYEE'S PAYROLL ACCOUNT</label>
                  </div>
                </div>
                <div class="border-top border-dark p-1">
                  <label>OTHER INSTRUCTIONS</label>
                  <input type="text" class="form-control form-control-sm" name="other_instructions">
                </div>
              </div>
            </div>



            
            <!-- Authorization Text -->
            <div class="border border-dark border-top-0 p-3">
              <p class="small">I hereby authorize PASCAL RESOURCES ENERGY, INC. to deduct from my salary all Unliquidated Cash Advance without further notice if I fail to comply with my liquidation date, set seven (7) working days after my expected arrival date.</p>
            </div>
            
            <!-- Approvals Section -->
            <div class="row m-0 border border-dark border-top-0">
              <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                <div class="border-dark p-1 text-center">REQUESTING DIVISION/DEPARTMENT</div>
                <div class="p-1 text-center"> 
                  <input type="text"
                    style="background: transparent; font-size: 11px; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding: 5px 0; line-height: 20px; text-align: center; width: 100%;"
                    value="{{auth()->user()->employee->department->name}}"
                    name="requestor_name"
                    readonly> 
                </div>
              </div>
              <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                <div class="border-dark p-3 text-center">RC CODE</div>
                <div class="p-1 text-center"> 
                  <input type="text"
                    class="text-center"
                    style="background: transparent; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                    value="{{auth()->user()->employee->cost_center}}"
                    name="requestor_name"
                    readonly> 
                </div>
              </div>
              <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                <div class="border-dark p-3 text-center">REQUESTED</div>
                <div class="p-1 text-center"> 
                  <input type="text"
                    style="background: transparent; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                    value="  {{auth()->user()->employee->first_name}} @if(auth()->user()->employee->middle_initial != null){{auth()->user()->employee->middle_initial}}.@endif {{auth()->user()->employee->last_name}}"
                    name="requestor_name"
                    readonly> 
                    <small class="text-center d-block">(Requestor's Signature Over Printed Name)</small>
                </div>
              </div>
              <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                <div class="border-dark p-3 text-center">CHECKED</div>
                <div class="p-1">
                  <input type="text" style="height: 30px;" class="form-control form-control-sm">
                  <small class="text-center d-block">Immediate Supervisor</small>
                </div>
              </div>
              <div class="col-lg col-md-12 col-sm-12 p-1">
                <div class="border-dark p-3 text-center">APPROVED</div>
                <div class="p-1">
                  <input type="text" style="background: transparent; text-align: center; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                   name="approved_by_head" value="{{auth()->user()->employee->immediateSupervisor->first_name}} @if(auth()->user()->employee->immediateSupervisor->middle_initial != null){{auth()->user()->employee->immediateSupervisor->middle_initial}}.@endif {{auth()->user()->employee->immediateSupervisor->last_name}}" readonly>
                  <small class="text-center d-block">Division/Cluster Head</small>
                </div>
              </div>
            </div>
            
            <!-- Treasury Section -->
            <div class="row m-0 border border-dark border-top-0">
              <div class="col-12 border-bottom border-dark p-1 text-center fw-bold" style="color: white; background-color: #3490dc; border-color: #3490dc;">TREASURY DIVISION / ACCOUNTING OPERATIONS</div>
              <div class="col-md-7 col-sm-12 border-end border-dark p-1">
                <div class="border-dark p-1">WITH OUTSTANDING BALANCE: P_______________________</div>
                <div class="p-1">WITHOUT OUTSTANDING BALANCE</div>
              </div>
              <div class="col-md-3 col-sm-6 border-end border-dark p-1">
                <div class="border-dark p-1">LIQUIDATION DUE ON:</div>
                <div class="p-1">
                  <input type="date" class="form-control form-control-sm" style="height: 30px;" name="liquidation_date" readonly>
                </div>
              </div>
              <div class="col-md-2 col-sm-6 p-1">
                <div class="p-1">VERIFIED BY:</div>
                <div class="p-1">
                  <input type="text" class="form-control form-control-sm" style="height: 30px;" name="verified_by" readonly>
                </div>
              </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-1">
              <small>Distribution: Copy 1 Treasury Division/Accounting Operations; 2 Attachment to PRF upon liquidation; 3 Requestor's copy</small>
            </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btnOb">Submit Travel Order</button>
      </div>
      <!-- </form> -->
       </form>
    </div>
  </div>
</div>


<style>
/* Default desktop layout */
.form-sections {
  display: flex;
}

.Itinerary-section {
  order: 1;
}

.cash-advance {
  order: 2;
}

@media (max-width: 768px) {
  .form-sections {
    flex-direction: column;
    gap: 1rem;
  }

  input.form-control-sm {
    font-size: 0.875rem;
  }
}

/* Responsive styles */
@media (max-width: 768px) {
  .form-sections {
    flex-direction: column;
  }
  
  .Itinerary-section {
    width: 100%;
  }
  
  .h.m-0 {
    display: flex;
    flex-direction: column;
  }
  
  .col-md-4, .col-md-2 {
    width: 100%;
    border-right: none !important;
  }
  
  /* Make borders consistent on mobile */
  .col-md-4:not(:last-child), .col-md-2:not(:last-child) {
    border-bottom: 1px solid #000 !important;
  }
  
  /* Hide desktop headers */
  .h.m-0:nth-child(2) {
    display: none;
  }
  
  /* Add responsive labels */
  .col-md-4::before {
    content: "DESTINATION";
    display: block;
    margin-bottom: 5px;
  }
  
  .col-md-2:nth-child(2)::before {
    content: "DEPARTURE DATE";
    display: block;
    margin-bottom: 5px;
  }
  
  .col-md-2:nth-child(3)::before {
    content: "DEPARTURE TIME";
    display: block;
    margin-bottom: 5px;
  }
  
  .col-md-2:nth-child(4)::before {
    content: "ARRIVAL DATE";
    display: block;
    margin-bottom: 5px;
    
  }
  
  .col-md-2:nth-child(5)::before {
    content: "ARRIVAL TIME";
    display: block;
    margin-bottom: 5px;
  }
}

/* Additional Modal Styles */
.modal-xl {
  max-width: 1300px;
}

@media (max-width: 1200px) {
  .modal-xl {
    max-width: 95%;
  }
}
</style>


<!-- // Add Row Button
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add Row Button Event Listener
    document.getElementById('add-row-btn').addEventListener('click', function() {
        // Create a new row
        const newRow = document.createElement('div');
        newRow.className = 'row border-top border-dark m-0 itinerary-row';
        
        // Set the HTML content for the new row
        newRow.innerHTML = `
            <div class="row border-dark m-0 itinerary-row">
                  <div class="col-md-4 border-right border-dark p-1">
                    <input type="text" class="form-control form-control-sm" style="height: 30px;" name="destination[]" value="{{ old('destination') }}" placeholder="Destination" required>
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="date" class="form-control form-control-sm" style="height: 30px; font-size: 0.7rem; padding: 0.25rem 0.4rem;" name="date_from[]" value="{{ old('date_from') }}" required>
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="time" class="form-control form-control-sm" style="height: 30px; font-size: 0.9rem; padding: 0.25rem 0.4rem;" name="departure_time[]" value="{{ old('departure_time') }}" required>
                  </div>
                  <div class="col-md-2 border-right border-dark p-1">
                    <input type="date" class="form-control form-control-sm" style="height: 30px; font-size: 0.7rem; padding: 0.25rem 0.4rem;" name="date_to[]" value="{{ old('date_to') }}" required>
                  </div>
                  <div class="col-md-2 p-1">
                    <input type="time" class="form-control form-control-sm" style="height: 30px; font-size: 0.9rem; padding: 0.25rem 0.4rem;" name="arrival_time[]" value="{{ old('arrival_time') }}" required>
                  </div>
                </div>
        `;
        
        // Append the new row to the container
        document.getElementById('itinerary-rows').appendChild(newRow);
    });
});




document.addEventListener('DOMContentLoaded', function() {
  // Initialize modal if using Bootstrap 5
  var travelOrderModal = document.getElementById('travelOrderModal');
  if (travelOrderModal) {
    var modal = new bootstrap.Modal(travelOrderModal);
  }
  

  // Handle form submission
  document.getElementById('submitTravelOrderForm').addEventListener('click', function() {
    document.getElementById('travelOrderForm').submit();
  });
});
</script> -->

<script>
function calculateTotals() {
  let totalAmount = 0;
  let totalDays = 0;
  let grandTotal = 0;

  $('.amount-input').each(function() {
    const row = $(this).data('row');
    const amount = parseFloat($(this).val()) || 0;

    if (row == 3) {
      // TRANSPORTATION / GASOLINE: total = amount lang, no multiplication
      $(`.total-field[data-row="${row}"]`).val(amount.toFixed(2));

      totalAmount += amount;
      // days is ignored for this row, so don't add to totalDays
      grandTotal += amount; // total = amount only
    } else {
      // Other rows: multiply amount * days
      const days = parseFloat($(`.days-input[data-row="${row}"]`).val()) || 0;
      const total = amount * days;

      $(`.total-field[data-row="${row}"]`).val(total.toFixed(2));

      totalAmount += amount;
      totalDays += days;
      grandTotal += total;
    }
  });

  // Update TOTAL AMOUNT row
  $('input[name="totalamount_amount"]').val(totalAmount.toFixed(2));
  $('input[name="totalamount_numofday"]').val(totalDays.toFixed(2));
  $('input[name="totalamount_total"]').val(grandTotal.toFixed(2));
}

$(document).ready(function() {
  // Recalculate when amount or days inputs change
  $('.amount-input, .days-input').on('input', calculateTotals);

  // Initial calculation on page load
  calculateTotals();
});


</script>




<script>
var app = new Vue({
    el: '#appOB',
    data() {
        return {
            date_from: '',
            date_to: '',
            applied_date: '',
            ob_max_date: '',
            min_date: '',
            max_date: '',
        };
    },
    methods: {
        validateDates() {
            if (this.applied_date) {
                const obDate = new Date(this.applied_date);
                obDate.setDate(obDate.getDate() + 1);
                this.min_date = this.applied_date + ' 00:00:00';
                this.ob_max_date = this.applied_date + ' 23:00:00';
                this.max_date = obDate.toISOString().split('T')[0] + ' 23:00:00';
            }
        }
    },
});
</script>
