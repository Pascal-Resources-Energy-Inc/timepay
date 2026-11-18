<div
    class="modal fade"
    id="releaseBmc{{ $borrowing->id }}"
    tabindex="-1"
    role="dialog"
    aria-labelledby="releaseBmcTitle{{ $borrowing->id }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg" role="document">
        <form
            method="POST"
            action="{{ url('/release-bmc/' . $borrowing->id) }}"
            onsubmit="return this.dataset.preparing === '1' || prepareBmcRelease(this);"
        >
            @csrf

            <div class="modal-content border-0 shadow-sm">
                <div class="modal-header border-bottom-0 pb-2">
                    <div>
                        <h5 class="modal-title font-weight-bold" id="releaseBmcTitle{{ $borrowing->id }}">
                            <i class="ti-package mr-1"></i>
                            Release Items
                        </h5>
                        <small class="text-white">
                            Items that will be handed to the borrower
                        </small>
                    </div>

                    <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-3">
                    @php
                        $requiresDeposit = $borrowing->requiresDeposit();
                        $plannedItems = collect($borrowing->release_items ?? [])->keyBy('item_name');
                    @endphp

                    <div class="bg-light border rounded p-3 mb-4">
                        <div class="row align-items-center py-1" style="font-size: 13px;">
                            <small class="col-5 text-muted text-uppercase font-weight-bold" style="letter-spacing: .4px;">
                                Reference No.
                            </small>
                            <span class="col-7 font-weight-bold">{{ $borrowing->reference_no }}</span>
                        </div>

                        <div class="row align-items-center py-1" style="font-size: 13px;">
                            <small class="col-5 text-muted text-uppercase font-weight-bold" style="letter-spacing: .4px;">
                                Borrower
                            </small>
                            <span class="col-7 font-weight-bold">
                                {{ $borrowing->borrower_first_name }} {{ $borrowing->borrower_last_name }}
                            </span>
                        </div>

                        <div class="row align-items-center py-1" style="font-size: 13px;">
                            <small class="col-5 text-muted text-uppercase font-weight-bold" style="letter-spacing: .4px;">
                                Borrower Profile
                            </small>
                            <span class="col-7 font-weight-bold">
                                {{ $borrowing->borrower_profile }}
                            </span>
                        </div>

                        <div class="row align-items-center py-1" style="font-size: 13px;">
                            <small class="col-5 text-muted text-uppercase font-weight-bold" style="letter-spacing: .4px;">
                                Approved No. of Days
                            </small>
                            <span class="col-7 font-weight-bold">
                                {{ $borrowing->approved_number_of_days }} day(s)
                            </span>
                        </div>

                        <div class="row align-items-center py-1" style="font-size: 13px;">
                            <small class="col-5 text-muted text-uppercase font-weight-bold" style="letter-spacing: .4px;">
                                Request Details
                            </small>
                            <span class="col-7 font-weight-bold">
                                {{ $borrowing->request_details}}
                            </span>
                        </div>
                        
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="font-weight-bold mb-0">Item(s) to release:</h6>
                        <small class="text-muted"><span class="text-danger">*</span> Required fields</small>
                    </div>

                    @php
                        $releasedQuantities = $borrowing->accountabilities
                            ->flatMap(function ($accountability) {
                                return $accountability->items;
                            })
                            ->groupBy('item_name')
                            ->map(function ($items) {
                                return $items->sum(function ($item) {
                                    return (int) $item->released_quantity;
                                });
                            });

                        $releasableItems = $borrowing->borrowedItems
                            ->filter(function ($item) {
                                return (int) $item->approved_quantity > 0;
                            })
                            ->map(function ($item) use ($releasedQuantities) {
                                $releasedQuantity = (int) $releasedQuantities
                                    ->get($item->item_name, 0);

                                return [
                                    'item' => $item,
                                    'released_quantity' => $releasedQuantity,
                                    'remaining_quantity' => max(
                                        0,
                                        (int) $item->approved_quantity -
                                        $releasedQuantity
                                    ),
                                ];
                            })
                            ->filter(function ($releaseItem) {
                                return $releaseItem['remaining_quantity'] > 0;
                            })
                            ->values();
                    @endphp

                    <div class="border rounded overflow-hidden mb-4">
                        <div class="row d-none d-md-flex mx-0 px-3 py-2 bg-light border-bottom">
                            <div class="col-md-3 px-0 pr-3 text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: .4px;">
                                Item
                            </div>
                            <div class="col-md-3 px-0 pr-3 text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: .4px;">
                                Release Now
                            </div>
                            <div class="col-md-6 px-0 text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: .4px;">
                                Serial Number(s)
                            </div>
                        </div>

                        @forelse ($releasableItems as $index => $releaseItem)
                            @php
                                $item = $releaseItem['item'];
                                $oldSerialNumbers = old('items.' . $index . '.serial_numbers', []);
                                $oldSerialNumbers = is_array($oldSerialNumbers) ? $oldSerialNumbers : [];
                                $oldMbdRemarks = old('items.' . $index . '.mbd_remarks', []);
                                $oldMbdRemarks = is_array($oldMbdRemarks) ? $oldMbdRemarks : [];
                                $requestedQuantity = (int) $item->requested_quantity;
                                $approvedQuantity = (int) $item->approved_quantity;
                                $releasedQuantity = (int) $releaseItem['released_quantity'];
                                $remainingQuantity = (int) $releaseItem['remaining_quantity'];
                                $releaseQuantity = (int) old(
                                    'items.' . $index . '.released_quantity',
                                    (int) data_get($plannedItems->get($item->item_name), 'quantity', 0)
                                );
                                $releaseQuantity = max(
                                    0,
                                    min($remainingQuantity, $releaseQuantity)
                                );
                            @endphp
                            <div
                                class="row align-items-start mx-0 px-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}"
                                data-bmc-release-item
                                data-deposit-rate="{{ $requiresDeposit ? $borrowing->depositRate($item->item_name) : 0 }}"
                            >
                                <input
                                    type="hidden"
                                    name="items[{{ $index }}][item_name]"
                                    value="{{ $item->item_name }}"
                                >

                                <div class="col-md-3 px-0 pr-md-3 mb-3 mb-md-0">
                                    <small class="d-md-none text-muted text-uppercase font-weight-bold d-block mb-1" style="font-size: 10px; letter-spacing: .4px;">
                                        Item
                                    </small>
                                    <span class="font-weight-bold" style="font-size: 14px;">{{ $item->item_name }}</span>
                                    <small class="text-muted d-block">Requested: {{ $requestedQuantity }}</small>
                                    <small class="text-info d-block">Approved: {{ $approvedQuantity }}</small>
                                    <small class="text-muted d-block">Released: {{ $releasedQuantity }}</small>
                                </div>

                                <div class="col-md-3 px-0 pr-md-3 mb-3 mb-md-0">
                                    <small class="d-md-none text-muted text-uppercase font-weight-bold d-block mb-1" style="font-size: 10px; letter-spacing: .4px;">
                                        Release Now
                                    </small>
                                    <div
                                        class="bmc-release-stepper"
                                        data-bmc-release-stepper
                                    >
                                        <button
                                            type="button"
                                            class="bmc-release-stepper-button"
                                            data-bmc-release-decrease
                                            aria-label="Decrease release quantity for {{ $item->item_name }}"
                                        >&minus;</button>
                                        <input
                                            type="number"
                                            name="items[{{ $index }}][released_quantity]"
                                            value="{{ $releaseQuantity }}"
                                            min="0"
                                            max="{{ $remainingQuantity }}"
                                            class="bmc-release-stepper-value"
                                            data-bmc-release-quantity
                                            aria-label="Release quantity for {{ $item->item_name }}"
                                            required
                                        >
                                        <button
                                            type="button"
                                            class="bmc-release-stepper-button"
                                            data-bmc-release-increase
                                            aria-label="Increase release quantity for {{ $item->item_name }}"
                                        >&plus;</button>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ $remainingQuantity }} remaining
                                    </small>
                                    @if ($requiresDeposit)
                                        <small class="text-muted d-block mt-1">
                                            Deposit:
                                            <strong data-bmc-deposit-subtotal>
                                                ₱{{ number_format($borrowing->depositRate($item->item_name) * $releaseQuantity, 2) }}
                                            </strong>
                                        </small>
                                    @endif
                                </div>

                                <div class="col-md-6 px-0">
                                    <span class="d-md-none text-muted text-uppercase font-weight-bold d-block mb-2" style="font-size: 10px; letter-spacing: .4px;">
                                        Serial Number(s)
                                    </span>
                                    <div
                                        data-bmc-serial-inputs
                                        data-input-name="items[{{ $index }}][serial_numbers]"
                                        data-remarks-name="items[{{ $index }}][mbd_remarks]"
                                        data-input-id-prefix="bmcSerial{{ $borrowing->id }}_{{ $index }}"
                                        data-item-name="{{ $item->item_name }}"
                                    >
                                        @for ($serialIndex = 0; $serialIndex < $releaseQuantity; $serialIndex++)
                                            <div class="{{ $serialIndex + 1 < $releaseQuantity ? 'mb-2' : '' }}" data-bmc-serial-row>
                                                <label
                                                    for="bmcSerial{{ $borrowing->id }}_{{ $index }}_{{ $serialIndex }}"
                                                    class="small text-muted d-block mb-1"
                                                >
                                                    Serial Number {{ $serialIndex + 1 }}
                                                </label>
                                                <input
                                                    type="text"
                                                    id="bmcSerial{{ $borrowing->id }}_{{ $index }}_{{ $serialIndex }}"
                                                    name="items[{{ $index }}][serial_numbers][{{ $serialIndex }}]"
                                                    value="{{ $oldSerialNumbers[$serialIndex] ?? '' }}"
                                                    class="form-control"
                                                    maxlength="150"
                                                    placeholder="Enter serial number"
                                                    aria-label="Serial number {{ $serialIndex + 1 }} for {{ $item->item_name }}"
                                                    data-bmc-serial-input
                                                    required
                                                >
                                                <label
                                                    for="bmcSerial{{ $borrowing->id }}_{{ $index }}_{{ $serialIndex }}_remarks"
                                                    class="small text-muted d-block mt-2 mb-1"
                                                >
                                                    MBD Remarks (optional)
                                                </label>
                                                <textarea
                                                    id="bmcSerial{{ $borrowing->id }}_{{ $index }}_{{ $serialIndex }}_remarks"
                                                    name="items[{{ $index }}][mbd_remarks][{{ $serialIndex }}]"
                                                    class="form-control"
                                                    rows="2"
                                                    maxlength="2000"
                                                    data-bmc-serial-remarks
                                                >{{ is_string($oldMbdRemarks[$serialIndex] ?? null) ? $oldMbdRemarks[$serialIndex] : '' }}</textarea>
                                            </div>
                                        @endfor
                                        @if ($releaseQuantity === 0)
                                            <small class="text-muted" data-bmc-no-serials>
                                                Set a release quantity to enter serial numbers.
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center text-muted">
                                No items have an approved quantity available for release.
                            </div>
                        @endforelse
                    </div>

                    @if ($requiresDeposit)
                        <div class="d-flex align-items-center justify-content-between border rounded bg-light px-3 py-3 mb-4">
                            <div>
                                <span class="font-weight-bold d-block">Total Deposit</span>
                                <small class="text-muted">Based on the quantity being released now.</small>
                                @if ($borrowing->payment_method && $borrowing->proof_of_transfer)
                                    <div class="ml-auto">
                                        <a href="{{ url('/bmc-payment-proof/' . $borrowing->id) }}" target="_blank" rel="noopener">
                                            <small>
                                                View deposit transfer
                                            </small>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <strong class="text-primary" style="font-size: 1.15rem;" data-bmc-deposit-total>
                                ₱0.00
                            </strong>
                        </div>
                    @endif

                    <div class="card border shadow-xs mb-0">
                        <div class="card-header bg-light py-2 rounded-top">
                            <h6 class="mb-0 font-weight-bold">
                                <i class="fa fa-comment mr-1"></i> Issuance Notes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5 form-group mb-md-0">
                                    <label class="font-weight-bold small text-uppercase" for="bmc_issuance_notes_type_{{ $borrowing->id }}">Type</label>
                                    <select class="form-control" id="bmc_issuance_notes_type_{{ $borrowing->id }}" data-bmc-issuance-notes-type required>
                                        <option value="" data-detail-placeholder="">-- Select Type --</option>
                                        <option value="Pick Up" data-detail-placeholder="e.g., HO, Plant, Warehouse">Pick Up</option>
                                        <option value="Field location" data-detail-placeholder="e.g., HO, Plant, Warehouse">Field location</option>
                                        <option value="Courier" data-detail-placeholder="e.g., SPX4321, Tracking No. 4123">Courier</option>
                                        <option value="Officer Name" data-detail-placeholder="e.g., Designation - Name">Thru Officer</option>
                                    </select>
                                </div>
                                <div class="col-md-7 form-group mb-0">
                                    <label class="font-weight-bold small text-uppercase" for="bmc_issuance_notes_detail_{{ $borrowing->id }}">Details</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="bmc_issuance_notes_detail_{{ $borrowing->id }}"
                                        data-bmc-issuance-notes-detail
                                        maxlength="2000"
                                        required
                                    >
                                </div>
                            </div>
                            <input type="hidden" name="release_notes" data-bmc-issuance-notes-value>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center px-3">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">
                        Cancel
                    </button>
                    @if ($requiresDeposit && !$borrowing->payment_method)
                        <button type="submit" formaction="{{ url('/prepare-bmc-release/' . $borrowing->id) }}" formnovalidate onclick="return prepareBmcPayment(this.form);" class="btn btn-primary px-4">
                            Set Items for Payment
                        </button>
                    @endif
                    <button type="submit" class="d-flex justify-content-center align-items-center btn btn-success px-4" style="gap: 5px;" @if ($requiresDeposit && !$borrowing->payment_method) disabled @endif>
                        <i class="ti-package mr-1"></i>
                        Release Items
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .bmc-release-stepper {
        display: grid;
        grid-template-columns: 38px 58px 38px;
        width: 134px;
        overflow: hidden;
        border: 1px solid #aeb8c4;
        border-radius: 9px;
        background: #fff;
    }

    .bmc-release-stepper-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 40px;
        padding: 0;
        border: 0;
        background: #f4f6f8;
        color: #374151;
        font-size: 18px;
        font-weight: 700;
        line-height: 1;
        cursor: pointer;
    }

    .bmc-release-stepper-button:hover:not(:disabled) {
        background: #e5e9ee;
    }

    .bmc-release-stepper-button:disabled {
        color: #a8afb8;
        cursor: not-allowed;
    }

    .bmc-release-stepper-value {
        width: 58px;
        min-width: 0;
        height: 40px;
        padding: 0 4px;
        border: 0;
        border-right: 1px solid #aeb8c4;
        border-left: 1px solid #aeb8c4;
        border-radius: 0;
        background: #fff;
        color: #1f2937;
        font-size: 16px;
        font-weight: 700;
        line-height: 40px;
        text-align: center;
        appearance: textfield;
    }

    .bmc-release-stepper-value::-webkit-inner-spin-button,
    .bmc-release-stepper-value::-webkit-outer-spin-button {
        margin: 0;
        -webkit-appearance: none;
    }

    .bmc-release-stepper-button:focus,
    .bmc-release-stepper-value:focus {
        position: relative;
        z-index: 1;
        outline: 2px solid #59c2e5;
        outline-offset: -2px;
    }
</style>

<script>
if (typeof window.formatBmcDeposit !== 'function') {
    window.formatBmcDeposit = function(amount) {
        return '₱' + Number(amount).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };
}

if (typeof window.syncBmcReleaseDepositTotal !== 'function') {
    window.syncBmcReleaseDepositTotal = function(form) {
        var $form = $(form);
        var total = 0;

        $form.find('[data-bmc-release-item]').each(function() {
            var $item = $(this);
            var rate = parseFloat($item.attr('data-deposit-rate')) || 0;
            var quantity =
                parseInt($item.find('[data-bmc-release-quantity]').val(), 10) || 0;

            total += rate * quantity;
        });

        $form.find('[data-bmc-deposit-total]')
            .text(window.formatBmcDeposit(total));
    };
}

if (typeof window.syncBmcReleaseItem !== 'function') {
    window.syncBmcReleaseItem = function(item) {
        var $item = $(item);
        var $quantityInput = $item.find('[data-bmc-release-quantity]');
        var minimum = parseInt($quantityInput.attr('min'), 10) || 0;
        var maximum = parseInt($quantityInput.attr('max'), 10) || 0;
        var quantity = parseInt($quantityInput.val(), 10);

        quantity = isNaN(quantity)
            ? minimum
            : Math.max(minimum, Math.min(maximum, quantity));

        $quantityInput.val(quantity);
        $item.find('[data-bmc-release-decrease]')
            .prop('disabled', quantity <= minimum);
        $item.find('[data-bmc-release-increase]')
            .prop('disabled', quantity >= maximum);

        var depositRate = parseFloat($item.attr('data-deposit-rate')) || 0;
        $item.find('[data-bmc-deposit-subtotal]')
            .text(window.formatBmcDeposit(depositRate * quantity));
        window.syncBmcReleaseDepositTotal($item.closest('form'));

        var $container = $item.find('[data-bmc-serial-inputs]');
        var savedValues = $container.find('[data-bmc-serial-input]').map(function() {
            return $(this).val();
        }).get();
        var savedRemarks = $container.find('[data-bmc-serial-remarks]').map(function() {
            return $(this).val();
        }).get();
        var inputName = $container.attr('data-input-name');
        var remarksName = $container.attr('data-remarks-name');
        var inputIdPrefix = $container.attr('data-input-id-prefix');
        var itemName = $container.attr('data-item-name');

        $container.empty();

        if (quantity === 0) {
            $('<small>', {
                class: 'text-muted',
                text: 'Set a release quantity to enter serial numbers.'
            }).attr('data-bmc-no-serials', '').appendTo($container);
            return;
        }

        for (var index = 0; index < quantity; index++) {
            var inputId = inputIdPrefix + '_' + index;
            var $row = $('<div>', {
                class: index + 1 < quantity ? 'mb-2' : ''
            }).attr('data-bmc-serial-row', '');

            $('<label>', {
                for: inputId,
                class: 'small text-muted d-block mb-1',
                text: 'Serial Number ' + (index + 1)
            }).appendTo($row);

            $('<input>', {
                type: 'text',
                id: inputId,
                name: inputName + '[' + index + ']',
                value: savedValues[index] || '',
                class: 'form-control',
                maxlength: 150,
                placeholder: 'Enter serial number',
                'aria-label': 'Serial number ' + (index + 1) + ' for ' + itemName,
                required: true
            }).attr('data-bmc-serial-input', '').appendTo($row);

            var remarksId = inputId + '_remarks';
            $('<label>', {
                for: remarksId,
                class: 'small text-muted d-block mt-2 mb-1',
                text: 'MBD Remarks'
            }).appendTo($row);

            $('<textarea>', {
                id: remarksId,
                name: remarksName + '[' + index + ']',
                class: 'form-control',
                rows: 2,
                maxlength: 2000,
                placeholder: 'e.g condition of the item',
            }).val(savedRemarks[index] || '')
                .attr('data-bmc-serial-remarks', '')
                .appendTo($row);

            $row.appendTo($container);
        }
    };
}

if (typeof window.prepareBmcPayment !== 'function') {
    window.prepareBmcPayment = function(form) {
        var inputs = Array.from(form.querySelectorAll('[data-bmc-release-quantity]'));
        var invalid = inputs.some(function(input) {
            var value = input.value.trim();
            var quantity = Number(value);
            return value === '' || !Number.isInteger(quantity) || quantity < 0 ||
                quantity > Math.min(Number(input.max), 99);
        });

        if (invalid) {
            Swal.fire('Check quantities', 'Enter a whole number within the remaining quantity for each item.', 'warning');
            return false;
        }

        if (!inputs.some(function(input) { return Number(input.value) > 0; })) {
            Swal.fire('No items selected', '', 'warning');
            return false;
        }

        form.dataset.preparing = '1';
        return true;
    };
}

if (typeof window.prepareBmcRelease !== 'function') {
    window.prepareBmcRelease = function(form) {
        var $form = $(form);
        var noteType = $form.find('[data-bmc-issuance-notes-type]').val();
        var noteDetail = $form.find('[data-bmc-issuance-notes-detail]').val().trim();
        var issuanceNotes = noteType
            ? (noteDetail ? noteType + ': ' + noteDetail : noteType)
            : noteDetail;
        var totalReleaseQuantity = 0;
        var invalidSerials = false;

        $form.find('[data-bmc-release-item]').each(function() {
            window.syncBmcReleaseItem(this);

            var $item = $(this);
            var quantity =
                parseInt($item.find('[data-bmc-release-quantity]').val(), 10) || 0;
            var serialNumbers = $item.find('[data-bmc-serial-input]').map(function() {
                return $(this).val().trim();
            }).get();

            totalReleaseQuantity += quantity;

            if (
                serialNumbers.length !== quantity ||
                serialNumbers.some(function(serialNumber) {
                    return serialNumber === '';
                })
            ) {
                invalidSerials = true;
            }
        });

        if (totalReleaseQuantity < 1) {
            Swal.fire(
                'No items selected',
                'Set at least one item quantity to release.',
                'warning'
            );
            return false;
        }

        if (invalidSerials) {
            Swal.fire(
                'Invalid serial numbers',
                'Enter one serial number for each released quantity.',
                'warning'
            );
            return false;
        }

        $form.find('[data-bmc-issuance-notes-value]').val(issuanceNotes);
        form.querySelector('button[type="submit"]').disabled = true;
        show();

        return true;
    };
}

$(function() {
    $('[data-bmc-release-item]').each(function() {
        window.syncBmcReleaseItem(this);
    });
});

$(document)
    .off('click.bmcReleaseDecrease', '[data-bmc-release-decrease]')
    .on('click.bmcReleaseDecrease', '[data-bmc-release-decrease]', function() {
        var $item = $(this).closest('[data-bmc-release-item]');
        var $input = $item.find('[data-bmc-release-quantity]');

        $input.val((parseInt($input.val(), 10) || 0) - 1);
        window.syncBmcReleaseItem($item);
    })
    .off('click.bmcReleaseIncrease', '[data-bmc-release-increase]')
    .on('click.bmcReleaseIncrease', '[data-bmc-release-increase]', function() {
        var $item = $(this).closest('[data-bmc-release-item]');
        var $input = $item.find('[data-bmc-release-quantity]');

        $input.val((parseInt($input.val(), 10) || 0) + 1);
        window.syncBmcReleaseItem($item);
    })
    .off('input.bmcReleaseQuantity change.bmcReleaseQuantity', '[data-bmc-release-quantity]')
    .on('input.bmcReleaseQuantity change.bmcReleaseQuantity', '[data-bmc-release-quantity]', function() {
        window.syncBmcReleaseItem($(this).closest('[data-bmc-release-item]'));
    })
    .off('change.bmcIssuanceNotes', '[data-bmc-issuance-notes-type]')
    .on('change.bmcIssuanceNotes', '[data-bmc-issuance-notes-type]', function() {
        var placeholder =
            $(this).find(':selected').data('detail-placeholder') || '';

        $(this)
            .closest('.row')
            .find('[data-bmc-issuance-notes-detail]')
            .attr('placeholder', placeholder);
    });
</script>
