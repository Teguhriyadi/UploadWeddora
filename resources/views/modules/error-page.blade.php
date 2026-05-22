<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Buku Tamu - Error</title>

    <style>
        :root {
            --ink: #2d2f2a;
            --muted: #6b6f66;
            --paper: #ffffff;
            --bg: #f6f2ec;
            --accent: #7a826f;
            --danger: #b24a4a;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
            color: var(--ink);
            background: var(--bg);
        }

        .page {
            min-height: 100svh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px;
        }

        .card {
            width: min(520px, 100%);
            background: var(--paper);
            padding: clamp(22px, 3.5vh, 36px) clamp(20px, 4vw, 34px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: clamp(8px, 1.4vh, 10px);
            border: 3px solid rgba(122, 130, 111, 0.6);
            pointer-events: none;
        }

        .card > * {
            position: relative;
            z-index: 1;
        }

        /* ================= HEADER (TETAP) ================= */
        .logo {
            display: grid;
            justify-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .logo-img-wrap {
            width: clamp(110px, 22vmin, 150px);
            display: grid;
            place-items: center;
        }

        .logo-img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        .title {
            margin: 0;
            text-align: center;
            font-size: clamp(22px, 3.8vw, 28px);
            letter-spacing: 0.08em;
            color: var(--accent);
            font-weight: 800;
        }

        .subtitle {
            margin: 6px 0 0;
            text-align: center;
            color: var(--muted);
            font-size: 14px;
        }

        /* ================= ERROR CONTENT ================= */

        .error {
            text-align: center;
            margin-top: 20px;
            padding: 10px 0 0;
        }

        .error-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 10px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: rgba(178, 74, 74, 0.12);
            color: var(--danger);
            font-size: 28px;
            font-weight: 700;
        }

        .error-title {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: var(--danger);
        }

        .error-message {
            margin-top: 8px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5;
        }

        .error-box {
            margin-top: 16px;
            padding: 12px 14px;
            background: rgba(178, 74, 74, 0.06);
            border: 1px dashed rgba(178, 74, 74, 0.35);
            border-radius: 10px;
            font-size: 13px;
            color: var(--ink);
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
            background: var(--accent);
            color: white;
            font-size: 14px;
            text-decoration: none;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* PRINT */
        @media print {
            body {
                background: #fff;
            }

            .card {
                box-shadow: none;
            }
        }

        /* MOBILE */
        @media (max-width: 480px) {
            .error-title {
                font-size: 18px;
            }

            .error-message {
                font-size: 12px;
            }

            .error-box {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <main class="card">

            <!-- HEADER (dipertahankan) -->
            <header>
                <div class="logo">
                    <div class="logo-img-wrap">
                        <img class="logo-img" src="{{ asset('templating/img/Logo-Weddora.png') }}" alt="Weddora">
                    </div>
                </div>

                <h1 class="title">BUKU TAMU DIGITAL</h1>
                <p class="subtitle">Wedding Guest System</p>
            </header>

            <!-- ERROR CONTENT -->
            <section class="error">
                <div class="error-icon">!</div>

                <h2 class="error-title">Data Tidak Ditemukan</h2>

                <p class="error-message">
                    QR Code atau link yang Anda akses tidak valid,
                    atau tidak terdaftar dalam sistem.
                </p>

                <div class="error-box">
                    Silakan pastikan kembali QR Code yang Anda scan berasal dari undangan resmi.
                </div>
            </section>

        </main>
    </div>
</body>

</html>
