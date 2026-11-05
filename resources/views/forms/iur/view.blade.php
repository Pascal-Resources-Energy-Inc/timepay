@php
  $showUniform = in_array($iur->request_for, ['Uniform', 'Uniform/Partial', 'Both']);
  $showID = in_array($iur->request_for, ['ID', 'Both']);
  $statusClass = [
    'Pending' => 'badge-warning',
    'Processing' => 'badge-primary',
    'Partial' => 'badge-info',
    'Released' => 'badge-success',
    'Declined' => 'badge-danger',
  ][$iur->status] ?? 'badge-secondary';
@endphp

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
        <h4 class="mb-0">{{ $iur->user->first_name }} {{ $iur->user->last_name }}</h4>
        <small class="text-muted font-weight-bold">
          {{ $iur->iur_reference }}
        </small>
      </div>

      <div class="d-flex align-items-center mb-2" style="gap: 8px;">
        <a href="{{ url('iur') }}" style="display: flex; align-items: center; width: fit-content;" class="btn btn-sm btn-light border">
          <i class="ti-arrow-left mr-1"></i> Back
        </a>
      </div>
    </div>

    <div id="accountability-data-{{ $iur->id }}"
        data-employee-name="{{ $iur->user->first_name }} {{ $iur->user->last_name }}"
        data-branch="{{ $iur->work_location }}"
        data-position="{{ $iur->user->position }}"
        data-date="{{ date('M d, Y', strtotime($iur->created_at)) }}"
        data-department="{{ $iur->user->department->name ?? '' }}"
        data-reference="{{ $iur->iur_reference }}"
        data-request-for="{{ $iur->request_for }}"
        data-size="{{ $iur->size }}"
        data-id-request="{{ $iur->id_request ?? '' }}"
        data-issued="{{ $iur->issued ?? '' }}"
        data-employee-code="{{ $iur->user->employee_code ?? '' }}"
        data-issued-remarks="{{ $iur->issued_remarks ?? '' }}"
        data-signature="{{ $iur->e_signature ? asset($iur->e_signature) : '' }}"
        style="display:none;"></div>

    {{-- RELEASE HISTORY --}}
    @if($iur->accountabilities->count() > 0)
    <div class="d-flex align-items-start mb-3 px-3 py-2" style="background: #edf1f8; border-left: 3px solid #6c8eab; border-radius: 4px; color: #4b5563; font-size: 13px;">
      <i class="ti-info-alt mr-2 mt-1 flex-shrink-0" style="color: #527694;"></i>
      <div style="min-width: 0; line-height: 1.45;">
        <strong class="d-block mb-1">Reference guide</strong>
        <div class="d-flex align-items-start mb-1">
          <span style="flex: 0 0 10px; width: 10px; height: 10px; margin-top: .28rem; margin-right: .45rem; border-radius: 2px; background: #d4edda; border: 1px solid #9fcbaa;"></span>
          <span>Green means the accountability is signed.</span>
        </div>
        <div class="d-flex align-items-start">
          <span style="flex: 0 0 10px; width: 10px; height: 10px; margin-top: .28rem; margin-right: .45rem; border-radius: 2px; background: #f8d7da; border: 1px solid #dfa7ac;"></span>
          <span>Red means the accountability is not yet signed.</span>
        </div>
      </div>
    </div>
    <div class="card border shadow-sm mb-3">
      <div class="card-header bg-secondary text-white py-2 d-flex align-items-center">
        <i class="ti-package mr-2"></i>
        <h6 class="mb-0 font-weight-bold">Release History</h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-bordered mb-0">
            <thead>
              <tr>
                <th class="px-1" style="width: 1%; white-space: nowrap;"></th>
                <th>Ref No.</th>
                <th>Date</th>
                <th>Prod</th>
                <th>White</th>
                <th>Black</th>
                <th>Polo</th>
                <th>ID</th>
                <th>Issued By</th>
              </tr>
            </thead>
            <tbody>
              @foreach($iur->accountabilities as $acc)
              @php
                $signed = !is_null($acc->signed_at);
                $accItems = [];
                if($acc->released_prod_qty > 0) $accItems[] = $acc->released_prod_qty.' Prod';
                if($acc->released_white_qty > 0) $accItems[] = $acc->released_white_qty.' White';
                if($acc->released_black_qty > 0) $accItems[] = $acc->released_black_qty.' Black';
                if($acc->released_collar_qty > 0) $accItems[] = $acc->released_collar_qty.' Polo';
                if($acc->released_id) $accItems[] = 'ID';
              @endphp
              <tr>
                <td class="text-center px-1 py-1 align-middle" style="width: 1%; white-space: nowrap;">
                  <div class="d-inline-flex align-items-center justify-content-center" style="gap: .25rem;">
                  <a href="{{ url('iur-accountability/' . $acc->id) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-info btn-sm d-inline-flex align-items-center justify-content-center"
                    style="width: 28px; height: 28px; padding: 0;"
                    title="View summary"
                    aria-label="View accountability summary {{ $acc->accountability_ref }}">
                    <i class="ti-eye"></i>
                  </a>
                  <a href="{{ url('iur-accountability-preview/' . $acc->id) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-primary btn-sm d-inline-flex align-items-center justify-content-center"
                    style="width: 28px; height: 28px; padding: 0;"
                    title="View accountability"
                    aria-label="View accountability {{ $acc->accountability_ref }}">
                    <i class="ti-file"></i>
                  </a>
                  @if(!$signed && in_array($iur->status, ['Processing', 'Partial', 'Released']))
                  <button type="button" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center"
                    style="width: 28px; height: 28px; padding: 0;"
                    title="Sign to acknowledge"
                    data-toggle="modal" data-target="#iur-signature-modal-{{ $iur->id }}"
                    data-acc-id="{{ $acc->id }}"
                    data-acc-sig="{{ $acc->employee_signature ? asset($acc->employee_signature) : '' }}"
                    data-acc-ref="{{ $acc->accountability_ref }}"
                    data-acc-date="{{ date('M d, Y', strtotime($acc->issued_date)) }}"
                    data-acc-items="{{ implode(', ', $accItems) }}">
                    <i class="ti-write"></i>
                  </button>
                  @endif
                  </div>
                </td>

                <!-- print  -->
                <!-- <td class="text-center p-1 align-middle">
                  <button type="button" class="btn btn-outline-secondary btn-sm p-1" title="Print this release"
                    onclick="printAccForm('view-form-{{ $iur->id }}', '{{ $iur->id }}', {ref:'{{ $acc->accountability_ref }}',prod:{{ $acc->released_prod_qty }},white:{{ $acc->released_white_qty }},black:{{ $acc->released_black_qty }},collar:{{ $acc->released_collar_qty }},id:{{ $acc->released_id ? 'true' : 'false' }},prodCondition:'{{ $acc->prod_condition ?? '' }}',whiteCondition:'{{ $acc->white_condition ?? '' }}',blackCondition:'{{ $acc->black_condition ?? '' }}',collarCondition:'{{ $acc->collar_condition ?? '' }}',signature:'{{ $acc->employee_signature ? asset($acc->employee_signature) : '' }}',signedDate:'{{ $acc->signed_at ? date('M d, Y', strtotime($acc->signed_at)) : '' }}'})">
                    <i class="ti-printer"></i>
                  </button>
                </td> -->

                @php $signed = !is_null($acc->signed_at); @endphp

                <td style="background-color: {{ $signed ? '#d4edda' : '#f8d7da' }};">{{ $acc->accountability_ref }}</td>
                <td>{{ date('M d, Y', strtotime($acc->issued_date)) }}</td>
                <td>{{ $acc->released_prod_qty > 0 ? $acc->released_prod_qty : '-' }}</td>
                <td>{{ $acc->released_white_qty > 0 ? $acc->released_white_qty : '-' }}</td>
                <td>{{ $acc->released_black_qty > 0 ? $acc->released_black_qty : '-' }}</td>
                <td>{{ $acc->released_collar_qty > 0 ? $acc->released_collar_qty : '-' }}</td>
                <td>{{ $acc->released_id ? 'Yes' : '-' }}</td>
                <td>{{ $acc->issuer->name ?? '-' }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @endif

    {{-- REQUEST INFO --}}
    <div class="card border shadow-sm mb-3">
      <div class="card-header rounded-top bg-light py-2 d-flex align-items-center">
        <i class="ti-info-alt text-primary mr-2"></i>
        <h6 class="mb-0 font-weight-bold">Request Information</h6>
        <span class="ml-auto badge {{ $statusClass }}">{{ $iur->status }}</span>
      </div>
      <div class="card-body">
        <table class="table table-sm table-borderless mb-0" style="font-size: 0.85rem;">
          <tbody>
            <tr>
              <td class="text-muted" style="width:35%;">Reference No.</td>
              <td class="font-weight-bold">{{ $iur->iur_reference }}</td>
            </tr>
            <tr>
              <td class="text-muted">Type</td>
              <td class="font-weight-bold">{{ $iur->type }}</td>
            </tr>
            <tr>
              <td class="text-muted">Work Location</td>
              <td class="font-weight-bold">{{ $iur->work_location }}</td>
            </tr>
            <tr>
              <td class="text-muted">Request For</td>
              <td class="font-weight-bold">{{ $iur->request_for }}</td>
            </tr>
            <tr>
              <td class="text-muted">Details of Work</td>
              <td class="font-weight-bold">{{ $iur->details }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- UNIFORM --}}
    @if($showUniform)
    <div class="card border shadow-sm mb-3">
      <div class="card-header rounded-top bg-light py-2">
        <i class="ti-layout-tab text-primary mr-1"></i>
        <h6 class="mb-0 font-weight-bold d-inline">Uniform Details</h6>
      </div>
      <div class="card-body">
        <table class="table table-sm table-borderless mb-0" style="font-size: 0.85rem;">
          <tbody>
            <tr>
              <td class="text-muted" style="width:35%;">Size</td>
              <td class="font-weight-bold">{{ $iur->size }}</td>
            </tr>
            <tr>
              <td class="text-muted">Issued Before</td>
              <td class="font-weight-bold">{{ $iur->issued ?? '-' }}</td>
            </tr>
            @if($iur->issued == 'Yes')
            <tr>
              <td class="text-muted">Issued Remarks</td>
              <td class="font-weight-bold">{{ $iur->issued_remarks ?? '-' }}</td>
            </tr>
            @endif
            <tr>
              <td class="text-muted">Reason</td>
              <td class="font-weight-bold">{{ $iur->issued_reasons ?? '-' }}</td>
            </tr>

            @if ($iur->notes)
            <tr>
              <td class="text-muted">Notes</td>
              <td class="font-weight-bold">{{ $iur->notes ?? '-' }}</td>
            </tr>
            @endif

            @php
              $uniformConditionProofs = $iur->uniformConditionImages->pluck('image_path');
              if ($uniformConditionProofs->isEmpty() && $iur->uniform_condition_image) {
                $uniformConditionProofs->push($iur->uniform_condition_image);
              }
            @endphp
            @if($uniformConditionProofs->isNotEmpty())
            <tr>
              <td class="text-muted align-top pt-3">Uniform Condition Proofs</td>
              <td class="pt-3">
                <div class="d-flex flex-wrap" style="gap: 10px;">
                @foreach($uniformConditionProofs as $proofPath)
                <a href="{{ asset($proofPath) }}" target="_blank" rel="noopener noreferrer" class="d-inline-block">
                  <img src="{{ asset($proofPath) }}"
                    alt="Current uniform condition proof"
                    style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 6px;">
                </a>
                @endforeach
                </div>
              </td>
            </tr>
            @endif
          </tbody>
        </table>

        <hr class="my-2">
        <small class="text-muted d-block mb-2 font-weight-bold">Requested Items:</small>
        <div class="row">
          @if($iur->prod_qty > 0)
          @php $complete = $iur->received_prod_qty >= $iur->prod_qty; @endphp
          <div class="col-6 col-md-3 mb-2">
            <div class="border rounded p-2 text-center" style="border-left: 4px solid {{ $complete ? '#28a745' : '#dee2e6' }} !important;{{ $complete ? ' background-color: #d4edda;' : '' }}">
              <small class="d-block font-weight-bold {{ $complete ? 'text-success' : 'text-muted' }}">Prod Shirt</small>
              <span class="h5 mb-0 font-weight-bold">{{ $iur->received_prod_qty }}/{{ $iur->prod_qty }}</span>
              @if($complete)<small class="d-block font-weight-bold text-success">Complete</small>@endif
            </div>
          </div>
          @endif
          @if($iur->white_qty > 0)
          @php $complete = $iur->received_white_qty >= $iur->white_qty; @endphp
          <div class="col-6 col-md-3 mb-2">
            <div class="border rounded p-2 text-center" style="border-left: 4px solid {{ $complete ? '#28a745' : '#dee2e6' }} !important;{{ $complete ? ' background-color: #d4edda;' : '' }}">
              <small class="d-block font-weight-bold {{ $complete ? 'text-success' : 'text-muted' }}">White</small>
              <span class="h5 mb-0 font-weight-bold {{ $complete ? 'text-success' : '' }}">{{ $iur->received_white_qty }}/{{ $iur->white_qty }}</span>
              @if($complete)<small class="d-block font-weight-bold text-success">Complete</small>@endif
            </div>
          </div>
          @endif
          @if($iur->black_qty > 0)
          @php $complete = $iur->received_black_qty >= $iur->black_qty; @endphp
          <div class="col-6 col-md-3 mb-2">
            <div class="border rounded p-2 text-center" style="border-left: 4px solid {{ $complete ? '#28a745' : '#dee2e6' }} !important;{{ $complete ? ' background-color: #d4edda;' : '' }}">
              <small class="d-block font-weight-bold {{ $complete ? 'text-success' : 'text-muted' }}">Black</small>
              <span class="h5 mb-0 font-weight-bold {{ $complete ? 'text-success' : '' }}">{{ $iur->received_black_qty }}/{{ $iur->black_qty }}</span>
              @if($complete)<small class="d-block font-weight-bold text-success">Complete</small>@endif
            </div>
          </div>
          @endif
          @if($iur->collar_qty > 0)
          @php $complete = $iur->received_collar_qty >= $iur->collar_qty; @endphp
          <div class="col-6 col-md-3 mb-2">
            <div class="border rounded p-2 text-center" style="border-left: 4px solid {{ $complete ? '#28a745' : '#dee2e6' }} !important;{{ $complete ? ' background-color: #d4edda;' : '' }}">
              <small class="d-block font-weight-bold {{ $complete ? 'text-success' : 'text-muted' }}">Polo</small>
              <span class="h5 mb-0 font-weight-bold {{ $complete ? 'text-success' : '' }}">{{ $iur->received_collar_qty }}/{{ $iur->collar_qty }}</span>
              @if($complete)<small class="d-block font-weight-bold text-success">Complete</small>@endif
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
    @endif

    {{-- ID --}}
    @if($showID)
    <div class="card border shadow-sm mb-3">
      <div class="card-header rounded-top bg-light py-2">
        <i class="ti-id-badge text-primary mr-1"></i>
        <h6 class="mb-0 font-weight-bold d-inline">ID Details</h6>
      </div>
      <div class="card-body py-2">
        <table class="table table-sm table-borderless mb-0" style="font-size: 0.85rem;">
          <tbody>
            <tr>
              <td class="text-muted" style="width:35%;">Reason</td>
              <td class="font-weight-bold">{{ $iur->id_request ?? '-' }}</td>
            </tr>
            <tr>
              <td class="text-muted align-top pt-3">ID Picture</td>
              <td class="pt-3">
                @if($iur->id_picture)
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                  <a href="{{ asset($iur->id_picture) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mb-2 mb-sm-0 mr-sm-3 flex-shrink-0">
                    <img src="{{ asset($iur->id_picture) }}"
                      alt="Uploaded ID photo"
                      style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 6px;">
                  </a>

                  <div style="min-width: 0; max-width: 100%;">
                    <small class="text-muted d-block text-truncate mb-2" style="max-width: 320px;">
                      {{ basename($iur->id_picture) }}
                    </small>

                    <div class="d-flex flex-wrap">
                      <a href="{{ asset($iur->id_picture) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="d-flex align-items-center btn btn-sm btn-outline-primary mr-2 mb-1">
                        <i class="ti-eye mr-1" style="margin-bottom: 0.20rem"></i> View
                      </a>
                      <a href="{{ asset($iur->id_picture) }}"
                        download
                        class="btn btn-sm btn-outline-secondary mb-1">
                        <i class="ti-download mr-1"></i> Download
                      </a>
                    </div>
                  </div>
                </div>
                @else
                <span class="text-muted fst-italic">No ID picture uploaded</span>
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    @endif

    @php
      $approverMessages = collect($iur->requestor_messages ?? [])->reverse()->values();
    @endphp

    @if ($approverMessages->isNotEmpty())
    <div class="card border shadow-sm mb-3">
      <div class="card-header rounded-top bg-light py-2 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
          <h6 class="mb-0 font-weight-bold">Action Required</h6>
        </div>
        <span class="badge badge-warning">{{ $approverMessages->count() }}</span>
      </div>
      <div class="card-body">
        @foreach ($approverMessages as $approverMessage)
        <div class="{{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
          <div class="text-dark" style="white-space: pre-wrap; word-break: break-word;">{{ data_get($approverMessage, 'message') }}</div>
          @if (data_get($approverMessage, 'sent_at'))
          <small class="text-muted d-block mt-2">
            Sent {{ \Carbon\Carbon::parse(data_get($approverMessage, 'sent_at'))->format('M. d, Y h:i A') }}
          </small>
          @endif
        </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- Notes --}}
    @if ($iur->approval_remarks)
    <div class="card border shadow-sm mb-3">
      <div class="card-header rounded-top bg-light py-2 d-flex align-items-center">
        <i class="ti-comment text-primary mr-2"></i>
        <h6 class="mb-0 font-weight-bold">Approver Remarks</h6>
      </div>
      <div class="card-body">
        <p class="mb-0 fw-bold">{{ $iur->approval_remarks ?? '-' }}</p>
      </div>
    </div>
    @endif

    <div class="text-right mt-4">
      <a href="{{ url('iur') }}" class="btn btn-light border px-4">
        Close
      </a>
    </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@include('for-approval.remarks.iur_signature_modal', ['form_approval' => $iur])
@include('for-approval.print-accountability-iur', ['form_approval' => $iur])

@endsection
