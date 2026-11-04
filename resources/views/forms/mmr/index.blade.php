@extends('layouts.header')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="modal fade" id="sendMarketingMaterialReleaseLinkModal" tabindex="-1" role="dialog" aria-labelledby="sendMarketingMaterialReleaseLinkModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <form method="post" action="{{ route('marketing-material-releases.invitation.send') }}" onsubmit="show()">
          @csrf
          <div class="modal-content border-0">
            <div class="modal-header">
              <div>
                <h5 class="modal-title mb-1" id="sendMarketingMaterialReleaseLinkModalTitle">Send Link</h5>
              </div>
              <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <div class="form-group mb-0">
                <label for="marketingMaterialReleaseInvitationEmail">Email Address <span class="text-danger">*</span></label>
                <input
                  type="email"
                  name="recipient_email"
                  id="marketingMaterialReleaseInvitationEmail"
                  class="form-control"
                  maxlength="255"
                  value="{{ old('recipient_email') }}"
                  placeholder="example@example.com"
                  required
                >
                @if ($errors->has('recipient_email'))
                  <small class="text-danger">{{ $errors->first('recipient_email') }}</small>
                @endif
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-light border" data-dismiss="modal">Cancel</button>
              <button type="submit" class="d-flex align-items-center btn btn-sm btn-info">
                <i class="ti-email mr-2"></i> Send Form Link
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade" id="marketingMaterialReleaseQrCodeModal" tabindex="-1" role="dialog" aria-labelledby="marketingMaterialReleaseQrCodeModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
          <div class="modal-header">
            <div>
              <h5 class="modal-title mb-1" id="marketingMaterialReleaseQrCodeModalTitle">QR Code</h5>
              <small class="text-white">Scan the code or share the public form link.</small>
            </div>
            <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body text-center">
            <div class="d-inline-flex align-items-center justify-content-center bg-white border rounded p-3 mb-3">
              {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(240)->margin(1)->generate(route('marketing-material-releases.public.create')) !!}
            </div>

            <div class="input-group">
              <input
                type="text"
                id="marketingMaterialReleasePublicLink"
                class="form-control"
                value="{{ route('marketing-material-releases.public.create') }}"
                readonly
              >
              <div class="input-group-append">
                <button type="button" id="copyMarketingMaterialReleaseLink" class="btn btn-outline-primary">
                  <i class="ti-files mr-1"></i> <span id="copyMarketingMaterialReleaseLinkLabel" aria-live="polite">Copy</span>
                </button>
              </div>
            </div>
          </div>

          <!-- <div class="modal-footer"> -->
          <!--   <button type="button" class="btn btn-sm btn-light border" data-dismiss="modal">Close</button> -->
          <!--   <button type="button" id="shareMarketingMaterialReleaseLink" class="d-flex align-items-center btn btn-sm btn-primary"> -->
          <!--     <i class="ti-share mr-2"></i> Share Link -->
          <!--   </button> -->
          <!-- </div> -->
        </div>
      </div>
    </div>

    <div class="row grid-margin">
      <div class="col-lg-3 mb-1">
        <div class="card card-tale">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <h4 class="mb-4">Total Responses</h4>
                <a href="{{ route('marketing-material-releases.index', ['status' => 'For Approval']) }}" class="h2 card-text text-white">{{ $forApprovalCount }}</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- <div class="col-lg-3 mb-1"> -->
      <!--   <div class="card card-light-blue"> -->
      <!--     <div class="card-body"> -->
      <!--       <div class="media"> -->
      <!--         <div class="media-body"> -->
      <!--           <h4 class="mb-4">Approved</h4> -->
      <!--           <a href="{{ route('marketing-material-releases.index', ['status' => 'Approved']) }}" class="h2 card-text text-white">{{ $approvedCount }}</a> -->
      <!--         </div> -->
      <!--       </div> -->
      <!--     </div> -->
      <!--   </div> -->
      <!-- </div> -->

      <!-- <div class="col-lg-3 mb-1"> -->
      <!--   <div class="card card-light-danger"> -->
      <!--     <div class="card-body"> -->
      <!--       <div class="media"> -->
      <!--         <div class="media-body"> -->
      <!--           <h4 class="mb-4">Declined</h4> -->
      <!--           <a href="{{ route('marketing-material-releases.index', ['status' => 'Declined']) }}" class="h2 card-text text-white">{{ $declinedCount }}</a> -->
      <!--         </div> -->
      <!--       </div> -->
      <!--     </div> -->
      <!--   </div> -->
      <!-- </div> -->

      <!-- <div class="col-lg-3 mb-1"> -->
      <!--   <div class="card card-dark-blue"> -->
      <!--     <div class="card-body"> -->
      <!--       <div class="media"> -->
      <!--         <div class="media-body"> -->
      <!--           <h4 class="mb-4">Cancelled</h4> -->
      <!--           <a href="{{ route('marketing-material-releases.index', ['status' => 'Cancelled']) }}" class="h2 card-text text-white">{{ $cancelledCount }}</a> -->
      <!--         </div> -->
      <!--       </div> -->
      <!--     </div> -->
      <!--   </div> -->
      <!-- </div> -->
    </div>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <div>
                <h1 style="font-size: 1.4rem; font-weight: 600; font-family: Arial, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">
                  Marketing Material Releases
                </h1>
                <small class="text-muted">{{ $totalReleases }} total record{{ $totalReleases === 1 ? '' : 's' }}</small>
              </div>

              <div class="d-flex" style="gap: 5px;">
                  @if ($canViewAllReleases)
                  <button type="button" class="d-flex align-items-center btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#marketingMaterialReleaseQrCodeModal">
                    <i class="fa fa-qrcode" aria-hidden="true"></i>
                  </button>

                  <button type="button" class="d-flex align-items-center btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#sendMarketingMaterialReleaseLinkModal">
                    <i class="ti-email mr-1"></i>
                    <span>Send Form Link</span>
                  </button>
                  @endif

                  <a href="{{ route('marketing-material-releases.create') }}" class="d-flex align-items-center btn btn-outline-info btn-sm">
                    <i class="ti-plus mr-1"></i>
                    <span>Fill Out Form</span>
                  </a>
              </div>
            </div>

            <form method="GET" action="{{ route('marketing-material-releases.index') }}">
              <div class="row">
                <div class="col-md-4">
                  <div class="d-flex form-group flex-column">
                    <label class="mb-1">Search</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search ref no., name, email, phone...">
                  </div>
                </div>

                <!-- <div class="col-md-2"> -->
                <!--   <div class="form-group"> -->
                <!--     <label class="mb-1">Status</label> -->
                <!--     <select name="status" class="form-control form-control-sm"> -->
                <!--       <option value="">All</option> -->
                <!--       <option value="For Approval" {{ $status === 'For Approval' ? 'selected' : '' }}>For Approval</option> -->
                <!--       <option value="Approved" {{ $status === 'Approved' ? 'selected' : '' }}>Approved</option> -->
                <!--       <option value="Declined" {{ $status === 'Declined' ? 'selected' : '' }}>Declined</option> -->
                <!--       <option value="Cancelled" {{ $status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option> -->
                <!--     </select> -->
                <!--   </div> -->
                <!-- </div> -->

                <div class="col-md-2">
                  <div class="form-group">
                    <label class="mb-1">From</label>
                    <input type="date" value="{{ $from }}" class="form-control form-control-sm" name="from">
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label class="mb-1">To</label>
                    <input type="date" value="{{ $to }}" class="form-control form-control-sm" name="to">
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label class="invisible mb-1">Filter</label>
                    <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Filter</button>
                  </div>
                </div>
              </div>
            </form>

            <div class="table-responsive">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th style="background-color: #e6e6e6; color: #666;">Action</th>
                    <th style="background-color: #e6e6e6; color: #666;">Ref No.</th>
                    <th style="background-color: #e6e6e6; color: #666;">Submission Date</th>
                    <th style="background-color: #e6e6e6; color: #666;">Name</th>
                    <th style="background-color: #e6e6e6; color: #666;">Contact</th>
                    <th style="background-color: #e6e6e6; color: #666;">Date Signed</th>
                    <!-- <th style="background-color: #e6e6e6; color: #666;">Status</th> -->
                  </tr>
                </thead>
                <tbody>
                  @forelse ($releases as $release)
                  @php
                    $isInactiveSubmitter = optional(optional($release->user)->employee)->status === 'Inactive';
                  @endphp
                  <tr class="align-middle">
                    <td class="text-center" style="white-space: nowrap;">
                      <a href="{{ route('marketing-material-releases.show', $release->id) }}" class="btn btn-info btn-rounded btn-icon d-inline-flex align-items-center justify-content-center" title="View">
                        <i class="ti-eye"></i>
                      </a>
                      @if ($release->canBeEditedBy(auth()->id()))
                      <a href="{{ route('marketing-material-releases.edit', $release->id) }}" class="btn btn-warning btn-rounded btn-icon d-inline-flex align-items-center justify-content-center" title="Edit (available once within 24 hours)" aria-label="Edit request">
                        <i class="ti-pencil"></i>
                      </a>
                      @endif

                      <!-- @if ($release->status === 'For Approval') -->
                      <!-- <button type="button" class="btn btn-danger btn-rounded btn-icon btn-cancel" data-id="{{ $release->id }}" title="Cancel"> -->
                      <!--   <i class="ti-close btn-icon-prepend"></i> -->
                      <!-- </button> -->
                      <!-- @endif -->
                    </td>
                    <td style="white-space: nowrap;">
                      <span class="badge badge-primary badge-pill">{{ $release->reference_no }}</span>
                    </td>
                    <td style="white-space: nowrap;">
                      {{ optional($release->submitted_at)->format('F j, Y') }}<br>
                      <small class="text-muted">{{ optional($release->submitted_at)->format('g:i a') }}</small>
                    </td>
                    <td style="white-space: nowrap;{{ $isInactiveSubmitter ? ' background-color: #fde8e8;' : '' }}">
                      <div>
                        <div>{{ $release->first_name }} {{ $release->last_name }}</div>
                        @if ($isInactiveSubmitter)
                          <small class="d-block font-weight-bold text-danger">Inactive</small>
                        @endif
                      </div>
                      @if ($release->user_id === null) 
                        <small class="text-danger">From public submission</small>
                      @endif
                    </td>
                    <td>
                      <a href="mailto:{{ $release->email }}">{{ $release->email }}</a><br>
                      <small class="text-muted">{{ $release->phone_number }}</small>
                    </td>
                    <td style="white-space: nowrap;">
                      {{ optional($release->date_signed)->format('F j, Y') }}<br>
                      <small>{{ optional($release->submitted_at)->format('g:i a') }}</small>
                    </td>
                    <!-- <td> -->
                    <!--   @if ($release->status === 'Approved') -->
                    <!--   <span class="badge badge-success">Approved</span> -->
                    <!--   @elseif ($release->status === 'Declined') -->
                    <!--   <span class="badge badge-danger">Declined</span> -->
                    <!--   @elseif ($release->status === 'Cancelled') -->
                    <!--   <span class="badge badge-secondary">Cancelled</span> -->
                    <!--   @else -->
                    <!--   <span class="badge badge-warning">{{ $release->status }}</span> -->
                    <!--   @endif -->
                    <!-- </td> -->
                  </tr>
                  @empty
                  <tr>
                    <td colspan="7" class="text-center text-muted py-4">No marketing material releases found.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <div class="mt-3">{{ $releases->appends(request()->query())->links() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<form id="cancelForm" method="POST" style="display: none;">
  @csrf
</form>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).on('click', '.btn-cancel', function () {
  var id = $(this).data('id');
  var url = "{{ route('marketing-material-releases.cancel', ':id') }}".replace(':id', id);

  Swal.fire({
    title: 'Are you sure?',
    text: 'Do you want to cancel this release?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, cancel it',
    cancelButtonText: 'No'
  }).then(function (result) {
    if (result.isConfirmed) {
      $('#cancelForm').attr('action', url).submit();
    }
  });
});

function copyMarketingMaterialReleaseLink() {
  var linkInput = document.getElementById('marketingMaterialReleasePublicLink');
  var copyButtonLabel = document.getElementById('copyMarketingMaterialReleaseLinkLabel');
  var link = linkInput.value;

  function showCopiedState() {
    copyButtonLabel.textContent = 'Copied';
  }

  function copyWithInputSelection() {
    linkInput.select();
    linkInput.setSelectionRange(0, link.length);
    var copied = document.execCommand('copy');
    window.getSelection().removeAllRanges();

    if (copied) {
      showCopiedState();
    }
  }

  if (navigator.clipboard && window.isSecureContext) {
    return navigator.clipboard.writeText(link)
      .then(showCopiedState)
      .catch(copyWithInputSelection);
  }

  copyWithInputSelection();

  return Promise.resolve();
}

$(document).on('click', '#copyMarketingMaterialReleaseLink', function () {
  copyMarketingMaterialReleaseLink();
});

function showMarketingMaterialReleaseShareUnavailable() {
  Swal.fire({
    title: 'Sharing unavailable',
    text: 'Native sharing is not available in this browser or connection. Use the Copy button instead, or open the app over HTTPS.',
    icon: 'info',
    confirmButtonText: 'OK'
  });
}

$(document).on('click', '#shareMarketingMaterialReleaseLink', function () {
  var link = document.getElementById('marketingMaterialReleasePublicLink').value;

  if (!navigator.share) {
    showMarketingMaterialReleaseShareUnavailable();
    return;
  }

  navigator.share({
    title: 'Marketing Material Release Form',
    text: 'Please complete the Marketing Material Release Form.',
    url: link
  }).catch(function (error) {
    if (error.name !== 'AbortError') {
      showMarketingMaterialReleaseShareUnavailable();
    }
  });
});

$(document).on('hidden.bs.modal', '#marketingMaterialReleaseQrCodeModal', function () {
  document.getElementById('copyMarketingMaterialReleaseLinkLabel').textContent = 'Copy';
});

@if ($errors->has('recipient_email'))
$(function () {
  $('#sendMarketingMaterialReleaseLinkModal').modal('show');
});
@endif
</script>
