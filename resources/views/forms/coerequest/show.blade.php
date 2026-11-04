<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="modal fade" id="view-coe-{{ $coe->id }}" tabindex="-1" aria-labelledby="coeRequestModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="coeRequestModalLabel">
                    <strong>CERTIFICATE OF EMPLOYMENT REQUEST</strong><br />
                    <p style="color: #e6e6e6;">HRD-LPD-FOR-00X-000</p>
                </h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <form id="coeRequestForm" method="POST" action="new-coe">
                @csrf
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Information Request</h6>
                        @php
                            $statusStyles = [
                                'Approved'   => 'coe-status--released',
                                'Pending'    => 'coe-status--pending',
                                'Processing' => 'coe-status--processing',
                                'Declined'   => 'coe-status--declined',
                                'Cancelled'  => 'coe-status--declined',
                            ];
                            $statusClass = $statusStyles[$coe->status] ?? '';
                            $statusLabel = $coe->status === 'Approved' ? 'Released' : $coe->status;
                        @endphp
                        <span class="coe-status {{ $statusClass }} mt-0">{{ $statusLabel }}</span>
                    </div>

                    <div class="mb-3" style="font-size: 13px;">
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Name:</div>
                            <div class="col-md-8">{{ $coe->first_name }} {{ $coe->last_name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Position:</div>
                            <div class="col-md-8">{{ $coe->designation ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Email:</div>
                            <div class="col-md-8">{{ $coe->email }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Hiring Date:</div>
                            <div class="col-md-8">{{ \Carbon\Carbon::parse($coe->hiring_date)->format('F d, Y') }}</div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3" style="font-size: 13px;">
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Certificate Type:</div>
                            <div class="col-md-8">{{ $coe->reason_for_request }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Purpose:</div>
                            <div class="col-md-8">{{ $coe->purpose }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Delivery Method:</div>
                            <div class="col-md-8">{{ $coe->receive_method === 'Hard Copy' ? 'Pick up' : $coe->receive_method }}</div>
                        </div>

                        @if($coe->receive_method === 'Email')
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Email Address:</div>
                                <div class="col-md-8">{{ $coe->email }}</div>
                            </div>
                        @elseif($coe->receive_method === 'Viber')
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Viber Number:</div>
                                <div class="col-md-8">{{ $coe->viber_number ?? '-' }}</div>
                            </div>
                        @elseif($coe->receive_method === 'Hard Copy')
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Pickup:</div>
                                <div class="col-md-8">
                                    <div class="alert alert-info mb-0 py-1" style="font-size: 13px;">
                                        Your COE will be available for pickup at the HR office.
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Additional Notes:</div>
                            <div class="col-md-8">{{ $coe->additional_notes ?? '-' }}</div>
                        </div>
                    </div>

                    @if($coe->status === 'Approved')
                        <hr>
                        <div class="mb-3" style="font-size: 13px;">
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Delivery Proof:</div>
                                <div class="col-md-8">
                                  @if($coe->proof)
                                  <a href="{{ asset($coe->proof) }}" class="d-flex align-items-center" target="_blank" style="color: #0d6efd;">
                                    <i class="ti-image mr-1"></i> View proof of delivery
                                  </a>
                                  @else
                                  <span class="text-muted">-</span>
                                  @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($coe->approval_remarks && in_array($coe->status, ['Approved', 'Declined']))
                        <hr>
                        <div class="mb-3" style="font-size: 13px;">
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">{{ $coe->status === 'Approved' ? 'Release Remarks:' : 'Approval Remarks:' }}</div>
                                <div class="col-md-8">
                                  {{ $coe->approval_remarks ? $coe->approval_remarks : '-' }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @php($actionTrail = $coe->trails)

                    @if($actionTrail->isNotEmpty())
                        <hr>
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-uppercase text-muted mb-3" style="font-size: 0.875rem; letter-spacing: 0.02em;">Activity</h6>
                            <div class="border rounded bg-light px-3 pt-3">
                                @foreach($actionTrail as $item)
                                    <div class="d-flex {{ !$loop->last ? 'border-bottom mb-3 pb-3' : 'pb-3' }}">
                                        <span class="bg-primary rounded-circle flex-shrink-0 mt-1 mr-3" style="width: 9px; height: 9px;"></span>
                                        <div style="min-width: 0;">
                                            <div class="font-weight-bold text-break">{{ $item->action_label }}</div>
                                            <div class="text-muted text-break" style="font-size: 0.78rem;">
                                                {{ $item->performed_by_name }} &middot; {{ $item->created_at->format('M d, Y, h:i A') }}
                                            </div>
                                            @if($item->remarks)
                                                <div class="mt-1 text-break" style="font-size: 0.82rem; white-space: pre-wrap;">{{ $item->remarks }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <input type="hidden" name="resignation_date" value="{{ optional($coe->employee)->date_resigned }}" />
                    <input type="hidden" name="processed_at" value="{{ $coe->processed_at }}">
                    <input type="hidden" name="approver_name" value="{{ optional($coe->approvedBy)->name }}" />
                    <input type="hidden" name="approver_signature" value="{{ optional(optional($coe->approvedBy)->employee)->signature ? asset(optional(optional($coe->approvedBy)->employee)->signature) : '' }}" />
                    <input type="hidden" name="approver_position" value="{{ optional(optional($coe->approvedBy)->employee)->position }}" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@include('for-approval.print-coe')
