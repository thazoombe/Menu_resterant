<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu Catalog - {{ date('Y-m-d') }}</title>
    <style>
        body { font-family: sans-serif; padding: 40px; color: #333; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        h1 { margin: 0; font-size: 24px; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f8fafc; text-align: left; padding: 12px; border: 1px solid #e2e8f0; font-size: 11px; text-transform: uppercase; }
        td { padding: 12px; border: 1px solid #e2e8f0; font-size: 13px; }
        .price { font-weight: bold; }
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
        <h1>RESTAURANT MENU CATALOG</h1>
        <p>Generated on: {{ date('F j, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Dish Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
            <tr>
                <td>#{{ $menu->id }}</td>
                <td style="font-weight: 600;">{{ $menu->name }}</td>
                <td>{{ $menu->category->name ?? 'N/A' }}</td>
                <td>{{ $menu->description }}</td>
                <td class="price">${{ number_format($menu->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
