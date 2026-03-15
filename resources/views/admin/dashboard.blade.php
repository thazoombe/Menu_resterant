<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ $appSettings['restaurant_name'] ?? 'Resto' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; display: flex; }
        
        .sidebar { width: 260px; background: #0f172a; color: white; height: 100vh; padding: 2rem; box-sizing: border-box; position: sticky; top: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 100; overflow-x: hidden; }
        .sidebar.collapsed { width: 0; padding: 2rem 0; transform: translateX(-260px); }
        .sidebar nav a { display: block; color: #94a3b8; text-decoration: none; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem; transition: all 0.2s; font-weight: 600; white-space: nowrap; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #1e293b; color: white; }
        
        .main { flex: 1; padding: 3rem; box-sizing: border-box; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; }
        .main.expanded { padding-left: 3rem; }
        
        .toggle-btn { background: white; border: 1px solid #e2e8f0; width: 40px; height: 40px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #0f172a; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .toggle-btn:hover { background: #f8fafc; border-color: #cbd5e1; }
        
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; gap: 1.5rem; }
        .header-left { display: flex; align-items: center; gap: 1.25rem; }
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
        <a href="/admin/categories">Categories</a>
        <a href="/admin/expenses">Expenses</a>
        <a href="/admin/reports">Reports</a>
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
            <h1>Dashboard Overview</h1>
        </div>
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
        <div class="stat-card">
            <span>Total Categories</span>
            <h3>{{ $totalCategories }}</h3>
        </div>
    </div>

    {{-- Charts --}}
    <div class="chart-grid" style="grid-template-columns: 1.5fr 1fr 1fr;">
        <div class="section">
            <h2>📈 Daily Sales — Last 30 Days</h2>
            <canvas id="dailySalesChart" height="150"></canvas>
        </div>
        <div class="section">
            <h2>🏆 Top Selling Dishes</h2>
            <canvas id="topFoodsChart" height="150"></canvas>
        </div>
        <div class="section">
            <h2>📊 Revenue by Category</h2>
            <div style="height: 150px; display: flex; align-items: center; justify-content: center;">
                <canvas id="categoryChart"></canvas>
            </div>
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
                    <th style="text-align: right;">Action</th>
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
                    <td style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                        <form action="/admin/order/status/{{ $order->id }}" method="POST">
                            @csrf
                            <select name="status" onchange="this.form.submit()" style="padding: 0.25rem; font-size: 0.75rem; border-radius: 0.4rem; border: 1px solid #e2e8f0; outline: none; background: transparent;">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </form>
                        
                        @if($order->status === 'paid' || $order->status === 'completed')
                        <form action="{{ route('admin.payment.refund', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to refund this order?')">
                            @csrf
                            <button type="submit" class="btn-status" style="color: #ef4444; border-color: #ef4444; padding: 0.25rem 0.5rem; font-size: 0.7rem;">Refund</button>
                        </form>
                        @endif

                        <button onclick="createPaymentLink({{ $order->id }})" class="btn-status" style="color: #10b981; border-color: #10b981; padding: 0.25rem 0.5rem; font-size: 0.7rem;">Link</button>

                        <a href="/order/invoice/{{ $order->id }}" target="_blank" style="color: #3b82f6; text-decoration: none; font-weight: 700; font-size: 0.8rem;">🖨️</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
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

// ── Chart 3: Revenue by Category Doughnut ────────────────────
const categorySalesData = @json($categorySales);
new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: categorySalesData.map(c => c.name),
        datasets: [{
            data: categorySalesData.map(c => parseFloat(c.revenue)),
            backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4','#f97316'],
            borderWidth: 0,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        cutout: '65%'
    }
});
</script>

@if(($appSettings['enable_translation'] ?? 'yes') === 'yes')
<div id="google_translate_element" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; background: white; padding: 10px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
      pageLanguage: 'en',
      includedLanguages: 'en,km',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE
  }, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<style>
/* Hide the google branding */
.goog-te-gadget { color: transparent !important; font-family: 'Inter', sans-serif; font-size: 0px; }
.goog-te-gadget .goog-te-combo { margin: 0; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: 'Inter', sans-serif; font-size: 0.9rem; color: #1e293b; outline: none; }
.goog-te-banner-frame.skiptranslate { display: none !important; }
body { top: 0px !important; }
</style>
@endif

<script>
// Sidebar Toggle Logic
const sidebar = document.querySelector('.sidebar');
const main = document.querySelector('.main');
const toggleBtn = document.getElementById('sidebar-toggle');

// Load state
if (localStorage.getItem('sidebar-collapsed') === 'true') {
    sidebar.classList.add('collapsed');
    main.classList.add('expanded');
}

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    main.classList.toggle('expanded');
    localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
});

async function createPaymentLink(orderId) {
    if (!confirm('Generate a payment link for this order?')) return;
    
    try {
        const response = await fetch(`/admin/payment/link/${orderId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        if (data.status == 0 && data.short_link) {
            prompt('Payment Link Generated! Copy this link:', data.short_link);
        } else {
            alert('Error: ' + (data.description || 'Failed to generate link'));
        }
    } catch (error) {
        console.error(error);
        alert('An error occurred.');
    }
}
</script>

@if(($appSettings['default_theme'] ?? 'light') === 'dark')
<style>
    /* Dark Mode Overrides for Admin Dashboard */
    body { background-color: #0f172a; color: #f8fafc; }
    .sidebar { border-right: 1px solid #334155; }
    .toggle-btn { background: #1e293b; border-color: #334155; color: white; }
    .toggle-btn:hover { background: #334155; }
    .main { background-color: #0f172a; }
    header h1 { color: #f8fafc; }
    .stat-card { background: #1e293b; border-color: #334155; }
    .stat-card span { color: #94a3b8; }
    .stat-card h3 { color: #f8fafc; }
    .stat-card.profit h3 { color: #34d399; }
    .section { background: #1e293b; border-color: #334155; }
    .section h2 { color: #f8fafc; }
    th { color: #94a3b8; border-bottom-color: #334155; }
    td { border-bottom-color: #334155; color: #cbd5e1; }
    td strong { color: #f8fafc; }
    .btn-status { background: #0f172a; border-color: #334155; color: #f8fafc; }
    .btn-status:hover { background: #334155; }
    select.btn-status { color: #f8fafc; background-color: #0f172a; border: 1px solid #334155; }
    select.btn-status option { background-color: #1e293b; color: #f8fafc; }
    #google_translate_element { background: #1e293b !important; border: 1px solid #334155; }
    .goog-te-combo { background: #0f172a !important; color: white !important; border-color: #334155 !important; }
    .category-badge { color: #f8fafc; background: #334155; }
</style>
@endif

</html>
