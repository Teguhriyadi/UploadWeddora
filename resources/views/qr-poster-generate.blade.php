<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Buku Tamu</title>
    <style>
        @page {
            size: 10cm 13cm;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 1181px;
            height: 1535px;
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
            width: 10cm;
            height: 13cm;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            width: 10cm;
            height: 13cm;
            position: relative;
            background: #fff;
            padding: 6mm 7mm;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 3mm;
            border: 1px solid rgba(122, 130, 111, 0.6);
            pointer-events: none;
        }

        /* LOGO 🔽 diperkecil */
        .logo {
            display: grid;
            justify-items: center;
            margin-bottom: 1.5mm;
        }

        .logo-img-wrap {
            width: 14mm;
            /* 🔥 sebelumnya lebih besar, sekarang diperkecil */
        }

        .logo-img {
            width: 100%;
            height: auto;
        }

        /* TITLE */
        .title {
            margin: 0;
            text-align: center;
            font-size: 12pt;
            color: #7a826f;
            font-weight: 800;
        }

        .subtitle {
            margin: 1mm 0 2mm;
            text-align: center;
            font-size: 8.5pt;
            color: #6b6f66;
        }

        /* QR 🔽 diperkecil */
        .qr {
            display: grid;
            justify-items: center;
            margin-top: 2mm;
        }

        .qr img {
            width: 32mm;
            height: 32mm;
        }

        .recipient {
            text-align: center;
            margin-top: 2mm;
        }

        .recipient-label {
            font-size: 8.5pt;
            color: #6b6f66;
        }

        .recipient-name {
            font-size: 11pt;
            font-weight: 600;
            color: #556b2f;
        }

        /* NOTES */
        .notes {
            text-align: center;
            margin-top: 2mm;
        }

        .notes-title {
            font-size: 8.5pt;
            font-weight: 700;
        }

        .notes-text {
            font-size: 8pt;
            color: #6b6f66;
            line-height: 1.25;
        }

        /* EVENT */
        .event {
            text-align: center;
            margin-top: 2mm;
        }

        .event-label {
            font-size: 8.5pt;
            color: #6b6f66;
        }

        .event-name {
            font-size: 12.5pt;
            font-weight: 600;
            color: #7a826f;
        }

        .event-date {
            font-size: 8.5pt;
            color: #6b6f66;
        }

        /* CLOSING */
        .closing {
            text-align: center;
            margin-top: 2mm;
        }

        .thanks {
            font-size: 8pt;
            color: #6b6f66;
            line-height: 1.25;
            margin: 0;
        }

        .divider {
            margin: 3mm 0;
            display: grid;
            align-items: center;
            grid-template-columns: 1fr auto 1fr;
            gap: 10px;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1.5px;
            background: rgba(85, 107, 47, 0.65);
        }

        .divider-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #556b2f;
            display: flex;
            padding-left: 2px;
            align-items: center;
            justify-content: center;
        }

        .divider-dot svg {
            width: 10px;
            height: 10px;
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
