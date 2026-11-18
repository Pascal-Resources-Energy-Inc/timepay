<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountability Form</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm 14mm 18mm;

            @bottom-right {
                content: "PORTABLE BOOTH & ROLL-UP BANNER ACCOUNTABILITY FORM | CEO-MBD-FOR-012-001.B";
                color: #dedede;
                font: 8pt Arial, sans-serif;
                text-align: right;
                vertical-align: middle;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #e9edf2;
            color: #111;
            font: 10pt/1.3 Arial, sans-serif;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 24px auto;
            padding: 14mm;
            background: #fff;
        }

        .print-toolbar {
            position: fixed;
            top: 18px;
            right: 22px;
        }

        .print-button {
            border: 0;
            border-radius: 6px;
            padding: 10px 18px;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
        }

        .document-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 5mm;
            margin-bottom: 5mm;
        }

        .document-header img {
            width: 1.20in;
            height: auto;
        }

        h1 {
            margin: 0;
            font-size: 14pt;
            text-align: center;
        }

        h2 {
            margin: 5mm 0 2mm;
            font-size: 10pt;
        }

        h3 {
            margin: 0 0 1mm;
            font-size: 10pt;
            text-align: center;
        }

        section {
            margin-top: 4mm;
        }

        .columns {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 2mm 5mm;
        }

        .bmc-parts-columns > div:only-child {
            grid-column: 1 / -1;
        }

        .field {
            display: flex;
            align-items: baseline;
            gap: 1.5mm;
        }

        .blank {
            flex: 1;
            min-width: 8mm;
            border-bottom: 1px solid;
        }

        .parameter {
            flex: 1;
            min-width: 8mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            height: 6mm;
            padding: 1mm 2mm;
            border: 1px solid #99c1e5;
        }

        .parts, .items {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .parts th, .items th {
            background-color: #507bc8;
            color: #fff;
        }

        .parts tbody tr:nth-child(even), .items tbody tr:nth-child(even) {
            background-color: #f2f7fc;
        }

        .parts td:last-child, .items th {
            text-align: center;
        }

        .items {
            margin-top: 3mm;
            table-layout: fixed;
        }

        ol {
            margin: 0;
            padding-left: 5mm;
        }

        li + li {
            margin-top: 1mm;
        }

        p {
            margin: 0 0 3mm;
        }

        .signature-fields {
            margin-left: auto;
            width: 80mm;
            max-width: 100%;
        }

        .signature-date {
            color: #555;
            font-size: 8pt;
            text-align: right;
        }

        .signature-line {
            position: relative;
            height: 6mm;
            margin-bottom: 1mm;
            border-bottom: 1px solid;
        }

        .signature-line img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 20mm;
            object-fit: contain;
        }

        footer {
            margin-top: 6mm;
            padding-top: 2mm;
            color: #bfbfbf;
            font-size: 8pt;
            text-align: right;
        }

        @media print {
            footer {
                display: none;
            }

            body {
                background: #fff;
            }
            .print-toolbar {
                display: none;
            }
            .page {
                width: auto;
                min-height: 0;
                margin: 0;
                padding: 0;
            }
            section {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    @if ($showActions ?? true)
        <div class="print-toolbar">
            <button type="button" class="print-button" onclick="window.print()">Print Form</button>
        </div>
    @endif

    <main class="page">
        <header class="document-header">
            <div>
            <img src="{{ asset('images/PASCAL_RESOURCES_ENERGY_INC.png') }}" alt="Pascal Resources Energy, Inc.">
            </div>

            <div style="margin: auto;">
                <p style="font-size: 1rem; font-weight: bold;">PORTABLE BOOTH &amp; ROLL-UP BANNER ACCOUNTABILITY FORM</p>
            </div>
        </header>

        <div class="columns">
            <div class="field">
                Date Issued: 
                <span class="parameter">{{ optional($accountability->released_at)->format('F j, Y') }}</span>
            </div>
            <div class="field">Reference No.: <span class="parameter">{{ $accountability->accountability_ref }}</span></div>
        </div>

        <section>
            <h2>USER INFORMATION</h2>
            <div class="columns">
                <div class="field">
                    Name: 
                    <span class="parameter">{{ trim(($borrowing->borrower_first_name ?? '') . ' ' . ($borrowing->borrower_last_name ?? '')) }}</span>
                </div>
                <div class="field">
                    Email: 
                    <span class="parameter">{{ $borrowing->email }}</span>
                </div>
                <div class="field">
                    Location: 
                    <span class="parameter">{{ $borrowing->hub_name ?? '' }}</span>
                </div>
                <div class="field">
                    Phone Number: 
                    <span class="parameter">{{ $borrowing->phone_number }}</span>
                </div>
                <div class="field">
                    Business Name: 
                    <span class="parameter">{{ $borrowing->business_name }}</span>
                </div>
            </div>
        </section>

        <section>
            <h2>EVENT DETAILS</h2>
            <div class="columns">
                <div class="field">Date Needed: <span class="parameter">{{ optional($borrowing->date_needed)->format('F j, Y') }}</span></div>
                <div class="field">Event: <span class="parameter">{{ $borrowing->event_name }}</span></div>
            </div>
        </section>

        <section>
            <h2>BORROWED ITEMS</h2>
            @php
                $releasedItemNames = $accountability->items->pluck('item_name');
            @endphp
            <div class="columns bmc-parts-columns">
                @if ($releasedItemNames->contains('Booth'))
                <div>
                    <p style="text-align: left; margin-bottom: 3px;">Portable Booth Parts</p>
                    <table class="parts" aria-label="Portable booth parts">
                        <thead><tr><th scope="col">Item</th><th scope="col">Qty Issued</th></tr></thead>
                        <tbody>
                            <tr><td>Header</td><td>1 piece</td></tr>
                            <tr><td>Body</td><td>1 piece</td></tr>
                            <tr><td>Poles</td><td>4 pieces</td></tr>
                            <tr><td>Shelves</td><td>3 pieces</td></tr>
                        </tbody>
                    </table>
                </div>
                @endif
                @if ($releasedItemNames->contains('Negosyo Partner Roll Up Banner') || $releasedItemNames->contains('End User Roll Up Banner'))
                <div>
                    <p style="text-align: left; margin-bottom: 3px;">Roll-Up Banner</p>
                    <table class="parts" aria-label="Roll-up banner parts">
                        <thead><tr><th scope="col">Item</th><th scope="col">Qty Issued</th></tr></thead>
                        <tbody>
                            <tr><td>Top Rail</td><td>1 piece</td></tr>
                            <tr><td>Bungee Pole</td><td>1 piece</td></tr>
                            <tr><td>Stand</td><td>1 piece</td></tr>
                            <tr><td>Tarpaulin</td><td>1 piece</td></tr>
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <table class="items" aria-label="Borrowed item serial numbers and remarks">
                <thead>
                    <tr>
                        <th scope="col">Item</th>
                        <th scope="col">Serial Number</th>
                        <th scope="col">MBD Remarks</th>
                        <th scope="col">Borrower Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accountability->items as $item)
                        @forelse ($item->serials as $serial)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $serial->serial_number }}</td>
                                <td>{{ $serial->mbd_remarks ?? '' }}</td>
                                <td>{{ $serial->borrower_remarks ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->serial_number ?? '' }}</td>
                                <td>{{ $item->condition_on_release ?? '' }}</td>
                                <td></td>
                            </tr>
                        @endforelse
                    @endforeach
                </tbody>
            </table>
        </section>

        <section>
            <h2>TERMS OF USE</h2>
            <ol>
                <li>The borrower accepts full responsibility for the care and return of the item borrowed in its original condition.</li>
                <li>The item borrowed is exclusive for use or display of Gaz Lite products only.</li>
                <li>Displaying other products or brands on the portable booth is prohibited.</li>
            </ol>
        </section>

        <section>
            <h2>PAYMENT AGREEMENT</h2>
            <ol>
                <li>The Borrower shall pay a Security Deposit of ₱{{ number_format($accountability->items->sum('deposit_amount'), 2) }}. The security deposit deposit will be refunded to the Borrower only after the items has been returned to PREI and found to be in good condition.</li>
                <li>If the Borrower wishes to extend the borrowing period, they must submit another BMF Requests indicating the requested extension of date and the reason of its extension.</li>
                <li>The Parties affirm and acknowledge that they have carefully read and understood the terms and conditions.</li>
            </ol>
        </section>

        <section>
            <h2>ACKNOWLEDGEMENT</h2>
            <p>By signing below, I acknowledge that I have received the item/s borrowed in the condition noted above, and I agree to the terms of use and liability as stated in this form.</p>
            <div class="signature-fields">
                @if ($accountability->signed_at)
                    <div class="signature-date">Received: {{ $accountability->signed_at->format('M d, Y g:i A') }}</div>
                @endif
                <div style="font-size: 11px; margin-top: 6px;">Understood and Received by</div>
                <div class="signature-line">
                    @if ($accountability->borrower_signature)
                        <img src="{{ asset($accountability->borrower_signature) }}" alt="Borrower signature">
                    @endif
                </div>
                <strong style="font-size: 11px;">{{ trim(($borrowing->borrower_first_name ?? '') . ' ' . ($borrowing->borrower_last_name ?? '')) }}</strong>
                <div style="font-size: 11px;">{{ $borrowing->borrower_profile ?? '' }}</div>
            </div>
        </section>

        <!-- <footer>PORTABLE BOOTH &amp; ROLL-UP BANNER ACCOUNTABILITY FORM | CEO-MBD-FOR-012-001.A</footer> -->
    </main>

    <script>
        document.querySelectorAll('.signature-line img').forEach(function (img) {
            function centerSignature() {
                try {
                    var canvas = document.createElement('canvas');
                    canvas.width = img.naturalWidth;
                    canvas.height = img.naturalHeight;
                    var context = canvas.getContext('2d');
                    context.drawImage(img, 0, 0);
                    var pixels = context.getImageData(0, 0, canvas.width, canvas.height).data;
                    var left = canvas.width, top = canvas.height, right = -1, bottom = -1;

                    for (var y = 0; y < canvas.height; y++) {
                        for (var x = 0; x < canvas.width; x++) {
                            var i = (y * canvas.width + x) * 4;
                            if (pixels[i + 3] > 0 && (pixels[i] < 250 || pixels[i + 1] < 250 || pixels[i + 2] < 250)) {
                                left = Math.min(left, x);
                                top = Math.min(top, y);
                                right = Math.max(right, x);
                                bottom = Math.max(bottom, y);
                            }
                        }
                    }

                    if (right < left || bottom < top) return;
                    var cropped = document.createElement('canvas');
                    cropped.width = right - left + 1;
                    cropped.height = bottom - top + 1;
                    cropped.getContext('2d').drawImage(canvas, left, top, cropped.width, cropped.height, 0, 0, cropped.width, cropped.height);
                    img.src = cropped.toDataURL('image/png');
                } catch (error) {
                    console.warn('Unable to trim signature image:', error);
                }
            }

            if (img.complete && img.naturalWidth) centerSignature();
            else img.addEventListener('load', centerSignature, { once: true });
        });
    </script>
</body>
</html>
