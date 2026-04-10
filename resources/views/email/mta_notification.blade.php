<h3>New MTA Request</h3>

<p>Hello {{ $approver->name }},</p>

<p>A new MTA request has been submitted.</p>

<ul>
    <li>Date: {{ $mta->mta_date }}</li>
    <li>Work Location: {{ $mta->work_location }}</li>
    <li>Amount: {{ $mta->mta_amount }}</li>
</ul>

<p>Please review it in the system.</p>