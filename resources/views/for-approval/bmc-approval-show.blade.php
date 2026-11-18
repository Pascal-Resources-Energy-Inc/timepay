@extends('layouts.header')
@section('css_header')
<style>
    .main-panel .card { z-index: auto; }
</style>
@endsection
@section('content')
@php
  $requiresDeposit = $borrowing->requiresDeposit();
  $requestedDepositTotal = $borrowing->borrowedItems
    ->sum(function ($item) use ($borrowing) {
      return $borrowing->depositRate($item->item_name) *
        (int) $item->requested_quantity;
    });
@endphp
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
              <div class="d-flex align-items-center justify-content-between mb-4">
                  <div>
                      <h4 class="mb-0">{{ $borrowing->borrower_first_name }} {{ $borrowing->borrower_last_name }}</h4>
                      <small class="text-muted font-weight-bold">
                          {{ $borrowing->reference_no }}
                      </small>
                  </div>

                  <a href="{{ url('/bmc-approval') }}" style="display: flex; align-items: center; width: fit-content;" class="btn btn-sm btn-light border mb-2">
                      <i class="ti-arrow-left mr-1"></i> Back
                  </a>
              </div>

            <style>
              .bmc-section {
                border: 1px solid #e3e6ed;
                border-radius: .6rem;
                margin-bottom: 1.4rem;
                overflow: hidden;
              }
              .bmc-section-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: .9rem 1.2rem;
                background: #f8f9fc;
                border-bottom: 1px solid #e3e6ed;
              }
              .bmc-section-header h5 {
                margin: 0;
                font-size: .95rem;
                font-weight: 600;
              }
              .bmc-section-header h5 i {
                color: #007bff;
              }
              .bmc-section-body {
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
              .bmc-reference-guide {
                min-width: 0;
                line-height: 1.45;
              }
              .bmc-reference-guide-item {
                display: flex;
                align-items: flex-start;
              }
              .bmc-reference-guide-swatch {
                flex: 0 0 10px;
                width: 10px;
                height: 10px;
                margin-top: .28rem;
                margin-right: .45rem;
                border-radius: 2px;
              }
              .bmc-refund-summary {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                padding: 1rem 1.2rem;
                margin-bottom: 1.3rem;
                background: #f7f8fc;
                border-left: 3px solid #4b49ac;
              }
              .bmc-refund-summary strong {
                color: #343177;
                font-size: 1.25rem;
                white-space: nowrap;
              }
              .bmc-refund-form label { font-weight: 600; font-size: .875rem; margin-bottom: .45rem; }
              .bmc-refund-fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem; max-width: 960px; }
              .bmc-refund-fields .form-group { margin-bottom: 1.15rem; min-width: 0; }
              .bmc-refund-file { width: 100%; min-height: 42px; padding: .4rem; border: 1px solid #d5d9e2; border-radius: 4px; font-size: .85rem; color: #555d6b; }
              .bmc-refund-file::file-selector-button { margin-right: .75rem; padding: .35rem .7rem; border: 0; border-radius: 3px; background: #eceef5; color: #343b4a; cursor: pointer; }
              .bmc-refund-footer { border-top: 1px solid #e8eaf0; padding-top: 1rem; display: flex; justify-content: flex-end; }
              @media (max-width: 767.98px) {
                .bmc-refund-fields { grid-template-columns: 1fr; gap: 0; }
                .bmc-refund-footer .btn { width: 100%; }
                .bmc-section-body {
                  overflow-x: auto;
                  -webkit-overflow-scrolling: touch;
                }
                .bmc-section-body .info-table {
                  min-width: 520px;
                }
              }
            </style>

            @if ($requiresDeposit && $borrowing->status === \App\MarketingCollateralBorrowing::STATUS_CLOSED)
              <div class="bmc-section">
                <div class="bmc-section-header" style="background: #edf1f8; color: #4b5563;"><h5>Deposit Refund</h5></div>
                <div class="bmc-section-body">
                  <div class="bmc-refund-summary">
                    <div><span class="font-weight-bold d-block">Total deposit</span><small class="text-muted">For all released items</small></div>
                    <strong>₱{{ number_format($borrowing->accountabilities->sum(function ($accountability) { return $accountability->items->sum('deposit_amount'); }), 2) }}</strong>
                  </div>
                  @if ($borrowing->proof_of_refund && $borrowing->refund_payment_method)
                    <p class="mb-1">Refund payment method: <strong>{{ $borrowing->refund_payment_method }}</strong></p>
                    <a href="{{ url('/bmc-refund-proof/' . $borrowing->id) }}" target="_blank" rel="noopener">View proof of refund</a>
                  @else
                    <p class="text-muted mb-3">Once the deposit has been returned, provide the refund details below.</p>
                    <form class="bmc-refund-form" method="POST" action="{{ url('/bmc-refund/' . $borrowing->id) }}" enctype="multipart/form-data" onsubmit="if (this.dataset.submitting === '1') return false; this.dataset.submitting = '1'; var button = this.querySelector('button[type=submit]'); button.disabled = true; button.textContent = 'Saving…';">
                      @csrf
                      <div class="bmc-refund-fields">
                      <div class="form-group">
                        <label for="refund_payment_method">Refund payment method</label>
                        <select id="refund_payment_method" name="refund_payment_method" class="form-control" required>
                          <option value="">Select a payment method</option>
                          @foreach (['Cash', 'GCash', 'Bank Transfer'] as $method)
                            <option value="{{ $method }}" {{ old('refund_payment_method') === $method ? 'selected' : '' }}>{{ $method }}</option>
                          @endforeach
                        </select>
                        @if ($errors->has('refund_payment_method')) <small class="text-danger">{{ $errors->first('refund_payment_method') }}</small> @endif
                      </div>
                      <div class="form-group">
                        <label for="proof_of_refund">Proof of refund</label>
                        <input id="proof_of_refund" name="proof_of_refund" type="file" class="bmc-refund-file" accept=".jpg,.jpeg,.png,.pdf" required>
                        <small class="text-muted d-block mt-1">Accepted: JPG, PNG or PDF (up to 5 MB)</small>
                        @if ($errors->has('proof_of_refund')) <small class="text-danger d-block">{{ $errors->first('proof_of_refund') }}</small> @endif
                      </div>
                      </div>
                      <div class="bmc-refund-footer">
                      <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  @endif
                </div>
              </div>
            @endif

            @if ($borrowing->accountabilities->count() > 0)
              <div class="d-flex align-items-start mb-2 px-3 py-2" style="background: #edf1f8; border-left: 3px solid #6c8eab; border-radius: 4px; color: #4b5563; font-size: 13px;">
                <i class="ti-info-alt mr-2 mt-1 flex-shrink-0" style="color: #527694;"></i>
                <div class="bmc-reference-guide">
                  <strong class="d-block mb-1">Reference guide</strong>
                  <div class="bmc-reference-guide-item mb-1">
                    <span class="bmc-reference-guide-swatch" style="background: #d4edda; border: 1px solid #9fcbaa;"></span>
                    <span>Green means the borrower has signed.</span>
                  </div>
                  <div class="bmc-reference-guide-item">
                    <span class="bmc-reference-guide-swatch" style="background: #f8d7da; border: 1px solid #dfa7ac;"></span>
                    <span>Red means the borrower's signature is still pending.</span>
                  </div>
                </div>
              </div>
              <div class="card mb-3">
                <div class="card-header bg-secondary text-white py-2">
                  <h6 class="mb-0">Release History</h6>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                      <thead class="table-bordered">
                        <tr>
                          <th style="width: 104px;"></th>
                          <th>Ref</th>
                          <th>Released At</th>
                          <th>Signed At</th>
                          <th>Released By</th>
                          <th>Expected Return</th>
                          <!-- <th style="width: 190px; min-width: 190px;">Issuance Notes</th> -->
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($borrowing->accountabilities as $accountability)
                          @php
                            $signed = !is_null($accountability->signed_at);

                            $expectedReturnNeedsAttention =
                                $accountability->expected_return_at &&
                                in_array($accountability->status, [
                                    \App\BmcAccountability::STATUS_RELEASED,
                                    \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION,
                                    \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
                                ], true) &&
                                $accountability->expected_return_at
                                    ->copy()
                                    ->startOfDay()
                                    ->lte(today());

                            $maximumExpectedReturnDate =
                                $accountability->released_at
                                    ? $accountability->released_at
                                        ->copy()
                                        ->startOfDay()
                                        ->addDays(40)
                                    : null;
                            $canEditExpectedReturn =
                                $accountability->released_at &&
                                in_array($accountability->status, [
                                    \App\BmcAccountability::STATUS_RELEASED,
                                    \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
                                ], true) &&
                                today()->lte($maximumExpectedReturnDate);
                            $expectedReturnEditUnavailableReason = null;

                            if (!$accountability->released_at) {
                                $expectedReturnEditUnavailableReason =
                                    'The release date is unavailable.';
                            } elseif (!in_array($accountability->status, [
                                \App\BmcAccountability::STATUS_RELEASED,
                                \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
                            ], true)) {
                                $expectedReturnEditUnavailableReason =
                                    'Expected return is read-only for this status.';
                            } elseif (today()->gt($maximumExpectedReturnDate)) {
                                $expectedReturnEditUnavailableReason =
                                    'The 40-day editing window has ended.';
                            }
                          @endphp
                          <tr>
                            <td class="text-center" style="padding: 2px; white-space: nowrap;">
                              <button
                                type="button"
                                class="btn btn-info p-1"
                                title="View summary"
                                data-toggle="modal"
                                data-target="#viewBmcAccountability{{ $accountability->id }}"
                              >
                                <i class="ti-eye"></i>
                              </button>
                              <button
                                type="button"
                                class="btn btn-primary p-1"
                                title="Print this release"
                                onclick="window.open('{{ url('/bmc-accountability-print/' . $accountability->id) }}', '_blank')"
                              >
                                <i class="ti-printer"></i>
                              </button>
                              @if (
                                $accountability->status ===
                                  \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION &&
                                $accountability->borrower_signature
                              )
                                <button
                                  type="button"
                                  class="btn btn-warning p-1"
                                  title="Confirm return"
                                  data-toggle="modal"
                                  data-target="#closeBmcAccountability{{ $accountability->id }}"
                                >
                                  <i class="ti-check-box"></i>
                                </button>
                              @endif
                            </td>
                            <td style="background-color: {{ $signed ? '#d4edda' : '#f8d7da' }}; white-space: nowrap;">
                              {{ $accountability->accountability_ref }}
                            </td>
                            <td style="white-space: nowrap;">
                              {{ optional($accountability->released_at)->format('M d, Y g:i A') ?? '-' }}
                            </td>
                            <td style="white-space: nowrap;">
                              {{ optional($accountability->signed_at)->format('M d, Y g:i A') ?? '-' }}
                            </td>
                            <td style="white-space: nowrap;">{{ optional($accountability->releasedBy)->name ?? '-' }}</td>
                            <td style="white-space: nowrap; {{ $expectedReturnNeedsAttention ? 'background-color: #f8d7da; color: #721c24; font-weight: 600;' : '' }}">
                              @if ($canEditExpectedReturn)
                                <button
                                  type="button"
                                  class="btn btn-link border-0 p-0"
                                  style="color: inherit; font-size: inherit; font-weight: inherit; text-decoration: underline;"
                                  title="Edit expected return"
                                  aria-label="Edit expected return for {{ $accountability->accountability_ref }}"
                                  data-toggle="modal"
                                  data-target="#editBmcExpectedReturn{{ $accountability->id }}"
                                >
                                  {{ optional($accountability->expected_return_at)->format('M d, Y') ?? 'Set date' }}
                                  <i class="ti-pencil-alt ml-1" aria-hidden="true"></i>
                                </button>
                              @else
                                <span title="{{ $expectedReturnEditUnavailableReason }}">
                                  {{ optional($accountability->expected_return_at)->format('M d, Y') ?? '-' }}
                                </span>
                              @endif
                              @if ($accountability->extensions->isNotEmpty())
                                @php
                                  $pendingExtension = $accountability->extensions->firstWhere(
                                    'status', \App\BmcAccountabilityExtension::STATUS_PENDING
                                  );
                                  $reviewError = ($errors->has('decision') || $errors->has('remarks')) &&
                                    $accountability->extensions->contains(function ($extension) {
                                      return (string) $extension->id === (string) old('extension_id');
                                    });
                                @endphp
                                <button type="button"
                                  class="btn btn-sm d-block mt-2 {{ $pendingExtension ? 'btn-warning' : 'btn-light border' }}"
                                  data-toggle="modal" data-target="#bmcExtensions{{ $accountability->id }}"
                                  aria-label="{{ $pendingExtension ? 'Review pending extension' : 'View extension history' }} for {{ $accountability->accountability_ref }}">
                                  {{ $pendingExtension ? 'Review extension' : 'Extension history' }}
                                </button>
                                @if ($pendingExtension)
                                  <small class="d-block text-muted mt-1">
                                    Requested {{ $pendingExtension->requested_return_date->format('M d, Y') }}
                                  </small>
                                @endif
                                @if ($reviewError)
                                  <small class="d-block text-danger mt-1" role="alert" style="white-space: normal;">
                                    {{ $errors->first('decision') ?: $errors->first('remarks') }}
                                  </small>
                                @endif
                              @endif
                            </td>
                            <td>
                              <span class="badge {{ $accountability->status === \App\BmcAccountability::STATUS_PARTIALLY_RETURNED ? 'text-dark' : 'text-white' }} badge-{{ $accountability->status === \App\BmcAccountability::STATUS_CLOSED ? 'secondary' : ($accountability->status === \App\BmcAccountability::STATUS_PARTIALLY_RETURNED ? 'warning' : ($accountability->status === \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION ? 'info' : 'success')) }}">
                                {{ $accountability->status }}
                              </span>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              @foreach ($borrowing->accountabilities as $accountability)
                @if ($accountability->extensions->isNotEmpty())
                  <div class="modal fade" id="bmcExtensions{{ $accountability->id }}" tabindex="-1"
                    role="dialog" aria-labelledby="bmcExtensionsTitle{{ $accountability->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content border-0">
                        <div class="modal-header">
                          <div>
                            <h5 class="modal-title" id="bmcExtensionsTitle{{ $accountability->id }}">Extension Request</h5>
                            <small class="text-white">{{ $accountability->accountability_ref }}</small>
                          </div>
                          <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          @foreach ($accountability->extensions as $extension)
                            @php
                              $isPending = $extension->status === \App\BmcAccountabilityExtension::STATUS_PENDING;
                              $isReviewRetry = (string) old('extension_id') === (string) $extension->id;
                              $statusColor = [
                                \App\BmcAccountabilityExtension::STATUS_PENDING => 'warning',
                                \App\BmcAccountabilityExtension::STATUS_APPROVED => 'success',
                                \App\BmcAccountabilityExtension::STATUS_DECLINED => 'danger',
                              ][$extension->status] ?? 'secondary';
                            @endphp
                            <article class="border rounded p-3 {{ $loop->last ? '' : 'mb-3' }}">
                              <div class="d-flex flex-wrap align-items-start justify-content-between bg-light rounded px-3 pt-2 mb-3">
                                <div class="mr-3 mb-2">
                                  <small class="d-block text-muted">{{ $isPending ? 'Current due date' : 'Previous due date' }}</small>
                                  <strong>{{ optional($isPending ? $accountability->expected_return_at : $extension->current_return_date)->format('M d, Y') ?? 'Not set' }}</strong>
                                </div>
                                <div class="mr-3 mb-2">
                                  <small class="d-block text-muted">Requested date</small>
                                  <strong>{{ $extension->requested_return_date->format('M d, Y') }}</strong>
                                </div>
                                <span class="badge badge-{{ $statusColor }} mb-2">{{ ucfirst($extension->status) }}</span>
                              </div>
                              <div class="mb-3">
                                <small class="d-block text-muted mb-1">Reason for extension</small>
                                <p class="text-break mb-0" style="white-space: pre-wrap;">{{ $extension->reason }}</p>
                              </div>
                              @if ($isPending)
                                <form method="POST" action="{{ route('bmc.extensions.review', $extension->id) }}" data-bmc-extension-review>
                                  @csrf
                                  <input type="hidden" name="extension_id" value="{{ $extension->id }}">
                                  <input type="hidden" name="decision" data-bmc-extension-decision disabled>
                                  @if ($isReviewRetry && ($errors->has('decision') || $errors->has('remarks')))
                                    <div class="alert alert-danger py-2" role="alert">
                                      {{ $errors->first('decision') ?: $errors->first('remarks') }}
                                    </div>
                                  @endif
                                  <div class="form-group">
                                    <label for="bmcExtensionRemarks{{ $extension->id }}">Remarks <span><small>(optional)</small></span></label>
                                    <textarea id="bmcExtensionRemarks{{ $extension->id }}" name="remarks"
                                      class="form-control" rows="2">{{ $isReviewRetry ? old('remarks') : '' }}</textarea>
                                  </div>
                                  <div class="d-flex flex-wrap justify-content-end">
                                    <button type="submit" name="decision" value="decline" class="btn btn-danger btn-sm mr-2 mb-1">Decline</button>
                                    <button type="submit" name="decision" value="approve" class="btn btn-success btn-sm mb-1">Approve extension</button>
                                  </div>
                                </form>
                              @else
                                <p class="text-muted small mb-0">
                                  Reviewed by {{ optional($extension->reviewedBy)->name ?? 'MBD' }}
                                  on {{ $extension->updated_at->format('M d, Y') }}
                                </p>
                                @if (filled($extension->remarks))
                                  <p class="text-break mt-2 mb-0" style="white-space: pre-wrap;">{{ $extension->remarks }}</p>
                                @endif
                              @endif
                            </article>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
                @php
                  $maximumExpectedReturnDate = $accountability->released_at
                    ? $accountability->released_at
                        ->copy()
                        ->startOfDay()
                        ->addDays(40)
                    : null;
                  $minimumExpectedReturnDate = $accountability->released_at
                    ? $accountability->released_at
                        ->copy()
                        ->startOfDay()
                        ->addDay()
                    : null;

                  if (
                    $minimumExpectedReturnDate &&
                    today()->gt($minimumExpectedReturnDate)
                  ) {
                    $minimumExpectedReturnDate = today();
                  }

                  $canEditExpectedReturn =
                    $accountability->released_at &&
                    in_array($accountability->status, [
                      \App\BmcAccountability::STATUS_RELEASED,
                      \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
                    ], true) &&
                    today()->lte($maximumExpectedReturnDate);
                @endphp

                @if ($canEditExpectedReturn)
                  <div
                    class="modal fade"
                    id="editBmcExpectedReturn{{ $accountability->id }}"
                    tabindex="-1"
                    role="dialog"
                    aria-labelledby="editBmcExpectedReturnTitle{{ $accountability->id }}"
                    aria-hidden="true"
                  >
                    <div class="modal-dialog" role="document">
                      <form
                        method="POST"
                        action="{{ route('bmc.accountability.expected-return.update', $accountability->id) }}"
                        onsubmit="show();"
                      >
                        @csrf
                        <div class="modal-content border-0">
                          <div class="modal-header">
                            <div>
                              <h5
                                class="modal-title"
                                id="editBmcExpectedReturnTitle{{ $accountability->id }}"
                              >
                                Edit Expected Return
                              </h5>
                              <small class="text-white">
                                {{ $accountability->accountability_ref }}
                              </small>
                            </div>
                            <button
                              type="button"
                              class="btn-close btn-danger"
                              data-dismiss="modal"
                              aria-label="Close"
                            >
                              <span aria-hidden="true" class="text-white">&times;</span>
                            </button>
                          </div>

                          <div class="modal-body">
                            <div class="form-group mb-2">
                              <label
                                class="font-weight-bold"
                                for="bmcExpectedReturn{{ $accountability->id }}"
                              >
                                Expected Return
                              </label>
                              <input
                                type="date"
                                class="form-control"
                                id="bmcExpectedReturn{{ $accountability->id }}"
                                name="expected_return_at"
                                value="{{ optional($accountability->expected_return_at)->format('Y-m-d') }}"
                                min="{{ $minimumExpectedReturnDate->format('Y-m-d') }}"
                                max="{{ $maximumExpectedReturnDate->format('Y-m-d') }}"
                                required
                              >
                            </div>
                            <small class="text-muted d-block">
                              Select a date from
                              {{ $minimumExpectedReturnDate->format('M d, Y') }}
                              to
                              {{ $maximumExpectedReturnDate->format('M d, Y') }}.
                            </small>
                            <small class="text-muted d-block mt-1">
                              Maximum: 40 days
                            </small>
                          </div>

                          <div class="modal-footer">
                            <button
                              type="button"
                              class="btn btn-light border"
                              data-dismiss="modal"
                            >
                              Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                              Save Date
                            </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                @endif

                <div
                  class="modal fade"
                  id="viewBmcAccountability{{ $accountability->id }}"
                  tabindex="-1"
                  role="dialog"
                  aria-labelledby="viewBmcAccountabilityTitle{{ $accountability->id }}"
                  aria-hidden="true"
                >
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content border-0">
                      <div class="modal-header bg-info text-white">
                        <div>
                          <h5 class="modal-title" id="viewBmcAccountabilityTitle{{ $accountability->id }}">
                            Request Details
                          </h5>
                          <small>{{ $accountability->accountability_ref }}</small>
                        </div>
                        <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true" class="text-white">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <div class="table-responsive">
                          <table class="table rounded table-bordered table-sm mb-3">
                            <thead class="bg-light">
                              <tr>
                                <th>Borrowed Item(s)</th>
                                <th class="text-center">Released</th>
                                <th class="text-center">Returned</th>
                                <th class="text-center">Remaining</th>
                                <th>Serial No.</th>
                                @if ($requiresDeposit)
                                  <th class="text-right">Deposit</th>
                                @endif
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($accountability->items as $item)
                                <tr>
                                  <td>{{ $item->item_name }}</td>
                                  <td class="text-center">{{ $item->released_quantity ?: 1 }}</td>
                                  <td
                                    class="text-center"
                                    style="{{ (int) ($item->returned_quantity ?? 0) >= (int) ($item->released_quantity ?: 1) ? 'background-color: #d4edda; color: #155724; font-weight: 600;' : '' }}"
                                  >{{ (int) ($item->returned_quantity ?? 0) }}</td>
                                  <td
                                    class="text-center"
                                    style="{{ max((int) ($item->released_quantity ?: 1) - (int) ($item->returned_quantity ?? 0), 0) > 0 ? 'background-color: #f8d7da; color: #721c24; font-weight: 600;' : '' }}"
                                  >{{ max((int) ($item->released_quantity ?: 1) - (int) ($item->returned_quantity ?? 0), 0) }}</td>
                                  <td>{{ $item->serial_number ?? '-' }}</td>
                                  @if ($requiresDeposit)
                                    <td class="text-right" style="white-space: nowrap;">
                                      ₱{{ number_format((float) $item->deposit_amount, 2) }}
                                    </td>
                                  @endif
                                </tr>
                              @endforeach
                            </tbody>
                            @if ($requiresDeposit)
                              <tfoot class="bg-light">
                                <tr>
                                  <th colspan="5" class="text-right">Total Deposit</th>
                                  <th class="text-right" style="white-space: nowrap;">
                                    ₱{{ number_format($accountability->items->sum('deposit_amount'), 2) }}
                                  </th>
                                </tr>
                              </tfoot>
                            @endif
                          </table>
                          @if ($requiresDeposit && $accountability->payment_method)
                            <div class="mt-2 mb-3">
                              <strong>Payment method:</strong> {{ $accountability->payment_method }}
                              @if ($accountability->proof_of_transfer)
                                <a class="ml-2" href="{{ url('/bmc-accountability-payment-proof/' . $accountability->id) }}" target="_blank" rel="noopener">View proof of transfer</a>
                              @endif
                            </div>
                          @endif
                        </div>

                        <div class="card border shadow-none mb-3">
                          <div class="card-header rounded-top bg-light py-2">
                            <h6 class="font-weight-bold mb-0">Return History</h6>
                          </div>
                          <div class="card-body p-0">
                            <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                              <table class="table table-bordered table-sm mb-0" style="min-width: 1240px;">
                                <thead class="bg-light">
                                  <tr>
                                    <th style="width: 140px;">Date Returned</th>
                                    <th style="min-width: 250px;">Item</th>
                                    <th class="text-center" style="width: 90px;">Quantity</th>
                                    <th style="min-width: 220px;">Serial Number(s)</th>
                                    <th style="min-width: 170px;">Received By</th>
                                    <th style="min-width: 260px; width: 260px;">Mode of Return</th>
                                    <th style="min-width: 260px; width: 260px;">Borrower Remarks</th>
                                    <th style="min-width: 220px;">Approver Remarks</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @forelse ($accountability->returnHistory as $returnEntry)
                                    <tr>
                                      <td style="white-space: nowrap;">{{ optional($returnEntry->returned_at)->format('M d, Y') ?? '-' }}</td>
                                      <td>{{ optional($returnEntry->item)->item_name ?? '-' }}</td>
                                      <td class="text-center">{{ $returnEntry->returned_quantity }}</td>
                                      <td style="white-space: pre-wrap;">{{ $returnEntry->serial_numbers ?? '-' }}</td>
                                      <td>{{ optional($returnEntry->receivedBy)->name ?? '-' }}</td>
                                      <td style="min-width: 260px; white-space: pre-wrap; word-break: break-word; vertical-align: top;">{{ $returnEntry->mor ?? '-' }}</td>
                                      <td style="min-width: 260px; word-break: break-word; vertical-align: top;">
                                        <div style="white-space: pre-wrap;">{{ $returnEntry->return_remarks ? preg_replace('/^Borrower remarks:\s*/i', '', $returnEntry->return_remarks) : '-' }}</div>
                                        @foreach ($returnEntry->attachment_paths as $path)
                                          <a href="{{ asset($path) }}" target="_blank" rel="noopener noreferrer" class="d-block mt-1">
                                            View attachment{{ $loop->count > 1 ? ' ' . $loop->iteration : '' }}
                                          </a>
                                        @endforeach
                                      </td>
                                      <td style="white-space: pre-wrap; word-break: break-word; vertical-align: top;">{{ $returnEntry->approver_remarks ?? '-' }}</td>
                                    </tr>
                                  @empty
                                    <tr>
                                      <td colspan="8" class="text-center text-muted">No return history recorded yet.</td>
                                    </tr>
                                  @endforelse
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>

                        <div class="row mb-3">
                          <div class="col-12 mb-3">
                            <div class="card h-100 border shadow-none">
                              <div class="card-header rounded-top bg-light py-2">
                                <h6 class="font-weight-bold mb-0">Release Details</h6>
                              </div>
                              <div class="card-body p-3">
                                <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                                <table class="table table-sm table-borderless mb-0" style="min-width: 620px;">
                                  <tr>
                                    <td class="text-muted font-weight-bold" style="width: 42%;">Released At</td>
                                    <td>{{ optional($accountability->released_at)->format('M d, Y g:i A') ?? '-' }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-muted font-weight-bold">Released By</td>
                                    <td>{{ optional($accountability->releasedBy)->name ?? '-' }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-muted font-weight-bold">Expected Return</td>
                                    <td style="{{ $accountability->expected_return_at && in_array($accountability->status, [\App\BmcAccountability::STATUS_RELEASED, \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION, \App\BmcAccountability::STATUS_PARTIALLY_RETURNED], true) && $accountability->expected_return_at->copy()->startOfDay()->lte(today()) ? 'background-color: #f8d7da; color: #721c24; font-weight: 600;' : '' }}">
                                      {{ optional($accountability->expected_return_at)->format('M d, Y') ?? '-' }}
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="text-muted font-weight-bold">Signed At</td>
                                    <td>{{ optional($accountability->signed_at)->format('M d, Y g:i A') ?? '-' }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-muted font-weight-bold">Issuance</td>
                                    <td style="white-space: pre-wrap;">{{ $accountability->release_notes ?? '-' }}</td>
                                  </tr>
                                </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-12">
                            <div class="card h-100 border shadow-none">
                              <div class="card-header rounded-top bg-light py-2">
                                <h6 class="font-weight-bold mb-0">Closure Details</h6>
                              </div>
                              <div class="card-body p-3">
                                <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                                <table class="table table-sm table-borderless mb-0" style="min-width: 620px;">
                                  <tr>
                                    <td class="text-muted font-weight-bold" style="width: 42%;">Status</td>
                                    <td>
                                      <span class="badge {{ $accountability->status === \App\BmcAccountability::STATUS_PARTIALLY_RETURNED ? 'text-dark' : 'text-white' }} badge-{{ $accountability->status === \App\BmcAccountability::STATUS_CLOSED ? 'secondary' : ($accountability->status === \App\BmcAccountability::STATUS_PARTIALLY_RETURNED ? 'warning' : ($accountability->status === \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION ? 'info' : 'success')) }}">
                                        {{ $accountability->status }}
                                      </span>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="text-muted font-weight-bold">Closed By</td>
                                    <td>{{ optional($accountability->closedBy)->name ?? '-' }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-muted font-weight-bold">Closed At</td>
                                    <td>{{ optional($accountability->closed_at)->format('M d, Y g:i A') ?? '-' }}</td>
                                  </tr>
                                  <!-- @if ($accountability->closing_remarks) -->
                                  <!--   <tr> -->
                                  <!--     <td class="text-muted font-weight-bold">Borrower's Return Remarks</td> -->
                                  <!--     <td style="white-space: pre-wrap;">{{ $accountability->closing_remarks }}</td> -->
                                  <!--   </tr> -->
                                  <!-- @endif -->
                                  <!-- <tr> -->
                                  <!--   <td class="text-muted font-weight-bold">Mode of Return</td> -->
                                  <!--   <td>{{ $accountability->mor ?? '-'}}</td> -->
                                  <!-- </tr> -->
                                  <!-- <tr> -->
                                  <!--   <td class="text-muted font-weight-bold">Return Remarks</td> -->
                                  <!--   <td style="white-space: pre-wrap;">{{ $accountability->closing_remarks ?? '-' }}</td> -->
                                  <!-- </tr> -->
                                </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>

                      <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                @if (
                  $accountability->status ===
                    \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION &&
                  $accountability->borrower_signature
                )
                  @include(
                    'for-approval.remarks.bmc_close_modal',
                    ['accountability' => $accountability]
                  )
                @endif
              @endforeach
            @endif

            <div class="bmc-section">
              <div class="bmc-section-header">
                <h5><i class="ti-user mr-2"></i>I. Borrower's Information</h5>
              </div>
              <div class="bmc-section-body">
                <table class="table info-table">
                  <tr><td>Borrower's Profile</td><td class="text-primary" style="font-weight: bold;">{{ $borrowing->borrower_profile }}</td></tr>
                  <tr><td>Borrower's Full Name</td><td>{{ $borrowing->borrower_first_name }} {{ $borrowing->borrower_last_name }}</td></tr>
                  @if ($borrowing->department) <tr><td>Department</td><td>{{ $borrowing->department ?? '-'}}</td></tr> @endif
                  @if ($borrowing->business_name) <tr><td>Business Name</td><td>{{ $borrowing->business_name ?? '-'}}</td></tr> @endif
                  @if ($borrowing->designation) <tr><td>Designation</td><td>{{ $borrowing->designation ?? '-'}}</td></tr> @endif
                  <tr><td>Email</td><td>{{ $borrowing->email }}</td></tr>
                  @if ($borrowing->hub_code) <tr><td>Hub Code</td><td>{{ $borrowing->hub_code ?? '-'}}</td></tr> @endif
                  @if ($borrowing->hub_name) <tr><td>Hub Name</td><td>{{ $borrowing->hub_name ?? '-'}}</td></tr> @endif
                  <tr><td>Phone Number</td><td>{{ $borrowing->phone_number }}</td></tr>
                </table>
              </div>
            </div>

            <div class="bmc-section">
              <div class="bmc-section-header">
                <h5><i class="ti-calendar mr-2"></i>II. Event Details</h5>
              </div>
              <div class="bmc-section-body">
                <table class="table info-table">
                  <tr><td>Date Needed</td><td>{{ optional($borrowing->date_needed)->format('F j, Y') }}</td></tr>
                  <tr><td>Event Name</td><td>{{ $borrowing->event_name }}</td></tr>
                  <tr><td>Request Details</td><td>{{ $borrowing->request_details }}</td></tr>
                  <tr><td>No. of Days Item is Needed</td><td>{{ $borrowing->number_of_days_item_needed }} day(s)</td></tr>
                </table>
              </div>
            </div>

            <div class="bmc-section">
              <div class="bmc-section-header">
                <h5><i class="ti-gift mr-2"></i>III. Item for Borrowing</h5>
              </div>
              <div class="bmc-section-body">
                <table class="table info-table">
                  @foreach ($borrowing->borrowedItems as $item)
                  <tr>
                    <td>Item</td>
                    <td>
                      {{ $item->item_name }} &times; {{ $item->requested_quantity }}
                      @if ($requiresDeposit)
                        <small class="text-muted d-block">
                          ₱{{ number_format($borrowing->depositRate($item->item_name), 2) }}
                          per item &middot;
                          ₱{{ number_format($borrowing->depositRate($item->item_name) * (int) $item->requested_quantity, 2) }}
                        </small>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                  @if ($requiresDeposit)
                    <tr class="bg-light">
                      <td>Total Deposit Value</td>
                      <td class="font-weight-bold">
                        ₱{{ number_format($requestedDepositTotal, 2) }}
                        <small class="text-muted font-weight-normal d-block">
                          Based on the quantities requested.
                        </small>
                      </td>
                    </tr>
                  @endif
                </table>
              </div>
            </div>

            <div class="bmc-section">
              <div class="bmc-section-header">
                <h5><i class="ti-check-box mr-2"></i>IV. Approval Details</h5>
              </div>
              <div class="bmc-section-body">
                <table class="table info-table">
                  <tr><td>Status</td><td><span class="badge badge-{{ $borrowing->status == 'Approved' ? 'success' : ($borrowing->status == 'Declined' ? 'danger' : ($borrowing->status == 'Cancelled' ? 'secondary' : 'warning')) }}">{{ $borrowing->status }}</span></td></tr>
                  @if ($borrowing->processed_by)
                    @php $approver_name = \App\User::find($borrowing->processed_by); @endphp
                    <tr><td>Approved / Declined By</td><td>{{ $approver_name ? $approver_name->name : $borrowing->processed_by }}</td></tr>
                  @endif
                  @if ($borrowing->processed_at)
                    <tr><td>Processed At</td><td>{{ date('M d, Y h:i A', strtotime($borrowing->processed_at)) }}</td></tr>
                  @endif
                  @if ($borrowing->mbd_remarks)
                    <tr><td>Remarks</td><td>{{ $borrowing->mbd_remarks }}</td></tr>
                  @endif
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('submit', function (event) {
    var form = event.target;

    if (!form.hasAttribute('data-bmc-extension-review')) {
      return;
    }

    var decision = form.querySelector('[data-bmc-extension-decision]');
    var submitButton = event.submitter;

    if (!decision.disabled || !submitButton || submitButton.name !== 'decision') {
      event.preventDefault();
      return;
    }

    decision.value = submitButton.value;
    decision.disabled = false;

    form.querySelectorAll('button[name="decision"]').forEach(function (button) {
      button.disabled = true;
    });

    submitButton.textContent = 'Submitting…';
  });

  var bmcAccountabilityNoteTypes = [
    'Face to face',
    'Near location',
    'Field location',
    'Pickup',
    'Courier',
    'Officer Name'
  ];

  function parseBmcAccountabilityNotes(raw) {
    if (!raw) {
      return { type: '', detail: '' };
    }

    for (var i = 0; i < bmcAccountabilityNoteTypes.length; i++) {
      var type = bmcAccountabilityNoteTypes[i];

      if (raw === type) {
        return { type: type, detail: '' };
      }

      if (raw.indexOf(type + ': ') === 0) {
        return {
          type: type,
          detail: raw.substring(type.length + 2)
        };
      }
    }

    return { type: '', detail: raw };
  }

  $(document).on('click', '.bmc-acc-notes-text', function () {
    var cell = $(this).closest('.bmc-acc-notes-cell');
    var parsed = parseBmcAccountabilityNotes(String(cell.data('notes') || ''));

    cell.find('.bmc-acc-notes-type').val(parsed.type);
    cell.find('.bmc-acc-notes-detail').val(parsed.detail);
    cell.find('.bmc-acc-notes-text').hide();
    cell.find('.bmc-acc-notes-edit').show();
    cell.find('.bmc-acc-notes-type').focus();
  });

  $(document).on('click', '.bmc-acc-notes-cancel', function () {
    var cell = $(this).closest('.bmc-acc-notes-cell');
    cell.find('.bmc-acc-notes-edit').hide();
    cell.find('.bmc-acc-notes-text').show();
  });

  $(document).on('click', '.bmc-acc-notes-save', function () {
    var button = $(this);
    var cell = button.closest('.bmc-acc-notes-cell');
    var accountabilityId = cell.data('accountability-id');
    var type = cell.find('.bmc-acc-notes-type').val();
    var detail = cell.find('.bmc-acc-notes-detail').val().trim();
    var notes = type ? (detail ? type + ': ' + detail : type) : detail;

    button.prop('disabled', true);

    $.ajax({
      type: 'POST',
      url: '{{ url('/update-bmc-accountability-notes') }}/' + accountabilityId,
      data: {
        notes: notes,
        _token: '{{ csrf_token() }}'
      },
      success: function () {
        var noteText = cell.find('.bmc-acc-notes-text');

        cell.data('notes', notes);

        if (notes) {
          noteText
            .removeClass('text-muted font-italic')
            .text(notes)
            .attr('title', 'Click to edit');
        } else {
          noteText
            .addClass('text-muted font-italic')
            .text('Add note...')
            .attr('title', 'Click to add notes');
        }

        cell.find('.bmc-acc-notes-edit').hide();
        noteText.show();
        button.prop('disabled', false);
      },
      error: function (xhr) {
        var message = xhr.responseJSON && xhr.responseJSON.message
          ? xhr.responseJSON.message
          : 'Failed to save issuance notes.';

        Swal.fire('Error', message, 'error');
        button.prop('disabled', false);
      }
    });
  });
</script>

@endsection
