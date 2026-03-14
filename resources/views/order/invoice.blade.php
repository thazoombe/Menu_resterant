<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }} - {{ $appSettings['restaurant_name'] ?? 'Resto Delights' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
            background: #f1f5f9;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 24px;
        }

        .brand h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
        }

        .brand p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 14px;
        }

        .invoice-details {
            text-align: right;
        }

        .invoice-details h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            color: #3b82f6;
        }

        .invoice-details p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 14px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .info-section h3 {
            margin: 0 0 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
        }

        .info-section p {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        th {
            text-align: left;
            padding: 12px;
            background: #f8fafc;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 16px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            margin-left: auto;
            width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .total-row.grand-total {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 2px solid #f1f5f9;
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
        }

        .no-print {
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
            border: none;
        }

        .btn-print {
            background: #0f172a;
            color: white;
        }

        .btn-back {
            background: white;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        @media print {
            body { padding: 0; background: white; }
            .invoice-box { box-shadow: none; border: none; padding: 0; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="/" class="btn btn-back">← Back to Menu</a>
        <button onclick="window.print()" class="btn btn-print">Print Invoice</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div class="brand">
                <h1>{{ $appSettings['restaurant_name'] ?? 'Resto Delights' }}</h1>
                <p>{{ $appSettings['tagline'] ?? 'Hand-crafted meals delivered straight to your door.' }}</p>
            </div>
            <div class="invoice-details">
                <h2>INVOICE</h2>
                <p>#INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p>{{ $order->created_at->format('F d, Y') }}</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-section">
                <h3>Bill To</h3>
                <p>{{ $order->customer_name }}</p>
            </div>
            <div class="info-section" style="text-align: right;">
                <h3>Status</h3>
                <p style="text-transform: capitalize; color: {{ $order->status === 'completed' ? '#10b981' : '#f59e0b' }}">{{ $order->status }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: #0f172a;">{{ $item->menu->name ?? 'Deleted Item' }}</div>
                    </td>
                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row">
                <span style="color: #64748b;">Subtotal</span>
                <span style="font-weight: 600;">${{ number_format($order->total_price, 2) }}</span>
            </div>
            <div class="total-row grand-total">
                <span>Total</span>
                <span>${{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for dining with us!</p>
            <p style="font-size: 12px; margin-top: 8px;">© 2026 {{ $appSettings['restaurant_name'] ?? 'Resto Delights' }}. All Rights Reserved.</p>
        </div>
    </div>

</body>
</html>
