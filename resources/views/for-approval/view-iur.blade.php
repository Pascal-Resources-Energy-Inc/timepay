<div class="modal fade" id="view-modal-{{ $form_approval->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">IUR Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                {{-- BASIC INFO --}}
                <h5>Request Information</h5>
                <hr>

                <div class="row">
                    <div class="col-md-6"><b>Reference:</b> {{ $form_approval->iur_reference }}</div>
                    <div class="col-md-6"><b>Status:</b> {{ $form_approval->status }}</div>
                    <div class="col-md-6"><b>Type:</b> {{ $form_approval->type }}</div>
                    <div class="col-md-6"><b>Request For:</b> {{ $form_approval->request_for }}</div>
                    <div class="col-md-6"><b>Work Location:</b> {{ $form_approval->work_location }}</div>
                    <div class="col-md-6"><b>Date:</b> {{ date('M d, Y', strtotime($form_approval->created_at)) }}</div>
                    <div class="col-md-12 mt-2"><b>Details:</b> {{ $form_approval->details }}</div>
                </div>

                {{-- UNIFORM --}}
                @if($form_approval->request_for == 'Uniform' || $form_approval->request_for == 'Both')
                <h5 class="mt-4">Uniform Details</h5>
                <hr>

                <div class="row">
                    <div class="col-md-6"><b>Issued Before:</b> {{ $form_approval->issued }}</div>
                    <div class="col-md-6"><b>Size:</b> {{ $form_approval->size }}</div>

                    @if($form_approval->issued == 'Yes')
                        <div class="col-md-6"><b>Issued Remarks:</b> {{ $form_approval->issued_remarks ?? '-' }}</div>
                    @endif

                    <div class="col-md-6"><b>Reason:</b> {{ $form_approval->issued_reasons ?? '-' }}</div>
                    <div class="col-md-12"><b>Notes:</b> {{ $form_approval->notes ?? '-' }}</div>
                </div>
                @endif

                {{-- ID --}}
                @if($form_approval->request_for == 'ID' || $form_approval->request_for == 'Both')
                <h5 class="mt-4">ID Details</h5>
                <hr>

                <div class="row">
                    <div class="col-md-6"><b>Reason:</b> {{ $form_approval->id_request }}</div>

                    <div class="col-md-6">
                        <b>ID Picture:</b><br>

                        @if($form_approval->id_picture)
                            <img src="{{ asset($form_approval->id_picture) }}" 
                                style="max-width:200px; border:1px solid #ddd; padding:5px; display:block; margin-bottom:10px;">

                            <!-- DOWNLOAD BUTTON -->
                            <a href="{{ asset($form_approval->id_picture) }}" 
                            download 
                            class="btn btn-primary btn-sm">
                                <i class="ti-download"></i> Download
                            </a>
                        @else
                            <span>No Image</span>
                        @endif
                    </div>
                </div>
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>