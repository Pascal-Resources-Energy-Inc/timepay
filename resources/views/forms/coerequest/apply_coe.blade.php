<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="modal fade" id="coeRequestModal" tabindex="-1" aria-labelledby="coeRequestModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="coeRequestModalLabel">
                    <strong>CERTIFICATE OF EMPLOYMENT REQUEST</strong><br />
                    <p style="color: #e6e6e6;">HRD-LPD-FOR-00X-000</p>
                </h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <form id="coeRequestForm" method="POST" action="new-coe">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="mb-3">Employee Information</h6>
                    </div>

                    <div class="mb-3" style="font-size: 13px;">
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Name:</div>
                            <div class="col-md-8">{{ auth()->user()->employee->first_name }} {{ auth()->user()->employee->last_name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Position:</div>
                            <div class="col-md-8">{{ auth()->user()->employee->position ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Email:</div>
                            <div class="col-md-8">{{ auth()->user()->employee->personal_email }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Hiring Date:</div>
                            <div class="col-md-8">{{ \Carbon\Carbon::parse(auth()->user()->employee->original_date_hired)->format('F d, Y') }}</div>
                        </div>
                    </div>

                    <!-- COE Request Type -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label style="font-size: 14px">
                                Certificate Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-control"
                                id="reason_for_request"
                                name="reason_for_request"
                                required>
                                <option value="">Please Select</option>
                                <option value="Plain" {{ old('reason_for_request') == 'plain' ? 'selected' : '' }}>Plain</option>
                                <option value="With Salary" {{ old('reason_for_request') == 'with_salary' ? 'selected' : '' }}>With Salary Details</option>
                            </select>
                        </div>
                        <!-- <div class="col-md-6 mb-3"> -->
                        <!--     <label for="coe_first_name" class="form-label"> -->
                        <!--         Employment Status <span class="text-danger">*</span> -->
                        <!--     </label> -->
                        <!---->
                        <!--     <input type="text" -->
                        <!--         class="form-control" -->
                        <!--         id="employment_status" -->
                        <!--         name="employment_status" -->
                        <!--         value="Active" -->
                        <!--         required -->
                        <!--         disabled /> -->
                        <!-- </div> -->
                    </div>

                    <!-- Hiring Date -->
                    <!-- <div class="row"> -->
                    <!--     <div class="col-md-6 mb-3"> -->
                    <!--         <label for="hiring_date" class="form-label"> -->
                    <!--             Hiring Date <span class="text-danger">*</span> -->
                    <!--         </label> -->
                    <!--         <input type="date" -->
                    <!--             class="form-control" -->
                    <!--             id="hiring_date" -->
                    <!--             name="hiring_date" -->
                    <!--             value="{{ auth()->user()->employee->original_date_hired }}" -->
                    <!--             disabled /> -->
                    <!--         <small class="form-text text-muted">DD-MM-YYYY</small> -->
                    <!--     </div> -->
                    <!---->
                    <!--     <div class="col-md-6 mb-3"> -->
                    <!--         <label for="designation" class="form-label"> -->
                    <!--             Designation <span class="text-danger">*</span> -->
                    <!--         </label> -->
                    <!--         <input type="text" -->
                    <!--             class="form-control" -->
                    <!--             id="designation" -->
                    <!--             name="designation" -->
                    <!--             value="{{ old('designation') }}" -->
                    <!--             placeholder="BH" -->
                    <!--             required /> -->
                    <!--         <small class="form-text text-muted">Ex.: BH, ADS etc</small> -->
                    <!--     </div> -->
                    <!-- </div> -->

                    <!-- <div class="row"> -->
                    <!--     <div class="col-md-6 mb-3"> -->
                    <!--         <label for="coe_first_name" class="form-label"> -->
                    <!--             Full Name <span class="text-danger">*</span> -->
                    <!--         </label> -->
                    <!--         <input type="text" -->
                    <!--             class="form-control" -->
                    <!--             id="coe_first_name" -->
                    <!--             name="name" -->
                    <!--             value="{{ auth()->user()->employee->first_name }}" -->
                    <!--             placeholder="First Name" -->
                    <!--             disabled /> -->
                    <!--         <small class="form-text text-muted">First Name</small> -->
                    <!--     </div> -->
                    <!---->
                    <!--     <div class="col-md-6 mb-3"> -->
                    <!--         <label for="coe_last_name" class="form-label"> -->
                    <!--             &nbsp; -->
                    <!--         </label> -->
                    <!--         <input type="text" -->
                    <!--             class="form-control" -->
                    <!--             id="coe_last_name" -->
                    <!--             name="last_name" -->
                    <!--             value="{{ auth()->user()->employee->last_name }}" -->
                    <!--             placeholder="Last Name" -->
                    <!--             disabled /> -->
                    <!--         <small class="form-text text-muted">Last Name</small> -->
                    <!--     </div> -->
                    <!-- </div> -->

                    <!-- Purpose -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="purpose" style="font-size:  14px;">
                                Purpose <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-control"
                                id="purpose_select"
                                name="purpose"
                                required>
                                <option value="">Select purpose</option>
                                <option value="Visa Application">Visa Application</option>
                                <option value="Bank">Bank Loan</option>
                                <option value="Other" class="text-muted">Other...</option>
                            </select>

                            <div id="other-purpose-wrapper" class="d-none">
                                <label class="mt-2" style="font-size: 14px;">If other, please specify</label>
                                <input type="text"
                                    name="purpose_other"
                                    id="purpose_other"
                                    placeholder="Enter purpose"
                                    class="form-control">
                            </div>

                            <div id="visa-purpose-wrapper" class="d-none">
                                <label class="form-label mt-2">
                                    Country of Destination
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="purpose_visa"
                                    id="purpose_visa"
                                    placeholder="Enter country of destination (e.g. Australia, Japan, UAE)"
                                    class="form-control">
                            </div>

                            <div id="bank-purpose-wrapper" class="d-none">
                                <label class="form-label mt-2">
                                    Specify Loan Type
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="purpose_bank"
                                    id="purpose_bank"
                                    placeholder="e.g. Car Loan, Housing Loan, Personal Loan"
                                    class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Method -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label style="font-size: 14px;">
                                I would like to receive my COE thru: <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-control"
                                id="receive_method"
                                name="receive_method"
                                required>
                                <option value="Email" selected>Email</option>
                                <option value="Viber">Viber</option>
                                <option value="Hard Copy">HR Office (Hard Copy)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="delivery-detail-container">
                            <div id="email-wrapper">
                                <label for="coe_email" style="font-size: 14px;">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email"
                                    class="form-control"
                                    id="coe_email"
                                    name="email"
                                    value="{{ auth()->user()->employee->personal_email }}"
                                    placeholder="example@example.com"
                                    />
                            </div>
                            <div id="viber-wrapper" class="d-none">
                                <label for="coe_viber" style="font-size: 14px;">
                                    Viber <span class="text-danger">*</span>
                                </label>
                                <input type="tel"
                                    class="form-control"
                                    id="coe_viber"
                                    name="viber_number"
                                    value="{{ auth()->user()->employee->personal_number }}"
                                    placeholder="09XXXXXXXXX"
                                    maxlength="11" />
                            </div>
                            <div id="hardcopy-wrapper" class="d-none">
                                <label for="coe_viber" style="font-size: 14px;">
                                    Pickup:
                                </label>
                                <div class="alert alert-info mb-0 py-1" style="font-size: 13px;">
                                    Your COE will be available for pickup at the HR office.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Additional Notes -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="additional_notes" style="font-size: 13px;">
                                Additional notes
                                <span class="text-muted">
                                    <small>(optional)</small>
                                </span>
                            </label>
                            <textarea class="form-control"
                                id="additional_notes"
                                name="additional_notes"
                                placeholder="Specify your needs..."
                                rows="3">{{ old('additional_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                    <button type="button" class="btn btn-light border" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    document.getElementById('purpose_select').addEventListener('change', function() {
        var val = this.value;
        document.getElementById('other-purpose-wrapper').classList.toggle('d-none', val !== 'Other');
        document.getElementById('visa-purpose-wrapper').classList.toggle('d-none', val !== 'Visa Application');
        document.getElementById('bank-purpose-wrapper').classList.toggle('d-none', val !== 'Bank');
    });

    document.getElementById('receive_method').addEventListener('change', function() {
        var val = this.value;
        document.getElementById('email-wrapper').classList.toggle('d-none', val !== 'Email');
        document.getElementById('viber-wrapper').classList.toggle('d-none', val !== 'Viber');
        document.getElementById('hardcopy-wrapper').classList.toggle('d-none', val !== 'Hard Copy');
    });
</script>
