<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $sale->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.8;
        }
        .content {
            padding: 30px;
        }
        .meta-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
        }
        .meta-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .meta-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .meta-value {
            font-size: 14px;
            color: #334155;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-align: left;
            padding: 12px;
            font-size: 13px;
            border-bottom: 2px solid #cbd5e1;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #334155;
        }
        .text-right {
            text-align: right;
        }
        .total-row td {
            font-weight: 700;
            font-size: 16px;
            color: #0f172a;
            border-bottom: none;
            padding-top: 20px;
        }
        .footer {
            background-color: #f8fafc;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .badge {
            background-color: #10b981;
            color: white;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thank You for Your Purchase!</h1>
            <p>Invoice #{{ $sale->invoice_number }}</p>
        </div>

        <div class="content">
            <div class="meta-section">
                <div class="meta-column">
                    <div class="meta-label">Billed To</div>
                    <div class="meta-value">
                        <strong>{{ $sale->customer->name ?? 'Walk-in Customer' }}</strong><br>
                        @if($sale->customer)
                            {{ $sale->customer->email }}<br>
                            {{ $sale->customer->phone }}
                        @endif
                    </div>
                </div>
                <div class="meta-column text-right">
                    <div class="meta-label">Details</div>
                    <div class="meta-value">
                        <strong>Date:</strong> {{ $sale->created_at->format('F d, Y h:i A') }}<br>
                        <strong>Branch:</strong> {{ $sale->branch->name ?? 'Main' }}<br>
                        <strong>Status:</strong> <span class="badge">Paid</span>
                    </div>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product->name }}</strong><br>
                                <small style="color: #64748b;">SKU: {{ $item->product->sku }}</small>
                            </td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2"></td>
                        <td class="text-right"><strong>Grand Total:</strong></td>
                        <td class="text-right"><strong>${{ number_format($sale->total_amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <p style="font-size: 13px; color: #64748b; text-align: center; margin-top: 40px;">
                If you have any questions about this invoice, please contact support.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
