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

        html,
        body {
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
            min-height: 100svh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px;
        }

        .card {
            width: min(520px, 100%);
            background: var(--paper);
            /* border: 2px solid var(--accent); */
            padding: clamp(22px, 3.5vh, 36px) clamp(20px, 4vw, 34px) clamp(18px, 3vh, 30px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: clamp(8px, 1.4vh, 10px);
            border: 3px solid rgba(122, 130, 111, 0.6);
            pointer-events: none;
            z-index: 0;
        }

        .card>* {
            position: relative;
            z-index: 1;
        }

        .logo {
            display: grid;
            justify-items: center;
            gap: clamp(8px, 1.6vh, 12px);
            margin-bottom: clamp(10px, 2vh, 16px);
        }

        .logo-img-wrap {
            width: clamp(110px, 22vmin, 150px);
            overflow: visible;
            display: grid;
            place-items: center;
        }

        .logo-img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
            object-position: center;
            max-height: clamp(100px, 10vmin, 88px);
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
            width: clamp(250px, 26vmin, 250px);
            height: clamp(250px, 26vmin, 250px);
        }

        .divider {
            margin: clamp(2px, 2.2vh, 2px) 0 clamp(10px, 2vh, 14px);
            display: grid;
            align-items: center;
            grid-template-columns: 1fr auto 1fr;
            gap: 14px;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 2px;
            background: rgba(85, 107, 47, 0.65);
        }

        .divider-dot {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 0;
            overflow: hidden;
            background: #556b2f;
        }

        .divider-dot svg {
            width: 19px;
            height: 19px;
            display: block;
            margin: 0;
            fill: none;
            stroke: #fff;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            transform: translate(2.0px, 0.5px);
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
            font-size: clamp(20px, 4.3vmin, 20px);
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

        .recipient {
            text-align: center;
            margin-top: clamp(10px, 2vh, 14px);
        }

        .recipient-label {
            color: var(--muted);
            font-size: 14px;
        }

        .recipient-name {
            margin-top: 5px;
            font-weight: 500;
            font-size: clamp(18px, 3.6vmin, 24px);
            letter-spacing: 0.01em;
            color: #556b2f;
        }

        .recipient-line {
            width: min(82%, 360px);
            margin: 14px auto 0;
            border-bottom: 2px dotted rgba(85, 107, 47, 0.55);
        }

        .notes {
            text-align: center;
            margin-top: clamp(5px, 2vh, 5px);
        }

        .notes-title {
            font-weight: 800;
            color: var(--ink);
            font-size: 16px;
            letter-spacing: 0.02em;
        }

        .notes-text {
            margin-top: 6px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.45;
        }

        .closing {
            text-align: center;
            margin-top: clamp(10px, 2vh, 14px);
        }

        .thanks {
            margin: 0;
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

        @media (max-width: 480px) {

            .card {
                padding: 14px 14px 18px;
            }

            .logo-img {
                width: 50px;
            }

            .title {
                font-size: 18px;
                letter-spacing: 0.06em;
            }

            .subtitle {
                font-size: 12px;
            }

            .qr img {
                width: 200px;
                height: 200px;
            }

            .recipient-name {
                font-size: 18px;
            }

            .event-name {
                font-size: 22px;
                line-height: 1.1;
            }

            .event-label {
                font-size: 13px;
            }

            .event-date {
                font-size: 12px;
            }

            /* 🔥 notes & closing lebih rapat tapi tetap enak */
            .notes-text {
                font-size: 12px;
            }

            .thanks {
                font-size: 11px;
                line-height: 1.4;
            }

            /* 🔥 divider lebih kecil */
            .divider-dot {
                width: 24px;
                height: 24px;
            }

            .divider-dot svg {
                width: 14px;
                height: 14px;
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
                        <img class="logo-img" src="{{ asset('templating/img/Logo-Weddora.png') }}" alt="Weddora">
                    </div>
                </div>

                <h1 class="title">BUKU TAMU DIGITAL</h1>
                <p class="subtitle">Scan untuk mengisi buku tamu</p>
            </header>

            <section class="qr" aria-label="QR Code">
                <img src="{{ $qr_url }}" alt="QR Code {{ $kode_token }}" width="240" height="240">
            </section>

            <section class="recipient">
                <div class="recipient-label">Kepada Yth:</div>
                <div class="recipient-name">{{ $guest?->nama_tamu ?? 'Tamu Undangan' }}</div>
            </section>

            <section class="notes">
                <div class="notes-title">Notes:</div>
                <div class="notes-text">
                    Mohon untuk membawa dan menunjukkan<br>
                    kartu ini ke penerima tamu.
                </div>
            </section>

            <div class="divider" aria-hidden="true">
                <div class="divider-dot">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path
                            d="M12 20.6s-7.2-4.4-9.5-8.5C.6 8.7 2.2 5.5 5.4 5.4c1.7 0 3.2.9 4.1 2.2.9-1.3 2.4-2.2 4.1-2.2 3.2.1 4.8 3.3 2.9 6.7-2.3 4.1-9.5 8.5-9.5 8.5z" />
                    </svg>
                </div>
            </div>

            <section class="event">
                <div class="event-label">The Wedding of</div>
                <div class="event-name">{!! str_replace('&amp;', '<span class="amp">&amp;</span>', e($event_name)) !!}</div>

                @if ($event_date)
                    <div class="event-date">{{ $event_date }}</div>
                @endif
            </section>

            <section class="closing">
                <p class="thanks">
                    Terima kasih atas doa, ucapan &amp; kehadirannya.<br>
                    Semoga Allah membalas kebaikan Anda.
                </p>
            </section>
        </main>
    </div>
</body>

</html>
