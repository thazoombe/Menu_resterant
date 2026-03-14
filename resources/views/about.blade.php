@use('Illuminate\Support\Facades\Auth')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Story - {{ $appSettings['restaurant_name'] ?? 'RestoDelights' }}</title>
    
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
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.1);
        }

        @if(($appSettings['default_theme'] ?? 'light') === 'dark')
        :root {
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --bg-light: #020617;
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
            box-shadow: 0 4px 30px rgba(0,0,0,0.05);
        }

        .logo { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.05em; color: white; text-decoration: none; transition: color 0.3s; }
        nav.sticky-nav.scrolled .logo { color: var(--text-main); }

        .nav-links { display: flex; gap: 2.5rem; align-items: center; }
        .nav-links a { text-decoration: none; color: white; font-weight: 600; font-size: 0.95rem; transition: all 0.3s; opacity: 0.8; }
        nav.sticky-nav.scrolled .nav-links a { color: var(--text-main); }
        .nav-links a:hover { opacity: 1; color: var(--accent) !important; transform: translateY(-2px); }
        .btn-nav { background: var(--primary); color: white !important; padding: 0.75rem 1.75rem; border-radius: 3rem; font-weight: 700; box-shadow: 0 10px 20px -5px rgba(99,102,241,0.4); opacity: 1 !important; }

        /* Header / Story Hero */
        header {
            background: #000;
            color: white;
            padding: 12rem 2rem 15rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        header .bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.4; }
        header .content { position: relative; z-index: 2; max-width: 800px; margin: 0 auto; }
        header h1 { font-size: 5rem; font-weight: 900; letter-spacing: -0.06em; margin: 0; }
        header p { color: rgba(255,255,255,0.7); font-size: 1.4rem; margin-top: 1.5rem; font-weight: 300; }

        /* Sections */
        .container { max-width: 1400px; margin: -8rem auto 8rem; padding: 0 5%; position: relative; z-index: 10; }
        
        .section-header { text-align: center; margin-bottom: 5rem; }
        .section-header h2 { font-size: 3rem; font-weight: 900; color: var(--bg-dark); }
        .section-header p { color: var(--text-muted); font-size: 1.2rem; }

        .about-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 3rem; }
        
        .about-card {
            background: white;
            border-radius: 3rem;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
        }

        .about-card:hover { transform: translateY(-15px); box-shadow: var(--shadow); border-color: var(--primary); }

        .card-img-wrap { height: 400px; overflow: hidden; position: relative; }
        .card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s; }
        .about-card:hover .card-img { transform: scale(1.1); }

        .card-info { padding: 2.5rem; text-align: center; }
        .card-name { font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin: 0; }
        .card-role { font-size: 0.9rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.1em; margin-top: 0.5rem; }

        /* Reveal Animations */
        .reveal { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }

        /* Footer */
        footer { background: var(--bg-dark); color: rgba(255,255,255,0.8); padding: 8rem 5% 4rem; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 10rem; position: relative; }
        footer::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent); }
        
        .footer-content { display: flex; justify-content: space-between; align-items: center; max-width: 1400px; margin: 0 auto; }
        .footer-logo { font-size: 2rem; font-weight: 800; color: white; text-decoration: none; letter-spacing: -0.04em; }
        .footer-logo span { color: var(--primary); }
        .footer-copy { color: rgba(255,255,255,0.4); font-size: 0.9rem; }

        /* Modal Styles matching Menu app */
        .modal { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(15px); z-index: 2000; display: none; align-items: center; justify-content: center; opacity: 0; transition: all 0.5s; padding: 2rem; }
        .modal.active { opacity: 1; }
        .modal-content { background: var(--glass); backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px); border: 1px solid var(--glass-border); width: 1000px; max-width: 100%; height: 700px; border-radius: 4rem; overflow: hidden; transform: scale(0.9) translateY(40px); transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); position: relative; display: flex; box-shadow: 0 50px 100px rgba(0,0,0,0.5); }
        .modal.active .modal-content { transform: scale(1) translateY(0); }
        
        .modal-left { flex: 1.2; position: relative; }
        .modal-left img { width: 100%; height: 100%; object-fit: cover; }
        .modal-right { flex: 1; padding: 5rem; display: flex; flex-direction: column; justify-content: center; }
        .modal-name { font-size: 3.5rem; font-weight: 900; color: var(--text-main); margin: 0; line-height: 1; letter-spacing: -0.03em; }
        .modal-role { font-size: 1.1rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.15em; margin-top: 1rem; }
        .modal-desc { color: var(--text-muted); font-size: 1.15rem; line-height: 1.8; margin-top: 3rem; }

        .close-modal { position: absolute; top: 2.5rem; right: 2.5rem; background: var(--glass); border: 1px solid var(--glass-border); width: 4rem; height: 4rem; border-radius: 50%; font-size: 1.75rem; cursor: pointer; color: var(--text-main); z-index: 100; transition: all 0.3s; display: flex; align-items: center; justify-content: center; }
        .close-modal:hover { transform: scale(1.1) rotate(90deg); background: white; }

        @media (max-width: 992px) {
            .modal-content { flex-direction: column; height: auto; max-height: 90vh; }
            .modal-left { height: 300px; }
            .modal-right { padding: 3rem; }
            .modal-name { font-size: 2.5rem; }
            header h1 { font-size: 3.5rem; }
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
        @auth
            <a href="/profile">My Account</a>
        @else
            <a href="/login" class="btn-nav">Login</a>
        @endauth
    </div>
</nav>

<header>
    <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" class="bg" alt="Gourmet Background">
    <div class="content">
        <h1 class="reveal">Our Story & Passion</h1>
        <p class="reveal" style="transition-delay: 0.2s">{{ $appSettings['tagline'] ?? 'Meticulously crafted flavors, served with heart.' }}</p>
    </div>
</header>

<div class="container">
    <div class="section-header reveal">
        <h2>Meet the Architects of Taste</h2>
        <p>A team of dedicated professionals committed to your culinary delight.</p>
    </div>

    <div class="about-grid">
        @foreach($aboutItems as $index => $item)
            <div class="about-card reveal" style="transition-delay: {{ $index * 0.1 }}s" onclick="showDetail({{ json_encode($item) }})">
                <div class="card-img-wrap">
                    <img src="{{ $item->image_path ?? 'https://ui-avatars.com/api/?name='.urlencode($item->name).'&background=random&size=800' }}" class="card-img" alt="{{ $item->name }}">
                </div>
                <div class="card-info">
                    <h3 class="card-name">{{ $item->name }}</h3>
                    <p class="card-role">{{ $item->role }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Detail Modal --}}
<div class="modal" id="detail-modal" onclick="closeDetail(event)">
    <div class="modal-content">
        <button class="close-modal" onclick="closeDetail()">&times;</button>
        <div class="modal-left">
            <img id="modal-img" src="" alt="Profile image">
        </div>
        <div class="modal-right">
            <h2 id="modal-name" class="modal-name"></h2>
            <p id="modal-role" class="modal-role"></p>
            <div id="modal-desc" class="modal-desc"></div>
        </div>
    </div>
</div>

<footer>
    <div class="footer-content">
        <a href="/" class="footer-logo">{{ $appSettings['restaurant_name'] ?? 'The Premium Restaurant' }}</a>
        <p class="footer-copy">&copy; 2026 Made by Lay Vanntha. All Rights Reserved.</p>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Sticky Header logic
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Reveal Animation logic
        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });

    function showDetail(item) {
        document.getElementById('modal-img').src = item.image_path || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(item.name) + '&background=random&size=800';
        document.getElementById('modal-name').innerText = item.name;
        document.getElementById('modal-role').innerText = item.role || '';
        document.getElementById('modal-desc').innerText = item.description;
        
        const modal = document.getElementById('detail-modal');
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
    }

    function closeDetail(event) {
        if (!event || event.target === document.getElementById('detail-modal') || event.target.classList.contains('close-modal')) {
            const modal = document.getElementById('detail-modal');
            modal.classList.remove('active');
            setTimeout(() => modal.style.display = 'none', 400);
        }
    }
</script>

</body>
</html>
