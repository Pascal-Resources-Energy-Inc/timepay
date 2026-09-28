<div class="modal fade" id="view-modal-{{ $coe->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title font-weight-bold">COE REQUEST DETAILS</h5>
                    <p style="color: #e6e6e6;">HRD-LPD-FOR-00X-000</p>
                </div>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body pt-3">

                {{-- Header: Name + Status --}}
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h4 class="mb-0 font-weight-bold">{{ $coe->user->name ?? $coe->first_name . ' ' . $coe->last_name }}</h4>
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
                    <span class="badge {{ $badgeClass }} py-2 px-3">{{ $coe->status }}</span>
                </div>

                {{-- Sub-line: Designation • Employment Status --}}
                <p class="text-muted mb-3">
                    {{ $coe->designation ?? 'N/A' }}
                    @if($coe->designation && $coe->employment_status)
                        &bull;
                    @endif
                    {{ $coe->employment_status ?? '' }}
                </p>

                {{-- Divider --}}
                <hr class="mt-0 mb-3">

                {{-- Employee Information --}}
                <h6 class="font-weight-bold text-uppercase text-muted mb-2">Employee Information</h6>
                <hr class="mt-0 mb-3">

                @if($coe->receive_method === 'Hard Copy')
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Pick Up</div>
                    <div class="col-sm-8">HR Office (Hard Copy)</div>
                </div>
                @elseif($coe->receive_method === 'Viber' && $coe->viber_number)
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Viber Number:</div>
                    <div class="col-sm-8">{{ $coe->viber_number }}</div>
                </div>
                @else
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Email:</div>
                    <div class="col-sm-8">{{ $coe->email ?? 'N/A' }}</div>
                </div>
                @endif
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Hiring Date:</div>
                    <div class="col-sm-8">{{ $coe->hiring_date ? \Carbon\Carbon::parse($coe->hiring_date)->format('M d, Y') : 'N/A' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Employment Status:</div>
                    <div class="col-sm-8">{{ $coe->employment_status ?? 'N/A' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Designation:</div>
                    <div class="col-sm-8">{{ $coe->designation ?? 'N/A' }}</div>
                </div>

                {{-- Divider --}}
                <hr class="mt-3 mb-3">

                {{-- Request Information --}}
                <h6 class="font-weight-bold text-uppercase text-muted mb-2">Request Information</h6>
                <hr class="mt-0 mb-3">

                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Purpose:</div>
                    <div class="col-sm-8">{{ $coe->purpose ?? 'N/A' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Request:</div>
                    <div class="col-sm-8" style="{{ $coe->reason_for_request === 'With Salary' ? 'color: red;' : '' }}">{{ $coe->reason_for_request }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Receive Method:</div>
                    <div class="col-sm-8">{{ $coe->receive_method ?? 'N/A' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Date Filed:</div>
                    <div class="col-sm-8">{{ $coe->created_at ? date('M d, Y', strtotime($coe->created_at)) : 'N/A' }}</div>
                </div>

                {{-- Additional Notes --}}
                @if($coe->additional_notes)
                <hr class="mt-3 mb-3">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Additional Notes:</div>
                    <div class="col-sm-8">{{ $coe->additional_notes }}</div>
                </div>
                @endif

                {{-- Approval Remarks --}}
                @if($coe->approval_remarks)
                <hr class="mt-3 mb-3">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Remarks:</div>
                    <div class="col-sm-8">{{ $coe->approval_remarks }}</div>
                </div>
                @endif

                {{-- Attachment view --}}
                @if($coe->attachment)
                <hr class="mt-3 mb-3">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Attachment:</div>
                    <div class="col-sm-8">
                        <a href="{{ asset($coe->attachment) }}" target="_blank" style="color: #0d6efd;">
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
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary" onclick="window.open('{{ url('coe-print', $coe->id) }}', '_blank')">Print</button> -->
                <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
