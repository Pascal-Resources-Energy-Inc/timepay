<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items Due for Return - {{ $accountability->accountability_ref }}</title>

    <style>
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f3f4f6 !important;
        }

        table {
            border-collapse: collapse;
        }

        .container {
            width: 100% !important;
            max-width: 680px !important;
            -webkit-text-size-adjust: 100%;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #e6e6e6;
            border-radius: 8px;
        }

        @media only screen and (max-width: 480px) {
            .outer-pad {
                padding: 8px 4px !important;
            }

            .card {
                border-right: 0 !important;
                border-left: 0 !important;
                border-radius: 0 !important;
            }

            .brand-td {
                padding: 18px 14px 5px !important;
                font-size: 16px !important;
            }

            .pad24,
            .content-td,
            .section-wrap,
            .action-wrap,
            .foot-td {
                padding-right: 14px !important;
                padding-left: 14px !important;
            }

            td.label,
            td.value,
            td.item-cell {
                padding: 8px 10px !important;
                font-size: 12px !important;
            }

            td.label {
                width: 36% !important;
                white-space: normal !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f3f4f6">
        <tr>
            <td align="center" class="outer-pad" style="padding:16px 8px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="container" style="max-width:680px; width:100%;">
                    <tr>
                        <td class="card" style="background-color:#ffffff; border:1px solid #e6e6e6; border-radius:8px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-bottom:1px solid #efefef;">
                                <tr>
                                    <td class="brand-td" style="padding:24px 24px 5px; font-family:Roboto, Arial, sans-serif; font-size:17px; font-weight:500; color:#111111;">
                                        PASCAL RESOURCES ENERGY INC.
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pad24" style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td bgcolor="#59c2e5" width="50%" height="6" style="height:6px; line-height:6px; font-size:0;">&nbsp;</td>
                                                <td bgcolor="#fe0001" width="50%" height="6" style="height:6px; line-height:6px; font-size:0;">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pad24" style="padding:7px 24px 12px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#666666;">
                                        Borrowing Marketing Collateral
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="content-td" style="padding:20px 24px 0; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.65; color:#374151;">
                                        @if ($recipientType === 'approver')
                                            Good day,<br><br>
                                            This is a reminder that the borrowed item(s) below is due for return today. Please coordinate with the requestor as needed.
                                        @else
                                            Good day,<br><br>
                                            This is a reminder that the borrowed item(s) below is due for return today.
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="section-wrap" style="padding:24px 24px 10px; font-family:Helvetica, Arial, sans-serif; font-size:14px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">
                                        Return Details
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pad24" style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td class="label" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; border:1px solid #e5e7eb; border-right:0; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top;">Reference No.</td>
                                                <td class="value" style="padding:10px 14px; border:1px solid #e5e7eb; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#0369a1; font-weight:700; word-break:break-word;">{{ $accountability->accountability_ref }}</td>
                                            </tr>
                                            <tr>
                                                <td class="label" bgcolor="#f8fafc" style="padding:10px 14px; border:1px solid #e5e7eb; border-top:0; border-right:0; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top;">Requestor</td>
                                                <td class="value" style="padding:10px 14px; border:1px solid #e5e7eb; border-top:0; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; word-break:break-word;">{{ $borrowing->borrower_first_name }} {{ $borrowing->borrower_last_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="label" bgcolor="#fff7ed" style="padding:10px 14px; border:1px solid #fed7aa; border-top:0; border-right:0; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; color:#9a3412; vertical-align:top;">Expected Return</td>
                                                <td class="value" style="padding:10px 14px; border:1px solid #fed7aa; border-top:0; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#9a3412; font-weight:700;">{{ optional($accountability->expected_return_at)->format('F j, Y') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 5px;">
                                <tr>
                                    <td class="section-wrap" style="padding:24px 24px 10px; font-family:Helvetica, Arial, sans-serif; font-size:14px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">
                                        Items to Return
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pad24" style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td class="item-cell" bgcolor="#f8fafc" style="padding:9px 12px; border:1px solid #e5e7eb; font-family:Helvetica, Arial, sans-serif; font-size:12px; font-weight:700; color:#475569;">Item</td>
                                                <td class="item-cell" width="90" bgcolor="#f8fafc" align="center" style="width:90px; padding:9px 12px; border:1px solid #e5e7eb; border-left:0; font-family:Helvetica, Arial, sans-serif; font-size:12px; font-weight:700; color:#475569;">Remaining</td>
                                                <td class="item-cell" bgcolor="#f8fafc" style="padding:9px 12px; border:1px solid #e5e7eb; border-left:0; font-family:Helvetica, Arial, sans-serif; font-size:12px; font-weight:700; color:#475569;">Serial Number(s)</td>
                                            </tr>
                                            @foreach ($accountability->items as $item)
                                                @php
                                                    $remainingQuantity = max(
                                                        (int) $item->released_quantity -
                                                        (int) ($item->returned_quantity ?? 0),
                                                        0
                                                    );
                                                    $availableSerialNumbers = $item->serials
                                                        ->where('status', 'Available')
                                                        ->pluck('serial_number')
                                                        ->filter()
                                                        ->values();
                                                @endphp
                                                @if ($remainingQuantity > 0)
                                                    <tr>
                                                        <td class="item-cell" style="padding:10px 12px; border:1px solid #e5e7eb; border-top:0; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; word-break:break-word;">{{ $item->item_name }}</td>
                                                        <td class="item-cell" align="center" style="padding:10px 12px; border:1px solid #e5e7eb; border-top:0; border-left:0; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; color:#1f2937;">{{ $remainingQuantity }}</td>
                                                        <td class="item-cell" style="padding:10px 12px; border:1px solid #e5e7eb; border-top:0; border-left:0; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.55; color:#1f2937; word-break:break-word;">
                                                            {{ $availableSerialNumbers->isNotEmpty() ? $availableSerialNumbers->implode(', ') : ($item->serial_number ?: 'N/A') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #efefef;">
                                <tr>
                                    <td class="foot-td" style="padding:16px 24px 24px; font-family:Helvetica, Arial, sans-serif; font-size:12px; line-height:1.6; color:#888888; text-align:center;">
                                        This is an automated email notification. Please do not reply to this email.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
