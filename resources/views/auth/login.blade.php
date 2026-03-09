<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Resto</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background: #fdfcfb; color: #1e293b; margin: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .auth-card { background: white; padding: 3rem; border-radius: 1.5rem; width: 400px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }
        h1 { margin: 0 0 0.5rem; font-size: 1.875rem; font-weight: 800; color: #0f172a; text-align: center; }
        p { margin: 0 0 2rem; color: #64748b; text-align: center; font-size: 0.875rem; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem; }
        input { width: 100%; padding: 0.875rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; box-sizing: border-box; font-size: 1rem; transition: border-color 0.2s; background: #f8fafc; }
        input:focus { border-color: #3b82f6; background: white; }
        .btn-submit { width: 100%; background: #0f172a; color: white; border: none; padding: 1rem; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; transition: transform 0.2s; margin-top: 1rem; }
        .btn-submit:hover { transform: scale(1.02); }
        .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: #64748b; }
        .auth-footer a { color: #3b82f6; text-decoration: none; font-weight: 600; }
        .error-msg { color: #ef4444; font-size: 0.75rem; font-weight: 600; margin-top: 0.4rem; display: block; }
    </style>
</head>
<body>

<div class="auth-card">
    <h1>Welcome Back</h1>
    <p>Login to your account to order faster.</p>

    <form action="/login" method="POST">
        @csrf
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="btn-submit">Sign In</button>
    </form>

    <div class="auth-footer">
        Don't have an account? <a href="/register">Sign Up</a>
    </div>
</div>

</body>
</html>
