@extends('layouts.header')
@section('css_header')
<style>
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

  .bmc-summary-caption {
    margin-top: 9px;
    overflow: hidden;
    color: #94a3b8;
    font-size: 0.78rem;
    font-weight: 600;
    text-overflow: ellipsis;
    white-space: nowrap;
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
    .bmc-summary-card .card-body {
      min-height: auto;
    }
  }
</style>
@endsection
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row grid-margin bmc-summary-cards">
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('bmc.index', ['status' => 'For Approval']) }}" class="card bmc-summary-card {{ $status === 'For Approval' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-waiting"><i class="ti-time"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="For Approval">For Approval</div>
              <div class="bmc-summary-value">{{ $for_approval_count }}</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('bmc.index', ['status' => 'Approved']) }}" class="card bmc-summary-card {{ $status === 'Approved' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-approved"><i class="ti-check-box"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="Approved">Approved</div>
              <div class="bmc-summary-value">{{ $approved_count }}</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('bmc.index', ['status' => \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED]) }}" class="card bmc-summary-card {{ $status === \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-partial"><i class="ti-package"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="Partial Released">Partial Released</div>
              <div class="bmc-summary-value">{{ $partial_release_count }}</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('bmc.index', ['status' => 'Released']) }}" class="card bmc-summary-card {{ $status === 'Released' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-released"><i class="ti-truck"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="Released">Released</div>
              <div class="bmc-summary-value">{{ $released_count }}</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('bmc.index', ['return_status' => \App\BmcAccountability::STATUS_PARTIALLY_RETURNED]) }}" class="card bmc-summary-card {{ $return_status === \App\BmcAccountability::STATUS_PARTIALLY_RETURNED ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-return"><i class="ti-reload"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="Partial Return">Partial Return</div>
              <div class="bmc-summary-value">{{ $partial_return_count }}</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('bmc.index', ['status' => 'Closed']) }}" class="card bmc-summary-card {{ $status === 'Closed' ? 'is-active' : '' }}">
          <div class="card-body">
            <span class="bmc-summary-icon bmc-icon-closed"><i class="ti-lock"></i></span>
            <div class="bmc-summary-content">
              <div class="bmc-summary-label" title="Closed">Closed</div>
              <div class="bmc-summary-value">{{ $closed_count }}</div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <div class="d-flex flex-column">
                  <h4 class="card-title">Borrowing Marketing Collateral Request</h4>
              </div>
              <a href="{{ route('bmc.create') }}" class="d-flex gap-1 align-items-center btn btn-outline-info btn-sm">
                <i class="ti-plus mr-1"></i>
                <span>New Request</span>
              </a>
            </div>

            <form method="get" action="{{ route('bmc.index') }}" enctype="multipart/form-data">
              <input type="hidden" name="return_status" value="{{ $return_status ?? '' }}">
              <div class="row">
                <div class="col-md-4">
                  <div class="d-flex form-group flex-column">
                    <label class="mb-1">Search</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search ref no., borrower, event, hub..." />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="mb-1">Status</label>
                    <select name="status" class="form-control form-control-sm">
                      <option value="">All</option>
                      <option value="For Approval" @if($status == 'For Approval') selected @endif>For Approval</option>
                      <option value="Approved" @if($status == 'Approved') selected @endif>Approved</option>
                      <option value="{{ \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED }}" @if($status === \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED) selected @endif>Partially Released</option>
                      <option value="Released" @if($status == 'Released') selected @endif>Released</option>
                      <option value="Declined" @if($status == 'Declined') selected @endif>Declined</option>
                      <option value="Cancelled" @if($status == 'Cancelled') selected @endif>Cancelled</option>
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
                    <th style="background-color: #e6e6e6; color: #666;">Action</th>
                    <th style="background-color: #e6e6e6; color: #666;">Reference No.</th>
                    <th style="background-color: #e6e6e6; color: #666;">Borrower</th>
                    <th style="background-color: #e6e6e6; color: #666;">Signed Accountability</th>
                    <th style="background-color: #e6e6e6; color: #666;">Event Name</th>
                    <th style="background-color: #e6e6e6; color: #666;">Date</th>
                    <th style="background-color: #e6e6e6; color: #666;">Items</th>
                    <th class="bmc-remarks-column" style="background-color: #e6e6e6; color: #666;">Remarks</th>
                    <th style="background-color: #e6e6e6; color: #666;">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($requests as $request)
                  <tr class="align-middle">
                      <td class="text-center">
                          @if ($request->status === \App\MarketingCollateralBorrowing::STATUS_FOR_APPROVAL)
                            <a
                              href="{{ route('bmc.edit', $request->id) }}"
                              class="btn btn-warning btn-rounded btn-icon d-inline-flex align-items-center justify-content-center"
                              style="vertical-align: middle;"
                              title="Edit Request"
                            >
                              <i class="ti-pencil"></i>
                            </a>
                          @endif
                          <a href="{{ route('bmc.show', $request->id) }}" title="View">
                              <button type="button" class="btn btn-info btn-rounded btn-icon" title="View">
                                  <i class="ti-eye btn-icon-prepend"></i>
                              </button>
                          </a>
                      </td>
                      <td style="white-space: nowrap;">
                        <span class="badge badge-primary badge-pill">{{ $request->reference_no }}</span>
                      </td>
                      <td style="white-space: nowrap;">
                          {{ $request->borrower_first_name }} {{ $request->borrower_last_name }}<br>
                          <small class="text-info">{{ $request->borrower_profile }}</small>
                          @if ($request->department) <small class="text-info">{{ $request->department }}</small> @endif
                      </td>
                      <td style="font-size: 10px; line-height: 1.6; white-space: nowrap;">
                        @forelse ($request->accountabilities as $accountability)
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
                            <small class="badge badge-info d-block mt-1">
                              Awaiting Return Confirmation
                            </small>
                          @endif
                        @empty
                          <span class="text-muted">—</span>
                        @endforelse
                      </td>
                      <td>{{ $request->event_name }}</td>
                      <td style="white-space: nowrap;">
                        @php
                          $displayAccountability = $request->accountabilities
                            ->whereIn('status', [
                              \App\BmcAccountability::STATUS_RELEASED,
                              \App\BmcAccountability::STATUS_FOR_RETURN_CONFIRMATION,
                              \App\BmcAccountability::STATUS_PARTIALLY_RETURNED,
                            ])
                            ->sortBy('expected_return_at')
                            ->first();

                          if (!$displayAccountability) {
                            $displayAccountability = $request->accountabilities
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
                                  {{ optional($request->date_needed)->format('M d, Y') }}
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
                          $releasedByItem = $request->accountabilities
                            ->flatMap(function ($accountability) {
                              return $accountability->items;
                            })
                            ->groupBy('item_name')
                            ->map(function ($items) {
                              return $items->sum(function ($item) {
                                return (int) $item->released_quantity;
                              });
                            });
                          $usesApprovedQuantity = in_array($request->status, [
                            \App\MarketingCollateralBorrowing::STATUS_APPROVED,
                            \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED,
                            \App\MarketingCollateralBorrowing::STATUS_RELEASED,
                            \App\MarketingCollateralBorrowing::STATUS_CLOSED,
                          ], true);
                        @endphp
                        @foreach ($request->borrowedItems as $item)
                          @php
                            $targetQuantity = $usesApprovedQuantity
                              ? (int) ($item->approved_quantity ?? 0)
                              : (int) $item->requested_quantity;
                            $releasedQuantity = (int) $releasedByItem
                              ->get($item->item_name, 0);
                            $itemLabel = [
                              'Negosyo Partner Roll Up Banner' => 'Partner Banner',
                              'End User Roll Up Banner' => 'End User Banner',
                            ][$item->item_name] ?? $item->item_name;
                            $fullyReleased =
                              $targetQuantity > 0 &&
                              $releasedQuantity >= $targetQuantity;
                          @endphp
                          @if ($targetQuantity > 0)
                            <div class="d-flex justify-content-between font-weight-bold">
                              <span class="mr-3" title="{{ $item->item_name }}">{{ $itemLabel }}:</span>
                              <span style="color: {{ $fullyReleased ? 'blue' : 'red' }};">
                                {{ $releasedQuantity }}/{{ $targetQuantity }}
                              </span>
                            </div>
                          @endif
                        @endforeach
                      </td>
                      <td class="bmc-remarks-column">
                        <div class="bmc-remarks-text" title="{{ $request->mbd_remarks ?? '-' }}">
                          {{ $request->mbd_remarks ?? '-' }}
                        </div>
                      </td>
                    <td>
                        @if ($request->status === 'Approved')
                            <span class="badge badge-success">Approved</span>
                        @elseif ($request->status === 'Declined')
                            <span class="badge badge-danger">Declined</span>
                        @elseif ($request->status === 'Cancelled')
                            <span class="badge badge-danger">Cancelled</span>
                        @elseif ($request->status === 'For Approval')
                            <span class="badge badge-primary">For Approval</span>
                        @elseif ($request->status === \App\MarketingCollateralBorrowing::STATUS_PARTIALLY_RELEASED)
                            <span class="badge badge-info">Partially Released</span>
                        @elseif ($request->status === 'Released')
                            <span class="badge badge-info">Released</span>
                        @elseif ($request->status === 'Closed')
                            <span class="badge badge-secondary text-white">Closed</span>
                        @else
                            <span class="badge badge-secondary text-white">
                                {{ $request->status }}
                            </span>
                        @endif
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="9" class="text-center text-muted py-4">No records found.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <div class="mt-3">{{ $requests->appends(request()->query())->links() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
