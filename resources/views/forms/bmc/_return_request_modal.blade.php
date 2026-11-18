@php
    $isReturnRetry = (string) old('return_accountability_id') === (string) $accountability->id;
    $returnModeError = $isReturnRetry
        ? ($errors->first('return_mode') ?: $errors->first('return_mode_details'))
        : null;
@endphp
<div
    class="modal fade"
    id="requestBmcReturn{{ $accountability->id }}"
    tabindex="-1"
    role="dialog"
    aria-labelledby="requestBmcReturnTitle{{ $accountability->id }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg" role="document">
        <form
            method="POST"
            enctype="multipart/form-data"
            action="{{ route('bmc.return.request', $accountability->id) }}"
            onsubmit="return prepareBmcReturnRequest(this);"
        >
            @csrf
            <input type="hidden" name="return_accountability_id" value="{{ $accountability->id }}">

            <div class="modal-content border-0">
                <div class="modal-header">
                    <div>
                        <h5
                            class="modal-title font-weight-bold"
                            id="requestBmcReturnTitle{{ $accountability->id }}"
                        >
                            Return items
                        </h5>
                        <small class="text-white">{{ $accountability->accountability_ref }}</small>
                    </div>
                    <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Select the exact serial number(s) of item you are returning now.
                    </p>

                    <div class="mb-3">
                        @foreach ($accountability->items as $item)
                            @php
                                $previouslyReturnedSerialNumbers = $accountability
                                    ->returnHistory
                                    ->where('bmc_accountability_item_id', $item->id)
                                    ->flatMap(function ($returnEntry) {
                                        return collect(explode(',', str_replace(
                                            ["\r", "\n"],
                                            ',',
                                            (string) $returnEntry->serial_numbers
                                        )))
                                            ->map(function ($serialNumber) {
                                                return trim($serialNumber);
                                            })
                                            ->filter();
                                    })
                                    ->unique()
                                    ->values();
                                $itemSerialRecords = $item->serials;
                                $availableSerials = $itemSerialRecords->isNotEmpty()
                                    ? $itemSerialRecords->filter(function ($serial) {
                                        return $serial->status ===
                                            \App\BmcItemSerial::STATUS_AVAILABLE;
                                    })->values()
                                    : collect(explode(',', str_replace(
                                        ["\r", "\n"],
                                        ',',
                                        (string) $item->serial_number
                                    )))
                                        ->map(function ($serialNumber) {
                                            return trim($serialNumber);
                                        })
                                        ->filter()
                                        ->diff($previouslyReturnedSerialNumbers)
                                        ->values();
                                $oldSelectedSerialIds = collect(old(
                                    'return_serial_ids.' . $item->id,
                                    []
                                ))->map(function ($serialId) {
                                    return (string) $serialId;
                                });
                                $oldSelectedSerialNumbers = collect(old(
                                    'return_serial_numbers.' . $item->id,
                                    []
                                ));
                            @endphp

                            @if ($availableSerials->isNotEmpty())
                                <section
                                    class="bmc-return-serial-group mb-3"
                                    data-bmc-return-request-serial-group
                                >
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>{{ $item->item_name }}</strong>
                                        <span
                                            class="badge badge-light border"
                                            data-bmc-return-request-selected-count
                                        >
                                            0 selected
                                        </span>
                                    </div>

                                    <div class="row mx-n1">
                                        @foreach ($availableSerials as $serial)
                                            @php
                                                $serialNumber = $itemSerialRecords->isNotEmpty()
                                                    ? $serial->serial_number
                                                    : $serial;
                                                $serialId = $itemSerialRecords->isNotEmpty()
                                                    ? $serial->id
                                                    : null;
                                                $serialInputName = $itemSerialRecords->isNotEmpty()
                                                    ? 'return_serial_ids[' . $item->id . '][]'
                                                    : 'return_serial_numbers[' . $item->id . '][]';
                                                $serialSelected = $itemSerialRecords->isNotEmpty()
                                                    ? $oldSelectedSerialIds->contains((string) $serialId)
                                                    : $oldSelectedSerialNumbers->contains($serialNumber);
                                            @endphp
                                            <div class="col-12 px-1 mb-2">
                                                <label class="bmc-return-serial-option mb-0">
                                                    <input
                                                        type="checkbox"
                                                        name="{{ $serialInputName }}"
                                                        value="{{ $serialId ?? $serialNumber }}"
                                                        data-bmc-return-request-serial
                                                        {{ $serialSelected ? 'checked' : '' }}
                                                    >
                                                    <span>{{ $serialNumber }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                        @endforeach
                    </div>

                    @include('forms.bmc._return_mode_fields', [
                        'fieldPrefix' => 'bmcBorrowerReturnMode' . $accountability->id,
                        'selectedMode' => $isReturnRetry ? old('return_mode') : null,
                        'details' => $isReturnRetry ? old('return_mode_details') : null,
                        'modeError' => $returnModeError,
                    ])

                    <div class="form-group">
                        <label
                            for="bmcReturnRemarks{{ $accountability->id }}"
                            class="font-weight-bold"
                        >
                            Return Remarks <span class="text-danger">*</span>
                        </label>
                        <textarea
                            id="bmcReturnRemarks{{ $accountability->id }}"
                            name="return_remarks"
                            class="form-control"
                            rows="4"
                            maxlength="2000"
                            placeholder="e.g., item condition"
                            required
                        >{{ old('return_remarks') }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label for="bmcReturnAttachment{{ $accountability->id }}" class="font-weight-bold">
                            Proof of Attachment <span class="text-danger font-weight-normal">*</span>
                        </label>
                        <input type="file" id="bmcReturnAttachment{{ $accountability->id }}" name="attachment[]"
                            class="form-control-file" accept="image/jpeg,image/png" multiple
                            aria-describedby="bmcReturnAttachmentHelp{{ $accountability->id }}" required>
                        <small id="bmcReturnAttachmentHelp{{ $accountability->id }}" class="form-text text-muted">
                            Upload up to 3 JPG or PNG photos, 5 MB each.
                        </small>
                    </div>
                </div>

                <div class="modal-footer d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center px-3">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-info">
                        Return
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .bmc-return-serial-group {
        padding: 14px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    .bmc-return-serial-option {
        display: flex;
        align-items: center;
        min-height: 42px;
        padding: 10px 12px;
        border: 1px solid #dee2e6;
        border-radius: 7px;
        background: #ffffff;
        cursor: pointer;
    }

    .bmc-return-serial-option:hover {
        border-color: #59c2e5;
        background: #f7fcfe;
    }

    .bmc-return-serial-option input {
        margin-right: 10px;
    }

    .bmc-return-serial-option span {
        overflow-wrap: anywhere;
        font-weight: 600;
    }
</style>

<script>
@if ($returnModeError)
    $(function () {
        $('#requestBmcReturn{{ $accountability->id }}').modal('show');
    });
@endif

if (typeof window.prepareBmcReturnRequest !== 'function') {
    window.prepareBmcReturnRequest = function(form) {
        const $form = $(form);
        const selectedSerialCount = $form
            .find('[data-bmc-return-request-serial]:checked')
            .length;

        if (selectedSerialCount < 1) {
            Swal.fire(
                'Serial number required',
                'Select at least one serial number to return.',
                'warning'
            );
            return false;
        }

        form.querySelector('button[type="submit"]').disabled = true;
        show();

        return true;
    };
}

if (typeof window.syncBmcReturnRequestSerialCount !== 'function') {
    window.syncBmcReturnRequestSerialCount = function(group) {
        const $group = $(group);
        const selectedCount = $group
            .find('[data-bmc-return-request-serial]:checked')
            .length;

        $group
            .find('[data-bmc-return-request-selected-count]')
            .text(selectedCount + ' selected');
    };
}

$(document)
    .off('change.bmcReturnRequestSerial', '[data-bmc-return-request-serial]')
    .on('change.bmcReturnRequestSerial', '[data-bmc-return-request-serial]', function() {
        window.syncBmcReturnRequestSerialCount(
            $(this).closest('[data-bmc-return-request-serial-group]')
        );
    });

$('[data-bmc-return-request-serial-group]').each(function() {
    window.syncBmcReturnRequestSerialCount(this);
});
</script>
