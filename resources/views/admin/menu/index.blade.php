<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management - Resto</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; display: flex; }
        
        .sidebar { width: 260px; background: #0f172a; color: white; height: 100vh; padding: 2rem; box-sizing: border-box; position: sticky; top: 0; }
        .sidebar h2 { font-size: 1.25rem; font-weight: 800; margin-bottom: 2.5rem; color: #3b82f6; }
        .sidebar nav a { display: block; color: #94a3b8; text-decoration: none; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem; transition: all 0.2s; font-weight: 600; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #1e293b; color: white; }
        
        .main { flex: 1; padding: 3rem; box-sizing: border-box; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        header h1 { margin: 0; font-size: 1.875rem; font-weight: 800; color: #0f172a; }
        
        .btn { display: inline-flex; align-items: center; padding: 0.625rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 700; transition: all 0.2s; cursor: pointer; border: none; font-size: 0.875rem; }
        .btn-primary { background: #3b82f6; color: white; box-shadow: 0 4px 6px -1px rgb(59 130 246 / 0.2); }
        .btn-primary:hover { background: #2563eb; transform: translateY(-1px); }
        
        .btn-edit { background: #fef3c7; color: #92400e; margin-right: 0.5rem; padding: 0.4rem 0.875rem; }
        .btn-edit:hover { background: #fde68a; }
        
        .btn-delete { background: #fee2e2; color: #991b1b; padding: 0.4rem 0.875rem; }
        .btn-delete:hover { background: #fecaca; }

        .section { background: white; border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); border: 1px solid #e2e8f0; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; background: #fbfcfd; }
        td { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9375rem; vertical-align: middle; }
        tr:hover td { background: #fbfcfe; }
        
        .category-badge { background: #f1f5f9; padding: 0.25rem 0.625rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; color: #64748b; }
        .price-text { font-weight: 800; color: #0f172a; }

        .success-msg { background: #dcfce7; color: #166534; padding: 1rem 1.5rem; border-bottom: 1px solid #bbf7d0; font-weight: 600; font-size: 0.875rem; }
        .actions { display: flex; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Resto Admin</h2>
    <nav>
        <a href="/admin/dashboard">Dashboard</a>
        <a href="/admin/menu" class="active">Menu Items</a>
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
        <h1>Menu Management</h1>
        <div style="display: flex; gap: 8px; align-items: center;">
            <a href="/admin/export/menu" class="btn" style="background: white; border: 1px solid #3b82f6; color: #3b82f6; padding: 0.5rem 0.8rem; font-size: 0.75rem;">CSV Export</a>
            <a href="/admin/export/menu/print" target="_blank" class="btn" style="background: white; border: 1px solid #f59e0b; color: #f59e0b; padding: 0.5rem 0.8rem; font-size: 0.75rem;">PDF Catalog</a>
            <a href="/admin/menu/create" class="btn btn-primary">+ Add New Dish</a>
        </div>
    </header>

    <div class="section">
        @if(session('success'))
            <div class="success-msg">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Dish Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th style="width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menus as $menu)
                <tr>
                    <td><span style="color: #94a3b8;">#{{ $menu->id }}</span></td>
                    <td style="font-weight: 600; color: #0f172a;">{{ $menu->name }}</td>
                    <td><span class="category-badge">{{ $menu->category->name ?? 'N/A' }}</span></td>
                    <td><span class="price-text">${{ number_format($menu->price, 2) }}</span></td>
                    <td>
                        <div class="actions">
                            <a href="/admin/menu/edit/{{ $menu->id }}" class="btn-edit btn">Edit</a>
                            <form action="/admin/menu/delete/{{ $menu->id }}" method="POST" onsubmit="return confirm('Delete this dish?');">
                                @csrf
                                <button type="submit" class="btn-delete btn">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</body>
</html>