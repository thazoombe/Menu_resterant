<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; display: flex; }
        
        .sidebar { width: 260px; background: #0f172a; color: white; height: 100vh; padding: 2rem; box-sizing: border-box; position: sticky; top: 0; }
        .sidebar h2 { font-size: 1.25rem; font-weight: 800; margin-bottom: 2.5rem; color: #3b82f6; }
        .sidebar nav a { display: block; color: #94a3b8; text-decoration: none; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem; transition: all 0.2s; font-weight: 600; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #1e293b; color: white; }
        
        .main { flex: 1; padding: 3rem; box-sizing: border-box; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; }
        header h1 { margin: 0; font-size: 1.875rem; font-weight: 800; color: #0f172a; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 3rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); border: 1px solid #e2e8f0; }
        .stat-card span { display: block; color: #64748b; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; }
        .stat-card h3 { margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; }
        .stat-card.profit h3 { color: #10b981; }
        .stat-card.expense h3 { color: #ef4444; }

        .section { background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); border: 1px solid #e2e8f0; }
        .section h2 { margin-top: 0; font-size: 1.25rem; font-weight: 800; margin-bottom: 1.5rem; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 1rem; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9375rem; vertical-align: middle; }
        
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        .btn-status { background: #f1f5f9; border: 1px solid #e2e8f0; padding: 0.4rem 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; cursor: pointer; text-decoration: none; color: #1e293b; transition: all 0.2s; }
        .btn-status:hover { background: #e2e8f0; }

        .order-items { font-size: 0.825rem; color: #64748b; margin-top: 0.25rem; }
        .chart-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
        @media(max-width: 900px) { .chart-grid { grid-template-columns: 1fr; } }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>

<div class="sidebar">
    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:2.5rem;">
        @if(Auth::user()->profile_photo_path)
            <img src="{{ Auth::user()->profile_photo_path }}" alt="avatar"
                 style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:2px solid #3b82f6;flex-shrink:0;">
        @else
            <span style="width:42px;height:42px;border-radius:50%;background:#3b82f6;display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:800;color:white;flex-shrink:0;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </span>
        @endif
        <div>
            <div style="font-size:0.75rem;color:#3b82f6;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Admin</div>
            <div style="font-weight:700;color:white;font-size:0.95rem;">{{ Auth::user()->name }}</div>
        </div>
    </div>
    <nav>
        <a href="/admin/dashboard" class="active">Dashboard</a>
        <a href="/admin/menu">Menu Items</a>
        <a href="/admin/expenses">Expenses</a>
        <a href="#">Settings</a>
        <a href="/" style="color: #10b981; margin-top: 1rem;" target="_blank">🏠 View Homepage</a>
        <form action="/admin/logout" method="POST" style="margin-top: 0.5rem;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #ef4444; font-weight: 700; cursor: pointer; padding: 0.75rem 1rem; width: 100%; text-align: left; font-family: inherit; font-size: 1rem;">Sign Out</button>
        </form>
    </nav>
</div>

<div class="main">
    <header>
        <h1>Dashboard Overview</h1>
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <a href="/admin/export/orders" class="btn-status" style="text-decoration: none; padding: 0.5rem 0.8rem; background: white; border: 1px solid #3b82f6; color: #3b82f6; font-size: 0.75rem; font-weight: 700;">CSV Orders</a>
            <a href="/admin/export/orders/print" target="_blank" class="btn-status" style="text-decoration: none; padding: 0.5rem 0.8rem; background: white; border: 1px solid #f59e0b; color: #f59e0b; font-size: 0.75rem; font-weight: 700;">PDF Orders</a>
            <a href="/admin/export/expenses" class="btn-status" style="text-decoration: none; padding: 0.5rem 0.8rem; background: white; border: 1px solid #ef4444; color: #ef4444; font-size: 0.75rem; font-weight: 700;">CSV Expenses</a>
            <a href="/admin/export/expenses/print" target="_blank" class="btn-status" style="text-decoration: none; padding: 0.5rem 0.8rem; background: white; border: 1px solid #64748b; color: #64748b; font-size: 0.75rem; font-weight: 700;">PDF Expenses</a>
            <a href="/admin/menu/create" class="btn-status" style="text-decoration: none; padding: 0.5rem 0.8rem; background: #0f172a; color: white; border: none; font-size: 0.75rem; font-weight: 700;">+ Add Dish</a>
        </div>
    </header>

    <div class="stats-grid">
        <div class="stat-card">
            <span>Sales Today</span>
            <h3>${{ number_format($salesToday, 2) }}</h3>
        </div>
        <div class="stat-card">
            <span>Monthly Revenue</span>
            <h3>${{ number_format($monthlyRevenue, 2) }}</h3>
        </div>
        <div class="stat-card expense">
            <span>Monthly Expenses</span>
            <h3>${{ number_format($monthlyExpenses, 2) }}</h3>
        </div>
        <div class="stat-card profit">
            <span>Net Profit</span>
            <h3>${{ number_format($netProfit, 2) }}</h3>
        </div>
    </div>

    {{-- Charts --}}
    <div class="chart-grid">
        <div class="section">
            <h2>📈 Daily Sales — Last 30 Days</h2>
            <canvas id="dailySalesChart" height="120"></canvas>
        </div>
        <div class="section">
            <h2>🏆 Top Selling Dishes</h2>
            <canvas id="topFoodsChart" height="120"></canvas>
        </div>
    </div>

    <div class="section">
        <h2>Recent Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>
                        <div class="order-items">
                            @foreach($order->items as $item)
                                {{ $item->quantity }}x {{ $item->menu->name ?? 'Deleted' }}@if(!$loop->last), @endif
                            @endforeach
                        </div>
                    </td>
                    <td><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                    <td>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td>
                        <form action="/admin/order/status/{{ $order->id }}" method="POST">
                            @csrf
                            <select name="status" class="btn-status" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Mark Pending</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Mark Complete</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Mark Cancel</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    <div class="section" style="margin-top: 2rem;">
        <h2>Recent Expenses</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentExpenses as $expense)
                <tr>
                    <td>{{ $expense->date }}</td>
                    <td>{{ $expense->description }}</td>
                    <td><span class="category-badge">{{ $expense->category }}</span></td>
                    <td><strong>${{ number_format($expense->amount, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</body>

<script>
// Data from PHP
const salesLabels = @json($dailySalesLabels);
const salesData   = @json($dailySalesData);
const topLabels   = @json($topFoodLabels);
const topData     = @json($topFoodData);

// ── Chart 1: Daily Sales Line Chart ──────────────────────────
new Chart(document.getElementById('dailySalesChart'), {
    type: 'line',
    data: {
        labels: salesLabels,
        datasets: [{
            label: 'Revenue ($)',
            data: salesData,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#3b82f6',
            pointRadius: 3,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' $' + ctx.parsed.y.toFixed(2)
                }
            }
        },
        scales: {
            x: {
                ticks: { maxRotation: 45, minRotation: 30, font: { size: 10 } },
                grid: { display: false }
            },
            y: {
                beginAtZero: true,
                ticks: { callback: v => '$' + v }
            }
        }
    }
});

// ── Chart 2: Top Foods Horizontal Bar Chart ───────────────────
new Chart(document.getElementById('topFoodsChart'), {
    type: 'bar',
    data: {
        labels: topLabels,
        datasets: [{
            label: 'Units Sold',
            data: topData,
            backgroundColor: [
                '#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6',
                '#ec4899','#14b8a6','#f97316','#06b6d4','#84cc16'
            ],
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            },
            y: {
                ticks: { font: { size: 11 } }
            }
        }
    }
});
</script>
</html>
