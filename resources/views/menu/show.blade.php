<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menu->name }} - {{ $appSettings['restaurant_name'] ?? 'Resto' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary: #f59e0b;
            --primary-dark: #d97706;
            --dark: #0f172a;
            --gray-light: #f1f5f9;
            --gray: #64748b;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.9);
            --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
        }

        .navbar {
            background: var(--glass);
            backdrop-filter: blur(10px);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .logo { font-size: 1.8rem; font-weight: 800; color: var(--dark); text-decoration: none; }
        .logo span { color: var(--primary); }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 5%;
        }

        .breadcrumb {
            margin-bottom: 2rem;
            color: var(--gray);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .breadcrumb a { color: var(--primary); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            margin-bottom: 5rem;
        }

        .gallery {
            position: relative;
        }

        .main-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .thumbnails {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .thumb {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 0.75rem;
            cursor: pointer;
            opacity: 0.6;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .thumb.active, .thumb:hover {
            opacity: 1;
            border-color: var(--primary);
        }

        .product-info h1 {
            font-size: 3rem;
            font-weight: 800;
            color: var(--dark);
            margin: 0 0 0.5rem 0;
            line-height: 1.2;
        }

        .price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .badges {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-new { background: #fee2e2; color: #ef4444; }
        .badge-popular { background: #fef3c7; color: #f59e0b; }
        .badge-promo { background: #e0e7ff; color: #4f46e5; }

        .description {
            font-size: 1.1rem;
            color: var(--gray);
            margin-bottom: 2.5rem;
        }

        .add-to-cart-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 3rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }

        .add-to-cart-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.6);
        }

        .btn-submit { background: var(--primary); color: white; border: none; padding: 1rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer; transition: all 0.3s; width: 100%; border: 2px solid transparent; }
        .btn-submit:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3); }

        /* Footer Styles */
        footer { background: #f8fafc; padding: 4rem 2rem 2rem; border-top: 1px solid #e2e8f0; margin-top: 4rem; }
        .footer-content { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 4rem; }
        .footer-brand h2 { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; }
        .footer-brand p { color: #64748b; line-height: 1.6; max-width: 300px; }
        .footer-links h3 { font-size: 0.875rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1.5rem; }
        .footer-links ul { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 0.75rem; }
        .footer-links a { color: #475569; text-decoration: none; font-weight: 500; transition: color 0.2s; }
        .footer-links a:hover { color: #3b82f6; }
        .footer-social h3 { font-size: 0.875rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1.5rem; }
        .social-grid { display: flex; gap: 1rem; flex-wrap: wrap; }
        .social-link { width: 40px; height: 40px; border-radius: 50%; background: #fff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s; font-size: 1.25rem; }
        .social-link:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: #3b82f6; color: #3b82f6; }
        .footer-bottom { max-width: 1200px; margin: 4rem auto 0; padding-top: 2rem; border-top: 1px solid #e2e8f0; text-align: center; color: #94a3b8; font-size: 0.875rem; font-weight: 500; }

        @media (max-width: 768px) {
            .footer-content { grid-template-columns: 1fr; gap: 2rem; text-align: center; }
            .footer-brand p { margin: 0 auto; }
            .social-grid { justify-content: center; }
        }

        /* Reviews Section */
        .reviews-section {
            background: var(--white);
            border-radius: 1.5rem;
            padding: 3rem;
            box-shadow: var(--shadow);
            margin-bottom: 4rem;
        }

        .reviews-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--gray-light);
            padding-bottom: 1rem;
        }

        .reviews-header h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0;
            color: var(--dark);
        }

        .avg-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .star { color: #cbd5e1; font-size: 1.25rem; }
        .star.filled { color: #eab308; }

        .review-card {
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--gray-light);
        }
        .review-card:last-child { border-bottom: none; }

        .review-author {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .review-date { font-size: 0.85rem; color: var(--gray); font-weight: 400; }
        .review-rating { margin-bottom: 0.75rem; }
        .review-comment { color: #475569; }

        .review-form {
            background: var(--gray-light);
            padding: 2rem;
            border-radius: 1rem;
            margin-top: 2rem;
        }

        .review-form h3 { margin-top: 0; margin-bottom: 1.5rem; font-weight: 700; }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 0.5rem; }
        .form-group textarea {
            width: 100%; padding: 1rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; 
            font-family: inherit; font-size: 1rem; resize: vertical; min-height: 100px;
            box-sizing: border-box;
        }
        .form-group textarea:focus { outline: none; border-color: var(--primary); }

        .rating-input {
            display: flex;
            gap: 0.5rem;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .rating-input input { display: none; }
        .rating-input label {
            font-size: 2rem;
            color: #cbd5e1;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #eab308;
        }

        .btn-submit {
            background: var(--dark); color: white; border: none; padding: 0.875rem 2rem;
            border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: all 0.2s;
        }
        .btn-submit:hover { background: #1e293b; }

        .alert-success { background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 600; }

        @media(max-width: 768px) {
            .product-grid { grid-template-columns: 1fr; gap: 2rem; }
            .main-image { height: 350px; }
            .product-info h1 { font-size: 2.2rem; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="/" class="logo">{{ $appSettings['restaurant_name'] ?? 'Resto' }}<span>.</span></a>
        <div>
            <a href="/" style="text-decoration:none; color:var(--dark); font-weight:600; margin-right: 1.5rem;">Home</a>
            <a href="/about" style="text-decoration:none; color:var(--dark); font-weight:600; margin-right: 1.5rem;">About Us</a>
            <a href="/menu" style="text-decoration:none; color:var(--dark); font-weight:600;">Full Menu</a>
        </div>
    </nav>

    <div class="container">
        <div class="breadcrumb">
            <a href="/">Home</a> &gt; <a href="/menu">Menu</a> &gt; {{ $menu->category->name ?? 'Category' }} &gt; {{ $menu->name }}
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="product-grid">
            <div class="gallery">
                <img src="{{ $menu->image_path ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" alt="{{ $menu->name }}" class="main-image" id="mainImage">
                
                @if($menu->images->count() > 0)
                <div class="thumbnails">
                    <img src="{{ $menu->image_path ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c' }}" class="thumb active" onclick="setMainImage(this.src)">
                    @foreach($menu->images as $img)
                        <img src="{{ $img->image_path }}" class="thumb" onclick="setMainImage(this.src)">
                    @endforeach
                </div>
                @endif
            </div>

            <div class="product-info">
                <div class="badges">
                    @if($menu->is_new) <span class="badge badge-new">New</span> @endif
                    @if($menu->is_popular) <span class="badge badge-popular">Popular</span> @endif
                    @if($menu->is_promotion) <span class="badge badge-promo">Promo</span> @endif
                </div>
                
                <h1>{{ $menu->name }}</h1>
                
                <div class="avg-rating" style="margin-bottom: 1rem; font-size: 1rem;">
                    @php 
                        $avg = $menu->reviews->avg('rating') ?: 0;
                        $count = $menu->reviews->count();
                    @endphp
                    <div>
                        @for($i=1; $i<=5; $i++)
                            <span class="star {{ $i <= round($avg) ? 'filled' : '' }}">★</span>
                        @endfor
                    </div>
                    <span style="color: var(--gray); font-weight: 500;">({{ $count }} {{ Str::plural('review', $count) }})</span>
                </div>

                <div class="price">${{ number_format($menu->price, 2) }}</div>
                
                <div class="description">
                    {{ $menu->description }}
                </div>

                <button class="add-to-cart-btn" onclick="addToCartAndCheckout({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->discounted_price }})">
                    🛒 Order Now
                </button>
            </div>
        </div>

        <div class="reviews-section">
            <div class="reviews-header">
                <h2>Customer Reviews</h2>
                <div class="avg-rating">
                    <span>{{ number_format($avg, 1) }}</span>
                    <span class="star filled">★</span>
                </div>
            </div>

            @if($menu->reviews->count() > 0)
                @foreach($menu->reviews->sortByDesc('created_at') as $review)
                <div class="review-card">
                    <div class="review-author">
                        {{ $review->user->name ?? 'Guest User' }}
                        <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="review-rating">
                        @for($i=1; $i<=5; $i++)
                            <span class="star {{ $i <= $review->rating ? 'filled' : '' }}" style="font-size: 1rem;">★</span>
                        @endfor
                    </div>
                    @if($review->comment)
                        <div class="review-comment">{{ $review->comment }}</div>
                    @endif
                </div>
                @endforeach
            @else
                <p style="color: var(--gray); text-align: center; padding: 2rem 0;">No reviews yet. Be the first to try this dish!</p>
            @endif

            <div class="review-form">
                @auth
                    <h3>Write a Review</h3>
                    <form action="{{ route('menu.review.store', $menu->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Your Rating</label>
                            <div class="rating-input">
                                <input type="radio" id="star5" name="rating" value="5" required />
                                <label for="star5" title="5 stars">★</label>
                                <input type="radio" id="star4" name="rating" value="4" />
                                <label for="star4" title="4 stars">★</label>
                                <input type="radio" id="star3" name="rating" value="3" />
                                <label for="star3" title="3 stars">★</label>
                                <input type="radio" id="star2" name="rating" value="2" />
                                <label for="star2" title="2 stars">★</label>
                                <input type="radio" id="star1" name="rating" value="1" />
                                <label for="star1" title="1 star">★</label>
                            </div>
                            @error('rating') <span style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Your Comment (Optional)</label>
                            <textarea name="comment" placeholder="Tell us what you think about this dish..."></textarea>
                            @error('comment') <span style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn-submit">Submit Review</button>
                    </form>
                @else
                    <div style="text-align: center;">
                        <h3>Write a Review</h3>
                        <p style="color: var(--gray); margin-bottom: 1.5rem;">You need to be logged in to share your thoughts.</p>
                        <a href="/login" class="btn-submit" style="text-decoration:none; display:inline-block;">Log in to Review</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <script>
        // Pass PHP auth state to JS cleanly
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        const csrfToken = '{{ csrf_token() }}';
        const loggedInUserName = '{{ Auth::check() ? Auth::user()->name : "" }}';

        function setMainImage(src) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumb').forEach(el => el.classList.remove('active'));
            event.target.classList.add('active');
        }

        function addToCartAndCheckout(id, name, price) {
            let customerName;
            if (isLoggedIn) {
                customerName = loggedInUserName;
            } else {
                customerName = prompt('Please enter your name for the order:');
                if (!customerName) return;
            }

            const item = { id, name, price, quantity: 1 };

            fetch('/order/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ customer_name: customerName, items: [item] })
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
/* Hide the google branding */
.goog-te-gadget { color: transparent !important; font-family: 'Outfit', sans-serif; font-size: 0px; }
.goog-te-gadget .goog-te-combo { margin: 0; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: 'Outfit', sans-serif; font-size: 0.9rem; color: #1e293b; outline: none; }
.goog-te-banner-frame.skiptranslate { display: none !important; }
body { top: 0px !important; }
</style>
@endif

@if(($appSettings['default_theme'] ?? 'light') === 'dark')
<style>
    /* Dark Mode Overrides for Show Page */
    :root {
        --dark: #f8fafc;
        --gray-light: #1e293b;
        --white: #0f172a;
        --gray: #94a3b8;
    }
    body { background-color: #0f172a; color: #f8fafc; }
    .navbar { background: rgba(15, 23, 42, 0.9); border-bottom: 1px solid #334155; }
    .navbar a { color: #f8fafc !important; }
    .product-info h1, .reviews-header h2, .review-author { color: #f8fafc; }
    .review-comment { color: #cbd5e1; }
    .add-to-cart-btn { box-shadow: none; }
    .reviews-section { background: #1e293b; border: 1px solid #334155; }
    .review-form { background: #0f172a; border: 1px solid #334155; }
    .form-group textarea { background: #1e293b; color: white; border-color: #334155; }
    .btn-submit { background: #3b82f6; }
    .btn-submit:hover { background: #2563eb; }
    #google_translate_element { background: #1e293b !important; border: 1px solid #334155; }
    .goog-te-combo { background: #0f172a !important; color: white !important; border-color: #334155 !important; }
    .review-card { border-bottom: 1px solid #334155; }
    .reviews-header { border-bottom: 1px solid #334155; }
    
    footer { background: #0f172a; border-top: 1px solid #1e293b; }
    .footer-brand h2, .footer-links a, .footer-bottom { color: #f8fafc; }
    .footer-brand p, .footer-links a { color: #94a3b8; }
    .footer-links a:hover { color: #3b82f6; }
    .social-link { background: #1e293b; border-color: #334155; color: #f8fafc; }
    .footer-bottom { border-top-color: #1e293b; color: #64748b; }
</style>
@endif

<footer>
    <div class="footer-content">
        <div class="footer-brand">
            <h2>{{ $appSettings['restaurant_name'] ?? 'Resto Delights' }}</h2>
            <p>{{ $appSettings['tagline'] ?? 'Hand-crafted meals delivered straight to your door.' }}</p>
        </div>
        <div class="footer-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/about">About Us</a></li>
                <li><a href="/menu">Menu</a></li>
                <li><a href="/login">Customer Login</a></li>
                <li><a href="/admin/login">Admin Portal</a></li>
            </ul>
        </div>
        <div class="footer-social">
            <h3>Follow Us</h3>
            <div class="social-grid">
                @if(!empty($appSettings['facebook']))
                <a href="{{ $appSettings['facebook'] }}" target="_blank" class="social-link" title="Facebook">f</a>
                @endif
                @if(!empty($appSettings['instagram']))
                <a href="{{ $appSettings['instagram'] }}" target="_blank" class="social-link" title="Instagram">📸</a>
                @endif
                @if(!empty($appSettings['twitter']))
                <a href="{{ $appSettings['twitter'] }}" target="_blank" class="social-link" title="Twitter">𝕏</a>
                @endif
                @if(!empty($appSettings['tiktok']))
                <a href="{{ $appSettings['tiktok'] }}" target="_blank" class="social-link" title="TikTok">📱</a>
                @endif
                @if(!empty($appSettings['youtube']))
                <a href="{{ $appSettings['youtube'] }}" target="_blank" class="social-link" title="YouTube">📺</a>
                @endif
                @if(!empty($appSettings['telegram']))
                <a href="{{ $appSettings['telegram'] }}" target="_blank" class="social-link" title="Telegram">✈️</a>
                @endif
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2026 Made by Lay Vanntha. All Rights Reserved.
    </div>
</footer>

</body>
</html>
