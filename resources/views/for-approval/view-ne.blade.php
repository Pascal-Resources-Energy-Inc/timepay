<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <div class="modal fade" id="view-ne-{{ $ne->id }}" tabindex="-1" aria-labelledby="numberEnrollmentModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="numberEnrollmentModalLabel">
                        <strong>CELLPHONE NUMBER ENROLLMENT FORM</strong><br />
                        <small class="text-muted">HRD-TAD-FOR-006-000</small>
                    </h5>
                    <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>

                <form id="numberenrollmentForm" method="POST" action="new-ne">
                    @csrf
                    <div class="modal-body">
                       <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="enrollment_type" class="form-label">
                                    Enrollment Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-control"
                                    id="enrollment_type"
                                    name="enrollment_type"
                                    disabled>
                                    <option value="">Please Select</option>
                                    <option value="new_employee" {{ $ne->enrollment_type == 'new_employee' ? 'selected' : '' }}>New Employee</option>
                                    <option value="lost_sim" {{ $ne->enrollment_type == 'lost_sim' ? 'selected' : '' }}>Lost/Defective Sim</option>
                                    <option value="allowance_based" {{ $ne->enrollment_type == 'allowance_based' ? 'selected' : '' }}>Transition to Allowance Based</option>
                                    <option value="other" {{ $ne->enrollment_type == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="other" class="form-label">
                                    Other
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="other"
                                    name="other"
                                    value="{{ $ne->other }}"
                                    readonly />
                            </div>  
                        </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" readonly>{{ $ne->comment }}</textarea>
                            </div>

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
                                placeholder="2024_05"
                                value="{{ $ne->employee_number }}"
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
                                id="position_designation"
                                name="position_designation"
                                placeholder="Enter your position (e.g., Business Services Department Manager)"
                                value="{{ $ne->position_designation }}"
                                readonly />
                            <small class="form-text text-muted">
                                <strong>Allowance Rates:</strong> 
                                <span class="text-danger">₱800 - Manager positions</span>
                                <br> 
                                <span class="text-info">₱500 - Head positions, Supervisors, Auditors, Compliance Officers</span>
                                <br>
                                <span class="text-success">₱300 - Assistant Head positions, Specialists</span>
                            </small>
                            <small class="form-text text-muted">Ex: AA, ADS, etc</small>
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
                                placeholder="Naz"
                                value="{{ $ne->first_name }}"
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
                                placeholder="Cecil"
                                value="{{ $ne->last_name }}"
                                readonly />
                            <small class="form-text text-muted">Last Name</small>
                        </div>
                    </div>

                    <input type="hidden" id="location" name="location" value="{{ $ne->location }}">

                    <div class="row">
                        <!-- Cell Phone Number -->
                        <div class="col-md-6 mb-3">
                            <label for="cellphone_number" class="form-label">
                                Cellphone Number <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control"
                                id="cellphone_number"
                                name="cellphone_number"
                                value="{{ $ne->cellphone_number }}"
                                maxlength="11"
                                readonly />
                            <small class="form-text text-muted">11 digit number</small>
                        </div>

                        <!-- Network Provider -->
                        <div class="col-md-6 mb-3">
                            <label for="network_provider" class="form-label">
                                Network Provider <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-control"
                                id="network_provider"
                                name="network_provider"
                                disabled>
                                <option value="">Please Select</option>
                                <option value="smart_tnt" {{ $ne->network_provider == 'smart_tnt' ? 'selected' : '' }}>Smart / TnT</option>
                                <option value="globe_tm" {{ $ne->network_provider == 'globe_tm' ? 'selected' : '' }}>Globe / TM</option>
                                <option value="dito" {{ $ne->network_provider == 'dito' ? 'selected' : '' }}>Dito</option>
                                <option value="sun" {{ $ne->network_provider == 'sun' ? 'selected' : '' }}>Sun</option>
                                <option value="other" {{ $ne->network_provider == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                        <!-- Employee Email -->
                        <div class="mb-3">
                            <label for="employee_email" class="form-label">
                                Employee Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                class="form-control"
                                id="employee_email"
                                name="employee_email"
                                placeholder="cecil.naz20@gmail.com.ph"
                                value="{{ $ne->employee_email }}"
                                readonly />
                            <small class="form-text text-muted">example@example.com</small>
                        </div>
                </div>

                    <!-- Modal Footer with Submit and Close buttons -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="printModalContentSameWindow('view-ne-{{ $ne->id }}')">
                            <i class="bi bi-printer"></i> Print
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@include('for-approval.print-ne') 