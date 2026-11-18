@extends('layouts.header')

@section('content')
@php
  $borrowedItemValues = collect(old(
    'borrowed_items',
    $borrowing->borrowedItems
      ->map(function ($item) {
        return [
          'item_name' => $item->item_name,
          'requested_quantity' => $item->requested_quantity,
        ];
      })
      ->all()
  ))->keyBy('item_name');
@endphp

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center gap-1">
              <div class="d-flex flex-column">
                <div class="d-inline-block mb-1" style="width: fit-content;">
                  <h4 class="card-title mb-1">Edit Borrowing Marketing Collateral</h4>
                  <div class="d-flex">
                    <div class="w-100" style="border-top: 3px solid #59c2e5;"></div>
                    <div class="w-100" style="border-top: 3px solid #fe0001;"></div>
                  </div>
                </div>
                <small class="text-muted">{{ $borrowing->reference_no }}</small>
              </div>

              <a href="{{ route('bmc.show', $borrowing->id) }}" class="d-flex align-items-center btn btn-outline-light btn-sm">
                <i class="ti-arrow-left mr-1"></i>
                <span>Back</span>
              </a>
            </div>

            @if ($errors->any())
              <div class="alert alert-danger">
                <strong>Please check the information below.</strong>
                <ul class="mb-0 mt-2">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form action="{{ route('bmc.update', $borrowing->id) }}" method="POST">
              @csrf
              @method('PUT')

              <div class="row mt-4">
                <div class="col-md-12 mb-3">
                  <h4 class="card-title" id="bmc-employee-information">I. Borrower's Information</h4>
                  <!-- <img -->
                  <!--   src="{{ asset('products/BMF Pic.6879bdf9706341.76162042.jpg') }}" -->
                  <!--   alt="Borrowing marketing collateral" -->
                  <!--   class="img-fluid d-block mx-auto" -->
                  <!-- > -->
                </div>

                <div class="col-md-12 mb-4">
                    <section aria-labelledby="bmc-employee-information">
                      <dl class="mb-0" style="font-size: 1rem;">
                        <div class="row mb-2">
                          <dt class="col-md-4 text-muted font-weight-normal mb-0">Full Name:</dt>
                          <dd class="col-md-8 text-break mb-0">
                            {{ trim(($borrowerData['borrower_first_name'] ?? '') . ' ' . ($borrowerData['borrower_last_name'] ?? '')) ?: '—' }}
                          </dd>
                        </div>
                        <div class="row mb-2">
                          <dt class="col-md-4 text-muted font-weight-normal mb-0">Department:</dt>
                          <dd class="col-md-8 text-break mb-0">{{ $borrowerData['department'] ?? '—' }}</dd>
                        </div>
                        <div class="row mb-2">
                          <dt class="col-md-4 text-muted font-weight-normal mb-0">Designation:</dt>
                          <dd class="col-md-8 text-break mb-0">{{ $borrowerData['designation'] ?? '—' }}</dd>
                        </div>
                        <div class="row mb-2">
                          <dt class="col-md-4 text-muted font-weight-normal mb-0">Email:</dt>
                          <dd class="col-md-8 text-break mb-0">{{ $borrowerData['email'] ?? '—' }}</dd>
                        </div>
                        <div class="row mb-2">
                          <dt class="col-md-4 text-muted font-weight-normal mb-0">Phone Number:</dt>
                          <dd class="col-md-8 text-break mb-0">{{ $borrowerData['phone_number'] ?? '—' }}</dd>
                        </div>
                      </dl>
                    </section>
                </div>

                <div class="w-100"></div>

                <div class="col-md-12 mb-3">
                  <h4 class="card-title">II. Event Details</h4>
                  <hr>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="date_needed" class="form-label">Date Needed&nbsp;<span class="text-danger">*</span></label>
                  <input
                    type="date"
                    class="form-control"
                    name="date_needed"
                    id="date_needed"
                    value="{{ old('date_needed', optional($borrowing->date_needed)->format('Y-m-d')) }}"
                    required
                  >
                </div>

                <div class="col-md-6 mb-3">
                  <label for="event_name" class="form-label">Event Name&nbsp;<span class="text-danger">*</span></label>
                  <input
                    type="text"
                    class="form-control"
                    name="event_name"
                    id="event_name"
                    value="{{ old('event_name', $borrowing->event_name) }}"
                    placeholder="Enter Event Name"
                    required
                  >
                </div>

                <div class="col-md-12 mb-3">
                  <label for="request_details" class="form-label">Request Details&nbsp;<span class="text-danger">*</span></label>
                  <textarea
                    class="form-control"
                    id="request_details"
                    name="request_details"
                    rows="3"
                    placeholder="Enter Request Details"
                    required
                  >{{ old('request_details', $borrowing->request_details) }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="number_of_days_item_needed" class="form-label">Number of Days Item is Needed&nbsp;<span class="text-danger">*</span></label>
                  <input
                    type="number"
                    class="form-control"
                    name="number_of_days_item_needed"
                    id="number_of_days_item_needed"
                    value="{{ old('number_of_days_item_needed', $borrowing->number_of_days_item_needed) }}"
                    min="1"
                    max="40"
                    placeholder="Maximum Allowed: 40 days"
                    required
                  >
                </div>

                <div class="w-100"></div>

                <div class="col-md-12 mb-3">
                  <h4 class="card-title">III. Item for Borrowing</h4>
                  <hr>
                  <h4>Deposit Value</h4>
                  <img
                    src="{{ asset('products/BMC_ITEMS.png') }}"
                    alt="BMF deposit values"
                    class="img-fluid d-block mx-auto"
                  >
                </div>

                <div class="col-md-12 mb-3">
                  <div class="bmc-item-picker">
                    <div class="mb-4">
                      <h5 class="mb-1 font-weight-bold">Edit item(s) selection</h5>
                    </div>

                    <div class="row mx-n2">
                      @foreach (\App\MarketingCollateralBorrowing::BORROWABLE_ITEMS as $index => $item)
                      @php
                        $quantity = (int) data_get(
                          $borrowedItemValues->get($item),
                          'requested_quantity',
                          0
                        );
                      @endphp
                        <div class="col-lg-4 col-md-6 px-2 mb-3">
                          <div class="bmc-item-card h-100" data-bmc-quantity-row>
                            <input type="hidden" name="borrowed_items[{{ $index }}][item_name]" value="{{ $item }}">

                            <div class="d-flex align-items-start justify-content-between mb-3">
                              <span class="bmc-item-icon" aria-hidden="true">
                                <i class="ti-package"></i>
                              </span>
                              <span class="bmc-item-state" data-bmc-item-state>Not selected</span>
                            </div>

                            <label for="edit_borrowed_item_quantity_{{ $index }}" class="bmc-item-name">
                              {{ $item }}
                            </label>
                            <small class="text-muted d-block mb-3">Requested quantity</small>

                            <div class="bmc-quantity-control">
                              <button
                                type="button"
                                class="bmc-quantity-button bmc-quantity-button-minus"
                                data-bmc-quantity-decrease
                                aria-label="Decrease {{ $item }} quantity"
                              >
                                &minus;
                              </button>
                              <input
                                type="number"
                                id="edit_borrowed_item_quantity_{{ $index }}"
                                name="borrowed_items[{{ $index }}][requested_quantity]"
                                value="{{ $quantity }}"
                                min="0"
                                max="99"
                                inputmode="numeric"
                                data-bmc-quantity-input
                                aria-label="Requested quantity for {{ $item }}"
                                required
                              >
                              <button
                                type="button"
                                class="bmc-quantity-button bmc-quantity-button-plus"
                                data-bmc-quantity-increase
                                aria-label="Increase {{ $item }} quantity"
                              >
                                +
                              </button>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal-footer d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center">
                <a href="{{ route('bmc.show', $borrowing->id) }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Request</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<style>
  .bmc-item-picker {
    padding: 24px;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #f8fafc;
  }

  .bmc-item-card {
    display: flex;
    flex-direction: column;
    padding: 18px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: #ffffff;
    transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
  }

  .bmc-item-card:hover {
    border-color: #bae6fd;
    box-shadow: 0 6px 18px rgba(15, 23, 42, .06);
  }

  .bmc-item-card.is-selected {
    border-color: #59c2e5;
    background: #f0faff;
    box-shadow: 0 8px 22px rgba(89, 194, 229, .16);
    transform: translateY(-1px);
  }

  .bmc-item-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #eef2f7;
    color: #64748b;
    font-size: 18px;
  }

  .bmc-item-card.is-selected .bmc-item-icon {
    background: #dff6fd;
    color: #1485a8;
  }

  .bmc-item-state {
    padding: 5px 9px;
    border-radius: 999px;
    background: #f1f5f9;
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .02em;
  }

  .bmc-item-card.is-selected .bmc-item-state {
    background: #dff6fd;
    color: #0f6f8e;
  }

  .bmc-item-name {
    min-height: 44px;
    margin-bottom: 2px;
    color: #1f2937;
    font-size: 15px;
    font-weight: 700;
    line-height: 1.4;
  }

  .bmc-quantity-control {
    display: grid;
    grid-template-columns: 46px minmax(58px, 1fr) 46px;
    align-items: stretch;
    width: 100%;
    margin-top: auto;
    overflow: hidden;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    background: #ffffff;
  }

  .bmc-quantity-control input {
    width: 100%;
    min-width: 0;
    border: 0;
    border-right: 1px solid #e2e8f0;
    border-left: 1px solid #e2e8f0;
    outline: 0;
    background: #ffffff;
    color: #0f172a;
    font-size: 18px;
    font-weight: 700;
    text-align: center;
    appearance: textfield;
  }

  .bmc-quantity-control input::-webkit-outer-spin-button,
  .bmc-quantity-control input::-webkit-inner-spin-button {
    margin: 0;
    -webkit-appearance: none;
  }

  .bmc-quantity-button {
    min-height: 44px;
    border: 0;
    outline: 0;
    font-size: 22px;
    font-weight: 700;
    transition: background-color .15s ease, color .15s ease;
  }

  .bmc-quantity-button-minus {
    background: #f8fafc;
    color: #475569;
  }

  .bmc-quantity-button-plus {
    background: #59c2e5;
    color: #ffffff;
  }

  .bmc-quantity-button:hover:not(:disabled) {
    filter: brightness(.96);
  }

  .bmc-quantity-button:focus {
    box-shadow: inset 0 0 0 3px rgba(37, 99, 235, .2);
  }

  .bmc-quantity-button:disabled {
    cursor: not-allowed;
    opacity: .42;
  }

  @media (max-width: 575.98px) {
    .bmc-item-picker {
      padding: 18px;
    }

    .bmc-item-name {
      min-height: auto;
    }
  }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $('[data-bmc-quantity-row]').each(function () {
      var $row = $(this);
      var $input = $row.find('[data-bmc-quantity-input]');

      function syncState() {
        var quantity = parseInt($input.val(), 10) || 0;
        $row.find('[data-bmc-quantity-decrease]').prop('disabled', quantity <= 0);
        $row.find('[data-bmc-quantity-increase]').prop('disabled', quantity >= 99);
        $row.toggleClass('is-selected', quantity > 0);
        $row.find('[data-bmc-item-state]').text(quantity > 0 ? 'Selected' : 'Not selected');
      }

      $row.find('[data-bmc-quantity-decrease]').on('click', function () {
        $input.val(Math.max(0, (parseInt($input.val(), 10) || 0) - 1));
        syncState();
      });

      $row.find('[data-bmc-quantity-increase]').on('click', function () {
        $input.val(Math.min(99, (parseInt($input.val(), 10) || 0) + 1));
        syncState();
      });

      $input.on('input change', function () {
        var quantity = Math.max(0, Math.min(99, parseInt($input.val(), 10) || 0));
        $input.val(quantity);
        syncState();
      });

      syncState();
    });
  });
</script>
