<div class="modal fade" id="applyoffset" tabindex="-1" role="dialog" aria-labelledby="OSData" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="OSData">
                    <strong>OFFSET APPLICATION</strong><br />
                    <small class="text-muted">HRD-OT-FOR-003-000</small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="new-offset" id="overtimeForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="mb-3"><strong>Employee Information</strong></h6>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="first_name"
                                name="first_name"
                                placeholder="First Name"
                                value="{{ auth()->user()->employee->first_name }}"
                                readonly />
                            <small class="form-text text-muted">First Name</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">
                                &nbsp;
                            </label>
                            <input type="text"
                                class="form-control"
                                id="last_name"
                                name="last_name"
                                placeholder="Last Name"
                                value="{{ auth()->user()->employee->last_name }}"
                                readonly />
                            <small class="form-text text-muted">Last Name</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label">
                                Designation <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="designation"
                                name="designation"
                                placeholder="Ex. BH, ADS, etc."
                                value="{{ auth()->user()->employee->position ?? old('position') }}"
                                readonly />
                            <small class="form-text text-muted">Ex. BH, ADS, etc.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="employee_email" class="form-label">
                                Employee's Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                class="form-control"
                                id="employee_email"
                                name="employee_email"
                                placeholder="example@example.com"
                                value="{{ auth()->user()->employee->personal_email ?? old('personal_email') }}"
                                readonly />
                            <small class="form-text text-muted">example@example.com</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">
                                Department <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="department"
                                name="department"
                                placeholder="Information Technology Department"
                                value="{{ auth()->user()->employee->department->name ?? old('department') }}"
                                readonly />
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="ot_authorization_ref" class="form-label">
                                Input OT Authorization Ref (OTAR) #
                            </label>
                            <input type="text"
                                class="form-control"
                                id="ot_authorization_ref"
                                name="ot_authorization_ref"
                                placeholder="OT-PQD-01-2024" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ot_date" class="form-label">
                                Date of Over Time <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                class="form-control"
                                id="ot_date"
                                name="ot_date"
                                required />
                            <small class="form-text text-muted">DD-MM-YYYY</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="total_hours" class="form-label">
                                Total Number of OT (in Hours) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control"
                                id="total_hours"
                                name="total_hours"
                                placeholder="e.g., 23"
                                min="1"
                                required />
                           </div>
                    </div>

                    <input type="hidden" name="time_compensation_type" value="offset">

                    <div class="mb-3">
                        <label for="proof_otar" class="form-label">
                            Proof of OTAR <span class="text-danger">*</span>
                        </label>
                        <div class="border border-2 border-dashed rounded p-4 text-center" style="border-color: #dee2e6 !important; background-color: #f8f9fa; cursor: pointer;" onclick="document.getElementById('proof_otar').click();">
                            <div class="mb-3">
                                <svg width="48" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7,10 12,15 17,10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                            </div>
                            <h6 class="mb-2 text-muted">Browse Files</h6>
                            <p class="text-muted mb-0">Drag and drop files here or click to browse</p>
                            <input type="file" class="form-control d-none" id="proof_otar" name="proof_otar" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                        </div>
                        <div id="file-info" class="mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label for="detailed_description" class="form-label">
                            Detailed Description for Request <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="detailed_description" name="detailed_description" rows="5" placeholder="Provide detail description for this transaction." required></textarea>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('total_hours').addEventListener('keydown', function (e) {
        if (["e", "E", "+", "-"].includes(e.key)) {
        e.preventDefault();
        }
    });
</script>
<script>
document.getElementById('proof_otar').addEventListener('change', function(e) {
    const fileInfo = document.getElementById('file-info');
    const file = e.target.files[0];
    
    if (file) {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        fileInfo.innerHTML = `
            <div class="alert alert-info">
                <strong>Selected file:</strong> ${file.name}<br>
                <strong>Size:</strong> ${fileSize} MB<br>
                <strong>Type:</strong> ${file.type}
            </div>
        `;
    } else {
        fileInfo.innerHTML = '';
    }
});

// Allow drag and drop
const dropArea = document.querySelector('.border-dashed');
const fileInput = document.getElementById('proof_otar');

dropArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    dropArea.classList.add('bg-light');
});

dropArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    dropArea.classList.remove('bg-light');
});

dropArea.addEventListener('drop', function(e) {
    e.preventDefault();
    dropArea.classList.remove('bg-light');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        fileInput.dispatchEvent(new Event('change'));
    }
});
</script>