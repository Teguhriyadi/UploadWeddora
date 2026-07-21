<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Display</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            overflow: hidden;
            width: 100vw;
            height: 100vh;

            background: linear-gradient(135deg,
                    #4A0404 0%,
                    #7B1113 35%,
                    #A61C1C 70%,
                    #C62828 100%);

            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;

            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, .08), transparent 35%),
                radial-gradient(circle at bottom right, rgba(255, 255, 255, .05), transparent 40%);
        }

        #standby {

            position: absolute;

            z-index: 1;

            color: white;

            text-align: center;

            transition: .5s;
        }

        #standby h1 {

            font-size: 75px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        #standby h3 {

            margin-top: 20px;

            font-size: 35px;

            font-weight: 300;
        }

        #standby p {

            margin-top: 15px;

            font-size: 22px;

            opacity: .8;
        }

        #welcome-card {
            display: none;
            position: relative;
            z-index: 100;

            width: 900px;
            padding: 60px;

            border-radius: 30px;

            background: rgba(255, 255, 255, .12);

            border: 1px solid rgba(255, 255, 255, .18);

            backdrop-filter: blur(20px);

            box-shadow:
                0 20px 60px rgba(0, 0, 0, .35),
                inset 0 1px 1px rgba(255, 255, 255, .15);

            color: white;

            text-align: center;

            opacity: 0;
            transform: translateY(120px) scale(.9);
            transition: all .5s ease;
        }

        #welcome-card.show {

            opacity: 1;

            transform: translateY(0) scale(1);
        }

        .icon {

            font-size: 60px;

            margin-bottom: 15px;
        }

        .title {

            font-size: 55px;

            font-weight: 700;

            margin-bottom: 15px;
        }

        .guest {
            font-size:80px;
            font-weight:700;
            margin: 25px 0;
            min-height: 80px;
        }
        .thanks {
            font-size: 28px;
            line-height: 45px;
            opacity: .95;
        }
        .line {

            width: 200px;

            height: 2px;

            background: rgba(255, 255, 255, .4);

            margin: 35px auto;
        }

        .couple {

            font-size: 38px;

            font-weight: 600;
        }

        .date {

            margin-top: 12px;

            font-size: 22px;

            opacity: .8;
        }

        /* ===========================
           FOOTER
        ============================ */

        .footer {

            position: absolute;

            bottom: 30px;

            width: 100%;

            text-align: center;

            color: white;

            font-size: 18px;

            opacity: .7;
        }

        /* ===========================
           ANIMATION
        ============================ */

        @keyframes pulse {

            0% {

                transform: scale(.9);

            }

            50% {

                transform: scale(1.05);

            }

            100% {

                transform: scale(1);

            }

        }

        .pulse {

            animation: pulse .6s;
        }
    </style>

</head>

<body>
    <div id="standby">
        <h1>💍</h1>
        <h1>
            {{ $event->nama_event }}
        </h1>
        <h3>Wedding Reception</h3>
        <p>Selamat datang di acara pernikahan kami</p>
    </div>
    <div id="welcome-card">
        <div class="icon">
            🎉
        </div>
        <div class="title">
            SELAMAT DATANG
        </div>
        <div class="guest" id="guest-name">
            -
        </div>
        <div class="thanks">
            Terima kasih telah hadir
            <br>
            Semoga momen ini menjadi kenangan indah
        </div>
        <div class="line"></div>
        <div class="couple">
            {{ $event->nama_event }}
        </div>
        <div class="date">
            21 November 2026
        </div>
    </div>
    <div class="footer">
        Powered by Weddora Digital Guestbook
    </div>

    <script>
        let lastId = 0;

        let queue = [];

        let showing = false;

        async function polling() {
            try {
                const response = await fetch("{{ route('welcome.latest') }}?last_id=" + lastId, {
                    headers: {
                        "Accept": "application/json"
                    }
                });
                const data = await response.json();
                if (data.status) {
                    queue.push(data.guest);
                    lastId = data.last_id;
                    processQueue();
                }
            } catch (error) {
                console.error(error);
            } finally {
                setTimeout(polling, 700);
            }
        }

        function processQueue() {
            if (showing) return;
            if (queue.length === 0) return;
            showing = true;
            let guest = queue.shift();
            showGuest(guest);
        }

        function showGuest(guest) {

            const standby = document.getElementById("standby");
            const card = document.getElementById("welcome-card");
            const guestName = document.getElementById("guest-name");

            guestName.textContent = guest.nama;

            standby.style.opacity = "0";

            card.style.display = "block";

            requestAnimationFrame(() => {
                card.classList.add("show");
                card.classList.add("pulse");
            });

            setTimeout(() => {
                card.classList.remove("pulse");
            }, 700);

            setTimeout(() => {

                card.classList.remove("show");

                setTimeout(() => {
                    card.style.display = "none";
                }, 500);

                standby.style.opacity = "1";

                showing = false;

                processQueue();

            }, 5000);
        }
        polling();
    </script>
</body>

</html>
