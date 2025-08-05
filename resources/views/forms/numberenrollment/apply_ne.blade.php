<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Payroll Disbursement Modal -->
    <div class="modal fade" id="numberenrollment" tabindex="-1" aria-labelledby="numberenrollmentModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="numberenrollmentModalLabel">
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
                                    required>
                                    <option value="">Please Select</option>
                                    <option value="new_employee" {{ old('enrollment_type') == 'new_employee' ? 'selected' : '' }}>New Employee</option>
                                    <option value="lost_sim" {{ old('enrollment_type') == 'lost_sim' ? 'selected' : '' }}>Lost/Defective Sim</option>
                                    <option value="allowance_based" {{ old('enrollment_type') == 'allowance_based' ? 'selected' : '' }}>Transition to Allowance Based</option>
                                    <option value="other" {{ old('enrollment_type') == 'other' ? 'selected' : '' }}>Other</option>
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
                                    value="{{ old('other') }}"
                                     />
                            </div>  
                        </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required>{{ old('comment') }}</textarea>
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
                                value="{{ auth()->user()->employee->employee_number }}"
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
                                value="{{ auth()->user()->employee->position }}"
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
                                value="{{ auth()->user()->employee->first_name }}"
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
                                value="{{ auth()->user()->employee->last_name }}"
                                readonly />
                            <small class="form-text text-muted">Last Name</small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Cell Phone Number -->
                        <div class="col-md-6 mb-3">
                            <label for="cellphone_number" class="form-label">
                                Cellphone Number <span class="text-danger">*</span>
                            </label>
                            <input type="tel"
                                class="form-control"
                                id="cellphone_number"
                                name="cellphone_number"
                                placeholder="09123456789"
                                maxlength="11"
                                required />
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
                                required>
                                <option value="">Please Select</option>
                                <option value="smart_tnt" {{ old('network') == 'smart_tnt' ? 'selected' : '' }}>Smart / TnT</option>
                                <option value="globe_tm" {{ old('network') == 'globe_tm' ? 'selected' : '' }}>Globe / TM</option>
                                <option value="dito" {{ old('network') == 'dito' ? 'selected' : '' }}>Dito</option>
                                <option value="sun" {{ old('network') == 'sun' ? 'selected' : '' }}>Sun</option>
                                <option value="other" {{ old('network') == 'other' ? 'selected' : '' }}>Other</option>
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
                                value="{{ auth()->user()->employee->personal_email }}"
                                readonly />
                            <small class="form-text text-muted">example@example.com</small>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const enrollmentType = document.getElementById('enrollment_type');
    const otherInput = document.getElementById('other');
    const otherLabel = otherInput.closest('.col-md-6');

    function toggleOtherInput() {
        if (enrollmentType.value === 'other') {
            otherInput.required = true;
            otherLabel.style.display = 'block';
        } else {
            otherInput.required = false;
            otherLabel.style.display = 'none';
            otherInput.value = '';
        }
    }

    // Initialize on page load
    toggleOtherInput();

    // Update on change
    enrollmentType.addEventListener('change', toggleOtherInput);
});

function validateAndSubmitForm() {
    Swal.fire({
        title: '<strong>Before You Submit</strong>',
        icon: 'warning',
        html: `
            <div style="text-align: left; line-height: 1.6; color: #2c3e50;">
                <p style="margin-bottom: 15px; font-weight: 600;">Please confirm that you understand:</p>
                
                <div style="margin-bottom: 12px;">
                    <strong style="color: #007bff;">▶</strong> I am responsible to ensure this cellphone number is personal and active.
                </div>
                
                <div style="margin-bottom: 12px;">
                    <strong style="color: #007bff;">▶</strong> I will submit another form if I change my account.
                </div>
                
                <div style="margin-bottom: 12px;">
                    <strong style="color: #007bff;">▶</strong> I will join the Gaz Life Community Group Chat on Viber.
                </div>
                
                <div style="margin-bottom: 15px;">
                    <strong style="color: #007bff;">▶</strong> I understand failure to reply during working hours can lead to NTE.
                </div>
            </div>
        `,
        width: 580,
        confirmButtonText: '<i class="bi bi-check-circle"></i> Yes, I Understand & Submit',
        confirmButtonColor: '#28a745',
        cancelButtonText: '<i class="bi bi-x-circle"></i> Cancel',
        cancelButtonColor: '#6c757d',
        showCancelButton: true,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('numberenrollmentForm').submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('numberenrollmentForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (form.checkValidity()) {
            validateAndSubmitForm();
        } else {
            form.reportValidity();
        }
    });
});

const style = document.createElement('style');
style.textContent = `
    .swal-wide {
        border-radius: 15px !important;
    }
    .swal2-title {
        color: #2c3e50 !important;
        font-weight: 700 !important;
    }
    .swal2-html-container {
        font-size: 14px !important;
    }
`;
document.head.appendChild(style);

</script>