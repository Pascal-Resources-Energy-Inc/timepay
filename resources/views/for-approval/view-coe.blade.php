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
                @if ($coe->resign_date)
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted mb-1 mb-sm-0">Separation Date:</div>
                        <div class="col-sm-8 text-break">{{ $coe->resign_date ? \Carbon\Carbon::parse($coe->resign_date)->format('M d, Y') : 'N/A' }}</div>
                    </div>
                @endif
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Employment Status:</div>
                    <div class="col-sm-8 text-break">{{ $coe->employment_status }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Designation:</div>
                    <div class="col-sm-8 text-break">{{ $coe->designation }}</div>
                </div>

                @if($coe->user_id === null && $user_role === 'coe_approver' && in_array($coe->status, ['Pending', 'Processing']))
                <div class="border rounded bg-light p-3 mt-3">
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                        <div class="pr-sm-3">
                            <small class="text-muted">Correct employment information before completing this request.</small>
                        </div>
                        <button
                            type="button"
                            class="btn btn-outline-primary btn-sm mt-2 mt-sm-0 flex-shrink-0"
                            data-toggle="collapse"
                            data-target="#edit-public-coe-{{ $coe->id }}"
                            aria-expanded="false"
                            aria-controls="edit-public-coe-{{ $coe->id }}"
                        >
                            <i class="ti-pencil mr-1"></i> Edit details
                        </button>
                    </div>

                    <div class="collapse mt-3" id="edit-public-coe-{{ $coe->id }}">
                        <form method="POST" action="{{ url('update-public-coe/'.$coe->id) }}">
                            @csrf
                            <div class="form-group">
                                <label for="designation-{{ $coe->id }}">Designation <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    id="designation-{{ $coe->id }}"
                                    name="designation"
                                    class="form-control"
                                    value="{{ $coe->designation }}"
                                    maxlength="255"
                                    required
                                >
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="hiring-date-{{ $coe->id }}">Hiring date <span class="text-danger">*</span></label>
                                    <input
                                        type="date"
                                        id="hiring-date-{{ $coe->id }}"
                                        name="hiring_date"
                                        class="form-control"
                                        value="{{ $coe->hiring_date ? \Carbon\Carbon::parse($coe->hiring_date)->format('Y-m-d') : '' }}"
                                        max="{{ now()->format('Y-m-d') }}"
                                        required
                                    >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="resign-date-{{ $coe->id }}">Separation date <span class="text-danger">*</span></label>
                                    <input
                                        type="date"
                                        id="resign-date-{{ $coe->id }}"
                                        name="resign_date"
                                        class="form-control"
                                        value="{{ $coe->resign_date ? \Carbon\Carbon::parse($coe->resign_date)->format('Y-m-d') : '' }}"
                                        max="{{ now()->format('Y-m-d') }}"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

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

                @php($actionTrail = $coe->trails)

                @if($actionTrail->isNotEmpty())
                <hr class="mt-3 mb-3">
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
                @if($coe->attachment_remarks)
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted mb-1 mb-sm-0">Attachment Remarks:</div>
                    <div class="col-sm-8 text-break" style="white-space: pre-wrap;">{{ $coe->attachment_remarks }}</div>
                </div>
                @endif
                @endif

                @if($coe->receive_method === 'Email' && str_contains($coe->purpose, 'Employment') && $coe->status === 'Approved')
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
