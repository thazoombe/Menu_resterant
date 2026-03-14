<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dish - Resto</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; display: flex; }
        
        .sidebar { width: 260px; background: #0f172a; color: white; height: 100vh; padding: 2rem; box-sizing: border-box; position: sticky; top: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 100; overflow-x: hidden; }
        .sidebar.collapsed { width: 0; padding: 2rem 0; transform: translateX(-260px); }
        .sidebar nav a { display: block; color: #94a3b8; text-decoration: none; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem; transition: all 0.2s; font-weight: 600; white-space: nowrap; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #1e293b; color: white; }
        .sidebar nav a.active { background: #1e293b; color: white; }
        
        .main { flex: 1; padding: 3rem; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; }
        .main.expanded { padding-left: 3rem; }
        
        .toggle-btn { background: white; border: 1px solid #e2e8f0; width: 40px; height: 40px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #0f172a; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .toggle-btn:hover { background: #f8fafc; border-color: #cbd5e1; }
        
        .header-left { display: flex; align-items: center; gap: 1.25rem; margin-bottom: 2rem; width: 100%; max-width: 600px; }
        
        .card { background: white; padding: 3rem; border-radius: 1.5rem; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); width: 100%; max-width: 550px; border: 1px solid #e2e8f0; }
        h1 { color: #0f172a; font-weight: 800; margin-bottom: 2rem; font-size: 1.875rem; text-align: center; }
        
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-weight: 700; margin-bottom: 0.5rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        input, select { width: 100%; padding: 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.2s; box-sizing: border-box; outline: none; background: #f8fafc; }
        input:focus, select:focus { border-color: #3b82f6; background: white; box-shadow: 0 0 0 4px rgb(59 130 246 / 0.1); }
        
        .btn-update { display: block; width: 100%; padding: 1rem; background: #f59e0b; color: white; border: none; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; transition: background 0.2s; margin-top: 2rem; }
        .btn-update:hover { background: #d97706; }
        
        .back-link { display: block; text-align: center; margin-top: 1.5rem; color: #94a3b8; text-decoration: none; font-size: 0.875rem; font-weight: 600; }
        .back-link:hover { color: #0f172a; }
        .error-msg { color: #ef4444; font-size: 0.75rem; font-weight: 600; margin-top: 0.4rem; display: block; }

        @if(($appSettings['default_theme'] ?? 'light') === 'dark')
        body { background-color: #0f172a; color: #f8fafc; }
        .sidebar { border-right: 1px solid #334155; }
        .main { background-color: #0f172a; }
        .card { background: #1e293b; border-color: #334155; }
        h1 { color: #f8fafc; }
        label { color: #94a3b8; }
        input, select { background: #0f172a; border-color: #334155; color: white; }
        .btn-update { background: #f59e0b; }
        .back-link { color: #64748b; }
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
        <a href="/admin/menu" class="active">Menu Items</a>
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
    <div class="header-left">
        <button class="toggle-btn" id="sidebar-toggle" title="Toggle Sidebar">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 style="margin: 0; font-size: 1.875rem; font-weight: 800; color: #0f172a; flex: 1; text-align: center; margin-right: 40px;">Edit Dish Details</h1>
    </div>
    <div class="card">

        @if($menu->images->count() > 0)
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase;">Existing Additional Photos</label>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    @foreach($menu->images as $photo)
                        <div style="position: relative; display: inline-block;">
                            <img src="{{ $photo->image_path }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                            <form action="/admin/menu/delete-photo/{{ $photo->id }}" method="POST" style="position: absolute; top: -10px; right: -10px;">
                                @csrf
                                <button type="submit" style="background: #ef4444; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">&times;</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="/admin/menu/update/{{ $menu->id }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Food Name</label>
                <input type="text" name="name" value="{{ old('name', $menu->name) }}" required autofocus>
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" value="{{ old('description', $menu->description) }}" required>
                @error('description') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Price ($)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $menu->price) }}" required>
                @error('price') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label>Discount Type (Optional)</label>
                    <select name="discount_type" id="discount_type" onchange="toggleDiscountValue()">
                        <option value="">None</option>
                        <option value="fixed" {{ old('discount_type', $menu->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                        <option value="percent" {{ old('discount_type', $menu->discount_type) == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                    </select>
                    @error('discount_type') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label>Discount Value</label>
                    <input type="number" step="0.01" name="discount_value" id="discount_value" value="{{ old('discount_value', $menu->discount_value) }}" placeholder="0.00" {{ old('discount_type', $menu->discount_type) ? '' : 'disabled' }}>
                    @error('discount_value') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Dish Image</label>
                @if($menu->image_path)
                    <img src="{{ $menu->image_path }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 0.5rem; margin-bottom: 0.5rem; display: block; border: 1px solid #e2e8f0;">
                @endif
                <input type="file" name="image" accept="image/*">
                @error('image') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Add More Photos</label>
                <input type="file" name="photos[]" accept="image/*" multiple>
                @error('photos.*') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="display: flex; gap: 1rem; flex-wrap: wrap; background: #f8fafc; padding: 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                <label style="width: 100%; margin-bottom: 0.5rem;">Marketing Badges</label>
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; text-transform: none; color: #1e293b; cursor: pointer;">
                    <input type="checkbox" name="is_new" value="1" style="width: auto;" {{ $menu->is_new ? 'checked' : '' }}> New
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; text-transform: none; color: #1e293b; cursor: pointer;">
                    <input type="checkbox" name="is_popular" value="1" style="width: auto;" {{ $menu->is_popular ? 'checked' : '' }}> Popular
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; text-transform: none; color: #1e293b; cursor: pointer;">
                    <input type="checkbox" name="is_promotion" value="1" style="width: auto;" {{ $menu->is_promotion ? 'checked' : '' }}> Promotion
                </label>
            </div>

            <button type="submit" class="btn-update">Update Dish</button>
        </form>

        <a href="/admin/menu" class="back-link">← Cancel and Go Back</a>
    </div>
</div>

<script>
    function toggleDiscountValue() {
        const type = document.getElementById('discount_type').value;
        const valueInput = document.getElementById('discount_value');
        if (type === '') {
            valueInput.value = '';
            valueInput.disabled = true;
        } else {
            valueInput.disabled = false;
        }
    }
</script>

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