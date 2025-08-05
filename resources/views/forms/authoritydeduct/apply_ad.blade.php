<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Authority to Deduct Modal -->
<div class="modal fade" id="authorityDeductModal" tabindex="-1" aria-labelledby="authorityDeductModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authorityDeductModalLabel">
                    <strong>AUTHORITY TO DEDUCT FROM</strong><br />
                    <small class="text-muted">HRD-CBD-FOR-002-000</small>
                </h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <form action="new-ad" method="POST" id="authorityDeductForm">
                @csrf
                <div class="modal-body">
                    <div class="row">

                        <input type="text" id="ad_number" name="ad_number"
                          class="form-control text-center"
                          style="width: 100px; height: 40px; font-size: 0.9rem; padding: 0.25rem 0.5rem;"
                          value=" {{ $adNumber ?? 'AD-0000' }}" hidden>   
                    
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
                                value="{{ auth()->user()->employee->first_name }} @if(auth()->user()->employee->middle_initial){{ auth()->user()->employee->middle_initial }}.@endif {{ auth()->user()->employee->last_name }}"
                                required />
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
                                value="{{ auth()->user()->employee->position ?? old('position') }}"
                                placeholder="Ex: AGS, BH, SS, etc."
                                required />
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
                                value="{{ auth()->user()->employee->department->name ?? old('department') }}"
                                placeholder=""
                                required />
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
                                value="{{ auth()->user()->employee->location ?? old('location') }}"
                                required />
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
                                <option value="Smart Phone Charges" {{ old('type_of_deduction') == 'Smart Phone Charges' ? 'selected' : '' }}>Smart Phone Charges</option>
                                <option value="Card Replacement Charge" {{ old('type_of_deduction') == 'Card Replacement Charge' ? 'selected' : '' }}>Card Replacement Charge</option>
                                <option value="Company Phone Repair Charge" {{ old('type_of_deduction') == 'Company Phone Repair Charge' ? 'selected' : '' }}>Company Phone Repair Charge</option>
                                <option value="BPFC Charges" {{ old('type_of_deduction') == 'BPFC Charges' ? 'selected' : '' }}>BPFC Charges</option>
                                <option value="Inventory or Cash Shortage" {{ old('type_of_deduction') == 'Inventory or Cash Shortage' ? 'selected' : '' }}>Inventory or Cash Shortage</option>
                                <option value="SSS Loan" {{ old('type_of_deduction') == 'SSS Loan' ? 'selected' : '' }}>SSS Loan</option>
                                <option value="HDMF Loan" {{ old('type_of_deduction') == 'HDMF Loan' ? 'selected' : '' }}>HDMF Loan</option>
                                <option value="Others" {{ old('type_of_deduction') == 'Others' ? 'selected' : '' }}>Others</option>
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
                        <!-- Employee Signature -->
                        <div class="col-md-6 mb-3">
                            <label for="employee_signature" class="form-label">
                                Employee Signature <span class="text-danger">*</span>
                            </label>
                            <div class="border rounded p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                <canvas id="signatureCanvas" class="border-0" style="cursor: crosshair;"></canvas>
                                <div class="text-center mt-2">
                                    <small class="text-muted">Sign above</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="clearSignature">Clear Signature</button>
                            <input type="hidden" name="employee_signature" id="employee_signature" required />
                            <div class="invalid-feedback d-block"></div>
                        </div>

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
                                value="{{ auth()->user()->employee->personal_email }}"
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

class SignatureCanvas {
    constructor(canvasId, clearBtnId, hiddenInputId) {
        this.canvas = document.getElementById(canvasId);
        this.ctx = this.canvas.getContext('2d');
        this.clearButton = document.getElementById(clearBtnId);
        this.hiddenInput = document.getElementById(hiddenInputId);
        this.isDrawing = false;

        this.init();
    }

    init() {
        this.setupCanvas();
        this.setupDrawingSettings();
        this.bindEvents();
    }

    setupCanvas() {
        const rect = this.canvas.parentElement.getBoundingClientRect();
        Object.assign(this.canvas, { width: rect.width - 6, height: 180 });
        Object.assign(this.canvas.style, { width: '100%', height: '180px' });
    }

    setupDrawingSettings() {
        Object.assign(this.ctx, {
            strokeStyle: '#000',
            lineWidth: 2,
            lineCap: 'round',
            lineJoin: 'round'
        });
    }

    bindEvents() {
        const c = this.canvas;

        ['mousedown', 'mousemove'].forEach(evt =>
            c.addEventListener(evt, e => this[evt === 'mousedown' ? 'startDrawing' : 'draw'](e))
        );
        ['mouseup', 'mouseout'].forEach(evt =>
            c.addEventListener(evt, () => this.stopDrawing())
        );

        c.addEventListener('touchstart', e => this.handleTouch(e, 'mousedown'));
        c.addEventListener('touchmove', e => this.handleTouch(e, 'mousemove'));
        c.addEventListener('touchend', e => this.handleTouch(e, 'mouseup'));

        this.clearButton.addEventListener('click', () => this.clearSignature());
        window.addEventListener('resize', () => this.handleResize());
    }

    handleTouch(e, type) {
        e.preventDefault();
        const touch = e.touches[0] || {};
        const evt = new MouseEvent(type, {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        this.canvas.dispatchEvent(evt);
    }

    getMousePos(e) {
        const rect = this.canvas.getBoundingClientRect();
        const scaleX = this.canvas.width / rect.width;
        const scaleY = this.canvas.height / rect.height;
        return {
            x: (e.clientX - rect.left) * scaleX,
            y: (e.clientY - rect.top) * scaleY
        };
    }

    startDrawing(e) {
        const { x, y } = this.getMousePos(e);
        this.isDrawing = true;
        this.ctx.beginPath();
        this.ctx.moveTo(x, y);
        this.lastX = x;
        this.lastY = y;
    }

    draw(e) {
        if (!this.isDrawing) return;
        const { x, y } = this.getMousePos(e);
        this.ctx.lineTo(x, y);
        this.ctx.stroke();
        this.lastX = x;
        this.lastY = y;
        this.updateSignatureData();
    }

    stopDrawing() {
        if (!this.isDrawing) return;
        this.isDrawing = false;
        this.ctx.beginPath();
    }

    clearSignature() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        const input = this.hiddenInput;
        input.value = '';
        input.classList.remove('is-invalid');
        const feedback = input.parentElement.querySelector('.invalid-feedback');
        if (feedback) feedback.textContent = '';
    }

    updateSignatureData() {
        this.hiddenInput.value = this.canvas.toDataURL('image/png');
    }

    handleResize() {
        const imgData = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
        this.setupCanvas();
        this.setupDrawingSettings();
    }

    isEmpty() {
        const data = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height).data;
        for (let i = 3; i < data.length; i += 4) {
            if (data[i] !== 0) return false;
        }
        return true;
    }

    static initOnLoad(canvasId, clearBtnId, inputId) {
        document.addEventListener('DOMContentLoaded', () => {
            const sig = new SignatureCanvas(canvasId, clearBtnId, inputId);
            window.employeeSignature = sig;

            const form = document.getElementById('authorityDeductForm');
            if (form) {
                form.addEventListener('submit', e => {
                    if (sig.isEmpty()) {
                        e.preventDefault();
                        const input = sig.hiddenInput;
                        input.classList.add('is-invalid');
                        const feedback = input.parentElement.querySelector('.invalid-feedback');
                        if (feedback) feedback.textContent = 'Employee signature is required.';
                        sig.canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        alert('Please provide your signature before submitting.');
                        return false;
                    }
                    
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Submitting...';
                    }
                    
                    return true;
                });
            }
        });
    }
}

SignatureCanvas.initOnLoad('signatureCanvas', 'clearSignature', 'employee_signature');
</script>