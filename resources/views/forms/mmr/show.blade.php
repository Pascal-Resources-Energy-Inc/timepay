@extends('layouts.header')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
              <div>
                <h4 class="mb-0">{{ $release->first_name }} {{ $release->last_name }}</h4>
                <small class="text-muted font-weight-bold">{{ $release->reference_no }}</small>

                <!-- BADGE OF STATUS -->
                <!-- <div class="mt-1"> -->
                <!--   @if ($release->status === 'Approved') -->
                <!--   <span class="badge badge-success">Approved</span> -->
                <!--   @elseif ($release->status === 'Declined') -->
                <!--   <span class="badge badge-danger">Declined</span> -->
                <!--   @elseif ($release->status === 'Cancelled') -->
                <!--   <span class="badge badge-secondary">Cancelled</span> -->
                <!--   @else -->
                <!--   <span class="badge badge-warning">{{ $release->status }}</span> -->
                <!--   @endif -->
                <!-- </div> -->
              </div>

              <div class="d-flex align-items-center mt-2 mt-md-0">
                @if ($release->canBeEditedBy(auth()->id()))
                <a href="{{ route('marketing-material-releases.edit', $release->id) }}" class="btn btn-sm btn-warning mr-2" title="Available once within 24 hours">
                  <i class="ti-pencil mr-1"></i> Edit Request
                </a>
                @endif
                @if ($release->status === 'For Approval')
                <!-- <button type="button" class="btn btn-sm btn-outline-danger mr-2" id="cancelRelease"> -->
                <!--   <i class="ti-close mr-1"></i> Cancel Request -->
                <!-- </button> -->
                @endif
                <a href="{{ route('marketing-material-releases.index') }}" class="btn btn-sm btn-light border">
                  <i class="ti-arrow-left mr-1"></i> Back
                </a>
              </div>
            </div>

            <style>
              .mmr-section {
                border: 1px solid #e3e6ed;
                border-radius: .6rem;
                margin-bottom: 1.4rem;
                overflow: hidden;
              }
              .mmr-section-header {
                align-items: center;
                background: #f8f9fc;
                border-bottom: 1px solid #e3e6ed;
                display: flex;
                justify-content: space-between;
                padding: .9rem 1.2rem;
              }
              .mmr-section-header h5 {
                font-size: .95rem;
                font-weight: 600;
                margin: 0;
              }
              .mmr-section-header h5 i {
                color: #007bff;
              }
              .mmr-section-body {
                background: #fff;
                padding: 1.2rem;
              }
              .info-table td {
                border: none;
                padding: .45rem .5rem;
                vertical-align: top;
              }
              .info-table td:first-child {
                color: #6c757d;
                font-size: .82rem;
                font-weight: 600;
                width: 30%;
              }
              .info-table td:last-child {
                font-size: .88rem;
                word-break: break-word;
              }
              .preference-item {
                align-items: flex-start;
                border-bottom: 1px solid #edf0f5;
                display: flex;
                padding: .65rem 0;
              }
              .preference-item:last-child {
                border-bottom: 0;
              }
              .preference-icon {
                flex: 0 0 24px;
                margin-right: .6rem;
                padding-top: .1rem;
              }
              .signature-preview {
                background: #fff;
                border: 1px solid #dfe3e8;
                border-radius: .5rem;
                max-height: 220px;
                max-width: 100%;
                object-fit: contain;
                padding: .75rem;
              }
            </style>

            <div class="mmr-section">
              <div class="mmr-section-header">
                <h5><i class="ti-user mr-2"></i>I. Personal Information</h5>
              </div>
              <div class="mmr-section-body">
                <div class="table-responsive">
                  <table class="table table-sm info-table mb-0">
                    <tbody>
                      <tr><td>Reference No.</td><td>{{ $release->reference_no }}</td></tr>
                      <tr><td>Full Name</td><td>{{ $release->first_name }} {{ $release->last_name }}</td></tr>
                      <tr><td>Birth Date</td><td>{{ optional($release->birth_date)->format('F j, Y') }}</td></tr>
                      <tr><td>Gender</td><td>{{ $release->gender }}</td></tr>
                      <tr><td>Phone Number</td><td>{{ $release->phone_number }}</td></tr>
                      <tr><td>Email</td><td><a href="mailto:{{ $release->email }}">{{ $release->email }}</a></td></tr>
                      <!-- <tr> -->
                      <!--   <td>Address</td> -->
                      <!--   <td> -->
                      <!--     {{ $release->address_line }}, -->
                      <!--     {{ $release->address_city }}, -->
                      <!--     {{ $release->address_state_province }} -->
                      <!--     {{ $release->address_postal_code }} -->
                      <!--   </td> -->
                      <!-- </tr> -->
                      <tr><td>Date Submitted</td><td>{{ optional($release->submitted_at)->format('F j, Y, g:i a') }}</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="mmr-section">
              <div class="mmr-section-header">
                <h5><i class="ti-check-box mr-2"></i>II. Name-Usage Preferences</h5>
              </div>
              <div class="mmr-section-body py-2">
                @php
                  $namePreferences = [
                    'consent_complete_name' => 'Use of complete name',
                    'consent_first_name_only' => 'Use of first name only',
                    'consent_nickname' => 'Use of nickname',
                    'consent_anonymous_photos' => 'Use of photographs anonymously',
                  ];
                @endphp

                @foreach ($namePreferences as $field => $label)
                <div class="preference-item">
                  <span class="preference-icon text-{{ $release->{$field} ? 'success' : 'muted' }}">
                    <i class="{{ $release->{$field} ? 'ti-check-box' : 'ti-na' }}"></i>
                  </span>
                  <div>
                    <div>{{ $label }}</div>
                    <small class="text-{{ $release->{$field} ? 'success' : 'muted' }}">{{ $release->{$field} ? 'Selected' : 'Not selected' }}</small>
                  </div>
                </div>
                @endforeach
              </div>
            </div>

            <div class="mmr-section">
              <div class="mmr-section-header">
                <h5><i class="ti-shield mr-2"></i>III. Authorization and Release</h5>
              </div>
              <div class="mmr-section-body py-2">
                @php
                  $authorizations = [
                    'authorize_capture' => 'Authorized the taking of photographs, video, and narrative.',
                    'authorize_social_media' => 'Authorized use on social media and other marketing materials.',
                    'authorize_editing' => 'Authorized editing, alteration, copying, and distribution.',
                    'agree_company_ip_ownership' => 'Agreed that photo intellectual property rights belong to Pascal Resources Energy, Inc.',
                    'agree_no_compensation' => 'Agreed that no monetary compensation will be received.',
                  ];
                @endphp

                @foreach ($authorizations as $field => $label)
                <div class="preference-item">
                  <span class="preference-icon text-{{ $release->{$field} ? 'success' : 'muted' }}">
                    <i class="{{ $release->{$field} ? 'ti-check-box' : 'ti-na' }}"></i>
                  </span>
                  <div>
                    <div>{{ $label }}</div>
                    <small class="text-{{ $release->{$field} ? 'success' : 'muted' }}">{{ $release->{$field} ? 'Selected' : 'Not selected' }}</small>
                  </div>
                </div>
                @endforeach
              </div>
            </div>

            <div class="mmr-section">
              <div class="mmr-section-header">
                <h5><i class="ti-pencil-alt mr-2"></i>IV. Signing Information</h5>
              </div>
              <div class="mmr-section-body">
                <div class="row align-items-start">
                  <div class="col-md-8 mb-3 mb-md-0">
                    <div class="text-muted small font-weight-bold mb-2">SIGNATURE</div>
                    @if ($release->signature)
                    <img src="{{ asset($release->signature) }}" alt="Signature of {{ $release->first_name }} {{ $release->last_name }}" class="signature-preview">
                    @else
                    <span class="text-muted">No signature available.</span>
                    @endif
                  </div>
                  <div class="col-md-4">
                    <div class="text-muted small font-weight-bold mb-1">DATE SIGNED</div>
                    <div>
                      {{ optional($release->date_signed)->format('F j, Y') }}
                      <span>{{ optional($release->submitted_at)->format('g:i a') }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            @if ($release->approval_remarks || $release->approved_date)
            <div class="mmr-section">
              <div class="mmr-section-header">
                <h5><i class="ti-comment-alt mr-2"></i>V. Approval Information</h5>
              </div>
              <div class="mmr-section-body">
                <div class="table-responsive">
                  <table class="table table-sm info-table mb-0">
                    <tbody>
                      <tr><td>Status</td><td>{{ $release->status }}</td></tr>
                      <tr><td>Approval Date</td><td>{{ optional($release->approved_date)->format('F j, Y, g:i a') ?: '-' }}</td></tr>
                      <tr><td>Remarks</td><td>{{ $release->approval_remarks ?: '-' }}</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@if ($release->status === 'For Approval')
<form id="cancelForm" action="{{ route('marketing-material-releases.cancel', $release->id) }}" method="POST" style="display: none;">
  @csrf
</form>
@endif
@endsection

@if ($release->status === 'For Approval')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).on('click', '#cancelRelease', function () {
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
      $('#cancelForm').submit();
    }
  });
});
</script>
@endif
