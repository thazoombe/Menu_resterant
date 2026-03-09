<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expenses Report - {{ date('Y-m-d') }}</title>
    <style>
        body { font-family: sans-serif; padding: 40px; color: #333; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        h1 { margin: 0; font-size: 24px; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f8fafc; text-align: left; padding: 12px; border: 1px solid #e2e8f0; font-size: 12px; text-transform: uppercase; }
        td { padding: 12px; border: 1px solid #e2e8f0; font-size: 13px; }
        .total { font-weight: bold; text-align: right; margin-top: 20px; font-size: 18px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Print / Save as PDF</button>
        <button onclick="window.history.back()" style="padding: 10px 20px; cursor: pointer;">Go Back</button>
    </div>

    <div class="header">
        <h1>RESTAURANT EXPENSE REPORT</h1>
        <p>Generated on: {{ date('F j, Y, g:i a') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $totalExpenses = 0; @endphp
            @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->date }}</td>
                <td>{{ $expense->category }}</td>
                <td>{{ $expense->description }}</td>
                <td>${{ number_format($expense->amount, 2) }}</td>
            </tr>
            @php $totalExpenses += $expense->amount; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Expenses: ${{ number_format($totalExpenses, 2) }}
    </div>
</body>
</html>
