<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Buku Tamu</title>
    <style>
        @page {
            size: 8.5cm 11cm;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 1004px;
            height: 1299px;
            overflow: hidden;
            background: #fff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #2d2f2a;
            font-kerning: none;
            text-rendering: optimizeLegibility;
        }

        .page {
            width: 8.5cm;
            height: 11cm;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            width: 8.5cm;
            height: 11cm;
            position: relative;
            background: #fff;
            padding: 4mm 5mm;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 2.5mm;
            border: 1px solid rgba(122, 130, 111, 0.6);
            pointer-events: none;
        }

        /* LOGO */
        .logo {
            display: grid;
            justify-items: center;
            margin-bottom: 1mm;
        }

        .logo-img-wrap {
            width: 11mm;
        }

        .logo-img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* TITLE */
        .title {
            margin: 0;
            text-align: center;
            font-size: 10pt;
            color: #7a826f;
            font-weight: 800;
            line-height: 1.15;
        }

        .subtitle {
            margin: 0.8mm 0 1.5mm;
            text-align: center;
            font-size: 7pt;
            color: #6b6f66;
            line-height: 1.25;
        }

        /* QR */
        .qr {
            display: grid;
            justify-items: center;
            margin-top: 1.5mm;
        }

        .qr img {
            width: 25mm;
            height: 25mm;
            object-fit: contain;
        }

        /* RECIPIENT */
        .recipient {
            text-align: center;
            margin-top: 1.5mm;
        }

        .recipient-label {
            font-size: 7pt;
            color: #6b6f66;
        }

        .recipient-name {
            font-size: 9pt;
            font-weight: 700;
            color: #556b2f;
            line-height: 1.2;
            margin-top: 0.8mm;
        }

        /* NOTES */
        .notes {
            text-align: center;
            margin-top: 1.5mm;
        }

        .notes-title {
            font-size: 7pt;
            font-weight: 700;
            margin-bottom: 0.8mm;
        }

        .notes-text {
            font-size: 6.3pt;
            color: #6b6f66;
            line-height: 1.3;
        }

        /* EVENT */
        .event {
            text-align: center;
            margin-top: 1.5mm;
        }

        .event-label {
            font-size: 7pt;
            color: #6b6f66;
        }

        .event-name {
            font-size: 10pt;
            font-weight: 700;
            color: #7a826f;
            line-height: 1.15;
            margin-top: 0.8mm;
        }

        .event-date {
            font-size: 7pt;
            color: #6b6f66;
            margin-top: 0.8mm;
        }

        /* CLOSING */
        .closing {
            text-align: center;
            margin-top: 1.5mm;
        }

        .thanks {
            font-size: 6.3pt;
            color: #6b6f66;
            line-height: 1.3;
            margin: 0;
        }

        /* DIVIDER */
        .divider {
            margin: 2mm 0;
            display: grid;
            align-items: center;
            grid-template-columns: 1fr auto 1fr;
            gap: 8px;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1px;
            background: rgba(85, 107, 47, 0.65);
        }

        .divider-dot {
            width: 13px;
            height: 13px;
            padding-left: 1px;
            border-radius: 50%;
            background: #556b2f;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .divider-dot svg {
            width: 7px;
            height: 7px;
            fill: none;
            stroke: #fff;
            stroke-width: 2;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
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
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=1111" alt="QR Code"
                    width="240" height="240">
            </section>

            <section class="recipient">
                <div class="recipient-label">Kepada Yth:</div>
                <div class="recipient-name">{{ $guest?->nama_undangan ?? 'Tamu Undangan' }}</div>
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
                <div class="event-name">{!! str_replace('&amp;', '<span class="amp">&amp;</span>', e($event_name->nama_event)) !!}</div>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    async function downloadCard() {

        const element = document.querySelector('.card');

        await document.fonts.ready;

        setTimeout(async () => {

            const canvas = await html2canvas(element, {
                scale: 5,
                useCORS: true,
                backgroundColor: "#ffffff"
            });

            const image = canvas.toDataURL("image/png");

            const link = document.createElement('a');
            const name = @json(\Illuminate\Support\Str::slug($guest->nama_undangan));

            link.href = image;
            link.download = `QR-${name}.png`;

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            setTimeout(() => {

                if (window.opener) {
                    window.close();
                } else {
                    window.location.href = document.referrer || '{{ url('/modules/guest') }}';
                }

            }, 800);

        }, 500);
    }

    window.addEventListener('load', downloadCard);
</script>

</html>
