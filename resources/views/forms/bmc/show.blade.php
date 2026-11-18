@extends('layouts.header')
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

                  <div class="d-flex align-items-center mb-2" style="gap: 8px;">
                    <a href="{{ route('bmc.index') }}" style="display: flex; align-items: center; width: fit-content;" class="btn btn-sm btn-light border">
                        <i class="ti-arrow-left mr-1"></i> Back
                    </a>
                  </div>
              </div>

            @if ($errors->has('attachment') || $errors->has('attachment.*'))
              <div class="alert alert-danger" role="alert">
                Return proof photos could not be uploaded. {{ $errors->first('attachment') ?: $errors->first('attachment.*') }}
              </div>
            @endif

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
              .borrowed-item-list {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: .65rem;
              }
              .borrowed-item {
                display: inline-flex;
                align-items: center;
                width: fit-content;
                max-width: 100%;
                padding: .75rem 1rem;
                color: #24516f;
                background: #eef8ff;
                border: 1px solid #cfe9f8;
                border-left: 4px solid #59a9d8;
                border-radius: .5rem;
                font-size: .9rem;
                font-weight: 600;
                line-height: 1.4;
              }
              .borrowed-item i {
                margin-right: .65rem;
                color: #3d8fbe;
                font-size: 1rem;
              }
              @media (max-width: 767.98px) {
                .bmc-mobile-scroll {
                  overflow-x: auto;
                  -webkit-overflow-scrolling: touch;
                }
                .bmc-mobile-scroll .info-table {
                  min-width: 600px;
                  margin-bottom: 0;
                }
              }
            </style>

            @if ($borrowing->accountabilities->isNotEmpty())
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
              <div class="card mb-4">
                <div class="card-header bg-secondary text-white py-2">
                  <h6 class="mb-0">Release History</h6>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                      <thead>
                        <tr>
                          <th style="width: 1%;"></th>
                          <th>Ref</th>
                          <th>Released At</th>
                          <th>Signed At</th>
                          <th>Released By</th>
                          <th>To return</th>
                          <!-- <th>Issuance Notes</th> -->
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                          $extendableAccountabilities = $borrowing->accountabilities->filter(function ($release) use ($borrowing) {
                            return (int) $borrowing->user_id === (int) auth()->id() &&
                              $release->expected_return_at &&
                              $release->released_at &&
                              $release->released_at->copy()->addDays(40)->startOfDay()->gte(today()) &&
                              $release->expected_return_at->lt($release->released_at->copy()->addDays(40)->startOfDay()) &&
                              !$release->extensions->contains('status', \App\BmcAccountabilityExtension::STATUS_PENDING) &&
                              in_array($release->status, [
                                \App\BmcAccountability::STATUS_RELEASED,
                                \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
                              ], true) &&
                              $release->items->contains(function ($item) {
                                return (int) $item->released_quantity > (int) ($item->returned_quantity ?? 0);
                              });
                          })->keyBy('id');
                        @endphp
                        @foreach ($borrowing->accountabilities as $accountability)
                          @php
                            $signed = !is_null($accountability->signed_at);
                            $latestExtension = $accountability->extensions->first();
                            $expectedReturnNeedsAttention =
                              in_array($accountability->status, [
                                \App\BmcAccountability::STATUS_RELEASED,
                                \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION,
                                \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
                              ], true) &&
                              $accountability->expected_return_at &&
                              $accountability->expected_return_at->isToday();
                            $hasRemainingItems = $accountability->items
                              ->contains(function ($item) {
                                return (int) $item->released_quantity >
                                  (int) ($item->returned_quantity ?? 0);
                              });
                          @endphp
                          <tr>
                            <td class="text-center" style="padding: 2px; white-space: nowrap;">
                              <div class="d-inline-flex align-items-center justify-content-center" style="gap: .25rem;">
                                <button
                                  type="button"
                                  class="btn btn-info btn-sm d-inline-flex align-items-center justify-content-center"
                                  style="width: 28px; height: 28px; padding: 0;"
                                  title="View summary"
                                  aria-label="View accountability summary {{ $accountability->accountability_ref }}"
                                  data-toggle="modal"
                                  data-target="#viewBmcAccountability{{ $accountability->id }}"
                                >
                                  <i class="ti-eye"></i>
                                </button>
                                <a
                                  href="{{ route('bmc.accountability.preview', $accountability->id) }}"
                                  target="_blank"
                                  rel="noopener noreferrer"
                                  class="btn btn-primary btn-sm d-inline-flex align-items-center justify-content-center"
                                  style="width: 28px; height: 28px; padding: 0;"
                                  title="View accountability"
                                  aria-label="View accountability {{ $accountability->accountability_ref }}"
                                >
                                  <i class="ti-file"></i>
                                </a>
                              @if (
                                !$signed &&
                                $accountability->status === \App\BmcAccountability::STATUS_RELEASED
                              )
                                <button
                                  type="button"
                                  class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center"
                                  style="width: 28px; height: 28px; padding: 0;"
                                  data-toggle="modal"
                                  data-target="#bmcSignature{{ $accountability->id }}"
                                  title="Sign accountability"
                                  aria-label="Sign accountability {{ $accountability->accountability_ref }}"
                                >
                                  <i class="ti-write"></i>
                                </button>
                              @endif
                              @if (
                                $signed &&
                                $hasRemainingItems &&
                                in_array($accountability->status, [
                                  \App\BmcAccountability::STATUS_RELEASED,
                                  \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
                                ], true)
                              )
                                <button
                                  type="button"
                                  class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center"
                                  style="width: 28px; height: 28px; padding: 0;"
                                  data-toggle="modal"
                                  data-target="#requestBmcReturn{{ $accountability->id }}"
                                  title="Initiate return"
                                  aria-label="Initiate return for {{ $accountability->accountability_ref }}"
                                >
                                  <i class="ti-back-left"></i>
                                </button>
                              @elseif (
                                $accountability->status ===
                                  \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION
                              )
                                <button
                                  type="button"
                                  class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center"
                                  style="width: 28px; height: 28px; padding: 0;"
                                  title="Awaiting approver confirmation"
                                  disabled
                                >
                                  <i class="ti-time"></i>
                                </button>
                              @endif
                              @if ($extendableAccountabilities->has($accountability->id))
                                <button
                                  type="button"
                                  class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center"
                                  style="width: 28px; height: 28px; padding: 0;"
                                  data-toggle="modal"
                                  data-target="#bmcExtension{{ $accountability->id }}"
                                  title="Request extension"
                                  aria-label="Request extension for {{ $accountability->accountability_ref }}"
                                >
                                  <i class="ti-calendar" aria-hidden="true"></i>
                                </button>
                              @endif
                              </div>
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
                            <td style="white-space: nowrap;">
                              {{ optional($accountability->releasedBy)->name ?? '-' }}
                            </td>
                            <td style="white-space: nowrap; {{ $expectedReturnNeedsAttention ? 'background-color: #f8d7da; color: #721c24; font-weight: 600;' : '' }}">
                              {{ optional($accountability->expected_return_at)->format('M d, Y') ?? '-' }}
                              @if ($latestExtension)
                                <small class="d-block text-muted mt-1">
                                  Extension {{ $latestExtension->status }}
                                  · {{ $latestExtension->requested_return_date->format('M d, Y') }}
                                </small>
                              @endif
                              @if ((string) old('extension_accountability_id') === (string) $accountability->id &&
                                ($errors->has('requested_return_date') || $errors->has('reason')))
                                <small class="d-block text-danger mt-1" role="alert" style="white-space: normal;">
                                  {{ $errors->first('requested_return_date') ?: $errors->first('reason') }}
                                </small>
                              @endif
                            </td>
                            <!-- <td style="white-space: pre-wrap;">{{ $accountability->release_notes ?? '-' }}</td> -->
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
                <div
                  class="modal fade bmc-accountability-summary-modal"
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
                                    <th style="min-width: 260px; width: 260px;">MOR</th>
                                    <th style="min-width: 260px; width: 260px;">Return Remarks</th>
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
                                            View proof photo{{ $loop->count > 1 ? ' ' . $loop->iteration : '' }}
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
                                <table class="table table-sm table-borderless mb-0">
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
                                    <td>{{ optional($accountability->expected_return_at)->format('M d, Y') ?? '-' }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-muted font-weight-bold">Signed At</td>
                                    <td>{{ optional($accountability->signed_at)->format('M d, Y g:i A') ?? '-' }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-muted font-weight-bold">Issuance Notes</td>
                                    <td style="white-space: pre-wrap;">{{ $accountability->release_notes ?? '-' }}</td>
                                  </tr>
                                </table>
                              </div>
                            </div>
                          </div>

                          <div class="col-12">
                            <div class="card h-100 border shadow-none">
                              <div class="card-header rounded-top bg-light py-2">
                                <h6 class="font-weight-bold mb-0">Closure Details</h6>
                              </div>
                              <div class="card-body p-3">
                                <table class="table table-sm table-borderless mb-0">
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
                                  @if ($accountability->closing_remarks)
                                    <tr>
                                      <td class="text-muted font-weight-bold">Return Remarks</td>
                                      <td style="white-space: pre-wrap;">{{ $accountability->closing_remarks }}</td>
                                    </tr>
                                  @endif
                                  <!-- <tr> -->
                                  <!--   <td class="text-muted font-weight-bold">Mode of Return</td> -->
                                  <!--   <td>{{ $accountability->mor ?? '-' }}</td> -->
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

                      <div class="modal-footer d-flex flex-column flex-sm-row align-items-stretch px-4">
                        <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            @endif

            <div class="bmc-section">
              <div class="bmc-section-header">
                <h5><i class="ti-user mr-2"></i>I. Borrower's Information</h5>
              </div>
              <div class="bmc-section-body">
                <div class="bmc-mobile-scroll">
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
            </div>

            <div class="bmc-section">
              <div class="bmc-section-header">
                <h5><i class="ti-calendar mr-2"></i>II. Event Details</h5>
              </div>
              <div class="bmc-section-body">
                <div class="bmc-mobile-scroll">
                  <table class="table info-table">
                    <tr><td>Date Needed</td><td>{{ optional($borrowing->date_needed)->format('F j, Y') }}</td></tr>
                    <tr><td>Event Name</td><td>{{ $borrowing->event_name }}</td></tr>
                    <tr><td>Request Details</td><td>{{ $borrowing->request_details }}</td></tr>
                    <tr><td>Number of Days Item is Needed</td><td>{{ $borrowing->number_of_days_item_needed }} day(s)</td></tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="bmc-section">
              <div class="bmc-section-header">
                <h5><i class="ti-gift mr-2"></i>III. Item for Borrowing</h5>
              </div>
              <div class="bmc-section-body">
                <div class="borrowed-item-list">
                  @foreach ($borrowing->borrowedItems as $item)
                    <div class="borrowed-item">
                      <i class="ti-package"></i>
                      <span>
                        <span class="d-block">
                          {{ $item->item_name }} &times; {{ $item->requested_quantity }}
                        </span>
                        @if ($requiresDeposit)
                          <small class="text-muted d-block font-weight-normal">
                            ₱{{ number_format($borrowing->depositRate($item->item_name), 2) }}
                            per item &middot;
                            ₱{{ number_format($borrowing->depositRate($item->item_name) * (int) $item->requested_quantity, 2) }}
                          </small>
                        @endif
                      </span>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var summaryModals = document.querySelectorAll('.bmc-accountability-summary-modal');

    for (var index = 0; index < summaryModals.length; index++) {
      document.body.appendChild(summaryModals[index]);
    }
  });
</script>

@php
  $hasUnsignedReleasedAccountability = $borrowing->accountabilities->contains(function ($accountability) {
    return $accountability->status === \App\BmcAccountability::STATUS_RELEASED
      && is_null($accountability->signed_at);
  });
@endphp

@if ($hasUnsignedReleasedAccountability)
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

  @foreach ($borrowing->accountabilities as $accountability)
    @if (
      $accountability->status === \App\BmcAccountability::STATUS_RELEASED &&
      is_null($accountability->signed_at)
    )
      @include('forms.bmc._signature_modal', ['accountability' => $accountability])
    @endif
  @endforeach
@endif

@foreach ($borrowing->accountabilities as $accountability)
  @if (
    $accountability->borrower_signature &&
    in_array($accountability->status, [
      \App\BmcAccountability::STATUS_RELEASED,
      \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
    ], true) &&
    $accountability->items->contains(function ($item) {
      return (int) $item->released_quantity >
        (int) ($item->returned_quantity ?? 0);
    })
  )
    @include('forms.bmc._return_request_modal', ['accountability' => $accountability])
  @endif
@endforeach
@foreach ($extendableAccountabilities ?? [] as $accountability)
  @php
    $minimumExtensionDate = max(
      $accountability->expected_return_at->copy()->addDay()->toDateString(),
      today()->toDateString()
    );
    $maximumExtensionDate = $accountability->released_at->copy()->addDays(40)->toDateString();
    $isExtensionRetry = (string) old('extension_accountability_id') === (string) $accountability->id;
  @endphp
  <div class="modal fade" id="bmcExtension{{ $accountability->id }}" tabindex="-1"
    role="dialog" aria-labelledby="bmcExtensionTitle{{ $accountability->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form class="modal-content border-0" method="POST" action="{{ route('bmc.extensions.request', $accountability->id) }}"
        data-bmc-extension-form>
        @csrf
        <input type="hidden" name="extension_accountability_id" value="{{ $accountability->id }}">
        <div class="modal-header">
          <h5 class="modal-title" id="bmcExtensionTitle{{ $accountability->id }}">Request Extension</h5>
          <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <dl class="bg-light rounded p-3 mb-3">
            <dt class="text-muted">Reference No.</dt>
            <dd class="mb-2">{{ $accountability->accountability_ref }}</dd>
            <dt class="text-muted">Current expected return date</dt>
            <dd class="mb-0">{{ $accountability->expected_return_at->format('M d, Y') }}</dd>
          </dl>
          <div class="form-group">
            <label for="bmcExtensionDate{{ $accountability->id }}">Requested return date</label>
            <input type="date" class="form-control" id="bmcExtensionDate{{ $accountability->id }}"
              name="requested_return_date" value="{{ $isExtensionRetry ? old('requested_return_date') : '' }}"
              min="{{ $minimumExtensionDate }}" max="{{ $maximumExtensionDate }}" required>
            <small class="form-text text-muted">Date can be extened until: {{ $accountability->released_at->copy()->addDays(40)->format('M d, Y') }}</small>
          </div>
          <div class="form-group mb-0">
            <label for="bmcExtensionReason{{ $accountability->id }}">Reason for extension</label>
            <textarea class="form-control" id="bmcExtensionReason{{ $accountability->id }}"
              name="reason" rows="3" maxlength="2000" placeholder="Specify your reason..." required>{{ $isExtensionRetry ? old('reason') : '' }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light border" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Request</button>
        </div>
      </form>
    </div>
  </div>
@endforeach

<script>
  document.addEventListener('submit', function (event) {
    if (!event.target.hasAttribute('data-bmc-extension-form')) {
      return;
    }

    var submitButton = event.target.querySelector('button[type="submit"]');

    if (submitButton.disabled) {
      event.preventDefault();
      return;
    }

    submitButton.disabled = true;
    submitButton.textContent = 'Submitting…';
  });
</script>
@endsection
