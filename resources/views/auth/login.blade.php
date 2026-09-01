<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Eclair Beauty Clinic</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f0eb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-title {
            text-align: center;
            margin-bottom: 8px;
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        .login-subtitle {
            text-align: center;
            color: #888;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border 0.3s;
        }

        input:focus {
            border-color: #c9956e;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #c9956e;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background-color: #b07d58;
        }

        .error-message {
            background-color: #ffe0e0;
            color: #cc0000;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .clinic-name {
            text-align: center;
            color: #c9956e;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <p class="clinic-name">Eclair Beauty Clinic</p>
        <h2 class="login-title">Selamat Datang</h2>
        <p class="login-subtitle">Silakan login untuk melanjutkan</p>

        {{-- Tampilkan error kalau ada --}}
        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.proses') }}">
            @csrf

            <div class="form-group">
                <label>Username</label>
                <input
                    type="text"
                    name="username"
                    placeholder="Masukkan username"
                    value="{{ old('username') }}"
                    required
                >
                @error('username')
                    <small style="color:red">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >
                @error('password')
                    <small style="color:red">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-login">Masuk</button>
        </form>
    </div>
</body>
</html>
