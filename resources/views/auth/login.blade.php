<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Group 5</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&display=swap" rel="stylesheet">
    
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #fdf8f3; --surface: #ffffff; --border: rgba(168,144,110,0.3);
            --espresso: #1c0d00; --caramel: #c9873a; --text-primary: #2e1503; 
            --text-secondary: #7a5c3d; --radius-md: 12px; --radius-lg: 20px;
        }
        body {
            font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text-primary);
            display: flex; align-items: center; justify-content: center; min-height: 100vh;
        }
        .login-card {
            background: var(--surface); width: 100%; max-width: 400px;
            padding: 3rem 2rem; border-radius: var(--radius-lg);
            box-shadow: 0 20px 60px rgba(28,13,0,0.08); border: 1px solid var(--border);
            text-align: center;
        }
        .brand { font-family: 'Playfair Display', serif; font-size: 1.5rem; color: var(--espresso); margin-bottom: 0.5rem; }
        .brand em { color: var(--caramel); font-style: italic; }
        .desc { font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 2rem; }
        
        .field { text-align: left; margin-bottom: 1.5rem; }
        .field label { display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); letter-spacing: 0.05em; margin-bottom: 8px; }
        .field input {
            width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-md);
            font-family: 'DM Sans', sans-serif; font-size: 14px; outline: none; transition: 0.2s;
        }
        .field input:focus { border-color: var(--caramel); box-shadow: 0 0 0 3px rgba(201,135,58,0.15); }
        
        .btn-login {
            width: 100%; padding: 12px; background: var(--espresso); color: white;
            border: none; border-radius: 100px; font-weight: 700; font-size: 1rem;
            cursor: pointer; transition: 0.3s; margin-top: 1rem;
        }
        .btn-login:hover { background: var(--caramel); }
        
        .alert-error {
            background: #f8d7da; color: #721c24; padding: 10px; border-radius: var(--radius-md);
            font-size: 13px; margin-bottom: 1.5rem; text-align: left;
        }
        .back-link { display: inline-block; margin-top: 1.5rem; font-size: 13px; color: var(--text-secondary); text-decoration: none; }
        .back-link:hover { color: var(--caramel); }
    </style>
</head>
<body>

    <div class="login-card">
        <h1 class="brand">Group 5 × <em>Tagum</em></h1>
        <p class="desc">Enter your credentials to access the vault.</p>

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">Unlock Vault</button>
        </form>

        <a href="/" class="back-link">← Return to Public Site</a>
    </div>

</body>
</html>