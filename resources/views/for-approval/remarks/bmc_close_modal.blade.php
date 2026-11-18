@php
    $isConfirmationRetry = (string) old('confirmation_accountability_id') === (string) $accountability->id;
    $confirmationError = $isConfirmationRetry
        ? ($errors->first('return_mode') ?: $errors->first('return_mode_details') ?: $errors->first('returned_at'))
        : null;
    $approverRemarksError = $isConfirmationRetry ? $errors->first('approver_remarks') : null;
    $oldApproverRemarks = $isConfirmationRetry ? old('approver_remarks') : null;
@endphp
<div
    class="modal fade"
    id="closeBmcAccountability{{ $accountability->id }}"
    tabindex="-1"
    role="dialog"
    aria-labelledby="closeBmcAccountabilityTitle{{ $accountability->id }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg" role="document">
        <form
            method="POST"
            action="{{ url('/close-bmc-accountability/' . $accountability->id) }}"
            onsubmit="return prepareBmcClose(this);"
        >
            @csrf
            <input type="hidden" name="confirmation_accountability_id" value="{{ $accountability->id }}">

            <div class="modal-content border-0">
                <div class="modal-header">
                    <div>
                        <h5
                            class="modal-title font-weight-bold"
                            id="closeBmcAccountabilityTitle{{ $accountability->id }}"
                        >
                            <i class="ti-check-box mr-1"></i>
                            Confirm Returned Item(s)
                        </h5>
                        <small class="text-white">{{ $accountability->accountability_ref }}</small>
                    </div>

                    <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="border rounded bg-light p-3 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold d-block mb-1">
                            Borrower's Return Remarks
                        </small>
                        <div style="white-space: pre-wrap; overflow-wrap: anywhere;">{{ $accountability->closing_remarks }}</div>
                    </div>

                    @php
                        $pendingReturnRequests = $accountability->pendingReturns
                            ->keyBy('bmc_accountability_item_id');
                        $submittedReturn = $pendingReturnRequests->first();
                        $selectedReturnMode = $isConfirmationRetry ? old('return_mode') : optional($submittedReturn)->return_mode;
                        $selectedReturnDetails = $isConfirmationRetry ? old('return_mode_details') : optional($submittedReturn)->return_mode_details;
                        $proofPhotos = $accountability->pendingReturns
                            ->pluck('attachment_paths')->collapse()->unique()->values();
                    @endphp

                    @if ($proofPhotos->isNotEmpty())
                        <div class="d-flex flex-column mb-3">
                            @foreach ($proofPhotos as $photo)
                                <a href="{{ asset($photo) }}" target="_blank" rel="noopener noreferrer"
                                    class="mb-1">
                                    <small>
                                        View attachment {{$proofPhotos->count() > 1 ? '' . $loop->iteration : '' }}
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-3">
                        <h6 class="font-weight-bold mb-2">Items Being Returned</h6>

                        @foreach ($accountability->items as $item)
                            @php
                                $pendingReturnRequest =
                                    $pendingReturnRequests->get($item->id);
                                $requestedReturningQuantity = (int) optional(
                                    $pendingReturnRequest
                                )->returned_quantity;
                                $releasedQuantity =
                                    (int) ($item->released_quantity ?: 1);
                                $returnedQuantity =
                                    (int) ($item->returned_quantity ?? 0);
                                $remainingBeforeReturn = max(
                                    $releasedQuantity - $returnedQuantity,
                                    0
                                );
                                $remainingAfterReturn = max(
                                    $remainingBeforeReturn -
                                    $requestedReturningQuantity,
                                    0
                                );
                                $pendingSerialPayload =
                                    $pendingReturnRequest
                                        ? json_decode(
                                            $pendingReturnRequest
                                                ->serial_numbers,
                                            true
                                        )
                                        : null;
                                $borrowerSelectedSerialNumbers =
                                    is_array($pendingSerialPayload) &&
                                    isset($pendingSerialPayload['numbers'])
                                        ? collect(
                                            $pendingSerialPayload['numbers']
                                        )
                                            ->map(function ($serialNumber) {
                                                return trim(
                                                    (string) $serialNumber
                                                );
                                            })
                                            ->filter()
                                            ->values()
                                        : collect(explode(
                                            ',',
                                            (string) optional(
                                                $pendingReturnRequest
                                            )->serial_numbers
                                        ))
                                            ->map(function ($serialNumber) {
                                                return trim($serialNumber);
                                            })
                                            ->filter()
                                            ->values();
                            @endphp

                            @if ($requestedReturningQuantity > 0)
                                <section class="border rounded mb-3">
                                    <div class="d-flex rounded-top justify-content-between align-items-start bg-light border-bottom px-3 py-2">
                                        <div class="pr-3">
                                            <strong class="d-block">
                                                {{ $item->item_name }}
                                            </strong>
                                        </div>
                                        <span class="badge badge-info text-white">
                                            {{ $requestedReturningQuantity }}
                                            {{ $requestedReturningQuantity === 1 ? 'unit' : 'units' }}
                                        </span>
                                    </div>

                                    <div class="p-3">
                                        <small class="text-muted text-uppercase font-weight-bold d-block mb-1">
                                            Serial Number(s) Being Returned
                                        </small>

                                        <div class="mb-3" style="overflow-wrap: anywhere;">
                                            @forelse ($borrowerSelectedSerialNumbers as $serialNumber)
                                                <div class="py-1 font-weight-bold alert alert-info" style="margin-bottom: 5px;">
                                                    {{ $item->item_name }} - {{ $serialNumber }}
                                                </div>
                                            @empty
                                                <span class="text-danger">
                                                    No items.
                                                </span>
                                            @endforelse
                                        </div>

                                        <!-- <div class="row small"> -->
                                        <!--     <div class="col-md-4 mb-2 mb-md-0"> -->
                                        <!--         <span class="text-muted d-block">Released</span> -->
                                        <!--         <strong>{{ $releasedQuantity }}</strong> -->
                                        <!--     </div> -->
                                        <!--     <div class="col-md-4 mb-2 mb-md-0"> -->
                                        <!--         <span class="text-muted d-block">Previously Returned</span> -->
                                        <!--         <strong>{{ $returnedQuantity }}</strong> -->
                                        <!--     </div> -->
                                        <!--     <div class="col-md-4"> -->
                                        <!--         <span class="text-muted d-block">Remaining After Confirmation</span> -->
                                        <!--         <strong>{{ $remainingAfterReturn }}</strong> -->
                                        <!--     </div> -->
                                        <!-- </div> -->

                                        <input
                                            type="hidden"
                                            value="{{ $requestedReturningQuantity }}"
                                            data-bmc-return-quantity
                                            data-remaining="{{ $remainingBeforeReturn }}"
                                        >
                                    </div>
                                </section>
                            @endif
                        @endforeach
                    </div>

                    @include('forms.bmc._return_mode_fields', [
                        'fieldPrefix' => 'bmcConfirmationReturnMode' . $accountability->id,
                        'selectedMode' => $selectedReturnMode,
                        'details' => $selectedReturnDetails,
                        'modeError' => $confirmationError,
                    ])

                    <div class="form-group mb-3">
                        <label
                            for="bmcReturnedAt{{ $accountability->id }}"
                            class="font-weight-bold"
                        >
                            Date Returned
                        </label>
                        <input
                            type="date"
                            id="bmcReturnedAt{{ $accountability->id }}"
                            name="returned_at"
                            class="form-control"
                            value="{{ old('returned_at', now()->format('Y-m-d')) }}"
                            max="{{ now()->format('Y-m-d') }}"
                            required
                        >
                    </div>

                    <div class="form-group mb-0">
                        <label for="bmcApproverRemarks{{ $accountability->id }}" class="font-weight-bold">
                            Remarks <span class="text-muted font-weight-normal">(optional)</span>
                        </label>
                        <textarea id="bmcApproverRemarks{{ $accountability->id }}" name="approver_remarks"
                            class="form-control{{ $approverRemarksError ? ' is-invalid' : '' }}" rows="3" maxlength="2000"
                            placeholder="Any details regarding to this confirmation"
                            @if ($approverRemarksError)
                                aria-invalid="true" aria-describedby="bmcApproverRemarksError{{ $accountability->id }}"
                            @endif
                        >{{ is_string($oldApproverRemarks) ? $oldApproverRemarks : '' }}</textarea>
                        @if ($approverRemarksError)
                            <div id="bmcApproverRemarksError{{ $accountability->id }}" class="invalid-feedback" role="alert">{{ $approverRemarksError }}</div>
                        @endif
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        Confirm Return
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
@if ($confirmationError || $approverRemarksError)
    $(function () {
        $('#closeBmcAccountability{{ $accountability->id }}').modal('show');
    });
@endif

if (typeof window.prepareBmcClose !== 'function') {
    window.prepareBmcClose = function(form) {
        const $form = $(form);
        let returningAnyQuantity = false;
        let invalidQuantity = false;

        $form.find('[data-bmc-return-quantity]').each(function() {
            const quantity = Number($(this).val() || 0);
            const remaining = Number($(this).data('remaining') || 0);

            if (!Number.isInteger(quantity) || quantity < 0 || quantity > remaining) {
                invalidQuantity = true;
            }

            if (quantity > 0) {
                returningAnyQuantity = true;
            }
        });

        if (invalidQuantity) {
            Swal.fire('Invalid quantity', 'A borrower return quantity exceeds the remaining quantity.', 'warning');
            return false;
        }

        if (!returningAnyQuantity) {
            Swal.fire('Serial number required', 'The borrower must select at least one serial number before confirmation.', 'warning');
            return false;
        }

        form.querySelector('button[type="submit"]').disabled = true;
        show();

        return true;
    };
}

</script>
