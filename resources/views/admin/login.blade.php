<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Resto</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: white; margin: 0; display: flex; align-items: center; justify-content: center; height: 100vh; }
        
        .login-card { background: white; color: #1e293b; padding: 3rem; border-radius: 1.5rem; width: 400px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); }
        .login-card h2 { margin: 0 0 0.5rem; font-size: 1.875rem; font-weight: 800; color: #0f172a; text-align: center; }
        .login-card p { margin: 0 0 2.5rem; color: #64748b; text-align: center; font-size: 0.875rem; }
        
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem; }
        .form-group input { width: 100%; padding: 0.875rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; box-sizing: border-box; font-size: 1rem; transition: border-color 0.2s; }
        .form-group input:focus { border-color: #3b82f6; }
        
        .btn-login { width: 100%; background: #3b82f6; color: white; border: none; padding: 1rem; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; transition: background 0.2s; margin-top: 1rem; }
        .btn-login:hover { background: #2563eb; }
        
        .error-msg { color: #ef4444; font-size: 0.825rem; font-weight: 600; margin-top: 0.5rem; display: block; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Welcome Back</h2>
    <p>Please enter your details to access the dashboard.</p>
    
    <form action="/admin/login" method="POST">
        @csrf
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
            @error('email') <span class="error-msg">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        
        <button type="submit" class="btn-login">Sign In</button>
    </form>
</div>

</body>
</html>
