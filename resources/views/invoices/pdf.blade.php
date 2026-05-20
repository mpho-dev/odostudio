<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 28px;
        }
        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .detail-section {
            flex: 1;
        }
        .detail-section h3 {
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .total-row {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .amount {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Invoice</h1>
            </div>
            <div style="text-align: right;">
                <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Date:</strong> {{ now()->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="details">
            <div class="detail-section">
                <h3>Invoice Details</h3>
                <p><strong>Client:</strong> {{ $invoice->booking->bookingRequest->name }} {{ $invoice->booking->bookingRequest->surname }}</p>
                <p><strong>Email:</strong> {{ $invoice->booking->bookingRequest->email }}</p>
                <p><strong>Phone:</strong> {{ $invoice->booking->bookingRequest->phone }}</p>
            </div>
            <div class="detail-section">
                <h3>Booking Information</h3>
                <p><strong>Event Date:</strong> {{ $invoice->booking->event_date->format('M d, Y H:i') }}</p>
                <p><strong>Location:</strong> {{ $invoice->booking->location }}</p>
                <p><strong>Photographer:</strong> {{ $invoice->booking->photographer->name }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount">Rate</th>
                    <th class="amount">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Event Services</td>
                    <td class="amount">${{ number_format($invoice->rate, 2) }}</td>
                    <td class="amount">${{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" class="amount">Total:</td>
                    <td class="amount">${{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        @if ($invoice->notes)
            <div style="margin-bottom: 30px;">
                <h3>Notes</h3>
                <p>{{ $invoice->notes }}</p>
            </div>
        @endif

        <div class="footer">
            <p>This is a computer-generated invoice. No signature required.</p>
        </div>
    </div>
</body>
</html>
