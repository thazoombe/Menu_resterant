<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Resto Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; display: flex; }

        /* Sidebar */
        .sidebar { width: 260px; background: #0f172a; color: white; height: 100vh; padding: 2rem; box-sizing: border-box; position: sticky; top: 0; overflow-y: auto; }
        .sidebar nav a { display: block; color: #94a3b8; text-decoration: none; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem; transition: all 0.2s; font-weight: 600; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #1e293b; color: white; }

        /* Main */
        .main { flex: 1; padding: 3rem; overflow-y: auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; }
        .page-header h1 { margin: 0; font-size: 1.875rem; font-weight: 800; color: #0f172a; }

        /* Cards */
        .card { background: white; border-radius: 1.25rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; margin-bottom: 1.5rem; }
        .card-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.75rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9; }
        .card-icon { width: 40px; height: 40px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
        .card-header h2 { margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; }
        .card-header p { margin: 0.2rem 0 0; font-size: 0.8rem; color: #94a3b8; }

        /* Form */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-grid .full { grid-column: 1 / -1; }
        .form-group { display: flex; flex-direction: column; }
        label { font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.4rem; }
        input[type=text], input[type=email], input[type=url], input[type=number], textarea, select {
            padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 0.625rem;
            font-size: 0.9375rem; font-family: inherit; outline: none; transition: border-color 0.2s; width: 100%;
        }
        input:focus, textarea:focus, select:focus { border-color: #3b82f6; }
        textarea { resize: vertical; }

        /* Logo upload */
        .logo-upload { display: flex; align-items: center; gap: 1.5rem; }
        .logo-preview { width: 80px; height: 80px; border-radius: 1rem; border: 2px dashed #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
        .logo-preview img { width: 100%; height: 100%; object-fit: contain; }
        .logo-placeholder { font-size: 2rem; }
        .logo-btn { background: #f1f5f9; border: 1.5px solid #e2e8f0; border-radius: 0.625rem; padding: 0.625rem 1.25rem; font-weight: 600; font-size: 0.875rem; cursor: pointer; font-family: inherit; transition: all 0.2s; }
        .logo-btn:hover { background: #e2e8f0; }
        #logo-input { display: none; }

        /* Alerts */
        .alert-success { background: #dcfce7; color: #166534; border-radius: 0.875rem; padding: 1rem 1.25rem; margin-bottom: 2rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem; }

        /* Submit button */
        .btn-save { background: #0f172a; color: white; border: none; padding: 0.875rem 2.5rem; border-radius: 0.875rem; font-weight: 700; font-size: 1rem; cursor: pointer; font-family: inherit; transition: all 0.2s; }
        .btn-save:hover { background: #3b82f6; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(59,130,246,0.3); }

        .social-icon { width: 36px; height: 36px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; color: white; flex-shrink: 0; }
        .input-with-icon { display: flex; align-items: center; gap: 0.5rem; }
        .input-with-icon input { flex: 1; }
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
            <div style="font-size:0.7rem;color:#3b82f6;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Admin</div>
            <div style="font-weight:700;color:white;font-size:0.95rem;">{{ Auth::user()->name }}</div>
        </div>
    </div>
    <nav>
        <a href="/admin/dashboard">Dashboard</a>
        <a href="/admin/menu">Menu Items</a>
        <a href="/admin/expenses">Expenses</a>
        <a href="/admin/settings" class="active">Settings</a>
        <a href="/" style="color:#10b981;margin-top:1rem;" target="_blank">🏠 View Homepage</a>
        <form action="/admin/logout" method="POST" style="margin-top:0.5rem;">
            @csrf
            <button type="submit" style="background:none;border:none;color:#ef4444;font-weight:700;cursor:pointer;padding:0.75rem 1rem;width:100%;text-align:left;font-family:inherit;font-size:1rem;">Sign Out</button>
        </form>
    </nav>
</div>

<div class="main">
    <div class="page-header">
        <div>
            <h1>⚙️ Settings</h1>
            <p style="margin:0.25rem 0 0;color:#64748b;font-size:0.9rem;">Manage your restaurant's identity and website configuration.</p>
        </div>
        <button form="settings-form" type="submit" class="btn-save">💾 Save All Changes</button>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <span style="font-size:1.25rem;">✅</span> {{ session('success') }}
        </div>
    @endif

    <form id="settings-form" action="/admin/settings/update" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 1. Restaurant Identity --}}
        <div class="card">
            <div class="card-header">
                <div class="card-icon" style="background:#eff6ff;">🏪</div>
                <div>
                    <h2>Restaurant Identity</h2>
                    <p>Name, tagline, and logo shown throughout the website</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full">
                    <label>Restaurant Name</label>
                    <input type="text" name="restaurant_name" value="{{ $settings['restaurant_name'] ?? 'Resto Delights' }}" placeholder="e.g. Resto Delights">
                </div>
                <div class="form-group full">
                    <label>Tagline / Subtitle</label>
                    <input type="text" name="tagline" value="{{ $settings['tagline'] ?? '' }}" placeholder="e.g. Hand-crafted meals delivered to your door">
                </div>
                <div class="form-group full">
                    <label>Restaurant Logo</label>
                    <div class="logo-upload">
                        <div class="logo-preview" id="logo-preview-box">
                            @if(!empty($settings['logo_path']))
                                <img src="{{ $settings['logo_path'] }}" id="logo-img" alt="Logo">
                            @else
                                <span class="logo-placeholder" id="logo-placeholder">🍽️</span>
                            @endif
                        </div>
                        <div>
                            <button type="button" class="logo-btn" onclick="document.getElementById('logo-input').click()">📁 Upload Logo</button>
                            <input type="file" id="logo-input" name="logo" accept="image/*">
                            <p style="font-size:0.75rem;color:#94a3b8;margin:0.5rem 0 0;">PNG, JPG, GIF up to 2MB. Ideal size: 300×100px</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Contact Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-icon" style="background:#f0fdf4;">📞</div>
                <div>
                    <h2>Contact Information</h2>
                    <p>Phone number, email, and physical address</p>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}" placeholder="+855 12 345 678">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ $settings['email'] ?? '' }}" placeholder="hello@restauant.com">
                </div>
                <div class="form-group full">
                    <label>Physical Address</label>
                    <textarea name="address" rows="2" placeholder="Street, City, Country">{{ $settings['address'] ?? '' }}</textarea>
                </div>
                <div class="form-group full">
                    <label>Opening Hours</label>
                    <input type="text" name="opening_hours" value="{{ $settings['opening_hours'] ?? '' }}" placeholder="Mon–Fri 10:00–22:00, Sat–Sun 11:00–23:00">
                </div>
            </div>
        </div>

        {{-- 3. Financial Settings --}}
        <div class="card">
            <div class="card-header">
                <div class="card-icon" style="background:#fff7ed;">💰</div>
                <div>
                    <h2>Financial Settings</h2>
                    <p>Currency symbol and tax rate for orders</p>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Currency Symbol</label>
                    <input type="text" name="currency" value="{{ $settings['currency'] ?? '$' }}" placeholder="$" style="max-width:120px;">
                </div>
                <div class="form-group">
                    <label>Tax Rate (%)</label>
                    <input type="number" name="tax_rate" value="{{ $settings['tax_rate'] ?? '0' }}" placeholder="0" min="0" max="100" step="0.1" style="max-width:160px;">
                </div>
            </div>
        </div>

        {{-- 4. Social Media --}}
        <div class="card">
            <div class="card-header">
                <div class="card-icon" style="background:#fdf2f8;">📱</div>
                <div>
                    <h2>Social Media</h2>
                    <p>Links shown in the footer or contact section</p>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Facebook</label>
                    <div class="input-with-icon">
                        <span class="social-icon" style="background:#1877f2;">f</span>
                        <input type="url" name="facebook" value="{{ $settings['facebook'] ?? '' }}" placeholder="https://facebook.com/yourpage">
                    </div>
                </div>
                <div class="form-group">
                    <label>Instagram</label>
                    <div class="input-with-icon">
                        <span class="social-icon" style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);">📸</span>
                        <input type="url" name="instagram" value="{{ $settings['instagram'] ?? '' }}" placeholder="https://instagram.com/yourhandle">
                    </div>
                </div>
                <div class="form-group">
                    <label>Twitter / X</label>
                    <div class="input-with-icon">
                        <span class="social-icon" style="background:#000;">𝕏</span>
                        <input type="url" name="twitter" value="{{ $settings['twitter'] ?? '' }}" placeholder="https://twitter.com/yourhandle">
                    </div>
                </div>
            </div>
        </div>

        <div style="text-align:right;margin-top:1rem;">
            <button type="submit" class="btn-save">💾 Save All Changes</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('logo-input').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const box = document.getElementById('logo-preview-box');
            let img = document.getElementById('logo-img');
            const placeholder = document.getElementById('logo-placeholder');
            if (!img) {
                img = document.createElement('img');
                img.id = 'logo-img';
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'contain';
                box.innerHTML = '';
                box.appendChild(img);
            }
            img.src = e.target.result;
            if (placeholder) placeholder.remove();
        };
        reader.readAsDataURL(file);
    });
</script>
</body>
</html>
