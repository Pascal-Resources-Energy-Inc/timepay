@php
    $plantApprover = \App\ApproverSetting::with('user.employee')
        ->where('type_of_form', 'uir')
        ->where('status', 'Active')
        ->get()
        ->first(function($s) {
            $loc = $s->user->employee->location ?? '';
            return str_contains($loc, 'Plant');
        });

    $plantApproverName = $plantApprover ? $plantApprover->user->name : 'Mary Joy Gonzales';
    $plantApproverPosition = $plantApprover && $plantApprover->user->employee 
        ? $plantApprover->user->employee->position
        : 'Human Resource Assistant';

    $otherLocationsApprover = \App\ApproverSetting::with('user.employee')
        ->where('type_of_form', 'uir')
        ->where('status', 'Active')
        ->get()
        ->first(function($s) {
            $loc = $s->user->employee->location ?? '';
            return !str_contains($loc, 'Plant');
        });
    $otherLocationsName = $otherLocationsApprover ? $otherLocationsApprover->user->name : 'Reymalyn Padilla';
    $otherLocationsTitle = $otherLocationsApprover && $otherLocationsApprover->user->employee ? $otherLocationsApprover->user->employee->position : 'Business Process Officer';
@endphp

<script>
    function printAccountabilityForm(modalId, iurId) {
        printAccForm(modalId, iurId, null);
    }

    function printAccForm(modalId, iurId, accData) {
        const modal = document.getElementById(modalId);
        const data = document.getElementById('accountability-data-' + iurId).dataset;

        const employeeName = data.employeeName;
        const branch = data.branch;
        const position = data.position;
        const department = data.department;
        const reference = data.reference;
        const requestFor = data.requestFor;
        const size = data.size;
        const idRequest = data.idRequest;
        const showUniform = requestFor === 'Uniform' || requestFor === 'Both';
        const showID = requestFor === 'ID' || requestFor === 'Both';
        const signature = (accData && accData.signature) ? accData.signature : data.signature;
        const employeeCode = data.employeeCode;

        const useAcc = accData !== null;
        const date = (useAcc && accData.issuedDate) ? accData.issuedDate : data.date;
        // const accRef = useAcc ? accData.ref : reference;
        const accRef = reference;

        function itemRows() {
            var rows = '';
            var uniformRowRendered = false;
            if (showUniform) {
                var uniforms = useAcc
                    ? [{label:'Prod',qty:accData.prod,condition:accData.prodCondition},{label:'White',qty:accData.white,condition:accData.whiteCondition},{label:'Black',qty:accData.black,condition:accData.blackCondition},{label:'Polo',qty:accData.collar,condition:accData.collarCondition}]
                    : [{label:'Prod',qty:0,condition:''},{label:'White',qty:0,condition:''},{label:'Black',qty:0,condition:''},{label:'Polo',qty:0,condition:''}];
                var hasUniform = uniforms.some(function(u){return u.qty>0;});
                if (!useAcc || hasUniform) {
                    uniformRowRendered = true;
                    var desc = '';
                    if (useAcc) {
                        uniforms.forEach(function(u){
                            if (u.qty>0) desc += '<p>' + u.qty + 'pc' + (u.qty>1?'s':'') + ' ' + size + ' ' + u.label + (u.condition ? ' (' + u.condition + ')' : '') + '</p>';
                        });
                    } else {
                        desc = '<p>per request</p>';
                    }
                    var rowspan = showID ? ' rowspan="2"' : '';
                    rows += '<tr><td' + rowspan + ' style="vertical-align: middle;">' + department + '</td>';
                    if (useAcc) {
                        var itemText = '';
                        uniforms.forEach(function(u){
                            if (u.qty>0) itemText += '<p>' + u.label + '</p>';
                        });
                        rows += '<td>' + itemText + '</td><td>' + desc + '</td>';
                    } else {
                        rows += '<td><p>Uniform Items</p></td><td>' + desc + '</td>';
                    }
                    rows += '<td>' + accRef + '</td></tr>';
                }
            }
            if (showID && (!useAcc || accData.id)) {
                if (useAcc) {
                    rows += '<tr>' + (uniformRowRendered ? '' : '<td style="vertical-align: middle;">' + department + '</td>') + '<td>ID</td><td>PREI - ' + employeeCode + '</td><td>' + accRef + '</td></tr>';
                } else {
                    rows += '<tr>' + (uniformRowRendered ? '' : '<td style="vertical-align: middle;">' + department + '</td>') + '<td>ID</td><td>PREI - ' + employeeCode + '</td><td>' + accRef + '</td></tr>';
                }
            }
            return rows;
        }

        const plantApproverName = '{{ $plantApproverName }}';
        const plantApproverPosition = '{{ $plantApproverPosition }}';
        const otherLocationsName = '{{ $otherLocationsName }}';
        const otherLocationsTitle = '{{ $otherLocationsTitle }}';

        const isPlant = branch === 'Plant';
        const approverName = isPlant ? plantApproverName : otherLocationsName;
        const approverTitle = isPlant ? plantApproverPosition : otherLocationsTitle;

        const printWindow = window.open('', '_blank', 'width=900,height=600');

        const printContent = `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Accountability Form - Pascal Resources Energy Inc.</title>
        <style>
            @media print {
                body { margin: 0; }
                .no-print { display: none; }
            }

            @page {
                size: A4;
                margin: 12mm;
            }

            body {
                font-family: Arial, sans-serif;
                max-width: 210mm;
                margin: 0 auto;
                padding: 12mm;
                background: white;
                color: black;
                font-size: 11px;
                line-height: 1.3;
            }

            .header {
                border: 1px solid #000;
            }

            .header-grid {
                display: grid;
                grid-template-columns: 3fr 9fr;
            }

            .logo-section {
                border-right: 1px solid #000;
                padding: 16px;
                display: flex;
                align-items: center;
            }

            .logo-text {
                color: #666;
            }

            .logo-text h1 {
                font-size: 36px;
                font-weight: bold;
                margin: 0;
                line-height: 1;
                letter-spacing: -1px;
            }

            .logo-text h2 {
                font-size: 24px;
                font-weight: normal;
                margin: 0;
                line-height: 1;
            }

            .title-section {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .title-section h2 {
                font-size: 28px;
                font-weight: bold;
                color: #000;
                letter-spacing: 2px;
            }

            .info-row {
                margin-top: 12px;
            }

            .info-row span {
                display: inline;
            }

            .info-row strong {
                font-weight: bold;
            }

            .intro {
                margin-top: 12px;
                line-height: 1.6;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 16px;
                text-align: center;
            }

            table th, table td {
                border: 1px solid black;
                padding: 8px;
            }

            table td p {
                margin: 0;
                padding: 0;
                line-height: 1;
            }

            table thead {
                background: #f3f3f3;
            }

            .terms {
                list-style-type: disc;
                padding-left: 24px;
                margin-top: 24px;
                line-height: 1.6;
            }

            .terms li {
                margin-bottom: 8px;
            }

        </style>
    </head>
    <body>
        <div class="header">
            <div class="header-grid">
                <div class="logo-section">
                    <div class="logo-text">
                        <h1 style="color: #000;">PASCAL</h1>
                        <h2 style="color: #000;">RESOURCES</h2>
                        <h2 style="color: #000;">ENERGY,INC.</h2>
                    </div>
                </div>
                <div class="title-section">
                    <h2>ACCOUNTABILITY FORM</h2>
                </div>
            </div>
        </div>

        <div class="info-row">
            <span>Date:</span>
            <span style="margin-left: 8px;">${date}</span>
        </div>

        <div class="info-row">
            <div><span>Complete Name:</span> <strong>${employeeName}</strong></div>
            <div><span>Branch or Office:</span> <strong>${branch}</strong></div>
            <div><span>Position:</span> <strong>${position}</strong></div>
        </div>

        <p class="intro">
            This is to inform you that you have been designated by Pascal
            Resources Energy, Inc. as the duly sole custodian of the following
            company asset/s:
        </p>

        <table>
            <thead>
                <tr>
                    <th>DEPARTMENT</th>
                    <th>ITEM</th>
                    <th>DESCRIPTION</th>
                    <th>SERIAL NO.</th>
                </tr>
            </thead>
         <tbody>${itemRows()}</tbody>
        </table>

        <ul class="terms">
            <li>As the designated custodian, your use of the company equipment, devices shall be subjected to the following terms and conditions.</li>
            <li>Unless otherwise authorized by the management, the above-described company equipment/devices/unit/s shall be used only for official business.</li>
            <li>While in your custody, you shall be responsible for the safekeeping of the above-mentioned company equipment/devices/unit/s.</li>
            <li>If the equipment/devices/unit/s is/are damaged due to misuse, mishandling or negligence, repairs and materials shall be charged to you.</li>
            <li>If the equipment/devices/unit/s is/are lost due to misuse, mishandling or negligence, cost of the equipment/devices/unit/s shall be charged to you.</li>
            <li>Company-prescribed usage, limits and company policies regarding the equipment/devices/unit/s operating time shall be strictly observed. Any violation of the company policy shall be subjected to disciplinary measures and corresponding penalties, if applicable.</li>
            <li>Any excess in the usage of the above-mentioned equipment/devices/unit/s shall be charged to you.</li>
            <li>The company reserves the right to terminate your designation as custodian of the above-described company equipment/devices/unit/s at any time without prior notice.</li>
            <li>Upon separation from the company, you are required to return the above-mentioned equipment/devices/unit/s in good working condition.</li>
        </ul>

        <div style="margin-top: 50px; display: flex; gap: 70px;">

            <div style="flex: 1; position: relative;">
                <p style="margin-bottom: 20px;">Prepared by:</p>
                <div style="font-style: italic; position: absolute; bottom: 37px; left: 5; font-size: 10px; color: #666;">
                    ${useAcc && accData.issuedDate ? 'Issued on:' + accData.issuedDate + '  - ' : ''} System generated, no signature required
                </div>
                <div style="border-top: 1px solid black; padding-top: 4px;">
                    <div style="font-weight: bold;">${approverName}</div>
                    <div>${approverTitle}</div>
                </div>
            </div>

            <div style="flex: 1; position: relative;">
                <p style="margin-bottom: 20px;">Understood and accepted:</p>
                ${signature ? '<img src="' + signature + '" style="position: absolute; bottom: 22px; left: 0; right: 0; max-height: 55px; display: block;">' : ''}
                ${useAcc && accData.signedDate ? '<div style="position: absolute; top: -5px; right: 0; color: #666;">Signed on: ' + accData.signedDate + '</div>' : ''}
                <div style="border-top: 1px solid black; padding-top: 4px;">
                    <div style="font-weight: bold;">${employeeName}</div>
                    <div>${position}</div>
                </div>
            </div>
        </div>

        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()" style="padding: 10px 30px; font-size: 14px; cursor: pointer;">Print</button>
            <button onclick="window.close()" style="padding: 10px 30px; font-size: 14px; cursor: pointer; margin-left: 10px;">Close</button>
        </div>
    </body>
    </html>
    `;

        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.focus();

        printWindow.onload = function() {
            setTimeout(function() {
                printWindow.print();
            }, 500);
        };
    }
</script>
