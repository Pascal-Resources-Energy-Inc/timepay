<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TDS Update</title>
</head>
<body style="font-family: Arial; line-height: 1.6;">

    <h2 style="color:#2c3e50;">TDS Amount Updated</h2>

    <p>Hello,</p>

    <p>The TDS record has been updated successfully. Below are the details:</p>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr>
            <td><strong>Customer Name</strong></td>
            <td>{{ $record->customer_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>{{ $record->status ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Purchase Amount</strong></td>
            <td>₱ {{ number_format($record->purchase_amount, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Updated At</strong></td>
            <td>{{ $record->updated_at }}</td>
        </tr>
    </table>

    <br>

    <p>
        @if($filePath)
            📎 Attached is the uploaded document for your reference.
        @else
            No document was uploaded.
        @endif
    </p>

    <br>

    <p>Regards,<br><strong>Your System</strong></p>

</body>
</html>