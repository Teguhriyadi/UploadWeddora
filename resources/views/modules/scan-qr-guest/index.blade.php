@extends('layouts.master')

@push('title-modules', 'Scan QR Code Tamu')

@push('style-css')
    <style>
        .flash {
            position: absolute;
            width: 100%;
            height: 100%;
            background: white;
            top: 0;
            left: 0;
            opacity: 0;
            pointer-events: none;
        }

        .card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 30px;
        }

        .camera-wrapper {
            width: 100%;
            max-width: 500px;
            margin: auto;
            border-radius: 20px;
            overflow: hidden;
            border: 5px solid white;
            background: #000;
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .camera-wrapper::after {
            content: "📸 Siap ambil foto";
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 14px;
            background: rgba(0, 0, 0, 0.5);
            padding: 5px 10px;
            border-radius: 10px;
        }

        #video {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }

        #reader {
            width: 100%;
            max-width: 500px;
            margin: auto;
            min-height: 350px;
            border-radius: 15px;
            overflow: hidden;
            border: 3px dashed #0d6efd;
            padding: 10px;
        }

        #countdown {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 80px;
            color: white;
            display: none;
            font-weight: bold;
        }

        #previewSelfie img {
            margin-top: 15px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
            border: 4px solid #0d6efd;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        #btnSelfie {
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: bold;
            transition: 0.3s;
        }

        #btnSelfie:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .step-badge {
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 20px;
        }
    </style>
@endpush

@push('content-modules')
    <div class="card shadow">
        <div class="card-body text-center">

            <h3 class="fw-bold text-primary mb-1">📷 Scan QR Tamu</h3>
            <p class="text-muted mb-3">Silakan ambil selfie lalu scan QR Code</p>

            <div id="cameraArea">
                <div class="camera-wrapper">
                    <video id="video" autoplay playsinline></video>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <div id="countdown"></div>
                    <div class="flash" id="flash"></div>
                </div>
            </div>

            <div id="previewSelfie"></div>

            <button id="btnSelfie" class="btn btn-primary mt-3" onclick="takeSelfie()">
                <i class="fas fa-camera"></i> Ambil Selfie
            </button>

            <hr>

            <h5 class="mt-3">🔍 Scan QR Code</h5>
            <p class="text-muted">Arahkan kamera ke QR Code tamu</p>

            <video id="reader" style="width:100%; max-width:500px; border-radius:15px;"></video>

            <input type="hidden" id="selfie">

        </div>
    </div>

    <audio id="shutterSound" preload="auto">
        <source src="{{ asset('templating/sound/sound-selfie.mp3') }}" type="audio/mpeg">
    </audio>
@endpush

@push('style-js')
    <script src="https://unpkg.com/@zxing/library@latest"></script>

    <script type="text/javascript">
        let video = document.getElementById('video');
        let canvas = document.getElementById('canvas');

        let cameraReady = false;
        let currentStream = null;

        let codeReader = null;
        let scanning = false;
        let takingPhoto = false;

        function startCamera() {
            cameraReady = false;

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "user"
                    }
                })
                .then(stream => {
                    currentStream = stream;
                    video.srcObject = stream;

                    video.onloadedmetadata = () => {
                        video.play();
                        cameraReady = true;
                    };
                });
        }

        function stopCamera() {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }
        }

        function takeSelfie() {

            let sound = document.getElementById("shutterSound");

            sound.play().then(() => {
                sound.pause();
                sound.currentTime = 0;
            }).catch(() => {});

            let selfie = document.getElementById("selfie").value;

            if (selfie) {
                resetSelfie();
                return;
            }

            if (!cameraReady) {
                Swal.fire('Tunggu', 'Kamera belum siap', 'warning');
                return;
            }

            if (takingPhoto) return;

            takingPhoto = true;

            let count = 3;
            let el = document.getElementById("countdown");

            el.style.display = "block";
            el.innerText = count;

            let timer = setInterval(() => {
                count--;

                if (count > 0) {
                    el.innerText = count;
                } else {
                    clearInterval(timer);
                    el.style.display = "none";
                    capturePhoto();
                    takingPhoto = false;
                }
            }, 1000);
        }

        function capturePhoto() {

            let ctx = canvas.getContext("2d");

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            let sound = document.getElementById("shutterSound");
            sound.currentTime = 0;
            sound.play().catch(() => {});
            let image = canvas.toDataURL("image/png");

            document.getElementById("selfie").value = image;

            document.getElementById("previewSelfie").innerHTML =
                `<img src="${image}">`;

            stopCamera();

            document.getElementById("cameraArea").style.display = "none";
        }

        function resetSelfie() {
            document.getElementById("selfie").value = "";
            document.getElementById("previewSelfie").innerHTML = "";
            document.getElementById("cameraArea").style.display = "block";

            setTimeout(() => {
                startCamera();
            }, 500);
        }

        function startScanner() {

            codeReader = new ZXing.BrowserQRCodeReader();

            codeReader.getVideoInputDevices().then(videoInputDevices => {

                let selectedDeviceId = videoInputDevices[videoInputDevices.length - 1].deviceId;

                codeReader.decodeFromVideoDevice(selectedDeviceId, 'reader', (result, err) => {

                    if (result && scanning) {
                        onScanSuccess(result.text);
                    }

                });

                scanning = true;
            });
        }

        function stopScanner() {
            if (codeReader && scanning) {
                codeReader.reset();
                scanning = false;
            }
        }

        function onScanSuccess(decodedText) {

            if (!scanning) return;

            let selfie = document.getElementById("selfie").value;

            if (!selfie) {
                Swal.fire('Oops', 'Ambil selfie dulu ya', 'warning');
                return;
            }

            stopScanner();

            fetch("{{ url('/modules/scan-qr-guest') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        kode_token: decodedText,
                        selfie: selfie
                    })
                })
                .then(res => res.json())
                .then(data => {

                    if (data.status === "success") {
                        Swal.fire({
                            title: 'Scan Kehadiran Berhasil',
                            text: `🎉 Terima Kasih ${data.nama} Sudah Hadir`,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }

                    setTimeout(() => {
                        resetSelfie();
                        startScanner();
                    }, 2000);
                });
        }

        startCamera();
        startScanner();
    </script>
@endpush
