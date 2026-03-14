@use('Illuminate\Support\Facades\Auth')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $appSettings['restaurant_name'] ?? 'RestoDelights' }} - Gourmet Dining</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #f59e0b;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
            --bg-dark: #0f172a;
            --glass: rgba(255, 255, 255, 0.7);
            --glass-dark: rgba(15, 23, 42, 0.8);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.1);
        }

        @if(($appSettings['default_theme'] ?? 'light') === 'dark')
        :root {
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --bg-light: #020617;
            --bg-dark: #0f172a;
            --glass: rgba(15, 23, 42, 0.8);
            --glass-border: rgba(255, 255, 255, 0.1);
        }
        @endif

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
        ::-webkit-scrollbar-thumb { background: var(--text-muted); border-radius: 5px; border: 2px solid var(--bg-light); }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        /* Navigation */
        nav.sticky-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 1.5rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        nav.sticky-nav.scrolled {
            padding: 1rem 5%;
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.05em;
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        nav.sticky-nav.scrolled .logo {
            color: var(--text-main);
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s;
            opacity: 0.8;
        }

        nav.sticky-nav.scrolled .nav-links a {
            color: var(--text-main);
        }

        .nav-links a:hover {
            opacity: 1;
            color: var(--accent) !important;
            transform: translateY(-2px);
        }

        .btn-nav {
            background: var(--primary);
            color: white !important;
            padding: 0.75rem 1.75rem;
            border-radius: 3rem;
            font-weight: 700;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
            opacity: 1 !important;
        }

        .btn-nav:hover {
            background: var(--primary-dark);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.5);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            overflow: hidden;
            background: #000;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background: url('/images/hero_premium.png') center/cover no-repeat;
            opacity: 0.6;
            z-index: 1;
            transition: transform 0.1s ease-out;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.8));
            z-index: 2;
        }

        .hero-content {
            position: relative;
            z-index: 3;
            max-width: 900px;
            padding: 0 2rem;
        }

        .hero h1 {
            font-size: 5.5rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1;
            margin: 0;
            background: linear-gradient(to bottom, #fff, #94a3b8);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: slideInDown 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hero p {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 2rem 0 3rem;
            font-weight: 300;
            animation: slideInUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.2s backwards;
        }

        @keyframes slideInDown { from { opacity: 0; transform: translateY(-50px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideInUp { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }

        .hero-btns {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            animation: slideInUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.4s backwards;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 1.25rem 3rem;
            border-radius: 4rem;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.1rem;
            transition: all 0.4s;
            box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.5);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 25px 50px -10px rgba(99, 102, 241, 0.6);
        }

        .btn-secondary {
            background: var(--glass);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: var(--text-main);
            padding: 1.25rem 3rem;
            border-radius: 4rem;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.1rem;
            transition: all 0.4s;
            border: 1px solid var(--glass-border);
        }

        .btn-secondary:hover {
            background: white;
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2);
        }

        /* Features Section */
        .section {
            padding: 10rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3rem;
            margin-top: 4rem;
        }

        .feature-card {
            background: white;
            border-radius: 3rem;
            padding: 4rem 3rem;
            text-align: center;
            transition: all 0.5s;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
        }

        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow);
            border-color: var(--primary);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            border-radius: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2.5rem;
            font-size: 2rem;
        }

        .feature-card h3 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 1.25rem;
        }

        .feature-card p {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        /* Signature Showcase */
        .showcase {
            background: var(--bg-dark);
            color: white;
            padding: 10rem 5%;
            border-radius: 0;
            position: relative;
            overflow: hidden;
        }

        .showcase-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 6rem;
        }

        .showcase-header h2 {
            font-size: 4rem;
            font-weight: 900;
            letter-spacing: -0.05em;
            margin: 0;
        }

        .showcase-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .showcase-item {
            height: 450px;
            border-radius: 3rem;
            overflow: hidden;
            position: relative;
        }

        .showcase-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s;
        }

        .showcase-item:hover img {
            transform: scale(1.1);
        }

        .showcase-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.9), transparent 60%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2.5rem;
            opacity: 0;
            transition: opacity 0.5s;
        }

        .showcase-item:hover .showcase-overlay {
            opacity: 1;
        }

        /* Footer */
        footer { background: var(--bg-dark); color: rgba(255,255,255,0.8); padding: 8rem 5% 4rem; margin-top: 10rem; position: relative; }
        footer::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent); }
        
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 4rem; max-width: 1400px; margin: 0 auto; }
        .footer-brand h2 { font-size: 2.25rem; font-weight: 800; color: white; margin-bottom: 2rem; letter-spacing: -0.04em; }
        .footer-brand h2 span { color: var(--primary); }
        .footer-text { color: rgba(255,255,255,0.5); max-width: 400px; line-height: 2; font-size: 0.95rem; }
        
        .footer-title { font-weight: 800; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.15em; margin-bottom: 2.5rem; color: var(--primary); }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 1.25rem; }
        .footer-links a { text-decoration: none; color: rgba(255,255,255,0.6); font-weight: 500; transition: all 0.3s; font-size: 0.95rem; }
        .footer-links a:hover { color: white; transform: translateX(5px); display: inline-block; }
        
        .footer-bottom { text-align: center; margin-top: 6rem; padding-top: 3rem; border-top: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.3); font-size: 0.85rem; }

        @media (max-width: 992px) {
            .hero h1 { font-size: 4rem; }
            .grid-3 { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; gap: 4rem; text-align: center; }
            .footer-text { margin: 0 auto; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

<nav class="sticky-nav" id="main-nav">
    <a href="/" class="logo">{{ $appSettings['restaurant_name'] ?? 'RestoDelights' }}</a>
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/menu">Browse Menu</a>
        <a href="/about">Our Story</a>
        @guest
            <a href="/login">Login</a>
            <a href="/register" class="btn-nav">Join Now</a>
        @else
            <a href="/profile">My Account</a>
            <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-nav">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        @endguest
    </div>
</nav>

<section class="hero">
    <div class="hero-bg" id="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Experience Culinary Excellence</h1>
        <p>{{ $appSettings['tagline'] ?? 'Hand-crafted meals prepared with passion, delivered with care.' }}</p>
        <div class="hero-btns">
            <a href="/menu" class="btn-primary">Order Now</a>
            <a href="/about" class="btn-secondary">Learn Our Story</a>
        </div>
    </div>
</section>

<section class="section">
    <div style="text-align: center; max-width: 800px; margin: 0 auto;" class="reveal">
        <h2 style="font-size: 3.5rem; font-weight: 900; letter-spacing: -0.05em;">Why Dine With Us?</h2>
        <p style="color: var(--text-muted); font-size: 1.25rem;">We combine traditional techniques with modern flavors to create an unforgettable dining experience.</p>
    </div>

    <div class="grid-3">
        <div class="feature-card reveal" style="transition-delay: 0.1s">
            <div class="feature-icon">✨</div>
            <h3>Premium Ingredients</h3>
            <p>Only the freshest, locally sourced produce and premium cuts make it to our kitchen.</p>
        </div>
        <div class="feature-card reveal" style="transition-delay: 0.2s">
            <div class="feature-icon">👨‍🍳</div>
            <h3>Master Chefs</h3>
            <p>Our culinary team brings decades of experience from the world's finest kitchens.</p>
        </div>
        <div class="feature-card reveal" style="transition-delay: 0.3s">
            <div class="feature-icon">🚚</div>
            <h3>Swift Delivery</h3>
            <p>Enjoy the gourmet experience from the comfort of your home with our specialized fleet.</p>
        </div>
    </div>
</section>

<section class="showcase">
    <div class="showcase-header reveal">
        <h2>Signature Flavors</h2>
        <p style="color: rgba(255,255,255,0.7); font-size: 1.2rem;">A glimpse into our chef's most celebrated creations.</p>
    </div>

    <div class="showcase-grid">
        <div class="showcase-item reveal">
            <img src="/images/premium_pizza.png" alt="Gourmet Pizza">
            <div class="showcase-overlay">
                <h3>Signature Pizzas</h3>
                <p>Wood-fired perfection with hand-stretched dough.</p>
            </div>
        </div>
        <div class="showcase-item reveal" style="transition-delay: 0.1s">
            <img src="/images/premium_burger.png" alt="Artisan Burger">
            <div class="showcase-overlay">
                <h3>Artisan Burgers</h3>
                <p>Prime beef patties with secret house-made sauce.</p>
            </div>
        </div>
        <div class="showcase-item reveal" style="transition-delay: 0.2s">
            <img src="/images/premium_salad.png" alt="Fresh Greens">
            <div class="showcase-overlay">
                <h3>Spring Salads</h3>
                <p>Vibrant, organic greens and seasonal dressings.</p>
            </div>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 6rem;" class="reveal">
        <a href="/menu" class="btn-primary" style="padding: 1.25rem 4rem;">Explore Full Menu</a>
    </div>
</section>

<footer>
    <div class="footer-grid">
        <div class="footer-brand">
            <h2 class="footer-brand">{{ $appSettings['restaurant_name'] ?? 'The Premium Restaurant' }}</h2>
            <p class="footer-text">Elevating the art of dining through passion, innovation, and unparalleled service. Join us on a culinary journey like no other.</p>
        </div>
        <div>
            <h4 class="footer-title">Explore</h4>
            <ul class="footer-links">
                <li><a href="/">Home</a></li>
                <li><a href="/menu">Browse Menu</a></li>
                <li><a href="/about">Our Story</a></li>
                <li><a href="/login">Customer Login</a></li>
            </ul>
        </div>
        <div>
            <h4 class="footer-title">Contact</h4>
            <ul class="footer-links">
                <li><span style="color: rgba(255,255,255,0.5)">123 Gourmet Way, Culinary City</span></li>
                <li><a href="tel:1234567890">+1 (234) 567-890</a></li>
                <li><a href="mailto:hello@restodelights.com">hello@restodelights.com</a></li>
            </ul>
        </div>
        <div>
            <h4 class="footer-title">Social</h4>
            <div style="display: flex; gap: 1rem;">
                <a href="#" style="color: white; font-size: 1.2rem; background: rgba(255,255,255,0.05); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">f</a>
                <a href="#" style="color: white; font-size: 1.2rem; background: rgba(255,255,255,0.05); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">📸</a>
                <a href="#" style="color: white; font-size: 1.2rem; background: rgba(255,255,255,0.05); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">𝕏</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2026 {{ $appSettings['restaurant_name'] ?? 'RestoDelights' }}. Made with ❤️ by Lay Vanntha.
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sticky Header logic
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Hero Parallax
        const heroBg = document.getElementById('hero-bg');
        window.addEventListener('scroll', () => {
            const scroll = window.pageYOffset;
            if (heroBg) {
                heroBg.style.transform = `translateY(${scroll * 0.4}px) scale(1.1)`;
            }
        });

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
    });
</script>

</body>
</html>
