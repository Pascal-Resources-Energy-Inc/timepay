<div class="modal fade" id="iur-release-modal-{{$form_approval->id}}" tabindex="-1" role="dialog" aria-labelledby="releaseIUR" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header border-bottom-0 pb-3">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-truck mr-1"></i> Release Items
                </h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <form method="POST" action="release-iur/{{$form_approval->id}}" onsubmit="return validateRelease(this, 'btnRelease');">
                @csrf
                <div class="modal-body pt-3">
                    @if(in_array($form_approval->request_for, ['Uniform', 'Both']))
                    <div class="card border shadow-xs mb-3">
                        <div class="card-header bg-light py-2 rounded-top">
                            <h6 class="mb-0 font-weight-bold">
                                <i class="fa fa-tags mr-1"></i> Uniform Items
                            </h6>
                        </div>
                        <div class="card-body pb-2">
                            <div class="row">
                                @php $prodComplete = $form_approval->received_prod_qty >= $form_approval->prod_qty; @endphp
                                @if($form_approval->prod_qty > 0)
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold small text-uppercase">Prod Shirt</label>
                                    <div class="d-flex align-items-center mb-1">
                                        @if($prodComplete)
                                        <span class="badge badge-success">Completed</span>
                                        @else
                                        <span class="badge badge-warning mr-2">Requested: {{ $form_approval->prod_qty }}</span>
                                        <span class="badge badge-info">Remaining: {{ $form_approval->prod_qty - $form_approval->received_prod_qty }}</span>
                                        @endif
                                    </div>
                                    <input type="number" name="released_prod_qty" class="form-control form-control-sm" min="0" max="{{ $form_approval->prod_qty - $form_approval->received_prod_qty }}" value="0" {{ $prodComplete ? 'disabled' : '' }}>

                                    <!-- uniform condition selection -->
                                    @if(!$prodComplete)
                                    <select name="prod_condition" class="form-control form-control-sm mt-1" id="prod_condition_{{ $form_approval->id }}">
                                        <option value="">-- Condition --</option>
                                        <option value="New">New</option>
                                        <option value="Used">Used</option>
                                    </select>
                                    @endif
                                </div>
                                @endif

                                @php $whiteComplete = $form_approval->received_white_qty >= $form_approval->white_qty; @endphp
                                @if($form_approval->white_qty > 0)
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold small text-uppercase">White</label>
                                    <div class="d-flex align-items-center mb-1">
                                        @if($whiteComplete)
                                        <span class="badge badge-success">Completed</span>
                                        @else
                                        <span class="badge badge-warning mr-2">Requested: {{ $form_approval->white_qty }}</span>
                                        <span class="badge badge-info">Remaining: {{ $form_approval->white_qty - $form_approval->received_white_qty }}</span>
                                        @endif
                                    </div>
                                    <input type="number" name="released_white_qty" class="form-control form-control-sm" min="0" max="{{ $form_approval->white_qty - $form_approval->received_white_qty }}" value="0" {{ $whiteComplete ? 'disabled' : '' }}>

                                    @if(!$whiteComplete)
                                    <select name="white_condition" class="form-control form-control-sm mt-1" id="white_condition_{{ $form_approval->id }}">
                                        <option value="">-- Condition --</option>
                                        <option value="New">New</option>
                                        <option value="Used">Used</option>
                                    </select>
                                    @endif
                                </div>
                                @endif

                                @php $blackComplete = $form_approval->received_black_qty >= $form_approval->black_qty; @endphp
                                @if($form_approval->black_qty > 0)
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold small text-uppercase">Black</label>
                                    <div class="d-flex align-items-center mb-1">
                                        @if($blackComplete)
                                        <span class="badge badge-success">Completed</span>
                                        @else
                                        <span class="badge badge-warning mr-2">Requested: {{ $form_approval->black_qty }}</span>
                                        <span class="badge badge-info">Remaining: {{ $form_approval->black_qty - $form_approval->received_black_qty }}</span>
                                        @endif
                                    </div>
                                    <input type="number" name="released_black_qty" class="form-control form-control-sm" min="0" max="{{ $form_approval->black_qty - $form_approval->received_black_qty }}" value="0" {{ $blackComplete ? 'disabled' : '' }}>
                                    @if(!$blackComplete)
                                    <select name="black_condition" class="form-control form-control-sm mt-1" id="black_condition_{{ $form_approval->id }}">
                                        <option value="">-- Condition --</option>
                                        <option value="New">New</option>
                                        <option value="Used">Used</option>
                                    </select>
                                    @endif
                                </div>
                                @endif

                                @php $poloComplete = $form_approval->received_collar_qty >= $form_approval->collar_qty; @endphp
                                @if($form_approval->collar_qty > 0)
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold small text-uppercase">Polo</label>
                                    <div class="d-flex align-items-center mb-1">
                                        @if($poloComplete)
                                        <span class="badge badge-success">Completed</span>
                                        @else
                                        <span class="badge badge-warning mr-2">Requested: {{ $form_approval->collar_qty }}</span>
                                        <span class="badge badge-info">Remaining: {{ $form_approval->collar_qty - $form_approval->received_collar_qty }}</span>
                                        @endif
                                    </div>
                                    <input type="number" name="released_collar_qty" class="form-control form-control-sm" min="0" max="{{ $form_approval->collar_qty - $form_approval->received_collar_qty }}" value="0" {{ $poloComplete ? 'disabled' : '' }}>
                                    @if(!$poloComplete)
                                    <select name="collar_condition" class="form-control form-control-sm mt-1" id="collar_condition_{{ $form_approval->id }}">
                                        <option value="">-- Condition --</option>
                                        <option value="New">New</option>
                                        <option value="Used">Used</option>
                                    </select>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(in_array($form_approval->request_for, ['ID', 'Both']) && optional(auth()->user()->employee)->location !== 'Lubao Office')
                    @php $idCompleted = $form_approval->accountabilities->sum('released_id') > 0; @endphp
                    <div class="card border shadow-xs mb-3">
                        <div class="card-header bg-light py-2 rounded-top">
                            <h6 class="mb-0 font-weight-bold">
                                <i class="fa fa-id-card mr-1"></i> Employee ID
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($idCompleted)
                            <div class="text-center py-3">
                                <!-- $signed ? '#d4edda' : '#f8d7da' -->
                                <span class="badge badge-success" style="font-size:14px; padding:8px 20px;">ID Already Released</span>
                            </div>
                            @else
                            <div class="row align-items-center">
                                <!-- front -->

                                <!-- back -->

                                <!-- confirm -->
                                <div class="col-md-12 mt-3">
                                    <div class="border rounded p-3" data-id-release-confirmation style="background:#f8fafc; border-color:#cbd5e1; transition:all .2s ease;">
                                        <div class="d-flex align-items-start justify-content-between mb-3">
                                            <div class="d-flex align-items-center font-weight-bold">
                                                To release
                                            </div>
                                            <span class="badge badge-warning" data-release-status style="font-size:11px; padding:6px 9px;">Awaiting confirmation</span>
                                        </div>

                                        <label for="released_id_{{ $form_approval->id }}" class="d-flex align-items-center border rounded p-3 mb-2 bg-white" data-release-option style="cursor:pointer; transition:all .2s ease;">
                                            <input type="checkbox" name="released_id" value="1" id="released_id_{{ $form_approval->id }}" class="mr-3" style="width:20px; height:20px; flex-shrink:0; cursor:pointer;">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                {{-- ID picture --}}
                                                <div
                                                    class="border d-flex align-items-center justify-content-center overflow-hidden bg-light"
                                                    style="width:60px; height:60px; flex-shrink:0;"
                                                >
                                                    @if ($form_approval->id_picture)
                                                        <img
                                                            src="{{ asset($form_approval->id_picture) }}"
                                                            alt="{{ $form_approval->user->first_name }} {{ $form_approval->user->last_name }}"
                                                            style="width:100%; height:100%; object-fit:cover;"
                                                        >
                                                    @else
                                                        <i class="fa fa-user text-muted" style="font-size:22px;"></i>
                                                    @endif
                                                </div>

                                                {{-- Employee details --}}
                                                <div class="ml-3" style="min-width:0;">
                                                    <strong
                                                        class="d-block text-truncate"
                                                        style="font-size:14px; color:#1f2937;"
                                                    >
                                                        {{ $form_approval->user->first_name }}
                                                        {{ $form_approval->user->last_name }}
                                                    </strong>

                                                    <small class="text-muted d-block text-truncate">
                                                        {{ $form_approval->user->position }}
                                                    </small>

                                                    <small class="text-muted d-block text-truncate">
                                                        {{ optional($form_approval->user->department)->name }}
                                                    </small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="card border shadow-xs">
                        <div class="card-header bg-light py-2 rounded-top">
                            <h6 class="mb-0 font-weight-bold">
                                <i class="fa fa-comment mr-1"></i> Issuance Notes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5 form-group mb-md-0">
                                    <label class="font-weight-bold small text-uppercase" for="issuance_notes_type_{{ $form_approval->id }}">Type</label>
                                    <select class="form-control" id="issuance_notes_type_{{ $form_approval->id }}" data-issuance-notes-type required>
                                        <option value="" data-detail-placeholder="">-- Select Type --</option>
                                        <option value="Face to face" data-detail-placeholder="e.g., HO, Plant, Warehouse">Pick Up</option>
                                        <option value="Field location" data-detail-placeholder="e.g., Field location">Field location</option>
                                        <option value="Courier" data-detail-placeholder="e.g., SPX4321, Tracking No. 4123">Courier</option>
                                        <option value="Officer Name" data-detail-placeholder="e.g., Designation - Name">Thru Officer</option>
                                    </select>
                                </div>
                                <div class="col-md-7 form-group mb-0">
                                    <label class="font-weight-bold small text-uppercase" for="issuance_notes_detail_{{ $form_approval->id }}">Details</label>
                                    <input type="text" class="form-control" id="issuance_notes_detail_{{ $form_approval->id }}" data-issuance-notes-detail required>
                                </div>
                            </div>
                            <input type="hidden" name="notes" data-issuance-notes-value>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="btnRelease" class="btn btn-info px-4">
                        Release Items
                    </button>

                    @if(in_array($form_approval->request_for, ['ID', 'Both']) && optional(auth()->user()->employee)->location !== 'Lubao Office')
                    <a href="{{ url('/batch-print-form') . '?' . http_build_query(['refs' => [$form_approval->iur_reference]]) }}"
                       target="_blank"
                       class="btn btn-primary px-4"
                       style="display:flex; gap:5px; align-items:center;">
                        <span>Print ID</span>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
if (typeof window.validateRelease !== 'function') {
    window.validateRelease = function(form, btnName) {
        var $form = $(form);
        var noteType = $form.find('[data-issuance-notes-type]').val();
        var noteDetail = $form.find('[data-issuance-notes-detail]').val().trim();
        var issuanceNotes = noteType ? (noteDetail ? noteType + ': ' + noteDetail : noteType) : noteDetail;
        $form.find('[data-issuance-notes-value]').val(issuanceNotes);

        var hasRelease = false;
        $form.find('input[type="number"]').each(function() {
            if (!$(this).prop('disabled') && parseInt($(this).val()) > 0) {
                hasRelease = true;
                return false;
            }
        });
        if (!hasRelease) {
            hasRelease = $form.find('input[name="released_id"]:checked').length > 0;
        }
        if (!hasRelease) {
            Swal.fire('Nothing to release', 'Enter a quantity or check ID release before submitting.', 'warning');
            return false;
        }
        document.getElementById(btnName).disabled = true;
        return true;
    };
}

$(document)
    .off('change.issuanceNotes', '[data-issuance-notes-type]')
    .on('change.issuanceNotes', '[data-issuance-notes-type]', function() {
        var placeholder = $(this).find(':selected').data('detail-placeholder');
        $(this).closest('.row').find('[data-issuance-notes-detail]').attr('placeholder', placeholder);
    });

$(document).on('input change', 'input[name^="released_"]', function() {
    var $group = $(this).closest('.form-group');
    var $select = $group.find('select');
    if (!$select.length) return;

    if (parseInt($(this).val()) > 0) {
        $select.attr('required', true);
        $select.addClass('border-primary');
    } else {
        $select.removeAttr('required').val('').removeClass('border-primary border-danger');
    }
});

$(document)
    .off('change.idReleaseConfirmation', 'input[name="released_id"]')
    .on('change.idReleaseConfirmation', 'input[name="released_id"]', function() {
        var $confirmation = $(this).closest('[data-id-release-confirmation]');
        var $option = $confirmation.find('[data-release-option]');
        var $status = $confirmation.find('[data-release-status]');
        var isConfirmed = $(this).is(':checked');

        $confirmation.css({
            background: isConfirmed ? '#f0fdf4' : '#f8fafc',
            borderColor: isConfirmed ? '#28a745' : '#cbd5e1'
        });
        $option.toggleClass('border-success', isConfirmed);
        $status
            .toggleClass('badge-warning', !isConfirmed)
            .toggleClass('badge-success', isConfirmed)
            .text(isConfirmed ? 'Ready to release' : 'Awaiting confirmation');
    });
</script>
