<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generate Semua QR</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        @page {
            size: 6cm 11cm;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 6cm;
            height: 11cm;
            overflow: hidden;
            background: #fff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #2d2f2a;
        }

        .page {
            width: 6cm;
            height: 11cm;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            width: 6cm;
            height: 11cm;
            position: relative;
            background: #fff;
            padding: 3.5mm 3.5mm;
            overflow: hidden;

            display: flex;
            flex-direction: column;
            justify-content: space-between;

            max-width: 6cm;
            max-height: 11cm;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 2mm;
            border: 1px solid rgba(122, 130, 111, 0.6);
            pointer-events: none;
        }

        /* HEADER */
        .logo {
            display: grid;
            justify-items: center;
            margin-bottom: 0.6mm;
        }

        .logo-img-wrap {
            width: 15mm;
        }

        .logo-img {
            width: 100%;
        }

        .title {
            margin: 0;
            text-align: center;
            font-size: 10pt;
            color: #7a826f;
            font-weight: 800;
        }

        .subtitle {
            margin: 0.4mm 0 0.8mm;
            text-align: center;
            font-size: 7pt;
            color: #6b6f66;
        }

        /* QR */
        .qr {
            display: grid;
            justify-items: center;
            margin-top: 0.8mm;
        }

        .qr img {
            width: 30mm;
            height: 30mm;
        }

        /* RECIPIENT */
        .recipient {
            text-align: center;
            margin-top: 0.8mm;
        }

        .recipient-label {
            font-size: 5.8pt;
            color: #6b6f66;
        }

        .recipient-name {
            font-size: 8.5pt;
            font-weight: 700;
            color: #556b2f;
        }

        /* NOTES */
        .notes {
            text-align: center;
            margin-top: 0.8mm;
        }

        .notes-title {
            font-size: 5.8pt;
            font-weight: 700;
        }

        .notes-text {
            font-size: 6pt;
            color: #6b6f66;
        }

        /* DIVIDER */
        .divider {
            margin: 1.2mm 0;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1px;
            background: rgba(85, 107, 47, 0.65);
        }

        .divider-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #556b2f;
            display: flex;
            padding-left: 1px;
            align-items: center;
            justify-content: center;
        }

        .divider-dot svg {
            width: 5px;
            height: 5px;
            fill: none;
            stroke: #fff;
            stroke-width: 2;
        }

        /* EVENT */
        .event {
            text-align: center;
            margin-top: 0.8mm;
        }

        .event-label {
            font-size: 7pt;
            color: #6b6f66;
        }

        .event-name {
            font-size: 12pt;
            font-weight: 700;
            color: #7a826f;
        }

        .event-date {
            font-size: 8pt;
            color: #6b6f66;
        }

        /* CLOSING */
        .closing {
            text-align: center;
            margin-top: 1mm;
        }

        .thanks {
            font-size: 6pt;
            color: #6b6f66;
        }

        /* BUTTON */
        .control {
            padding: 10px;
        }

        button {
            padding: 10px 15px;
            background: #556b2f;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .hidden {
            display: none;
        }

        @media print {

            html,
            body {
                width: 6cm;
                height: 11cm;
            }

            .card {
                width: 6cm;
                height: 11cm;
            }
        }
    </style>
</head>

<body>

    <script>
        const guests = @json($guests);
        const eventName = @json($event_name->nama_event);
        const eventDate = @json($event_date);

        function buildCard(g) {
            return `
        <div class="page">
            <main class="card">

                <header>
                    <div class="logo">
                        <div class="logo-img-wrap">
                            <img class="logo-img" src="{{ asset('templating/img/Logo-Weddora.png') }}">
                        </div>
                    </div>

                    <h1 class="title">BUKU TAMU DIGITAL</h1>
                    <p class="subtitle">Scan untuk mengisi buku tamu</p>
                </header>

                <section class="qr">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=${encodeURIComponent(g.kode_token)}">
                </section>

                <section class="recipient">
                    <div class="recipient-label">Kepada Yth:</div>
                    <div class="recipient-name">${g.nama_undangan}</div>
                </section>

                <section class="notes">
                    <div class="notes-title">Notes:</div>
                    <div class="notes-text">
                        Mohon untuk membawa dan menunjukkan<br>
                        kartu ini ke penerima tamu.
                    </div>
                </section>

                <div class="divider">
                    <div class="divider-dot">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20.6s-7.2-4.4-9.5-8.5C.6 8.7 2.2 5.5 5.4 5.4c1.7 0 3.2.9 4.1 2.2.9-1.3 2.4-2.2 4.1-2.2 3.2.1 4.8 3.3 2.9 6.7-2.3 4.1-9.5 8.5-9.5 8.5z"/>
                        </svg>
                    </div>
                </div>

                <section class="event">
                    <div class="event-label">The Wedding of</div>
                    <div class="event-name">${eventName}</div>
                    <div class="event-date">${eventDate}</div>
                </section>

                <section class="closing">
                    <p class="thanks">
                        Terima kasih atas doa, ucapan &amp; kehadirannya.<br>
                        Semoga Allah membalas kebaikan Anda.
                    </p>
                </section>

            </main>
        </div>`;
        }

        async function generateAll() {

            for (let i = 0; i < guests.length; i++) {

                const wrapper = document.createElement('div');
                wrapper.innerHTML = buildCard(guests[i]);
                document.body.appendChild(wrapper);

                const card = wrapper.querySelector('.card');

                await document.fonts.ready;
                await new Promise(r => setTimeout(r, 200));

                const scale = 3.125;

                const canvas = await html2canvas(card, {
                    scale: scale,
                    useCORS: true,
                    backgroundColor: "#fff",
                    width: card.offsetWidth,
                    height: card.offsetHeight,
                    windowWidth: card.offsetWidth,
                    windowHeight: card.offsetHeight
                });

                const img = canvas.toDataURL("image/png");

                const link = document.createElement('a');
                link.href = img;
                link.download = `QR-${guests[i].nama_undangan}.png`;

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                document.body.removeChild(wrapper);

                await new Promise(r => setTimeout(r, 400));
            }

            alert("Selesai generate semua QR");
        }

        window.addEventListener("load", () => {
            setTimeout(() => {
                generateAll();
            }, 500);
        });
    </script>

</body>

</html>
