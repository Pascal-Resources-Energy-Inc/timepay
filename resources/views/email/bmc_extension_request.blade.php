<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $heading ?? 'Extension Request' }}</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue';
            font-size: 1rem;
            background-color: #F3F5F7;
        }
    
    </style>
</head>

<body>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="height: 100vh; margin: 0 auto; min-width: 320px; max-width: 1440px;">
<tr><td valign="top">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        <!-- header part -->
        <tr>
            <td style="padding: 10px 16px 0;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td colspan="2" style="padding-bottom: 6px; color:#111111; white-space: nowrap;">
                            PASCAL&nbsp;RESOURCES&nbsp;ENERGY&nbsp;INC.
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#59c2e5" width="50%" height="6" style="height:6px; padding: 0; line-height:6px; font-size:0; mso-line-height-rule:exactly;">&nbsp;</td>
                        <td bgcolor="#fe0001" width="50%" height="6" style="height:6px; padding: 0; line-height:6px; font-size:0; mso-line-height-rule:exactly;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-top: 4px; font-family: Arial, Helvetica; font-size: 14px; color: #666666;">
                            {{ $heading ?? 'Extension Request' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="padding: 20px 16px 10px; font-family: Arial, Helvetica, sans-serif; font-size: 15px; line-height: 22px; color: #222222;">
                @php
                    $release = isset($extension) ? $extension->accountability : null;
                    $requestRecord = $borrowing ?? optional($release)->borrowing;
                    $isPreview = !isset($extension) && !isset($borrowing);
                    $status = $extension->status ?? null;
                    $isApproved = $status === \App\BmcAccountabilityExtension::STATUS_APPROVED;
                    $isDeclined = $status === \App\BmcAccountabilityExtension::STATUS_DECLINED;
                    $isDecision = $isApproved || $isDeclined;
                    $borrowerName = trim(
                        (optional($requestRecord)->borrower_first_name ?? '') . ' ' .
                        (optional($requestRecord)->borrower_last_name ?? '')
                    );
                    $reference = optional($release)->accountability_ref
                        ?? optional($requestRecord)->reference_no;
                    $actionUrl = $isPreview ? '#' : null;

                    if (optional($requestRecord)->id) {
                        $actionUrl = $isDecision
                            ? route('bmc.show', $requestRecord->id)
                            : url('bmc-approval/' . $requestRecord->id);
                    }
                @endphp

                <p style="margin: 0 0 16px;">
                    @if ($isApproved)
                        Your request to extend the return date has been approved. Please return the items by the new due date below.
                    @elseif ($isDeclined)
                        Your request to extend the return date has been declined. Please return the items by the current due date below.
                    @else
                        A borrower has requested to extend their borrowed collateral(s).
                    @endif
                </p>

                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                    <tr>
                        <th scope="row" align="left" valign="top" width="38%" style="padding: 6px 12px 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">Reference No.</th>
                        <td valign="top" style="padding: 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">{{ $isPreview ? 'BMC - 000001-02' : ($reference ?? '—') }}</td>
                    </tr>
                    <tr>
                        <th scope="row" align="left" valign="top" style="padding: 6px 12px 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">Borrower</th>
                        <td valign="top" style="padding: 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">{{ $isPreview ? 'Sample Borrower' : ($borrowerName ?: '—') }}</td>
                    </tr>
                    @if (isset($extension) || $isPreview)
                        <tr>
                            <th scope="row" align="left" valign="top" style="padding: 6px 12px 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">{{ $isApproved ? 'Due date at request' : 'Current due date' }}</th>
                            <td valign="top" style="padding: 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
                                @if ($isPreview)
                                    November 20, 2026
                                @elseif ($isDeclined)
                                    {{ optional(optional($release)->expected_return_at)->format('F j, Y') ?? '—' }}
                                @else
                                    {{ optional($extension->current_return_date)->format('F j, Y') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" align="left" valign="top" style="padding: 6px 12px 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">{{ $isApproved ? 'New due date' : 'Requested due date' }}</th>
                            <td valign="top" style="padding: 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">{{ $isPreview ? 'November 24, 2026' : optional($extension->requested_return_date)->format('F j, Y') }}</td>
                        </tr>
                        <tr>
                            <th scope="row" align="left" valign="top" style="padding: 6px 12px 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">Reason</th>
                            <td valign="top" style="padding: 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">{!! nl2br(e($isPreview ? 'The event was extended by four days.' : $extension->reason)) !!}</td>
                        </tr>
                    @endif
                    @if ($isDecision && filled($extension->remarks))
                        <tr>
                            <th scope="row" align="left" valign="top" style="padding: 6px 12px 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">Reviewer remarks</th>
                            <td valign="top" style="padding: 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">{!! nl2br(e($extension->remarks)) !!}</td>
                        </tr>
                    @endif
                </table>

                @if ($actionUrl)
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td style="padding-top: 18px;">
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td style="font-family: Arial, Helvetica, sans-serif;">
                                            <a href="{{ $actionUrl }}" style="font-size: 13px;">{{ $isDecision ? 'View BMC Request' : 'Review Extension' }}</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                @endif
            </td>
        </tr>

    </table>
</td></tr>
</table>
</body>

</html>
