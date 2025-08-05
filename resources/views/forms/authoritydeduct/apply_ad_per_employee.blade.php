<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Authority to Deduct Modal -->
<div class="modal fade" id="atdperemployee" tabindex="-1" aria-labelledby="authorityDeductModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authorityDeductModalLabel">
                    <strong>AUTHORITY TO DEDUCT FROM</strong><br />
                    <small class="text-muted">HRD-CBD-FOR-002-000</small>
                </h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <form action="new-ad-per-employee" method="POST" id="authorityDeductForm">
                @csrf
                <div class="modal-body">
                    <div class="row">

                        <input type="text" id="ad_number" name="ad_number"
                          class="form-control text-center"
                          style="width: 100px; height: 40px; font-size: 0.9rem; padding: 0.25rem 0.5rem;"
                          value=" {{ $adNumber ?? 'AD-0000' }}" hidden>
                          
                        <div class="col-md-6 mb-3">
                            <label for="employee_number" class="form-label">
                                Employee Number <span class="text-danger">*</span>
                            </label>
                            <select class="form-control select2-employee-modal" id="employee_number" name="employee_number" required>
                                <option value="">Select Employee Number</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->employee_number }}" 
                                            data-name="{{ $employee->first_name }} {{ $employee->last_name }}"
                                            data-designation="{{ $employee->position }}"
                                            data-department="{{ $employee->department->name ?? '' }}"
                                            data-location="{{ $employee->location }}"
                                            data-code="{{ $employee->employee_code }}"
                                            data-user-id="{{ $employee->user_id ?? $employee->id }}"
                                            data-personal-email="{{ $employee->personal_email ?? $employee->email ?? '' }}">
                                        {{ $employee->employee_number }} - {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" id="selected_employee_user_id" name="selected_employee_user_id" value="">
                    
                        <!-- Date Prepared -->
                        <div class="col-md-6 mb-3">
                            <label for="date_prepared" class="form-label">
                                Date Prepared <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                class="form-control"
                                id="date_prepared"
                                name="date_prepared"
                                value="{{ old('date_prepared', date('Y-m-d')) }}"
                                required />
                        </div>

                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                value=""
                                readonly />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label">
                                Designation <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="designation"
                                name="designation"
                                value=""
                                placeholder="Ex: AGS, BH, SS, etc."
                                readonly />
                            <small class="form-text text-muted">Ex: AGS, BH, SS, etc.</small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Department -->
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">
                                Department <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="department"
                                name="department"
                                value=""
                                placeholder=""
                                readonly />
                        </div>
                            
                        <!-- Work Location -->
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">
                                Work Location <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="location"
                                name="location"
                                value=""
                                readonly />
                        </div>
                    </div>

                    <br>
                    <!-- Authorization Statement -->
                    <div class="mb-4">
                        <p class="mb-2">I, hereby authorize <strong>PASCAL RESOURCES ENERGY INC.</strong> to deduct from my wages the details submitted herein.</p>
                    </div>
                    <br>

                    <div class="row">
                        <!-- Type of Deduction -->
                        <div class="col-md-6 mb-3">
                            <label for="type_of_deduction" class="form-label">
                                Type of Deduction <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-control"
                                id="type_of_deduction"
                                name="type_of_deduction"
                                required>
                                <option value="">Please Select</option>
                                <option value="SSS Loan" {{ old('type_of_deduction') == 'SSS Loan' ? 'selected' : '' }}>SSS Loan</option>
                                <option value="HDMF Loan" {{ old('type_of_deduction') == 'HDMF Loan' ? 'selected' : '' }}>HDMF Loan</option>
                            </select>
                        </div>

                        <!-- Particular -->
                        <div class="col-md-6 mb-3">
                            <label for="particular" class="form-label">
                                Particular <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="particular"
                                name="particular"
                                value="{{ old('particular') }}"
                                placeholder="Smart Line Charge for Mar 2023"
                                required />
                            <small class="form-text text-muted">Brief Description of Item for Deduction</small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Total Amount -->
                        <div class="col-md-6 mb-3">
                            <label for="total_amount" class="form-label">
                                Total Amount <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number"
                                    class="form-control"
                                    id="total_amount"
                                    name="total_amount"
                                    value="{{ old('total_amount') }}"
                                    step="0.01"
                                    min="0"
                                    required />
                            </div>
                            <small class="form-text text-muted">Ex: 750</small>
                        </div>

                        <!-- Total Amount Check -->
                        <div class="col-md-6 mb-3">
                            <label for="Amount_Equal" class="form-label" style="font-size: 15px;">
                                Total Amount is equal to or higher than P1,000?  <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-control"
                                id="Amount_Equal"
                                name="Amount_Equal"
                                required>
                                <option value="">Please Select</option>
                                <option value="yes" {{ old('Amount_Equal') == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('Amount_Equal') == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Number of Deductions -->
                        <div class="col-md-6 mb-3">
                            <label for="no_of_deductions" class="form-label">
                                No. of Deductions <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control"
                                id="no_of_deductions"
                                name="no_of_deductions"
                                value="{{ old('no_of_deductions') }}"
                                min="1"
                                max="120"
                                required />
                        </div>

                        <!-- Amount Per Cut off -->
                        <div class="col-md-6 mb-3">
                            <label for="amount_per_cutoff" class="form-label">
                                Amount Per Cut off <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number"
                                    class="form-control"
                                    id="amount_per_cutoff"
                                    name="amount_per_cutoff"
                                    value="{{ old('amount_per_cutoff') }}"
                                    step="0.01"
                                    min="0"
                                    readonly />
                            </div>
                            <small class="form-text text-muted">Auto computed based on total amount ÷ number of deductions</small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Start of Deduction -->
                        <div class="col-md-6 mb-3">
                            <label for="start_of_deduction" class="form-label">
                                Start of Deduction <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                class="form-control"
                                id="start_of_deduction"
                                name="start_of_deduction"
                                value="{{ old('start_of_deduction') }}"
                                required />
                            <small class="form-text text-muted">Select cutoff date (15th or 25th recommended)</small>
                        </div>
                    </div>

                    <br>
                    <!-- Understanding Statement -->
                    <div class="mb-4">
                        <p class="small">
                            I understand that the cost of the items will be deducted from my wages, further understand that it is my responsibility to pay for any cost associated with returning items. If terminate my position prior to the purchase being paid in full, authorize Pascal Resources Energy Inc. to deduct the unpaid balance from my wages.
                        </p>
                        <br>
                       <p class="small text-uppercase fw-bold">
                           <strong><i>This is a voluntary program and is not a condition of employment with Pascal Resources</i></strong>
                       </p>
                    </div>
                    <br>

                    <div class="row">
                        <!-- Date Issued -->
                        <div class="col-md-6 mb-3">
                            <label for="date_issued" class="form-label">Date Issued</label>
                            <input type="date"
                                class="form-control"
                                id="date_issued"
                                name="date_issued"
                                value="{{ old('date_issued', date('Y-m-d')) }}" />
                            <small class="form-text text-muted">Date when form was issued</small>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <!-- Requestor's Email -->
                        <div class="col-md-6 mb-3">
                            <label for="personal_email" class="form-label">
                                Requestor's Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                class="form-control"
                                id="personal_email"
                                name="personal_email"
                                value=""
                                placeholder="example@example.com"
                                required />
                            <small class="form-text text-muted">Email where notifications will be sent</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-send"></i> Submit Request
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for employee dropdown in modal
    $('.select2-employee-modal').select2({
        placeholder: 'Search and select employee...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#atdperemployee')
    });
    
    // Auto-fill form fields when employee is selected
    $('#employee_number').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var employeeName = selectedOption.data('name');
        var designation = selectedOption.data('designation');
        var department = selectedOption.data('department');
        var location = selectedOption.data('location');
        var employeeCode = selectedOption.data('code');
        var userID = selectedOption.data('user-id');
        var personalEmail = selectedOption.data('personal-email');
        
        // Auto-fill other form fields
        if (employeeName) {
            $('#name').val(employeeName);
        }
        if (designation) {
            $('#designation').val(designation);
        }
        if (department) {
            $('#department').val(department);
        }
        if (location) {
            $('#location').val(location);
        }
        if (userID) {
            $('#selected_employee_user_id').val(userID);
        }
        if (personalEmail) {
            $('#personal_email').val(personalEmail);
        }
    });
    
    // Reset Select2 when modal is closed
    $('#atdperemployee').on('hidden.bs.modal', function () {
        $('.select2-employee-modal').val(null).trigger('change');
        // Clear all auto-filled fields
        $('#name').val('');
        $('#designation').val('');
        $('#department').val('');
        $('#location').val('');
        $('#selected_employee_user_id').val('');
        $('#personal_email').val('');
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalAmountInput = document.getElementById('total_amount');
    const noDeductionsInput = document.getElementById('no_of_deductions');
    const amountPerCutoffInput = document.getElementById('amount_per_cutoff');

    function calculateAmountPerCutoff() {
        const totalAmount = parseFloat(totalAmountInput.value) || 0;
        const noDeductions = parseInt(noDeductionsInput.value) || 1;
        
        if (totalAmount > 0 && noDeductions > 0) {
            const amountPerCutoff = (totalAmount / noDeductions).toFixed(2);
            amountPerCutoffInput.value = amountPerCutoff;
        } else {
            amountPerCutoffInput.value = '';
        }
    }

    totalAmountInput.addEventListener('input', calculateAmountPerCutoff);
    noDeductionsInput.addEventListener('input', calculateAmountPerCutoff);
});
</script>