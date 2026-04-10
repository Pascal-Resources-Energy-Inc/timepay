<h3>MTA Request Declined</h3>

<p>Hello {{ $mta->user->name }},</p>

<p>Your Monetized Transportation Allowance request has been declined.</p>

<ul>
    <li>Date: {{ $mta->mta_date }}</li>
    <li>Work Location: {{ $mta->work_location }}</li>
    <li>Liters Loaded: {{ $mta->liters_loaded }}</li>
    <li>Amount: {{ number_format($mta->mta_amount, 2) }}</li>
    <li>Remarks: {{ $mta->approval_remarks }}</li>
</ul>

<p>If you believe this was made in error, please contact your approver.</p>

<p>Declined by: {{ $approver->name }}</p>