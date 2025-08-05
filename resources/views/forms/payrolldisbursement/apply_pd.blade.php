<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Payroll Disbursement Modal -->
    <div class="modal fade" id="payrollDisbursementModal" tabindex="-1" aria-labelledby="payrollDisbursementModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payrollDisbursementModalLabel">
                        <strong>PAYROLL DISBURSEMENT</strong><br />
                        <small class="text-muted">HRD-PD-FOR-003-000</small>
                    </h5>
                    <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>

                <form action="new-pd" id="payrollDisbursementForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <h6 class="mb-3"><strong>Employee Personal Information</strong></h6>
                        </div>
                        <br>
                        <div class="row">
                            <!-- Employee Number -->
                            <div class="col-md-6 mb-3">
                                <label for="employee_number" class="form-label">
                                    Employee Number <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="employee_number"
                                    name="employee_number"
                                    value="{{ auth()->user()->employee->employee_number }}"
                                    placeholder="2024_01"
                                    readonly />
                                <small class="form-text text-muted">Ex: 2024_01 (See your pay slip for reference)</small>
                            </div>

                            <!-- Position/Designation -->
                            <div class="col-md-6 mb-3">
                                <label for="position_designation" class="form-label">
                                    Position / Designation <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="designation"
                                    name="designation"
                                    value="{{ auth()->user()->employee->position }}"
                                    placeholder="BH"
                                    required />
                                <small class="form-text text-muted">Ex: BH, ADS, etc</small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Full Name - First Name -->
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="first_name"
                                    name="first_name"
                                    value="{{ auth()->user()->employee->first_name }}"
                                    placeholder="Naz"
                                    readonly />
                                <small class="form-text text-muted">First Name</small>
                            </div>

                            <!-- Full Name - Last Name -->
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">
                                    &nbsp;
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="last_name"
                                    name="last_name"
                                    value="{{ auth()->user()->employee->last_name }}"
                                    placeholder="Mie"
                                    readonly />
                                <small class="form-text text-muted">Last Name</small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Cell Phone Number -->
                            <div class="col-md-6 mb-3">
                                <label for="cell_phone_number" class="form-label">
                                    Cell Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="tel"
                                    class="form-control"
                                    id="cell_phone_number"
                                    name="cell_phone_number"
                                    value="{{ auth()->user()->employee->personal_number }}"
                                    placeholder="09988456463"
                                    maxlength="11"
                                    required />
                                <small class="form-text text-muted">Input 11 digit mobile number</small>
                            </div>

                            <!-- Employee Email -->
                            <div class="col-md-6 mb-3">
                                <label for="employee_email" class="form-label">
                                    Employee Email <span class="text-danger">*</span>
                                </label>
                                <input type="email"
                                    class="form-control"
                                    id="employee_email"
                                    name="employee_email"
                                    value="{{ auth()->user()->employee->personal_email }}"
                                    placeholder="nazmie100@gmail.com"
                                    readonly />
                                <small class="form-text text-muted">example@example.com</small>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="mb-4">
                            <h6 class="mb-3"><strong>Disbursement Account Details</strong></h6>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reason_for_request" class="form-label">
                                    Reason for Request <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-control"
                                    id="reason_for_request"
                                    name="reason_for_request"
                                    required>
                                    <option value="">Please Select</option>
                                   <option value="Captured Card" {{ old('reason_for_request') == 'captured_card' ? 'selected' : '' }}>Captured Card (Kinain ng ATM)</option>
                                    <option value="Defective Card" {{ old('reason_for_request') == 'defective_card' ? 'selected' : '' }}>Defective Card (Nasira)</option>
                                    <option value="Lost Card" {{ old('reason_for_request') == 'lost_card' ? 'selected' : '' }}>Lost Card (Nawala ang card)</option>
                                    <option value="Transition BDO" {{ old('reason_for_request') == 'transitioned_bdo' ? 'selected' : '' }}>Transitioned to BDO (Metro to BDO)</option>
                                    <option value="New Employee" {{ old('reason_for_request') == 'new_employee' ? 'selected' : '' }}>New Employee</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="other" class="form-label">
                                    Other
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="Other"
                                    name="Other"
                                    value="{{ old('Other') }}"
                                 />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required>{{ old('comment') }}</textarea>
                        </div>

                            <div class="mb-3">
                                <label for="disbursement_account" class="form-label">
                                    Disbursement Account Request <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-control"
                                    id="disbursement_account"
                                    name="disbursement_account"
                                    required>
                                    <option value="">Please Select</option>
                                    <option value="BDO Payroll" {{ old('disbursement_account') == 'bdo_payroll' ? 'selected' : '' }}>BDO Payroll Account</option>
                                    <option value="BDO Personal" {{ old('disbursement_account') == 'bdo_personal' ? 'selected' : '' }}>BDO Personal Account</option>
                                    <option value="Metrobank Paycard" {{ old('disbursement_account') == 'metrobank_paycard' ? 'selected' : '' }}>Metrobank Paycard</option>
                                    <option value="Metrobank Personal" {{ old('disbursement_account') == 'metrobank_personal' ? 'selected' : '' }}>Metrobank Personal Account</option>
                                    <option value="Gcash Personal" {{ old('disbursement_account') == 'gcash_personal' ? 'selected' : '' }}>Gcash Personal Account</option>
                                    <option value="Gcash Other" {{ old('disbursement_account') == 'gcash_other' ? 'selected' : '' }}>Gcash Other Person Account</option>
                                </select>
                            </div>
                 

                        <div class="row">
                            <!-- Account Name -->
                            <div class="col-md-6 mb-3">
                                <label for="account_name" class="form-label">
                                    Account Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="account_name"
                                    name="account_name"
                                    value="{{ old('account_name') }}"
                                    required />
                            </div>

                            <!-- Account Number -->
                            <div class="col-md-6 mb-3">
                                <label for="account_number" class="form-label">
                                    Account Number <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                    class="form-control"
                                    id="account_number"
                                    name="account_number"
                                    value="{{ old('account_number') }}"
                                    required />
                                <small class="form-text text-muted">Account Number not Card Number, 11 digit for Gcash</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send"></i> Submit
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>


