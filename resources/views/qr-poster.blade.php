<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Buku Tamu</title>
    <style>
        :root {
            --ink: #2d2f2a;
            --muted: #6b6f66;
            --paper: #ffffff;
            --bg: #f6f2ec;
            --accent: #7a826f;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
            color: var(--ink);
            background: var(--bg);
        }

        .page {
            height: 100svh;
            display: grid;
            place-items: center;
            padding: clamp(12px, 2.5vh, 18px);
        }

        .card {
            width: min(520px, 100%);
            background: var(--paper);
            border: 2px solid var(--accent);
            padding: clamp(22px, 3.5vh, 36px) clamp(20px, 4vw, 34px) clamp(18px, 3vh, 30px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: clamp(8px, 1.4vh, 10px);
            border: 1px solid rgba(122, 130, 111, 0.6);
            pointer-events: none;
        }

        .logo {
            display: grid;
            justify-items: center;
            gap: clamp(8px, 1.6vh, 12px);
            margin-bottom: clamp(10px, 2vh, 16px);
        }

        .logo-img-wrap {
            width: clamp(92px, 20vmin, 120px);
            height: clamp(34px, 6vmin, 48px);
            overflow: hidden;
            display: grid;
            place-items: center;
        }

        .logo-img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center 20%;
        }

        .logo-name {
            display: none;
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
            letter-spacing: 0.02em;
        }

        .qr {
            margin-top: clamp(14px, 2.6vh, 26px);
            display: grid;
            justify-items: center;
            gap: 10px;
        }

        .qr img {
            width: clamp(160px, 30vmin, 240px);
            height: clamp(160px, 30vmin, 240px);
        }

        .divider {
            margin: clamp(12px, 2.2vh, 18px) 0 clamp(10px, 2vh, 14px);
            display: grid;
            align-items: center;
            grid-template-columns: 1fr auto 1fr;
            gap: 14px;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1px;
            background: rgba(122, 130, 111, 0.55);
        }

        .divider-dot {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            border: 1px solid rgba(122, 130, 111, 0.7);
            display: grid;
            place-items: center;
            overflow: hidden;
            background: #fff;
        }

        .divider-dot img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            object-position: center;
        }

        .event {
            text-align: center;
            margin-bottom: 16px;
        }

        .event-label {
            color: var(--muted);
            font-size: 15px;
            letter-spacing: 0.03em;
        }

        .event-name {
            margin-top: 6px;
            font-family: ui-serif, Georgia, "Times New Roman", Times, serif;
            font-size: clamp(32px, 5.6vmin, 46px);
            line-height: 1.02;
            letter-spacing: 0.01em;
            color: var(--accent);
            font-weight: 500;
        }

        .event-name .amp {
            font-size: 0.82em;
            font-weight: 400;
        }

        .ornament {
            margin-top: clamp(8px, 1.6vh, 10px);
            display: grid;
            justify-items: center;
        }

        .ornament svg {
            width: clamp(150px, 34vw, 190px);
            height: 22px;
            display: block;
        }

        .ornament path,
        .ornament circle {
            stroke: rgba(122, 130, 111, 0.85);
        }

        .ornament circle {
            fill: rgba(122, 130, 111, 0.15);
        }

        .event-date {
            margin-top: clamp(8px, 1.8vh, 12px);
            color: var(--muted);
            font-size: 15px;
            letter-spacing: 0.02em;
        }

        .guest {
            text-align: center;
            margin-top: 10px;
        }

        .guest-label {
            color: var(--muted);
            font-size: 14px;
        }

        .guest-name {
            margin-top: 8px;
            font-weight: 800;
            font-size: clamp(20px, 4.4vmin, 30px);
            letter-spacing: 0.01em;
            color: var(--ink);
        }

        .guest-family {
            margin-top: 4px;
            color: var(--muted);
            font-size: 15px;
        }

        .thanks {
            margin: 14px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.55;
        }

        @media print {
            body {
                background: #fff;
            }

            .page {
                padding: 0;
            }

            .card {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <main class="card">
            <header>
                <div class="logo">
                    <div class="logo-img-wrap">
                        <img class="logo-img" src="{{ asset('templating/img/Weddora-Logo.jpeg') }}" alt="Weddora">
                    </div>
                </div>

                <h1 class="title">BUKU TAMU DIGITAL</h1>
                <p class="subtitle">Scan untuk mengisi buku tamu</p>
            </header>

            <section class="qr" aria-label="QR Code">
                <img src="{{ $qr_url }}" alt="QR Code {{ $kode_token }}" width="240" height="240">
            </section>

            <div class="divider" aria-hidden="true">
                <div class="divider-dot">
                    <img src="{{ asset('templating/img/Weddora-Logo.jpeg') }}" alt="" aria-hidden="true">
                </div>
            </div>

            <section class="event">
                <div class="event-label">The Wedding of</div>
                <div class="event-name">{!! str_replace('&amp;', '<span class="amp">&amp;</span>', e($event_name)) !!}</div>
                {{-- <div class="ornament" aria-hidden="true">
                    <svg viewBox="0 0 220 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="">
                        <path d="M12 12C38 12 44 4 58 4C72 4 78 12 96 12C114 12 120 4 134 4C148 4 154 12 180 12" stroke-width="1.4" stroke-linecap="round"/>
                        <path d="M12 12C38 12 44 20 58 20C72 20 78 12 96 12C114 12 120 20 134 20C148 20 154 12 180 12" stroke-width="1.4" stroke-linecap="round"/>
                        <circle cx="196" cy="12" r="3.2" stroke-width="1.2"/>
                        <circle cx="196" cy="12" r="1.3" stroke-width="0"/>
                        <circle cx="196" cy="12" r="1.3" fill="rgba(122, 130, 111, 0.75)"/>
                        <circle cx="196" cy="6.2" r="2.1" stroke-width="1.1"/>
                        <circle cx="196" cy="17.8" r="2.1" stroke-width="1.1"/>
                        <circle cx="190.6" cy="12" r="2.1" stroke-width="1.1"/>
                        <circle cx="201.4" cy="12" r="2.1" stroke-width="1.1"/>
                    </svg>
                </div> --}}
                @if($event_date)
                    <div class="event-date">{{ $event_date }}</div>
                @endif
            </section>

            <section class="guest">
                <div class="guest-label">Kepada Yth:</div>
                <div class="guest-name">{{ $guest?->nama_tamu ?? 'Fatur Muhammad' }}</div>
                @if($guest?->keluarga)
                    <div class="guest-family">{{ $guest->keluarga }}</div>
                @endif
                <p class="thanks">
                    Terima kasih atas doa, ucapan &amp; kehadirannya.<br>
                    Semoga Allah membalas kebaikan Anda.
                </p>
            </section>
        </main>
    </div>
</body>
</html>
