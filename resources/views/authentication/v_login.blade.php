<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ config('app.name') }} | Login
    </title>

    <link href="{{ asset('templating/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Google Font Wedding -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Poppins:wght@300;400&display=swap"
        rel="stylesheet">

    <style>
        body {
            height: 100vh;
            background: url('https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070') center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.45);
        }

        .login-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 35px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .wedding-title {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 600;
        }

        .form-control {
            height: 45px;
            border-radius: 8px;
        }

        .btn-login {
            height: 45px;
            background: #d4af37;
            border: none;
            font-weight: 600;
        }

        .btn-login:hover {
            background: #c59d2f;
        }

        .footer-text {
            font-size: 13px;
            color: #888;
        }

        .divider {
            margin: 15px 0;
            border-top: 1px solid #eee;
        }
    </style>

</head>

<body>

    <div class="overlay"></div>

    <div class="login-card">

        @if (session('success'))
            <div class="alert alert-success">
                <strong>Berhasil</strong>, {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <strong>Gagal,</strong> {{ session('error') }}
            </div>
        @endif

        <div class="text-center mb-4">

            <div class="wedding-title">
                Welcome to Our Wedding
            </div>

            <p class="text-muted">
                {{ env('APP_NAME') }}
            </p>

        </div>

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" value="{{ old('username') }}"
                    class="form-control @error('username') is-invalid @enderror" placeholder="Masukkan Username">

                @error('username')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Masukkan Password">

                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-grid">
                <button class="btn btn-login text-white">
                    LOGIN
                </button>
            </div>

        </form>

        <div class="divider"></div>

        <div class="text-center footer-text">
            © 2026 {{ env('APP_NAME') }} Wedding Invitation
        </div>

    </div>

</body>

</html>
