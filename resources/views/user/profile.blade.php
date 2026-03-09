<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Resto Delights</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; padding: 0; }

        /* Top bar */
        .topbar { background: #0f172a; padding: 0.75rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .topbar a { color: #94a3b8; text-decoration: none; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem; transition: color 0.2s; }
        .topbar a:hover { color: white; }

        .page { max-width: 1000px; margin: 0 auto; padding: 2.5rem 2rem; }

        /* Hero header */
        .hero { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); border-radius: 1.5rem; padding: 2.5rem 3rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 2rem; position: relative; overflow: hidden; }
        .hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 80% 50%, rgba(59,130,246,0.15) 0%, transparent 60%); }
        .hero-text h1 { margin: 0; font-size: 2rem; font-weight: 800; color: white; }
        .hero-text p { color: #94a3b8; margin: 0.4rem 0 0; font-size: 0.95rem; }

        /* Avatar */
        .avatar-wrap { position: relative; flex-shrink: 0; }
        .avatar { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(59,130,246,0.6); background: #1e293b; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; color: #3b82f6; overflow: hidden; }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-edit { position: absolute; bottom: 2px; right: 2px; background: #3b82f6; border: 2px solid #0f172a; border-radius: 50%; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s; }
        .avatar-edit:hover { background: #2563eb; }
        .avatar-edit svg { width: 13px; height: 13px; stroke: white; fill: none; }
        #photo-input { display: none; }

        /* Grid */
        .grid { display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; }
        @media(max-width: 768px) { .grid { grid-template-columns: 1fr; } }

        .card { background: white; border-radius: 1.25rem; padding: 1.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; }
        .card h2 { margin: 0 0 1.25rem; font-size: 1.1rem; font-weight: 800; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem; }

        label { display: block; font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.4rem; }
        input, textarea { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 0.625rem; font-size: 0.9375rem; font-family: inherit; outline: none; transition: border-color 0.2s; }
        input:focus, textarea:focus { border-color: #3b82f6; }
        .form-row { margin-bottom: 1rem; }

        .btn { background: #0f172a; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 700; cursor: pointer; font-family: inherit; font-size: 0.9375rem; transition: all 0.2s; width: 100%; margin-top: 0.5rem; }
        .btn:hover { background: #3b82f6; }

        .alert-success { background: #dcfce7; color: #166534; border-radius: 0.75rem; padding: 0.875rem 1rem; margin-bottom: 1.5rem; font-weight: 600; font-size: 0.875rem; }

        /* Favorites & Orders */
        .item-row { display: flex; justify-content: space-between; align-items: center; padding: 0.875rem 1rem; background: #f8fafc; border-radius: 0.875rem; margin-bottom: 0.75rem; border: 1px solid #f1f5f9; }
        .item-row h4 { margin: 0; font-weight: 700; font-size: 0.95rem; }
        .item-row span { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; display: block; }
        .badge { padding: 0.2rem 0.65rem; border-radius: 2rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .badge-completed { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .tag-blue { background: #eff6ff; color: #3b82f6; }
        .empty { color: #94a3b8; font-style: italic; font-size: 0.875rem; padding: 1rem 0; }
    </style>
</head>
<body>

<div class="topbar">
    <a href="/">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
        Back to Menu
    </a>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color:#ef4444;">Logout</a>
    <form id="logout-form" action="/logout" method="POST" style="display:none;">@csrf</form>
</div>

<div class="page">

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    {{-- Hero --}}
    <div class="hero">
        <div class="avatar-wrap">
            <div class="avatar" id="avatar-preview">
                @if($user->profile_photo_path)
                    <img src="{{ $user->profile_photo_path }}" alt="Profile Photo" id="avatar-img">
                @else
                    <span id="avatar-initials">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
            </div>
            <label for="photo-input" class="avatar-edit" title="Change photo">
                <svg viewBox="0 0 24 24" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
            </label>
        </div>
        <div class="hero-text">
            <h1>{{ $user->name }}</h1>
            <p>{{ $user->email }} &nbsp;·&nbsp; Member since {{ $user->created_at->format('M Y') }}</p>
        </div>
    </div>

    <div class="grid">
        {{-- Profile Form --}}
        <div>
            <div class="card">
                <h2>✏️ Edit Profile</h2>
                <form action="/profile/update" method="POST" enctype="multipart/form-data" id="profile-form">
                    @csrf
                    <input type="file" id="photo-input" name="profile_photo" accept="image/*">
                    <div class="form-row">
                        <label>Full Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="form-row">
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ $user->phone }}">
                    </div>
                    <div class="form-row">
                        <label>Address</label>
                        <textarea name="address" rows="3">{{ $user->address }}</textarea>
                    </div>
                    <button type="submit" class="btn">Save Changes</button>
                </form>
            </div>
        </div>

        {{-- Favorites & Orders --}}
        <div>
            <div class="card" style="margin-bottom: 1.5rem;">
                <h2>❤️ Favorite Dishes</h2>
                @forelse($favorites as $fav)
                    <div class="item-row">
                        <div>
                            <h4>{{ $fav->name }}</h4>
                            <span>{{ $fav->category->name ?? 'Specialty' }} &nbsp;·&nbsp; ${{ number_format($fav->price, 2) }}</span>
                        </div>
                        <a href="/" style="font-size: 0.8rem; font-weight: 700; color: #3b82f6; text-decoration: none;">Order Again →</a>
                    </div>
                @empty
                    <p class="empty">No favorites yet. Heart a dish on the menu!</p>
                @endforelse
            </div>

            <div class="card">
                <h2>🧾 Order History</h2>
                @forelse($orders as $order)
                    <div class="item-row">
                        <div>
                            <h4>Order #{{ $order->id }}</h4>
                            <span>{{ $order->created_at->format('d M Y, H:i') }} &nbsp;·&nbsp; ${{ number_format($order->total_price, 2) }}</span>
                        </div>
                        <span class="badge badge-{{ $order->status }}">{{ $order->status }}</span>
                    </div>
                @empty
                    <p class="empty">No orders yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    // Live preview when user picks a photo
    document.getElementById('photo-input').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const initials = document.getElementById('avatar-initials');
            let img = document.getElementById('avatar-img');
            if (!img) {
                img = document.createElement('img');
                img.id = 'avatar-img';
                preview.innerHTML = '';
                preview.appendChild(img);
            }
            img.src = e.target.result;
            if (initials) initials.remove();
        };
        reader.readAsDataURL(file);
        // Auto-submit when photo is picked
        document.getElementById('profile-form').submit();
    });
</script>
</body>
</html>
