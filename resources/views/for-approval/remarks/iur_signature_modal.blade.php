<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<div class="modal fade" id="iur-signature-modal-{{ $form_approval->id }}" tabindex="-1" role="dialog" aria-labelledby="signatureModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">Employee Signature</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="acc-details-container-{{ $form_approval->id }}">
                    @if(in_array($form_approval->request_for, ['Uniform', 'Uniform/Partial', 'Both']) && $form_approval->accountabilities->count() > 0)
                    <div class="card bg-light mb-3 text-left">
                        <div class="card-body py-2 px-3">
                            <p class="mb-1 font-weight-bold">Items released to you:</p>
                            @foreach($form_approval->accountabilities as $acc)
                            <div class="small mb-1">
                                <span class="text-muted">{{ $acc->accountability_ref }} ({{ date('M d, Y', strtotime($acc->issued_date)) }}):</span>
                                @php $items = []; @endphp
                                @if($acc->released_prod_qty > 0) @php $items[] = $acc->released_prod_qty.' Prod'; @endphp @endif
                                @if($acc->released_white_qty > 0) @php $items[] = $acc->released_white_qty.' White'; @endphp @endif
                                @if($acc->released_black_qty > 0) @php $items[] = $acc->released_black_qty.' Black'; @endphp @endif
                                @if($acc->released_collar_qty > 0) @php $items[] = $acc->released_collar_qty.' Polo'; @endphp @endif
                                @if($acc->released_id) @php $items[] = 'ID'; @endphp @endif
                                <strong>{{ implode(', ', $items) }}</strong>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @elseif(in_array($form_approval->request_for, ['Uniform', 'Uniform/Partial', 'Both']))
                    <div class="card bg-light mb-3 text-left">
                        <div class="card-body py-2 px-3">
                            <p class="mb-0 small">
                                <span class="text-muted">Requested uniforms:</span>
                                <strong>
                                    @if($form_approval->prod_qty > 0){{ $form_approval->prod_qty }} Prod @endif
                                    @if($form_approval->white_qty > 0){{ $form_approval->white_qty }} White @endif
                                    @if($form_approval->black_qty > 0){{ $form_approval->black_qty }} Black @endif
                                    @if($form_approval->collar_qty > 0){{ $form_approval->collar_qty }} Polo @endif
                                </strong>
                            </p>
                        </div>
                    </div>
                    @endif

                    @if(in_array($form_approval->request_for, ['ID', 'Both']))
                    <div class="card bg-light mb-3 text-left">
                        <div class="card-body py-2 px-3">
                            <p class="mb-0 small">
                                <span class="text-muted">ID:</span>
                                <strong>{{ $form_approval->accountabilities->sum('released_id') > 0 ? 'ID released' : 'ID not yet released' }}</strong>
                            </p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="text-center">
                    @if($form_approval->e_signature)
                    <div id="existing-signature-{{ $form_approval->id }}" class="mb-3">
                        <p class="text-muted">Current signature:</p>
                        <img src="{{ asset($form_approval->e_signature) }}" style="max-width:300px; border:1px solid #ddd; padding:10px; background:#fff;">
                        <hr>
                        <p class="text-muted">Draw new signature below to replace:</p>
                    </div>
                    @endif
                    <canvas id="signature-canvas-{{ $form_approval->id }}"
                        style="border:2px dashed #ccc; border-radius:8px; width:100%; max-width:450px; height:200px; cursor:crosshair; touch-action:none;"
                        width="450" height="200"></canvas>
                    <p class="text-muted mt-2 small">Sign using your mouse or finger above</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-white" id="clear-signature-{{ $form_approval->id }}">
                    Clear
                </button>
                <button type="button" class="btn btn-success" id="save-signature-{{ $form_approval->id }}">Upload</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Reload page on modal close so signed button disappears
        $('#iur-signature-modal-{{ $form_approval->id }}').on('hidden.bs.modal', function() {
            location.reload();
        });

        const canvas = document.getElementById('signature-canvas-{{ $form_approval->id }}');
        if (!canvas) return;

        const signaturePad = new SignaturePad(canvas);

        // Resize canvas properly
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width * ratio;
            canvas.height = rect.height * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }

        // On show, populate acc details from triggering button's data attributes
        $('#iur-signature-modal-{{ $form_approval->id }}').on('show.bs.modal', function(e) {
            var btn = $(e.relatedTarget);
            var accId = btn.data('acc-id');
            $('#iur-signature-modal-{{ $form_approval->id }}').data('currentAccId', accId || '');
            var ref = btn.data('acc-ref');
            if (ref) {
                var date = btn.data('acc-date');
                var items = btn.data('acc-items');
                var html = '<div class="card bg-light mb-3 text-left"><div class="card-body py-2 px-3">';
                html += '<p class="mb-1 font-weight-bold">Items released to you:</p>';
                html += '<div class="small mb-1"><span class="text-muted">' + ref + ' (' + date + '):</span> <strong>' + items + '</strong></div>';
                html += '</div></div>';
                $('#acc-details-container-{{ $form_approval->id }}').html(html);
            }
        });

        // Only resize on modal show to avoid clearing existing drawing
        $('#iur-signature-modal-{{ $form_approval->id }}').on('shown.bs.modal', function() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width * ratio;
            canvas.height = rect.height * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        });

        document.getElementById('clear-signature-{{ $form_approval->id }}').addEventListener('click', function() {
            signaturePad.clear();
        });

        document.getElementById('save-signature-{{ $form_approval->id }}').addEventListener('click', function() {
            if (signaturePad.isEmpty()) {
                Swal.fire('Empty', 'Please draw your signature before saving.', 'warning');
                return;
            }

            const dataUrl = signaturePad.toDataURL('image/png');

            var accId = $('#iur-signature-modal-{{ $form_approval->id }}').data('currentAccId');
            $.ajax({
                url: '/save-iur-signature/{{ $form_approval->id }}',
                type: 'POST',
                data: {
                    signature: dataUrl,
                    accountability_id: accId || '',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire('Saved!', 'Signature saved successfully.', 'success');
                    $('#iur-signature-modal-{{ $form_approval->id }}').modal('hide');
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseText || 'Failed to save signature.', 'error');
                }
            });
        });
    });
</script>