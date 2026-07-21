<!DOCTYPE html>
<html>

<head>

    <title>Welcome Display</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            overflow: hidden;

            background: url('{{ asset('images/prewedding.jpg') }}');

            background-size: cover;

            background-position: center;

            height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;

            font-family: Arial;

        }

        body::before {

            content: '';

            position: fixed;

            inset: 0;

            background: rgba(0, 0, 0, .45);

            backdrop-filter: blur(8px);

        }

        #welcome-card {

            position: relative;

            z-index: 100;

            width: 750px;

            padding: 60px;

            border-radius: 25px;

            background: rgba(255, 255, 255, .12);

            backdrop-filter: blur(20px);

            text-align: center;

            color: white;

            opacity: 0;

            transform: translateY(100px);

            transition: .5s;

        }

        #welcome-card.show {

            opacity: 1;

            transform: translateY(0);

        }

        h1 {

            font-size: 58px;

            margin-bottom: 20px;

        }

        h2 {

            font-size: 48px;

            margin-bottom: 20px;

        }

        p {

            font-size: 25px;

        }
    </style>

</head>

<body>

    <div id="welcome-card">

        <h1>💍 SELAMAT DATANG</h1>

        <h2 id="guest-name">-</h2>

        <p>Terima kasih telah menghadiri acara kami.</p>

    </div>

    <script>
        let queue = [];

        let showing = false;

        Echo.channel('welcome-screen')

            .listen('.guest.checkedin', (e) => {

                queue.push(e.guest);

                processQueue();

            });

        function processQueue() {

            if (showing) return;

            if (queue.length == 0) return;

            showing = true;

            let guest = queue.shift();

            document.getElementById("guest-name").innerHTML = guest.nama;

            let card = document.getElementById("welcome-card");

            card.classList.add("show");

            setTimeout(() => {

                card.classList.remove("show");

                showing = false;

                processQueue();

            }, 5000);

        }
    </script>

</body>

</html>
