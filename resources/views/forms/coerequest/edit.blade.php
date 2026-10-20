<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="modal fade" id="edit-coe-{{ $coe->id }}" tabindex="-1" aria-labelledby="coeRequestModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="coeRequestModalLabel">
                    <strong>EDIT REQUEST</strong><br />
                    <p style="color: #e6e6e6;">HRD-LPD-FOR-00X-000</p>
                </h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <form id="coeRequestForm" method="POST" action="{{ route('edit-coe', $coe->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="mb-3">Information Request</h6>
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
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Employment Status:</div>
                            <div class="col-md-8">{{ $coe->employment_status }}</div>
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
                                <option value="Plain" {{ $coe->reason_for_request == 'Plain' ? 'selected' : '' }}>Plain</option>
                                <option value="With Salary" {{ $coe->reason_for_request == 'With Salary' ? 'selected' : '' }}>With Salary Details</option>
                            </select>
                        </div>
                    </div>

                    <!-- Purpose -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="purpose" style="font-size: 14px;">
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
                                <option value="">Please Select</option>
                                <option value="Email" {{ $coe->receive_method == 'Email' ? 'selected' : '' }}>Email</option>
                                <option value="Viber" {{ $coe->receive_method == 'Viber' ? 'selected' : '' }}>Viber</option>
                                <option value="Hard Copy" {{ $coe->receive_method == 'Hard Copy' ? 'selected' : '' }}>HR Office (Hard Copy)</option>
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
                                    value="{{ $coe->email }}"
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
                                    value="{{ $coe->viber_number }}"
                                    placeholder="09XXXXXXXXX"
                                    maxlength="11" />
                            </div>
                            <div id="hardcopy-wrapper" class="d-none">
                                <label style="font-size: 14px;">
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
                                rows="3">{{ $coe->additional_notes }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Update
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
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('edit-coe-{{ $coe->id }}');
        if (!modal) return;

        var purposeSelect = modal.querySelector('#purpose_select');
        var purposeInit = function() {
            var val = purposeSelect.value;
            modal.querySelector('#other-purpose-wrapper').classList.toggle('d-none', val !== 'Other');
            modal.querySelector('#visa-purpose-wrapper').classList.toggle('d-none', val !== 'Visa Application');
            modal.querySelector('#bank-purpose-wrapper').classList.toggle('d-none', val !== 'Bank');
        };
        purposeSelect.addEventListener('change', purposeInit);

        var storedPurpose = "{{ $coe->purpose }}";
        (function() {
            var method = 'Other', detail = storedPurpose;
            ['Visa Application', 'Bank'].forEach(function(opt) {
                if (storedPurpose === opt || storedPurpose.indexOf(opt + ' - ') === 0) {
                    method = opt;
                    detail = storedPurpose === opt ? '' : storedPurpose.substring((opt + ' - ').length);
                }
            });
            purposeSelect.value = method;
            if (method === 'Other') {
                modal.querySelector('#purpose_other').value = detail;
            } else if (method === 'Visa Application') {
                modal.querySelector('#purpose_visa').value = detail;
            } else if (method === 'Bank') {
                modal.querySelector('#purpose_bank').value = detail;
            }
            purposeInit();
        })();

        var select = modal.querySelector('#receive_method');
        var apply = function() {
            var val = select.value;
            modal.querySelector('#email-wrapper').classList.toggle('d-none', val !== 'Email');
            modal.querySelector('#viber-wrapper').classList.toggle('d-none', val !== 'Viber');
            modal.querySelector('#hardcopy-wrapper').classList.toggle('d-none', val !== 'Hard Copy');
        };
        apply();
        select.addEventListener('change', apply);
    });
</script>
