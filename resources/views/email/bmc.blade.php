<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BMC Request — {{ ucfirst($type ?? 'submitted') }}</title>

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
            .card {
                border-radius: 0 !important;
                border-left: none !important;
                border-right: none !important;
            }

            .outer-pad {
                padding: 8px 4px !important;
            }

            .brand-td {
                padding: 18px 14px 10px 14px !important;
                font-size: 18px !important;
            }

            .pad24 {
                padding: 0 14px !important;
            }

            .gap-top {
                padding: 18px 14px 0 14px !important;
            }

            .sub-td {
                padding: 4px 14px 12px 14px !important;
                font-size: 12px !important;
            }

            .greet-td,
            .greet-msg {
                padding: 16px 14px 0 14px !important;
                font-size: 13px !important;
            }

            .box-pad {
                padding: 10px 12px !important;
            }

            .sec-wrap {
                padding: 18px 14px 10px 14px !important;
            }

            td.lbl,
            td.val {
                font-size: 12px !important;
                padding: 8px 10px !important;
            }

            td.lbl {
                font-size: 11px !important;
                white-space: normal !important;
                line-height: 1.35 !important;
                width: 34% !important;
            }

            td.val {
                word-break: break-word !important;
            }

            .chip-td {
                display: inline-block !important;
                white-space: normal !important;
                padding: 3px 8px !important;
                margin: 0 4px 4px 0 !important;
                font-size: 11px !important;
            }

            .foot-td {
                padding: 12px 14px 20px 14px !important;
                font-size: 11px !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f3f4f6" style="background-color:#f3f4f6;">
        <tr>
            <td align="center" class="outer-pad" style="padding:16px 8px;">
                <!--[if mso]>
                <table role="presentation" width="680" cellpadding="0" cellspacing="0" border="0" align="center"><tr><td>
                <![endif]-->

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="container" style="max-width:680px; width:100%;">
                    <tr>
                        <td class="card" style="background-color:#ffffff; border:1px solid #e6e6e6; border-radius:8px;">

                            <!-- HEADER -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-bottom:1px solid #efefef;">
                                <tr>
                                    <td class="brand-td" style="padding:24px 24px 5px 24px; font-family:Roboto, -apple-system, 'Segoe UI', Arial, sans-serif; font-size:17px; font-weight: 500; color:#111111;">PASCAL RESOURCES ENERGY INC.</td>
                                </tr>
                                <tr>
                                    <td class="pad24" style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td bgcolor="#59c2e5" width="50%" height="6" style="width:50%; height:6px; line-height:6px; font-size:0; mso-line-height-rule:exactly;">&nbsp;</td>
                                                <td bgcolor="#fe0001" width="50%" height="6" style="width:50%; height:6px; line-height:6px; font-size:0; mso-line-height-rule:exactly;">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pad24 sub-td" style="padding:7px 24px 12px 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#666666;">Borrowing Marketing Collateral Request</td>
                                </tr>
                            </table>

                            <!-- GREETING -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                        @if(
                            isset($type) &&
                            ($type == 'approved' || $type == 'declined') &&
                            !blank($borrowing->mbd_remarks)
                        )
                                    <tr>
                                        <td class="greet-msg" style="padding:15px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:16px; line-height:1.6; color:#374151;">
                                            @if(isset($type) && $type == 'approved')
                                                Your request has been approved.
                                            @else
                                                Your request has been denied.
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="greet-td" style="padding:20px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                                            Hi,
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="greet-td" style="padding:6px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                                            A new borrowing marketing collateral request has been submitted and is awaiting for your approval. Please review the details below.
                                        </td>
                                    </tr>
                                @endif
                            </table>

                            <!-- REF NO BOX -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="pad24 gap-top" style="padding:18px 24px 0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e5e7eb; background-color:#f0f7ff; border-left:4px solid #59c2e5;">
                                            <tr>
                                                <td class="box-pad" style="padding:14px 16px; font-family:Helvetica, Arial, sans-serif; font-size:11px; font-weight:700; color:#64748b; letter-spacing:.4px;">
                                                    Ref No.<br>
                                                    <span style="display:block; margin-top:4px; font-size:11px; font-weight:700; color:#0369a1;">{{$borrowing->reference_no}}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- approver's remarks -->
                            @if(isset($type) && ($type == 'approved' || $type == 'declined') && !blank($borrowing->mbd_remarks))
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="pad24 gap-top" style="padding:18px 24px 0 24px;">
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e5e7eb; border-left:4px solid #5ac3e6; background-color:#f8fafc;">
                                                <tr>
                                                    <td class="box-pad" style="padding:14px 16px; font-family:Helvetica, Arial, sans-serif; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.4px;">
                                                        MBD's Remarks<br>
                                                    <span style="display:block; margin-top:6px; font-size:13px; font-weight:400; line-height:1.6; color:#334155; white-space:pre-line;">{{ $borrowing->mbd_remarks }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            @endif


                            <!-- SECTION TITLE -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="sec-wrap" style="padding:26px 24px 12px 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="font-family:Helvetica, Arial, sans-serif; font-size:14px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">Borrower Information</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- INFO TABLE -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:separate;">
                                <tr>
                                    <td class="pad24" style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Borrower's Profile</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{$borrowing->borrower_profile}}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Borrower's Full Name</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;"><strong>{{$borrowing->borrower_first_name}} {{$borrowing->borrower_last_name}}</strong></td>
                                            </tr>
                                            @if ($borrowing->department)
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Department</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{$borrowing->department}}</td>
                                            </tr>
                                            @endif

                                            @if ($borrowing->business_name)
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Business Name</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{$borrowing->business_name}}</td>
                                            </tr>
                                            @endif

                                            @if ($borrowing->designation)
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Designation</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{$borrowing->designation}}</td>
                                            </tr>
                                            @endif

                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Email</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{$borrowing->email}}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Phone Number</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{$borrowing->phone_number}}</td>
                                            </tr>

                                            @if ($borrowing->hub_name)
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Hub</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; word-break:break-word;">{{ trim(($borrowing->hub_code ?? '') . ' · ' . ($borrowing->hub_name ?? ''), ' ·') ?: '-' }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- SECTION TITLE -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="sec-wrap" style="padding:26px 24px 12px 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="font-family:Helvetica, Arial, sans-serif; font-size:14px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">Event Details</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- INFO TABLE -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:separate;">
                                <tr>
                                    <td class="pad24" style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Date Needed</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ $borrowing->date_needed ? date('F j, Y', strtotime($borrowing->date_needed)) : '—' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Event Name</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{$borrowing->event_name}}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Request Details</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; white-space:pre-line; word-break:break-word;">{{$borrowing->request_details}}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Number of Days Needed</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; word-break:break-word;">{{$borrowing->number_of_days_item_needed}} day(s)</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- SECTION TITLE -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="sec-wrap" style="padding:26px 24px 12px 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="font-family:Helvetica, Arial, sans-serif; font-size:14px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">Item for Borrowing</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- INFO TABLE -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:separate;">
                                <tr>
                                    <td class="pad24" style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Borrowed Items</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; word-break:break-word;">
                                                    @if ($borrowing->borrowedItems->isNotEmpty())
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                        @foreach ($borrowing->borrowedItems as $item)
                                                        <tr>
                                                            <td style="padding:0 0 6px 0;">
                                                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e5e7eb; background-color:#f8fafc;">
                                                                    <tr>
                                                                        <td style="padding:6px 12px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#1f2937; border-right:1px solid #e5e7eb;">{{ $item->item_name }} &times; {{ $item->requested_quantity }}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- FOOTER -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #efefef;">
                                <tr>
                                    <td class="foot-td" style="padding:16px 24px 24px 24px; font-family:Helvetica, Arial, sans-serif; font-size:12px; color:#888888; text-align:center;">This is an automated notification from the HERA system. Please do not reply to this email.</td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

                <!--[if mso]>
                </td></tr></table>
                <![endif]-->
            </td>
        </tr>
    </table>

</body>

</html>
