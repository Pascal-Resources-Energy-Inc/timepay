<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Certificate of Employment - Pascal Resources Energy Inc.</title>
    <style>
        @media print {
            body {
                margin: 0;
            }

            .no-print {
                display: none !important;
            }
        }

        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            max-width: 8.5in;
            margin: 0 auto;
            padding: 30px;
            background: white;
            color: black;
            line-height: 1.4;
            font-size: 12px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 60px;
        }

        .company-logo {
            display: table-cell;
            width: 200px;
            vertical-align: top;
        }

        .company-info {
            display: table-cell;
            text-align: right;
            vertical-align: top;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
        }

        /* New wrapper */
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 60px;
        }

        .certificate-title {
            text-align: center;
            font-weight: bold;
            font-size: 25px;
            margin: 80px 0 60px 0;
            text-decoration: underline;
        }

        .certificate-body {
            margin: 60px 0;
            text-align: justify;
            line-height: 1.8;
            font-size: 14px;
        }

        .certificate-paragraph, .second-paragraph {
            margin-bottom: 30px;
            font-size: 14px;
            text-indent: 0;
            line-height: 1.4;
        }
        
        .second-paragraph {
            margin-bottom: 15px;
        }

        .date-location {
            margin: 60px 0;
            font-size: 14px;
        }

        .signature-section {
            margin: 100px 0 60px 0;
            line-height: 1.3;
            font-size: 14px;
        }

        .signature-name, .signature-title{
            font-weight: bold;
        }

        .signature-contact {
            color: #0066cc;
            text-decoration: underline;
            margin-bottom: 3px;
        }

        .reference-section {
            margin-top: 80px;
            font-size: 12px;
        }

        .bold {
            font-weight: bold;
        }

        /* for salary details */
        .detail-table { width: 100%; border-collapse: collapse; line-height: 1.4; }
        .detail-table td { padding: 0; vertical-align: top; }
        .detail-label { width: 220px; font-weight: bold; padding-right: 12px; }

        #co-salary[contenteditable="true"] {
            outline: none;
            border-radius: 2px;
        }

        #co-salary[contenteditable="true"]:hover {
            box-shadow: 0 0 0 1px #cbd5e1;
            background: #f8fafc;
        }

        #co-salary[contenteditable="true"]:focus {
            box-shadow: 0 0 0 2px #3b82f6;
            background: #eff6ff;
        }

        #co-position[contenteditable="true"] {
            outline: none;
            border-radius: 2px;
        }

        #co-position[contenteditable="true"]:hover {
            box-shadow: 0 0 0 1px #cbd5e1;
            background: #f8fafc;
        }

        #co-position[contenteditable="true"]:focus {
            box-shadow: 0 0 0 2px #3b82f6;
            background: #eff6ff;
        }
    </style>
</head>

<body>
    @if (!isset($isPdf) || !$isPdf)
    <div style="text-align: right; margin-bottom: 20px;" class="no-print">
        @if ($coe->request === 'With Salary')
            <span style="font-size: 11px; color: #888; margin-right: 8px;">Click the Salary value to edit it.</span>
        @endif
        <button onclick="window.print()" style="padding: 8px 20px; font-size: 14px; cursor: pointer;">Print</button>
    </div>
    @endif
    <div class="header-container">
        <div class="company-logo">
            <div>
                @php
                $logoPath = public_path('images/PASCAL_RESOURCES_ENERGY_INC.png');
                $logoSrc = file_exists($logoPath)
                ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                : '';
                @endphp

                @if($logoSrc)
                <img src="{{ $logoSrc }}" alt="PASCAL RESOURCES ENERGY, INC." style="max-width:100%;height:auto;display:block;margin:0 auto;">
                @endif
            </div>
        </div>
        <div class="company-info">
            <div><strong>PASCAL RESOURCES ENERGY, INC.</strong></div>
            <div>Principal Address: Barangay San Isidro, Lubao, Pampanga</div>
            <div>Corporate Office: 93 West Capitol Drive, Barangay Kapitolyo, Pasig City</div>
            <div>TIN # 009-628-713</div>
            <div>Telephone number: 8244-1784</div>
        </div>
    </div>

    <div class="clear"></div>

    <div class="certificate-title">CERTIFICATE OF EMPLOYMENT</div>

    <div class="certificate-body">
    <div class="certificate-paragraph">
        This is to certify that <span class="bold">{{ $coe->salutation }}{{ $coe->first_name }} {{ $coe->last_name }}</span>
        @if($coe->employment_status === 'Separated')
            was an employee of
        @else
            is currently an employee of
        @endif
        Pascal Resources Energy, Inc.
        as
        <span class="bold">{{ $coe->designation }}</span>,
        @if($coe->employment_status === 'Separated')
            from
        @else
            since
        @endif
        <span class="bold">{{ \Carbon\Carbon::parse($coe->hiring_date)->format('F j, Y') }}</span>
        @if($coe->employment_status === 'Separated' && $coe->resign_date)
            to <span class="bold">{{ \Carbon\Carbon::parse($coe->resign_date)->format('F j, Y') }}</span>
        @endif
        @if ($coe->reason_for_request === 'With Salary')
            with compensation of <span id="co-salary" contenteditable="true">(editable)</span>
        @endif
    </div>

        <!-- don't remove this. this format might be useful -->

        <!-- @if ($coe->reason_for_request === 'With Salary') -->
        <!--     <div class="second-paragraph"> -->
        <!--         As of the date of this certification, the employment and compensation details are as follows: -->
        <!--     </div> -->
        <!---->
        <!--     <div class="detail-block"> -->
        <!--         <table class="detail-table" cellpadding="0" cellspacing="0"> -->
        <!--             <tr><td class="detail-label">Position:</td><td id="co-position" contenteditable="true">{{ $coe->designation ?? '-' }}</td></tr> -->
        <!--             <tr><td class="detail-label">Employment Status:</td><td>{{ $coe->employment_status ?? '-'}}</td></tr> -->
        <!--             <tr><td class="detail-label">Date Hired:</td><td>{{ \Carbon\Carbon::parse($coe->hiring_date)->format('F j, Y') }}</td></tr> -->
        <!--             <tr><td class="detail-label">Salary:</td><td id="co-salary" contenteditable="true">(editable)</td></tr> -->
        <!--         </table> -->
        <!--     </div> -->
        <!---->
        <!--     <br> -->
        <!-- @endif -->

        <div style="line-height: 1.2;">
            This certification is being issued upon the request of 
            <b>{{ $coe->salutation }} {{ $coe->last_name }}</b>
            for the following purpose: <span class="bold">{{ $coe->purpose }}</span>
        </div>
    </div>

    <div class="date-location">
        @php
            $issued = \Carbon\Carbon::parse($coe->processed_at ?? '');
        @endphp
        Issued this <span class="bold">{{ $issued->format('jS') }}</span> day of <span class="bold">{{ $issued->format('F Y') }}</span> at <span class="bold">Kapitolyo, Pasig City, Philippines.</span>
    </div>

    <div class="signature-section">
        <div class="signature-name">Matthew C. Par</div>
        <div class="signature-title">VP – Marketing and Branding</div>
        <div class="signature-title">Pascal Resources Energy, Inc.</div>

        <div>E: <span class="signature-contact">hr@pascalresources.com.ph<span></div>
        <div>M: +639989589865</div>
        <div>T: 8244-1784</div>
    </div>

    <div class="reference-section">
        Ref: {{ $coe->reference_number }}
    </div>
</body>

</html>
