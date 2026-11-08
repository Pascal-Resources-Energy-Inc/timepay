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
    @php
        $iur = $acc->iur;
        $employee = $iur->user;
        $employeeName = $employee->first_name . ' ' . $employee->last_name;
        $branch = $iur->work_location;
        $position = $employee->position;
        $department = $employee->department->name ?? '';
        $reference = $acc->accountability_ref;
        $requestFor = $iur->request_for;
        $size = $iur->size;
        $employeeCode = $employee->employee_code ?? '';
        $date = $acc->issued_date ? date('M d, Y', strtotime($acc->issued_date)) : '-';

        $showUniform = in_array($requestFor, ['Uniform', 'Both']);
        $showID = in_array($requestFor, ['ID', 'Both']);
        $uniforms = [
            ['label' => 'Prod', 'qty' => $acc->released_prod_qty, 'condition' => $acc->prod_condition],
            ['label' => 'White', 'qty' => $acc->released_white_qty, 'condition' => $acc->white_condition],
            ['label' => 'Black', 'qty' => $acc->released_black_qty, 'condition' => $acc->black_condition],
            ['label' => 'Polo', 'qty' => $acc->released_collar_qty, 'condition' => $acc->collar_condition],
        ];
        $hasUniform = $showUniform && collect($uniforms)->some(fn($u) => $u['qty'] > 0);

        $plant_location_approver = \App\ApproverSetting::with('user.employee')
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->get()
            ->first(function($s) {
                $loc = $s->user->employee->location ?? '';
                return str_contains($loc, 'Lubao');
            });
        $plantApproverName = $plant_location_approver ? $plant_location_approver->user->name : 'Mary Joy Gonzales';
        $plantApproverDesignation = $plant_location_approver && $plant_location_approver->user->employee ? $plant_location_approver->user->employee->position : '';

        $otherLocationsApprover = \App\ApproverSetting::with('user.employee')
            ->where('type_of_form', 'uir')
            ->where('status', 'Active')
            ->get()
            ->first(function($s) {
                $loc = $s->user->employee->location ?? '';
                return !str_contains($loc, 'Lubao');
            });
        $otherLocationsName = $otherLocationsApprover ? $otherLocationsApprover->user->name : 'Reymalyn Padilla';
        $otherLocationsTitle = $otherLocationsApprover && $otherLocationsApprover->user->employee ? $otherLocationsApprover->user->employee->position : 'Business Process Officer';

        $isLubaoGuinobatan = $branch === 'Guinobatan Warehouse / Lubao Office';
        $approverName = $isLubaoGuinobatan ? $plantApproverName : $otherLocationsName;
        $approverTitle = $isLubaoGuinobatan ? $plantApproverDesignation : $otherLocationsTitle;

        $signature = $acc->employee_signature ? asset($acc->employee_signature) : null;
        $signedDate = $acc->signed_at ? date('M d, Y g:i A', strtotime($acc->signed_at)) : null;
    @endphp

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
        <span style="margin-left: 8px;">{{ $date }}</span>
    </div>

    <div class="info-row">
        <div><span>Complete Name:</span> <strong>{{ $employeeName }}</strong></div>
        <div><span>Branch or Office:</span> <strong>{{ $branch }}</strong></div>
        <div><span>Position:</span> <strong>{{ $position }}</strong></div>
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
        <tbody>
            @if($hasUniform)
                <tr>
                    <td style="vertical-align: middle;" rowspan="{{ $showID && $acc->released_id ? 2 : 1 }}">{{ $department }}</td>
                    <td>
                        @foreach($uniforms as $u)
                            @if($u['qty'] > 0)
                            <p>{{ $u['label'] }}</p>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($uniforms as $u)
                            @if($u['qty'] > 0)
                            <p>{{ $u['qty'] }}pc{{ $u['qty'] > 1 ? 's' : '' }} {{ $size }} {{ $u['label'] }}{{ $u['condition'] ? ' (' . $u['condition'] . ')' : '' }}</p>
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $reference }}</td>
                </tr>
            @endif
            @if($showID && $acc->released_id)
            <tr>
                @if(!$hasUniform)
                <td style="vertical-align: middle;">{{ $department }}</td>
                @endif
                <td>ID</td>
                <td>PREI - {{ $employeeCode }}</td>
                <td>{{ $reference }}</td>
            </tr>
            @endif
        </tbody>
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

            @if($acc->issued_date)
            <div style="position: absolute; top: -5px; right: 0; color: #666;">
                Issued on: {{ date('M d, Y', strtotime($acc->issued_date)) }}
            </div>
            @endif

            <div style="text-align: center; width: 100%; font-style: italic; position: absolute; bottom: 37px; color: #666;">
                System generated, no signature required
            </div>
            <div style="border-top: 1px solid black; padding-top: 4px;">
                <div style="font-weight: bold;">{{ $acc->issuer->name }}</div>
                <div>{{ $acc->issuer->employee->position }}</div>
            </div>
        </div>

        <div style="flex: 1; position: relative;">
            <p style="margin-bottom: 20px;">Understood and accepted:</p>
            @if($signature)
              <img src="{{ $signature }}" style="position: absolute; bottom: 22px; left: 0; right: 0; max-height: 55px; display: block;">
            @endif

            @if($signedDate)
            <div style="position: absolute; top: -5px; right: 0; color: #666;">Signed on: {{ $signedDate }}</div>
            @endif
            <div style="border-top: 1px solid black; padding-top: 4px;">
                <div style="font-weight: bold;">{{ $employeeName }}</div>
                <div>{{ $position }}</div>
            </div>
        </div>
    </div>

    @if($showActions ?? true)
    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 14px; cursor: pointer;">Print</button>
        <button onclick="window.close()" style="padding: 10px 30px; font-size: 14px; cursor: pointer; margin-left: 10px;">Close</button>
    </div>
    @endif
</body>
</html>
