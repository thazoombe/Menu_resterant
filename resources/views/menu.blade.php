<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; background: #fdfcfb; color: #1e293b; margin: 0; padding: 0; overflow-x: hidden; }
        
        .top-bar { background: #0f172a; padding: 0.75rem 2rem; display: flex; justify-content: flex-end; gap: 1.5rem; color: #94a3b8; font-size: 0.85rem; }
        .top-bar a { color: white; text-decoration: none; font-weight: 600; transition: color 0.2s; }
        .top-bar a:hover { color: #3b82f6; }
        
        header { background: #0f172a; color: white; padding: 4rem 2rem; text-align: center; position: relative; }
        header h1 { margin: 0; font-size: 3rem; font-weight: 800; letter-spacing: -0.05em; animation: fadeInDown 0.8s ease; }
        header p { color: #94a3b8; font-size: 1.1rem; margin-top: 0.5rem; animation: fadeInUp 0.8s ease; }
        
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }

        .nav-tools { max-width: 1100px; margin: -1.75rem auto 3rem; display: flex; gap: 1.25rem; padding: 0 2rem; position: relative; z-index: 10; }
        .search-bar { flex: 1; background: white; padding: 0.875rem 1.75rem; border-radius: 3rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; display: flex; align-items: center; }
        .search-bar input { border: none; width: 100%; outline: none; font-size: 1rem; margin-left: 0.75rem; }
        
        .tray-btn { background: #3b82f6; color: white; padding: 0.875rem 1.75rem; border-radius: 3rem; border: none; font-weight: 700; cursor: pointer; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 0.75rem; transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .tray-btn:hover { transform: scale(1.05); background: #2563eb; }
        .tray-count { background: white; color: #3b82f6; width: 1.5rem; height: 1.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 800; }

        .container { max-width: 1100px; margin: 0 auto; padding: 0 2rem 5rem; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2.5rem; }
        
        .menu-item { background: white; border-radius: 1.5rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); border: 1px solid #f1f5f9; display: flex; flex-direction: column; position: relative; animation: scaleIn 0.6s ease backwards; }
        .menu-item:hover { transform: translateY(-8px); box-shadow: 0 30px 45px -10px rgba(0,0,0,0.15); border-color: #3b82f6; }
        
        .food-img { width: 100%; height: 200px; background: #f8fafc; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-weight: 600; overflow: hidden; position: relative; }
        .food-img img { width: 100%; height: 100%; object-fit: cover; }

        .badge-container { position: absolute; top: 1rem; left: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap; z-index: 5; }
        .badge { font-size: 0.65rem; padding: 0.3rem 0.7rem; border-radius: 2rem; font-weight: 800; text-transform: uppercase; color: white; letter-spacing: 0.05em; }
        .badge-new { background: #10b981; }
        .badge-popular { background: #f59e0b; }
        .badge-promo { background: #ef4444; }

        .fav-btn { position: absolute; top: 1rem; right: 1rem; background: white; width: 2.5rem; height: 2.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); z-index: 5; transition: transform 0.2s; }
        .fav-btn:hover { transform: scale(1.1); }
        .fav-btn svg { width: 1.25rem; height: 1.25rem; fill: #cbd5e1; transition: fill 0.2s; }
        .fav-btn.active svg { fill: #ef4444; }

        .content { padding: 1.5rem 2rem 2rem; display: flex; flex-direction: column; flex: 1; }
        .category { font-size: 0.75rem; text-transform: uppercase; font-weight: 800; color: #3b82f6; letter-spacing: 0.1em; margin-bottom: 0.75rem; }
        .menu-item h2 { margin: 0 0 0.5rem; font-size: 1.5rem; font-weight: 800; color: #0f172a; }
        .menu-item p { margin: 0 0 1.5rem; color: #64748b; line-height: 1.6; font-size: 0.95rem; flex: 1; }
        
        .price-row { display: flex; align-items: center; justify-content: space-between; margin-top: auto; }
        .price { font-size: 1.5rem; font-weight: 800; color: #0f172a; }
        .add-btn { background: #0f172a; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        .add-btn:hover { background: #3b82f6; }

        /* Slider Tray */
        .cart-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); z-index: 100; opacity: 0; pointer-events: none; transition: opacity 0.4s; }
        .cart-overlay.active { opacity: 1; pointer-events: auto; }
        .cart-panel { position: fixed; top: 0; right: -450px; width: 450px; height: 100%; background: white; z-index: 101; transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: -20px 0 50px rgba(0,0,0,0.1); padding: 2.5rem; box-sizing: border-box; display: flex; flex-direction: column; }
        .cart-panel.active { right: 0; }
        .cart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; }
        .cart-header h2 { margin: 0; font-size: 2rem; font-weight: 800; letter-spacing: -0.05em; }
        .close-cart { background: #f1f5f9; border: none; width: 2.5rem; height: 2.5rem; border-radius: 50%; font-size: 1.25rem; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .close-cart:hover { background: #e2e8f0; color: #0f172a; }

        .cart-items { flex: 1; overflow-y: auto; margin-right: -1rem; padding-right: 1rem; }
        .cart-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; background: #f8fafc; padding: 1.25rem; border-radius: 1.25rem; border: 1px solid #f1f5f9; animation: scaleIn 0.3s ease; }
        .cart-item-info h4 { margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; }
        .cart-item-info span { font-size: 0.9rem; color: #64748b; font-weight: 600; }
        
        .cart-footer { padding-top: 2rem; margin-top: 1rem; }
        .total-row { display: flex; justify-content: space-between; align-items: center; font-size: 1.5rem; font-weight: 800; margin-bottom: 2rem; }
        .checkout-btn { width: 100%; background: #0f172a; color: white; border: none; padding: 1.25rem; border-radius: 1rem; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: all 0.3s; }
        .checkout-btn:hover { background: #3b82f6; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3); }
        .empty-msg { text-align: center; color: #94a3b8; margin-top: 5rem; display: flex; flex-direction: column; align-items: center; gap: 1rem; }
        .empty-msg svg { width: 4rem; height: 4rem; stroke: #e2e8f0; }
    </style>
</head>
<body>

<div class="top-bar">
    @auth
        <span style="display:flex;align-items:center;gap:0.6rem;">
            @if(Auth::user()->profile_photo_path)
                <img src="{{ Auth::user()->profile_photo_path }}" alt="avatar"
                     style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:2px solid #3b82f6;">
            @else
                <span style="width:28px;height:28px;border-radius:50%;background:#3b82f6;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800;color:white;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
            @endif
            Hello, <strong>{{ Auth::user()->name }}</strong>
        </span>
        <a href="/profile">My Profile</a>
        @if(Auth::user()->role == 'admin')
            <a href="/admin/dashboard">Admin Panel</a>
        @endif
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="/logout" method="POST" style="display: none;">@csrf</form>
    @else
        <a href="/login">Login</a>
        <a href="/register">Sign Up</a>
    @endauth
</div>

<header>
    <h1>Resto Delights</h1>
    <p>Hand-crafted meals delivered straight to your door.</p>
</header>

<div class="nav-tools">
    <div class="search-bar">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="menu-search" placeholder="Search for your favorite dish...">
    </div>
    <button class="tray-btn" onclick="toggleCart()">
        View Tray
        <span class="tray-count" id="cart-count">0</span>
    </button>
</div>

<div class="container">
    <div class="menu-grid" id="menu-list">
        @foreach($menus as $index => $menu)
        <div class="menu-item" data-name="{{ strtolower($menu->name) }}" style="animation-delay: {{ $index * 0.1 }}s">
            <div class="food-img">
                @if($menu->image_path)
                    <img src="{{ $menu->image_path }}" alt="{{ $menu->name }}">
                @else
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                @endif
                
                <div class="badge-container">
                    @if($menu->is_new) <span class="badge badge-new">New</span> @endif
                    @if($menu->is_popular) <span class="badge badge-popular">Popular</span> @endif
                    @if($menu->is_promotion) <span class="badge badge-promo">Promo</span> @endif
                </div>

                <button class="fav-btn {{ (Auth::check() && Auth::user()->favorites->contains($menu->id)) ? 'active' : '' }}" onclick="toggleFavorite({{ $menu->id }}, this)">
                    <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </button>
            </div>
            
            <div class="content">
                <span class="category">{{ $menu->category->name ?? 'Specialty' }}</span>
                <h2>{{ $menu->name }}</h2>
                <p>{{ $menu->description }}</p>
                <div class="price-row">
                    <span class="price">${{ number_format($menu->price, 2) }}</span>
                    <button class="add-btn" onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})">+ Add to Tray</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Cart Sidebar -->
<div class="cart-overlay" id="cart-overlay" onclick="toggleCart()"></div>
<div class="cart-panel" id="cart-panel">
    <div class="cart-header">
        <h2>Your Tray</h2>
        <button class="close-cart" onclick="toggleCart()">&times;</button>
    </div>
    <div class="cart-items" id="cart-items">
        <div class="empty-msg">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            Your tray is feeling a bit lonely...
        </div>
    </div>
    <div class="cart-footer">
        <div class="total-row">
            <span>Total:</span>
            <span id="cart-total">$0.00</span>
        </div>
        <button class="checkout-btn" onclick="checkout()">Place Order Now</button>
    </div>
</div>

<script>
    let cart = [];

    // Pass PHP auth state to JS cleanly
    const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    const csrfToken = '{{ csrf_token() }}';
    const loggedInUserName = '{{ Auth::check() ? Auth::user()->name : "" }}';

    // ── Favorites ─────────────────────────────────────────────
    function toggleFavorite(id, btn) {
        if (!isLoggedIn) {
            window.location.href = '/login';
            return;
        }

        fetch(`/favorite/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.classList.toggle('active', data.favorited);
            }
        })
        .catch(() => alert('Could not update favorites. Please try again.'));
    }

    // ── Cart ──────────────────────────────────────────────────
    function toggleCart() {
        document.getElementById('cart-overlay').classList.toggle('active');
        document.getElementById('cart-panel').classList.toggle('active');
    }

    function addToCart(id, name, price) {
        const item = cart.find(i => i.id === id);
        if (item) {
            item.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        updateCartUI();
        const countBadge = document.getElementById('cart-count');
        countBadge.style.transform = 'scale(1.4)';
        setTimeout(() => countBadge.style.transform = 'scale(1)', 200);
    }

    function updateCartUI() {
        const itemsContainer = document.getElementById('cart-items');
        const countBadge = document.getElementById('cart-count');
        const totalDisplay = document.getElementById('cart-total');

        if (cart.length === 0) {
            itemsContainer.innerHTML = `
                <div class="empty-msg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    Your tray is feeling a bit lonely...
                </div>`;
            countBadge.innerText = '0';
            totalDisplay.innerText = '$0.00';
            return;
        }

        let html = '';
        let total = 0;
        let count = 0;
        cart.forEach((item, index) => {
            total += item.price * item.quantity;
            count += item.quantity;
            html += `
                <div class="cart-item">
                    <div class="cart-item-info">
                        <h4>${item.name}</h4>
                        <span>$${item.price.toFixed(2)} x ${item.quantity}</span>
                    </div>
                    <button onclick="removeFromCart(${index})" style="background:#fee2e2; border:none; color:#ef4444; width:2rem; height:2rem; border-radius:50%; cursor:pointer; font-weight:700; display:flex; align-items:center; justify-content:center;">&times;</button>
                </div>
            `;
        });
        itemsContainer.innerHTML = html;
        countBadge.innerText = count;
        totalDisplay.innerText = `$${total.toFixed(2)}`;
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCartUI();
    }

    function checkout() {
        if (cart.length === 0) return alert('Your tray is empty!');

        let customerName;
        if (isLoggedIn) {
            customerName = loggedInUserName;
        } else {
            customerName = prompt('Please enter your name for the order:');
            if (!customerName) return;
        }

        fetch('/order/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ customer_name: customerName, items: cart })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('✅ Order placed! Order ID: ' + data.order_id);
                cart = [];
                updateCartUI();
                toggleCart();
            } else {
                alert('Something went wrong. Please try again.');
            }
        })
        .catch(() => alert('Checkout failed. Please try again.'));
    }

    // ── Search ────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('menu-search');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase().trim();
                document.querySelectorAll('.menu-item').forEach(function (item) {
                    const name = (item.dataset.name || '').toLowerCase();
                    item.style.display = (!query || name.includes(query)) ? '' : 'none';
                });
            });
        }
    });

</script>

<!-- Marketing Pop-up -->
@if($featuredItem)
<div class="modal-overlay" id="promo-modal">
    <div class="modal-card">
        <button class="close-modal" onclick="closePromo()">&times;</button>
        <div class="promo-img">
            @if($featuredItem->image_path)
                <img src="{{ $featuredItem->image_path }}" alt="{{ $featuredItem->name }}">
            @else
                <div style="background: #f8fafc; height: 100%; display: flex; align-items: center; justify-content: center;">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                </div>
            @endif
            <div class="badge badge-promo" style="position: absolute; top: 1.5rem; left: 1.5rem; scale: 1.5;">Today's Special</div>
        </div>
        <div class="promo-content">
            <h3>{{ $featuredItem->name }}</h3>
            <p>{{ $featuredItem->description }}</p>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                <span style="font-size: 2rem; font-weight: 800; color: #0f172a;">${{ number_format($featuredItem->price, 2) }}</span>
                <button class="add-btn" style="padding: 1rem 2rem;" onclick="addToCart({{ $featuredItem->id }}, '{{ addslashes($featuredItem->name) }}', {{ $featuredItem->price }}); closePromo();">Order Now</button>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px); z-index: 1000; display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.4s; }
    .modal-overlay.active { opacity: 1; pointer-events: auto; }
    .modal-card { background: white; width: 500px; border-radius: 2rem; overflow: hidden; transform: scale(0.8) translateY(20px); transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); }
    .modal-overlay.active .modal-card { transform: scale(1) translateY(0); }
    
    .promo-img { height: 280px; position: relative; overflow: hidden; }
    .promo-img img { width: 100%; height: 100%; object-fit: cover; }
    .promo-content { padding: 2.5rem; }
    .promo-content h3 { margin: 0; font-size: 2.25rem; font-weight: 800; letter-spacing: -0.05em; color: #0f172a; }
    .promo-content p { color: #64748b; font-size: 1.1rem; line-height: 1.6; margin-top: 0.75rem; }
    
    .close-modal { position: absolute; top: 1.5rem; right: 1.5rem; background: white; border: none; width: 2.5rem; height: 2.5rem; border-radius: 50%; font-size: 1.5rem; cursor: pointer; color: #0f172a; z-index: 10; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; }
</style>

<script>
    // Show promo modal on load
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const modal = document.getElementById('promo-modal');
            if (modal && !localStorage.getItem('promo_shown')) {
                modal.classList.add('active');
            }
        }, 1000);
    });

    function closePromo() {
        const modal = document.getElementById('promo-modal');
        if (modal) modal.classList.remove('active');
        localStorage.setItem('promo_shown', 'true');
    }
</script>

</body>
</html>