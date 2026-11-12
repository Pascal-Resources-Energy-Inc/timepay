<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Layout Design Request — {{ ucfirst($type ?? 'submitted') }}</title>

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

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="container" style=" width:100%;">
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
                                    <td class="pad24 sub-td" style="padding:7px 24px 12px 24px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#666666;">Layout Design Request</td>
                                </tr>
                            </table>

                            <!-- GREETING -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                @if(isset($type) && in_array($type, ['approved', 'processed', 'declined']))
                                    <tr>
                                        <td class="greet-td" style="padding:20px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                                            Hi {{$ldr->requestor_first_name}},
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="greet-msg" style="padding:10px 24px 0 24px; font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:1.6; color:#374151;">
                                            @if($type == 'approved')
                                                Your layout design request has been approved.
                                            @elseif($type == 'processed')
                                                Your layout design request has been processed.
                                                @if (!empty($hasEmailAttachments))
                                                    See the attached file{{ $emailAttachmentCount > 1 ? 's' : '' }}.
                                                @endif
                                            @else
                                                Your layout design request has been declined.
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
                                            A new layout design request has been submitted and is awaiting for MBD's review. Please review the details below.
                                        </td>
                                    </tr>
                                @endif
                            </table>

                            @if(isset($type) && $type == 'processed' && !empty($hasPortalOnlyAttachments))
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="pad24" style="padding:18px 24px 0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #cfe0f1; background-color:#edf4fb; border-left:4px solid #527694;">
                                            <tr>
                                                <td style="padding:14px 16px; font-family:Helvetica, Arial, sans-serif; font-size:13px; line-height:1.6; color:#374151;">
                                                    One or more files were not attached because they exceed email delivery limits. Please log in to your HERA account and open this request to access the complete files.
                                                    <br>
                                                    <a href="{{ route('layout-design-requests.show', $ldr->id) }}" style="display:inline-block; margin-top:8px; color:#0369a1; font-weight:600; text-decoration:underline;">Click here</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- approver's comment -->
                            @if(isset($type) && $type == 'processed')
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="pad24" style="padding:24px 24px 1px 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border: 1px solid #e5e7eb;">
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Approver's comment</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; white-space:pre-line; word-break:break-word;">{{ $ldr->closing_remarks ?: '' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- REF NO BOX -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="pad24 gap-top" style="padding:18px 24px 0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e5e7eb; background-color:#f0f7ff; border-left:4px solid #59c2e5;">
                                            <tr>
                                                <td class="box-pad" style="padding:14px 16px; font-family:Helvetica, Arial, sans-serif; font-size:11px; font-weight:700; color:#64748b; letter-spacing:.4px;">
                                                    Ref No.<br>
                                                    <span style="display:block; margin-top:4px; font-weight:700; color:#0369a1;">{{$ldr->reference_no}}</span>
                                                </td>
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
                                                <td style="font-family:Helvetica, Arial, sans-serif; font-size:14px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">Request Information</td>
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
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Date Requested</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ $ldr->date_requested ? date('F j, Y', strtotime($ldr->date_requested)) : '—' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Requestor's Name</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;"><strong>{{$ldr->requestor_first_name}} {{$ldr->requestor_last_name}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Department</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{$ldr->department}}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Requestor's Email</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; word-break:break-word;">{{$ldr->requestor_email}}</td>
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
                                                <td style="font-family:Helvetica, Arial, sans-serif; font-size:14px; font-weight:700; color:#111111; text-transform:uppercase; letter-spacing:.4px;">Design Details</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- INFO TABLE -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:separate;">
                                <tr>
                                    <td class="pad24" style="padding:0 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border: 1px solid #e5e7eb;">
                                            @php
                                                $normalize = function ($value) {
                                                    if (is_array($value)) {
                                                        return $value;
                                                    }
                                                    if (!is_string($value)) {
                                                        return [];
                                                    }
                                                    $decoded = json_decode($value, true);
                                                    if (is_array($decoded)) {
                                                        return $decoded;
                                                    }
                                                    return array_filter(array_map('trim', explode(',', $value)));
                                                };
                                                $intended = $normalize($ldr->intended_use);
                                                $collateral = $normalize($ldr->marketing_collateral_type);
                                                $hasStoreSignageDetails = $ldr->business_name
                                                    || $ldr->ad_territory
                                                    || $ldr->business_address
                                                    || $ldr->contact_number;
                                            @endphp
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Intended Use</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">
                                                    @if ($intended)
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                        @foreach ($intended as $item)
                                                        <tr>
                                                            <td style="padding:0 0 6px 0;">
                                                                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td class="chip-td" bgcolor="#eef2ff" style="background-color:#eef2ff; padding:3px 10px; font-family:Helvetica, Arial, sans-serif; font-size:12px; font-weight:600; color:#3730a3; border:1px solid #e0e7ff;">{{$item}}</td>
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
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Medium Type</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{$ldr->medium_type}}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Marketing Collateral Type</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">
                                                    @if ($collateral)
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                        @foreach ($collateral as $item)
                                                        <tr>
                                                            <td style="padding:0 0 6px 0;">
                                                                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td class="chip-td" bgcolor="#eef2ff" style="background-color:#eef2ff; padding:3px 10px; font-family:Helvetica, Arial, sans-serif; font-size:12px; font-weight:600; color:#3730a3; border:1px solid #e0e7ff;">{{$item}}</td>
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
                                            @if ($hasStoreSignageDetails)
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Business Name</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ $ldr->business_name ?: '—' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">AD Territory</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ $ldr->ad_territory ?: '—' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Business Address</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ $ldr->business_address ?: '—' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Contact Number</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ $ldr->contact_number ?: '—' }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Purpose of Layout Request</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; white-space:pre-line; word-break:break-word;">{{$ldr->purpose_of_layout_request}}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Project Description</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; white-space:pre-line; word-break:break-word;">{{$ldr->project_description}}</td>
                                            </tr>
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Preferred Date Needed</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ $ldr->date_needed ? date('F j, Y', strtotime($ldr->date_needed)) : '-' }}</td>
                                            </tr>
                                            @if ($ldr->priority_level)
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Priority Level</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ $ldr->priority_level }}</td>
                                            </tr>
                                            @endif
                                            @if ($ldr->due_date)
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Due Date</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; border-bottom:none; word-break:break-word;">{{ optional($ldr->due_date)->format('F j, Y') }}</td>
                                            </tr>
                                            @endif
                                            @if ($ldr->pegSamples->isNotEmpty() || $ldr->peg_sample_path)
                                            <tr>
                                                <td class="lbl" width="38%" bgcolor="#f8fafc" style="width:38%; padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#475569; vertical-align:top; border:1px solid #e5e7eb; border-right:none; white-space:nowrap;">Peg Sample</td>
                                                <td class="val" style="padding:10px 14px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#1f2937; vertical-align:top; border:1px solid #e5e7eb; word-break:break-word;">
                                                    @if ($ldr->pegSamples->isNotEmpty() || $ldr->peg_sample_path)
                                                        <a href="{{ route('ldr-approval.view', $ldr->id) }}" style="color:#0369a1; font-weight:600; text-decoration:underline;">View request in HERA</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- FOOTER -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #efefef; margin-top: 20px;">
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
