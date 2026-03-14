<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Admin Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        :root {
            --primary: #3b82f6;
            --secondary: #1e293b;
            --success: #10b981;
            --danger: #ef4444;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --text-dark: #1e293b;
            --text-gray: #64748b;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background: var(--bg-light);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles (Consistent with other admin pages) */
        .sidebar { width: 260px; background: #0f172a; color: white; height: 100vh; padding: 2rem; box-sizing: border-box; position: sticky; top: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 100; overflow-x: hidden; }
        .sidebar.collapsed { width: 0; padding: 2rem 0; transform: translateX(-260px); }
        .sidebar nav a { display: block; color: #94a3b8; text-decoration: none; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem; transition: all 0.2s; font-weight: 600; white-space: nowrap; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #1e293b; color: white; }
        .sidebar nav a.active { background: #1e293b; color: white; }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
        }
        
        .toggle-btn { background: white; border: 1px solid #e2e8f0; width: 40px; height: 40px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #0f172a; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .toggle-btn:hover { background: #f8fafc; border-color: #cbd5e1; }
        
        .header-left { display: flex; align-items: center; gap: 1.25rem; margin-bottom: 2rem; }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
        }

        /* Category Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            align-items: start;
        }

        .section {
            background: var(--white);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
        }

        .section h2 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-gray);
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid var(--border);
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            width: 100%;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 1rem;
            color: var(--text-gray);
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 2px solid var(--bg-light);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--bg-light);
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .btn-edit, .btn-delete {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 6px;
            font-weight: 700;
            text-decoration: none;
        }

        .btn-edit {
            background: #eff6ff;
            color: var(--primary);
        }

        .btn-delete {
            background: #fef2f2;
            color: var(--danger);
        }

        .count-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            background: var(--bg-light);
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-gray);
        }

        /* Alert Styles */
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #10b981; }
        .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #ef4444; }

        @if(($appSettings['default_theme'] ?? 'light') === 'dark')
        :root {
            --bg-light: #0f172a;
            --white: #1e293b;
            --text-dark: #f8fafc;
            --text-gray: #94a3b8;
            --border: #334155;
        }
        body { background: #0f172a; color: #f8fafc; }
        .section { background: #1e293b; border-color: #334155; }
        .form-group input { background: #0f172a; color: white; border-color: #334155; }
        th { border-bottom-color: #334155; }
        td { border-bottom-color: #334155; color: #f8fafc; }
        .btn-edit { background: rgba(59, 130, 246, 0.1); }
        .btn-delete { background: rgba(239, 68, 68, 0.1); }
        .count-badge { background: #0f172a; color: #94a3b8; }
        #google_translate_element { background: #1e293b !important; border: 1px solid #334155 !important; }
        .goog-te-combo { background: #0f172a !important; color: white !important; border-color: #334155 !important; }
        
        .sidebar { border-right: 1px solid #334155; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #1e293b; color: white; }
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
        <a href="/admin/categories" class="active">Categories</a>
        <a href="/admin/expenses">Expenses</a>
        <a href="/admin/reports">Reports</a>
        <a href="/admin/settings">Settings</a>
    </nav>
</div>

<div class="main-content">
    <div class="header-left">
        <button class="toggle-btn" id="sidebar-toggle" title="Toggle Sidebar">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 style="margin: 0; font-size: 1.875rem; font-weight: 800; color: #0f172a;">Categories</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="content-grid">
        <div class="section">
            <h2>Add New Category</h2>
            <form action="/admin/categories" method="POST">
                @csrf
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="name" placeholder="e.g. Lunch, Drinks, Snacks" required>
                    @error('name') <p style="color:var(--danger); font-size:0.8rem; margin:0.5rem 0;">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn btn-primary">Create Category</button>
            </form>
        </div>

        <div class="section">
            <h2>Manage Categories</h2>
            <table>
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Food Items</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr id="row-{{ $category->id }}">
                        <!-- Display Mode -->
                        <td class="display-mode">
                            <span style="font-weight: 600;">{{ $category->name }}</span>
                        </td>
                        <td class="display-mode">
                            <span class="count-badge">{{ $category->menus_count }} items</span>
                        </td>
                        <td class="display-mode" style="text-align: right;">
                            <div class="actions">
                                <button class="btn-edit" onclick="toggleEdit({{ $category->id }}, true)" style="border:none; cursor:pointer;">Edit</button>
                                <form action="/admin/categories/delete/{{ $category->id }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this category? This will fail if there are food items in it.')">
                                    @csrf
                                    <button type="submit" class="btn-delete" style="border:none; cursor:pointer;">Delete</button>
                                </form>
                            </div>
                        </td>

                        <!-- Edit Mode -->
                        <td class="edit-mode" colspan="2" style="display: none;">
                            <form action="/admin/categories/{{ $category->id }}" method="POST" style="display: flex; gap: 1rem; align-items: center; margin: 0;">
                                @csrf
                                <input type="text" name="name" value="{{ $category->name }}" required style="flex: 1; padding: 0.4rem 0.8rem; border-radius: 6px; border: 1px solid var(--border); font-size: 0.95rem;">
                                <button type="submit" class="btn-edit" style="background: var(--success); color: white; border: none; cursor: pointer;">Save</button>
                                <button type="button" class="btn-delete" onclick="toggleEdit({{ $category->id }}, false)" style="background: var(--bg-light); color: var(--text-gray); border: none; cursor: pointer;">Cancel</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($categories->isEmpty())
                    <tr>
                        <td colspan="3" style="text-align:center; padding: 3rem 0; color: var(--text-gray);">No categories found. Start by adding one.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Sidebar Toggle Logic
const sidebar = document.querySelector('.sidebar');
const toggleBtn = document.getElementById('sidebar-toggle');

if (localStorage.getItem('sidebar-collapsed') === 'true') {
    sidebar.classList.add('collapsed');
}

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
});

    function toggleEdit(id, show) {
        const row = document.getElementById('row-' + id);
        const displayCells = row.querySelectorAll('.display-mode');
        const editCell = row.querySelector('.edit-mode');

        if (show) {
            displayCells.forEach(cell => cell.style.display = 'none');
            editCell.style.display = 'table-cell';
        } else {
            displayCells.forEach(cell => cell.style.display = 'table-cell');
            editCell.style.display = 'none';
        }
    }
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
.goog-te-gadget { color: transparent !important; font-size: 0px; }
.goog-te-gadget .goog-te-combo { margin: 0; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: 'Inter', sans-serif; font-size: 0.9rem; outline: none; }
</style>
@endif

</body>
</html>
