<div class="modal fade" id="view-modal-{{ $coe->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header align-items-start">
                <div class="pr-3" style="min-width: 0;">
                    <h5 class="modal-title font-weight-bold mb-1 text-break">COE REQUEST DETAILS</h5>
                    <p class="mb-0 text-break" style="color: #e6e6e6;">HRD-LPD-FOR-00X-000</p>
                </div>
                <button type="button" class="btn-close btn-danger flex-shrink-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-3" style="font-size: 0.95rem;">

                {{-- Header: Name + Status --}}
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-start mb-2">
                    @php
                        $status = $coe->status;
                        if (in_array($status, ['Approved'])) {
                            $badgeClass = 'badge-success';
                        } elseif (in_array($status, ['Declined', 'Cancelled', 'Disapproved'])) {
                            $badgeClass = 'badge-danger';
                        } else {
                            $badgeClass = 'badge-warning';
                        }
                    @endphp
                </div>

                {{-- Divider --}}
                <hr class="mt-0 mb-3">

                {{-- Employee Information --}}
                <h6 class="font-weight-bold text-uppercase text-muted mb-2 d-flex justify-content-between align-items-center" style="font-size: 0.875rem; letter-spacing: 0.02em;">
                    <span>Employee Information</span>
                    <span class="badge {{ $badgeClass }} py-2 px-3 mt-2 mt-sm-0 flex-shrink-0" style="font-size: 0.70rem; font-weight: 500;">{{ $coe->status }}</span>
                </h6>
                <hr class="mt-0 mb-3">

                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Name:</div>
                    <div class="col-sm-8 text-break">{{ $coe->first_name . ' ' . $coe->last_name }}</div>
                </div>

                @if($coe->receive_method === 'Hard Copy')
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Pick Up</div>
                    <div class="col-sm-8 text-break">HR Office (Hard Copy)</div>
                </div>
                @elseif($coe->receive_method === 'Viber' && $coe->viber_number)
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Viber Number:</div>
                    <div class="col-sm-8 text-break">{{ $coe->viber_number }}</div>
                </div>
                @else
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Email:</div>
                    <div class="col-sm-8 text-break">{{ $coe->email ?? 'N/A' }}</div>
                </div>
                @endif
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Hiring Date:</div>
                    <div class="col-sm-8 text-break">{{ $coe->hiring_date ? \Carbon\Carbon::parse($coe->hiring_date)->format('M d, Y') : 'N/A' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Employment Status:</div>
                    <div class="col-sm-8 text-break">{{ $coe->employment_status ?? 'N/A' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Designation:</div>
                    <div class="col-sm-8 text-break">{{ $coe->designation ?? 'N/A' }}</div>
                </div>

                {{-- Divider --}}
                <hr class="mt-3 mb-3">

                {{-- Request Information --}}
                <h6 class="font-weight-bold text-uppercase text-muted mb-2" style="font-size: 0.875rem; letter-spacing: 0.02em;">Request Information</h6>
                <hr class="mt-0 mb-3">

                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Purpose:</div>
                    <div class="col-sm-8 text-break">{{ $coe->purpose ?? 'N/A' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Request:</div>
                    <div class="col-sm-8 text-break" style="{{ $coe->reason_for_request === 'With Salary' ? 'color: red;' : '' }}">{{ $coe->reason_for_request }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Receive Method:</div>
                    <div class="col-sm-8 text-break">{{ $coe->receive_method ?? 'N/A' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Date Filed:</div>
                    <div class="col-sm-8 text-break">{{ $coe->created_at ? date('M d, Y', strtotime($coe->created_at)) : 'N/A' }}</div>
                </div>

                {{-- Additional Notes --}}
                @if($coe->additional_notes)
                <hr class="mt-3 mb-3">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Additional Notes:</div>
                    <div class="col-sm-8 text-break">{{ $coe->additional_notes }}</div>
                </div>
                @endif

                {{-- Approval Remarks --}}
                @if($coe->approval_remarks)
                <hr class="mt-3 mb-3">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Remarks:</div>
                    <div class="col-sm-8 text-break">{{ $coe->approval_remarks }}</div>
                </div>
                @endif

                {{-- Attachment view --}}
                @if($coe->attachment)
                <hr class="mt-3 mb-3">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Attachment:</div>
                    <div class="col-sm-8 text-break">
                        <a class="text-break" href="{{ asset($coe->attachment) }}" target="_blank" style="color: #0d6efd;">
                            {{ basename($coe->attachment) }}
                        </a>
                    </div>
                </div>
                @endif

                @if($coe->receive_method === 'Email' && str_contains($coe->purpose, 'Employment'))
                <hr class="mt-3 mb-3">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Resend to:</div>
                    <div class="col-sm-8">
                        <form action="{{ url('resend-coe-email/'.$coe->id) }}" method="POST">
                            @csrf
                            <div class="input-group input-group-sm">
                                <input type="email" name="email" class="form-control" value="{{ $coe->email ?? '' }}" required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-sm">Resend</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center px-3">
                <!-- <button type="button" class="btn btn-primary" onclick="window.open('{{ url('coe-print', $coe->id) }}', '_blank')">Print</button> -->
                <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
