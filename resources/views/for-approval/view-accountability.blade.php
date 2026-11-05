<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Release Detail — {{ $acc->accountability_ref }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('/body_css/vendors/ti-icons/css/themify-icons.css') }}">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; background: #f4f6f9; }
        .card { border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 font-weight-bold">
                <i class="ti-package mr-1"></i> Release Detail
            </h4>
            <button onclick="window.close()" class="d-flex align-items-center btn btn-outline-secondary btn-sm">
                <i class="ti-arrow-left mr-1"></i> Back
            </button>
        </div>

        {{-- Release Info --}}
        <div class="card mb-3">
            <div class="card-header py-2" style="background: #e6e6e6; color: #666666;">
                <h6 class="mb-0 font-weight-bold">Release Information</h6>
            </div>
            <div class="card-body py-2">
                <table class="table table-sm table-borderless mb-0" style="font-size: 0.85rem;">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width:35%;">Reference</td>
                            <td class="fw-bold">{{ $acc->accountability_ref }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">IUR Reference</td>
                            <td class="fw-bold">{{ $acc->iur->iur_reference ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Issued Date</td>
                            <td class="fw-bold">{{ $acc->issued_date ? date('M d, Y', strtotime($acc->issued_date)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Signed At</td>
                            <td class="fw-bold">{{ $acc->signed_at ? date('M d, Y g:i A', strtotime($acc->signed_at)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>
                                @if($acc->signed_at)
                                    <span class="badge badge-success">Signed</span>
                                @else
                                    <span class="badge badge-warning">Unsigned</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Issued By</td>
                            <td class="fw-bold">{{ $acc->issuer->name ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Employee Info --}}
        <div class="card mb-3">
            <div class="card-header py-2" style="background: #e6e6e6; color: #666666;">
                <h6 class="mb-0 font-weight-bold">Employee Information</h6>
            </div>
            <div class="card-body py-2">
                <table class="table table-sm table-borderless mb-0" style="font-size: 0.85rem;">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width:35%;">Name</td>
                            <td class="fw-bold">{{ $acc->iur->user->first_name ?? '' }} {{ $acc->iur->user->last_name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Employee Code</td>
                            <td class="fw-bold">PREI - {{ $acc->iur->user->employee_code ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Position</td>
                            <td class="fw-bold">{{ $acc->iur->user->position ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Department</td>
                            <td class="fw-bold">{{ $acc->iur->user->department->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Work Location</td>
                            <td class="fw-bold">{{ $acc->iur->work_location ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Released Items --}}
        <div class="card mb-3">
            <div class="card-header py-2" style="background: #e6e6e6; color: #666666;">
                <h6 class="mb-0 font-weight-bold">Released Items</h6>
            </div>
            <div class="card-body py-2">
                <table class="table table-sm table-borderless mb-0" style="font-size: 0.85rem;">
                    <tbody>
                        @if($acc->released_prod_qty > 0)
                        <tr>
                            <td class="text-muted" style="width:35%;">Prod Shirt</td>
                            <td class="fw-bold">{{ $acc->released_prod_qty }} pc(s){{ $acc->prod_condition ? ' (' . $acc->prod_condition . ')' : '' }}</td>
                        </tr>
                        @endif
                        @if($acc->released_white_qty > 0)
                        <tr>
                            <td class="text-muted">White</td>
                            <td class="fw-bold">{{ $acc->released_white_qty }} pc(s){{ $acc->white_condition ? ' (' . $acc->white_condition . ')' : '' }}</td>
                        </tr>
                        @endif
                        @if($acc->released_black_qty > 0)
                        <tr>
                            <td class="text-muted">Black</td>
                            <td class="fw-bold">{{ $acc->released_black_qty }} pc(s){{ $acc->black_condition ? ' (' . $acc->black_condition . ')' : '' }}</td>
                        </tr>
                        @endif
                        @if($acc->released_collar_qty > 0)
                        <tr>
                            <td class="text-muted">Polo</td>
                            <td class="fw-bold">{{ $acc->released_collar_qty }} pc(s){{ $acc->collar_condition ? ' (' . $acc->collar_condition . ')' : '' }}</td>
                        </tr>
                        @endif
                        @if($acc->released_id)
                        <tr>
                            <td class="text-muted">ID</td>
                            <td class="fw-bold">Yes</td>
                        </tr>
                        @endif
                        @if(!$acc->released_prod_qty && !$acc->released_white_qty && !$acc->released_black_qty && !$acc->released_collar_qty && !$acc->released_id)
                        <tr>
                            <td colspan="2" class="text-muted fst-italic">No items released</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if($acc->notes)
        {{-- Notes --}}
        <div class="card mb-3">
            <div class="card-header py-2" style="background: #e6e6e6; color: #666666;">
                <h6 class="mb-0 font-weight-bold">Issuance Notes</h6>
            </div>
            <div class="card-body py-2">
                <p class="mb-0" style="font-size: 0.85rem; white-space: pre-wrap;">{{ $acc->notes }}</p>
            </div>
        </div>
        @endif

        {{-- Signature --}}
        @if($acc->employee_signature)
        <div class="card mb-3">
            <div class="card-header py-2" style="background: #e6e6e6; color: #666666;">
                <h6 class="mb-0 font-weight-bold">Employee Signature</h6>
            </div>
            <div class="card-body py-2 text-center">
                <img src="{{ asset($acc->employee_signature) }}" style="max-height: 100px;">
            </div>
        </div>
        @endif

    </div>
</body>
</html>
