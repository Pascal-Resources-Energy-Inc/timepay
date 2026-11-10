<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layout Design Deadline Reminder</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f3f4f6">
        <tr>
            <td align="center" style="padding:16px 8px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:680px; background:#ffffff; border:1px solid #e6e6e6; border-radius:8px;">
                    <tr>
                        <td style="padding:24px 24px 5px; font-family:Arial, sans-serif; font-size:17px; color:#111111;">
                            PASCAL RESOURCES ENERGY INC.
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 24px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td bgcolor="#59c2e5" width="50%" height="6" style="height:6px; font-size:0;">&nbsp;</td>
                                    <td bgcolor="#fe0001" width="50%" height="6" style="height:6px; font-size:0;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:7px 24px 12px; font-family:Arial, sans-serif; font-size:13px; color:#666666; border-bottom:1px solid #efefef;">
                            Layout Design Request
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 24px 8px; font-family:Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                            Good day,<br><br>
                            This is a reminder that the following request(s) listed below are due today. Please review the deadlines and complete the task.
                        </td>
                    </tr>

                    @php
                        $deadlineRows = [];

                        foreach ($dueToday as $deadlineRequest) {
                            $deadlineRows[] = [
                                'request' => $deadlineRequest,
                                'label' => 'Due today',
                                'color' => '#b91c1c',
                            ];
                        }

                        foreach ($dueSoon as $deadlineRequest) {
                            $deadlineRows[] = [
                                'request' => $deadlineRequest,
                                'label' => 'Due next working day',
                                'color' => '#92400e',
                            ];
                        }
                    @endphp

                    <tr>
                        <td style="padding:16px 24px 10px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                                <tr>
                                    <td style="padding:9px 10px; border:1px solid #e5e7eb; background:#f8fafc; font-family:Arial, sans-serif; font-size:12px; font-weight:700; color:#475569;">Reference</td>
                                    <td style="padding:9px 10px; border:1px solid #e5e7eb; background:#f8fafc; font-family:Arial, sans-serif; font-size:12px; font-weight:700; color:#475569;">Requestor</td>
                                    <td style="padding:9px 10px; border:1px solid #e5e7eb; background:#f8fafc; font-family:Arial, sans-serif; font-size:12px; font-weight:700; color:#475569;">Priority</td>
                                    <td style="padding:9px 10px; border:1px solid #e5e7eb; background:#f8fafc; font-family:Arial, sans-serif; font-size:12px; font-weight:700; color:#475569;">Deadline</td>
                                </tr>
                                @foreach ($deadlineRows as $deadlineRow)
                                    @php
                                        $deadlineRequest = $deadlineRow['request'];
                                        $requestorName = trim(
                                            ($deadlineRequest->requestor_first_name ?? '') . ' ' .
                                            ($deadlineRequest->requestor_last_name ?? '')
                                        ) ?: ($deadlineRequest->full_name ?? '-');
                                    @endphp
                                    <tr>
                                        <td style="padding:9px 10px; border:1px solid #e5e7eb; font-family:Arial, sans-serif; font-size:12px; color:#0369a1; font-weight:700;">{{ $deadlineRequest->reference_no }}</td>
                                        <td style="padding:9px 10px; border:1px solid #e5e7eb; font-family:Arial, sans-serif; font-size:12px; color:#1f2937;">{{ $requestorName }}</td>
                                        <td style="padding:9px 10px; border:1px solid #e5e7eb; font-family:Arial, sans-serif; font-size:12px; color:#1f2937;">{{ $deadlineRequest->priority_level ?: '-' }}</td>
                                        <td style="padding:9px 10px; border:1px solid #e5e7eb; font-family:Arial, sans-serif; font-size:12px; color:{{ $deadlineRow['color'] }}; font-weight:700;">
                                            {{ $deadlineRow['label'] }}<br>
                                            {{ optional($deadlineRequest->due_date)->format('F j, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:20px 24px;">
                            <a href="{{ $actionUrl }}" style="display:inline-block; font-family:Arial, sans-serif; font-size:13px;">
                                View this request
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 24px 24px; border-top:1px solid #efefef; font-family:Arial, sans-serif; font-size:12px; line-height:1.6; color:#888888; text-align:center;">
                            This is an automated notification from the HERA system. Please do not reply to this email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
