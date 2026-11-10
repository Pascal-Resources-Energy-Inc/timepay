@extends('layouts.header')
@section('css_header')
<style>
    .main-panel .card { z-index: auto; }

    .ldr-summary-card {
      height: 100%;
      color: inherit;
      background: #ffffff;
      border: 1px solid #e7ebf1;
      border-radius: 14px;
      box-shadow: 0 3px 10px rgba(30, 41, 59, 0.06);
      text-decoration: none !important;
      transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .ldr-summary-card:hover {
      color: inherit;
      border-color: #cbd5e1;
      box-shadow: 0 8px 18px rgba(30, 41, 59, 0.1);
      transform: translateY(-2px);
    }

    .ldr-summary-card.is-active {
      border-color: #6477d8;
      box-shadow: 0 0 0 2px rgba(100, 119, 216, 0.14), 0 8px 18px rgba(30, 41, 59, 0.09);
    }

    .ldr-summary-card .card-body {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      min-height: 108px;
      padding: 6px;
    }

    .ldr-summary-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex: 0 0 40px;
      width: 40px;
      height: 40px;
      border-radius: 10px;
      font-size: 18px;
    }

    .ldr-summary-content {
      min-width: 0;
      flex: 1;
    }

    .ldr-summary-label {
      overflow: hidden;
      color: #526987;
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.045em;
      line-height: 1.25;
      text-overflow: ellipsis;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .ldr-summary-value {
      margin-top: 5px;
      color: #0f172a;
      font-size: 1.6rem;
      font-weight: 700;
      line-height: 1;
    }

    .ldr-summary-caption {
      margin-top: 9px;
      overflow: hidden;
      color: #94a3b8;
      font-size: 0.78rem;
      font-weight: 600;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .ldr-icon-pending { background: #fff3cd; color: #a16207; }
    .ldr-icon-approved { background: #ede9fe; color: #6d4aff; }
    .ldr-icon-processed { background: #dcfce7; color: #15803d; }
    .ldr-icon-declined { background: #fee2e2; color: #b91c1c; }
    .ldr-icon-cancelled { background: #e2e8f0; color: #475569; }

    @media (max-width: 767.98px) {
      .ldr-summary-card .card-body {
        min-height: auto;
      }
    }

    .ldr-upload-zone {
      position: relative;
      display: flex;
      align-items: center;
      gap: 14px;
      width: 100%;
      margin: 0;
      padding: 16px;
      border: 2px dashed #cbd5e1;
      border-radius: 10px;
      background: #f8fafc;
      cursor: pointer;
      transition: border-color .2s ease, background-color .2s ease, box-shadow .2s ease;
    }

    .ldr-upload-zone:hover,
    .ldr-upload-zone:focus-within {
      border-color: #4b9ec9;
      background: #f0f9ff;
      box-shadow: 0 0 0 3px rgba(75, 158, 201, .12);
    }

    .ldr-upload-zone.has-file {
      border-style: solid;
      border-color: #28a745;
      background: #f3fff6;
    }

    .ldr-upload-zone.has-error {
      border-color: #dc3545;
      background: #fff8f8;
    }

    .ldr-upload-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex: 0 0 44px;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: #e0f2fe;
      color: #1685b8;
      font-size: 20px;
    }

    .ldr-upload-zone.has-file .ldr-upload-icon {
      background: #dff6e5;
      color: #218838;
    }

    .ldr-upload-copy {
      flex: 1;
      min-width: 0;
      line-height: 1.35;
    }

    .ldr-upload-title,
    .ldr-upload-file-name,
    .ldr-upload-hint {
      display: block;
    }

    .ldr-upload-title {
      color: #263238;
      font-size: 13px;
      font-weight: 600;
    }

    .ldr-upload-file-name {
      margin-top: 3px;
      overflow: hidden;
      color: #1685b8;
      font-size: 12px;
      font-weight: 600;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .ldr-upload-hint {
      margin-top: 3px;
      color: #6c757d;
      font-size: 11px;
    }

    .ldr-output-input {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      overflow: hidden;
      opacity: 0;
    }

    .ldr-selected-files {
      margin-top: 10px;
      padding: 10px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
    }

    .ldr-selected-files-title {
      margin-bottom: 6px;
      color: #475569;
      font-size: 12px;
      font-weight: 600;
    }

    .ldr-selected-file-row {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px;
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
    }

    .ldr-selected-file-row + .ldr-selected-file-row {
      margin-top: 6px;
    }

    .ldr-selected-file-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex: 0 0 34px;
      width: 34px;
      height: 34px;
      color: #1685b8;
      background: #e0f2fe;
      border-radius: 6px;
    }

    .ldr-selected-file-details {
      min-width: 0;
      flex: 1 1 auto;
    }

    .ldr-selected-file-name {
      display: block;
      overflow: hidden;
      color: #334155;
      font-size: 12px;
      font-weight: 600;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .ldr-selected-file-size {
      display: block;
      margin-top: 2px;
      color: #64748b;
      font-size: 11px;
    }

    .ldr-selected-file-remove {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex: 0 0 30px;
      width: 30px;
      height: 30px;
      padding: 0;
      color: #64748b;
      background: transparent;
      border: 0;
      border-radius: 50%;
      font-size: 22px;
      line-height: 1;
      cursor: pointer;
    }

    .ldr-selected-file-remove:hover,
    .ldr-selected-file-remove:focus {
      color: #dc3545;
      background: #fff1f2;
      outline: none;
    }

    @media (max-width: 575.98px) {
      .ldr-close-footer {
        display: flex;
        flex-direction: column-reverse;
        align-items: stretch;
        gap: 8px;
      }

      .ldr-close-footer .btn {
        width: 100%;
        margin: 0;
      }

      .ldr-selected-file-name {
        max-width: calc(100vw - 190px);
      }
    }
</style>
@endsection
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class='row grid-margin ldr-summary-cards'>
      <div class='col-xl-4 col-md-6 mb-3'>
        <a href="{{ route('ldr-approval.index', ['status' => 'Pending']) }}" class="card ldr-summary-card {{ $filter_status === 'Pending' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="ldr-summary-icon ldr-icon-pending"><i class="ti-time"></i></span>
            <div class="ldr-summary-content">
              <div class="ldr-summary-label" title="Pending">Pending</div>
              <div class="ldr-summary-value">{{$pending}}</div>
              <div class="ldr-summary-caption">Awaiting review</div>
            </div>
          </div>
        </a>
      </div>
      <div class='col-xl-4 col-md-6 mb-3'>
        <a href="{{ route('ldr-approval.index', ['status' => 'Approved']) }}" class="card ldr-summary-card {{ $filter_status === 'Approved' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="ldr-summary-icon ldr-icon-approved"><i class="ti-check-box"></i></span>
            <div class="ldr-summary-content">
              <div class="ldr-summary-label" title="Approved">Approved</div>
              <div class="ldr-summary-value">{{$approved}}</div>
              <div class="ldr-summary-caption">Ready for processing</div>
            </div>
          </div>
        </a>
      </div>
      <div class='col-xl-4 col-md-6 mb-3'>
        <a href="{{ route('ldr-approval.index', ['status' => 'Processed']) }}" class="card ldr-summary-card {{ $filter_status === 'Processed' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="ldr-summary-icon ldr-icon-processed"><i class="ti-check"></i></span>
            <div class="ldr-summary-content">
              <div class="ldr-summary-label" title="Processed">Processed</div>
              <div class="ldr-summary-value">{{$processed}}</div>
              <div class="ldr-summary-caption">Processed requests</div>
            </div>
          </div>
        </a>
      </div>
      <div class='col-xl-4 col-md-6 mb-3'>
        <a href="/ldr-approval?status=Declined" class="card ldr-summary-card {{ $filter_status === 'Declined' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="ldr-summary-icon ldr-icon-declined"><i class="ti-close"></i></span>
            <div class="ldr-summary-content">
              <div class="ldr-summary-label" title="Declined">Declined</div>
              <div class="ldr-summary-value">{{$declined}}</div>
              <div class="ldr-summary-caption">Not approved</div>
            </div>
          </div>
        </a>
      </div>
      <div class='col-xl-4 col-md-6 mb-3'>
        <a href="/ldr-approval?status=Cancelled" class="card ldr-summary-card {{ $filter_status === 'Cancelled' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="ldr-summary-icon ldr-icon-cancelled"><i class="ti-na"></i></span>
            <div class="ldr-summary-content">
              <div class="ldr-summary-label" title="Cancelled">Cancelled</div>
              <div class="ldr-summary-value">{{$cancelled}}</div>
              <div class="ldr-summary-caption">Cancelled requests</div>
            </div>
          </div>
        </a>
      </div>
    </div>


    <!-- contents -->
    <div class='row'>
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Layout Design Request Approvals</h4>
            <form method="get" action="{{ route('ldr-approval.index') }}" onsubmit='show();'>
              <div class="row">
               <!-- search through the records -->
               <div class="col-md-4">
                   <div class="d-flex form-group flex-column">
                       <label class="mb-1">Search</label>
                       <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search ref no., requestor..." />
                   </div>
               </div>
               <div class="col-md-2">
                 <div class="d-flex form-group flex-column">
                   <label class="mb-1">Status</label>
                   <select name="status" class="form-control form-control-sm js-example-basic-single" style="width:100%;">
                     <option value="Pending" @if ($filter_status == 'Pending') selected @endif>Pending</option>
                     <option value="Approved" @if ($filter_status == 'Approved') selected @endif>Approved</option>
                     <option value="Processed" @if ($filter_status == 'Processed') selected @endif>Processed</option>
                     <option value="Declined" @if ($filter_status == 'Declined') selected @endif>Declined</option>
                     <option value="Cancelled" @if ($filter_status == 'Cancelled') selected @endif>Cancelled</option>
                     <option value="From Import" @if ($filter_status == 'From Import') selected @endif>From Import</option>
                   </select>
                 </div>
               </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="text-right mb-1">From</label>
                    <input type="date" value="{{ $from }}" class="form-control form-control-sm" name="from" />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="text-right mb-1">To</label>
                    <input type="date" value="{{ $to }}" class="form-control form-control-sm" name="to" />
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
                    <th>Action</th>
                    <th>Reference No</th>
                    <th>Requestor</th>
                    <th>Department</th>
                    <th>Collateral Type</th>
                    <th>Medium</th>
                    <th>Preferred Date</th>
                    <th>Priority Level</th>
                    <th>Due Date</th>
                    <th>Peg Sample</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($requests as $r)
                    <tr>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center" style="gap: 5px;">
                                @if ($r->status === 'Pending')
                                <button type="button" class="btn btn-primary btn-rounded btn-icon" data-toggle="modal" data-target="#processLdr{{$r->id}}" title="Approve"><i class="ti-check"></i></button>
                                <button type="button" class="btn btn-danger btn-rounded btn-icon" data-toggle="modal" data-target="#declineLdr{{$r->id}}" title="Decline"><i class="ti-close"></i></button>
                                @endif
                                @if ($r->status === 'Approved')
                                <button type="button" class="btn btn-success btn-rounded btn-icon" data-toggle="modal" data-target="#closeLdr{{$r->id}}" title="Mark as Processed"><i class="ti-arrow-right"></i></button>
                                @endif
                                <a href="{{ route('ldr-approval.view', $r->id) }}" class="d-flex align-items-center justify-content-center btn btn-info btn-rounded btn-icon" title="View Details"><i class="ti-eye"></i></a>
                            </div>
                        </td>
                      <td><span class="badge badge-primary">{{$r->reference_no}}</span></td>
                      <td>
                          {{ trim(($r->requestor_first_name ?? '') . ' ' . ($r->requestor_last_name ?? ''))
                          ?: ($r->full_name ?? '-') }}
                          <br>
                          <small class="text-muted">{{$r->requestor_email}}</small>
                      </td>
                      <td>{{$r->department}}</td>
                      <td>
                          @php
                            $tableCollateralTypes = (array) $r->marketing_collateral_type;
                            $tableHasStoreSignageValue = collect($tableCollateralTypes)->contains(function ($item) {
                              return in_array($item, \App\LayoutDesign::STORE_SIGNAGE_OPTIONS, true);
                            });
                            $tableDisplayCollateralTypes = array_values(array_filter(
                              $tableCollateralTypes,
                              function ($item) use ($tableHasStoreSignageValue) {
                                return !$tableHasStoreSignageValue || $item !== 'Store Signage';
                              }
                            ));
                          @endphp
                          @forelse ($tableDisplayCollateralTypes as $item)
                              <span class="badge badge-info d-block mb-1">{{ $item }}</span>
                          @empty
                              <span class="text-muted">-</span>
                          @endforelse
                      </td>
                      <td>{{$r->medium_type}}</td>
                      <td>{{ $r->date_needed ? $r->date_needed->format('M d, Y') : '-' }}</td>
                      <td>
                        @if ($r->priority_level)
                          <span class="badge text-white badge-{{ $r->priority_level === 'Major' ? 'danger' : 'secondary' }}">{{ $r->priority_level }}</span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td style="white-space: nowrap;">
                        @if ($r->due_date)
                          {{ optional($r->due_date)->format('M d, Y') }}<br>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td style="text-align: center;">
                        @if ($r->pegSamples->count() > 1)
                          <span class="text-muted" title="Open the request details to view all attachments">
                            Multiple attachments.
                            <span>
                                <a href="{{ route('ldr-approval.view', $r->id) }}">
                                    view all here
                                </a>
                            </span>
                          </span>
                        @elseif ($r->pegSamples->count() === 1)
                          @php
                            $pegSample = $r->pegSamples->first();
                            $pegSampleName = $pegSample->original_name ?: basename($pegSample->output_attachment_path);
                          @endphp
                          <a href="{{ $pegSample->public_url }}" target="_blank" rel="noopener noreferrer" class="text-info d-inline-block text-truncate" style="max-width: 180px; vertical-align: middle;" title="{{ $pegSampleName }}">
                            <i class="{{ $pegSample->isExternalLink() ? 'ti-link' : (strpos((string) $pegSample->mime_type, 'video/') === 0 ? 'ti-video-camera' : 'ti-file') }} mr-1"></i>{{ $pegSampleName }}
                          </a>
                        @elseif ($r->peg_sample_path)
                          <a href="{{ asset(ltrim($r->peg_sample_path, '/')) }}" target="_blank" rel="noopener noreferrer" class="text-info d-inline-block text-truncate" style="max-width: 180px; vertical-align: middle;" title="{{ basename($r->peg_sample_path) }}">
                            <i class="ti-file mr-1"></i>{{ basename($r->peg_sample_path) }}
                          </a>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td>
                        <span class="badge badge-{{ $r->status == 'Processed' ? 'success' : ($r->status == 'Approved' ? 'info' : ($r->status == 'From Import' ? 'secondary' : (($r->status == 'Declined' || $r->status == 'Cancelled') ? 'danger' : 'warning'))) }}">{{$r->status}}</span>
                      </td>
                    </tr>
                  @endforeach
                  @if(count($requests) == 0)
                    <tr><td colspan="11" class="text-center text-muted py-4">No records found.</td></tr>
                  @endif
                </tbody>
              </table>
            </div>
            <div class="mt-3">{{ $requests->appends(request()->query())->links() }}</div>
            @foreach ($requests as $r)
              @php
                $collateralTypes = (array) $r->marketing_collateral_type;
                $selectedStoreSignageOption = collect($collateralTypes)->first(function ($collateralType) {
                  return in_array($collateralType, \App\LayoutDesign::STORE_SIGNAGE_OPTIONS, true);
                });
                $displayCollateralTypes = array_values(array_diff(
                  $collateralTypes,
                  \App\LayoutDesign::STORE_SIGNAGE_OPTIONS
                ));
                $isRefurbishedStoreSignage = in_array(
                  'Refurbished Store Signage (P5,000)',
                  $collateralTypes,
                  true
                );
              @endphp
              <div class="modal fade" id="processLdr{{$r->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <form method="post" action="{{ route('ldr.approve', $r->id) }}" onsubmit="show();" data-open-due-date="{{ $isRefurbishedStoreSignage ? '1' : '0' }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="ldr_id" value="{{ $r->id }}">
                    <div class="modal-content border-0">
                      <div class="modal-header">
                        <h5 class="modal-title">Approve Request</h5>
                        <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </div>
                      <div class="modal-body">
                        <div class="bg-light rounded p-3" style="font-size: 12px;">
                          <div class="row mb-2">
                            <div class="col-4 text-muted">Reference No.</div>
                            <div class="col-8 font-weight-bold">{{$r->reference_no}}</div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4 text-muted">Requestor</div>
                            <div class="col-8 font-weight-bold">{{$r->requestor_first_name}} {{$r->requestor_last_name}}</div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4 text-muted">Email</div>
                            <div class="col-8 text-break">{{$r->requestor_email}}</div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4 text-muted">Collateral Type</div>
                            <div class="col-8">
                              @forelse ($displayCollateralTypes as $collateralType)
                                <div class="mb-1">
                                  <span class="badge badge-info">
                                    {{ $collateralType === 'Store Signage' && $selectedStoreSignageOption ? $selectedStoreSignageOption : $collateralType }}
                                  </span>
                                </div>
                              @empty
                                <span class="text-muted">-</span>
                              @endforelse
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4 text-muted">Purpose</div>
                            <div class="col-8" style="white-space: pre-line;">{{$r->purpose_of_layout_request}}</div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4 text-muted">Description</div>
                            <div class="col-8" style="white-space: pre-line;">{{$r->project_description}}</div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4 text-muted">Intended Use</div>
                            <div class="col-8">{{ implode(', ', (array) $r->intended_use) }}</div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4 text-muted">Medium Type</div>
                            <div class="col-8">{{$r->medium_type}}</div>
                          </div>
                          <div class="row @if ($r->pegSamples->isNotEmpty() || $r->peg_sample_path) mb-2 @endif">
                            <div class="col-4 text-muted">Preferred Date Needed</div>
                            <div class="col-8">{{ $r->date_needed ? date('M d, Y', strtotime($r->date_needed)) : '-' }}</div>
                          </div>
                          @if ($r->pegSamples->isNotEmpty() || $r->peg_sample_path)
                          <div class="row">
                            <div class="col-4 text-muted">Peg Sample</div>
                            <div class="col-8">
                              @if ($r->pegSamples->isNotEmpty())
                                @foreach ($r->pegSamples as $pegSample)
                                  <a href="{{ $pegSample->public_url }}" target="_blank" rel="noopener noreferrer" class="text-info d-block mb-1 text-break">
                                    <i class="{{ $pegSample->isExternalLink() ? 'ti-link' : (strpos((string) $pegSample->mime_type, 'video/') === 0 ? 'ti-video-camera' : 'ti-file') }} mr-1"></i>{{ $pegSample->display_name }}
                                  </a>
                                @endforeach
                              @else
                                <a href="{{ asset(ltrim($r->peg_sample_path, '/')) }}" target="_blank" rel="noopener noreferrer" class="text-info">
                                  <i class="ti-file mr-1"></i>View Attachment
                                </a>
                              @endif
                            </div>
                          </div>
                          @endif
                        </div>
                        @php
                          $approvalHasErrors = (int) old('ldr_id') === (int) $r->id;
                        @endphp
                        
                        @if ($isRefurbishedStoreSignage)
                          <div class="alert alert-info mt-3 mb-0 py-2 px-3" role="alert">
                            <i class="ti-info-alt mr-1"></i>
                            This request includes Refurbished Store Signage (P5,000) therefore, the due date is not subject to the standard 3 or 7 working-days limit.
                          </div>
                        @endif

                        <div class="row mt-3">
                          <div class="col-md-6 form-group mb-3 mb-md-0">
                            <label for="priorityLevel{{$r->id}}" class="font-weight-bold">Priority Level <span class="text-danger">*</span></label>
                            <select id="priorityLevel{{$r->id}}" name="priority_level" class="form-control ldr-priority-level @if($approvalHasErrors && $errors->has('priority_level')) is-invalid @endif" required>
                              <option value="">Select priority</option>
                              @foreach (\App\LayoutDesign::PRIORITY_LEVELS as $priorityLevel)
                                <option value="{{ $priorityLevel }}" @if(($approvalHasErrors ? old('priority_level') : $r->priority_level) === $priorityLevel) selected @endif>{{ $priorityLevel }}</option>
                              @endforeach
                            </select>
                            @if($approvalHasErrors && $errors->has('priority_level'))
                              <div class="invalid-feedback">{{ $errors->first('priority_level') }}</div>
                            @endif
                          </div>
                          <div class="col-md-6 form-group mb-0">
                            <label for="dueDate{{$r->id}}" class="font-weight-bold">Due Date <span class="text-danger">*</span></label>
                            <input id="dueDate{{$r->id}}"
                                type="date"
                                name="due_date"
                                class="form-control ldr-due-date @if($approvalHasErrors && $errors->has('due_date')) is-invalid @endif"
                                value="{{ $approvalHasErrors ? old('due_date') : optional($r->due_date)->format('Y-m-d') }}"
                                min="{{ now()->format('Y-m-d') }}"
                                required>
                            <small class="form-text text-muted ldr-due-date-help"></small>
                            @if($approvalHasErrors && $errors->has('due_date'))
                              <div class="invalid-feedback">{{ $errors->first('due_date') }}</div>
                            @endif
                          </div>
                        </div>
                        <div class="form-group mt-3 mb-0">
                          <label for="additionalNotes{{$r->id}}" class="font-weight-bold">Additional Notes</label>
                          <textarea
                            id="additionalNotes{{$r->id}}"
                            name="additional_notes"
                            class="form-control @if($approvalHasErrors && $errors->has('additional_notes')) is-invalid @endif"
                            rows="3"
                            placeholder="Enter any additional notes for this request">{{ $approvalHasErrors ? old('additional_notes') : $r->additional_notes }}</textarea>
                          @if($approvalHasErrors && $errors->has('additional_notes'))
                            <div class="invalid-feedback">{{ $errors->first('additional_notes') }}</div>
                          @endif
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Approve</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <div class="modal fade" id="closeLdr{{$r->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <form method="post" action="{{ route('ldr.mark-processed', $r->id) }}" enctype="multipart/form-data" onsubmit="show();" class="ldr-complete-form">
                          {{csrf_field()}}
                          <input type="hidden" name="ldr_id" value="{{ $r->id }}">
                          <div class="modal-content border-0">
                            <div class="modal-header">
                              <h5 class="modal-title">Mark Request as Processed</h5>
                              <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <div class="bg-light rounded p-3" style="font-size: 12px;">
                                  <div class="row mb-2">
                                    <div class="col-4 text-muted">Reference No.</div>
                                    <div class="col-8 font-weight-bold">{{$r->reference_no}}</div>
                                  </div>
                                  <div class="row mb-2">
                                    <div class="col-4 text-muted">Requestor</div>
                                    <div class="col-8">
                                      <div class="font-weight-bold">{{$r->requestor_first_name}} {{$r->requestor_last_name}}</div>
                                    </div>
                                  </div>
                                  <div class="row mb-2">
                                    <div class="col-4 text-muted">Send to</div>
                                    <div class="col-8">
                                      <div class="text-info text-break" style="text-decoration:underline;">{{$r->requestor_email}}</div>
                                    </div>
                                  </div>
                                  <div class="row mb-2">
                                    <div class="col-4 text-muted">Intended Use</div>
                                    <div class="col-8">
                                      <div class="text-muted text-break">{{ implode(', ', (array) $r->intended_use) }}</div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            <div class="d-flex align-items-start mb-3 px-3 py-2" style="background: #f7f9fc; border-left: 3px solid #6c8eab; border-radius: 4px; color: #4b5563; font-size: 13px;">
                              <i class="ti-info-alt mr-2 mt-1" style="color: #527694;"></i>
                              <div>
                                <strong>Note:</strong>
                                Files that exceed email delivery file limits will not be attached to the email. The requestor can log in to HERA to access them.
                              </div>
                            </div>

                              <!-- attachment part -->
                              <div class="form-group mb-4">
                                @php
                                  $hasAttachmentError =
                                    $errors->has('output_attachments') ||
                                    $errors->has('output_attachments.*') ||
                                    $errors->has('output_attachment_link');
                                @endphp

                                <label class="ldr-upload-zone {{ (int) old('ldr_id') === (int) $r->id && $hasAttachmentError ? 'has-error' : '' }}" for="outputAttachment{{$r->id}}">
                                  <input 
                                    type="file" 
                                    id="outputAttachment{{$r->id}}" 
                                    name="output_attachments[]" 
                                    class="ldr-output-input" 
                                    accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.ppt,.pptx,.mp4,.mov,.avi,.webm,.mkv"
                                    multiple
                                  >
                                  <span class="ldr-upload-icon"><i class="ti-cloud-up"></i></span>
                                  <span class="ldr-upload-copy">
                                    <span class="ldr-upload-title">Upload attachments</span>
                                    <span class="ldr-upload-file-name">Click to choose one or more files</span>
                                    <span class="ldr-upload-hint">
                                        JPG, PNG, WEBP, PDF, Word, PowerPoint, MP4, WEBM, — 100 MB maximum per file.
                                    </span>
                                    <span class="mt-3 ldr-upload-hint">
                                        Hold CTRL to select multiple selection
                                    </span>
                                  </span>
                                </label>

                                <div
                                  class="ldr-selected-files d-none"
                                  data-input-id="outputAttachment{{$r->id}}"
                                  aria-live="polite"
                                ></div>

                                @if ((int) old('ldr_id') === (int) $r->id && $hasAttachmentError)
                                  <div class="invalid-feedback d-block">
                                    {{ $errors->first('output_attachments') ?: $errors->first('output_attachments.*') }}
                                  </div>
                                @endif

                                <div class="mt-3">
                                  <label for="outputAttachmentLink{{$r->id}}" class="form-label">Or Share an Output Link</label>
                                  <input
                                    type="url"
                                    id="outputAttachmentLink{{$r->id}}"
                                    name="output_attachment_link"
                                    class="form-control ldr-output-link @if ((int) old('ldr_id') === (int) $r->id && $errors->has('output_attachment_link')) is-invalid @endif"
                                    value="{{ (int) old('ldr_id') === (int) $r->id ? old('output_attachment_link') : '' }}"
                                    maxlength="255"
                                    placeholder="https://drive.google.com/..."
                                  >
                                  <small class="text-muted d-block mt-1">Make sure the requestor can open the shared link.</small>
                                  @if ((int) old('ldr_id') === (int) $r->id && $errors->has('output_attachment_link'))
                                    <div class="invalid-feedback">{{ $errors->first('output_attachment_link') }}</div>
                                  @endif
                                </div>
                              </div>

                              <!-- Issuance date -->
                              <!-- <div class="form-group"> -->
                              <!--   <label for="issuanceDate{{$r->id}}">Issuance Date <span class="text-danger">*</span></label> -->
                              <!--   <input type="date" id="issuanceDate{{$r->id}}" name="issuance_date" class="form-control @if ((int) old('ldr_id') === (int) $r->id && $errors->has('issuance_date')) is-invalid @endif" value="{{ (int) old('ldr_id') === (int) $r->id ? old('issuance_date') : date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required> -->
                              <!--   @if ((int) old('ldr_id') === (int) $r->id && $errors->has('issuance_date')) -->
                              <!--     <div class="invalid-feedback">{{ $errors->first('issuance_date') }}</div> -->
                              <!--   @endif -->
                              <!-- </div> -->

                              <!-- remaks -->
                              <div class="form-group">
                                <label for="closingRemarks{{$r->id}}">Remarks <span class="text-danger">*</span></label>
                                <textarea id="closingRemarks{{$r->id}}" name="closing_remarks" class="form-control @if ((int) old('ldr_id') === (int) $r->id && $errors->has('closing_remarks')) is-invalid @endif" rows="3" placeholder="enter remarks" required>{{ (int) old('ldr_id') === (int) $r->id ? old('closing_remarks') : '' }}</textarea>
                                @if ((int) old('ldr_id') === (int) $r->id && $errors->has('closing_remarks'))
                                  <div class="invalid-feedback">{{ $errors->first('closing_remarks') }}</div>
                                @endif
                              </div>

                            </div>
                            <div class="modal-footer ldr-close-footer">
                              <button type="button" class="btn btn-light border" data-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-success">Mark as Processed</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>

                    <div class="modal fade" id="declineLdr{{$r->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <form method="post" action="{{ route('ldr.decline', $r->id) }}" class="decline-ldr-form">
                          {{csrf_field()}}
                          <input type="hidden" name="ldr_id" value="{{ $r->id }}">
                          <div class="modal-content border-0">
                            <div class="modal-header">
                              <h5 class="modal-title">Decline Request</h5>
                              <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <div class="bg-light rounded p-3">
                                  <label class="text-muted text-uppercase mb-1" style="font-size: 11px; letter-spacing: .5px;">Reference No.</label>
                                  <div class="font-weight-bold">{{$r->reference_no}}</div>
                                  <label class="text-muted text-uppercase mb-1 mt-2" style="font-size: 11px; letter-spacing: .5px;">Requestor</label>
                                  <div class="font-weight-bold">{{$r->requestor_first_name}} {{$r->requestor_last_name}}</div>
                                </div>
                              </div>
                              <div class="form-group mt-3">
                                <label class="text-muted text-uppercase mb-1" style="font-size: 11px; letter-spacing: .5px;">Remarks <span class="text-danger">*</span></label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Reason for declining">{{ (int) old('ldr_id') === (int) $r->id ? old('remarks') : '' }}</textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-danger">Decline</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
  function addLdrWorkingDays(value, numberOfDays) {
    var date = new Date(value);
    var workingDays = 0;

    while (workingDays < numberOfDays) {
      date.setDate(date.getDate() + 1);

      if (date.getDay() !== 0 && date.getDay() !== 6) {
        workingDays++;
      }
    }

    return date;
  }

  function isLdrWeekend(value) {
    if (!value) {
      return false;
    }

    var dateParts = value.slice(0, 10).split('-');
    var date = new Date(
      Number(dateParts[0]),
      Number(dateParts[1]) - 1,
      Number(dateParts[2])
    );

    return date.getDay() === 0 || date.getDay() === 6;
  }

  // 3 days
  function getMinorLdrDueDateLimits() {
      let dueDate = addLdrWorkingDays(new Date(), 3);

      function pad(value) {
          return value < 10 ? '0' + value : String(value);
      }

      let date = dueDate.getFullYear()
        + '-' + pad(dueDate.getMonth() + 1)
        + '-' + pad(dueDate.getDate());

      return { date: date, min: date, max: date };
  }

  // 7 days
  function getMajorLdrDueDateLimits() {
      let dueDate = addLdrWorkingDays(new Date(), 7);

      function pad(value) {
          return value < 10 ? '0' + value : String(value);
      }

      let date = dueDate.getFullYear()
        + '-' + pad(dueDate.getMonth() + 1)
        + '-' + pad(dueDate.getDate());

      return { date: date, min: date, max: date };
  }

  function syncLdrDueDate(prioritySelect, refreshDueDate) {
    var form = $(prioritySelect).closest('form');
    var dueDate = form.find('.ldr-due-date');
    var helpText = form.find('.ldr-due-date-help');
    var isMinor = $(prioritySelect).val() === 'Minor';
    var isMajor = $(prioritySelect).val() === 'Major';
    var hasOpenDueDate = String(form.data('open-due-date')) === '1';
    var defaultMin = dueDate.data('default-min');

    if (typeof defaultMin === 'undefined') {
      defaultMin = dueDate.attr('min') || '';
      dueDate.data('default-min', defaultMin);
    }

    if (hasOpenDueDate) {
      dueDate.removeAttr('max');
      if (defaultMin) {
        dueDate.attr('min', defaultMin);
      } else {
        dueDate.removeAttr('min');
      }
      dueDate.prop('readonly', false).data('priority-autofilled', false);
      helpText.text('');
      dueDate.trigger('input');
      return;
    }

    if (isMinor) {
        let limits = getMinorLdrDueDateLimits();
        let currentValue = dueDate.val();

        if (
            refreshDueDate
            || !currentValue
            || currentValue !== limits.date
        ) {
            dueDate.val(limits.date);
        }

        dueDate
            .attr('min', limits.min)
            .attr('max', limits.max)
            .prop('readonly', false)
            .data('priority-autofilled', true);

        helpText.text('The due date for minor requests is automatically set to (3) working days.');
        dueDate.trigger('input');
        return;
    }

      if (isMajor) {
          let majorLimits = getMajorLdrDueDateLimits();
          let majorCurrentValue = dueDate.val();

          if (
              refreshDueDate
              || !majorCurrentValue
              || majorCurrentValue !== majorLimits.date
          ) {
              dueDate.val(majorLimits.date);
          }

          dueDate
              .attr('min', majorLimits.min)
              .attr('max', majorLimits.max)
              .prop('readonly', false)
              .data('priority-autofilled', true);

          helpText.text('The due date for major request is automatically set to (7) working days.');
          dueDate.trigger('input');
          return;
      }

    if (dueDate.data('priority-autofilled')) {
      dueDate.val('');
    }

    dueDate.removeAttr('max');
    if (defaultMin) {
      dueDate.attr('min', defaultMin);
    } else {
      dueDate.removeAttr('min');
    }
    dueDate.prop('readonly', false).data('priority-autofilled', false);
    helpText.text('');
    dueDate.trigger('input');
  }

  $(document).on('input change', '.ldr-due-date', function() {
    var form = $(this).closest('form');
    var isMinor = form.find('.ldr-priority-level').val() === 'Minor';
    var hasOpenDueDate = String(form.data('open-due-date')) === '1';
    var invalidWeekend = !hasOpenDueDate && isMinor && isLdrWeekend(this.value);

    this.setCustomValidity(
      invalidWeekend ? 'Minor requests cannot be due on Saturday or Sunday.' : ''
    );
  });

  $(document).on('change', '.ldr-priority-level', function() {
    syncLdrDueDate(this, true);
  });

  $(document).on('shown.bs.modal', '[id^="processLdr"]', function() {
    var prioritySelect = $(this).find('.ldr-priority-level').get(0);

    if (prioritySelect) {
      syncLdrDueDate(prioritySelect, false);
    }
  });

  function formatLdrAttachmentSize(bytes) {
    if (bytes < 1048576) {
      return Math.max(1, Math.round(bytes / 1024)) + ' KB';
    }

    return (bytes / 1048576).toFixed(1) + ' MB';
  }

  function renderLdrOutputAttachments(input) {
    var uploadZone = $(input).closest('.ldr-upload-zone');
    var uploadTitle = uploadZone.find('.ldr-upload-title');
    var uploadIcon = uploadZone.find('.ldr-upload-icon i');
    var fileName = uploadZone.find('.ldr-upload-file-name');
    var selectedList = $('.ldr-selected-files[data-input-id="' + input.id + '"]');
    var files = $(input).data('selectedFiles') || [];

    selectedList.empty();

    if (!files.length) {
      uploadZone.removeClass('has-file');
      uploadTitle.text('Upload attachments');
      uploadIcon.attr('class', 'ti-cloud-up');
      fileName.text('Click to choose one or more files');
      selectedList.addClass('d-none');
      return;
    }

    var totalBytes = files.reduce(function(total, file) {
      return total + file.size;
    }, 0);
    var totalSize = formatLdrAttachmentSize(totalBytes);

    uploadZone.removeClass('has-error').addClass('has-file');
    uploadTitle.text(files.length + ' file(s) attached');
    uploadIcon.attr('class', 'ti-check');
    fileName.text(files.length === 1
      ? files[0].name + ' · ' + totalSize
      : files.length + ' files selected · ' + totalSize
    );

    selectedList.append(
      $('<div>', {
        class: 'ldr-selected-files-title',
        text: 'Selected attachments (' + files.length + ')'
      })
    );

    files.forEach(function(file, index) {
      var iconClass = file.type.indexOf('image/') === 0
        ? 'ti-image'
        : (file.type.indexOf('video/') === 0 ? 'ti-video-camera' : 'ti-file');

      var row = $('<div>', { class: 'ldr-selected-file-row' })
        .append(
          $('<span>', { class: 'ldr-selected-file-icon' })
            .append($('<i>', { class: iconClass }))
        )
        .append(
          $('<span>', { class: 'ldr-selected-file-details' })
            .append($('<span>', {
              class: 'ldr-selected-file-name',
              text: file.name,
              title: file.name
            }))
            .append($('<span>', {
              class: 'ldr-selected-file-size',
              text: formatLdrAttachmentSize(file.size)
            }))
        )
        .append(
          $('<button>', {
            type: 'button',
            class: 'ldr-selected-file-remove',
            title: 'Remove ' + file.name,
            'aria-label': 'Remove ' + file.name,
            'data-file-index': index,
            text: '×'
          })
        );

      selectedList.append(row);
    });

    selectedList.removeClass('d-none');
  }

  $(document).on('change', '.ldr-output-input', function() {
    $(this).data('selectedFiles', Array.from(this.files || []));
    renderLdrOutputAttachments(this);
  });

  $(document).on('click', '.ldr-selected-file-remove', function() {
    var selectedList = $(this).closest('.ldr-selected-files');
    var input = document.getElementById(selectedList.attr('data-input-id'));
    var files = ($(input).data('selectedFiles') || []).slice();
    var fileIndex = Number($(this).attr('data-file-index'));
    var transfer = new DataTransfer();

    files.splice(fileIndex, 1);
    files.forEach(function(file) {
      transfer.items.add(file);
    });

    input.files = transfer.files;
    $(input).data('selectedFiles', files);
    renderLdrOutputAttachments(input);
  });

  $(document).on('change input', '.ldr-output-input, .ldr-output-link', function() {
    var form = $(this).closest('.ldr-complete-form');
    var fileInput = form.find('.ldr-output-input').get(0);

    if (fileInput) {
      fileInput.setCustomValidity('');
    }
  });

  $(document).on('submit', '.ldr-complete-form', function(event) {
    var form = $(this);
    var fileInput = form.find('.ldr-output-input').get(0);
    var linkValue = $.trim(form.find('.ldr-output-link').val());
    var hasFiles = fileInput && fileInput.files && fileInput.files.length > 0;

    if (!hasFiles && !linkValue) {
      event.preventDefault();
      fileInput.setCustomValidity('Upload at least one output attachment or provide a shared link.');
      fileInput.reportValidity();
      return false;
    }

    fileInput.setCustomValidity('');
  });

  $(document).on('submit', '.decline-ldr-form', function(event) {
    var form = $(this);
    var remarks = form.find('textarea[name="remarks"]');

    if (!$.trim(remarks.val())) {
      event.preventDefault();

      Swal.fire({
        icon: 'warning',
        title: 'Remarks required',
        text: 'Please enter the reason for declining this request.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545'
      }).then(function() {
        remarks.trigger('focus');
      });

      return false;
    }

    if (typeof show === 'function') {
      show();
    }
  });

  @if ($errors->has('remarks'))
  $(document).ready(function() {
    var requestId = @json(old('ldr_id'));

    if (requestId) {
      $('#declineLdr' + requestId).modal('show');
    }

    Swal.fire({
      icon: 'error',
      title: 'Remarks required',
      text: @json($errors->first('remarks')),
      confirmButtonText: 'OK',
      confirmButtonColor: '#dc3545'
    }).then(function() {
      if (requestId) {
        $('#declineLdr' + requestId)
          .find('textarea[name="remarks"]')
          .trigger('focus');
      }
    });
  });
  @endif

  @if ($errors->has('priority_level') || $errors->has('due_date') || $errors->has('additional_notes'))
  $(document).ready(function() {
    var requestId = @json(old('ldr_id'));

    if (requestId) {
      $('#processLdr' + requestId).modal('show');
    }
  });
  @endif

  @if ($errors->has('issuance_date') || $errors->has('closing_remarks') || $errors->has('output_attachments') || $errors->has('output_attachments.*') || $errors->has('output_attachment_link'))
  $(document).ready(function() {
    var requestId = @json(old('ldr_id'));

    if (requestId) {
      $('#closeLdr' + requestId).modal('show');
    }
  });
  @endif
</script>
@endsection
