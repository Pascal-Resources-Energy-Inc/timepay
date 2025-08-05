<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <div class="modal fade" id="coeRequestModal" tabindex="-1" aria-labelledby="coeRequestModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="coeRequestModalLabel">
                        <strong>CERTIFICATE OF EMPLOYMENT REQUEST</strong><br />
                        <small class="text-muted">HRD-LPD-FOR-00X-000</small>
                    </h5>
                    <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>

                <form id="coeRequestForm" method="POST" action="new-coe">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <h6 class="mb-3"><strong>COE Request Details</strong></h6>
                        </div>
                        <br>

                        <!-- COE Request Type -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    COE Request <span class="text-danger">*</span>
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Employment Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-control"
                                    id="employment_status"
                                    name="employment_status"
                                    required>
                                    <option value="">Please Select</option>
                                   <option value="Active" {{ old('employment_status') == 'active' ? 'selected' : '' }}>Active - Still Employed</option>
                                    <option value="Separated" {{ old('employment_status') == 'separated' ? 'selected' : '' }}>Separated - Former Employee</option>
                                </select>
                            </div>
                        </div>

                        <br>
                        <div class="mb-4">
                            <h6 class="mb-3"><strong>Employee Information</strong></h6>
                        </div>
                        <br>

                        <!-- Hiring Date -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="hiring_date" class="form-label">
                                    Hiring Date <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                    class="form-control"
                                    id="hiring_date"
                                    name="hiring_date"
                                    value="{{ auth()->user()->employee->original_date_hired }}"
                                    disabled />
                                <small class="form-text text-muted">DD-MM-YYYY</small>
                            </div>

                            <!-- Designation -->
                            <div class="col-md-6 mb-3">
                                <label for="designation" class="form-label">
                                    Designation <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="designation"
                                    name="designation"
                                    value="{{ old('designation') }}"
                                    placeholder="BH"
                                    required />
                                <small class="form-text text-muted">Ex.: BH, ADS etc</small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- First Name -->
                            <div class="col-md-6 mb-3">
                                <label for="coe_first_name" class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="coe_first_name"
                                    name="first_name"
                                    value="{{ auth()->user()->employee->first_name }}"
                                    placeholder="First Name"
                                    disabled />
                                <small class="form-text text-muted">First Name</small>
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6 mb-3">
                                <label for="coe_last_name" class="form-label">
                                    &nbsp;
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="coe_last_name"
                                    name="last_name"
                                    value="{{ auth()->user()->employee->last_name }}"
                                    placeholder="Last Name"
                                    disabled />
                                <small class="form-text text-muted">Last Name</small>
                            </div>
                        </div>

                        <!-- Purpose -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="purpose" class="form-label">
                                    Purpose for COE Request <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                    id="purpose" 
                                    name="purpose" 
                                    rows="3" 
                                    placeholder="Be clear in stating your purpose" 
                                    required>{{ old('purpose') }}</textarea>
                            </div>
                        </div>

                        <!-- Delivery Method -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    I would like to receive my COE thru: <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-control"
                                    id="receive_method"
                                    name="receive_method"
                                    required>
                                    <option value="">Please Select</option>
                                   <option value="Email" {{ old('receive_method') == 'Email' ? 'selected' : '' }}>Email</option>
                                    <option value="Viber" {{ old('receive_method') == 'Viber' ? 'selected' : '' }}>Viber</option>
                                </select>
                            </div>
                             <div class="col-md-6 mb-3">
                                    <label for="coe_email" class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email"
                                        class="form-control"
                                        id="coe_email"
                                        name="email"
                                        value="{{ auth()->user()->employee->personal_email }}"
                                        placeholder="example@example.com"
                                        readonly />
                                    <small class="form-text text-muted">example@example.com</small>
                                </div>
                        </div>
                            <!-- Additional Notes -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="additional_notes" class="form-label">
                                        Additional Notes, if any
                                    </label>
                                    <textarea class="form-control" 
                                        id="additional_notes" 
                                        name="additional_notes" 
                                        rows="3">{{ old('additional_notes') }}</textarea>
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