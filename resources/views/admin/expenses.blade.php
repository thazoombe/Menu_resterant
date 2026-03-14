<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Management</title>
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

        .form-section { background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); border: 1px solid #e2e8f0; margin-bottom: 3rem; }
        .form-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; align-items: end; }
        .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .form-group label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .form-group input, .form-group select { padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none; }
        .btn-save { background: #3b82f6; color: white; border: none; padding: 0.75rem; border-radius: 0.5rem; font-weight: 700; cursor: pointer; }

        .section { background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); border: 1px solid #e2e8f0; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 1rem; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 0.75rem; text-transform: uppercase; }
        td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9375rem; }
        .btn-delete { color: #ef4444; background: none; border: none; font-weight: 700; cursor: pointer; }

        @if(($appSettings['default_theme'] ?? 'light') === 'dark')
        body { background-color: #0f172a; color: #f8fafc; }
        .sidebar { border-right: 1px solid #334155; }
        .main { background-color: #0f172a; }
        header h1 { color: #f8fafc; }
        .section, .form-section { background: #1e293b; border-color: #334155; }
        .form-group label { color: #94a3b8; }
        .form-group input, .form-group select { background: #0f172a; border-color: #334155; color: white; }
        th { color: #94a3b8; border-bottom-color: #334155; }
        td { border-bottom-color: #334155; color: #cbd5e1; }
        .btn-save { background: #3b82f6; }
        .toggle-btn { background: #1e293b; border-color: #334155; color: white; }
        .toggle-btn:hover { background: #334155; }
        @endif
    </style>
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
        <a href="/admin/dashboard">Dashboard</a>
        <a href="/admin/menu">Menu Items</a>
        <a href="/admin/categories">Categories</a>
        <a href="/admin/expenses" class="active">Expenses</a>
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
            <h1>Expense Tracker</h1>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <a href="/admin/export/expenses" class="btn-save" style="background: white; border: 1px solid #ef4444; color: #ef4444; padding: 0.5rem 0.8rem; font-size: 0.75rem; text-decoration: none;">CSV Export</a>
            <a href="/admin/export/expenses/print" target="_blank" class="btn-save" style="background: white; border: 1px solid #64748b; color: #64748b; padding: 0.5rem 0.8rem; font-size: 0.75rem; text-decoration: none;">PDF Report</a>
        </div>
    </header>

    <div class="form-section">
        <form action="/admin/expenses/store" method="POST" class="form-grid">
            @csrf
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" placeholder="e.g. Fresh Tomatoes" required>
            </div>
            <div class="form-group">
                <label>Amount ($)</label>
                <input type="number" step="0.01" name="amount" placeholder="0.00" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="Ingredients">Ingredients</option>
                    <option value="Utilities">Utilities</option>
                    <option value="Rent">Rent</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" required>
            </div>
            <button type="submit" class="btn-save">Record Expense</button>
        </form>
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->date }}</td>
                    <td>{{ $expense->description }}</td>
                    <td><span style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem;">{{ $expense->category }}</span></td>
                    <td><strong>${{ number_format($expense->amount, 2) }}</strong></td>
                    <td>
                        <form action="/admin/expenses/delete/{{ $expense->id }}" method="POST" onsubmit="return confirm('Delete this expense?');">
                            @csrf
                            <button type="submit" class="btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

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
