<div class="modal fade" id="travelOrderModal" tabindex="-1" aria-labelledby="OBDATA" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 1200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="OBDATA">Travel Order Form</h5>
        <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form method='POST' action='new-to' onsubmit="btnOB.disabled = true; return true;" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <!-- Form Content -->
              <div class="container mt-2">
                <div class="row mb-2">
                  <div class="col-md-8 col-sm-12">
                    <h3 class="fw-bold">PASCAL RESOURCES ENERGY, INC.</h3>
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
                                      value="{{ old('applied_date', date('Y-m-d')) }}" readonly>
                                </div>
                              </div>
                                <div class="col-2 p-0 text-center">
                                  T.O. No
                                </div>
                                <input type="text" id="to_number" name="to_number"
                                        class="form-control text-center"
                                        style="width: 100px; height: 40px; font-size: 0.9rem; padding: 0.25rem 0.5rem;"
                                        value=" {{ $toNumber ?? '' }}" readonly>             
                        </div>
                  </div>
                </div>
              </div>
                
            <div class="form-sections">
              <!-- Itinerary Section -->
              <div class="itinerary-section">
                <!-- Desktop Itinerary Table -->
                  <div class="desktop-itinerary">
                    <table class="itinerary-table">
                        <tr>
                            <td colspan="5" class="itinerary-header">ITINERARY</td>
                        </tr>
                        <tr class="itinerary-subheader">
                          <td rowspan="2">DESTINATION</td>
                          <td colspan="2">DEPARTURE</td>
                          <td colspan="2">EXP. ARRIVAL</td>
                        </tr>
                        <tr class="itinerary-subheader">
                          <td>DATE</td>
                          <td>TIME</td>
                          <td>DATE</td>
                          <td>TIME</td>
                        </tr>

                        <tr class="itinerary-row">
                            <td><input type="text" class="form-control form-control-sm destination" placeholder="Destination" name="destination1" value="{{ old('destination1') }}" required></td>
                            <td><input type="date" class="form-control form-control-sm" name="date_from1" value="{{ old('date_from1') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required></td>
                            <td><input type="time" class="form-control form-control-sm" name="departure_time1" value="{{ old('departure_time1') }}" required></td>
                            <td><input type="date" class="form-control form-control-sm" name="date_to1" value="{{ old('date_to1') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required></td>
                            <td><input type="time" class="form-control form-control-sm" name="arrival_time1" value="{{ old('arrival_time1') }}" required></td>
                        </tr>
                      <tr class="itinerary-row">
                            <td><input type="text" class="form-control form-control-sm destination" placeholder="Destination" name="destination2" value="{{ old('destination2') }}"></td>
                            <td><input type="date" class="form-control form-control-sm" name="date_from2" value="{{ old('date_from2') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"></td>
                            <td><input type="time" class="form-control form-control-sm" name="departure_time2" value="{{ old('departure_time2') }}"></td>
                            <td><input type="date" class="form-control form-control-sm" name="date_to2" value="{{ old('date_to2') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"></td>
                            <td><input type="time" class="form-control form-control-sm" name="arrival_time2" value="{{ old('arrival_time2') }}"></td>
                        </tr>
                        <tr class="itinerary-row">
                            <td><input type="text" class="form-control form-control-sm destination" placeholder="Destination" name="destination_3" value="{{ old('destination_3') }}"></td>
                            <td><input type="date" class="form-control form-control-sm" name="date_from_3" value="{{ old('date_from_3') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"></td>
                            <td><input type="time" class="form-control form-control-sm" name="departure_time_3" value="{{ old('departure_time_3') }}"></td>
                            <td><input type="date" class="form-control form-control-sm" name="date_to_3" value="{{ old('date_to_3') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"></td>
                            <td><input type="time" class="form-control form-control-sm" name="arrival_time_3" value="{{ old('arrival_time_3') }}"></td>
                        </tr>
                        <tr class="itinerary-row">
                            <td><input type="text" class="form-control form-control-sm destination" placeholder="Destination" name="destination_4" value="{{ old('destination_4') }}"></td>
                            <td><input type="date" class="form-control form-control-sm" name="date_from_4" value="{{ old('date_from_4') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"></td>
                            <td><input type="time" class="form-control form-control-sm" name="departure_time_4" value="{{ old('departure_time_4') }}"></td>
                            <td><input type="date" class="form-control form-control-sm" name="date_to_4" value="{{ old('date_to_4') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"></td>
                            <td><input type="time" class="form-control form-control-sm" name="arrival_time_4" value="{{ old('arrival_time_4') }}"></td>
                        </tr>
                        <tr class="itinerary-row">
                            <td><input type="text" class="form-control form-control-sm destination" placeholder="Destination" name="destination_5" value="{{ old('destination_5') }}"></td>
                            <td><input type="date" class="form-control form-control-sm" name="date_from_5" value="{{ old('date_from_5') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"></td>
                            <td><input type="time" class="form-control form-control-sm" name="departure_time_5" value="{{ old('departure_time_5') }}"></td>
                            <td><input type="date" class="form-control form-control-sm" name="date_to_5" value="{{ old('date_to_5') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"></td>
                            <td><input type="time" class="form-control form-control-sm" name="arrival_time_5" value="{{ old('arrival_time_5') }}"></td>
                        </tr>
                    </table>
                  </div>

                  <!-- Mobile Itinerary Cards -->
                  <div class="mobile-itinerary">
                      <h5 class="text-center mb-3" style="background-color: #3490dc; color: white; padding: 10px; border-radius: 4px;">ITINERARY</h5>
                          <div id="destination-cards-container">
                            <div class="destination-card">
                                <h6>Destination 1</h6>
                                <div class="form-group">
                                    <label for="destination1">Destination</label>
                                    <input type="text" id="destination1" name="destination" class="form-control" placeholder="Enter destination" value="{{ old('destination') }}" required>
                                </div>
                              
                                <div class="date-time-group">
                                    <div class="form-group">
                                        <label>Departure Date</label>
                                        <input type="date" name="date_from" class="form-control" value="{{ old('date_from') }}" min="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Departure Time</label>
                                        <input type="time" name="departure_time" class="form-control" value="{{ old('departure_time') }}" required>
                                    </div>
                                </div>

                                <div class="date-time-group">
                                    <div class="form-group">
                                        <label>Exp. Arrival Date</label>
                                        <input type="date" name="date_to" class="form-control" value="{{ old('date_to') }}" min="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Exp. Arrival Time</label>
                                        <input type="time" name="arrival_time" class="form-control" value="{{ old('arrival_time') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Destination 2 Card -->
                              <div class="destination-card">
                                  <button type="button" class="remove-destination-btn" onclick="removeDestination(this)">Remove</button>
                                    <h6>Destination 2</h6>
                                    <div class="form-group">
                                        <label>Destination</label>
                                        <input type="text" name="destination_2" class="form-control" placeholder="Enter destination" value="{{ old('destination_2') }}">
                                    </div>
                                    
                                    <div class="date-time-group">
                                        <div class="form-group">
                                            <label>Departure Date</label>
                                            <input type="date" name="date_from_2" class="form-control" value="{{ old('date_from_2') }}" min="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Departure Time</label>
                                            <input type="time" name="departure_time_2" class="form-control" value="{{ old('departure_time_2') }}">
                                        </div>
                                    </div>

                                    <div class="date-time-group">
                                        <div class="form-group">
                                            <label>Exp. Arrival Date</label>
                                            <input type="date" name="date_to_2" class="form-control" value="{{ old('date_to_2') }}" min="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Exp. Arrival Time</label>
                                            <input type="time" name="arrival_time_2" class="form-control" value="{{ old('arrival_time_2') }}">
                                        </div>
                                    </div>
                              </div>
                          </div>
                        <button type="button" class="add-destination-btn" onclick="addDestination()">+ Add Another Destination</button>
                    </div>
               </div>
                
                  <div class="cash-advance">
                      <table class="expense-table">
                          <tr>
                              <td colspan="4" class="table-header">
                                  TRAVEL EXPENSE COMPUTATION
                              </td>
                          </tr>
                          <tr class="column-headers">
                              <td style="width: 30%;">PARTICULARS<br>(ALLOWANCES)</td>
                              <td style="width: 20%;">AMOUNT /<br>LIMIT</td>
                              <td style="width: 20%;">NUMBER<br>OF DAYS</td>
                              <td style="width: 30%;">TOTAL</td>
                          </tr>

                          <tr class="data-row">
                              <td class="particulars-cell">PER DIEM/FIXED</td>
                              <td>
                                  <input type="number" class="form-control amount-input" name="perdiem_amount" data-row="1" required>
                              </td>
                              <td>
                                  <input type="number" class="form-control days-input" name="perdiem_numofday" data-row="1">
                              </td>
                              <td>
                                  <input type="number" class="form-control total-field" name="perdiem_total" data-row="1" readonly>
                              </td>
                          </tr>

                          <tr class="data-row">
                              <td class="particulars-cell">HOTEL/LODGING</td>
                              <td>
                                  <input type="number" class="form-control amount-input" name="hotellodging_amount" data-row="2">
                              </td>
                              <td>
                                  <input type="number" class="form-control days-input" name="hotellodging_numofday" data-row="2">
                              </td>
                              <td>
                                  <input type="number" class="form-control total-field" name="hotellodging_total" data-row="2" readonly>
                              </td>
                          </tr>

                          <tr class="data-row">
                              <td class="particulars-cell">TRANSPORTATION /<br>GASOLINE</td>
                              <td>
                                  <input type="number" class="form-control amount-input" name="transpo_amount" data-row="3" required>
                              </td>
                              <td>
                                  <input type="number" class="form-control days-input" name="transpo_numofday" data-row="3">
                              </td>
                              <td>
                                  <input type="number" class="form-control total-field no-calc" name="transpo_total" data-row="3" readonly>
                              </td>
                          </tr>

                          <tr class="data-row">
                              <td class="particulars-cell">TOTAL FEES</td>
                              <td>
                                  <input type="number" class="form-control amount-input" name="totalfees_amount" data-row="4">
                              </td>
                              <td>
                                  <input type="number" class="form-control days-input" name="totalfees_numofday" data-row="4">
                              </td>
                              <td>
                                  <input type="number" class="form-control total-field" name="totalfees_total" data-row="4" readonly>
                              </td>
                          </tr>

                          <tr class="data-row total-amount-row">
                              <td class="particulars-cell">TOTAL AMOUNT</td>
                              <td>
                                  <input type="number" class="form-control" readonly>
                              </td>
                              <td>
                                  <input type="number" class="form-control" readonly>
                              </td>
                              <td>
                                  <input type="number" class="form-control total-field" name="totalamount_total" readonly>
                              </td>
                          </tr>
                      </table>
                  </div>
            </div>

                @php
                    $to_id = isset($to) && is_object($to) ? $to->id : 'default';
                @endphp

                <div class="row m-0 border-bottom border-left border-right border-dark mt-0">
                    <div class="col-lg-7 col-md-12 border-right border-dark p-2" style="text-align: left;">
                        &nbsp;&nbsp;PURPOSE
                        <textarea class="form-control purpose" name="purpose" rows="10" placeholder="Enter purpose here..."></textarea>
                    </div>
                    <div class="col-lg-5 p-0 text-left">
                        <div class="row m-0 border-bottom border-dark payment" style="height: 40px;">
                            <div class="col-6 p-2 d-flex align-items-center justify-content-center">
                                <input type="radio" name="payment_type" id="ca_checkbox_123" class="me-2 ca-radio"
                                      value="cash advance"
                                      style="width: 17px; height: 17px; margin-right: 20px; margin-bottom: 5px;" 
                                      data-modal-id="123" required>
                                <label for="ca_checkbox_123" class="payment-label">CASH ADVANCE</label>
                            </div>
                            <div class="col-6 p-2 d-flex align-items-center justify-content-center">
                                <input type="radio" name="payment_type" id="pa_checkbox_123" class="me-2 pa-radio"
                                      value="reimbursement"
                                      style="width: 17px; height: 17px; margin-right: 20px; margin-bottom: 5px;" 
                                      data-modal-id="123">
                                <label for="pa_checkbox_123" class="payment-label">REIMBURSEMENT</label>
                            </div>
                        </div>

                        <!-- Mode of Payment Section -->
                        <div id="modeOfPaymentSection_123" class="mode-of-payment-section" style="display: none;">
                            <div class="col-md-12 p-0">
                                <div class="form-check d-flex align-items-center px-5 py-0">
                                    <input class="form-check-input me-1" type="radio" name="mode_payment" id="cash_123" value="cash">
                                    <label class="form-check-label mb-0" for="cash_123">CASH</label>
                                </div>
                            </div>
                            <div class="border-top border-dark p-0">
                                <div class="form-check d-flex align-items-center px-5 py-0">
                                    <input class="form-check-input me-1" type="radio" name="mode_payment" id="check_123" value="check">
                                    <label class="form-check-label mb-0" for="check_123">CHECK</label>
                                </div>
                            </div>
                            <div class="border-top border-bottom border-dark p-0">
                                <div class="form-check d-flex align-items-center px-5 py-0">
                                    <input class="form-check-input me-1" type="radio" name="mode_payment" id="payroll_123" value="payroll">
                                    <label class="form-check-label mb-0" for="payroll_123">CREDIT TO EMPLOYEE'S PAYROLL ACCOUNT</label>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <label>OTHER INSTRUCTIONS</label>
                            <input type="text" class="form-control form-control-sm ins" name="other_instruct" id="other_instruct" placeholder="Enter other instructions...">
                        </div>
                    </div>
                </div>

                <!-- Authorization Text -->
                <div class="border border-dark border-top-0 p-3">
                  <p class="small">I hereby authorize PASCAL RESOURCES ENERGY, INC. to deduct from my salary all Unliquidation Travel Expense without further notice if I fail to comply with my liquidation date, set seven (7) working days after my expected arrival date.</p>
                </div>
                
                <div class="row m-0 border border-dark border-top-0">
                  <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                    <div class="border-dark p-1 text-center">REQUESTING DIVISION/DEPARTMENT</div>
                    <div class="p-1 text-center"> 
                      <input type="text"
                        style="background: transparent; font-size: 11px; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding: 5px 0; line-height: 20px; text-align: center; width: 100%;"
                        value="{{ auth()->user()->employee->department->name }}"
                        name="department"
                        readonly> 
                    </div>
                  </div>
                  <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                    <div class="border-dark p-3 text-center">RC CODE</div>
                    <div class="p-1 text-center"> 
                      <input type="text"
                        style="height: 30px;" 
                        class="form-control form-control-sm text-center"
                        value="{{auth()->user()->employee->cost_center ?? ''}}"
                        name="rc_code"
                        required
                        > 
                    </div>
                  </div>
                <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                    <div class="border-dark p-3 text-center">
                      REQUESTED
                    </div>
                    <div class="p-1 text-center">
                    <button type="button" id="openSignature" 
                          style="background-color: transparent; position: absolute; top: 40px; right: 55px; border: 2px solid red;" 
                          required>
                          <img src="images/sign.png" alt="Open Signature" style="height: 27px;">
                      </button>
                      <img id="sig_image" name="sig_image" src="" alt="Your Signature" 
                          style="height: 55px; position: absolute; right: 35px; top: 32px; border: none; cursor: pointer; display: none;" />
                      <input type="hidden" name="sig_image_data" id="sig_image_data" required>
                          <input type="text"
                          class="text-center"
                          style="background: transparent; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                          value="{{ auth()->user()->employee->first_name }} @if(auth()->user()->employee->middle_initial){{ auth()->user()->employee->middle_initial }}.@endif{{ auth()->user()->employee->last_name }}"
                          name="requestor_name"
                          readonly>
                        <small class="text-center d-block">(Requestor's Signature Over Printed Name)</small>
                    </div>
                </div>
                    @php
                        $supervisor = auth()->user()->employee->immediateSupervisor;

                        $supervisorName = null;

                        if ($supervisor) {
                            $supervisorName = $supervisor->first_name . ' ' .
                                              ($supervisor->middle_initial ? $supervisor->middle_initial . '. ' : '') .
                                              $supervisor->last_name;
                        }
                    @endphp
                    <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                      <div class="border-dark p-3 text-center">
                        CHECKED BY
                      </div>        
                      <div class="p-1">
                        <input type="text" 
                          style="background: transparent; text-align: center; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                          name="approved_by" 
                          value="{{ $supervisorName }}" 
                          readonly
                        >
                        <small class="text-center d-block">Immediate Supervisor</small>
                      </div>
                    </div>
                    
                    <script>
                      window.approvalThreshold = {{ $approvalThreshold ?? 0 }};
                      window.allApprovers = @json($approversForJs ?? []);
                    </script>

                  <div class="col-lg col-md-12 col-sm-12 p-1">
                      <div class="border-dark p-3 text-center">APPROVED BY</div>
                      <div class="p-1">
                          <input type="text"
                              style="background: transparent; text-align: center; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                              name="approved_by_head"
                              value=""
                              readonly>
                          <small class="text-center d-block">Division/Cluster Head</small>
                      </div>
                  </div>
                </div>
                <div class="row m-0 border border-dark border-top-0">
                  <div class="col-12 border-bottom border-dark p-1 text-center fw-bold" style="color: white; background-color: #3490dc; border-color: #3490dc;">REMARKS AND LIQUIDATION DETAILS</div>
                  <div class="col-md-7 col-sm-13 border-right border-dark p-1">
                    <div class="border-dark p-1">REMARKS</div>
                    <div class="p-1"></div>
                  </div>
                  <div class="col-md-3 col-sm-6 border-end border-dark p-1">
                    <div class="border-dark p-1">LIQUIDATION DUE ON:</div>
                    <div class="p-1">
                      <input type="date" class="form-control form-control-sm" style="height: 30px;" name="liquidation_date" readonly>
                    </div>
                  </div>            
                </div>
              <small>Distribution: 1 Copy attached to RFP upon liquidation.</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="btnOb">Submit Travel Order</button>
          </div>
        </form>
    </div>
  </div>
</div>

<style>

.modal-content {
    display: none;
}

.modal.show .modal-content {
    display: block;
}

#sig-canvas {
      border: 2px dotted #CCCCCC;
      border-radius: 15px;
      cursor: crosshair;
    }
    .swal2-popup textarea {
      width: 100% !important;
    }
    .swal2-popup canvas {
      max-width: 100%;
    }

.form-sections {
  display: flex;
}

.Itinerary-section {
  order: 1;
}

.cash-advance {
  order: 2;
}

.desktop-itinerary {
    display: block;
}

.mobile-itinerary {
    display: none;
}

.expense-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #333;
}

.table-header {
    background-color: #3490dc;
    color: white;
    text-align: center;
    padding: 8px;
    border: 1px solid #333;
}

.column-headers {
    border: 1px solid #333;
}

.column-headers td {
    padding: 8px 4px;
    text-align: center;
    border-right: 1px solid #333;
    font-size: 14px;
    height: 62px;
}

.column-headers td:last-child {
    border-right: none;
}

.data-row td {
    border-right: 1px solid #333;
    border-bottom: 1px solid #333;
    vertical-align: middle;
    font-size: 12px;
    height: 38.4px;
}

.data-row td:last-child {
    border-right: none;
}

.particulars-cell {
    background-color: #f8f9fa;
    padding-left: 8px !important;
    text-align: left;
}

.cash-advance .form-control {
    width: 100%;
    height: 35px;
    padding: 6px 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    text-align: center;
}

.form-control:focus {
    outline: none;
    border-color: #3490dc;
    box-shadow: 0 0 0 2px rgba(52, 144, 220, 0.2);
}

.total-amount-row {
    background-color: #e3f2fd;
}

.total-amount-row .form-control {
    background-color: #e3f2fd;
}

.itinerary-table {
    border-left: 1px solid #333;
    border-top: 1px solid #333;
    border-bottom: 1px solid #333;
}

.itinerary-table .form-control {
  width: 108px !important;
  height: 32px !important;
}

.itinerary-table .destination {
  width: 200px !important;
}

.itinerary-header {
    background-color: #3490dc;
    color: white;
    text-align: center;
    padding: 4px;
    border-bottom: 1px solid #333;
}

.itinerary-subheader {
    border-top: 1px solid #333;
    text-align: center;
    font-size: 14px;
}

.itinerary-subheader td {
    padding: 8px;
    border-right: 1px solid #333;
    height: 14.2px !important;
    line-height: 14.2px;
}

.itinerary-subheader td:last-child {
    border-right: none;
}

.itinerary-row .form-control {
  font-size: 12px;
}

.itinerary-row {
    border-top: 1px solid #333;
        height: 38.4px !important;
}

.itinerary-row td {
    border-right: 1px solid #333;
}

.itinerary-row td:last-child {
    border-right: none;
}

.destination-card {
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.destination-card h6 {
    color: #3490dc;
    margin-bottom: 15px;
    text-align: center;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 5px;
    color: #495057;
}

.date-time-group {
    display: flex;
    gap: 10px;
}

.date-time-group .form-group {
    flex: 1;
}

@media (max-width: 992px) {
    .form-sections {
        flex-direction: column;
        gap: 20px;
    }

    .cash-advance {
        order: 2;
    }
}

@media (max-width: 768px) {
    .desktop-itinerary {
        display: none;
    }

    .mobile-itinerary {
        display: block;
    }

    .expense-table {
        font-size: 12px;
    }

    .column-headers td {
        padding: 6px 2px;
        font-size: 11px;
        line-height: 1.2;
    }

    .data-row td {
        padding: 6px 2px;
    }

    .particulars-cell {
        font-size: 11px;
        padding-left: 4px !important;
    }

    .form-control {
        height: 32px;
        font-size: 12px;
        padding: 4px 6px;
    }

    .table-header {
        padding: 8px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .date-time-group {
        flex-direction: column;
        gap: 10px;
    }

    .destination-card {
        padding: 12px;
    }

    .expense-table {
        font-size: 11px;
    }

    .column-headers td {
        font-size: 10px;
        padding: 4px 1px;
        line-height: 1.1;
    }

    .data-row td {
        padding: 4px 1px;
    }

    .form-control {
        height: 30px;
        font-size: 11px;
        padding: 2px 4px;
    }

    .particulars-cell {
        font-size: 10px;
    }
}

.add-destination-btn {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
}

.add-destination-btn:hover {
    background-color: #218838;
}

.remove-destination-btn {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 4px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    float: right;
}

.remove-destination-btn:hover {
    background-color: #c82333;
}

.cash-advance {
  max-width: 100%;
  margin: 0 auto;
  background: white;
  border-radius: 1px;
  overflow: hidden;
}

.expense-table {
  width: 100%;
  border-collapse: collapse;
  border: 1px solid #333;
}

.table-header {
  background-color: #3490dc;
  color: white;
  text-align: center;
  padding: 4px;
  border: 1px solid #333;
}

.column-headers {
  border: 1px solid #333;
}

.column-headers td {
  padding: 8px 4px;
  text-align: center;
  border-right: 1px solid #333;
  font-size: 14px;
  height: 62px;
}

.column-headers td:last-child {
  border-right: none;
}

.data-row td {
  border-right: 1px solid #333;
  border-bottom: 1px solid #333;
  vertical-align: middle;
  font-size: 10px;
  height: 38.4px;
}

.data-row td:last-child {
  border-right: none;
}

.data-row:last-child td {
  border-bottom: none;
}

.particulars-cell {
  background-color: #f8f9fa;
  padding-left: 8px !important;
  text-align: left;
}


.form-control.purpose {
  text-align: left !important;
}

.form-control.ins {
  text-align: left !important;
}

.form-control:focus {
  outline: none;
  border-color: #3490dc;
  box-shadow: 0 0 0 2px rgba(52, 144, 220, 0.2);
}

.form-control[readonly] {
  background-color: #f8f9fa;
  color: #6c757d;
}

.total-amount-row {
  background-color: #e3f2fd;
}

.total-amount-row .form-control {
  background-color: #e3f2fd;
}

/* Mobile Styles */
@media (max-width: 768px) {
  body {
      padding: 10px;
  }

  .expense-table {
      font-size: 12px;
  }

  .column-headers td {
      padding: 6px 2px;
      font-size: 11px;
      line-height: 1.2;
  }

  .data-row td {
      padding: 6px 2px;
  }

  .particulars-cell {
      font-size: 11px;
      padding-left: 4px !important;
  }

  .form-control {
      height: 32px;
      font-size: 12px;
      padding: 4px 6px;
  }

  .table-header {
      padding: 8px;
      font-size: 14px;
  }
}

/* Very small screens */
@media (max-width: 480px) {
  .expense-table {
      font-size: 11px;
  }

  .column-headers td {
      font-size: 10px;
      padding: 4px 1px;
      line-height: 1.1;
  }

  .data-row td {
      padding: 4px 1px;
  }

  .form-control {
      height: 30px;
      font-size: 11px;
      padding: 2px 4px;
  }

  .particulars-cell {
      font-size: 10px;
  }
}

/* Large screens */
@media (min-width: 1200px) {
  .cash-advance {
      max-width: 800px;
  }
}

@media (max-width: 650px) {
  .form-sections {
    flex-direction: column;
    gap: 1rem;
  }

  input.form-control-sm {
    font-size: 0.875rem;
  }
}

 @media (max-width: 576px) {
    .payment-label {
      font-size: 9px;
    }
    .payment {
      border-top: 1px solid #333;
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
  

  /* Hide desktop headers */
  .h.m-0:nth-child(2) {
    display: none;
  }
  
  .Itinerary-section{
    border-right: 1px solid #000 !important;
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

<script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM Content Loaded');
            
            const modalId = "123"; // Simulating your $to_id
            const cashAdvanceRadio = document.getElementById('ca_checkbox_' + modalId);
            const reimbursementRadio = document.getElementById('pa_checkbox_' + modalId);
            const modeSection = document.getElementById('modeOfPaymentSection_' + modalId);
            const debugInfo = document.getElementById('debugInfo');

            console.log('Elements found:', {
                cashAdvanceRadio: !!cashAdvanceRadio,
                reimbursementRadio: !!reimbursementRadio,
                modeSection: !!modeSection
            });

            function toggleModeSection() {
                console.log('toggleModeSection called');
                
                if (!cashAdvanceRadio || !modeSection) {
                    console.error('Required elements not found');
                    debugInfo.textContent = 'Error: Required elements not found';
                    return;
                }

                const isCashAdvanceSelected = cashAdvanceRadio.checked;
                console.log('Cash Advance selected:', isCashAdvanceSelected);
                
                if (isCashAdvanceSelected) {
                    modeSection.style.display = 'block';
                    debugInfo.textContent = 'Cash Advance selected - Mode of Payment section is visible';
                    console.log('Showing mode section');
                } else {
                    modeSection.style.display = 'none';
                    debugInfo.textContent = reimbursementRadio && reimbursementRadio.checked ? 
                        'Reimbursement selected - Mode of Payment section is hidden' : 
                        'No payment type selected';
                    console.log('Hiding mode section');
                }
            }

            // Add event listeners
            if (cashAdvanceRadio) {
                cashAdvanceRadio.addEventListener('change', function() {
                    console.log('Cash advance radio changed:', this.checked);
                    toggleModeSection();
                });
            }

            if (reimbursementRadio) {
                reimbursementRadio.addEventListener('change', function() {
                    console.log('Reimbursement radio changed:', this.checked);
                    toggleModeSection();
                });
            }

            // Initial call
            toggleModeSection();
        });
    </script>
    
<script>
document.addEventListener('DOMContentLoaded', function () {
    const destinationInputs = document.querySelectorAll('.destination');

    destinationInputs.forEach((destinationInput, index) => {
        destinationInput.addEventListener('input', function () {
            const value = destinationInput.value.trim();
            const row = destinationInput.closest('.itinerary-row');
            
            if (!row) return;
            const dateFrom = row.querySelector('[name^="date_from"]');
            const timeFrom = row.querySelector('[name^="departure_time"]');
            const dateTo = row.querySelector('[name^="date_to"]');
            const timeTo = row.querySelector('[name^="arrival_time"]');

            // Set 'required' if destination is filled
            const required = value !== '';
            if (dateFrom) dateFrom.required = required;
            if (timeFrom) timeFrom.required = required;
            if (dateTo) dateTo.required = required;
            if (timeTo) timeTo.required = required;
        });
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
   const amountInputs = document.querySelectorAll('.amount-input');

   amountInputs.forEach((amountInput, index) => {
       amountInput.addEventListener('input', function () {
           const value = amountInput.value.trim();
           const row = amountInput.getAttribute('data-row');
           
           if (!row) return;
           const daysInput = document.querySelector(`.days-input[data-row="${row}"]`);

           // Set 'required' if amount is filled
           const required = value !== '' && value > 0;
           if (daysInput) daysInput.required = required;
       });
   });
});
</script>

<script>
let destinationCount = 2;
const maxDestinations = 5;

function addDestination() {
    if (destinationCount >= maxDestinations) {
        alert("You can only add up to 5 destinations.");
        return;
    }

    destinationCount++;
    const container = document.getElementById('destination-cards-container');

    // 👇 Use destinationCount to generate dynamic names
    const newCard = document.createElement('div');
    newCard.className = 'destination-card';
    newCard.innerHTML = `
        <button type="button" class="remove-destination-btn" onclick="removeDestination(this)">Remove</button>
        <h6>Destination ${destinationCount}</h6>
        <div class="form-group">
            <label>Destination</label>
            <input type="text" class="form-control" name="destination_${destinationCount}" value="{{ old('destination_${destinationCount}') }}" placeholder="Destination">
        </div>
        
        <div class="date-time-group">
            <div class="form-group">
                <label>Departure Date</label>
                <input type="date" class="form-control" name="date_from_${destinationCount}" value="{{ old('date_from_${destinationCount}') }}" min="{{ date('Y-m-d') }}">
            </div>
            <div class="form-group">
                <label>Departure Time</label>
                <input type="time" name="departure_time_${destinationCount}" value="{{ old('departure_time_${destinationCount}') }}" class="form-control">
            </div>
        </div>

        <div class="date-time-group">
            <div class="form-group">
                <label>Exp. Arrival Date</label>
                <input type="date" name="date_to_${destinationCount}" value="{{ old('date_to_${destinationCount}') }}" min="{{ date('Y-m-d') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Exp. Arrival Time</label>
                <input type="time" name="arrival_time_${destinationCount}" value="{{ old('arrival_time_${destinationCount}') }}" class="form-control" required>
            </div>
        </div>
    `;

    container.appendChild(newCard);
}

function removeDestination(button) {
    const card = button.closest('.destination-card');
    card.remove();
    destinationCount--;
    updateDestinationNumbers();
}

document.addEventListener('DOMContentLoaded', function() {
    function updateRequiredFieldsForViewport() {
        const isMobile = window.innerWidth <= 768;
        
        const desktopFields = document.querySelectorAll('.desktop-itinerary input, .desktop-itinerary select');
        desktopFields.forEach(field => {
            if (isMobile) {
                field.removeAttribute('required');
            } else {
                if (field.dataset.originalRequired === 'true') {
                    field.setAttribute('required', 'required');
                }
            }
        });
        
        const mobileFields = document.querySelectorAll('.mobile-itinerary input, .mobile-itinerary select');
        mobileFields.forEach(field => {
            if (!isMobile) {
                field.removeAttribute('required');
            } else {
                if (field.dataset.originalRequired === 'true') {
                    field.setAttribute('required', 'required');
                }
            }
        });
    }
    
    window.addEventListener('resize', updateRequiredFieldsForViewport);
    document.querySelector('form').addEventListener('submit', updateRequiredFieldsForViewport);
    
    updateRequiredFieldsForViewport();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const liquidationDateInput = document.querySelector('input[name="liquidation_date"]');

    function getLatestDateTo() {
        const dateToInputs = Array.from(document.querySelectorAll('input[name^="date_to"]'));
        let latestDate = null;

        dateToInputs.forEach(input => {
            const dateValue = input.value;
            const dateObj = new Date(dateValue);
            if (!isNaN(dateObj)) {
                if (!latestDate || dateObj > latestDate) {
                    latestDate = dateObj;
                }
            }
        });

        return latestDate;
    }

    function calculateLiquidationDate(baseDate) {
        let daysToAdd = 0;
        let weekdaysAdded = 0;

        while (weekdaysAdded < 7) {
            daysToAdd++;
            const tempDate = new Date(baseDate);
            tempDate.setDate(tempDate.getDate() + daysToAdd);
            const dayOfWeek = tempDate.getDay();

            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                weekdaysAdded++;
            }
        }

        const finalDate = new Date(baseDate);
        finalDate.setDate(baseDate.getDate() + daysToAdd);
        return finalDate;
    }

    function updateLiquidationDate() {
        const latestDate = getLatestDateTo();
        if (!latestDate) return;

        const liquidationDate = calculateLiquidationDate(latestDate);
        const year = liquidationDate.getFullYear();
        const month = String(liquidationDate.getMonth() + 1).padStart(2, '0');
        const day = String(liquidationDate.getDate()).padStart(2, '0');
        liquidationDateInput.value = `${year}-${month}-${day}`;
    }

    const dateToInputs = document.querySelectorAll('input[name^="date_to"]');
    dateToInputs.forEach(input => {
        input.addEventListener('change', updateLiquidationDate);
    });

    updateLiquidationDate();
});
</script>

<script>
function calculateTotals($container) {
  let totalAmount = 0;
  let totalDays = 0;
  let grandTotal = 0;

  $container.find('.amount-input').each(function () {
    const row = $(this).data('row');
    const amount = parseFloat($(this).val()) || 0;

    if (row == 3) {
      $container.find(`.total-field[data-row="${row}"]`).val(amount.toFixed(2));
      totalAmount += amount;
      grandTotal += amount;
    } else {
      const days = parseFloat($container.find(`.days-input[data-row="${row}"]`).val()) || 0;
      const total = amount * days;
      $container.find(`.total-field[data-row="${row}"]`).val(total.toFixed(2));

      totalAmount += amount;
      totalDays += days;
      grandTotal += total;
    }
  });

  $container.find('input[name="totalamount_amount"]').val(totalAmount.toFixed(2));
  $container.find('input[name="totalamount_numofday"]').val(totalDays.toFixed(2));
  $container.find('input[name="totalamount_total"]').val(grandTotal.toFixed(2));

  updateApproverDisplay(grandTotal);
}

function updateApproverDisplay(totalAmount) {
  const approvalThreshold = window.approvalThreshold || 0;
  const allApprovers = window.allApprovers || [];
  
  const approverInput = $('input[name="approved_by_head"]');
  
  if (!approverInput.length) return;
  
  const firstApprover = allApprovers.find(approver => approver.position === 'division_head') || allApprovers[0];
  const finalApprover = allApprovers.find(approver => approver.as_final === 'on');
  
  if (totalAmount > approvalThreshold) {
    if (finalApprover && finalApprover.approver_info) {
      approverInput.val(finalApprover.approver_info.name || finalApprover.approver_info.full_name);
    }
  } else {
    if (firstApprover && firstApprover.approver_info) {
      approverInput.val(firstApprover.approver_info.name || firstApprover.approver_info.full_name);
      
      approverInput.siblings('small').text('Division/Cluster Head');
      
      approverInput.css('background-color', 'transparent');
      approverInput.siblings('small').css('color', '');
    }
  }
}

$(document).ready(function () {
  $('.cash-advance').each(function () {
    const $this = $(this);
    $this.find('.amount-input, .days-input').on('input', function () {
      calculateTotals($this);
    });

    calculateTotals($this);
  });
  
  $('input[name="totalamount_total"]').on('input', function() {
    const totalAmount = parseFloat($(this).val()) || 0;
    updateApproverDisplay(totalAmount);
  });
});

function triggerApproverUpdate() {
  const totalAmount = parseFloat($('input[name="totalamount_total"]').val()) || 0;
  updateApproverDisplay(totalAmount);
}
</script>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="new-to"]');
    const btnOb = document.getElementById('btnOb');
    
    if (form && btnOb) {
        form.addEventListener('submit', function(e) {
            const signatureData = document.getElementById('sig_image_data');
            
            if (!signatureData || !signatureData.value || signatureData.value.trim() === '') {
                e.preventDefault();
                btnOb.disabled = false;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Signature Required',
                    text: 'Please provide your signature above your NAME, just click the "Sign Here" the one with the red border before submitting the form.',
                    confirmButtonText: 'OK',
                    padding: '2em'
                });
                return false;
            }
            
            btnOb.disabled = true;
            return true;
        });
    }
});
</script>

<script>
document.getElementById('openSignature').addEventListener('click', function () {
  Swal.fire({
    title: 'E-Signature',
    html: `
      <p>Sign in the canvas below and save your signature as an image!</p>
      <canvas id="sig-canvas" width="500" height="160" style="border:1px solid #ccc;" required></canvas>
      <br><br>
      <button type="button" class="btn btn-primary" id="sig-submitBtn">Submit Signature</button>
      <button type="button" class="btn btn-secondary" id="sig-clearBtn">Clear Signature</button>
      <br><br>
      <textarea id="sig-dataUrl" class="form-control" rows="2" style="display: none;">Data URL for your signature will go here!</textarea>
    `,
    showConfirmButton: false,
    width: 600,
    didOpen: () => {
      initSignatureCanvas();
    }
  });
});

function initSignatureCanvas() {
  const canvas = document.getElementById("sig-canvas");
  const ctx = canvas.getContext("2d");
  ctx.strokeStyle = "#222";
  ctx.lineWidth = 4;

  let drawing = false;
  let lastPos = { x: 0, y: 0 };
  let mousePos = { x: 0, y: 0 };

  function getMousePos(canvasDom, mouseEvent) {
    const rect = canvasDom.getBoundingClientRect();
    return {
      x: mouseEvent.clientX - rect.left,
      y: mouseEvent.clientY - rect.top
    };
  }

  function getTouchPos(canvasDom, touchEvent) {
    const rect = canvasDom.getBoundingClientRect();
    return {
      x: touchEvent.touches[0].clientX - rect.left,
      y: touchEvent.touches[0].clientY - rect.top
    };
  }

  function renderCanvas() {
    if (drawing) {
      ctx.beginPath();
      ctx.moveTo(lastPos.x, lastPos.y);
      ctx.lineTo(mousePos.x, mousePos.y);
      ctx.stroke();
      ctx.closePath();
      lastPos = mousePos;
    }
  }

  (function drawLoop() {
    requestAnimationFrame(drawLoop);
    renderCanvas();
  })();

  canvas.addEventListener("mousedown", (e) => {
    drawing = true;
    lastPos = getMousePos(canvas, e);
  });
  canvas.addEventListener("mouseup", () => drawing = false);
  canvas.addEventListener("mousemove", (e) => mousePos = getMousePos(canvas, e));

  canvas.addEventListener("touchstart", (e) => {
    const touch = e.touches[0];
    const me = new MouseEvent("mousedown", {
      clientX: touch.clientX,
      clientY: touch.clientY
    });
    canvas.dispatchEvent(me);
  });
  canvas.addEventListener("touchmove", (e) => {
    const touch = e.touches[0];
    const me = new MouseEvent("mousemove", {
      clientX: touch.clientX,
      clientY: touch.clientY
    });
    canvas.dispatchEvent(me);
  });
  canvas.addEventListener("touchend", () => {
    const me = new MouseEvent("mouseup", {});
    canvas.dispatchEvent(me);
  });

  document.body.addEventListener("touchstart", (e) => {
    if (e.target === canvas) e.preventDefault();
  }, { passive: false });
  document.body.addEventListener("touchend", (e) => {
    if (e.target === canvas) e.preventDefault();
  }, { passive: false });
  document.body.addEventListener("touchmove", (e) => {
    if (e.target === canvas) e.preventDefault();
  }, { passive: false });

  // Save Signature
  document.getElementById("sig-submitBtn").addEventListener("click", () => {
    const dataUrl = canvas.toDataURL();
    document.getElementById("sig-dataUrl").value = dataUrl;
    document.getElementById("sig_image").src = dataUrl;
    document.getElementById("sig_image_data").value = dataUrl;

    document.getElementById("openSignature").style.display = "none";
    document.getElementById("sig_image").style.display = "block";

    Swal.close();
  });


  document.getElementById("sig-clearBtn").addEventListener("click", () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById("sig-dataUrl").value = "Data URL for your signature will go here!";
    document.getElementById("sig_image").src = "";
    document.getElementById("sig_image_data").value = "";
    document.getElementById("openSignature").style.display = "block";
    document.getElementById("sig_image").style.display = "none";
  });
}

document.getElementById("sig_image").addEventListener("click", () => {
  document.getElementById("openSignature").click();
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
