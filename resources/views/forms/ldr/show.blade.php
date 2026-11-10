@extends('layouts.header')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
              <div class="d-flex align-items-center justify-content-between mb-4">
                  <div>
                      <h4 class="mb-0">{{ $request->requestor_first_name }} {{ $request->requestor_last_name }}</h4>
                      <small class="text-muted font-weight-bold">
                          {{ $request->reference_no }} <br>
                      </small>

                      @if($request->status === 'Processed')
                          <small class="badge badge-success mt-2">Processed</small>
                      @elseif($request->status === 'Approved')
                          <small class="badge badge-info mt-2">Approved</small>
                      @elseif($request->status === 'Pending')
                          <small class="badge badge-warning mt-2">Pending</small>
                      @elseif($request->status === 'Declined')
                          <small class="badge badge-danger mt-2">Declined</small>
                      @elseif($request->status === 'Cancelled')
                          <small class="badge badge-danger mt-2">Cancelled</small>
                      @endif
                  </div>

                  <div class="d-flex align-items-center">
                      <a href="{{ route('layout-design-requests.index') }}" style="display: flex; align-items: center; width: fit-content;" class="btn btn-sm btn-light border mb-2">
                          <i class="ti-arrow-left mr-1"></i> Back
                      </a>
                  </div>
              </div>

            <style>
              .ldr-section {
                border: 1px solid #e3e6ed;
                border-radius: .6rem;
                margin-bottom: 1.4rem;
                overflow: hidden;
              }
              .ldr-section-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: .9rem 1.2rem;
                background: #f8f9fc;
                border-bottom: 1px solid #e3e6ed;
              }
              .ldr-section-header h5 {
                margin: 0;
                font-size: .95rem;
                font-weight: 600;
              }
              .ldr-section-header h5 i {
                color: #007bff;
              }
              .ldr-section-body {
                padding: 1.2rem;
                background: #fff;
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
              }
              .ldr-attachment-list {
                display: flex;
                flex-direction: column;
                gap: .65rem;
              }
              .ldr-attachment-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                padding: .75rem;
                background: #f8fafc;
                border: 1px solid #e3e6ed;
                border-radius: .5rem;
              }
              .ldr-attachment-details {
                display: flex;
                align-items: center;
                min-width: 0;
              }
              .ldr-attachment-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                flex: 0 0 38px;
                width: 38px;
                height: 38px;
                margin-right: .75rem;
                color: #2878a9;
                background: #e6f3fb;
                border-radius: .45rem;
                font-size: 1rem;
              }
              .ldr-attachment-name {
                display: block;
                color: #263238;
                font-weight: 600;
                overflow-wrap: anywhere;
              }
              .ldr-attachment-meta {
                display: block;
                margin-top: .15rem;
                color: #6c757d;
                font-size: .75rem;
              }
              @media (max-width: 767.98px) {
                .ldr-section-body {
                  overflow-x: auto;
                  -webkit-overflow-scrolling: touch;
                }
                .ldr-section-body .info-table {
                  min-width: 600px;
                  margin-bottom: 0;
                }
              }
              @media (max-width: 575.98px) {
                .ldr-attachment-item {
                  align-items: stretch;
                  flex-direction: column;
                }
                .ldr-attachment-item .btn {
                  width: 100%;
                }
              }
            </style>

            <div class="ldr-section">
              <div class="ldr-section-header">
                <h5><i class="ti-user mr-2"></i>I. Request Information</h5>
              </div>
              <div class="ldr-section-body">
                <table class="table info-table">
                  <tr><td>Date Requested</td><td>{{ optional($request->date_requested)->format('F j, Y') }}</td></tr>
                  <tr><td>Requestor's Name</td><td>{{ $request->requestor_first_name }} {{ $request->requestor_last_name }}</td></tr>
                  <tr><td>Department</td><td>{{ $request->department }}</td></tr>
                  <tr><td>Requestor's Email</td><td>{{ $request->requestor_email }}</td></tr>
                </table>
              </div>
            </div>

            <div class="ldr-section">
              <div class="ldr-section-header">
                <h5><i class="ti-paint-bucket mr-2"></i>II. Design Details</h5>
              </div>
              <div class="ldr-section-body">
                <table class="table info-table">
                  <tr>
                      <td>Intended Use</td>
                      <td>
                          @foreach ((array) $request->intended_use as $item)
                          <span class="badge badge-info mr-1">{{ $item }}</span>
                          @endforeach
                      </td>
                  </tr>
                  <tr>
                      <td>Medium Type</td>
                      <td>{{ $request->medium_type }}</td>
                  </tr>
                  <tr>
                      <td>Marketing Collateral Type</td>
                      <td>
                          @foreach ((array) $request->marketing_collateral_type as $item)
                          <span class="badge badge-primary mr-1">{{ $item }}</span>
                          @endforeach
                      </td>
                  </tr>
                  @if($request->business_name || $request->ad_territory || $request->business_address || $request->contact_number)
                  <tr><td>Business Name</td><td>{{ $request->business_name ?: '—' }}</td></tr>
                  <tr><td>AD Territory</td><td>{{ $request->ad_territory ?: '—' }}</td></tr>
                  <tr><td>Business Address</td><td>{{ $request->business_address ?: '—' }}</td></tr>
                  <tr><td>Contact Number</td><td>{{ $request->contact_number ?: '—' }}</td></tr>
                  @endif
                  <tr><td>Purpose of Layout Request</td><td style="white-space: pre-line;">{{ $request->purpose_of_layout_request }}</td></tr>
                  <tr><td>Project Description</td><td style="white-space: pre-line;">{{ $request->project_description }}</td></tr>
                  <tr><td>Peg Sample</td>
                    <td>
                      @if ($request->pegSamples->isNotEmpty())
                        <div class="d-flex flex-column" style="gap: .35rem;">
                          @foreach ($request->pegSamples as $pegSample)
                            <a href="{{ $pegSample->public_url }}" target="_blank" rel="noopener noreferrer" class="text-info font-weight-bold">
                              <i class="{{ $pegSample->isExternalLink() ? 'ti-link' : (strpos((string) $pegSample->mime_type, 'video/') === 0 ? 'ti-video-camera' : 'ti-file') }} mr-1"></i>
                              {{ $pegSample->display_name }}
                            </a>
                          @endforeach
                        </div>
                        <small class="text-muted d-block mt-1">Click a file to view</small>
                      @elseif ($request->peg_sample_path)
                        <a href="{{ asset(ltrim($request->peg_sample_path, '/')) }}" target="_blank" rel="noopener noreferrer" class="text-info font-weight-bold">
                          <i class="ti-file mr-1"></i> {{ basename($request->peg_sample_path) }}
                        </a>
                        <small class="text-muted d-block mt-1">Click to view</small>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                  </tr>
                  <tr><td>Preferred Date Needed</td><td>{{ optional($request->date_needed)->format('F j, Y') ?? '-' }}</td></tr>
                </table>
              </div>
            </div>

            @if($request->priority_level || $request->due_date || $request->remarks)
            <div class="ldr-section">
              <div class="ldr-section-header">
                <h5><i class="ti-check-box mr-2"></i>III. Approval Details</h5>
              </div>
              <div class="ldr-section-body">
                <table class="table info-table">
                  @if($request->priority_level)
                  <tr><td>Priority Level</td><td><span class="badge badge-{{ $request->priority_level === 'Major' ? 'danger' : 'secondary' }}">{{ $request->priority_level }}</span></td></tr>
                  @endif
                  @if($request->due_date)
                  <tr><td>Due Date</td><td>{{ optional($request->due_date)->format('F j, Y') }}</td></tr>
                  @endif
                  @if($request->remarks)
                  <tr><td>Remarks</td><td style="white-space: pre-line;">{{ $request->remarks }}</td></tr>
                  @endif
                </table>
              </div>
            </div>
            @endif

            @if($request->status === 'Processed')
            <div class="ldr-section">
              <div class="ldr-section-header">
                <h5><i class="ti-check-box mr-2"></i>Issuance Details</h5>
              </div>
              <div class="ldr-section-body">
                <table class="table info-table">
                  <tr><td>Issuance Date</td><td>{{ optional($request->issuance_date)->format('F j, Y') }}</td></tr>
                  <tr><td>Remarks</td><td style="white-space: pre-line;">{{ $request->closing_remarks }}</td></tr>
                  <tr>
                    <td>Output Attachments</td>
                    <td>
                      <div class="ldr-attachment-list">
                        @forelse($request->outputAttachments as $attachment)
                          @php
                            $attachmentName = $attachment->original_name ?: basename($attachment->output_attachment_path);
                            $isExternalLink = $attachment->isExternalLink();
                            $attachmentExtension = strtoupper(pathinfo($attachmentName, PATHINFO_EXTENSION));
                            $attachmentBytes = (int) $attachment->file_size;
                            $attachmentSize = $attachmentBytes >= 1048576
                              ? number_format($attachmentBytes / 1048576, 1) . ' MB'
                              : ($attachmentBytes >= 1024
                                ? number_format($attachmentBytes / 1024, 1) . ' KB'
                                : $attachmentBytes . ' bytes');
                            $isVideo = strpos((string) $attachment->mime_type, 'video/') === 0;
                          @endphp

                          <div class="ldr-attachment-item">
                            <div class="ldr-attachment-details">
                              <span class="ldr-attachment-icon">
                                <i class="{{ $isExternalLink ? 'ti-link' : ($isVideo ? 'ti-video-camera' : 'ti-file') }}"></i>
                              </span>
                              <span>
                                <span class="ldr-attachment-name">{{ $attachmentName }}</span>
                                <span class="ldr-attachment-meta">
                                  {{ $isExternalLink ? 'SHARED LINK' : (($attachmentExtension ?: 'FILE') . ' · ' . $attachmentSize) }}
                                </span>
                              </span>
                            </div>

                            <a href="{{ $attachment->public_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-info flex-shrink-0">
                              <i class="ti-new-window mr-1"></i>{{ $isExternalLink ? 'Open link' : 'Open file' }}
                            </a>
                          </div>
                        @empty
                          <span class="text-muted">No output attachments available.</span>
                        @endforelse
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            @endif

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
