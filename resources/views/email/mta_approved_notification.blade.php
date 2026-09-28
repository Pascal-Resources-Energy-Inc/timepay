<h3>MTA Request Approved</h3>

<p>Hello {{ $mta->user->name }},</p>

<p>Your Monetized Transportation Allowance request has been approved.</p>

<ul>
    <li>Date: {{ $mta->mta_date }}</li>
    <li>Work Location: {{ $mta->work_location }}</li>
    <li>Liters Loaded: {{ $mta->liters_loaded }}</li>
    <li>Amount: {{ number_format($mta->mta_amount, 2) }}</li>
    <li>Remarks: {{ $mta->approval_remarks }}</li>
</ul>

<p>Approved by: {{ $approver->name }}</p>

<p>Please contact your approver if you have any questions.</p>
