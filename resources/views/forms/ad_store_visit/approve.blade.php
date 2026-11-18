<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Store Visit Approval - {{ $report->reference_no }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 24px 12px;
            background: #f3f6fa;
            color: #24364b;
            font-family: Arial, Helvetica, sans-serif;
        }

        .page {
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
        }

        .card {
            overflow: hidden;
            border: 1px solid #e0e6ee;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 8px 28px rgba(23, 43, 77, .08);
        }

        .header {
            padding: 24px 28px 18px;
            border-bottom: 1px solid #e8ecf2;
        }

        .company {
            margin: 0;
            color: #111827;
            font-size: 17px;
            font-family: Roboto, Arial, Helvetica, sans-serif;
        }

        .brand-line {
            display: flex;
            height: 6px;
            margin: 7px 0;
        }

        .brand-line span:first-child {
            width: 50%;
            background: #59c2e5;
        }

        .brand-line span:last-child {
            width: 50%;
            background: #fe0001;
        }

        .subtitle {
            margin: 0;
            color: #6b7280;
            font-size: 13px;
        }

        .body {
            padding: 26px 28px;
        }

        h1 {
            margin: 0 0 8px;
            color: #172b4d;
            font-size: 24px;
        }

        .intro {
            margin: 0 0 22px;
            color: #56657a;
            font-size: 14px;
            line-height: 1.6;
        }

        .notice {
            margin-bottom: 22px;
            padding: 15px 16px;
            border: 1px solid;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.55;
        }

        .notice.success {
            border-color: #b7e2c5;
            background: #edf9f1;
            color: #17663a;
        }

        .notice.info {
            border-color: #cbdced;
            background: #f0f7ff;
            color: #31547e;
        }

        .notice.error {
            border-color: #f0bdc2;
            background: #fff1f2;
            color: #9f2936;
        }

        .details {
            overflow: hidden;
            margin-bottom: 24px;
            border: 1px solid #e3e8ef;
            border-radius: 8px;
        }

        .row {
            display: grid;
            grid-template-columns: minmax(135px, 34%) 1fr;
            border-bottom: 1px solid #e8ecf2;
        }

        .row:last-child {
            border-bottom: 0;
        }

        .label,
        .value {
            min-width: 0;
            padding: 12px 14px;
            font-size: 13px;
            line-height: 1.5;
        }

        .label {
            background: #f8fafc;
            color: #64748b;
            font-weight: 700;
        }

        .value {
            color: #26364b;
            overflow-wrap: anywhere;
            white-space: pre-line;
        }

        .section-title {
            margin: 0 0 10px;
            color: #111827;
            font-size: 14px;
            letter-spacing: .4px;
            text-transform: uppercase;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 10px;
        }

        .approval-form {
            margin: 0;
        }

        .approve-button,
        .disapprove-button,
        .cancel-disapproval-button,
        .submit-disapproval-button {
            min-width: 190px;
            padding: 13px 22px;
            cursor: pointer;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 700;
        }

        .approve-button,
        .disapprove-button {
            min-width: 155px;
            padding: 10px 18px;
        }
        .approve-button {
            border: 1px solid #3f7652;
            background: #3f7652;
            color: #fff;
        }

        .approve-button:hover,
        .approve-button:focus {
            background: #345f43;
            border-color: #2f563c;
            outline: 3px solid rgba(63, 118, 82, .22);
        }

        .disapprove-button {
            border: 1px solid #a45158;
            background: #a45158;
            color: #fff;
        }

        .disapprove-button:hover,
        .disapprove-button:focus {
            background: #8f454c;
            border-color: #843b42;
            outline: 3px solid rgba(164, 81, 88, .22);
        }

        .disapproval-panel {
            margin-top: 16px;
            padding: 18px;
            border: 1px solid #fecaca;
            border-radius: 8px;
            background: #fff7f7;
        }

        .disapproval-panel[hidden],
        .reason-error[hidden] {
            display: none;
        }

        .reason-label {
            display: block;
            margin-bottom: 7px;
            color: #7f1d1d;
            font-size: 14px;
            font-weight: 700;
        }

        .reason-textarea {
            display: block;
            width: 100%;
            min-height: 100px;
            padding: 11px 12px;
            resize: vertical;
            border: 1px solid #d7dde5;
            border-radius: 7px;
            background: #fff;
            color: #24364b;
            font: inherit;
            line-height: 1.5;
        }

        .reason-textarea:focus {
            border-color: #dc2626;
            outline: 3px solid rgba(220, 38, 38, .12);
        }

        .reason-textarea[aria-invalid="true"] {
            border-color: #dc2626;
        }

        .reason-help,
        .reason-error {
            margin: 7px 0 0;
            font-size: 12px;
            line-height: 1.5;
        }

        .reason-help {
            color: #64748b;
        }

        .reason-error {
            color: #b42318;
            font-weight: 700;
        }

        .disapproval-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 14px;
        }

        .cancel-disapproval-button {
            min-width: 110px;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #475569;
        }

        .submit-disapproval-button {
            border: 1px solid #a45158;
            background: #a45158;
            color: #fff;
        }

        .cancel-disapproval-button:hover,
        .cancel-disapproval-button:focus {
            background: #f8fafc;
        }

        .submit-disapproval-button:hover,
        .submit-disapproval-button:focus {
            background: #8f454c;
            border-color: #843b42;
        }

        .footer {
            padding: 16px 28px 22px;
            border-top: 1px solid #e8ecf2;
            color: #8893a3;
            font-size: 12px;
            text-align: center;
        }

        @media (max-width: 560px) {
            body {
                padding: 8px;
            }

            .header,
            .body {
                padding-right: 18px;
                padding-left: 18px;
            }

            h1 {
                font-size: 20px;
            }

            .row {
                display: block;
            }

            .label {
                padding-bottom: 4px;
            }

            .value {
                padding-top: 4px;
            }

            .actions,
            .disapproval-actions {
                flex-direction: column;
            }

            .approval-form,
            .approve-button,
            .disapprove-button,
            .cancel-disapproval-button,
            .submit-disapproval-button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main class="page">
        <section class="card">
            <header class="header">
                <p class="company">PASCAL RESOURCES ENERGY INC.</p>
                <div class="brand-line" aria-hidden="true"><span></span><span></span></div>
                <p class="subtitle">Store Visit Report</p>
            </header>

            <div class="body">
                <p class="intro">Review the visit information below before confirming this report.</p>

                @if ($approvalCompleted)
                    <div class="notice success" role="status">
                        The store visit report has been approved, Thank you.
                    </div>
                @elseif ($disapprovalCompleted)
                    <div class="notice error" role="status">
                        The store visit report has been disapproved.
                        <div style="margin-top:8px; white-space:pre-line;"><strong>Reason:</strong> {{ $report->disapproval_reason }}</div>
                    </div>
                @elseif ($report->isFromImport())
                    <div class="notice info" role="status">
                        This report was imported and does not require approval.
                    </div>
                @elseif ($alreadyApproved)
                    <div class="notice info" role="status">
                        This store visit report has already been approved. No additional email was sent.
                    </div>
                @elseif ($alreadyDisapproved)
                    <div class="notice error" role="status">
                        This store visit report has been disapproved by Area Distributor.
                    </div>
                @elseif ($approvalFailed)
                    <div class="notice error" role="alert">
                        The decision notification could not be sent. Nothing was recorded, so you may try again.
                    </div>
                @endif
                <h2 class="section-title">Visit Details</h2>
                <div class="details">
                    <div class="row">
                        <div class="label">Reference No.</div>
                        <div class="value"><strong>{{ $report->reference_no }}</strong></div>
                    </div>
                    <div class="row">
                        <div class="label">Area Distributor</div>
                        <div class="value">{{ $report->area_distributor ?: 'N/A' }}</div>
                    </div>
                    <div class="row">
                        <div class="label">AD Email Address</div>
                        <div class="value">{{ $report->area_distributor_email ?: 'N/A' }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Representative Email</div>
                        <div class="value">{{ $report->representative_email ?: 'N/A' }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Visit Date</div>
                        <div class="value">{{ optional($report->visit_date)->format('F j, Y') ?: 'N/A' }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Issues and Concerns</div>
                        <div class="value">{{ $report->issues_and_concerns ?: 'N/A' }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Recommendations</div>
                        <div class="value">{{ $report->recommendations ?: 'N/A' }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Inspected by</div>
                        <div class="value">{{ $report->submitted_by_name ?: 'N/A' }}</div>
                    </div>
                </div>

                <h2 class="section-title">Point Persons During Visit</h2>
                <div class="details">
                    @forelse ($report->pointPersons as $person)
                        <div class="row">
                            <div class="label">{{ $person->role }}</div>
                            <div class="value"><strong>{{ $person->full_name ?: 'N/A' }}</strong><br>Present: {{ $person->is_present ? 'Yes' : 'No' }}<br>Comment: {{ $person->comment ?: 'N/A' }}</div>
                        </div>
                    @empty
                        <div class="row">
                            <div class="label">Point Persons</div>
                            <div class="value">No point persons recorded.</div>
                        </div>
                    @endforelse
                </div>

                <h2 class="section-title">Product Availability</h2>
                <div class="details">
                    @forelse ($report->productAvailabilities as $product)
                        <div class="row">
                            <div class="label">{{ $product->product_name }}</div>
                            <div class="value">Inspected: {{ $product->is_inspected ? 'Yes' : 'No' }}<br>Available: {{ $product->is_available === null ? 'N/A' : ($product->is_available ? 'Yes' : 'No') }}<br>Remarks: {{ $product->remarks ?: 'N/A' }}</div>
                        </div>
                    @empty
                        <div class="row">
                            <div class="label">Products</div>
                            <div class="value">No product availability records found.</div>
                        </div>
                    @endforelse
                </div>

                <h2 class="section-title">Acquired Accounts</h2>
                <div class="details">
                    @forelse ($report->acquiredAccounts as $account)
                        <div class="row">
                            <div class="label">{{ $account->customer_name }}</div>
                            <div class="value">Customer Type: {{ $account->customer_type ?: 'N/A' }}<br>Amount Purchased: &#8369;{{ number_format((float) $account->amount_purchased, 2) }}</div>
                        </div>
                    @empty
                        <div class="row">
                            <div class="label">Accounts</div>
                            <div class="value">No acquired accounts were recorded.</div>
                        </div>
                    @endforelse
                </div>

                <h2 class="section-title">Other Matters</h2>
                <div class="details">
                    @forelse ($report->otherMatters as $matter)
                        <div class="row">
                            <div class="label">{{ $matter->matter }}</div>
                            <div class="value">Inspected: {{ $matter->is_inspected ? 'Yes' : 'No' }}<br>Response: {{ $matter->status === null ? 'N/A' : ($matter->status ? 'Yes' : 'No') }}<br>Remarks: {{ $matter->remarks ?: 'N/A' }}</div>
                        </div>
                    @empty
                        <div class="row">
                            <div class="label">Other Matters</div>
                            <div class="value">No other matters recorded.</div>
                        </div>
                    @endforelse
                </div>
                @if ($report->isApprovalPending())
                    <div class="actions">
                        <button
                            type="button"
                            class="disapprove-button"
                            id="show-disapproval"
                            aria-expanded="{{ $errors->has('disapproval_reason') ? 'true' : 'false' }}"
                            aria-controls="disapproval-panel"
                        >Disapprove</button>

                        <form method="post" action="{{ request()->fullUrl() }}" class="approval-form">
                            @csrf
                            <input type="hidden" name="decision" value="approve">
                            <button type="submit" class="approve-button">Approve Report</button>
                        </form>
                    </div>

                    <form
                        method="post"
                        action="{{ request()->fullUrl() }}"
                        class="disapproval-panel"
                        id="disapproval-panel"
                        @if (!$errors->has('disapproval_reason')) hidden @endif
                    >
                        @csrf
                        <input type="hidden" name="decision" value="disapprove">

                        <label class="reason-label" for="disapproval-reason">Reason for disapproval</label>
                        <textarea
                            style="resize: none;"
                            class="reason-textarea"
                            id="disapproval-reason"
                            name="disapproval_reason"
                            maxlength="1000"
                            required
                            placeholder="Explain clearly why this report is being disapproved."
                            aria-describedby="disapproval-reason-help disapproval-reason-error"
                            @if ($errors->has('disapproval_reason')) aria-invalid="true" @endif
                        >{{ old('disapproval_reason') }}</textarea>
                        <p class="reason-help" id="disapproval-reason-help">A reason is required before the report can be disapproved.</p>

                        @if ($errors->has('disapproval_reason'))
                            <p class="reason-error" id="disapproval-reason-error" role="alert">{{ $errors->first('disapproval_reason') }}</p>
                        @endif

                        <div class="disapproval-actions">
                            <button type="button" class="cancel-disapproval-button" id="cancel-disapproval">Cancel</button>
                            <button type="submit" class="submit-disapproval-button">Submit Disapproval</button>
                        </div>
                    </form>
                @endif
            </div>

            <footer class="footer">
                This is an automated notification. Please do not reply to this email.
            </footer>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var showButton = document.getElementById('show-disapproval');
            var panel = document.getElementById('disapproval-panel');
            var reason = document.getElementById('disapproval-reason');
            var reasonError = document.getElementById('disapproval-reason-error');
            var cancelButton = document.getElementById('cancel-disapproval');

            if (!showButton || !panel) {
                return;
            }

            if (!panel.hidden) {
                showButton.setAttribute('aria-expanded', 'true');
            }

            showButton.addEventListener('click', function () {
                panel.hidden = false;
                showButton.setAttribute('aria-expanded', 'true');
                reason.focus();
            });

            cancelButton.addEventListener('click', function () {
                panel.reset();
                panel.hidden = true;
                showButton.setAttribute('aria-expanded', 'false');
                reason.removeAttribute('aria-invalid');

                if (reasonError) {
                    reasonError.hidden = true;
                }

                showButton.focus();
            });

            reason.addEventListener('input', function () {
                if (reason.value.trim()) {
                    reason.removeAttribute('aria-invalid');

                    if (reasonError) {
                        reasonError.hidden = true;
                    }
                }
            });
        });
    </script>
</body>

</html>
