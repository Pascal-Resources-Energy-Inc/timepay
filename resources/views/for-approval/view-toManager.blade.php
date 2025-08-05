@foreach($tos as $form_approval)
  <div class="modal fade" id="view-modal-{{ $form_approval->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 1200px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
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
                          <input type="" id="applied_date" name="applied_date"
                              class="form-control"
                              style="width: 100px; height: 40px; font-size: 0.7rem; padding: 0.25rem 0.5rem;"
                              value="{{ $form_approval->applied_date ? \Carbon\Carbon::parse($form_approval->applied_date)->format('F j, Y') : '' }}">
                        </div>
                      </div>
                      <div class="col-2 p-0 text-center">T.O. No.</div>
                      <input type="text" id="to_number" name="to_number"
                              class="form-control text-center"
                              style="width: 100px; height: 40px; font-size: 0.9rem; padding: 0.25rem 0.5rem;"
                              value="{{ $form_approval->to_number }}" readonly>    
                    </div>
                  </div>
                </div>
              </div>
            
            <div class="form-sections">
            <div class="">
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
                            <td><input type="" class="form-control form-control-sm destination" name="destination" value="{{ $form_approval->destination }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="date_from" value="{{ date('M d, Y', strtotime($form_approval->date_from)) }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="departure_time" value="{{ date('h:i A', strtotime($form_approval->date_from)) }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="date_to" value="{{ date('M d, Y', strtotime($form_approval->date_to)) }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="arrival_time" value="{{ date('h:i A', strtotime($form_approval->date_to)) }}" disabled></td>
                        </tr>
                        <tr class="itinerary-row">
                            <td><input type="" class="form-control form-control-sm destination" name="destination_2" value="{{ $form_approval->destination_2 }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="date_from_2" value="{{ $form_approval->date_from_2 ? date('M d, Y', strtotime($form_approval->date_from_2)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="departure_time_2" value="{{ $form_approval->date_from_2 ? date('h:i A', strtotime($form_approval->date_from_2)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="date_to_2" value="{{ $form_approval->date_to_2 ? date('M d, Y', strtotime($form_approval->date_to_2)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="arrival_time_2" value="{{ $form_approval->date_to_2 ? date('h:i A', strtotime($form_approval->date_to_2)) : '' }}" disabled></td>
                        </tr>
                        <tr class="itinerary-row">
                            <td><input type="" class="form-control form-control-sm destination" name="destination_3" value="{{ $form_approval->destination_3 }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="date_from_3" value="{{ $form_approval->date_from_3 ? date('M d, Y', strtotime($form_approval->date_from_3)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="departure_time_3" value="{{ $form_approval->date_from_3 ? date('h:i A', strtotime($form_approval->date_from_3)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="date_to_3" value="{{ $form_approval->date_to_3 ? date('M d, Y', strtotime($form_approval->date_to_3)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="arrival_time_3" value="{{ $form_approval->date_to_3 ? date('h:i A', strtotime($form_approval->date_to_3)) : '' }}" disabled></td>
                        </tr>
                        <tr class="itinerary-row">
                            <td><input type="" class="form-control form-control-sm destination" name="destination_4" value="{{ $form_approval->destination_4 }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="date_from_4" value="{{ $form_approval->date_from_4 ? date('M d, Y', strtotime($form_approval->date_from_4)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="departure_time_4" value="{{ $form_approval->date_from_4 ? date('h:i A', strtotime($form_approval->date_from_4)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="date_to_4" value="{{ $form_approval->date_to_4 ? date('M d, Y', strtotime($form_approval->date_to_4)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="arrival_time_4" value="{{ $form_approval->date_to_4 ? date('h:i A', strtotime($form_approval->date_to_4)) : '' }}"disabled></td>
                        </tr>
                        <tr class="itinerary-row">
                            <td><input type="" class="form-control form-control-sm destination" name="destination_5" value="{{ $form_approval->destination_5 }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="date_from_5" value="{{ $form_approval->date_from_5 ? date('M d, Y', strtotime($form_approval->date_from_5)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="departure_time_5" value="{{ $form_approval->date_from_5 ? date('h:i A', strtotime($form_approval->date_from_5)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="date_to_5" value="{{ $form_approval->date_to_5 ? date('M d, Y', strtotime($form_approval->date_to_5)) : '' }}" disabled></td>
                            <td><input type="" class="form-control form-control-sm" name="arrival_time_5" value="{{ $form_approval->date_to_5 ? date('h:i A', strtotime($form_approval->date_to_5)) : '' }}" disabled></td>
                        </tr>
                    </table>
                </div>

                <div class="mobile-itinerary">
                    <h5 class="text-center mb-3" style="background-color: #3490dc; color: white; padding: 10px; border-radius: 4px;">ITINERARY</h5>
                    <div id="">
                        {{-- Destination 1 (Always Visible) --}}
                        <div class="destination-card">
                            <h6>Destination 1</h6>
                            <div class="form-group">
                                <label for="mobile_destination1">Destination</label>
                                <input type="text" id="mobile_destination1" class="form-control" name="destination" value="{{ $form_approval->destination }}" disabled>
                            </div>
                            
                            <div class="date-time-group">
                                <div class="form-group">
                                    <label>Departure Date</label>
                                    <input type="text" class="form-control" name="date_from" value="{{ date('M d, Y', strtotime($form_approval->date_from)) }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label>Departure Time</label>
                                    <input type="text" class="form-control" name="departure_time" value="{{ date('h:i A', strtotime($form_approval->date_from)) }}" disabled>
                                </div>
                            </div>

                            <div class="date-time-group">
                                <div class="form-group">
                                    <label>Exp. Arrival Date</label>
                                    <input type="text" class="form-control" name="date_to" value="{{ date('M d, Y', strtotime($form_approval->date_to)) }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label>Exp. Arrival Time</label>
                                    <input type="text" class="form-control" name="arrival_time" value="{{ date('h:i A', strtotime($form_approval->date_to)) }}" disabled>
                                </div>
                            </div>
                        </div>

                        {{-- Destination 2 --}}
                        @if(!empty($form_approval->destination_2))
                            <div class="destination-card">
                                <h6>Destination 2</h6>
                                <div class="form-group">
                                    <label>Destination</label>
                                    <input type="text" class="form-control" name="destination_2" value="{{ $form_approval->destination_2 }}" disabled>
                                </div>

                                <div class="date-time-group">
                                    <div class="form-group">
                                        <label>Departure Date</label>
                                        <input type="text" class="form-control" name="date_from_2" value="{{ $form_approval->date_from_2 ? date('M d, Y', strtotime($form_approval->date_from_2)) : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Departure Time</label>
                                        <input type="text" class="form-control" name="departure_time_2" value="{{ $form_approval->date_from_2 ? date('h:i A', strtotime($form_approval->date_from_2)) : '' }}" disabled>
                                    </div>
                                </div>

                                <div class="date-time-group">
                                    <div class="form-group">
                                        <label>Exp. Arrival Date</label>
                                        <input type="text" class="form-control" name="date_to_2" value="{{ $form_approval->date_to_2 ? date('M d, Y', strtotime($form_approval->date_to_2)) : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Exp. Arrival Time</label>
                                        <input type="text" class="form-control" name="arrival_time_2" value="{{ $form_approval->date_to_2 ? date('h:i A', strtotime($form_approval->date_to_2)) : '' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Destination 3 --}}
                        @if(!empty($form_approval->destination_3))
                            <div class="destination-card">
                                <h6>Destination 3</h6>
                                <div class="form-group">
                                    <label>Destination</label>
                                    <input type="text" class="form-control" name="destination_3" value="{{ $form_approval->destination_3 }}" disabled>
                                </div>

                                <div class="date-time-group">
                                    <div class="form-group">
                                        <label>Departure Date</label>
                                        <input type="text" class="form-control" name="date_from_3" value="{{ $form_approval->date_from_3 ? date('M d, Y', strtotime($form_approval->date_from_3)) : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Departure Time</label>
                                        <input type="text" class="form-control" name="departure_time_3" value="{{ $form_approval->date_from_3 ? date('h:i A', strtotime($form_approval->date_from_3)) : '' }}" disabled>
                                    </div>
                                </div>

                                <div class="date-time-group">
                                    <div class="form-group">
                                        <label>Exp. Arrival Date</label>
                                        <input type="text" class="form-control" name="date_to_3" value="{{ $form_approval->date_to_3 ? date('M d, Y', strtotime($form_approval->date_to_3)) : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Exp. Arrival Time</label>
                                        <input type="text" class="form-control" name="arrival_time_3" value="{{ $form_approval->date_to_3 ? date('h:i A', strtotime($form_approval->date_to_3)) : '' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Destination 4 --}}
                        @if(!empty($form_approval->destination_4))
                            <div class="destination-card">
                                <h6>Destination 4</h6>
                                <div class="form-group">
                                    <label>Destination</label>
                                    <input type="text" class="form-control" name="destination_4" value="{{ $form_approval->destination_4 }}" disabled>
                                </div>

                                <div class="date-time-group">
                                    <div class="form-group">
                                        <label>Departure Date</label>
                                        <input type="text" class="form-control" name="date_from_4" value="{{ $form_approval->date_from_4 ? date('M d, Y', strtotime($form_approval->date_from_4)) : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Departure Time</label>
                                        <input type="text" class="form-control" name="departure_time_4" value="{{ $form_approval->date_from_4 ? date('h:i A', strtotime($form_approval->date_from_4)) : '' }}" disabled>
                                    </div>
                                </div>

                                <div class="date-time-group">
                                    <div class="form-group">
                                        <label>Exp. Arrival Date</label>
                                        <input type="text" class="form-control" name="date_to_4" value="{{ $form_approval->date_to_4 ? date('M d, Y', strtotime($form_approval->date_to_4)) : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Exp. Arrival Time</label>
                                        <input type="text" class="form-control" name="arrival_time_4" value="{{ $form_approval->date_to_4 ? date('h:i A', strtotime($form_approval->date_to_4)) : '' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Destination 5 --}}
                        @if(!empty($form_approval->destination_5))
                            <div class="destination-card">
                                <h6>Destination 5</h6>
                                <div class="form-group">
                                    <label>Destination</label>
                                    <input type="text" class="form-control" name="destination_5" value="{{ $form_approval->destination_5 }}" disabled>
                                </div>

                                <div class="date-time-group">
                                    <div class="form-group">
                                        <label>Departure Date</label>
                                        <input type="text" class="form-control" name="date_from_5" value="{{ $form_approval->date_from_5 ? date('M d, Y', strtotime($form_approval->date_from_5)) : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Departure Time</label>
                                        <input type="text" class="form-control" name="departure_time_5" value="{{ $form_approval->date_from_5 ? date('h:i A', strtotime($form_approval->date_from_5)) : '' }}" disabled>
                                    </div>
                                </div>

                                <div class="date-time-group">
                                    <div class="form-group">
                                        <label>Exp. Arrival Date</label>
                                        <input type="text" class="form-control" name="date_to_5" value="{{ $form_approval->date_to_5 ? date('M d, Y', strtotime($form_approval->date_to_5)) : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Exp. Arrival Time</label>
                                        <input type="text" class="form-control" name="arrival_time_5" value="{{ $form_approval->date_to_5 ? date('h:i A', strtotime($form_approval->date_to_5)) : '' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
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
                                <input type="number" class="form-control amount-input" name="perdiem_amount" value="{{ $form_approval->perdiem_amount }}" data-row="1" disabled>
                            </td>
                            <td>
                                <input type="number" class="form-control days-input" name="perdiem_numofday" value="{{ $form_approval->perdiem_numofday }}" data-row="1" disabled>
                            </td>
                            <td>
                                <input type="number" class="form-control total-field" value="{{ $form_approval->perdiem_total }}" data-row="1" disabled>
                            </td>
                        </tr>

                        <tr class="data-row">
                            <td class="particulars-cell">HOTEL/LODGING</td>
                            <td>
                                <input type="number" class="form-control amount-input" name="hotellodging_amount" value="{{ $form_approval->hotellodging_amount }}" data-row="2" disabled>
                            </td>
                            <td>
                                <input type="number" class="form-control days-input" name="hotellodging_numofday" value="{{ $form_approval->hotellodging_numofday }}" data-row="2" disabled>
                            </td>
                            <td>
                                <input type="number" class="form-control total-field" name="hotellodging_total" value="{{ $form_approval->hotellodging_total }}" data-row="2" disabled>
                            </td>
                        </tr>

                        <tr class="data-row">
                            <td class="particulars-cell">TRANSPORTATION /<br>GASOLINE</td>
                            <td>
                                <input type="number" class="form-control amount-input" name="transpo_amount" value="{{ $form_approval->transpo_amount }}" data-row="3" disabled>
                            </td>
                            <td>
                                <input type="number" class="form-control days-input" name="transpo_numofday" value="{{ $form_approval->transpo_numofday }}" data-row="3" disabled>
                            </td>
                            <td>
                                <input type="number" class="form-control total-field no-calc" name="transpo_total" value="{{ $form_approval->transpo_total }}" data-row="3" disabled>
                            </td>
                        </tr>

                        <tr class="data-row">
                            <td class="particulars-cell">TOTAL FEES</td>
                            <td>
                                <input type="number" class="form-control amount-input" name="totalfees_amount" value="{{ $form_approval->totalfees_amount }}" data-row="4" disabled>
                            </td>
                            <td>
                                <input type="number" class="form-control days-input" name="totalfees_numofday" value="{{ $form_approval->totalfees_numofday }}" data-row="4" disabled>
                            </td>
                            <td>
                                <input type="number" class="form-control total-field" name="totalfees_total" value="{{ $form_approval->totalfees_total }}" data-row="4" disabled>
                            </td>
                        </tr>

                        <tr class="data-row total-amount-row">
                            <td class="particulars-cell">TOTAL AMOUNT</td>
                            <td>
                                <input type="number" class="form-control" value="{{ $form_approval->totalamount_amount }}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control" value="{{ $form_approval->totalamount_numofday }}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control total-field" name="totalamount_total" value="{{ $form_approval->totalamount_total }}" data-row="5" readonly>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="row m-0 border-bottom border-left border-right border-dark mt-0">
                <div class="col-lg-7 col-md-12 border-right border-dark p-2">       
                    <label class="d-block mb-2">PURPOSE</label>
                    <textarea class="form-control" name="purpose" rows="10" readonly
                        style="text-align: left; padding: 0.5rem;">  {{ $form_approval->purpose }}
                    </textarea>
                </div>
                <div class="col-lg-5 p-0 text-left">
                  <div class="row m-0 border-bottom border-dark payment" style="height: 40px;">
                      <div class="col-6 p-2 d-flex align-items-center justify-content-center">
                        <input 
                          type="checkbox" 
                          name="caradio" 
                          id="ca_checkbox" 
                          value="cash_advance"
                          class="me-2" 
                          style="width: 17px; height: 17px; margin-right: 20px; margin-bottom: 5px;"
                          {{ $form_approval->payment_type === 'cash advance' ? 'checked' : '' }}
                          disabled
                        >
                        <label for="ca_checkbox" class="payment-label">CASH ADVANCE</label>
                      </div>
                      <div class="col-6 p-2 d-flex align-items-center justify-content-center">
                        <input 
                          type="checkbox" 
                          name="caradio" 
                          id="pa_checkbox" 
                          value="reimbursement"
                          class="me-2" 
                          style="width: 17px; height: 17px; margin-right: 20px; margin-bottom: 5px;"
                          {{ $form_approval->payment_type === 'reimbursement' ? 'checked' : '' }}
                          disabled
                        >
                        <label for="pa_checkbox" class="payment-label">REIMBURSEMENT</label>
                      </div>
                    </div>
                    @if ($form_approval->payment_type !== 'reimbursement')
                    <div id="modeOfPaymentSection">
                        <div class="col-md-12 p-0">
                          <div class="form-check d-flex align-items-center px-5 py-0">
                            <input 
                              class="form-check-input me-2" 
                              type="checkbox" 
                              name="mode_payment" 
                              id="cash" 
                              value="cash"
                              {{ $form_approval->mode_payment === 'cash' ? 'checked' : '' }}
                              disabled
                            >
                            <label class="form-check-label mb-0" for="cash">CASH</label>
                          </div>
                        </div>
                        <div class="border-top border-dark p-0">
                          <div class="form-check d-flex align-items-center px-5 py-0">
                            <input 
                              class="form-check-input me-2" 
                              type="checkbox" 
                              name="mode_payment" 
                              id="check" 
                              value="check"
                              {{ $form_approval->mode_payment === 'check' ? 'checked' : '' }}
                              disabled
                            >
                            <label class="form-check-label mb-0" for="check">CHECK</label>
                          </div>
                        </div>
                        <div class="border-top border-bottom border-dark p-0">
                          <div class="form-check d-flex align-items-center px-5 py-0">
                            <input 
                              class="form-check-input me-2" 
                              type="checkbox" 
                              name="mode_payment" 
                              id="payroll" 
                              value="payroll"
                              {{ $form_approval->mode_payment === 'payroll' ? 'checked' : '' }}
                            disabled
                            >
                            <label class="form-check-label mb-0" for="payroll">CREDIT TO EMPLOYEE'S PAYROLL ACCOUNT</label>
                          </div>
                        </div>
                  </div>
                  @endif

                  <!-- Other Instructions -->
                  <div class="p-1">
                    <label>OTHER INSTRUCTIONS</label>
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      name="other_instruct" 
                      id="other_instruct" 
                      value="{{ $form_approval->other_instruct}}"
                    readonly>
                  </div>
                </div>
            </div>
            
            <div class="border border-dark border-top-0 p-3">
              <p class="small">I hereby authorize PASCAL RESOURCES ENERGY, INC. to deduct from my salary all Unliquidated Cash Advance without further notice if I fail to comply with my liquidation date, set seven (7) working days after my expected arrival date.</p>
            </div>
            
            <div class="row m-0 border border-dark border-top-0">
                <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                    <div class="border-dark p-1 text-center">
                      REQUESTING DIVISION/DEPARTMENT
                    </div>
                    <div class="p-1 text-center">
                    <input type="text"
                      style="background: transparent; font-size: 11px; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding: 5px 0; line-height: 20px; text-align: center; width: 100%;"
                      name="department" value="{{ $form_approval->user->employee->department->name }}"
                      readonly>
                    </div>
                </div>
                <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                  <div class="border-dark p-3 text-center">RC CODE</div>
                  <div class="p-1 text-center"> 
                    <input type="text"
                      class="text-center"
                      style="background: transparent; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                      value="{{ $form_approval->rc_code }}"
                      name="cost_center"
                      readonly> 
                  </div>
                </div>
                <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                  <div class="border-dark p-3 text-center">REQUESTED BY</div>
                  <div class="p-1 text-center">
                     @if($form_approval->sig_image)
                       @php
                            try {
                                $decryptedSig = Crypt::decryptString($form_approval->sig_image);
                            } catch (Exception $e) {
                                $decryptedSig = null;
                            }
                        @endphp
                        @if($decryptedSig)
                                <img src="data:image/png;base64,{{ $decryptedSig }}" 
                                    alt="Signature" 
                                    style="height: 55px; position: absolute; right: 35px; top: 32px; border: none;">
                          <input type="hidden" name="sig_image" value="{{ $decryptedSig }}">                            
                        @endif
                    @endif
                     <input type="text"
                      style="background: transparent; font-size: 15px; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding: 5px 0; line-height: 20px; text-align: center; width: 100%;"
                      name="requestor_name" 
                      value="{{ $form_approval->employee->first_name }} @if($form_approval->employee->middle_initial) {{ $form_approval->employee->middle_initial }}. @endif{{ $form_approval->employee->last_name }}"
                      readonly>
                      <small class="text-center d-block">(Requestor's Signature Over Printed Name)</small>
                  </div>
                </div>
                @if ($to->status == 'Pending' || $to->status == 'Cancelled')
                    <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                      <div class="border-dark p-3 text-center">CHECKED BY</div>
                      @php
                          $finalDate = $form_approval->approved_immediate_sup 
                              ? \Carbon\Carbon::parse($form_approval->approved_immediate_sup)->format('d-M-Y')
                              : ($form_approval->approved_date 
                                  ? \Carbon\Carbon::parse($form_approval->approved_date)->format('d-M-Y')
                                  : '');
                        
                          $supervisor = auth()->user()->employee->immediateSupervisor;

                          $supervisorName = null;

                          if ($supervisor) {
                              $supervisorName = $supervisor->first_name . ' ' .
                                                ($supervisor->middle_initial ? $supervisor->middle_initial . '. ' : '') .
                                                $supervisor->last_name;
                          }
                      @endphp
                        <div style="position: relative;">
                            <span style="position: absolute; top: -5px; right: 17px; font-size: 0.8rem;" name="approved">
                                <small>System Approved Date: {{ $finalDate }}</small>
                            </span>
                            <input type="hidden" name="display_approved_date" value="{{ $finalDate }}">
                        </div>
                      <div class="p-1">
                        <input type="text" 
                          style="background: transparent; text-align: center; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                          name="approved_by" 
                          value="{{ $supervisorName }}" 
                          readonly>
                          <small class="text-center d-block">Immediate Supervisor</small>
                      </div>
                    </div>
                    <div class="col-lg col-md-12 col-sm-12 p-1">
                      <div class="border-dark p-3 text-center">APPROVED BY</div>
                      <div class="p-1">
                        @php
                            $approvedDateFormatted = $form_approval->approved_date 
                                ? \Carbon\Carbon::parse($form_approval->approved_date)->format('d-M-Y') 
                                : '';
                        @endphp
                        <div style="position: relative;">
                          <span style="position: absolute; top: -5px; right: 17px; font-size: 0.8rem;">
                            <small>System Approved Date: {{ $approvedDateFormatted }}</small>
                          </span>
                          <input type="hidden" name="display_approved_final" value="{{ $approvedDateFormatted }}">
                        </div>
                        <div class="p-1">
                          @if (isset($approvalThreshold) && isset($approvalThreshold->higher_than) && $form_approval->totalamount_total > $approvalThreshold->higher_than)
                              @if ($form_approval->final_approver)
                                  <input type="text"
                                      style="background: transparent; text-align: center; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                                      name="approved_by"
                                      value="{{ $form_approval->final_approver->name }}"
                                      readonly>
                              @else
                                  <p>No final approver found.</p>
                              @endif
                          @else
                              <input type="text"
                                  style="background: transparent; text-align: center; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                                  name="approved_by"
                                  value="{{ $form_approval->approver->first()->approver_info->name ?? ($form_approval->employee->immediateSupervisor->first_name . ($form_approval->employee->immediateSupervisor->middle_initial ? ' ' . $form_approval->employee->immediateSupervisor->middle_initial . '.' : '') . ' ' . $form_approval->employee->immediateSupervisor->last_name) }}"
                                  readonly>
                          @endif
                        <small class="text-center d-block">Division/Cluster Head</small>
                      </div>
                      </div>
                    </div>
                  </div>
                @endif
                @if ($to->status == 'Approved' || $to->status == "Declined")
                <div class="col-lg col-md-6 col-sm-12 border-right border-dark p-1">
                  <div class="border-dark p-3 text-center">CHECKED BY</div>
                    <div style="position: relative;">
                        <span style="position: absolute; top: -5px; right: 17px; font-size: 0.8rem;" name="approved">
                            <small>System Approved Date: {{ $form_approval->approved_date ? \Carbon\Carbon::parse($form_approval->approved_date)->format('F j, Y') : '' }}</small>
                        </span>
                        <input type="hidden" name="display_approved_date" value="{{ $form_approval->approved_date ? \Carbon\Carbon::parse($form_approval->approved_date)->format('F j, Y') : '' }}">
                    </div>
                  <div class="p-1 text-center">
                    <input type="text" 
                    style="background: transparent; text-align: center; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                    name="checked_by" 
                    value="{{ $form_approval->approvedBy->name ?? '' }}"
                    readonly>
                    <small class="text-center d-block">Immediate Supervisor</small>
                  </div>
                </div>
                <div class="col-lg col-md-12 col-sm-12 p-1">
                  <div class="border-dark p-3 text-center">APPROVED BY</div>
                  <div class="p-1 text-center">
                    <div style="position: relative;">
                      <span style="position: absolute; top: -5px; right: 17px; font-size: 0.8rem;">
                         @if ($form_approval->show_final_approver)
                              <small>System Approved Date: 
                                  {{ $form_approval->approved_head_division 
                                      ? \Carbon\Carbon::parse($form_approval->approved_head_division)->format('F j, Y') 
                                      : '' 
                                  }}
                              </small>
                          @else
                              <small>System Approved Date: 
                                  {{ $form_approval->approved_date 
                                      ? \Carbon\Carbon::parse($form_approval->approved_date)->format('F j, Y') 
                                      : '' 
                                  }}
                              </small>
                          @endif
                      </span>
                    <input type="hidden" name="display_approved_final" value="{{ $form_approval->show_final_approver ? ($form_approval->approved_head_division ? \Carbon\Carbon::parse($form_approval->approved_head_division)->format('F j, Y') : '') : ($form_approval->approved_date ? \Carbon\Carbon::parse($form_approval->approved_date)->format('F j, Y') : '') }}">
                    </div>
                    <div class="p-1">
                      @if ($form_approval->totalamount_total)
                          @if ($form_approval->status === 'Approved')
                              <img src="{{ asset('signed/APPROVED.png') }}"
                                  name="status"
                                  alt="Approved"
                                  style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 170px; opacity: 0.5; pointer-events: none; z-index: 10;">
                          @elseif ($form_approval->status === 'Declined')
                              <img src="{{ asset('signed/DENIED.png') }}"
                                  name="status"
                                  alt="Declined"
                                  style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 170px; opacity: 0.5; pointer-events: none; z-index: 10;">
                          @endif
                      @endif
                      <input type="hidden" name="status" value="{{ $form_approval->status }}" />
                      @if ($form_approval->show_final_approver)
                              <input type="text"
                                  style="background: transparent; text-align: center; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                                  name="approved_by"
                                  value="{{ $form_approval->approvedByHeadDivision->name ?? '' }}"
                                  readonly>
                      @else
                          <input type="text"
                              style="background: transparent; text-align: center; border: none; border-bottom: 1px solid #000; box-shadow: none; height: 30px; padding-left: 0;"
                              name="approved_by"
                              value="{{ $form_approval->approvedBy->name ?? ''}}"
                              readonly>
                      @endif
                    <small class="text-center d-block">Division/Cluster Head</small>
                   </div>
                  </div>
                </div>
              </div>
              @endif
              <div class="row m-0 border border-dark border-top-0">
                <div class="col-12 border-bottom border-dark p-1 text-center fw-bold" style="color: white; background-color: #3490dc; border-color: #3490dc;">REMARKS AND LIQUIDATION DETAILS</div>
                <div class="col-md-7 col-sm-12 border-right border-dark p-1">
                  <div class="border-dark p-1">
                    REMARKS
                  </div>
                  @if ($form_approval->show_final_approver)
                       <small> Remarks by Immediate Supervisor</small>
                       <small>( {{ $form_approval->approvedBy->name ?? '' }} - System Approved Date:  {{ $form_approval->approved_date ? \Carbon\Carbon::parse($form_approval->approved_date)->format('F j, Y') : '' }})</small>
                  <textarea class="form-control" name="approval_remarks" rows="1" readonly>{{ $form_approval->approval_remarks }}</textarea>
                       <small> Remarks by Head Supervisor</small>
                       <small>( {{ $form_approval->approvedByHeadDivision->name ?? '' }} - System Approved Date: {{ $form_approval->approved_head_division ? \Carbon\Carbon::parse($form_approval->approved_head_division)->format('F j, Y') : '' }})</small>
                  <textarea class="form-control" name="approval_remarks2" rows="1" readonly>{{ $form_approval->approval_remarks2 }}</textarea>
                      @else
                       <small> Remarks by Immediate Supervisor</small>
                       <small>( {{ $form_approval->approvedBy->name ?? '' }} - System Approved Date:  {{ $form_approval->approved_date ? \Carbon\Carbon::parse($form_approval->approved_date)->format('F j, Y') : '' }})</small>
                  <textarea class="form-control" name="approval_remarks" rows="1" readonly>{{ $form_approval->approval_remarks }}</textarea>
                      @endif
                </div>
                <div class="col-md-3 col-sm-6 border-end border-dark p-1">
                  <div class="border-dark p-1">LIQUIDATION DUE ON:</div>
                  <div style="margin-top: 17px;">
                    <input class="form-control" name="liquidation_date" id="liquidation_date" value="{{ $form_approval->liquidation_date ? \Carbon\Carbon::parse($form_approval->liquidation_date)->format('F j, Y') : '' }}" readonly>
                  </div>
                </div>
              </div>
             <small>Distribution: 1 Copy attached to RFP upon liquidation.</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="printModalContentSameWindow('view-modal-{{ $form_approval->id }}')">
            <i class="bi bi-printer"></i> Print
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endforeach


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
    border-right: none;
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
  

  .h.m-0:nth-child(2) {
    display: none;
  }
  
  .Itinerary-section{
    border-right: 1px solid #000 !important;
  }
}

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
  window.approvalThreshold = {{ $approvalThreshold ?? 0 }};
  window.allApprovers = @json($approversForJs ?? []);
</script>

@include('for-approval.print-toManager') 