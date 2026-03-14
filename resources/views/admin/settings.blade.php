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
        .sidebar { width: 260px; background: #0f172a; color: white; height: 100vh; padding: 2rem; box-sizing: border-box; position: sticky; top: 0; overflow-y: auto; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 100; overflow-x: hidden; }
        .sidebar.collapsed { width: 0; padding: 2rem 0; transform: translateX(-260px); }
        .sidebar nav a { display: block; color: #94a3b8; text-decoration: none; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem; transition: all 0.2s; font-weight: 600; white-space: nowrap; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #1e293b; color: white; }
        .sidebar nav a.active { background: #1e293b; color: white; }

        /* Main */
        .main { flex: 1; padding: 3rem; overflow-y: auto; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; }
        .main.expanded { padding-left: 3rem; }
        
        .toggle-btn { background: white; border: 1px solid #e2e8f0; width: 40px; height: 40px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #0f172a; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .toggle-btn:hover { background: #f8fafc; border-color: #cbd5e1; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; gap: 1.5rem; }
        .header-left { display: flex; align-items: center; gap: 1.25rem; }
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

        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 2000; align-items: center; justify-content: center; padding: 1rem; }
        .modal-content { background: white; border-radius: 2rem; padding: 3rem; width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); transform: translateY(20px); animation: modalIn 0.3s ease forwards; }
        @keyframes modalIn { to { transform: translateY(0); } }

        /* About Item Card in Admin */
        .about-item-card { background: white; border: 1px solid #f1f5f9; border-radius: 1.25rem; padding: 1.25rem; display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1rem; transition: all 0.2s; }
        .about-item-card:hover { border-color: #3b82f6; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .about-item-img { width: 64px; height: 64px; border-radius: 1rem; object-fit: cover; background: #f8fafc; flex-shrink: 0; }
        .about-item-info { flex: 1; }
        .about-item-name { font-weight: 700; color: #0f172a; margin: 0; font-size: 1rem; }
        .about-item-role { font-size: 0.825rem; color: #64748b; margin: 0.1rem 0 0; }
        
        .btn-action { width: 36px; height: 36px; border-radius: 0.75rem; border: 1px solid #e2e8f0; background: white; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
        .btn-action:hover { background: #f1f5f9; transform: scale(1.05); }
        .btn-delete-item:hover { background: #fee2e2; border-color: #fecaca; color: #ef4444; }

        .btn-save { background: #0f172a; color: white; border: none; padding: 0.75rem 1.75rem; border-radius: 0.75rem; font-weight: 700; font-size: 0.9375rem; cursor: pointer; font-family: inherit; transition: all 0.2s; }
        .btn-save:hover { background: #1e293b; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

        @if(($appSettings['default_theme'] ?? 'light') === 'dark')
        .toggle-btn { background: #1e293b; border-color: #334155; color: white; }
        .toggle-btn:hover { background: #334155; }
        .about-item-card { background: #1e293b; border-color: #334155; }
        .about-item-name { color: #f8fafc; }
        .btn-action { background: #0f172a; border-color: #334155; color: white; }
        .modal-content { background: #1a2233; color: white; border: 1px solid #334155; }
        input[type=text], input[type=email], input[type=url], input[type=number], textarea, select { background: #0f172a; color: white; border-color: #334155; }
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
            <div style="font-size:0.7rem;color:#3b82f6;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Admin</div>
            <div style="font-weight:700;color:white;font-size:0.95rem;">{{ Auth::user()->name }}</div>
        </div>
    </div>
    <nav>
        <a href="/admin/dashboard">Dashboard</a>
        <a href="/admin/menu">Menu Items</a>
        <a href="/admin/categories">Categories</a>
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
        <div class="header-left">
            <button class="toggle-btn" id="sidebar-toggle" title="Toggle Sidebar">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 style="margin:0;">⚙️ Settings</h1>
                <p style="margin:0.25rem 0 0;color:#64748b;font-size:0.9rem;">Manage your restaurant's identity and website configuration.</p>
            </div>
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
                    <label>About Restaurant</label>
                    <textarea name="about_restaurant" rows="5" placeholder="Write a detailed description about your restaurant's history or story...">{{ $settings['about_restaurant'] ?? '' }}</textarea>
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
                <div class="form-group">
                    <label>TikTok</label>
                    <div class="input-with-icon">
                        <span class="social-icon" style="background:#000;">📱</span>
                        <input type="url" name="tiktok" value="{{ $settings['tiktok'] ?? '' }}" placeholder="https://tiktok.com/@yourhandle">
                    </div>
                </div>
                <div class="form-group">
                    <label>YouTube</label>
                    <div class="input-with-icon">
                        <span class="social-icon" style="background:#ff0000;">📺</span>
                        <input type="url" name="youtube" value="{{ $settings['youtube'] ?? '' }}" placeholder="https://youtube.com/@yourchannel">
                    </div>
                </div>
                <div class="form-group">
                    <label>Telegram</label>
                    <div class="input-with-icon">
                        <span class="social-icon" style="background:#0088cc;">✈️</span>
                        <input type="url" name="telegram" value="{{ $settings['telegram'] ?? '' }}" placeholder="https://t.me/yourusername">
                    </div>
                </div>
            </div>
        </div>

        {{-- 6. About Us Items --}}
        <div class="card">
            <div class="card-header">
                <div class="card-icon" style="background:#fefce8;">👥</div>
                <div style="flex:1;">
                    <h2>About Us Items</h2>
                    <p>Manage team members or features shown on the About Us page</p>
                </div>
                <button type="button" class="logo-btn" style="background:#0f172a;color:white;border:none;" onclick="openAddModal()">(+) Add New Item</button>
            </div>

            <div id="about-items-list">
                @forelse($aboutItems as $item)
                    <div class="about-item-card">
                        <img src="{{ $item->image_path ?? 'https://ui-avatars.com/api/?name='.urlencode($item->name).'&background=random' }}" class="about-item-img">
                        <div class="about-item-info">
                            <p class="about-item-name">{{ $item->name }}</p>
                            <div style="display: flex; gap: 0.5rem; align-items: center; margin-top: 0.25rem;">
                                <p class="about-item-role" style="margin: 0;">{{ $item->role ?? 'No Role' }}</p>
                                @if($item->facebook_url) <span title="Facebook" style="color:#1877f2; font-size: 0.75rem;">f</span> @endif
                                @if($item->instagram_url) <span title="Instagram" style="color:#e1306c; font-size: 0.75rem;">📸</span> @endif
                                @if($item->google_url) <span title="Google" style="color:#ea4335; font-size: 0.75rem;">G</span> @endif
                            </div>
                        </div>
                        <div style="display:flex;gap:0.5rem;">
                            <button type="button" class="btn-action" data-item="{{ json_encode($item) }}" onclick='openEditModal(this)'>✏️</button>
                            <form action="/admin/about-items/delete/{{ $item->id }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                @csrf
                                <button type="submit" class="btn-action btn-delete-item">🗑️</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:2rem;color:#94a3b8;">
                        <p>No items added yet. Click "Add New Item" to start.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div style="text-align:right;margin-top:1rem;">
            <button type="submit" class="btn-save">💾 Save All Changes</button>
        </div>
    </form>
</div>

{{-- Add Modal --}}
<div id="addModal" class="modal">
    <div class="modal-content">
        <h2 style="margin-top:0;">Add New About Item</h2>
        <form action="/admin/about-items" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Name / Title</label>
                <input type="text" name="name" required placeholder="e.g. John Doe or Our Mission">
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Role / Subtitle (Optional)</label>
                <input type="text" name="role" placeholder="e.g. Head Chef or Established 2010">
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Description / Detail</label>
                <textarea name="description" rows="4" required placeholder="Detailed information about this stuff..."></textarea>
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Facebook Profile URL</label>
                <input type="url" name="facebook_url" placeholder="https://facebook.com/username">
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Instagram Profile URL</label>
                <input type="url" name="instagram_url" placeholder="https://instagram.com/username">
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Google / Website URL</label>
                <input type="url" name="google_url" placeholder="https://google.com/or/website">
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label>Image (Optional)</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <div style="display:flex;gap:1rem;">
                <button type="submit" class="btn-save" style="flex:1;">Create Item</button>
                <button type="button" class="btn-save" style="background:#64748b;flex:1;" onclick="closeModal('addModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="modal">
    <div class="modal-content">
        <h2 style="margin-top:0;">Edit About Item</h2>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Name / Title</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Role / Subtitle (Optional)</label>
                <input type="text" name="role" id="edit_role">
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Description / Detail</label>
                <textarea name="description" id="edit_description" rows="4" required></textarea>
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Facebook Profile URL</label>
                <input type="url" name="facebook_url" id="edit_facebook_url" placeholder="https://facebook.com/username">
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Instagram Profile URL</label>
                <input type="url" name="instagram_url" id="edit_instagram_url" placeholder="https://instagram.com/username">
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Google / Website URL</label>
                <input type="url" name="google_url" id="edit_google_url" placeholder="https://google.com/or/website">
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label>New Image (Optional)</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <div style="display:flex;gap:1rem;">
                <button type="submit" class="btn-save" style="flex:1;background:#f59e0b;">Update Item</button>
                <button type="button" class="btn-save" style="background:#64748b;flex:1;" onclick="closeModal('editModal')">Cancel</button>
            </div>
        </form>
    </div>
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

// Modal Logic
function openAddModal() {
    document.getElementById('addModal').style.display = 'flex';
}

function openEditModal(button) {
    const item = JSON.parse(button.getAttribute('data-item'));
    document.getElementById('edit_name').value = item.name;
    document.getElementById('edit_role').value = item.role || '';
    document.getElementById('edit_description').value = item.description;
    document.getElementById('edit_facebook_url').value = item.facebook_url || '';
    document.getElementById('edit_instagram_url').value = item.instagram_url || '';
    document.getElementById('edit_google_url').value = item.google_url || '';
    document.getElementById('editForm').action = '/admin/about-items/' + item.id;
    document.getElementById('editModal').style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

</body>
</html>
