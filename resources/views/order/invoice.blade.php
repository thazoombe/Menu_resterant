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

        .brand h1 { margin: 0; font-size: 24px; font-weight: 800; color: #0f172a; }
        .brand p { margin: 4px 0 0; color: #64748b; font-size: 14px; }
        .invoice-details { text-align: right; }
        .invoice-details h2 { margin: 0; font-size: 20px; font-weight: 800; color: #3b82f6; }
        .invoice-details p { margin: 4px 0 0; color: #64748b; font-size: 14px; }

        /* Order Info Grid - Refined Style */
        .info-grid {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 40px;
            background: #111827;
            padding: 25px;
            border-radius: 16px;
            color: white;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
        .detail-label { color: #94a3b8; font-size: 14px; }
        .detail-value { font-weight: 700; font-size: 14px; }
        .detail-amount { color: #10b981; font-size: 24px; font-weight: 800; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th { text-align: left; padding: 12px; background: #f8fafc; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #64748b; border-bottom: 1px solid #e2e8f0; }
        td { padding: 16px 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .text-right { text-align: right; }

        .totals { margin-left: auto; width: 300px; }
        .total-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .total-row.grand-total { margin-top: 12px; padding-top: 12px; border-top: 2px solid #f1f5f9; font-size: 20px; font-weight: 800; color: #0f172a; }

        .footer { margin-top: 60px; text-align: center; color: #94a3b8; font-size: 14px; }
        .no-print { margin-bottom: 24px; display: flex; gap: 12px; justify-content: center; }
        .btn { padding: 10px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; text-decoration: none; transition: all 0.2s; font-size: 14px; border: none; }
        .btn-print { background: #0f172a; color: white; }
        .btn-back { background: white; color: #64748b; border: 1px solid #e2e8f0; }

        /* Payment QR Section */
        .payment-section { margin-top: 40px; padding-top: 40px; border-top: 2px dashed #f1f5f9; display: flex; gap: 40px; align-items: center; }
        .qr-card { width: 180px; height: 180px; background: #f8fafc; padding: 12px; border-radius: 1rem; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; }
        .qr-card img { width: 100%; height: 100%; object-fit: contain; }
        .payment-info { flex: 1; }
        .payment-info h3 { margin: 0 0 12px; font-size: 18px; font-weight: 800; color: #0f172a; }
        .bank-details { background: #f8fafc; padding: 16px; border-radius: 0.75rem; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        .bank-details p { margin: 4px 0; font-size: 13px; color: #64748b; }
        .bank-details strong { color: #1e293b; }

        @media (max-width: 600px) {
            .payment-section { flex-direction: column; text-align: center; }
            .qr-card { margin: 0 auto; }
        }

        @media print {
            body { padding: 0; background: white; }
            .invoice-box { box-shadow: none; border: none; padding: 0; max-width: 100%; }
            .no-print { display: none; }
        }

        /* KHQR Modal - Sabay Flex Style */
        .khqr-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.95);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(10px);
        }
        .khqr-card-container {
            width: 100%;
            max-width: 400px;
            background: #111827;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            border: 1px solid #374151;
        }
        .khqr-modal-header {
            background: #4a0404;
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .khqr-member-of { display: flex; align-items: center; gap: 10px; }
        .khqr-member-of span { font-size: 12px; font-weight: 600; color: #94a3b8; }
        .khqr-member-tag { background: #991b1b; color: white; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 800; letter-spacing: 0.05em; }
        .khqr-timer { display: flex; align-items: center; gap: 8px; font-size: 18px; font-weight: 700; }
        .timer-icon { width: 20px; height: 20px; border: 3px solid rgba(255,255,255,0.1); border-top-color: #ef4444; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .khqr-modal-body { padding: 30px; text-align: center; }
        .khqr-instruction { color: #f8fafc; font-size: 18px; font-weight: 600; margin-bottom: 25px; }
        .khqr-main-card { background: white; border-radius: 20px; overflow: hidden; margin-bottom: 25px; color: #111827; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        .khqr-card-red-top { background: #d42027; height: 40px; display: flex; align-items: center; justify-content: center; }
        .khqr-card-red-top img { height: 20px; }
        .khqr-card-content { padding: 20px; border-bottom: 2px dashed #e2e8f0; }
        .khqr-merchant-name { font-size: 14px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 5px; }
        .khqr-amount-riel { font-size: 32px; font-weight: 800; color: #0f172a; }
        .khqr-qr-box { padding: 25px; position: relative; min-height: 240px; display: flex; align-items: center; justify-content: center; }
        #khqr-image-v2 { width: 100%; max-width: 220px; height: auto; }
        .khqr-transaction-id { font-size: 12px; color: #94a3b8; font-weight: 500; margin-top: 15px; }

        .khqr-modal-footer { padding: 0 30px 30px; display: flex; flex-direction: column; gap: 12px; }
        .btn-paid { background: #991b1b; color: white; padding: 14px; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 16px; transition: background 0.2s; }
        .btn-paid:hover { background: #7f1d1d; }
        .btn-download-qr { background: #1f2937; color: white; padding: 14px; border-radius: 12px; font-weight: 700; border: 1px solid #374151; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 16px; transition: background 0.2s; }
        .btn-download-qr:hover { background: #374151; }
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
            <div class="detail-row">
                <span class="detail-label">ឈ្មោះទិញ ៖</span>
                <span class="detail-value">{{ $order->customer_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">ទៅគណនី ៖</span>
                <span class="detail-value">{{ $appSettings['restaurant_name'] ?? 'Resto Delights' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">កាលបរិច្ឆេទ ៖</span>
                <span class="detail-value">{{ $order->created_at->format('d M Y h:i A') }}</span>
            </div>
            <div class="detail-row" style="margin-top: 10px; border-top: 1px dashed rgba(255,255,255,0.2); padding-top: 20px; border-bottom: none;">
                <span class="detail-label">តម្លៃ ៖</span>
                <span class="detail-amount">៛{{ number_format($order->total_price * 4100, 0) }}</span>
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
                    <td><div style="font-weight: 600; color: #0f172a;">{{ $item->menu->name ?? 'Deleted Item' }}</div></td>
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

        @if(!empty($appSettings['bank_name']))
        <div class="payment-section">
            <div class="qr-card">
                <img src="{{ !empty($appSettings['payment_qr_path']) ? $appSettings['payment_qr_path'] : 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/KHQR_logo.svg/512px-KHQR_logo.svg.png' }}" alt="Payment QR Code">
            </div>
            <div class="payment-info">
                <h3>Payment Confirmation</h3>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 16px;">This order has been successfully paid via KHQR.</p>
                
                <div class="bank-details" style="margin-top: 20px;">
                    <p>Bank: <strong>{{ $appSettings['bank_name'] }}</strong></p>
                    <p>Account Name: <strong>{{ $appSettings['account_name'] ?? 'N/A' }}</strong></p>
                    <p>Account Number: <strong>{{ $appSettings['account_number'] ?? 'N/A' }}</strong></p>
                </div>
            </div>
        </div>
        @endif

        <div class="footer">
            <p>Thank you for dining with us!</p>
            <p style="font-size: 12px; margin-top: 8px;">© 2026 {{ $appSettings['restaurant_name'] ?? 'Resto Delights' }}. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
