@extends('layouts.header')

@section('content')
@php
    $fullName = trim($compenben->first . ' ' . $compenben->last);
@endphp

<style>
    .compenben-show-page {
        --cb-border: #e2e8f0;
        --cb-heading: #0f172a;
        --cb-text: #334155;
        --cb-muted: #64748b;
        --cb-surface: #f8fafc;
        --cb-accent: #2563eb;
        --cb-accent-soft: #eff6ff;
    }

    .compenben-show-page .compenben-show-card {
        overflow: hidden;
        border: 1px solid var(--cb-border);
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
    }

    .compenben-show-page .compenben-show-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.5rem;
        border-bottom: 1px solid var(--cb-border);
        background: var(--cb-surface);
    }

    .compenben-show-page .compenben-header-copy {
        min-width: 0;
    }

    .compenben-show-page .compenben-eyebrow {
        display: block;
        margin-bottom: .3rem;
        color: var(--cb-muted);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .compenben-show-page .compenben-show-title {
        margin: 0;
        color: var(--cb-heading);
        font-size: 1.3rem;
        font-weight: 700;
        line-height: 1.35;
    }

    .compenben-show-page .compenben-show-header .btn {
        border-color: #cbd5e1;
        border-radius: .55rem;
        background: #fff;
        font-weight: 600;
    }

    .compenben-show-page .compenben-card-body {
        padding: 1.5rem;
    }

    .compenben-show-page .compenben-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        margin-bottom: 1.25rem;
        border: 1px solid var(--cb-border);
        border-radius: .8rem;
        background: #fff;
    }

    .compenben-show-page .compenben-summary-item {
        min-width: 0;
        padding: 1rem 1.1rem;
    }

    .compenben-show-page .compenben-summary-item + .compenben-summary-item {
        border-left: 1px solid var(--cb-border);
    }

    .compenben-show-page .compenben-detail-label {
        display: block;
        margin-bottom: .35rem;
        color: var(--cb-muted);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .045em;
        text-transform: uppercase;
    }

    .compenben-show-page .compenben-detail-value {
        margin: 0;
        color: var(--cb-text);
        font-size: .9rem;
        font-weight: 500;
        line-height: 1.55;
        overflow-wrap: anywhere;
    }

    .compenben-show-page .compenben-reference {
        display: inline-flex;
        align-items: center;
        padding: .3rem .65rem;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
        color: #1d4ed8;
        background: var(--cb-accent-soft);
        font-size: .82rem;
        font-weight: 700;
        letter-spacing: .02em;
    }

    .compenben-show-page .compenben-issue {
        color: var(--cb-heading);
        font-weight: 700;
    }

    .compenben-show-page .compenben-section {
        margin-top: 1.25rem;
        border: 1px solid var(--cb-border);
        border-radius: .8rem;
        background: #fff;
    }

    .compenben-show-page .compenben-section-header {
        display: flex;
        align-items: center;
        gap: .7rem;
        padding: 1rem 1.1rem;
        border-bottom: 1px solid var(--cb-border);
        background: var(--cb-surface);
    }

    .compenben-show-page .compenben-section-icon {
        display: inline-flex;
        width: 2rem;
        height: 2rem;
        flex: 0 0 2rem;
        align-items: center;
        justify-content: center;
        border-radius: .5rem;
        color: var(--cb-accent);
        background: var(--cb-accent-soft);
        font-size: .9rem;
    }

    .compenben-show-page .compenben-section-title {
        margin: 0;
        color: var(--cb-heading);
        font-size: .95rem;
        font-weight: 700;
    }

    .compenben-show-page .compenben-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        padding: .25rem 1.1rem;
    }

    .compenben-show-page .compenben-info-item {
        min-width: 0;
        padding: 1rem 0;
    }

    .compenben-show-page .compenben-info-item:nth-child(odd) {
        padding-right: 1.25rem;
    }

    .compenben-show-page .compenben-info-item:nth-child(even) {
        padding-left: 1.25rem;
        border-left: 1px solid var(--cb-border);
    }

    .compenben-show-page .compenben-info-item:nth-child(n+3) {
        border-top: 1px solid var(--cb-border);
    }

    .compenben-show-page .compenben-email-link {
        color: #1d4ed8;
        font-weight: 600;
        text-decoration: none;
    }

    .compenben-show-page .compenben-email-link:hover {
        text-decoration: underline;
    }

    .compenben-show-page .compenben-details-content {
        padding: 1.1rem;
    }

    .compenben-show-page .compenben-details-box {
        min-height: 7rem;
        padding: 1.1rem 1.2rem;
        border-left: 3px solid #60a5fa;
        border-radius: .55rem;
        background: var(--cb-surface);
    }

    .compenben-show-page .compenben-details-box .compenben-detail-value {
        color: #1e293b;
        font-size: .95rem;
        font-weight: 400;
        line-height: 1.75;
    }

    @media (max-width: 767.98px) {
        .compenben-show-page .compenben-summary {
            grid-template-columns: 1fr;
        }

        .compenben-show-page .compenben-summary-item + .compenben-summary-item {
            border-top: 1px solid var(--cb-border);
            border-left: 0;
        }

        .compenben-show-page .compenben-info-grid {
            grid-template-columns: 1fr;
        }

        .compenben-show-page .compenben-info-item,
        .compenben-show-page .compenben-info-item:nth-child(odd),
        .compenben-show-page .compenben-info-item:nth-child(even) {
            padding-right: 0;
            padding-left: 0;
            border-left: 0;
        }

        .compenben-show-page .compenben-info-item + .compenben-info-item {
            border-top: 1px solid var(--cb-border);
        }
    }

    @media (max-width: 575.98px) {
        .compenben-show-page .compenben-show-header,
        .compenben-show-page .compenben-card-body {
            padding: 1rem;
        }

        .compenben-show-page .compenben-show-header .btn {
            width: 100%;
        }

        .compenben-show-page .compenben-show-title {
            font-size: 1.1rem;
        }
    }
</style>

<div class="main-panel compenben-show-page">
    <div class="content-wrapper">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10 grid-margin stretch-card">
                <article class="card compenben-show-card">
                    <header class="compenben-show-header">
                        <div class="compenben-header-copy">
                            <span class="compenben-eyebrow">Compensation and Benefits Report</span>
                        </div>
                        <a href="{{ route('compenben.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ti-arrow-left mr-1" aria-hidden="true"></i>
                            Back to requests
                        </a>
                    </header>

                    <div class="compenben-card-body">
                        <section class="compenben-summary" aria-label="Request summary">
                            <div class="compenben-summary-item">
                                <span class="compenben-detail-label">Reference number</span>
                                <span class="compenben-reference">{{ $compenben->reference_no ?: '-' }}</span>
                            </div>
                            <div class="compenben-summary-item">
                                <span class="compenben-detail-label">Date submitted</span>
                                <p class="compenben-detail-value">
                                    {{ $compenben->created_at ? $compenben->created_at->format('F d, Y, h:i A') : '-' }}
                                </p>
                            </div>
                            <div class="compenben-summary-item">
                                <span class="compenben-detail-label">Issue category</span>
                                <p class="compenben-detail-value compenben-issue">{{ $compenben->issue ?: '-' }}</p>
                            </div>
                        </section>

                        <section class="compenben-section" aria-labelledby="employee-details-heading">
                            <div class="compenben-section-header">
                                <span class="compenben-section-icon" aria-hidden="true">
                                    <i class="ti-user"></i>
                                </span>
                                <h2 id="employee-details-heading" class="compenben-section-title">Employee information</h2>
                            </div>

                            <div class="compenben-info-grid">
                                <div class="compenben-info-item">
                                    <span class="compenben-detail-label">Employee code</span>
                                    <p class="compenben-detail-value">{{ $compenben->employee_code ?: '-' }}</p>
                                </div>
                                <div class="compenben-info-item">
                                    <span class="compenben-detail-label">Name</span>
                                    <p class="compenben-detail-value">{{ $fullName ?: '-' }}</p>
                                </div>
                                <div class="compenben-info-item">
                                    <span class="compenben-detail-label">Email address</span>
                                    <p class="compenben-detail-value">
                                        @if ($compenben->email)
                                            <a class="compenben-email-link" href="mailto:{{ $compenben->email }}">
                                                {{ $compenben->email }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="compenben-info-item">
                                    <span class="compenben-detail-label">Cellphone number</span>
                                    <p class="compenben-detail-value">{{ $compenben->phone ?: '-' }}</p>
                                </div>
                                <div class="compenben-info-item">
                                    <span class="compenben-detail-label">Designation / Position</span>
                                    <p class="compenben-detail-value">{{ $compenben->designation ?: '-' }}</p>
                                </div>
                                <div class="compenben-info-item">
                                    <span class="compenben-detail-label">Work location</span>
                                    <p class="compenben-detail-value">{{ $compenben->work_location ?: '-' }}</p>
                                </div>
                            </div>
                        </section>

                        <section class="compenben-section" aria-labelledby="request-details-heading">
                            <div class="compenben-section-header">
                                <span class="compenben-section-icon" aria-hidden="true">
                                    <i class="ti-comment-alt"></i>
                                </span>
                                <h2 id="request-details-heading" class="compenben-section-title">
                                    Details of issue / Purpose of request
                                </h2>
                            </div>
                            <div class="compenben-details-content">
                                <div class="compenben-details-box">
                                    <p class="compenben-detail-value">{!! nl2br(e($compenben->details ?: '-')) !!}</p>
                                </div>
                            </div>
                        </section>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>
@endsection
