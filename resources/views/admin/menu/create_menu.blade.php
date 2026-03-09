<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Dish - Resto</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; display: flex; }
        
        .sidebar { width: 260px; background: #0f172a; color: white; height: 100vh; padding: 2rem; box-sizing: border-box; position: sticky; top: 0; }
        .sidebar h2 { font-size: 1.25rem; font-weight: 800; margin-bottom: 2.5rem; color: #3b82f6; }
        .sidebar nav a { display: block; color: #94a3b8; text-decoration: none; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem; transition: all 0.2s; font-weight: 600; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #1e293b; color: white; }
        
        .main { flex: 1; padding: 3rem; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; }
        
        .card { background: white; padding: 3rem; border-radius: 1.5rem; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); width: 100%; max-width: 550px; border: 1px solid #e2e8f0; }
        h1 { color: #0f172a; font-weight: 800; margin-bottom: 2rem; font-size: 1.875rem; text-align: center; }
        
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-weight: 700; margin-bottom: 0.5rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        input, select { width: 100%; padding: 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.2s; box-sizing: border-box; outline: none; background: #f8fafc; }
        input:focus, select:focus { border-color: #3b82f6; background: white; box-shadow: 0 0 0 4px rgb(59 130 246 / 0.1); }
        
        .btn-save { display: block; width: 100%; padding: 1rem; background: #3b82f6; color: white; border: none; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; transition: background 0.2s; margin-top: 2rem; }
        .btn-save:hover { background: #2563eb; }
        
        .back-link { display: block; text-align: center; margin-top: 1.5rem; color: #94a3b8; text-decoration: none; font-size: 0.875rem; font-weight: 600; }
        .back-link:hover { color: #0f172a; }
        .error-msg { color: #ef4444; font-size: 0.75rem; font-weight: 600; margin-top: 0.4rem; display: block; }
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
    <div class="card">
        <h1>Add New Dish</h1>

        <form action="/admin/menu/store" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Food Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Margherita Pizza" required autofocus>
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" value="{{ old('description') }}" placeholder="e.g. Classic cheese and tomato" required>
                @error('description') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Price ($)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" placeholder="0.00" required>
                @error('price') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Dish Image</label>
                <input type="file" name="image" accept="image/*">
                @error('image') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="display: flex; gap: 1rem; flex-wrap: wrap; background: #f8fafc; padding: 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                <label style="width: 100%; margin-bottom: 0.5rem;">Marketing Badges</label>
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; text-transform: none; color: #1e293b; cursor: pointer;">
                    <input type="checkbox" name="is_new" value="1" style="width: auto;"> New
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; text-transform: none; color: #1e293b; cursor: pointer;">
                    <input type="checkbox" name="is_popular" value="1" style="width: auto;"> Popular
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; text-transform: none; color: #1e293b; cursor: pointer;">
                    <input type="checkbox" name="is_promotion" value="1" style="width: auto;"> Promotion
                </label>
            </div>

            <button type="submit" class="btn-save">Save to Menu</button>
        </form>

        <a href="/admin/menu" class="back-link">← Cancel and Go Back</a>
    </div>
</div>

</body>
</html>