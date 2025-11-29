<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Aplikasi Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-card {
            background: #fff;
            padding: 24px 28px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            width: 340px;
        }
        .login-card h1 {
            margin: 0 0 16px;
            font-size: 20px;
            text-align: center;
            color: #111827;
        }
        .login-card label {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
            color: #374151;
        }
        .login-card input[type="text"],
        .login-card input[type="password"] {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }
        .login-card button {
            width: 100%;
            padding: 8px 10px;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }
        .login-card button:hover {
            background: #0b5ed7;
        }
        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 8px 10px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 12px;
        }
        .small-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="login-card">
    <h1>Login</h1>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('login.attempt') }}" method="POST">
        @csrf

        <label for="username">Username</label>
        <input type="text"
               id="username"
               name="username"
               value="{{ old('username') }}"
               required
               autofocus>

        <label for="password">Password</label>
        <input type="password"
               id="password"
               name="password"
               required>

        <button type="submit">Masuk</button>

        <div class="small-text">
            Masukkan username & password.
        </div>
    </form>
</div>
</body>
</html>
