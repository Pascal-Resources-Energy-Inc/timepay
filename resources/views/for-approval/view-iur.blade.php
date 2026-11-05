<div class="modal fade" id="view-modal-{{ $form_approval->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">{{ $canManageCurrentIur && $form_approval->status === 'Pending' ? 'Process Request' : 'Request Details' }}</h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal">&times;</button>

            </div>

            <div class="modal-body">
                @php
                    $viewEmployeeFirst = optional($form_approval->user)->first_name ?: $form_approval->legacy_first;
                    $viewEmployeeLast = optional($form_approval->user)->last_name ?: $form_approval->legacy_last;
                    $viewEmployeeName = trim(($viewEmployeeFirst ?? '') . ' ' . ($viewEmployeeLast ?? ''));
                    $viewEmployeePosition = optional($form_approval->user)->position ?: $form_approval->legacy_designation;
                    $viewDateHired = optional($form_approval->user)->obanana_date_hired ?: $form_approval->legacy_date_hired;
                @endphp
                <form method="POST" action="process-iur/{{ $form_approval->id }}" id="processIurForm-{{ $form_approval->id }}">
                    @csrf
                    <input type="hidden" name="status" value="Processing">

                <div id="accountability-data-{{ $form_approval->id }}"
                    data-employee-name="{{ $viewEmployeeName }}"
                    data-branch="{{ $form_approval->work_location }}"
                    data-position="{{ $viewEmployeePosition ?? '' }}"
                    data-date="{{ date('M d, Y', strtotime($form_approval->created_at)) }}"
                    data-department="{{ optional(optional($form_approval->user)->department)->name ?? '' }}"
                    data-reference="{{ $form_approval->iur_reference }}"
                    data-request-for="{{ $form_approval->request_for }}"
                    data-size="{{ $form_approval->size }}"
                    data-id-request="{{ $form_approval->id_request ?? '' }}"
                    data-issued="{{ $form_approval->issued ?? '' }}"
                    data-issued-remarks="{{ $form_approval->issued_remarks ?? '' }}"
                    data-employee-code="{{ optional($form_approval->user)->employee_code ?? '' }}"
                    data-signature="{{ $form_approval->e_signature ? asset($form_approval->e_signature) : '' }}"
                    style="display:none;">
                </div>

                {{-- ACCOUNTABILITY HISTORY --}}
                @if($form_approval->accountabilities->count() > 0)
                <div class="d-flex align-items-start mb-2 px-3 py-2" style="background: #f7f9fc; border-left: 3px solid #6c8eab; border-radius: 4px; color: #4b5563; font-size: 13px;">
                    <i class="ti-info-alt mr-2 mt-1" style="color: #527694;"></i>
      <div style="min-width: 0; line-height: 1.45;">
        <strong class="d-block mb-1">Reference guide</strong>
        <div class="d-flex align-items-start mb-1">
          <span style="flex: 0 0 10px; width: 10px; height: 10px; margin-top: .28rem; margin-right: .45rem; border-radius: 2px; background: #d4edda; border: 1px solid #9fcbaa;"></span>
          <span>Green means the accountability is signed.</span>
        </div>
        <div class="d-flex align-items-start">
          <span style="flex: 0 0 10px; width: 10px; height: 10px; margin-top: .28rem; margin-right: .45rem; border-radius: 2px; background: #f8d7da; border: 1px solid #dfa7ac;"></span>
          <span>Red means the accountability is not yet signed.</span>
        </div>
      </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white py-2">
                        <h6 class="mb-0">Release History</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0">
                                <thead class="table-bordered">
                                    <tr>
                                        <th style="width:32px"></th>
                                        <th>Ref</th>
                                        <th>Signed At</th>
                                        <th>Prod</th>
                                        <th>White</th>
                                        <th>Black</th>
                                        <th>Polo</th>
                                        <th>ID</th>
                                        <th>Issued By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($form_approval->accountabilities as $acc)

                                    @php $signed = !is_null($acc->signed_at); @endphp

                                    <tr>
                                        <td style="text-align:center;padding:2px">
                                            <button type="button" class="btn btn-outline-info p-1" title="View summary" onclick="window.open('{{ url('iur-accountability/' . $acc->id) }}', '_blank')">
                                                <i class="ti-eye"></i>
                                            </button>
                                            @if($canManageCurrentIur)
                                            <button type="button" class="btn btn-outline-primary p-1" title="Print this release" onclick="window.open('{{ url('iur-accountability-print/' . $acc->id) }}', '_blank')">
                                                <i class="ti-printer"></i>
                                            </button>
                                            @endif
                                        </td>
                                        <td style="background-color: {{ $signed ? '#d4edda' : '#f8d7da' }};">{{ $acc->accountability_ref }}</td>
                                        <td>{{ $acc->signed_at ? date('M d, Y', strtotime($acc->signed_at)) : '-' }}</td>
                                        <td>{{ $acc->released_prod_qty > 0 ? $acc->released_prod_qty : '-' }}</td>
                                        <td>{{ $acc->released_white_qty > 0 ? $acc->released_white_qty : '-' }}</td>
                                        <td>{{ $acc->released_black_qty > 0 ? $acc->released_black_qty : '-' }}</td>
                                        <td>{{ $acc->released_collar_qty > 0 ? $acc->released_collar_qty : '-' }}</td>
                                        <td>{{ $acc->released_id ? 'Yes' : '-' }}</td>
                                        <td>{{ $acc->issuer->name ?? '-' }}</td>
                                    </tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- REQUEST INFORMATION --}}
                <div class="iur-section">

                    {{-- STATUS UPDATE --}}
                    <!-- <form method='POST' action='approve-iur/{{$form_approval->id}}' onsubmit="btnApprove.disabled = true; return true;" enctype="multipart/form-data">
                        @csrf
                        <label class="fw-bold mb-1 me-2 text-nowrap">Update Status:</label>
                        <div class="d-flex" style="gap: 0.5rem;">
                            <select name="status" class="form-control form-control-sm" required>
                                <option value="">-- Select Status --</option>
                                <option value="Processing">Processing</option>
                                <option value="Partial">Partial</option>
                                <option value="Received">Received</option>
                            </select>
                            <button type="submit" name="btnApprove" class="btn btn-primary btn-sm text-nowrap">Update</button>
                        </div>
                    </form> -->


                    <div class="iur-section-header">
                        <h5>Request Information</h5>
                    </div>
                    <div class="iur-section-body">
                        <div class="iur-mobile-scroll">
                        <table class="table iur-info-table mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Reference</td>
                                    <td>{{ $form_approval->iur_reference }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Employee Name</td>
                                    <td>{{ $viewEmployeeName !== '' ? $viewEmployeeName : '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Designation</td>
                                    <td>{{ $viewEmployeePosition ?: '—' }}</td>
                                </tr>
                                @if($viewDateHired)
                                <tr>
                                    <td class="text-muted">Date Hired</td>
                                    <td>{{ date('M d, Y', strtotime($viewDateHired)) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-muted">Status</td>
                                    <td>
                                        @if($form_approval->status == 'Processing')
                                        <span class="badge bg-warning text-dark">Processing</span>
                                        @elseif($form_approval->status == 'Partial')
                                        <span class="badge" style="background: #dbeafe; color: #1e3a5f;">Partial</span>
                                        @elseif($form_approval->status == 'Released')
                                        <span class="badge bg-success text-white">Released</span>
                                        @else
                                        {{ $form_approval->status }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Type</td>
                                    <td>{{ $form_approval->type }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Request For</td>
                                    <td>{{ $form_approval->request_for }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Work Location</td>
                                    <td>{{ $form_approval->work_location }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Date</td>
                                    <td>{{ date('M d, Y', strtotime($form_approval->created_at)) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Details of Work</td>
                                    <td>{{ $form_approval->details }}</td>
                                </tr>
                                @if ($form_approval->notes)
                                <tr>
                                    <td class="text-muted">Notes</td>
                                    <td style="white-space: pre-wrap;">{{ $form_approval->notes }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

{{-- UNIFORM --}}
@if(in_array($form_approval->request_for, ['Uniform', 'Uniform/Partial', 'Both']))
<div class="iur-section">
    <div class="iur-section-header">
        <h5>Uniform Details</h5>
    </div>
    <div class="iur-section-body">
        <div class="iur-mobile-scroll">
        <table class="table iur-info-table mb-0">
            <tbody>
                <tr>
                    <td class="text-muted">Size</td>
                    <td>{{ $form_approval->size }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Issued Before</td>
                    <td>{{ $form_approval->issued }}</td>
                </tr>
                @if($form_approval->issued == 'Yes')
                <tr>
                    <td class="text-muted align-top" style="line-height: 1.4; overflow-wrap: break-word;">Issued Remarks</td>
                    <td class="align-top" style="white-space: pre-wrap; overflow-wrap: anywhere; word-break: break-word;">{{ $form_approval->issued_remarks ?? '-' }}</td>
                </tr>
                @endif
                <tr>
                    <td class="text-muted">Reason</td>
                    <td>{{ $form_approval->issued_reasons ?? '-' }}</td>
                </tr>
                @php
                    $uniformConditionProofs = $form_approval->uniformConditionImages->pluck('image_path');
                    if ($uniformConditionProofs->isEmpty() && $form_approval->uniform_condition_image) {
                        $uniformConditionProofs->push($form_approval->uniform_condition_image);
                    }
                @endphp
                @if($uniformConditionProofs->isNotEmpty())
                <tr>
                    <td class="text-muted align-top">Uniform Condition Proofs</td>
                    <td>
                        <div class="d-flex flex-wrap" style="gap: 10px;">
                        @foreach($uniformConditionProofs as $proofPath)
                        <a href="{{ asset($proofPath) }}" target="_blank" rel="noopener noreferrer" class="d-inline-block">
                            <img src="{{ asset($proofPath) }}"
                                alt="Current uniform condition proof"
                                style="width:100px; height:100px; object-fit:cover; border:1px solid #dee2e6; border-radius:6px;">
                        </a>
                        @endforeach
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
        </div>

    <hr class="my-2">
    @if($canManageCurrentIur && $form_approval->status == 'Pending')
        <small class="text-muted fw-bold">Uniform Items</small>
        <div class="uniform-qty-grid mt-2 {{ $form_approval->prod_qty > 0 ? 'prod-visible' : '' }}">
            @if($form_approval->prod_qty > 0)
            <div class="qty-item">
                <small class="qty-label">Prod Shirt</small>
                <div class="input-group input-group-sm qty-prod">
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                    </div>
                    <input type="number" name="prod_qty" class="form-control text-center qty-input border-0 fw-bold" min="0" max="{{ $form_approval->prod_qty }}" value="{{ $form_approval->prod_qty }}">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                    </div>
                </div>
            </div>
            @endif

            @if($form_approval->white_qty > 0)
            <div class="qty-item">
                <small class="qty-label">White</small>
                <div class="input-group input-group-sm qty-white">
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                    </div>
                    <input type="number" name="white_qty" class="form-control text-center qty-input fw-bold" min="0" max="{{ $form_approval->white_qty }}" value="{{ $form_approval->white_qty }}">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                    </div>
                </div>
            </div>
            @endif

            @if($form_approval->black_qty > 0)
            <div class="qty-item">
                <small class="qty-label">Black</small>
                <div class="input-group input-group-sm qty-black">
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                    </div>
                    <input type="number" name="black_qty" class="form-control text-center qty-input border-0 fw-bold" min="0" max="{{ $form_approval->black_qty }}" value="{{ $form_approval->black_qty }}">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                    </div>
                </div>
            </div>
            @endif

            @if ($form_approval->collar_qty > 0)
            <div class="qty-item">
                <small class="qty-label">Polo</small>
                <div class="input-group input-group-sm qty-collar">
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                    </div>
                    <input type="number" name="collar_qty" class="form-control text-center qty-input border-0 fw-bold" min="0" max="{{ $form_approval->collar_qty }}" value="{{ $form_approval->collar_qty }}">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- noting the approver hiyeah -->
        <small class="uniform-zero-warning text-danger font-weight-bold d-none mt-2">
            @if($form_approval->request_for === 'Both')
                All uniform quantities are zero. The uniform portion will be
                cancelled, but the ID request will remain.
            @else
                All uniform quantities are zero. Processing will cancel this
                uniform request.
            @endif
        </small>
    @else
        <small class="text-muted d-block mb-2 font-weight-bold">Requested Items:</small>
        <div class="row">
            @if($form_approval->prod_qty > 0)
            @php $complete = $form_approval->received_prod_qty >= $form_approval->prod_qty; @endphp
            <div class="col-6 col-md-3 mb-2">
                <div class="border rounded p-2 text-center" style="border-left: 4px solid {{ $complete ? '#28a745' : '#dee2e6' }} !important;{{ $complete ? ' background-color: #d4edda;' : '' }}">
                    <small class="d-block font-weight-bold {{ $complete ? 'text-success' : 'text-muted' }}">Prod Shirt</small>
                    <span class="h5 mb-0 font-weight-bold">{{ $form_approval->received_prod_qty }}/{{ $form_approval->prod_qty }}</span>
                    @if($complete)<small class="d-block font-weight-bold text-success">Complete</small>@endif
                </div>
            </div>
            @endif
            @if($form_approval->white_qty > 0)
            @php $complete = $form_approval->received_white_qty >= $form_approval->white_qty; @endphp
            <div class="col-6 col-md-3 mb-2">
                <div class="border rounded p-2 text-center" style="border-left: 4px solid {{ $complete ? '#28a745' : '#dee2e6' }} !important;{{ $complete ? ' background-color: #d4edda;' : '' }}">
                    <small class="d-block font-weight-bold {{ $complete ? 'text-success' : 'text-muted' }}">White</small>
                    <span class="h5 mb-0 font-weight-bold {{ $complete ? 'text-success' : '' }}">{{ $form_approval->received_white_qty }}/{{ $form_approval->white_qty }}</span>
                    @if($complete)<small class="d-block font-weight-bold text-success">Complete</small>@endif
                </div>
            </div>
            @endif
            @if($form_approval->black_qty > 0)
            @php $complete = $form_approval->received_black_qty >= $form_approval->black_qty; @endphp
            <div class="col-6 col-md-3 mb-2">
                <div class="border rounded p-2 text-center" style="border-left: 4px solid {{ $complete ? '#28a745' : '#dee2e6' }} !important;{{ $complete ? ' background-color: #d4edda;' : '' }}">
                    <small class="d-block font-weight-bold {{ $complete ? 'text-success' : 'text-muted' }}">Black</small>
                    <span class="h5 mb-0 font-weight-bold {{ $complete ? 'text-success' : '' }}">{{ $form_approval->received_black_qty }}/{{ $form_approval->black_qty }}</span>
                    @if($complete)<small class="d-block font-weight-bold text-success">Complete</small>@endif
                </div>
            </div>
            @endif
            @if($form_approval->collar_qty > 0)
            @php $complete = $form_approval->received_collar_qty >= $form_approval->collar_qty; @endphp
            <div class="col-6 col-md-3 mb-2">
                <div class="border rounded p-2 text-center" style="border-left: 4px solid {{ $complete ? '#28a745' : '#dee2e6' }} !important;{{ $complete ? ' background-color: #d4edda;' : '' }}">
                    <small class="d-block font-weight-bold {{ $complete ? 'text-success' : 'text-muted' }}">Polo</small>
                    <span class="h5 mb-0 font-weight-bold {{ $complete ? 'text-success' : '' }}">{{ $form_approval->received_collar_qty }}/{{ $form_approval->collar_qty }}</span>
                    @if($complete)<small class="d-block font-weight-bold text-success">Complete</small>@endif
                </div>
            </div>
            @endif
        </div>
    @endif
    </div>
</div>
@endif

                {{-- ID --}}
                @if($form_approval->request_for == 'ID' || $form_approval->request_for == 'Both')
                <div class="iur-section">
                    <div class="iur-section-header">
                        <h5>ID Details</h5>
                    </div>
                    <div class="iur-section-body">
                        <div class="iur-mobile-scroll">
                        <table class="table iur-info-table mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Reason</td>
                                    <td>{{ $form_approval->id_request }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">ID Picture</td>
                                    <td>
                                        @if($form_approval->id_picture)
                                        <div class="d-flex align-items-center text-nowrap" style="gap: 0.5rem; line-height: 1.5;">
                                            <a href="{{ asset($form_approval->id_picture) }}" target="_blank" class="text-truncate text-decoration-none" style="min-width: 0;">{{ $form_approval->id_picture }}</a>
                                            <span class="text-muted">|</span>
                                            <a href="{{ asset($form_approval->id_picture) }}" download class="d-inline-flex align-items-center text-primary flex-shrink-0 p-0" title="Download" aria-label="Download ID picture">
                                                <i class="ti-download"></i>
                                            </a>
                                        </div>
                                        @else
                                            <span class="text-muted fst-italic">No image uploaded</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- @if($form_approval->status == 'Pending' || !empty($form_approval->approval_remarks)) -->
                <!-- <div class="iur-section"> -->
                <!--     <div class="iur-section-header"> -->
                <!--         <h5> -->
                <!--             Remarks -->
                <!--             <span class="text-muted"><small>(optional)</small></span> -->
                <!--         </h5> -->
                <!--     </div> -->
                <!--     <div class="iur-section-body"> -->
                <!--         @if($form_approval->status == 'Pending') -->
                <!--             <textarea name="approval_remarks" class="form-control" rows="4" maxlength="2000" placeholder="Enter your remarks"></textarea> -->
                <!--         @else -->
                <!--             <p class="mb-0 fw-bold" style="white-space: pre-wrap; overflow-wrap: anywhere; word-break: break-word;">{{ trim($form_approval->approval_remarks) }}</p> -->
                <!--         @endif -->
                <!--     </div> -->
                <!-- </div> -->
                <!-- @endif -->

                </form>

                @php
                    $requestorMessages = array_reverse($form_approval->requestor_messages ?? []);
                @endphp

                @if($form_approval->status === 'Pending' || count($requestorMessages) > 0)
                <div class="iur-section">
                    <div class="iur-section-header">
                        <h5>
                            Notes
                            <span class="text-muted">
                            </span>
                        </h5>
                    </div>
                    <div class="iur-section-body">
                        @if(count($requestorMessages) > 0)
                            <div class="iur-message-history">
                                @foreach($requestorMessages as $requestorMessage)
                                    <div class="iur-message-entry">
                                        <div class="iur-message-date">
                                            {{ !empty($requestorMessage['sent_at']) ? \Carbon\Carbon::parse($requestorMessage['sent_at'])->format('M d, Y h:i A') : 'Date unavailable' }}
                                        </div>
                                        <div class="iur-message-text">{{ $requestorMessage['message'] ?? '-' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($canManageCurrentIur && $form_approval->status == 'Pending')
                            <form method="POST" action="{{ url('message-iur-requestor/' . $form_approval->id) }}" class="iur-message-form">
                                @csrf
                                <div class="iur-message-composer">
                                    <textarea style="resize: none;" id="requestor-message-{{ $form_approval->id }}" name="message" class="form-control" rows="4" maxlength="2000" required placeholder="Explain what the requestor needs to correct or update e.g., change ID picture"></textarea>
                                    <button type="submit" class="btn btn-sm btn-outline-secondary iur-message-btn" title="Send this note back to the requestor">
                                        <i class="fa fa-reply" aria-hidden="true"></i>
                                        <span>Send</span>
                                    </button>
                                </div>
                                <small class="form-text text-muted">This sends an email and does not process or change the request status.</small>
                            </form>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer iur-modal-footer">
                @if($canManageCurrentIur && $form_approval->status == 'Pending')
                <button type="submit" form="processIurForm-{{ $form_approval->id }}" name="btnApprove" class="btn btn-primary iur-process-btn">Process</button>
                @endif
                <button type="button" class="btn btn-light border iur-close-btn" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
.iur-section {
    border: 1px solid #e3e6ed;
    border-radius: 0.6rem;
    margin-bottom: 1.4rem;
    overflow: hidden;
}

.iur-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.9rem 1.2rem;
    background: #f8f9fc;
    border-bottom: 1px solid #e3e6ed;
}

.iur-section-header h5 {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
}

.iur-section-header h5 i {
    color: #007bff;
}

.iur-section-body {
    padding: 1.2rem;
    background: #fff;
}

.iur-info-table {
    table-layout: fixed;
    width: 100%;
}

.iur-info-table td {
    border: none;
    padding: 0.45rem 0.5rem;
    vertical-align: top;
}

.iur-info-table td:first-child {
    width: 30%;
    color: #6c757d;
    font-size: 0.82rem;
    font-weight: 600;
}

.iur-info-table td:last-child {
    min-width: 0;
    font-size: 0.88rem;
    overflow-wrap: anywhere;
    word-break: break-word;
}

.iur-message-history {
    display: grid;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.iur-message-entry {
    padding: 0.85rem 1rem;
    background: #f8f9fc;
    border-left: 3px solid #6c8eab;
    border-radius: 0.35rem;
}

.iur-message-date {
    margin-bottom: 0.3rem;
    color: #6c757d;
    font-size: 0.75rem;
    font-weight: 600;
}

.iur-message-text {
    color: #343a40;
    font-size: 0.88rem;
    line-height: 1.5;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.iur-message-composer {
    position: relative;
}

.iur-message-composer textarea {
    min-height: 140px;
    padding-bottom: 4.25rem;
    resize: vertical;
}

.iur-message-btn {
    position: absolute;
    right: 0.75rem;
    bottom: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    max-width: calc(100% - 1.5rem);
    min-height: 38px;
    border-radius: 999px;
    white-space: nowrap;
}

.uniform-qty-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}
.uniform-qty-grid.prod-visible {
    grid-template-columns: repeat(4, 1fr);
}
.qty-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}
.qty-label {
    font-size: 11px;
    color: #6c757d;
    text-align: center;
    white-space: nowrap;
    font-weight: 600;
}
.qty-input {
    background: #fff !important;
    font-weight: bold;
}

/* prod */
.qty-prod .qty-input {
    background: #ff4d4d !important;
    color: #fff;
}
.qty-prod .btn-outline-secondary {
    background: #ff8080;
    border-color: #ff8080;
    color: #fff;
}

/* white */
.qty-white .qty-input {
    background: #fff !important;
    color: #333;
}
.qty-white .btn-outline-secondary {
    background: #f0f0f0;
    border-color: #ccc;
}

/* black */
.qty-black .qty-input {
    background: #222 !important;
    color: #fff;
}
.qty-black .btn-outline-secondary {
    background: #444;
    border-color: #444;
    color: #fff;
}

/* polo */
.qty-collar .qty-input {
    background: linear-gradient(140deg, #59c2e5 50%, #fe0001 50%) !important;
    color: #fff;
    font-weight: bold;
}

.qty-collar .input-group-prepend .btn-outline-secondary {
    background: #91d7ee;
    border-color: #91d7ee;
    color: #fff;
}

.qty-collar .input-group-append .btn-outline-secondary {
    background: #ff4d4d;
    border-color: #ff4d4d;
    color: #fff;
}

@media (max-width: 991.98px) {
    .uniform-qty-grid,
    .uniform-qty-grid.prod-visible {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 767.98px) {
    .iur-section-header,
    .iur-section-body {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .iur-mobile-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .iur-mobile-scroll .iur-info-table {
        min-width: 600px;
        margin-bottom: 0;
    }

    .iur-modal-footer {
        display: flex;
        flex-direction: column !important;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem !important;
    }

    .iur-modal-footer .btn {
        flex: 0 0 auto !important;
        width: 100% !important;
        max-width: 320px;
        min-height: 44px;
        margin: 0 !important;
        white-space: nowrap;
    }

    .iur-modal-footer .iur-process-btn {
        order: 1;
    }

    .iur-modal-footer .iur-close-btn {
        order: 2;
    }

}
</style>

<script>
if (typeof window._iurQtyInit === 'undefined') {
    window._iurQtyInit = true;

    $(document).on('click', '.qty-plus', function() {
        let input = $(this).closest('.input-group').find('.qty-input');
        let val = parseInt(input.val(), 10) || 0;
        let max = parseInt(input.attr('max'), 10);

        if (val < max) {
            input.val(val + 1);
            zeroQuantityWarning(input.closest('form'));
        }
    });

    $(document).on('click', '.qty-minus', function() {
        let input = $(this).closest('.input-group').find('.qty-input');
        let val = parseInt(input.val(), 10) || 0;
        let min = parseInt(input.attr('min'), 10);

        if (val > min) {
            input.val(val - 1);
            zeroQuantityWarning(input.closest('form'));
        }
    });

    $(document).on('input change', '.qty-input', function() {
        zeroQuantityWarning($(this).closest('form'));
    });

    $(function() {
        $('form[id^="processIurForm-"]').each(function() {
            zeroQuantityWarning($(this));
        });
    });
}

var _accNoteTypes = ['Face to face', 'Near location', 'Field location', 'Pickup', 'Courier', 'Officer Name'];

function _parseAccNotes(raw) {
    if (!raw) return { type: '', detail: '' };
    for (var i = 0; i < _accNoteTypes.length; i++) {
        var t = _accNoteTypes[i];
        if (raw === t) return { type: t, detail: '' };
        if (raw.indexOf(t + ': ') === 0) return { type: t, detail: raw.substring(t.length + 2) };
    }
    return { type: '', detail: raw };
}

function zeroQuantityWarning(form) {
    let total = 0;
    let warning = form.find('.uniform-zero-warning');

    form.find('.qty-input').each(function() {
        total += parseInt($(this).val(), 10) || 0;
    });

    warning.toggleClass('d-none', total !== 0);
    warning.toggleClass('d-block', total === 0);
}

$(document).on('click', '.acc-notes-text', function() {
    var $cell = $(this).closest('.acc-notes-cell');
    var parsed = _parseAccNotes($cell.data('notes'));
    $cell.find('.acc-notes-type').val(parsed.type);
    $cell.find('.acc-notes-detail').val(parsed.detail);
    $cell.find('.acc-notes-text').hide();
    $cell.find('.acc-notes-edit').show();
    $cell.find('.acc-notes-type').focus();
});

$(document).on('click', '.acc-notes-cancel', function() {
    var $cell = $(this).closest('.acc-notes-cell');
    $cell.find('.acc-notes-edit').hide();
    $cell.find('.acc-notes-text').show();
});

$(document).on('click', '.acc-notes-save', function() {
    var $cell = $(this).closest('.acc-notes-cell');
    var accId = $cell.data('acc-id');
    var type = $cell.find('.acc-notes-type').val();
    var detail = $cell.find('.acc-notes-detail').val().trim();
    var notes = type ? (detail ? type + ': ' + detail : type) : detail;

    $.ajax({
        type: 'POST',
        url: '/update-acc-notes/' + accId,
        data: {
            notes: notes,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $cell.data('notes', notes);
            if (notes) {
                $cell.find('.acc-notes-text').removeClass('text-muted fst-italic').text(notes).attr('title', 'Click to edit');
            } else {
                $cell.find('.acc-notes-text').addClass('text-muted fst-italic').text('Add note...').attr('title', 'Click to add notes');
            }
            $cell.find('.acc-notes-edit').hide();
            $cell.find('.acc-notes-text').show();
        },
        error: function() {
            Swal.fire('Error', 'Failed to save notes.', 'error');
        }
    });
});
</script>
