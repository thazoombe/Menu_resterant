@use('Illuminate\Support\Facades\Auth')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appSettings['restaurant_name'] ?? 'The Premier Restaurant' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --secondary: #0f172a;
            --accent: #f59e0b;
            --bg-light: #f8fafc;
            --bg-dark: #020617;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --glass: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
            --shadow-premium: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            --radius: 1.25rem;
        }

        @if(($appSettings['default_theme'] ?? 'light') === 'dark')
        :root {
            --bg-light: #020617;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --glass: rgba(15, 23, 42, 0.6);
            --glass-border: rgba(255, 255, 255, 0.1);
        }
        @endif

        * { transition: background-color 0.3s, color 0.3s; }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg-light); 
            color: var(--text-main); 
            margin: 0; 
            padding: 0; 
            overflow-x: hidden; 
            line-height: 1.6;
            opacity: 0;
            animation: pageFadeIn 1s ease-out forwards;
        }

        @keyframes pageFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: var(--bg-light); }
        ::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 5px; border: 2px solid var(--bg-light); }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        /* Glassmorphism Utility */
        .glass {
            background: var(--glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
        }

        /* Navigation */
        nav.sticky-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 4rem;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        nav.sticky-nav.scrolled {
            height: 60px;
            background: var(--glass);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        
        .nav-logo { font-size: 1.5rem; font-weight: 800; color: white; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; transition: color 0.3s; }
        .nav-logo span { color: var(--primary); }
        nav.sticky-nav.scrolled .nav-logo { color: var(--text-main); }
        
        .nav-links { display: flex; align-items: center; gap: 2rem; }
        .nav-links a { text-decoration: none; color: white; font-weight: 600; font-size: 0.9rem; transition: all 0.3s; opacity: 0.8; }
        .nav-links a:hover { opacity: 1; color: var(--primary) !important; }
        nav.sticky-nav.scrolled .nav-links a { color: var(--text-main); }
        
        .user-pill {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid var(--glass-border);
            text-decoration: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }
        nav.sticky-nav.scrolled .user-pill { color: var(--text-main); background: rgba(0,0,0,0.05); }

        /* Hero Section */
        .hero {
            position: relative;
            height: 80vh;
            min-height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 0 2rem;
            overflow: hidden;
            background: #020617;
        }
        .hero-bg {
            position: absolute;
            inset: 0;
            background-image: url('/images/hero_premium.png');
            background-size: cover;
            background-position: center;
            opacity: 0.6;
            filter: brightness(0.7);
            transform: scale(1.1);
            animation: slowZoom 20s infinite alternate;
        }
        @keyframes slowZoom { from { transform: scale(1); } to { transform: scale(1.1); } }
        
        .hero-content { position: relative; z-index: 5; max-width: 800px; }
        .hero h1 { font-size: clamp(3rem, 8vw, 5rem); font-weight: 800; margin: 0; letter-spacing: -0.04em; line-height: 1.1; }
        .hero p { font-size: 1.25rem; color: rgba(255,255,255,0.8); margin: 1.5rem 0 2.5rem; font-weight: 400; }
        
        .hero-actions { display: flex; justify-content: center; gap: 1rem; }
        .btn-primary { 
            background: var(--primary); 
            color: white; 
            padding: 1rem 2.5rem; 
            border-radius: 3rem; 
            font-weight: 700; 
            text-decoration: none; 
            transition: all 0.3s;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        }
        .btn-primary:hover { transform: translateY(-3px); background: var(--primary-hover); box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.5); }

        /* Floating Nav Tools */
        .tools-wrap {
            max-width: 1100px;
            margin: -3rem auto 4rem;
            position: relative;
            z-index: 100;
            padding: 0 2rem;
        }
        .tools-inner {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border-radius: 4rem;
            box-shadow: var(--shadow-premium);
        }
        .search-inner {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            background: rgba(255,255,255,0.05);
            border-radius: 3rem;
        }
        .search-inner:focus-within {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid var(--primary);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
        }
        .search-inner input {
            background: transparent;
            border: none;
            width: 100%;
            padding: 0.8rem 1rem;
            color: var(--text-main);
            outline: none;
            font-weight: 500;
        }
        
        .tray-floating:hover { background: var(--primary-hover); transform: scale(1.02); }

        /* New Premium Floating Tray */
        .premium-tray {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 900;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 4rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.4);
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
        }

        .premium-tray:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 30px 50px rgba(99, 102, 241, 0.5);
        }

        .premium-tray:active { transform: scale(0.95); }

        .tray-icon-wrap {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tray-count-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            font-weight: 800;
            min-width: 1.25rem;
            height: 1.25rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #818cf8;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: all 0.3s;
        }

        .tray-text {
            font-weight: 800;
            font-size: 1rem;
            letter-spacing: -0.01em;
        }

        @keyframes trayPulse {
            0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(99, 102, 241, 0); }
            100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
        }

        .tray-pulse { animation: trayPulse 2s infinite; }

        @media (max-width: 768px) {
            .premium-tray {
                bottom: 1.5rem;
                right: 1.5rem;
                padding: 0.8rem 1.75rem;
            }
            .premium-tray .tray-text { font-size: 0.9rem; }
        }

        /* Category Bar */
        .category-scroll {
            display: flex;
            gap: 0.75rem;
            overflow-x: auto;
            scrollbar-width: none;
            padding: 0.5rem 2rem;
            margin-bottom: 3rem;
            justify-content: center;
        }
        .category-scroll::-webkit-scrollbar { display: none; }
        .cat-chip {
            white-space: nowrap;
            padding: 0.6rem 1.5rem;
            border-radius: 3rem;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--text-muted);
            background: transparent;
            border: 1px solid var(--glass-border);
            cursor: pointer;
            transition: all 0.2s;
        }
        .cat-chip:hover { border-color: var(--primary); color: var(--primary); }
        .cat-chip.active { background: var(--primary); color: white; border-color: var(--primary); box-shadow: 0 10px 15px -5px rgba(99, 102, 241, 0.3); }

        /* Grid & Cards */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem 10rem; }
        .section-title { font-size: 2rem; font-weight: 800; margin-bottom: 2.5rem; display: flex; align-items: center; gap: 1rem; }
        .section-title::after { content: ''; flex: 1; height: 1px; background: var(--glass-border); }
        
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2.5rem; }
        
        .menu-card {
            background: var(--glass);
            border-radius: var(--radius);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
            border: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .menu-card:hover { transform: translateY(-10px); box-shadow: var(--shadow-premium); border-color: var(--primary); }
        
        .card-img-wrap { width: 100%; height: 240px; overflow: hidden; position: relative; }
        .card-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s; }
        .menu-card:hover .card-img-wrap img { transform: scale(1.1); }
        
        .card-badges { position: absolute; top: 1rem; left: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap; z-index: 5; }
        .premium-badge { font-size: 0.65rem; padding: 0.35rem 0.8rem; border-radius: 2rem; font-weight: 800; text-transform: uppercase; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        
        .card-content { padding: 1.75rem; display: flex; flex-direction: column; flex: 1; }
        .card-cat { color: var(--primary); font-weight: 800; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; }
        .card-title { font-size: 1.5rem; font-weight: 700; margin: 0 0 0.75rem; letter-spacing: -0.02em; }
        .card-desc { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        
        .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
        .card-price { display: flex; flex-direction: column; }
        .price-now { font-size: 1.75rem; font-weight: 800; color: var(--text-main); }
        .price-old { font-size: 0.9rem; text-decoration: line-through; color: var(--text-muted); font-weight: 500; }
        
        .btn-add {
            background: var(--secondary);
            color: white;
            border: none;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-add:hover { background: var(--primary); transform: rotate(90deg) scale(1.1); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4); }
        .btn-add:active { transform: rotate(90deg) scale(0.9); }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .btn-add.pulse { animation: pulse 0.4s ease-out; }

        .fav-btn svg { transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .fav-btn:hover svg { transform: scale(1.2); }
        .fav-btn.active svg { animation: favBounce 0.4s ease-out; }

        @keyframes favBounce {
            0% { transform: scale(1); }
            50% { transform: scale(1.5); }
            100% { transform: scale(1.2); }
        }

        /* Cart Panel Refined */
        .cart-panel { padding: 3rem; background: var(--bg-light); border-left: 1px solid var(--glass-border); box-shadow: -20px 0 50px rgba(0,0,0,0.2); }
        .cart-header h2 { font-size: 2.5rem; letter-spacing: -0.05em; }
        
        /* Modal Refined */
        .modal-card { border-radius: 3rem; border: 1px solid var(--glass-border); box-shadow: 0 50px 100px -20px rgba(0,0,0,0.5); }

        /* Reveal Animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.19, 1, 0.22, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

            .tools-inner { border-radius: 1.5rem; flex-direction: column; }
        }

        /* Page Loader Styles */
        #page-loader {
            position: fixed;
            inset: 0;
            background: var(--glass-dark);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease;
        }
        
        .loader-content {
            text-align: center;
            color: white;
        }
        
        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255,255,255,0.1);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .loading-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: var(--primary);
            z-index: 10000;
            transition: width 0.3s ease;
            width: 0;
        }

        .loader-text {
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-size: 0.8rem;
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div id="page-loader">
    <div class="loader-content">
        <div class="loader-spinner"></div>
        <div class="loader-text">Loading Excellence...</div>
    </div>
</div>
<div class="loading-bar" id="loading-bar"></div>

<nav class="sticky-nav" id="main-nav">
    <a href="/" class="nav-logo">{{ $appSettings['restaurant_name'] ?? 'The Premium Restaurant' }}</a>
    <div class="nav-links">
        <a href="/">Menu</a>
        <a href="/about">About</a>
        @auth
            @if(Auth::user()->role == 'admin')
                <a href="/admin/dashboard">Admin</a>
            @endif
        @endauth
    </div>
    <div style="display: flex; align-items: center; gap: 1rem;">
        @auth
            <a href="/profile" class="user-pill glass">
                @if(Auth::user()->profile_photo_path)
                    <img src="{{ Auth::user()->profile_photo_path }}" style="width:24px;height:24px;border-radius:50%;object-fit:cover;">
                @else
                    <span style="width:24px;height:24px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:0.7rem;color:white;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                @endif
                {{ Auth::user()->name }}
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">Logout</a>
            <form id="logout-form" action="/logout" method="POST" style="display: none;">@csrf</form>
        @else
            <a href="/login" style="font-weight: 700; font-size: 0.9rem;">Login</a>
            <a href="/register" class="btn-primary" style="padding: 0.6rem 1.5rem; font-size: 0.85rem;">Join Now</a>
        @endauth
    </div>
</nav>

<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
        <h1>{{ $appSettings['restaurant_name'] ?? 'The Premier Restaurant' }}</h1>
        <p>{{ $appSettings['tagline'] ?? 'Experience the art of fine dining delivered to your doorstep.' }}</p>
        <div class="hero-actions">
            <a href="#top" class="btn-primary" onclick="scrollToCategory('top')">Explore Menu</a>
            <a href="/about" class="btn-primary" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); box-shadow: none; border: 1px solid rgba(255,255,255,0.2);">Our Story</a>
        </div>
    </div>
</section>

<div class="tools-wrap" id="top">
    <div class="tools-inner glass">
        <div class="search-inner">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="opacity:0.5; margin-right: 0.5rem;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="menu-search" placeholder="Search for your favorite dish...">
            <button class="btn-primary" style="padding: 0.6rem 1.5rem; font-size: 0.8rem; border-radius: 2rem; margin-left: 0.5rem; box-shadow: none;" onclick="filterMenu()">Search</button>
        </div>
    </div>
</div>

<div class="premium-tray tray-pulse" onclick="toggleCart()" id="floating-tray">
    <div class="tray-icon-wrap">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        <div class="tray-count-badge" id="cart-count">0</div>
    </div>
    <span class="tray-text">View Your Tray</span>
</div>

<div class="category-scroll">
    <button class="cat-chip active" onclick="scrollToCategory('top')">All Items</button>
    @foreach($categories as $category)
        @if(isset($groupedMenus[$category->id]))
            <button class="cat-chip" onclick="scrollToCategory('category-{{ $category->id }}')">{{ $category->name }}</button>
        @endif
    @endforeach
</div>

<div class="container">
    @foreach($categories as $category)
        @if(isset($groupedMenus[$category->id]))
            <div class="category-section" id="category-{{ $category->id }}">
                <h2 class="section-title">{{ $category->name }}</h2>
                
                <div class="menu-grid">
                    @foreach($groupedMenus[$category->id] as $index => $menu)
                    <div class="menu-card reveal" data-name="{{ strtolower($menu->name) }}" data-category-id="{{ $menu->category_id }}">
                        <div class="card-img-wrap">
                            <a href="/menu/{{ $menu->id }}">
                                @if($menu->image_path)
                                    <img src="{{ $menu->image_path }}" alt="{{ $menu->name }}">
                                @else
                                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:var(--bg-dark); opacity:0.1;">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                    </div>
                                @endif
                            </a>
                            
                            <div class="card-badges">
                                @if($menu->is_new) <span class="premium-badge" style="background:#10b981;">New</span> @endif
                                @if($menu->is_popular) <span class="premium-badge" style="background:#f59e0b;">Bestseller</span> @endif
                                @if($menu->discount_type === 'percent')
                                    <span class="premium-badge" style="background:var(--primary);">-{{ number_format($menu->discount_value, 0) }}%</span>
                                @endif
                            </div>

                            <button class="fav-btn {{ (Auth::check() && Auth::user()->favorites->contains($menu->id)) ? 'active' : '' }}" onclick="toggleFavorite({{ $menu->id }}, this)" style="position: absolute; top: 1rem; right: 1rem; background: var(--glass); width: 2.5rem; height: 2.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; z-index: 5;">
                                <svg viewBox="0 0 24 24" style="width:1.25rem; height:1.25rem; fill:{{ (Auth::check() && Auth::user()->favorites->contains($menu->id)) ? '#ef4444' : '#cbd5e1' }};"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </button>
                        </div>
                        
                        <div class="card-content">
                            <span class="card-cat">{{ $menu->category->name }}</span>
                            <a href="/menu/{{ $menu->id }}" style="text-decoration:none; color:inherit;">
                                <h3 class="card-title">{{ $menu->name }}</h3>
                            </a>
                            <p class="card-desc">{{ $menu->description }}</p>
                            
                            <div class="card-footer">
                                <div class="card-price">
                                    @if($menu->discounted_price < $menu->price)
                                        <span class="price-old">${{ number_format($menu->price, 2) }}</span>
                                        <span class="price-now">${{ number_format($menu->discounted_price, 2) }}</span>
                                    @else
                                        <span class="price-now">${{ number_format($menu->price, 2) }}</span>
                                    @endif
                                </div>
                                <button class="btn-add" onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->discounted_price }}, event)">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 5v14M5 12h14"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>

<div class="cart-overlay" id="cart-overlay" onclick="toggleCart()"></div>
<div class="cart-panel" id="cart-panel">
    <div class="cart-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:3rem;">
        <h2 style="margin:0;">Your Tray</h2>
        <button class="close-cart" onclick="toggleCart()" style="background:none; border:none; font-size:2rem; cursor:pointer; color:var(--text-muted);">&times;</button>
    </div>
    <div class="cart-items" id="cart-items" style="flex:1; overflow-y:auto;">
        <div class="empty-msg" style="text-align:center; padding-top:5rem; color:var(--text-muted);">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:1.5rem; opacity:0.3;"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <p>Your tray is feeling a bit lonely...</p>
        </div>
    </div>
    <div class="cart-footer" style="padding-top:2rem; border-top:1px solid var(--glass-border);">
        <div class="total-row" style="display:flex; justify-content:space-between; font-size:1.5rem; font-weight:800; margin-bottom:2rem;">
            <span>Total</span>
            <span id="cart-total">$0.00</span>
        </div>
        <button class="btn-primary" onclick="checkout()" style="width:100%; padding:1.25rem; font-size:1.1rem;">Place Order Now</button>
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

    function addToCart(id, name, price, event) {
        const btn = event.currentTarget;
        btn.classList.add('pulse');
        setTimeout(() => btn.classList.remove('pulse'), 400);

        const item = cart.find(i => i.id === id);
        if (item) {
            item.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        updateCartUI();
        const countBadge = document.getElementById('cart-count');
        const tray = document.getElementById('floating-tray');
        
        countBadge.style.transform = 'scale(1.5)';
        tray.style.transform = 'scale(1.1)';
        
        setTimeout(() => {
            countBadge.style.transform = 'scale(1)';
            tray.style.transform = 'scale(1)';
        }, 200);
    }

    function updateCartUI() {
        const itemsContainer = document.getElementById('cart-items');
        const countBadge = document.getElementById('cart-count');
        const totalDisplay = document.getElementById('cart-total');
        const tray = document.getElementById('floating-tray');

        if (cart.length === 0) {
            itemsContainer.innerHTML = `
                <div class="empty-msg" style="text-align:center; padding-top:5rem; color:var(--text-muted);">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:1.5rem; opacity:0.3;"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <p>Your tray is feeling a bit lonely...</p>
                </div>`;
            countBadge.innerText = '0';
            totalDisplay.innerText = '$0.00';
            tray.classList.add('tray-pulse');
            return;
        }

        tray.classList.remove('tray-pulse');
        let html = '';
        let total = 0;
        let count = 0;
        cart.forEach((item, index) => {
            total += item.price * item.quantity;
            count += item.quantity;
            html += `
                <div class="cart-item glass" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; padding:1.25rem; border-radius:1rem; animation: slideIn 0.3s ease-out;">
                    <div class="cart-item-info">
                        <h4 style="margin:0; font-size:1rem; font-weight:700;">${item.name}</h4>
                        <span style="color:var(--primary); font-weight:800;">$${item.price.toFixed(2)}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; background: rgba(0,0,0,0.05); border-radius: 2rem; overflow: hidden; height: 1.8rem;">
                            <button onclick="updateQuantity(${index}, -1)" style="background:none; border:none; padding: 0 0.6rem; height: 100%; cursor:pointer; font-weight:800; color:var(--text-main);">-</button>
                            <span style="font-size: 0.8rem; font-weight: 700; min-width: 1.2rem; text-align: center;">${item.quantity}</span>
                            <button onclick="updateQuantity(${index}, 1)" style="background:none; border:none; padding: 0 0.6rem; height: 100%; cursor:pointer; font-weight:800; color:var(--text-main);">+</button>
                        </div>
                        <button onclick="removeFromCart(${index})" style="background:#fee2e2; border:none; color:#ef4444; width:1.8rem; height:1.8rem; border-radius:50%; cursor:pointer; font-weight:700;">&times;</button>
                    </div>
                </div>
            `;
        });
        itemsContainer.innerHTML = html;
        countBadge.innerText = count;
        totalDisplay.innerText = `$${total.toFixed(2)}`;
    }

    function updateQuantity(index, change) {
        if (cart[index]) {
            cart[index].quantity += change;
            if (cart[index].quantity <= 0) {
                removeFromCart(index);
            } else {
                updateCartUI();
                const countBadge = document.getElementById('cart-count');
                const tray = document.getElementById('floating-tray');
                
                countBadge.style.transform = 'scale(1.5)';
                tray.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    countBadge.style.transform = 'scale(1)';
                    tray.style.transform = 'scale(1)';
                }, 200);
            }
        }
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
                window.location.href = '/order/invoice/' + data.order_id;
            } else {
                alert('Something went wrong. Please try again.');
            }
        })
        .catch(() => alert('Checkout failed. Please try again.'));
    }

    // ── Search & Filter ──────────────────────────────────────────
    function filterMenu() {
        const query = document.getElementById('menu-search').value.toLowerCase();
        const menuItems = document.querySelectorAll('.menu-item');
        const sections = document.querySelectorAll('.category-section');

        menuItems.forEach(item => {
            const name = item.dataset.name;
            const searchMatch = name.includes(query);
            item.style.display = searchMatch ? 'flex' : 'none';
        });

        // Hide empty sections
        sections.forEach(section => {
            const visibleItems = section.querySelectorAll('.menu-item[style*="display: flex"]');
            section.style.display = visibleItems.length > 0 ? 'block' : 'none';
        });
    }

    function scrollToCategory(id) {
        if (id === 'top') {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            const el = document.getElementById(id);
            if (el) {
                const offset = 100; // Account for sticky nav
                const elementPosition = el.getBoundingClientRect().top + window.pageYOffset;
                const offsetPosition = elementPosition - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        }
        
        // Update active state in category chips
        const chips = document.querySelectorAll('.cat-chip');
        chips.forEach(chip => {
            const onClickAttr = chip.getAttribute('onclick');
            if (onClickAttr && onClickAttr.includes(id)) {
                chip.classList.add('active');
            } else {
                chip.classList.remove('active');
            }
        });
    }

    // Sticky Nav Scroll effect
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('main-nav');
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('menu-search');
        if (searchInput) {
            searchInput.addEventListener('input', filterMenu);
        }

        // Reveal Animation logic
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // Page Loader Control
        const loader = document.getElementById('page-loader');
        const loadingBar = document.getElementById('loading-bar');

        function hideLoader() {
            if(loadingBar) loadingBar.style.width = '100%';
            setTimeout(() => {
                if(loader) {
                    loader.style.opacity = '0';
                    setTimeout(() => loader.style.display = 'none', 500);
                }
                if(loadingBar) {
                    setTimeout(() => loadingBar.style.opacity = '0', 300);
                }
            }, 200);
        }

        function showLoader() {
            if(loader) {
                loader.style.display = 'flex';
                loader.style.opacity = '1';
            }
            if(loadingBar) {
                loadingBar.style.opacity = '1';
                loadingBar.style.width = '0%';
                setTimeout(() => loadingBar.style.width = '70%', 10);
            }
        }

        // Hide on load
        window.addEventListener('load', hideLoader);
        // Handle back/forward cache
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) hideLoader();
        });

        // Intercept clicks
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (
                    href && 
                    !href.startsWith('#') && 
                    !href.startsWith('tel:') && 
                    !href.startsWith('mailto:') && 
                    this.target !== '_blank' &&
                    !e.ctrlKey && !e.shiftKey && !e.metaKey && !e.altKey
                ) {
                    showLoader();
                }
            });
        });

        // Parallax Effect
        const heroBg = document.querySelector('.hero-bg');
        window.addEventListener('scroll', () => {
            const scroll = window.pageYOffset;
            if (heroBg) {
                heroBg.style.transform = `translateY(${scroll * 0.4}px) scale(1.1)`;
            }
        });
    });

</script>

<!-- Marketing Pop-up -->
@if($featuredItem)
<div class="modal-overlay" id="promo-modal">
    <div class="modal-card">
        <button class="close-modal" onclick="closePromo()">&times;</button>
        <div class="promo-img">
            <a href="/menu/{{ $featuredItem->id }}" style="display:block; height:100%;">
                @if($featuredItem->image_path)
                    <img src="{{ $featuredItem->image_path }}" alt="{{ $featuredItem->name }}">
                @else
                    <div style="background: #f8fafc; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    </div>
                @endif
            </a>
            <div class="badge badge-promo" style="position: absolute; top: 1.5rem; left: 1.5rem; scale: 1.5;">Today's Special</div>
        </div>
        <div class="promo-content">
            <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                <span class="badge-promo">Today's Special</span>
            </div>
            <a href="/menu/{{ $featuredItem->id }}" style="text-decoration:none; color:inherit;">
                <h3>{{ $featuredItem->name }}</h3>
            </a>
            <p>{{ $featuredItem->description }}</p>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 2rem; margin-top: 1.5rem;">
                <div class="price-container">
                    @if($featuredItem->discounted_price < $featuredItem->price)
                        <span class="original-price" style="text-decoration: line-through; color: var(--text-muted); font-size: 1.25rem;">${{ number_format($featuredItem->price, 2) }}</span>
                        <div class="price-promo">${{ number_format($featuredItem->discounted_price, 2) }}</div>
                    @else
                        <div class="price-promo">${{ number_format($featuredItem->price, 2) }}</div>
                    @endif
                </div>
                <button class="btn-primary" style="padding: 1.25rem 4rem; font-size: 1.1rem; width: 100%;" onclick="addToCart({{ $featuredItem->id }}, '{{ addslashes($featuredItem->name) }}', {{ $featuredItem->discounted_price }}, event); closePromo();">Add to Tray Now</button>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(12px); z-index: 1000; display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: all 0.5s; }
    .modal-overlay.active { opacity: 1; pointer-events: auto; }
    .modal-card { background: var(--glass); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid var(--glass-border); width: 550px; border-radius: 3rem; overflow: hidden; transform: scale(0.9) translateY(40px); transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); position: relative; box-shadow: 0 50px 100px -20px rgba(0,0,0,0.5); }
    .modal-overlay.active .modal-card { transform: scale(1) translateY(0); }
    
    .promo-img { height: 350px; position: relative; overflow: hidden; }
    .promo-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s; }
    .modal-card:hover .promo-img img { transform: scale(1.05); }
    .promo-content { padding: 3.5rem; text-align: center; }
    .promo-content h3 { margin: 0; font-size: 2.75rem; font-weight: 800; letter-spacing: -0.05em; color: var(--text-main); line-height: 1; }
    .promo-content p { color: var(--text-muted); margin: 1.5rem 0 3rem; font-size: 1.15rem; line-height: 1.6; }
    
    .close-modal { position: absolute; top: 1.5rem; right: 1.5rem; background: var(--glass); border: none; width: 3.5rem; height: 3.5rem; border-radius: 50%; font-size: 1.5rem; cursor: pointer; color: var(--text-main); z-index: 10; display: flex; align-items: center; justify-content: center; transition: all 0.3s; border: 1px solid var(--glass-border); }
    .close-modal:hover { transform: rotate(90deg); scale: 1.1; background: white; }

    .price-promo { font-size: 2.5rem; font-weight: 800; color: var(--text-main); }
    .badge-promo { background: var(--accent); color: white; padding: 0.5rem 1.25rem; border-radius: 2rem; font-weight: 800; text-transform: uppercase; font-size: 0.75rem; }

    /* Footer */
    footer { background: var(--bg-dark); color: rgba(255,255,255,0.8); padding: 8rem 2rem 4rem; margin-top: 10rem; position: relative; }
    footer::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent); }
    
    .footer-grid { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 4rem; }
    .footer-brand h2 { font-size: 2.25rem; font-weight: 800; margin-bottom: 1.5rem; color: white; letter-spacing: -0.04em; }
    .footer-brand h2 span { color: var(--primary); }
    .footer-brand p { color: rgba(255,255,255,0.5); max-width: 320px; line-height: 1.8; font-size: 0.95rem; }
    
    .footer-head { font-size: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; color: var(--primary); margin-bottom: 2rem; }
    .footer-links { list-style: none; padding: 0; margin: 0; }
    .footer-links li { margin-bottom: 1.25rem; }
    .footer-links a { text-decoration: none; color: rgba(255,255,255,0.6); font-weight: 500; transition: all 0.2s; font-size: 0.95rem; }
    .footer-links a:hover { color: white; transform: translateX(5px); display: inline-block; }
    
    .social-flex { display: flex; gap: 1rem; flex-wrap: wrap; }
    .social-icon { width: 45px; height: 45px; border-radius: 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s; color: white; font-size: 1.25rem; }
    .social-icon:hover { transform: translateY(-5px); background: var(--primary); border-color: var(--primary); box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4); }
    
    .footer-bottom { max-width: 1200px; margin: 6rem auto 0; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); text-align: center; color: rgba(255,255,255,0.3); font-size: 0.85rem; }
</style>

<footer>
    <div class="footer-grid">
        <div class="footer-brand">
            <h2>{{ $appSettings['restaurant_name'] ?? 'The Premium Restaurant' }}</h2>
            <p>{{ $appSettings['tagline'] ?? 'Defining the future of culinary excellence, one plate at a time.' }}</p>
        </div>
        <div>
            <div class="footer-head">Explore</div>
            <ul class="footer-links">
                <li><a href="/">Our Menu</a></li>
                <li><a href="/about">Our Story</a></li>
                <li><a href="/login">Reservations</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-head">Legal</div>
            <ul class="footer-links">
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="/admin/login">Admin Portal</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-head">Social</div>
            <div class="social-flex">
                <a href="#" class="social-icon">f</a>
                <a href="#" class="social-icon">📸</a>
                <a href="#" class="social-icon">𝕏</a>
                <a href="#" class="social-icon">📱</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2026 Made by Lay Vanntha. All Rights Reserved.
    </div>
</footer>

<script>
    // Sticky Header logic
    document.addEventListener('scroll', () => {
        const nav = document.querySelector('.sticky-nav');
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });

    // Promo modal trigger logic
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const modal = document.getElementById('promo-modal');
            if (modal && !localStorage.getItem('promo_shown')) {
                modal.classList.add('active');
            }
        }, 1200);
    });

    function closePromo() {
        const modal = document.getElementById('promo-modal');
        if (modal) modal.classList.remove('active');
        localStorage.setItem('promo_shown', 'true');
    }
</script>

@if(($appSettings['enable_translation'] ?? 'yes') === 'yes')
<div id="google_translate_element" style="position: fixed; bottom: 30px; left: 30px; z-index: 9999;"></div>
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
.goog-te-gadget .goog-te-combo { background: var(--glass); border: 1px solid var(--glass-border); padding: 0.6rem 1rem; border-radius: 2rem; color: var(--text-main); outline: none; font-weight: 600; font-size: 0.8rem; }
.goog-te-banner-frame.skiptranslate { display: none !important; }
body { top: 0px !important; }
</style>
@endif

</body>
</html>
