@extends('layouts.header')
@section('css_header')
<style>
    .main-panel .card { z-index: auto; }
    .bmc-request-summary-row {
      display: grid;
      gap: .25rem 1rem;
      margin: 0 0 .5rem;
    }
    @media (min-width: 576px) {
      .bmc-request-summary-row {
        grid-template-columns: minmax(0, 1fr) minmax(0, 2fr);
      }
    }
    .bmc-summary-card {
      height: 100%;
      color: inherit;
      background: #ffffff;
      border: 1px solid #e7ebf1;
      border-radius: 14px;
      box-shadow: 0 3px 10px rgba(30, 41, 59, 0.06);
      text-decoration: none !important;
      transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }
    .bmc-summary-card:hover {
      color: inherit;
      border-color: #cbd5e1;
      box-shadow: 0 8px 18px rgba(30, 41, 59, 0.1);
      transform: translateY(-2px);
    }
    .bmc-summary-card.is-active {
      border-color: #6477d8;
      box-shadow: 0 0 0 2px rgba(100, 119, 216, 0.14), 0 8px 18px rgba(30, 41, 59, 0.09);
    }
    .bmc-summary-card .card-body {
      display: flex;
      align-items: center;
      gap: 12px;
      min-height: 85px;
    }
    .bmc-summary-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex: 0 0 40px;
      width: 40px;
      height: 40px;
      border-radius: 10px;
      font-size: 18px;
    }
    .bmc-summary-content {
      min-width: 0;
      flex: 1;
    }
    .bmc-summary-label {
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
    .bmc-summary-value {
      margin-top: 5px;
      color: #0f172a;
      font-size: 1.2rem;
      font-weight: 700;
      line-height: 1;
    }
    .bmc-icon-waiting { background: #fff3cd; color: #a16207; }
    .bmc-icon-approved { background: #ede9fe; color: #6d4aff; }
    .bmc-icon-partial { background: #e0f2fe; color: #0284c7; }
    .bmc-icon-released { background: #dcfce7; color: #15803d; }
    .bmc-icon-return { background: #f3e8ff; color: #9333ea; }
    .bmc-icon-closed { background: #e2e8f0; color: #475569; }
    .bmc-remarks-column {
      width: 220px;
      min-width: 220px;
      max-width: 220px;
    }
    .bmc-remarks-text {
      display: -webkit-box;
      overflow: hidden;
      color: #475569;
      line-height: 1.4;
      overflow-wrap: anywhere;
      white-space: normal;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 3;
    }
    @media (max-width: 767.98px) {
      .bmc-summary-card .card-body { min-height: auto; }
    }
</style>
@endsection
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class='row grid-margin bmc-summary-cards'>
      <div class='col-xl-4 col-md-6 mb-3'>
        <a href="/bmc-approval?status=For Approval" class="card bmc-summary-card {{ $filter_status === 'For Approval' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-waiting"><i class="ti-time"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="For Approval">For Approval</div>
              <div class="bmc-summary-value">{{$for_approval}}</div>
            </div>
          </div>
        </a>
      </div>
      <div class='col-xl-4 col-md-6 mb-3'>
        <a href="/bmc-approval?status=Approved" class="card bmc-summary-card {{ $filter_status === 'Approved' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-approved"><i class="ti-check-box"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="Approved">Approved</div>
              <div class="bmc-summary-value">{{$approved}}</div>
            </div>
          </div>
        </a>
      </div>
      <div class='col-xl-4 col-md-6 mb-3'>
        <a href="{{ url('/bmc-approval') }}?status={{ urlencode(\App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED) }}" class="card bmc-summary-card {{ $filter_status === \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-partial"><i class="ti-package"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="Partial Released">Partial Released</div>
              <div class="bmc-summary-value">{{$partially_release}}</div>
            </div>
          </div>
        </a>
      </div>
      <div class='col-xl-4 col-md-6 mb-3'>
        <a href="/bmc-approval?status=Released" class="card bmc-summary-card {{ $filter_status === 'Released' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-released"><i class="ti-truck"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="Released">Released</div>
              <div class="bmc-summary-value">{{ \App\MarketingCollateralBorrowing::where('status', 'Released')->count() }}</div>
            </div>
          </div>
        </a>
      </div>
      <div class='col-xl-4 col-md-6 mb-3'>
        <a href="{{ url('/bmc-approval') }}?return_status={{ urlencode(\App\BmcAccountability::STATUS_PARTIALLY_RETURNED) }}" class="card bmc-summary-card {{ $return_status === \App\BmcAccountability::STATUS_PARTIALLY_RETURNED ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-return"><i class="ti-reload"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="Partial Return">Partial Return</div>
              <div class="bmc-summary-value">{{$partial_return}}</div>
            </div>
          </div>
        </a>
      </div>
      <div class='col-xl-4 col-md-6 mb-3'>
        <a href="/bmc-approval?status=Closed" class="card bmc-summary-card {{ $filter_status === 'Closed' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-closed"><i class="ti-lock"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="Closed">Closed</div>
              <div class="bmc-summary-value">{{ \App\MarketingCollateralBorrowing::where('status', 'Closed')->count() }}</div>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class='row'>
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Borrowing Marketing Collateral Approvals</h4>
            <form method='get' onsubmit='show();'>
              <input type="hidden" name="return_status" value="{{ $return_status ?? '' }}">
              <div class="row align-items-end">
                  <div class='col-md-3'>
                      <div class="form-group mb-2">
                          <label class="text-right mb-1">Search</label>
                          <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control form-control-sm" placeholder="Search ref no., borrower, event..." />
                      </div>
                  </div>
                <div class='col-md-3'>
                  <div class="form-group mb-2">
                    <select name='status' class="form-control form-control-sm js-example-basic-single" style='width:100%;'>
                      <option value="" @if ($filter_status == '') selected @endif>All</option>
                      <option value="For Approval" @if ($filter_status == 'For Approval') selected @endif>For Approval</option>
                      <option value="Approved" @if ($filter_status == 'Approved') selected @endif>Approved</option>
                      <option value="{{ \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED }}" @if ($filter_status === \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED) selected @endif>Partially Released</option>
                      <option value="{{ \App\MarketingCollateralBorrowing::STATUS_RELEASED }}" @if ($filter_status === \App\MarketingCollateralBorrowing::STATUS_RELEASED) selected @endif>Released</option>
                      <option value="{{ \App\BmcAccountability::STATUS_PARTIALLY_RETURNED }}" @if ($filter_status === \App\BmcAccountability::STATUS_PARTIALLY_RETURNED || $return_status === \App\BmcAccountability::STATUS_PARTIALLY_RETURNED) selected @endif>Partial Return</option>
                      <option value="{{ \App\MarketingCollateralBorrowing::STATUS_CLOSED }}" @if ($filter_status === \App\MarketingCollateralBorrowing::STATUS_CLOSED) selected @endif>Closed</option>
                      <option value="Declined" @if ($filter_status == 'Declined') selected @endif>Declined</option>
                      <option value="Cancelled" @if ($filter_status == 'Cancelled') selected @endif>Cancelled</option>
                    </select>
                  </div>
                </div>
                <div class='col-md-2'>
                  <div class="form-group mb-2">
                    <label class="text-right mb-1">From</label>
                    <input type="date" value="{{ $from }}" class="form-control form-control-sm" name="from" />
                  </div>
                </div>
                <div class='col-md-2'>
                  <div class="form-group mb-2">
                    <label class="text-right mb-1">To</label>
                    <input type="date" value="{{ $to }}" class="form-control form-control-sm" name="to" />
                  </div>
                </div>
                <div class='col-md-2 mb-4'>
                  <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                </div>
              </div>
            </form>
            <div class="table-responsive">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Reference No</th>
                    <th>Borrower</th>
                    <th>Signed Accountability</th>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th class="bmc-remarks-column">MBD Remarks</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($borrowings as $b)
                    <tr>
                      <td align="center">
                        @if ($b->status === 'For Approval')
                          <button type="button" class="btn btn-success btn-rounded btn-icon" data-toggle="modal" data-target="#approveBmc{{$b->id}}" title="Approve"><i class="ti-check"></i></button>
                          <button type="button" class="btn btn-danger btn-rounded btn-icon" data-toggle="modal" data-target="#declineBmc{{$b->id}}" title="Decline"><i class="ti-close"></i></button>
                          <button type="button" class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-target="#detailsBmc{{$b->id}}" title="View Request Details"><i class="ti-eye"></i></button>
                        @elseif (in_array($b->status, [\App\MarketingCollateralBorrowing::STATUS_APPROVED, \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED], true))
                          <button type="button" class="btn btn-success btn-rounded btn-icon" data-toggle="modal" data-target="#releaseBmc{{$b->id}}" title="{{ $b->status === \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED ? 'Release Remaining Items' : 'Release Items' }}"><i class="ti-package"></i></button>
                          <button type="button" class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-target="#detailsBmc{{$b->id}}" title="View Request Details"><i class="ti-eye"></i></button>
                          @if ($b->status === \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED)
                            <a href="{{ url('/bmc-approval/' . $b->id) }}" class="btn btn-primary btn-rounded btn-icon d-inline-flex align-items-center justify-content-center" style="vertical-align: middle;" title="View Accountability History"><i class="ti-clipboard"></i></a>
                          @endif
                        @elseif (in_array($b->status, [\App\MarketingCollateralBorrowing::STATUS_RELEASED, \App\MarketingCollateralBorrowing::STATUS_CLOSED], true))
                          <a href="{{ url('/bmc-approval/' . $b->id) }}" class="btn btn-primary btn-rounded btn-icon d-inline-flex align-items-center justify-content-center" style="vertical-align: middle;" title="View Accountability History"><i class="ti-clipboard"></i></a>
                        @else
                          @if ($b->approved_by)
                            <button type="button" class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-target="#detailsBmc{{$b->id}}" title="View Approver Details"><i class="ti-eye"></i></button>
                          @endif
                        @endif
                      </td>
                      <td>{{$b->reference_no}}</td>
                      <td>
                        <div class="mb-1">
                          {{$b->borrower_first_name}} {{$b->borrower_last_name}}
                        </div>
                        <small class="text-info d-block mb-1">{{ $b->borrower_profile }}</small>
                        @if ($b->department) <small class="text-info d-block">{{ $b->department}}</small> @endif
                      </td>
                      <td style="font-size: 10px; line-height: 1.6; white-space: nowrap;">
                        @forelse ($b->accountabilities as $accountability)
                          @php
                            $isSigned = !blank($accountability->borrower_signature);
                          @endphp

                          <div class="d-flex justify-content-between">
                            <span class="font-weight-bold">
                              {{ $accountability->accountability_ref }}:
                            </span>
                            <span
                              class="ml-2"
                              style="color: {{ $isSigned ? 'blue' : 'red' }}; font-weight: bold;"
                              title="{{ $isSigned ? 'Signed' : 'Not yet signed' }}"
                            >
                              {{ $isSigned ? '✓' : 'X' }}
                            </span>
                          </div>
                          @if (
                            $accountability->status ===
                              \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION
                          )
                            <small class="badge badge-warning d-block mt-1">
                              Return Confirmation Required
                            </small>
                          @endif
                        @empty
                          <span>-</span>
                        @endforelse
                      </td>
                      <td>
                          <div class="mb-1">{{$b->event_name}}</div>
                          <small
                            class="text-muted d-block"
                            style="white-space: pre-wrap; overflow-wrap: anywhere;"
                          >{{$b->request_details}}</small>
                      </td>
                      <td style="white-space: nowrap;">
                        @php
                            // Use the earliest open accountability because it needs
                            // to be returned first.
                            $displayAccountability = $b->accountabilities
                                ->whereIn('status', [
                                  \App\BmcAccountability::STATUS_RELEASED,
                                  \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION,
                                  \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
                                ])
                                ->sortBy('expected_return_at')
                                ->first();

                            // For a closed request, show its most recent accountability date.
                            if (!$displayAccountability) {
                                $displayAccountability = $b->accountabilities
                                    ->sortByDesc('released_at')
                                    ->first();
                            }

                            $expectedReturnDate = $displayAccountability
                                ? $displayAccountability->expected_return_at
                                : null;

                            $returnIsDue =
                                $displayAccountability &&
                                in_array($displayAccountability->status, [
                                  \App\BmcAccountability::STATUS_RELEASED,
                                  \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION,
                                  \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
                                ], true) &&
                                $expectedReturnDate &&
                                $expectedReturnDate->copy()->startOfDay()->lte(today());
                        @endphp
                        <div class="mb-1">
                          <span class="text-muted font-weight-bold">
                              <small>
                                  Date Needed:
                              </small>
                          </span>
                          <span class="text-info">
                              <small>
                              {{ \Carbon\Carbon::parse($b->date_needed)->format('M d, Y') }}
                              </small>
                          </span>
                        </div>
                        <div>
                          <span class="text-muted font-weight-bold">
                              <small>
                                  Expected Return:
                              </small>
                          </span>
                          @if ($expectedReturnDate)
                            <span class="{{ $returnIsDue ? 'text-danger font-weight-bold' : 'text-info' }}">
                                <small>
                                  {{ $expectedReturnDate->format('M d, Y') }}
                                </small>
                            </span>
                          @else
                            <small class="text-info">Set upon release</small>
                          @endif
                        </div>
                      </td>
                      <td style="font-size: 11px; line-height: 1.6; white-space: nowrap; min-width: 170px;">
                        @php
                          $releasedByItem = $b->accountabilities
                            ->flatMap(function ($accountability) {
                              return $accountability->items;
                            })
                            ->groupBy('item_name')
                            ->map(function ($items) {
                              return $items->sum(function ($item) {
                                return (int) $item->released_quantity;
                              });
                            });
                          $usesApprovedQuantity = in_array($b->status, [
                            \App\MarketingCollateralBorrowing::STATUS_APPROVED,
                            \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED,
                            \App\MarketingCollateralBorrowing::STATUS_RELEASED,
                            \App\MarketingCollateralBorrowing::STATUS_CLOSED,
                          ], true);
                        @endphp
                        @foreach ($b->borrowedItems as $borrowedItem)
                          @php
                            $targetQuantity = $usesApprovedQuantity
                              ? (int) ($borrowedItem->approved_quantity ?? 0)
                              : (int) $borrowedItem->requested_quantity;
                            $releasedQuantity = (int) $releasedByItem
                              ->get($borrowedItem->item_name, 0);
                            $itemLabel = [
                              'Negosyo Partner Roll Up Banner' => 'Partner Banner',
                              'End User Roll Up Banner' => 'End User Banner',
                            ][$borrowedItem->item_name] ?? $borrowedItem->item_name;
                            $fullyReleased =
                              $targetQuantity > 0 &&
                              $releasedQuantity >= $targetQuantity;
                          @endphp
                          @if ($targetQuantity > 0)
                            <div class="d-flex justify-content-between font-weight-bold">
                              <span class="mr-3" title="{{ $borrowedItem->item_name }}">{{ $itemLabel }}:</span>
                              <span style="color: {{ $fullyReleased ? 'blue' : 'red' }};">
                                {{ $releasedQuantity }}/{{ $targetQuantity }}
                              </span>
                            </div>
                          @endif
                        @endforeach
                      </td>
                      <td class="bmc-remarks-column">
                        <div class="bmc-remarks-text" title="{{ $b->mbd_remarks ?? '-' }}">
                          {{ $b->mbd_remarks ?? '-' }}
                        </div>
                      </td>
                      <td>
                        @php
                          $statusBadge = 'warning';

                          if (in_array($b->status, [
                            \App\MarketingCollateralBorrowing::STATUS_APPROVED,
                            \App\MarketingCollateralBorrowing::STATUS_RELEASED,
                          ], true)) {
                            $statusBadge = 'success';
                          } elseif ($b->status === \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED) {
                            $statusBadge = 'info';
                          } elseif ($b->status === \App\MarketingCollateralBorrowing::STATUS_DECLINED) {
                            $statusBadge = 'danger';
                          } elseif (in_array($b->status, [
                            \App\MarketingCollateralBorrowing::STATUS_CANCELLED,
                            \App\MarketingCollateralBorrowing::STATUS_CLOSED,
                          ], true)) {
                            $statusBadge = 'secondary';
                          }
                        @endphp
                        <span class="badge badge-{{ $statusBadge }}">{{$b->status}}</span>
                      </td>
                    </tr>
                  @endforeach
                  @if(count($borrowings) == 0)
                    <tr><td colspan="9" class="text-center text-muted py-4">No records found.</td></tr>
                  @endif
                </tbody>
              </table>
            </div>
            @foreach ($borrowings as $b)
              @if (in_array($b->status, [\App\MarketingCollateralBorrowing::STATUS_APPROVED, \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED], true))
                @include('for-approval.remarks.bmc_release_modal', ['borrowing' => $b])
              @endif

              <div class="modal fade" id="approveBmc{{$b->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <form method="post" action="{{ url('/approve-bmc/'.$b->id) }}" onsubmit="show();">
                    {{csrf_field()}}
                    @php
                      $approvedDays = (int) old(
                        'approved_number_of_days',
                        $b->approved_number_of_days ?? $b->number_of_days_item_needed
                      );
                      $approvedDays = max(1, min(40, $approvedDays));
                      $requiresDeposit = $b->requiresDeposit();
                      $requestedDepositTotal = $b->borrowedItems
                        ->sum(function ($item) use ($b) {
                          return $b->depositRate($item->item_name) *
                            (int) $item->requested_quantity;
                        });
                    @endphp
                    <div class="modal-content border-0">
                      <div class="modal-header">
                        <div>
                          <h5 class="modal-title">Review and Approve Request</h5>
                          <small class="text-white">Confirm the borrowing period and available quantities.</small>
                        </div>
                        <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <section class="mb-4" style="font-size: 0.95rem;">
                          <div class="d-flex flex-wrap justify-content-between align-items-baseline mb-3">
                            <h6 class="mb-0 ml-2">Request Summary</h6>
                            <span class="badge badge-info">{{ $b->status }}</span>
                          </div>
                          <dl class="mb-0">
                            <div class="bmc-request-summary-row">
                              <dt class="mb-0 text-muted font-weight-normal">Reference Number:</dt>
                              <dd class="text-break mb-0">{{ $b->reference_no }}</dd>
                            </div>
                            <div class="bmc-request-summary-row">
                              <dt class="mb-0 text-muted font-weight-normal">Requestor:</dt>
                              <dd class="text-break mb-0">{{ $b->borrower_first_name }} {{ $b->borrower_last_name }}</dd>
                            </div>
                            <div class="bmc-request-summary-row">
                              <dt class="mb-0 text-muted font-weight-normal">Department:</dt>
                              <dd class="text-break mb-0">{{ $b->department ?? '—' }}</dd>
                            </div>
                            <div class="bmc-request-summary-row">
                              <dt class="mb-0 text-muted font-weight-normal">Borrower Profile:</dt>
                              <dd class="text-break mb-0">{{ $b->borrower_profile }}</dd>
                            </div>
                          </dl>
                          <hr class="my-3">
                          <dl class="mb-0">
                            <div class="bmc-request-summary-row">
                              <dt class="mb-0 text-muted font-weight-normal">Event:</dt>
                              <dd class="text-break mb-0">{{ $b->event_name }}</dd>
                            </div>
                            <div class="bmc-request-summary-row">
                              <dt class="mb-0 text-muted font-weight-normal">Date Needed:</dt>
                              <dd class="text-break mb-0">{{ optional($b->date_needed)->format('F j, Y') ?? '—' }}</dd>
                            </div>
                            <div class="bmc-request-summary-row">
                              <dt class="mb-0 text-muted font-weight-normal">Requested Number of Days:</dt>
                              <dd class="text-break mb-0">{{ $b->number_of_days_item_needed }} day(s)</dd>
                            </div>
                            <div class="bmc-request-summary-row">
                              <dt class="mb-0 text-muted font-weight-normal">Request Details:</dt>
                              <dd class="text-break mb-0">{{ $b->request_details }}</dd>
                            </div>
                          </dl>
                          <hr class="mt-3 mb-0">
                        </section>

                        <div class="border rounded mb-4">
                          <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between p-3">
                            <div class="mb-3 mb-sm-0 pr-sm-4">
                              <span class="font-weight-bold d-block">Borrowing Period</span>
                              <small class="text-info">
                                Borrower asked for {{ $b->number_of_days_item_needed }} day(s).
                              </small>
                            </div>
                            <div>
                              <div
                                class="bmc-approval-stepper"
                                data-bmc-approval-stepper
                              >
                                <button
                                  type="button"
                                  class="bmc-approval-stepper-button"
                                  data-bmc-approval-decrease
                                  aria-label="Decrease approved number of days"
                                >&minus;</button>
                                <input
                                  type="number"
                                  class="bmc-approval-stepper-value"
                                  id="approvedDays{{ $b->id }}"
                                  name="approved_number_of_days"
                                  value="{{ $approvedDays }}"
                                  min="1"
                                  max="40"
                                  inputmode="numeric"
                                  data-bmc-approval-input
                                  required
                                >
                                <button
                                  type="button"
                                  class="bmc-approval-stepper-button"
                                  data-bmc-approval-increase
                                  aria-label="Increase approved number of days"
                                >+</button>
                              </div>
                              <small class="text-muted d-block mt-1">Max: 1–40 days</small>
                            </div>
                          </div>
                        </div>

                        <div class="border rounded mb-4">
                          <div class="bg-light border-bottom rounded-top px-3 py-2">
                            <span class="font-weight-bold d-block">Item Allocation</span>
                            <small class="text-muted">Set the quantity that can actually be provided.</small>
                          </div>
                          @foreach ($b->borrowedItems as $itemIndex => $item)
                            @php
                              $requestedQuantity = (int) $item->requested_quantity;
                              $approvedQuantity = (int) old(
                                'items.' . $itemIndex . '.approved_quantity',
                                is_null($item->approved_quantity)
                                  ? $requestedQuantity
                                  : $item->approved_quantity
                              );
                              $approvedQuantity = max(0, min($requestedQuantity, $approvedQuantity));
                            @endphp
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                              <input
                                type="hidden"
                                name="items[{{ $itemIndex }}][item_name]"
                                value="{{ $item->item_name }}"
                              >
                              <div class="mb-2 mb-sm-0 pr-sm-4">
                                <span class="font-weight-bold d-block">{{ $item->item_name }}</span>
                                <small class="text-info">Requested quantity: {{ $requestedQuantity }}</small>
                                @if ($requiresDeposit)
                                  <small class="text-muted d-block">
                                    Deposit: ₱{{ number_format($b->depositRate($item->item_name), 2) }} per item
                                  </small>
                                @endif
                              </div>
                              <div>
                                <div
                                  class="bmc-approval-stepper"
                                  data-bmc-approval-stepper
                                >
                                  <button
                                    type="button"
                                    class="bmc-approval-stepper-button"
                                    data-bmc-approval-decrease
                                    aria-label="Decrease approved quantity for {{ $item->item_name }}"
                                  >&minus;</button>
                                  <input
                                    type="number"
                                    class="bmc-approval-stepper-value"
                                    name="items[{{ $itemIndex }}][approved_quantity]"
                                    value="{{ $approvedQuantity }}"
                                    min="0"
                                    max="{{ $requestedQuantity }}"
                                    inputmode="numeric"
                                    data-bmc-approval-input
                                    aria-label="Approved quantity for {{ $item->item_name }}"
                                    required
                                  >
                                  <button
                                    type="button"
                                    class="bmc-approval-stepper-button"
                                    data-bmc-approval-increase
                                    aria-label="Increase approved quantity for {{ $item->item_name }}"
                                  >+</button>
                                </div>
                              </div>
                            </div>
                          @endforeach
                        </div>

                        <div class="form-group mb-0">
                          <label class="font-weight-bold" for="approvalRemarks{{ $b->id }}">Remarks</label>
                          <textarea
                            name="mbd_remarks"
                            id="approvalRemarks{{ $b->id }}"
                            class="form-control"
                            rows="3"
                            placeholder="Add an optional note for the requestor"
                          >{{ old('mbd_remarks') }}</textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

                    <div class="modal fade" id="declineBmc{{$b->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <form method="post" action="{{ url('/decline-bmc/'.$b->id) }}" onsubmit='show()'>
                          {{csrf_field()}}
                          <div class="modal-content border-0">
                            <div class="modal-header">
                              <h5 class="modal-title">Decline Request</h5>
                              <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <div class="bg-light rounded p-3 mb-3">
                                  <div class="d-flex align-items-center py-1" style="font-size: 13px;">
                                    <span class="text-muted font-weight-bold" style="width: 140px; flex: 0 0 140px;">
                                      Reference No.
                                    </span>
                                    <span class="font-weight-bold">{{$b->reference_no}}</span>
                                  </div>
                                  <div class="d-flex align-items-center py-1" style="font-size: 13px;">
                                    <span class="text-muted font-weight-bold" style="width: 140px; flex: 0 0 140px;">
                                      Requestor
                                    </span>
                                    <span class="font-weight-bold">{{$b->borrower_first_name}} {{$b->borrower_last_name}}</span>
                                  </div>
                                </div>
                                <label>Remarks <span class="text-danger">*</span></label>
                                <textarea name="mbd_remarks" class="form-control" rows="3" placeholder="Enter declining remarks" required></textarea>
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

                    <div class="modal fade" id="detailsBmc{{$b->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content border-0">
                          <div class="modal-header">
                            <h5 class="modal-title">Request Details</h5>
                            <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          </div>
                          <div class="modal-body">
                            <style>
                              .bmc-section { border: 1px solid #e3e6ed; border-radius: .6rem; margin-bottom: 1.4rem; overflow: hidden; }
                              .bmc-section-header { display: flex; align-items: center; justify-content: space-between; padding: .9rem 1.2rem; background: #f8f9fc; border-bottom: 1px solid #e3e6ed; }
                              .bmc-section-header h5 { margin: 0; font-size: .95rem; font-weight: 600; }
                              .bmc-section-header h5 i { color: #007bff; }
                              .bmc-section-body { padding: 1.2rem; background: #fff; }
                              .info-table td { border: none; padding: .45rem .5rem; vertical-align: top; }
                              .info-table td:first-child { color: #6c757d; font-size: .82rem; font-weight: 600; width: 30%; }
                              .info-table td:last-child { font-size: .88rem; }
                            </style>

                            <div class="bmc-section">
                              <div class="bmc-section-header">
                                <h5><i class="ti-user mr-2"></i>I. Borrower's Information</h5>
                              </div>
                              <div class="bmc-section-body">
                                <table class="table info-table">
                                  <tr><td>Borrower's Profile</td><td class="text-primary" style="font-weight: bold;">{{ $b->borrower_profile }}</td></tr>
                                  <tr><td>Borrower's Full Name</td><td>{{ $b->borrower_first_name }} {{ $b->borrower_last_name }}</td></tr>
                                  @if ($b->department) <tr><td>Department</td><td>{{ $b->department ?? '-'}}</td></tr> @endif
                                  @if ($b->business_name) <tr><td>Business Name</td><td>{{ $b->business_name ?? '-'}}</td></tr> @endif
                                  @if ($b->designation)<tr><td>Designation</td><td>{{ $b->designation ?? '-'}}</td></tr> @endif
                                  <tr><td>Email</td><td>{{ $b->email }}</td></tr>
                                  @if ($b->hub_code)<tr><td>Hub Code</td><td>{{ $b->hub_code ?? '-'}}</td></tr> @endif
                                  @if ($b->hub_name)<tr><td>Hub Name</td><td>{{ $b->hub_name ?? '-'}}</td></tr> @endif
                                  <tr><td>Phone Number</td><td>{{ $b->phone_number }}</td></tr>
                                </table>
                              </div>
                            </div>

                            <div class="bmc-section">
                              <div class="bmc-section-header">
                                <h5><i class="ti-calendar mr-2"></i>II. Event Details</h5>
                              </div>
                              <div class="bmc-section-body">
                                <table class="table info-table">
                                  <tr><td>Date Needed</td><td>{{ optional($b->date_needed)->format('F j, Y') }}</td></tr>
                                  <tr><td>Event Name</td><td>{{ $b->event_name }}</td></tr>
                                  <tr><td>Request Details</td><td>{{ $b->request_details }}</td></tr>
                                  <tr><td>No. of days needed</td><td>{{ $b->number_of_days_item_needed }} day(s)</td></tr>
                                </table>
                              </div>
                            </div>

                            <div class="bmc-section">
                              <div class="bmc-section-header">
                                <h5><i class="ti-gift mr-2"></i>III. Item for Borrowing</h5>
                              </div>
                              <div class="bmc-section-body">
                                <table class="table info-table">
                                  @foreach ($b->borrowedItems as $item)
                                  <tr>
                                    <td>Item</td>
                                    <td>
                                      {{ $item->item_name }} &times; {{ $item->requested_quantity }}
                                    </td>
                                  </tr>
                                  @endforeach
                                </table>
                              </div>
                            </div>

                            <div class="bmc-section">
                              <div class="bmc-section-header">
                                <h5><i class="ti-check-box mr-2"></i>IV. Approval Details</h5>
                              </div>
                              <div class="bmc-section-body">
                                <table class="table info-table">
                                  <tr><td>Status</td><td><span class="badge badge-{{ $b->status == 'Approved' ? 'success' : ($b->status == 'Declined' ? 'danger' : ($b->status == 'Cancelled' ? 'secondary' : 'warning')) }}">{{ $b->status }}</span></td></tr>
                                  @if ($b->approved_by)
                                    @php $approver_name = \App\User::find($b->approved_by); @endphp
                                    <tr><td>Approved / Declined By</td><td>{{ $approver_name ? $approver_name->name : $b->approved_by }}</td></tr>
                                  @endif
                                  @if ($b->approved_date)
                                    <tr><td>Approved At</td><td>{{ date('M d, Y h:i A', strtotime($b->approved_date)) }}</td></tr>
                                  @endif
                                  @if ($b->mbd_remarks)
                                    <tr><td>Remarks</td><td>{{ $b->mbd_remarks }}</td></tr>
                                  @endif
                                </table>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .bmc-approval-stepper {
    display: grid;
    grid-template-columns: 44px 64px 44px;
    flex: 0 0 152px;
    width: 152px;
    overflow: hidden;
    border: 1px solid #aeb8c4;
    border-radius: 10px;
    background: #ffffff;
  }

  .bmc-approval-stepper-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    padding: 0;
    border: 0;
    background: #f4f6f8;
    color: #374151;
    font-size: 20px;
    font-weight: 700;
    line-height: 1;
    cursor: pointer;
  }

  .bmc-approval-stepper-button:hover:not(:disabled) {
    background: #e5e9ee;
  }

  .bmc-approval-stepper-button:focus,
  .bmc-approval-stepper-value:focus {
    position: relative;
    z-index: 1;
    outline: 2px solid #59c2e5;
    outline-offset: -2px;
  }

  .bmc-approval-stepper-button:disabled {
    background: #f8f9fa;
    color: #9aa3ad;
    cursor: not-allowed;
    opacity: 1;
  }

  .bmc-approval-stepper-value {
    width: 64px;
    min-width: 0;
    height: 44px;
    padding: 0 6px;
    border: 0;
    border-right: 1px solid #aeb8c4;
    border-left: 1px solid #aeb8c4;
    border-radius: 0;
    outline: 0;
    background: #ffffff;
    color: #1f2937;
    font-size: 17px;
    font-weight: 700;
    line-height: 44px;
    text-align: center;
    opacity: 1;
    appearance: textfield;
  }

  .bmc-approval-stepper-value::-webkit-inner-spin-button,
  .bmc-approval-stepper-value::-webkit-outer-spin-button {
    margin: 0;
    -webkit-appearance: none;
  }
</style>

<script>
  $(document).ready(function () {
    function syncBmcApprovalStepper($stepper) {
      var $input = $stepper.find('[data-bmc-approval-input]');
      var minimum = parseInt($input.attr('min'), 10);
      var maximum = parseInt($input.attr('max'), 10);
      var value = parseInt($input.val(), 10);

      minimum = isNaN(minimum) ? 0 : minimum;
      maximum = isNaN(maximum) ? 99 : maximum;
      value = isNaN(value) ? minimum : Math.max(minimum, Math.min(maximum, value));

      $input.val(value);
      $stepper.find('[data-bmc-approval-decrease]').prop('disabled', value <= minimum);
      $stepper.find('[data-bmc-approval-increase]').prop('disabled', value >= maximum);
    }

    $('[data-bmc-approval-stepper]').each(function () {
      syncBmcApprovalStepper($(this));
    });

    $(document).on('click', '[data-bmc-approval-decrease]', function () {
      var $stepper = $(this).closest('[data-bmc-approval-stepper]');
      var $input = $stepper.find('[data-bmc-approval-input]');

      $input.val((parseInt($input.val(), 10) || 0) - 1);
      syncBmcApprovalStepper($stepper);
    });

    $(document).on('click', '[data-bmc-approval-increase]', function () {
      var $stepper = $(this).closest('[data-bmc-approval-stepper]');
      var $input = $stepper.find('[data-bmc-approval-input]');

      $input.val((parseInt($input.val(), 10) || 0) + 1);
      syncBmcApprovalStepper($stepper);
    });

    $(document).on('input change', '[data-bmc-approval-input]', function () {
      syncBmcApprovalStepper($(this).closest('[data-bmc-approval-stepper]'));
    });
  });
</script>

@endsection
