<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="modal fade" id="view-ad-{{ $ad->id }}" tabindex="-1" aria-labelledby="authorityDeductModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authorityDeductModalLabel">
                    <strong>AUTHORITY TO DEDUCT FROM</strong><br />
                    <small class="text-muted">HRD-CBD-FOR-002-000</small>
                </h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <form action="{{ route('edit-ad', $ad->id) }}" method="POST" id="authorityDeductForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <!-- Date Prepared -->
                        <div class="col-md-6 mb-3">
                            <label for="date_prepared" class="form-label">
                                Date Prepared <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                class="form-control"
                                id="date_prepared"
                                name="date_prepared"
                                value="{{ \Carbon\Carbon::parse($ad->date_prepared)->format('Y-m-d') }}"
                                readonly />
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
                                value="{{ $ad->name }}"
                                readonly />
                        </div>
                    </div>

                    <div class="row">
                        <!-- Designation -->
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label">
                                Designation <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="designation"
                                name="designation"
                                value="{{ $ad->designation }}"
                                placeholder="Ex: AGS, BH, SS, etc."
                                readonly />
                            <small class="form-text text-muted">Ex: AGS, BH, SS, etc.</small>
                        </div>

                        <!-- Department -->
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">
                                Department <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="department"
                                name="department"
                                value="{{ $ad->department }}"
                                readonly />
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Work Location -->
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">
                                Work Location <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="location"
                                name="location"
                                value="{{ $ad->location }}"
                                readonly />
                        </div>
                    </div>

                    <br>
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
                                    disabled>
                                <option value="">Please Select</option>
                                <option value="Loan" {{ $ad->type_of_deduction == 'Loan' ? 'selected' : '' }}>Loan</option>
                                <option value="SSS Loan" {{ $ad->type_of_deduction == 'SSS Loan' ? 'selected' : '' }}>SSS Loan</option>
                                <option value="HDMF Loan" {{ $ad->type_of_deduction == 'HDMF Loan' ? 'selected' : '' }}>HDMF Loan</option>
                                <option value="Cash Advance" {{ $ad->type_of_deduction == 'Cash Advance' ? 'selected' : '' }}>Cash Advance</option>
                                <option value="Insurance" {{ $ad->type_of_deduction == 'Insurance' ? 'selected' : '' }}>Insurance</option>
                                <option value="Uniform" {{ $ad->type_of_deduction == 'Uniform' ? 'selected' : '' }}>Uniform</option>
                                <option value="Other" {{ $ad->type_of_deduction == 'Other' ? 'selected' : '' }}>Other</option>
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
                                value="{{ $ad->particular }}"
                                placeholder="Smart Line Charge for Mar 2023"
                                readonly />
                            <small class="form-text text-muted">Brief Description of Item for Deduction</small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Total Amount -->
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">
                                Total Amount <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number"
                                    class="form-control"
                                    id="amount"
                                    name="amount"
                                    value="{{ $ad->amount }}"
                                    step="0.01"
                                    min="0"
                                    readonly />
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
                                readonly>
                                <option value="">Please Select</option>
                                <option value="yes" {{ (old('Amount_Equal') ?? $ad->amount_equal) == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ (old('Amount_Equal') ?? $ad->amount_equal) == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Number of Deductions -->
                        <div class="col-md-6 mb-3">
                            <label for="frequency" class="form-label">
                                No. of Deductions <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control"
                                id="frequency"
                                name="frequency"
                                value="{{ $ad->frequency }}"
                                min="1"
                                readonly />
                        </div>

                        <!-- Amount Per Cut off -->
                        <div class="col-md-6 mb-3">
                            <label for="deductible" class="form-label">
                                Amount Per Cut off <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number"
                                    class="form-control"
                                    id="deductible"
                                    name="deductible"
                                    value="{{ $ad->deductible }}"
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
                                value="{{ $ad->start_date }}"
                                readonly />
                            <small class="form-text text-muted">Select cutoff date (15th or 25th recommended)</small>
                        </div>
                    </div>
                    <br>
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
                                value="{{ $ad->date_issued ? \Carbon\Carbon::parse($ad->date_issued)->format('Y-m-d') : '' }}" 
                                readonly/>
                            <small class="form-text text-muted">Date when form was issued</small>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <!-- Requestor's Email -->
                        <div class="col-md-6 mb-3">
                            <label for="requestor_email" class="form-label">
                                Requestor's Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                class="form-control"
                                id="requestor_email"
                                name="requestor_email"
                                value="{{ $ad->requestor_email }}"
                                placeholder="example@example.com"
                                readonly />
                            <small class="form-text text-muted">Email where notifications will be sent</small>
                        </div>
                    </div>

                    <input type="hidden" name="keep_existing_signature" value="1">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalId = 'edit_ad{{ $ad->id }}';
    const modal = document.getElementById(modalId);
    
    if (modal) {
        const totalAmountInput = modal.querySelector('#total_amount');
        const noDeductionsInput = modal.querySelector('#no_of_deductions');
        const amountPerCutoffInput = modal.querySelector('#amount_per_cutoff');

        function calculateAmountPerCutoff() {
            const totalAmount = parseFloat(totalAmountInput.value) || 0;
            const noDeductions = parseInt(noDeductionsInput.value) || 1;

            console.log('Total Amount:', totalAmount);
            console.log('No. of Deductions:', noDeductions);

            if (totalAmount > 0 && noDeductions > 0) {
                const amountPerCutoff = (totalAmount / noDeductions).toFixed(2);
                amountPerCutoffInput.value = amountPerCutoff;
                console.log('Calculated Amount Per Cutoff:', amountPerCutoff);
            } else {
                amountPerCutoffInput.value = '';
            }
        }

        if (totalAmountInput && noDeductionsInput && amountPerCutoffInput) {
            totalAmountInput.addEventListener('input', calculateAmountPerCutoff);
            totalAmountInput.addEventListener('change', calculateAmountPerCutoff);
            noDeductionsInput.addEventListener('input', calculateAmountPerCutoff);
            noDeductionsInput.addEventListener('change', calculateAmountPerCutoff);

            calculateAmountPerCutoff();
        }
    }
});
</script>