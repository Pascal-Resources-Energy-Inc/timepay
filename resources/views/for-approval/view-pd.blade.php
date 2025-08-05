<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Payroll Disbursement Modal -->
    <div class="modal fade" id="view-pd-{{ $pd->id }}" tabindex="-1" aria-labelledby="payrollDisbursementModalLabel" aria-hidden="true" style="display: none;">
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
                                    value="{{ $pd->employee_number }}"
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
                                    value="{{ $pd->designation }}"
                                    placeholder="BH"
                                    readonly />
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
                                    value="{{ $pd->cell_phone_number }}"
                                    placeholder="09988456463"
                                    maxlength="11"
                                    readonly />
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
                                    disabled>
                                    <option value="">Please Select</option>
                                    <option value="Captured Card" {{ $pd->reason_for_request == 'Captured Card' ? 'selected' : '' }}>Captured Card (Kinain ng ATM)</option>
                                    <option value="Defective Card" {{ $pd->reason_for_request == 'Defective Card' ? 'selected' : '' }}>Defective Card (Nasira)</option>
                                    <option value="Lost Card" {{ $pd->reason_for_request == 'Lost Card' ? 'selected' : '' }}>Lost Card (Nawala ang card)</option>
                                    <option value="Transition BDO" {{ $pd->reason_for_request == 'Transition BDO' ? 'selected' : '' }}>Transitioned to BDO (Metro to BDO)</option>
                                    <option value="New Employee" {{ $pd->reason_for_request == 'New Employee' ? 'selected' : '' }}>New Employee</option>
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
                                    value="{{ $pd->other_reason }}" 
                                    readonly/>
                            </div>
                        </div>

                        <!-- Comment field -->
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" readonly>{{ $pd->comment }}</textarea>
                        </div>

                        <!-- Disbursement Account Request -->
                        <div class="mb-3">
                            <label for="disbursement_account" class="form-label">
                                Disbursement Account Request <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-control"
                                id="disbursement_account"
                                name="disbursement_account"
                                disabled>
                                <option value="">Please Select</option>
                                <option value="BDO Payroll" {{ $pd->disbursement_account == 'BDO Payroll' ? 'selected' : '' }}>BDO Payroll Account</option>
                                <option value="BDO Personal" {{ $pd->disbursement_account == 'BDO Personal' ? 'selected' : '' }}>BDO Personal Account</option>
                                <option value="Metrobank Paycard" {{ $pd->disbursement_account == 'Metrobank Paycard' ? 'selected' : '' }}>Metrobank Paycard</option>
                                <option value="Metrobank Personal" {{ $pd->disbursement_account == 'Metrobank Personal' ? 'selected' : '' }}>Metrobank Personal Account</option>
                                <option value="Gcash Personal" {{ $pd->disbursement_account == 'Gcash Personal' ? 'selected' : '' }}>Gcash Personal Account</option>
                                <option value="Gcash Other" {{ $pd->disbursement_account == 'Gcash Other' ? 'selected' : '' }}>Gcash Other Person Account</option>
                            </select>
                        </div>

                        <!-- Account Name and Account Number -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account_name" class="form-label">
                                    Account Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="account_name"
                                    name="account_name"
                                    value="{{ $pd->account_name }}"
                                    readonly />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="account_number" class="form-label">
                                    Account Number <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="account_number"
                                    name="account_number"
                                    value="{{ $pd->account_number }}"
                                    readonly />
                                <small class="form-text text-muted">Account Number not Card Number, 11 digit for Gcash</small>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer with Submit and Close buttons -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>