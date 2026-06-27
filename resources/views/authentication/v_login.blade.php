<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | Login</title>

    <link href="{{ asset('templating/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('templating/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --login-primary: #1f4f46;
            --login-primary-soft: rgba(31, 79, 70, 0.14);
            --login-accent: #d7b56d;
            --login-text: #1f2937;
            --login-muted: #6b7280;
            --login-border: rgba(148, 163, 184, 0.24);
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: var(--login-text);
            background:
                linear-gradient(135deg, rgba(8, 15, 30, 0.82), rgba(17, 24, 39, 0.68)),
                url('{{ url("templating/img/BG_Login.jpg") }}') center/cover no-repeat;
        }

        .login-shell {
            min-height: 100vh;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-frame {
            width: 100%;
            max-width: 1180px;
            border-radius: 28px;
            overflow: hidden;
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(340px, 440px);
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        .login-hero {
            position: relative;
            padding: 56px 52px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 640px;
            color: #fff;
            background:
                linear-gradient(180deg, rgba(17, 24, 39, 0.28), rgba(17, 24, 39, 0.72));
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 999px;
            width: fit-content;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.02em;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .hero-title {
            margin: 20px 0 14px;
            font-family: 'Playfair Display', serif;
            font-size: clamp(36px, 4vw, 56px);
            line-height: 1.05;
            letter-spacing: -0.02em;
        }

        .hero-desc {
            max-width: 540px;
            font-size: 15px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.82);
        }

        .hero-points {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 34px;
        }

        .hero-point {
            padding: 16px 18px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .hero-point strong {
            display: block;
            font-size: 18px;
            margin-bottom: 4px;
        }

        .hero-point span {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.76);
        }

        .hero-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 42px;
            color: rgba(255, 255, 255, 0.72);
            font-size: 13px;
        }

        .login-panel {
            background: rgba(255, 255, 255, 0.96);
            padding: 42px 34px;
            display: flex;
            align-items: center;
        }

        .login-card {
            width: 100%;
        }

        .brand-mark {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            font-size: 20px;
            color: var(--login-primary);
            background: var(--login-primary-soft);
        }

        .login-heading {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #111827;
        }

        .login-subtitle {
            margin-bottom: 26px;
            color: var(--login-muted);
            line-height: 1.7;
        }

        .alert {
            border: 0;
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .icon-left {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
        }

        .form-control {
            height: 52px;
            border-radius: 16px;
            border: 1px solid var(--login-border);
            padding-left: 44px;
            padding-right: 44px;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: rgba(31, 79, 70, 0.45);
        }

        .toggle-password {
            position: absolute;
            right: 6px;
            top: 6px;
            width: 40px;
            height: 40px;
            border: 0;
            background: transparent;
            color: #6b7280;
            border-radius: 12px;
        }

        .toggle-password:hover {
            background: rgba(31, 79, 70, 0.08);
            color: var(--login-primary);
        }

        .login-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 4px 0 22px;
            color: var(--login-muted);
            font-size: 13px;
        }

        .login-meta .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(31, 79, 70, 0.08);
            color: var(--login-primary);
            font-weight: 600;
        }

        .btn-login {
            height: 52px;
            border: 0;
            border-radius: 16px;
            color: #fff;
            font-weight: 700;
            letter-spacing: 0.02em;
            background: linear-gradient(135deg, var(--login-primary), #295e54);
            box-shadow: 0 16px 30px rgba(31, 79, 70, 0.22);
        }

        .btn-login:hover {
            color: #fff;
            transform: translateY(-1px);
        }

        .panel-footer {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid rgba(148, 163, 184, 0.18);
            font-size: 13px;
            color: var(--login-muted);
            text-align: center;
        }

        @media (max-width: 991.98px) {
            .login-frame {
                grid-template-columns: 1fr;
                max-width: 620px;
            }

            .login-hero {
                min-height: auto;
                padding: 34px 28px 28px;
            }

            .hero-points {
                grid-template-columns: 1fr;
            }

            .hero-footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 575.98px) {
            .login-shell {
                padding: 14px;
            }

            .login-panel {
                padding: 28px 18px;
            }

            .login-hero {
                padding: 28px 18px 22px;
            }

            .login-heading {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-frame">
            <section class="login-hero">
                <div>
                    <div class="hero-badge">
                        <i class="fas fa-heart"></i>
                        <span>Wedding Invitation Dashboard</span>
                    </div>

                    <h1 class="hero-title">
                        Kelola tamu, check-in, dan operasional acara dalam satu tempat.
                    </h1>

                    <p class="hero-desc">
                        Tampilan dibuat sederhana, nyaman dibaca, dan cocok dipakai saat persiapan maupun hari acara.
                        Semua data penting tetap cepat diakses dari laptop, tablet, atau handphone.
                    </p>

                    <div class="hero-points">
                        <div class="hero-point">
                            <strong>Praktis</strong>
                            <span>Login cepat untuk petugas dan admin.</span>
                        </div>
                        <div class="hero-point">
                            <strong>Rapi</strong>
                            <span>Navigasi utama lebih fokus ke pekerjaan inti.</span>
                        </div>
                        <div class="hero-point">
                            <strong>Responsif</strong>
                            <span>Nyaman dibuka di berbagai ukuran layar.</span>
                        </div>
                    </div>
                </div>

                <div class="hero-footer">
                    <span>{{ env('APP_NAME') }}</span>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </section>

            <section class="login-panel">
                <div class="login-card">
                    <div class="brand-mark">
                        <i class="fas fa-feather-alt"></i>
                    </div>

                    <div class="login-heading">Selamat datang</div>
                    <div class="login-subtitle">
                        Masuk ke dashboard untuk memantau kehadiran tamu, check-in, dan kebutuhan operasional acara.
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            <strong>Berhasil</strong>, {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <strong>Gagal</strong>, {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/login') }}">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <div class="input-wrap">
                                <i class="fas fa-user icon-left"></i>
                                <input type="text" name="username" value="{{ old('username') }}"
                                    class="form-control @error('username') is-invalid @enderror"
                                    placeholder="Masukkan username">
                            </div>
                            @error('username')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div class="input-wrap">
                                <i class="fas fa-lock icon-left"></i>
                                <input type="password" id="passwordInput" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password">
                                <button type="button" class="toggle-password" id="togglePassword" aria-label="Lihat password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="login-meta">
                            <span class="status-chip">
                                <i class="fas fa-shield-alt"></i>
                                Akses aman
                            </span>
                            <span>Gunakan akun petugas atau admin</span>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-login">Masuk ke Dashboard</button>
                        </div>
                    </form>

                    <div class="panel-footer">
                        © 2026 {{ env('APP_NAME') }}. Dirancang agar tetap sederhana, modern, dan mudah dipakai.
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('passwordInput');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword?.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            this.innerHTML = isPassword
                ? '<i class="fas fa-eye-slash"></i>'
                : '<i class="fas fa-eye"></i>';
        });
    </script>
</body>
</html>
