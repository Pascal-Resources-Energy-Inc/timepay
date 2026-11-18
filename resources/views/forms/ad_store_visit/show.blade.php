<div class="detail-hero">
    <h2>{{ $report->area_distributor }}</h2>
    <div class="text-muted">
        {{ $report->reference_no }} · Submitted {{ optional($report->submitted_at)->format('F j, Y \a\t g:i A') }}
    </div>

    <div class="detail-meta-grid">
        <div class="detail-meta-item">
            <span class="detail-label">Visit Date</span>
            <span class="detail-value">{{ optional($report->visit_date)->format('F j, Y') }}</span>
        </div>
        <div class="detail-meta-item">
            <span class="detail-label">Visit Time</span>
            <span class="detail-value">
                {{ date('g:i A', strtotime($report->time_in)) }} – {{ $report->time_out ? date('g:i A', strtotime($report->time_out)) : 'N/A' }}
            </span>
        </div>
        <div class="detail-meta-item">
            <span class="detail-label">AD Email Address</span>
            <a class="detail-value" href="mailto:{{ $report->area_distributor_email }}">{{ $report->area_distributor_email }}</a>
        </div>
        <div class="detail-meta-item">
            <span class="detail-label">Representative Email</span>
            @if ($report->representative_email)
                <a class="detail-value" href="mailto:{{ $report->representative_email }}">{{ $report->representative_email }}</a>
            @else
                <span class="detail-value text-muted">Not provided</span>
            @endif
        </div>
    </div>
</div>

<section class="detail-section">
    <div class="detail-section-header rounded-top">
        <i class="ti-user" aria-hidden="true"></i>
        <h3>Point Persons During Visit</h3>
    </div>
    <div class="detail-section-body">
        <div class="detail-table-wrap">
            <table class="table detail-table">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Full Name</th>
                        <th class="text-center">Present</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report->pointPersons as $person)
                        <tr>
                            <td><strong>{{ $person->role }}</strong></td>
                            <td>{{ $person->full_name ?: '—' }}</td>
                            <td class="text-center">
                                <span class="answer-chip {{ $person->is_present ? 'answer-yes' : 'answer-no' }}">
                                    {{ $person->is_present ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>{{ $person->comment ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No point persons recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="detail-section">
    <div class="detail-section-header rounded-top">
        <i class="ti-package" aria-hidden="true"></i>
        <h3>Product Availability</h3>
    </div>
    <div class="detail-section-body">
        <div class="detail-table-wrap">
            <table class="table detail-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Inspected</th>
                        <th class="text-center">Available</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report->productAvailabilities as $product)
                        <tr>
                            <td><strong>{{ $product->product_name }}</strong></td>
                            <td class="text-center">
                                <span class="answer-chip {{ $product->is_inspected ? 'answer-yes' : 'answer-no' }}">
                                    {{ $product->is_inspected ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($product->is_available === null)
                                    <span class="answer-chip answer-empty">N/A</span>
                                @else
                                    <span class="answer-chip {{ $product->is_available ? 'answer-yes' : 'answer-no' }}">
                                        {{ $product->is_available ? 'Yes' : 'No' }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $product->remarks ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No product availability records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="detail-section">
    <div class="detail-section-header rounded-top">
        <i class="ti-shopping-cart" aria-hidden="true"></i>
        <h3>Acquired Accounts</h3>
    </div>
    <div class="detail-section-body">
        @if ($report->acquiredAccounts->isNotEmpty())
            <div class="detail-table-wrap">
                <table class="table detail-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Customer Type</th>
                            <th class="text-right">Amount Purchased</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report->acquiredAccounts as $account)
                            <tr>
                                <td><strong>{{ $account->customer_name }}</strong></td>
                                <td>{{ $account->customer_type }}</td>
                                <td class="text-right">₱{{ number_format((float) $account->amount_purchased, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted mb-0">No acquired accounts were recorded for this visit.</p>
        @endif
    </div>
</section>

<section class="detail-section">
    <div class="detail-section-header rounded-top">
        <i class="ti-check-box" aria-hidden="true"></i>
        <h3>Other Matters</h3>
    </div>
    <div class="detail-section-body">
        <div class="detail-table-wrap">
            <table class="table detail-table">
                <thead>
                    <tr>
                        <th>Checklist Item</th>
                        <th class="text-center">Inspected</th>
                        <th class="text-center">Response</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report->otherMatters as $matter)
                        <tr>
                            <td><strong>{{ $matter->matter }}</strong></td>
                            <td class="text-center">
                                <span class="answer-chip {{ $matter->is_inspected ? 'answer-yes' : 'answer-no' }}">
                                    {{ $matter->is_inspected ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($matter->status === null)
                                    <span class="answer-chip answer-empty">N/A</span>
                                @else
                                    <span class="answer-chip {{ $matter->status ? 'answer-yes' : 'answer-no' }}">
                                        {{ $matter->status ? 'Yes' : 'No' }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $matter->remarks ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No other matters recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="detail-section">
    <div class="detail-section-header rounded-top">
        <i class="ti-comment-alt" aria-hidden="true"></i>
        <h3>Visit Notes</h3>
    </div>
    <div class="detail-section-body">
        <div class="narrative-grid">
            <div class="narrative-box">
                <span class="detail-label">Issues and Concerns</span>
                <p>{{ $report->issues_and_concerns }}</p>
            </div>
            <div class="narrative-box">
                <span class="detail-label">Recommendations</span>
                <p>{{ $report->recommendations }}</p>
            </div>
        </div>
    </div>
</section>

<section class="detail-section">
    <div class="detail-section-header rounded-top">
        <i class="ti-id-badge" aria-hidden="true"></i>
        <h3>Submitted By</h3>
    </div>
    <div class="detail-section-body">
        <div class="detail-meta-grid mt-0">
            <div class="detail-meta-item">
                <span class="detail-label">Name</span>
                <span class="detail-value">{{ $report->submitted_by_name }}</span>
            </div>
            <div class="detail-meta-item">
                <span class="detail-label">Designation</span>
                <span class="detail-value">{{ $report->submitted_by_designation }}</span>
            </div>
            <div class="detail-meta-item">
                <span class="detail-label">Email Address</span>
                <a class="detail-value" href="mailto:{{ $report->submitted_by_email }}">{{ $report->submitted_by_email }}</a>
            </div>
            <div class="detail-meta-item">
                <span class="detail-label">Date Submitted</span>
                <span class="detail-value">{{ optional($report->submitted_at)->format('F j, Y g:i A') }}</span>
            </div>
        </div>
    </div>
</section>

<section class="detail-section">
    <div class="detail-section-header rounded-top">
        <i class="ti-clip" aria-hidden="true"></i>
        <h3>Attachments</h3>
    </div>
    <div class="detail-section-body">
        <div class="attachment-list">
            @forelse ($report->attachments as $attachment)
                @php
                    $attachmentName = $attachment->original_name ?: basename($attachment->file_path);
                    $extension = strtoupper(pathinfo($attachmentName, PATHINFO_EXTENSION));
                    $bytes = (int) $attachment->file_size;
                    $fileSize = $bytes >= 1048576
                        ? number_format($bytes / 1048576, 1) . ' MB'
                        : ($bytes >= 1024
                            ? number_format($bytes / 1024, 1) . ' KB'
                            : $bytes . ' bytes');
                    $isImage = strpos((string) $attachment->mime_type, 'image/') === 0;
                @endphp
                <div class="attachment-item">
                    <div class="attachment-info">
                        <span class="attachment-icon">
                            <i class="{{ $isImage ? 'ti-image' : 'ti-file' }}" aria-hidden="true"></i>
                        </span>
                        <span>
                            <span class="attachment-name">{{ $attachmentName }}</span>
                            <span class="attachment-meta">{{ $extension ?: 'FILE' }} · {{ $fileSize }}</span>
                        </span>
                    </div>
                    <a href="{{ asset(ltrim($attachment->file_path, '/')) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm flex-shrink-0">
                        <i class="ti-new-window mr-1" aria-hidden="true"></i> Open
                    </a>
                </div>
            @empty
                <p class="text-muted mb-0">No attachments available.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="detail-section mb-0">
    <div class="detail-section-header rounded-top justify-content-between">
        <div class="d-flex align-items-center">
            <i class="ti-info-alt mr-2" aria-hidden="true"></i>
            <h3>Approval Status</h3>
        </div>

        @if ($report->isDisapproved())
            <span class="answer-chip answer-no">Disapproved</span>
        @elseif ($report->isApproved())
            <span class="answer-chip answer-yes">Approved</span>
          @elseif ($report->isFromImport())
              <span class="answer-chip answer-empty">From import</span>
        @else
            <span class="answer-chip answer-empty">Pending Approval</span>
        @endif
    </div>

    @if ($report->isDisapproved() && $report->disapproval_reason)
        <div class="detail-section-body">
            <div class="narrative-box">
                <span class="detail-label">Reason for Disapproval</span>
                <p>{{ $report->disapproval_reason }}</p>
            </div>
        </div>
    @endif
</section>
