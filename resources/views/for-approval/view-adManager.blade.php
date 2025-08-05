@foreach($ads as $form_approval)
<div class="modal fade" id="view-modal-{{ $form_approval->id }}" tabindex="-1" aria-labelledby="authorityDeductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authorityDeductModalLabel">Authority to Deduct Form</h5>
                    <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form Header -->
                    <div class="border border-dark form-header d-flex align-items-center">
                        <div class="company-block">
                            <div class="company-name">PASCAL</div>
                            <div class="company-name">RESOURCES</div>
                            <div class="company-name">ENERGY, INC.</div>
                        </div>
                        <div class="form-title">AUTHORITY TO DEDUCT</div>
                    </div>
                    <br>

                    <!-- Series and Date -->                
                    <div class="text-right mb-3">
                        <div>Series no: <span class="underline" data-value="{{ $form_approval->ad_number }}"><strong>{{ $form_approval->ad_number }}</strong></span></div>
                        <input name="seriesNo" value="{{ $form_approval->ad_number }}" hidden>
                        <div class="dat">Date received: <span class="underline"><strong>{{ \Carbon\Carbon::parse($form_approval->applied_date)->format('d M, Y') }}</strong></span></div>
                        <input name="dateReceived" value="{{ \Carbon\Carbon::parse($form_approval->applied_date)->format('d M, Y') }}" hidden>
                    </div>
                    <br>
                    <!-- Form Content -->
                    <div class="form-fields">
                        <p>I, <span class="underline"><strong>{{ $form_approval->employee->first_name }} @if($form_approval->employee->middle_initial) {{ $form_approval->employee->middle_initial }}. @endif{{ $form_approval->employee->last_name }}</strong>
                        </span> hereby authorize <strong>PASCAL RESOURCES ENERGY INC.</strong> to deduct from my </p><p> wages for <span class="underline"><strong>{{ $form_approval->particular }}</strong></span>
                        with the total amount of ₱<span class="underline"><strong>{{ $form_approval->amount }}</strong></span> only.</p>
                    </div>
                    <!-- Table -->
                    <div class="table-container">
                        <table class="deduct-table">
                            <thead>
                                <tr>
                                    <th>Particular</th>
                                    <th>Payment</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $form_approval->particular }}</td>
                                    <td>{{ $form_approval->deductible }}</td>
                                    <td>{{ $form_approval->amount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Terms -->
                    <div class="terms-text">
                        <p>I understand that the cost of the items will be deducted from my wages. I further understand that it is my responsibility to pay for any cost associated with returning items.</p>
                        <p>If I terminate my position prior to the purchase being paid in full, I authorize Pascal Resources Energy Inc. to deduct the unpaid balance from my wages.</p>
                    </div>

                    <!-- Voluntary Program Notice -->
                    <div class="voluntary-program">
                        THIS IS A VOLUNTARY PROGRAM AND IS NOT A CONDITION OF EMPLOYMENT WITH PASCAL RESOURCES ENERGY INC.
                    </div>

                    <!-- Signature Section -->
                    <div class="signature-section text-center">
                        <div>
                            <div>
                                    @if($form_approval->employee_signature)
                                            @php
                                                try {
                                                    $decryptedSig = Crypt::decryptString($form_approval->employee_signature);
                                                } catch (Exception $e) {
                                                    $decryptedSig = null;
                                                }
                                            @endphp
                                            @if($decryptedSig)
                                                    <img src="data:image/png;base64,{{ $decryptedSig }}" 
                                                        alt="Signature" 
                                                        style="height: 55px; position: absolute; margin-top: -32px; margin-left: -45px; border: none;">
                                            <input type="hidden" name="employee_signature" value="{{ $decryptedSig }}">                            
                                            @endif
                                    @endif
                            </div>
                                    <span style="color: lightgrey; position: absolute; margin-top: -40px; margin-left: -15px; font-size: 0.5rem;" name="approved">
                                        <small>
                                            Date:
                                            @if ($form_approval->applied_date)
                                                {{ \Carbon\Carbon::parse($form_approval->applied_date)->format('d-M-Y') }}
                                            @endif
                                        </small>
                                    </span>
                                    <input type="hidden" name="appliedDate" value="{{ $form_approval->applied_date }}">
                                    <div class="text-center mt-1"><strong>{{ $form_approval->employee->first_name }} @if($form_approval->employee->middle_initial) {{ $form_approval->employee->middle_initial }}. @endif{{ $form_approval->employee->last_name }}</strong></div>
                                    <div class="signature-line">Employee</div>
                                    <div class="text-center mt-2">(Signature over printed name)</div>
                        </div>
                        <div>
                            <!-- @if ($form_approval->status === 'Approved')
                              <img src="{{ asset('signed/APPROVED.png') }}"
                                  name="status"
                                  alt="Approved"
                                  style="position: absolute; top: 75%; right: -5%; transform: translateX(-50%); width: 170px; opacity: 0.5; pointer-events: none; z-index: 10;">
                            @elseif ($form_approval->status === 'Declined')
                                <img src="{{ asset('signed/DENIED.png') }}"
                                    name="status"
                                    alt="Declined"
                                    style="position: absolute; top: 75%; right: -5%; transform: translateX(-50%); width: 170px; opacity: 0.5; pointer-events: none; z-index: 10;">
                            @endif -->
                            <input type="hidden" name="status" value="{{ $form_approval->status }}" />
                            <div class="text-center mt-1"><strong>{{ \Carbon\Carbon::parse($form_approval->applied_date)->format('d M, Y') }}</strong></div>
                            <div class="signature-line">Date issued</div>
                        </div>
                    </div>

                    <!-- Form Code -->
                    <div class="form-code">
                        HRD-CBD-FOR-002-000 | ATD Form
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
        .form-header {
            border: 2px solid #000;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-weight: bold;
            font-size: 20px;
            margin: 0;
        }
        
        .form-title {
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            margin: 0;
            flex-grow: 1;
        }
        
        .form-fields {
            margin: 20px 0;
            line-height: 1.8;
        }
        
        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            margin: 0 5px;
            text-align: center;
        }

        .dat {
            margin: 20px 0;
        }
        
        .table-container {
            margin: 30px 0;
        }
        
        .deduct-table {
            border-collapse: collapse;
            width: 100%;
        }
        
        .deduct-table th,
        .deduct-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
        
        .deduct-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .terms-text {
            margin: 20px 0;
            line-height: 1.6;
            font-size: 14px;
        }
        
        .voluntary-program {
            font-style: italic;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 5px;
        }
        
        .form-code {
            text-align: right;
            margin-top: 20px;
            font-size: 12px;
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }
        
        .company-block {
            border-right: 1px solid #000;
            padding-right: 15px;
            margin-right: 15px;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    
@include('for-approval.print-adManager') 