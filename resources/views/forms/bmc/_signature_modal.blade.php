<div
    class="modal fade"
    id="bmcSignature{{ $accountability->id }}"
    tabindex="-1"
    role="dialog"
    aria-labelledby="bmcSignatureTitle{{ $accountability->id }}"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header border-bottom-0 pb-2">
                <div>
                    <h5 class="modal-title font-weight-bold" id="bmcSignatureTitle{{ $accountability->id }}">
                        Sign Accountability
                    </h5>
                    <small class="text-white">{{ $accountability->accountability_ref }}</small>
                </div>

                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>

            <div class="modal-body pt-3">
                <div class="bg-light rounded p-3 mb-3">
                    <div class="d-flex py-1">
                        <span class="text-muted font-weight-bold" style="width: 120px; flex: 0 0 120px; font-size: .78rem;">Borrower</span>
                        <span class="font-weight-bold" style="font-size: .85rem;">
                            {{ $accountability->borrowing->borrower_first_name }}
                            {{ $accountability->borrowing->borrower_last_name }}
                        </span>
                    </div>
                    <div class="d-flex py-1">
                        <span class="text-muted font-weight-bold" style="width: 120px; flex: 0 0 120px; font-size: .78rem;">Released Items</span>
                        <span class="d-flex flex-wrap align-items-center" style="gap: 4px;">
                            @foreach ($accountability->items as $item)
                                <span class="badge badge-info" style="font-size: .72rem; white-space: normal; line-height: 1.35;">
                                    {{ $item->item_name }} &times; {{ $item->released_quantity ?: 1 }}
                                </span>
                            @endforeach
                        </span>
                    </div>
                </div>

                @php
                    $serialCount = $accountability->items->sum(function ($item) {
                        return $item->serials->count();
                    });
                @endphp

                @if ($serialCount > 0)
                    <section class="mb-3" aria-labelledby="bmcRemarksTitle{{ $accountability->id }}">
                        <p class="text-muted small mb-2">Note any damage or missing parts you noticed at handover.</p>
                        @foreach ($accountability->items as $item)
                            @foreach ($item->serials as $serial)
                                <details class="border rounded px-3 py-2 mb-2" @if ($serialCount === 1 || filled($serial->borrower_remarks)) open @endif>
                                    <summary class="text-break">Item: {{ $item->item_name }} · {{ $serial->serial_number }}</summary>
                                    <label class="sr-only" for="bmcBorrowerRemark{{ $serial->id }}">
                                        Remark for {{ $item->item_name }}, serial {{ $serial->serial_number }}
                                    </label>
                                    <textarea
                                        id="bmcBorrowerRemark{{ $serial->id }}"
                                        class="form-control mt-2"
                                        rows="2"
                                        maxlength="2000"
                                        placeholder="Add a remark…"
                                        data-bmc-borrower-remark="{{ $serial->id }}"
                                    >{{ $serial->borrower_remarks ?? '' }}</textarea>
                                </details>
                            @endforeach
                        @endforeach
                    </section>
                @endif

                @if ($accountability->borrower_signature)
                    <div class="text-center mb-3">
                        <p class="text-muted small mb-2">Current signature</p>
                        <img
                            src="{{ asset($accountability->borrower_signature) }}"
                            alt="Current borrower signature"
                            style="max-width: 280px; max-height: 110px; border: 1px solid #dee2e6; padding: 8px; background: #fff;"
                        >
                        <p class="text-muted small mt-2 mb-0">Drawing a new signature will replace it.</p>
                    </div>
                @endif

                <div class="text-center">
                    <canvas
                        id="bmcSignatureCanvas{{ $accountability->id }}"
                        width="450"
                        height="200"
                        style="width: 100%; max-width: 450px; height: 200px; border: 2px dashed #cbd5e1; border-radius: 8px; background: #fff; cursor: crosshair; touch-action: none;"
                    ></canvas>
                    <p class="text-muted small mt-2 mb-0">Draw your signature using a mouse or finger.</p>
                </div>
            </div>

            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light border" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-secondary text-white" id="clearBmcSignature{{ $accountability->id }}">
                    Clear
                </button>
                <button type="button" class="btn btn-success" id="saveBmcSignature{{ $accountability->id }}">
                    Save Signature
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        var modal = $('#bmcSignature{{ $accountability->id }}');
        var canvas = document.getElementById('bmcSignatureCanvas{{ $accountability->id }}');
        var signaturePad = null;

        modal.on('shown.bs.modal', function () {
            if (!signaturePad) {
                signaturePad = new SignaturePad(canvas);
            }

            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            var rect = canvas.getBoundingClientRect();

            canvas.width = rect.width * ratio;
            canvas.height = 200 * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        });

        $('#clearBmcSignature{{ $accountability->id }}').on('click', function () {
            if (signaturePad) {
                signaturePad.clear();
            }
        });

        $('#saveBmcSignature{{ $accountability->id }}').on('click', function () {
            if (!signaturePad || signaturePad.isEmpty()) {
                Swal.fire('Signature required', 'Please provide your signature before saving.', 'warning');
                return;
            }

            var saveButton = $(this);
            var borrowerRemarks = {};
            modal.find('[data-bmc-borrower-remark]').each(function () {
                borrowerRemarks[this.dataset.bmcBorrowerRemark] = this.value;
            });
            saveButton.prop('disabled', true);

            $.ajax({
                url: '{{ url('/bmc-accountability-signature/' . $accountability->id) }}',
                type: 'POST',
                data: {
                    signature: signaturePad.toDataURL('image/png'),
                    borrower_remarks: borrowerRemarks,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire(
                        'Signed',
                        response.message || 'Accountability signed successfully.',
                        'success'
                    ).then(function () {
                        window.location.reload();
                    });
                },
                error: function (xhr) {
                    var message = 'The signature could not be saved.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }

                    Swal.fire('Error', message, 'error');
                    saveButton.prop('disabled', false);
                }
            });
        });
    });
</script>
