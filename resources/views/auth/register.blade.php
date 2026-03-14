<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Us - {{ $appSettings['restaurant_name'] ?? 'RestoDelights' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg: #f8fafc;
            --glass: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        @if(($appSettings['default_theme'] ?? 'light') === 'dark')
        :root {
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --bg: #0f172a;
            --glass: rgba(15, 23, 42, 0.8);
            --glass-border: rgba(255, 255, 255, 0.1);
        }
        @endif

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-main);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            padding: 2rem 0;
        }

        .bg-pattern {
            position: absolute;
            inset: 0;
            background: url('/images/hero_premium.png') center/cover;
            opacity: 0.15;
            filter: grayscale(1);
            z-index: -1;
        }

        .auth-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 3.5rem;
            border-radius: 3rem;
            width: 500px;
            max-width: 90%;
            box-shadow: 0 50px 100px -20px rgba(0,0,0,0.2);
            border: 1px solid var(--glass-border);
            text-align: center;
            animation: cardAppear 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardAppear {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        h1 { margin: 0 0 0.5rem; font-size: 2.25rem; font-weight: 900; letter-spacing: -0.04em; }
        p { margin: 0 0 2rem; color: var(--text-muted); font-size: 1rem; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; text-align: left; }
        .form-group { text-align: left; margin-bottom: 1.25rem; }
        .full-width { grid-column: span 2; }

        label { display: block; font-size: 0.7rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; margin-left: 0.5rem; }
        input { width: 100%; padding: 0.875rem 1.25rem; border: 1px solid var(--glass-border); border-radius: 1.25rem; outline: none; box-sizing: border-box; font-size: 0.95rem; transition: all 0.3s; background: rgba(255,255,255,0.05); color: var(--text-main); font-weight: 500; }
        input:focus { border-color: var(--primary); background: rgba(255,255,255,0.1); box-shadow: 0 0 20px rgba(99, 102, 241, 0.1); }

        .btn-submit { width: 100%; background: var(--primary); color: white; border: none; padding: 1.25rem; border-radius: 1.25rem; font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: all 0.3s; margin-top: 1rem; box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.4); }
        .btn-submit:hover { background: var(--primary-dark); transform: translateY(-3px); box-shadow: 0 20px 40px -5px rgba(99, 102, 241, 0.5); }

        .auth-footer { text-align: center; margin-top: 2rem; font-size: 0.95rem; color: var(--text-muted); }
        .auth-footer a { color: var(--primary); text-decoration: none; font-weight: 700; margin-left: 0.25rem; }
        .error-msg { color: #ef4444; font-size: 0.7rem; font-weight: 700; margin-top: 0.4rem; margin-left: 0.5rem; display: block; }

        .back-home { position: absolute; top: 2rem; left: 2rem; color: var(--text-muted); text-decoration: none; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; transition: color 0.3s; }
        .back-home:hover { color: var(--primary); }

        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group { grid-column: span 1 !important; }
            .auth-card { padding: 2rem; }
        }
    </style>
</head>
<body>

<div class="bg-pattern"></div>

<a href="/" class="back-home">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    Back to Home
</a>

<div class="auth-card">
    <h1>Create Account</h1>
    <p>Join {{ $appSettings['restaurant_name'] ?? 'RestoDelights' }} for the best dining experience.</p>

    <form action="/register" method="POST">
        @csrf
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group full-width">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="john@example.com">
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group full-width">
                <label>Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+1 (234) 567-890">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Confirm</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="btn-submit">Join the Community</button>
    </form>

    <div class="auth-footer">
        Already a member? <a href="/login">Sign In Instead</a>
    </div>
</div>

</body>
</html>
