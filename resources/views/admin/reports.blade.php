<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailed Reports - {{ $appSettings['restaurant_name'] ?? 'Resto' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; display: flex; }
        
        .sidebar { width: 260px; background: #0f172a; color: white; height: 100vh; padding: 2rem; box-sizing: border-box; position: sticky; top: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 100; overflow-x: hidden; }
        .sidebar.collapsed { width: 0; padding: 2rem 0; transform: translateX(-260px); }
        .sidebar nav a { display: block; color: #94a3b8; text-decoration: none; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem; transition: all 0.2s; font-weight: 600; white-space: nowrap; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #1e293b; color: white; }
        .sidebar nav a.active { background: #1e293b; color: white; }
        
        .main { flex: 1; padding: 3rem; box-sizing: border-box; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; }
        .main.expanded { padding-left: 3rem; }
        
        .toggle-btn { background: white; border: 1px solid #e2e8f0; width: 40px; height: 40px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #0f172a; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .toggle-btn:hover { background: #f8fafc; border-color: #cbd5e1; }
        
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; gap: 1.5rem; }
        .header-left { display: flex; align-items: center; gap: 1.25rem; }
        header h1 { margin: 0; font-size: 1.875rem; font-weight: 800; color: #0f172a; }

        .filter-card { background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); border: 1px solid #e2e8f0; margin-bottom: 2rem; }
        .filter-form { display: flex; gap: 1rem; align-items: flex-end; }
        .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .form-group label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .form-group input { padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none; }
        .btn-filter { background: #3b82f6; color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 0.5rem; font-weight: 700; cursor: pointer; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 3rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 14px 20px -5px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .stat-card span { display: block; color: #64748b; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; }
        .stat-card h3 { margin: 0; font-size: 1.75rem; font-weight: 800; color: #0f172a; }
        .stat-card.profit h3 { color: #10b981; }
        .stat-card.expense h3 { color: #ef4444; }

        .report-section { background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); border: 1px solid #e2e8f0; margin-bottom: 2rem; }
        .report-section h2 { margin-top: 0; font-size: 1.1rem; font-weight: 800; margin-bottom: 1.5rem; color: #0f172a; display: flex; align-items: center; gap: 0.5rem; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 1rem; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.875rem; }
        tr:last-child td { border-bottom: none; }
        
        .badge { padding: 0.25rem 0.6rem; border-radius: 2rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .badge-income { background: #dcfce7; color: #166534; }
        .badge-expense { background: #fee2e2; color: #991b1b; }

        @media print {
            .sidebar, .filter-card, .btn-print { display: none !important; }
            .main { padding: 0; }
            body { background: white; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:2.5rem;">
        @if(Auth::user()->profile_photo_path)
            <img src="{{ Auth::user()->profile_photo_path }}" alt="avatar" style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:2px solid #3b82f6;">
        @else
            <span style="width:42px;height:42px;border-radius:50%;background:#3b82f6;display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:800;color:white;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </span>
        @endif
        <div>
            <div style="font-size:0.75rem;color:#3b82f6;font-weight:700;text-transform:uppercase;">Admin</div>
            <div style="font-weight:700;color:white;font-size:0.95rem;">{{ Auth::user()->name }}</div>
        </div>
    </div>
    <nav>
        <a href="/admin/dashboard">Dashboard</a>
        <a href="/admin/menu">Menu Items</a>
        <a href="/admin/categories">Categories</a>
        <a href="/admin/expenses">Expenses</a>
        <a href="/admin/reports" class="active">Reports</a>
        <a href="/admin/settings">Settings</a>
        <a href="/" style="color: #10b981; margin-top: 1rem;" target="_blank">🏠 View Homepage</a>
        <form action="/admin/logout" method="POST" style="margin-top: 0.5rem;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #ef4444; font-weight: 700; cursor: pointer; padding: 0.75rem 1rem; width: 100%; text-align: left; font-family: inherit; font-size: 1rem;">Sign Out</button>
        </form>
    </nav>
</div>

<div class="main">
    <header>
        <div class="header-left">
            <button class="toggle-btn" id="sidebar-toggle" title="Toggle Sidebar">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1>Detailed Reports</h1>
        </div>
        <button onclick="window.print()" class="btn-filter" style="background: #0f172a;">🖨️ Print Report</button>
    </header>

    <div class="filter-card">
        <form action="/admin/reports" method="GET" class="filter-form">
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}">
            </div>
            <button type="submit" class="btn-filter">Generate Report</button>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span>Total Revenue</span>
            <h3>${{ number_format($totalSales, 2) }}</h3>
        </div>
        <div class="stat-card expense">
            <span>Total Expenses</span>
            <h3>${{ number_format($totalExpenses, 2) }}</h3>
        </div>
        <div class="stat-card profit">
            <span>Net Profit</span>
            <h3>${{ number_format($netProfit, 2) }}</h3>
        </div>
    </div>

    <div class="stats-grid" style="grid-template-columns: 2fr 1fr;">
        <div class="report-section" style="margin-bottom: 0;">
            <h2>💰 Sales History ({{ count($orders) }} Orders)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="report-section" style="margin-bottom: 0;">
            <h2>📊 Revenue by Category</h2>
            <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
                @if($categorySales->count() > 0)
                    <canvas id="categoryChart"></canvas>
                @else
                    <p style="color: #94a3b8; font-size: 0.9rem;">No data available for this range.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="report-section" style="margin-top: 2rem;">
        <h2>📉 Expense Details ({{ count($expenses) }} Records)</h2>
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
                @foreach($expenses as $expense)
                <tr>
                    <td>{{ date('M d, Y', strtotime($expense->date)) }}</td>
                    <td>{{ $expense->description }}</td>
                    <td><span class="badge" style="background: #f1f5f9; color: #475569;">{{ $expense->category }}</span></td>
                    <td style="color: #ef4444;"><strong>-${{ number_format($expense->amount, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if(($appSettings['default_theme'] ?? 'light') === 'dark')
<style>
    body { background-color: #0f172a; color: #f8fafc; }
    .sidebar { border-right: 1px solid #334155; }
    .main { background-color: #0f172a; }
    header h1 { color: #f8fafc; }
    .filter-card, .stat-card, .report-section { background: #1e293b; border-color: #334155; }
    .stat-card span, .form-group label { color: #94a3b8; }
    .stat-card h3, .report-section h2 { color: #f8fafc; }
    th { color: #94a3b8; border-bottom-color: #334155; }
    td { border-bottom-color: #334155; color: #cbd5e1; }
    .form-group input { background: #0f172a; border-color: #334155; color: white; }
    .stat-card.profit h3 { color: #34d399; }
</style>
@endif

@if($categorySales->count() > 0)
<script>
    const ctx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = {
        labels: {!! json_encode($categorySales->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($categorySales->pluck('revenue')) !!},
            backgroundColor: [
                '#3b82f6', '#10b981', '#f59e0b', '#ef4444', 
                '#8b5cf6', '#ec4899', '#06b6d4', '#f97316'
            ],
            borderWidth: 0,
            hoverOffset: 10
        }]
    };

    new Chart(ctx, {
        type: 'doughnut',
        data: categoryData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        color: '{{ (($appSettings["default_theme"] ?? "light") === "dark") ? "#94a3b8" : "#64748b" }}',
                        font: {
                            family: "'Inter', sans-serif",
                            weight: '600'
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endif

<script>
// Sidebar Toggle Logic
const sidebar = document.querySelector('.sidebar');
const main = document.querySelector('.main');
const toggleBtn = document.getElementById('sidebar-toggle');

if (localStorage.getItem('sidebar-collapsed') === 'true') {
    sidebar.classList.add('collapsed');
    main.classList.add('expanded');
}

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    main.classList.toggle('expanded');
    localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
});
</script>

</body>
</html>
