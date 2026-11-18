@extends('layouts.header')

@section('content')
@php
  $pointPersonRoles = [
    'Area Distributor',
    'AD Representative 1',
    'AD Representative 2',
  ];

  $products = [
    'FILLED 230G CYLINDERS',
    'FILLED 330G CYLINDERS',
    'STOVE KIT 230G',
    'STOVE KIT 330G',
    'GRILLER KIT 230G',
    'GRILLER KIT 330G',
    'TORCH KIT 230G',
    'TORCH KIT 330G',
    'GRILLER KIT V2',
    'TORCH KIT V2',
    'STOVEKIT V2',
    'CRATE 230G',
    'CRATE 330G',
  ];

  $otherMatters = [
    'Is AD carrying other brand of micro cylinders?',
    'Is Booth available?',
    'Are Dealer Tarpaulin available?',
    'Is Store Signage available?',
    'Status of LTO Permit?',
  ];

  $employee = optional(auth()->user())->employee;
  $employeeName = trim(
    (optional($employee)->first_name ?? '') . ' ' .
    (optional($employee)->last_name ?? '')
  );
@endphp

<div class="main-panel">
  <div class="content-wrapper asvc-create-page">
    <div class="row justify-content-center">
      <div class="col-12 col-xl-11 grid-margin stretch-card">
        <div class="card asvc-form-card">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="asvc-page-header d-flex justify-content-between align-items-center">
              <div class="d-flex flex-column">
                <div class="d-inline-block mb-1" style="width: fit-content;">
                  <h4 class="card-title mb-1">AD Store Visit Report</h4>
                  <div class="d-flex">
                    <div class="w-100" style="border-top: 3px solid #59c2e5;"></div>
                    <div class="w-100" style="border-top: 3px solid #fe0001;"></div>
                  </div>
                </div>
                <small class="text-muted">CEO-ADC-FOR-010-000</small>
              </div>

              <a href="{{ route('ad-store-visit-reports.index') }}" class="d-flex align-items-center btn btn-outline-secondary btn-sm asvc-back-button">
                <i class="ti-arrow-left mr-1"></i>
                <span>Back</span>
              </a>
            </div>

            <form action="{{ route('ad-store-visit-reports.store') }}" method="POST" enctype="multipart/form-data" class="asvc-create-form">
              @csrf

              <style>
                .asvc-create-page {
                  background: #f4f7fb;
                }
                .asvc-form-card {
                  overflow: hidden;
                  border: 0;
                  border-radius: .9rem;
                  box-shadow: 0 8px 28px rgba(23, 43, 77, .08);
                }
                .asvc-page-header {
                  flex-wrap: wrap;
                  gap: 1rem;
                  padding-bottom: 1.25rem;
                  border-bottom: 1px solid #e6ebf2;
                }
                .asvc-back-button {
                  flex: 0 0 auto;
                }
                .asvc-create-form {
                  max-width: 1180px;
                  margin: 0 auto;
                }
                .asvc-required-note {
                  margin: 1rem 0 0;
                  color: #667085;
                  font-size: .82rem;
                  text-align: right;
                }
                .asvc-alert {
                  margin-top: 1rem;
                  border: 0;
                  border-radius: .65rem;
                }
                .asvc-form-section {
                  margin-top: 1.25rem;
                  padding: 1.25rem;
                  background: #fff;
                  border: 1px solid #e3e9f1;
                  border-radius: .75rem;
                }
                .asvc-section-heading {
                  display: flex;
                  align-items: flex-start;
                  gap: .75rem;
                  margin-bottom: 1.25rem;
                  padding-bottom: 1rem;
                  border-bottom: 1px solid #edf0f5;
                }
                .asvc-section-icon {
                  display: inline-flex;
                  align-items: center;
                  justify-content: center;
                  flex: 0 0 38px;
                  width: 38px;
                  height: 38px;
                  color: #2468b3;
                  background: #eaf3ff;
                  border-radius: .55rem;
                }
                .asvc-section-title {
                  margin: 0;
                  color: #17233c;
                  font-size: 1.05rem;
                  font-weight: 700;
                }
                .asvc-section-description {
                  display: block;
                  margin-top: .2rem;
                  color: #7a8699;
                }
                .asvc-guidance {
                  display: flex;
                  align-items: flex-start;
                  gap: .65rem;
                  margin-bottom: 1.25rem;
                  padding: .8rem .9rem;
                  color: #31547e;
                  background: #eef6ff;
                  border: 1px solid #cfe2f8;
                  border-radius: .6rem;
                  line-height: 1.45;
                }
                .asvc-guidance i {
                  margin-top: .15rem;
                }
                .asvc-create-form .form-label {
                  margin-bottom: .45rem;
                  color: #27364f;
                  font-weight: 600;
                }
                .asvc-create-form .form-control {
                  min-height: 44px;
                  border-color: #ccd5e2;
                  border-radius: .45rem;
                }
                .asvc-create-form textarea.form-control {
                  min-height: auto;
                  resize: vertical;
                }
                .asvc-create-form .form-control:focus {
                  border-color: #4b8ee8;
                  box-shadow: 0 0 0 .15rem rgba(75, 142, 232, .15);
                }
                .asvc-create-form .select2-container {
                  display: block;
                  width: 100% !important;
                  max-width: 100%;
                }
                .asvc-create-form .select2-container .select2-selection--single {
                  height: 44px;
                  border-color: #ccd5e2;
                  border-radius: .45rem;
                }
                .asvc-create-form .select2-container .select2-selection--single .select2-selection__rendered {
                  display: flex;
                  align-items: center;
                  height: 100%;
                  padding-right: 2.25rem;
                  padding-left: .85rem;
                  padding-top: 0 !important;
                  padding-bottom: 0 !important;
                  overflow: hidden;
                  line-height: 1.35;
                  text-overflow: ellipsis;
                  white-space: nowrap;
                }
                .asvc-create-form .select2-container .select2-selection--single .select2-selection__arrow {
                  top: 0;
                  bottom: 0;
                  height: auto;
                  right: .55rem;
                }
                .asvc-create-form .select2-container--focus .select2-selection--single,
                .asvc-create-form .select2-container--open .select2-selection--single {
                  border-color: #4b8ee8;
                  box-shadow: 0 0 0 .15rem rgba(75, 142, 232, .15);
                }
                .asvc-table-wrap {
                  overflow-x: auto;
                  border: 1px solid #e1e7ef;
                  border-radius: .65rem;
                }
                .asvc-table {
                  min-width: 760px;
                  margin-bottom: 0;
                }
                .asvc-table thead th {
                  padding: .75rem;
                  color: #42526b;
                  background: #f6f8fb;
                  border-top: 0;
                  border-bottom: 1px solid #dfe5ec;
                  font-size: .78rem;
                  font-weight: 700;
                  letter-spacing: .02em;
                  text-transform: uppercase;
                  white-space: nowrap;
                }
                .asvc-table td {
                  padding: .7rem;
                  vertical-align: middle;
                  border-top-color: #edf0f4;
                }
                .asvc-table .form-control {
                  min-height: 39px;
                  font-size: .875rem;
                }
                .asvc-row-label {
                  min-width: 190px;
                  max-width: 100%;
                  color: #27364f;
                  font-weight: 600;
                  overflow-wrap: anywhere;
                  white-space: normal !important;
                  word-break: break-word;
                }
                .asvc-check-cell {
                  width: 95px;
                  text-align: center;
                }
                .asvc-check {
                  display: inline-flex;
                  align-items: center;
                  gap: .45rem;
                  margin: 0;
                  color: #52647d;
                  cursor: pointer;
                }
                .asvc-check input {
                  width: 17px;
                  height: 17px;
                }
                .asvc-account-number {
                  display: inline-flex;
                  align-items: center;
                  justify-content: center;
                  width: 28px;
                  height: 28px;
                  color: #31547e;
                  background: #eef6ff;
                  border-radius: 50%;
                  font-weight: 700;
                }
                .asvc-file-zone {
                  padding: 1rem;
                  background: #f8fafc;
                  border: 1px dashed #b8c6d8;
                  border-radius: .65rem;
                }
                .asvc-file-list {
                  display: grid;
                  grid-template-columns: repeat(2, minmax(0, 1fr));
                  gap: .55rem;
                  margin-top: .8rem;
                }
                .asvc-file-item {
                  display: flex;
                  align-items: center;
                  min-width: 0;
                  padding: .65rem .75rem;
                  color: #32435b;
                  background: #fff;
                  border: 1px solid #dfe6ee;
                  border-radius: .45rem;
                }
                .asvc-file-item i {
                  flex: 0 0 auto;
                  margin-right: .6rem;
                  color: #4b8ee8;
                }
                .asvc-file-name {
                  min-width: 0;
                  flex: 1 1 auto;
                  overflow: hidden;
                  text-overflow: ellipsis;
                  white-space: nowrap;
                }
                .asvc-file-remove {
                  display: inline-flex;
                  align-items: center;
                  justify-content: center;
                  flex: 0 0 30px;
                  width: 30px;
                  height: 30px;
                  margin-left: .65rem;
                  padding: 0;
                  color: #667085;
                  background: transparent;
                  border: 0;
                  border-radius: 50%;
                  font-size: 1.35rem;
                  line-height: 1;
                  cursor: pointer;
                }
                .asvc-file-remove:hover,
                .asvc-file-remove:focus {
                  color: #fff;
                  background: #dc3545;
                  outline: none;
                }
                .asvc-form-actions {
                  display: flex;
                  justify-content: flex-end;
                  gap: .65rem;
                  margin-top: 1.25rem;
                  padding-top: 1.25rem;
                  border-top: 1px solid #e6ebf2;
                }
                .asvc-form-actions .btn {
                  min-width: 130px;
                }
                @media (max-width: 767.98px) {
                  .asvc-create-page {
                    padding: 1rem !important;
                  }
                  .asvc-form-section {
                    padding: 1rem;
                  }
                  .asvc-table-wrap {
                    overflow: visible;
                    border: 0;
                    border-radius: 0;
                  }
                  .asvc-table {
                    display: block;
                    min-width: 0;
                  }
                  .asvc-table thead {
                    display: none;
                  }
                  .asvc-table tbody,
                  .asvc-table tr,
                  .asvc-table td {
                    display: block;
                    width: 100%;
                  }
                  .asvc-table tr {
                    margin-bottom: .85rem;
                    padding: .85rem;
                    background: #fff;
                    border: 1px solid #e1e7ef;
                    border-radius: .65rem;
                  }
                  .asvc-table tr:last-child {
                    margin-bottom: 0;
                  }
                  .asvc-table td {
                    padding: .42rem 0;
                    border: 0;
                    text-align: left;
                  }
                  .asvc-table td::before {
                    display: block;
                    margin-bottom: .3rem;
                    color: #667085;
                    content: attr(data-label);
                    font-size: .74rem;
                    font-weight: 700;
                    letter-spacing: .02em;
                    text-transform: uppercase;
                  }
                  .asvc-table td.asvc-check-cell {
                    text-align: left;
                  }
                  .asvc-row-label {
                    min-width: 0;
                    padding-right: .35rem !important;
                  }
                  .asvc-file-list {
                    grid-template-columns: 1fr;
                  }
                }
                @media (max-width: 575.98px) {
                  .asvc-create-page {
                    padding: .65rem !important;
                  }
                  .asvc-form-card {
                    border-radius: .65rem;
                  }
                  .asvc-page-header {
                    align-items: flex-start !important;
                  }
                  .asvc-page-header h4 {
                    font-size: 1.05rem;
                  }
                  .asvc-back-button span {
                    display: none;
                  }
                  .asvc-back-button .mr-1 {
                    margin-right: 0 !important;
                  }
                  .asvc-required-note {
                    text-align: left;
                  }
                  .asvc-section-heading {
                    gap: .6rem;
                  }
                  .asvc-form-actions {
                    flex-direction: column-reverse;
                  }
                  .asvc-form-actions .btn {
                    width: 100%;
                  }
                }
              </style>

              @if ($errors->any())
                <div class="alert alert-danger asvc-alert" role="alert">
                  <div class="font-weight-bold mb-1"><i class="ti-alert mr-1"></i>Please review the highlighted information.</div>
                  <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <section class="asvc-form-section" aria-labelledby="asvc-visit-information">
                <div class="asvc-section-heading">
                  <span class="asvc-section-icon"><i class="ti-location-pin"></i></span>
                  <div>
                    <h5 class="asvc-section-title" id="asvc-visit-information">Visit Information</h5>
                    <small class="asvc-section-description">Identify the store and record the actual visit schedule.</small>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 mb-3">
                    <label for="area_distributor" class="form-label">Area Distributor <span class="text-danger">*</span></label>
                    <select class="form-control asvc-area-distributor-select {{ $errors->has('area_distributor') ? 'is-invalid' : '' }}" name="area_distributor" id="area_distributor" data-placeholder="Search or select an Area Distributor" required>
                      <option value=""></option>
                      @foreach ($areaDistributors as $areaDistributor)
                        <option value="{{ $areaDistributor }}" {{ old('area_distributor') === $areaDistributor ? 'selected' : '' }}>{{ $areaDistributor }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-12 col-md-6 mb-3">
                    <label for="area_distributor_email" class="form-label">AD Email Address <span class="text-muted"><small>(provide at least one email)</small></span></label>
                    <input type="email" class="form-control {{ $errors->has('area_distributor_email') ? 'is-invalid' : '' }}" name="area_distributor_email" id="area_distributor_email" value="{{ old('area_distributor_email') }}" placeholder="example@gmail.com" autocomplete="email">
                  </div>

                  <div class="col-12 col-md-6 mb-3">
                    <label for="representative_email" class="form-label">AD Representative Email <span class="text-muted"><small>(optional)</small></span></label>
                    <input type="email" class="form-control {{ $errors->has('representative_email') ? 'is-invalid' : '' }}" name="representative_email" id="representative_email" value="{{ old('representative_email') }}" placeholder="example@gmail.com" autocomplete="email">
                  </div>

                  <div class="col-12 col-md-4 mb-3">
                    <label for="visit_date" class="form-label">Visit Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control {{ $errors->has('visit_date') ? 'is-invalid' : '' }}" name="visit_date" id="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required>
                  </div>

                  <div class="col-6 col-md-4 mb-3">
                    <label for="time_in" class="form-label">Time In <span class="text-danger">*</span></label>
                    <input
                        type="time"
                        class="form-control {{ $errors->has('time_in') ? 'is-invalid' : '' }}"
                        name="time_in"
                        id="time_in"
                        required
                    >
                  </div>
                </div>
              </section>

              <section class="asvc-form-section" aria-labelledby="asvc-point-persons">
                <div class="asvc-section-heading">
                  <span class="asvc-section-icon"><i class="ti-user"></i></span>
                  <div>
                    <h5 class="asvc-section-title" id="asvc-point-persons">Point Persons During Visit</h5>
                    <small class="asvc-section-description">Record the distributor or the current present representative during the visit.</small>
                  </div>
                </div>

                <div class="asvc-table-wrap">
                  <table class="table asvc-table">
                    <thead>
                      <tr>
                        <th>Role</th>
                        <th>Full Name</th>
                        <th class="text-center">Present</th>
                        <th>Comment</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($pointPersonRoles as $index => $role)
                        <tr>
                          <td class="asvc-row-label" data-label="Role">
                            {{ $role }}
                            <input type="hidden" name="point_persons[{{ $index }}][role]" value="{{ $role }}">
                          </td>
                          <td data-label="Full Name">
                            <input type="text" class="form-control" name="point_persons[{{ $index }}][full_name]" value="{{ old("point_persons.$index.full_name") }}" placeholder="Last name, First name">
                          </td>
                          <td class="asvc-check-cell" data-label="Present">
                            <input type="hidden" name="point_persons[{{ $index }}][is_present]" value="0">
                            <label class="asvc-check">
                              <input type="checkbox" name="point_persons[{{ $index }}][is_present]" value="1" {{ old("point_persons.$index.is_present") ? 'checked' : '' }}>
                              <span>Yes</span>
                            </label>
                          </td>
                          <td data-label="Comment">
                            <input type="text" class="form-control" name="point_persons[{{ $index }}][comment]" value="{{ old("point_persons.$index.comment") }}" placeholder="(optional)">
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </section>

              <section class="asvc-form-section" aria-labelledby="asvc-product-availability">
                <div class="asvc-section-heading">
                  <span class="asvc-section-icon"><i class="ti-package"></i></span>
                  <div>
                    <h5 class="asvc-section-title" id="asvc-product-availability">Product Availability</h5>
                    <small class="asvc-section-description">Inspect each applicable item, confirm availability, and add remarks when needed.</small>
                  </div>
                </div>

                <div class="asvc-table-wrap">
                  <table class="table asvc-table">
                    <thead>
                      <tr>
                        <th>Product</th>
                        <th class="text-center">Inspected</th>
                        <th>Availability</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($products as $index => $product)
                        <tr>
                          <td class="asvc-row-label" data-label="Product">
                            {{ $index + 1 }}. {{ $product }}
                            <input type="hidden" name="product_availabilities[{{ $index }}][product_name]" value="{{ $product }}">
                            <input type="hidden" name="product_availabilities[{{ $index }}][sort_order]" value="{{ $index + 1 }}">
                          </td>
                          <td class="asvc-check-cell" data-label="Inspected">
                            <input type="hidden" name="product_availabilities[{{ $index }}][is_inspected]" value="0">
                            <label class="asvc-check">
                              <input type="checkbox" name="product_availabilities[{{ $index }}][is_inspected]" value="1" {{ old("product_availabilities.$index.is_inspected") ? 'checked' : '' }}>
                              <span>Yes</span>
                            </label>
                          </td>
                          <td data-label="Availability">
                            <select class="form-control" name="product_availabilities[{{ $index }}][is_available]">
                              <option value="">Select</option>
                              <option value="Yes" {{ old("product_availabilities.$index.is_available") === 'Yes' ? 'selected' : '' }}>Yes</option>
                              <option value="No" {{ old("product_availabilities.$index.is_available") === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                          </td>
                          <td data-label="Remarks">
                            <input type="text" class="form-control" name="product_availabilities[{{ $index }}][remarks]" value="{{ old("product_availabilities.$index.remarks") }}" placeholder="(optional)">
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </section>

              <section class="asvc-form-section" aria-labelledby="asvc-acquired-accounts">
                <div class="asvc-section-heading">
                  <span class="asvc-section-icon"><i class="ti-briefcase"></i></span>
                  <div>
                    <h5 class="asvc-section-title" id="asvc-acquired-accounts">Acquired Accounts</h5>
                    <small class="asvc-section-description">Enter completed account rows only. Leave unused rows blank.</small>
                  </div>
                </div>

                <div class="asvc-table-wrap">
                  <table class="table asvc-table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Customer Name</th>
                        <th>Customer Type</th>
                        <th>Amount Purchased</th>
                      </tr>
                    </thead>
                    <tbody>
                      @for ($index = 0; $index < 10; $index++)
                        <tr>
                          <td data-label="Account"><span class="asvc-account-number">{{ $index + 1 }}</span></td>
                          <td data-label="Customer Name">
                            <input type="text" class="form-control" name="acquired_accounts[{{ $index }}][customer_name]" value="{{ old("acquired_accounts.$index.customer_name") }}" placeholder="Customer name">
                          </td>
                          <td data-label="Customer Type">
                            <select class="form-control" name="acquired_accounts[{{ $index }}][customer_type]">
                              <option value="">Select type</option>
                              @foreach (['End User', 'Dealer', 'Mega Dealer'] as $customerType)
                                <option value="{{ $customerType }}" {{ old("acquired_accounts.$index.customer_type") === $customerType ? 'selected' : '' }}>{{ $customerType }}</option>
                              @endforeach
                            </select>
                          </td>
                          <td data-label="Amount Purchased">
                            <input type="number" class="form-control" name="acquired_accounts[{{ $index }}][amount_purchased]" value="{{ old("acquired_accounts.$index.amount_purchased") }}" min="0" step="0.01" placeholder="0.00">
                          </td>
                        </tr>
                      @endfor
                    </tbody>
                  </table>
                </div>
              </section>

              <section class="asvc-form-section" aria-labelledby="asvc-other-matters">
                <div class="asvc-section-heading">
                  <span class="asvc-section-icon"><i class="ti-check-box"></i></span>
                  <div>
                    <h5 class="asvc-section-title" id="asvc-other-matters">Other Matters</h5>
                    <small class="asvc-section-description">Review the remaining store requirements and note any follow-up needed.</small>
                  </div>
                </div>

                <div class="asvc-table-wrap">
                  <table class="table asvc-table">
                    <thead>
                      <tr>
                        <th>Checklist Item</th>
                        <th class="text-center">Inspected</th>
                        <th>Status</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($otherMatters as $index => $matter)
                        <tr>
                          <td class="asvc-row-label" data-label="Checklist Item">
                            {{ $matter }}
                            <input type="hidden" name="other_matters[{{ $index }}][matter]" value="{{ $matter }}">
                          </td>
                          <td class="asvc-check-cell" data-label="Inspected">
                            <input type="hidden" name="other_matters[{{ $index }}][is_inspected]" value="0">
                            <label class="asvc-check">
                              <input type="checkbox" name="other_matters[{{ $index }}][is_inspected]" value="1" {{ old("other_matters.$index.is_inspected") ? 'checked' : '' }}>
                              <span>Yes</span>
                            </label>
                          </td>
                          <td data-label="Status">
                            <select class="form-control" name="other_matters[{{ $index }}][status]">
                              <option value="">Select</option>
                              <option value="Yes" {{ old("other_matters.$index.status") === 'Yes' ? 'selected' : '' }}>Yes</option>
                              <option value="No" {{ old("other_matters.$index.status") === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                          </td>
                          <td data-label="Remarks">
                            <input type="text" class="form-control" name="other_matters[{{ $index }}][remarks]" value="{{ old("other_matters.$index.remarks") }}" placeholder="(optional)">
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </section>

              <section class="asvc-form-section" aria-labelledby="asvc-discussion-summary">
                <div class="asvc-section-heading">
                  <span class="asvc-section-icon"><i class="ti-comment-alt"></i></span>
                  <div>
                    <h5 class="asvc-section-title" id="asvc-discussion-summary">Discussion Summary</h5>
                    <small class="asvc-section-description">Document the concerns raised and the recommendations discussed.</small>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 col-lg-6 mb-3">
                    <label for="issues_and_concerns" class="form-label">Issues and Concerns <span class="text-danger">*</span></label>
                    <textarea style="resize: none;" class="form-control {{ $errors->has('issues_and_concerns') ? 'is-invalid' : '' }}" name="issues_and_concerns" id="issues_and_concerns" rows="5" placeholder="Summarize the issues or concerns discussed" required>{{ old('issues_and_concerns') }}</textarea>
                  </div>

                  <div class="col-12 col-lg-6 mb-3">
                    <label for="recommendations" class="form-label">Recommendations <span class="text-danger">*</span></label>
                    <textarea style="resize: none;" class="form-control {{ $errors->has('recommendations') ? 'is-invalid' : '' }}" name="recommendations" id="recommendations" rows="5" placeholder="Summarize the recommendations discussed" required>{{ old('recommendations') }}</textarea>
                  </div>
                </div>
              </section>

              <section class="asvc-form-section" aria-labelledby="asvc-attachments">
                <div class="asvc-section-heading">
                  <span class="asvc-section-icon"><i class="ti-clip"></i></span>
                  <div>
                    <h5 class="asvc-section-title" id="asvc-attachments">Attachments</h5>
                    <small class="asvc-section-description">Upload document or supporting files.</small>
                  </div>
                </div>

                <div class="asvc-file-zone">
                  <label for="attachments" class="form-label">Files <span class="text-danger">*</span></label>
                  <input type="file" class="form-control {{ $errors->has('attachments') || $errors->has('attachments.*') ? 'is-invalid' : '' }}" name="attachments[]" id="attachments" multiple required aria-describedby="attachments-help">
                  <small class="text-muted d-block mt-1" id="attachments-help">e.g., JPG, JPEG, PNG. Maximum size: 30 MB per file.</small>
                  <div id="attachment-preview" class="asvc-file-list d-none" aria-live="polite"></div>
                </div>
              </section>

              <section class="asvc-form-section" aria-labelledby="asvc-submission-details">
                <div class="asvc-section-heading">
                  <span class="asvc-section-icon"><i class="ti-id-badge"></i></span>
                  <div>
                    <h5 class="asvc-section-title" id="asvc-submission-details">Submission Details</h5>
                    <small class="asvc-section-description">Confirm the HIC officer responsible for this report.</small>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 col-md-4 mb-3">
                    <label for="submitted_by_name" class="form-label">HIC Submitted By <span class="text-danger">*</span></label>
                    <input type="text" class="form-control {{ $errors->has('submitted_by_name') ? 'is-invalid' : '' }}" name="submitted_by_name" id="submitted_by_name" value="{{ old('submitted_by_name', $employeeName) }}" placeholder="Full name" required>
                  </div>

                  <div class="col-12 col-md-4 mb-3">
                    <label for="submitted_by_designation" class="form-label">Designation <span class="text-danger">*</span></label>
                    <input type="text" class="form-control {{ $errors->has('submitted_by_designation') ? 'is-invalid' : '' }}" name="submitted_by_designation" id="submitted_by_designation" value="{{ old('submitted_by_designation', optional($employee)->position) }}" placeholder="Designation" required>
                  </div>

                  <div class="col-12 col-md-4 mb-3">
                    <label for="submitted_by_email" class="form-label">Officer Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control {{ $errors->has('submitted_by_email') ? 'is-invalid' : '' }}" name="submitted_by_email" id="submitted_by_email" value="{{ old('submitted_by_email', auth()->user()->email) }}" placeholder="name@example.com" autocomplete="email" required>
                  </div>
                </div>
              </section>

              <div class="asvc-form-actions">
                <a href="{{ route('ad-store-visit-reports.index') }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary" id="asvc-submit-button">
                  Submit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.asvc-create-form');
    var submitButton = document.getElementById('asvc-submit-button');
    var submitting = false;

    if (form && submitButton) {
      form.addEventListener('submit', function (event) {
        if (submitting) {
          event.preventDefault();
          return;
        }

        submitting = true;
        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';
      });

      window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
          submitting = false;
          submitButton.disabled = false;
          submitButton.textContent = 'Submit';
        }
      });
    }

    var attachmentInput = document.getElementById('attachments');
    var attachmentPreview = document.getElementById('attachment-preview');
    var selectedAttachments = [];

    if (window.jQuery && jQuery.fn.select2) {
      jQuery('.asvc-area-distributor-select').select2({
        placeholder: 'Search or select an Area Distributor',
        width: '100%'
      });
    }

    if (!attachmentInput || !attachmentPreview) {
      return;
    }

    function syncAttachmentInput() {
      var transfer = new DataTransfer();

      selectedAttachments.forEach(function (file) {
        transfer.items.add(file);
      });

      attachmentInput.files = transfer.files;
    }

    function renderAttachmentPreview() {
      attachmentPreview.innerHTML = '';

      if (!selectedAttachments.length) {
        attachmentPreview.classList.add('d-none');
        return;
      }

      selectedAttachments.forEach(function (file, index) {
        var item = document.createElement('div');
        var icon = document.createElement('i');
        var name = document.createElement('span');
        var removeButton = document.createElement('button');

        item.className = 'asvc-file-item';
        icon.className = 'ti-file';
        name.className = 'asvc-file-name';
        name.textContent = file.name;
        name.title = file.name;
        removeButton.type = 'button';
        removeButton.className = 'asvc-file-remove';
        removeButton.setAttribute('data-file-index', index);
        removeButton.setAttribute('aria-label', 'Remove ' + file.name);
        removeButton.title = 'Remove ' + file.name;
        removeButton.textContent = '\u00d7';

        item.appendChild(icon);
        item.appendChild(name);
        item.appendChild(removeButton);
        attachmentPreview.appendChild(item);
      });

      attachmentPreview.classList.remove('d-none');
    }

    attachmentInput.addEventListener('change', function () {
      selectedAttachments = Array.prototype.slice.call(
        attachmentInput.files || []
      );

      renderAttachmentPreview();
    });

    attachmentPreview.addEventListener('click', function (event) {
      var removeButton = event.target.closest('.asvc-file-remove');

      if (!removeButton) {
        return;
      }

      selectedAttachments.splice(
        Number(removeButton.getAttribute('data-file-index')),
        1
      );

      syncAttachmentInput();
      renderAttachmentPreview();
    });
  });
</script>
