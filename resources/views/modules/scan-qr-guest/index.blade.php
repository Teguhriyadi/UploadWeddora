@extends('layouts.master')

@push('title-modules', 'Scan QR Code Tamu')

@push('style-css')
    <style>
        .card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .step-box {
            background: #f8f9fc;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .badge-step {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        #reader {
            width: 100%;
            height: 320px;
            background: #000;
            border-radius: 15px;
        }

        #previewSelfie img {
            margin-top: 10px;
            width: 100%;
            max-width: 250px;
            border-radius: 15px;
            border: 3px solid #0d6efd;
        }

        .hidden {
            display: none;
        }
    </style>
@endpush

@push('content-modules')
    <div class="card shadow">
        <div class="card-body text-center">

            <h3 class="fw-bold text-primary">📷 Scan QR Tamu</h3>
            <p class="text-muted">Scan QR dulu, selfie opsional, lalu simpan</p>

            {{-- STEP 1 --}}
            <div class="step-box">
                <span class="badge bg-primary badge-step">Step 1</span>
                <h5 class="mt-2">Scan QR Code</h5>
                <video id="reader" autoplay playsinline muted></video>
            </div>

            {{-- RESULT QR --}}
            <div id="qrResult" class="hidden alert alert-success">
                QR Terdeteksi: <b id="qrText"></b>
            </div>

            {{-- STEP 2 --}}
            <div id="selfieSection" class="step-box hidden">
                <span class="badge bg-warning text-dark badge-step">Step 2 (Opsional)</span>
                <h5 class="mt-2">Selfie (Opsional)</h5>

                <button class="btn btn-outline-primary btn-sm" onclick="openSelfie()">
                    Ambil Selfie
                </button>

                <div id="selfieBox" class="mt-2"></div>
            </div>

            {{-- STEP 3 --}}
            <div id="finalSection" class="step-box hidden">
                <span class="badge bg-success badge-step">Step 3</span>

                <button class="btn btn-success mt-2" onclick="submitData()">
                    Simpan Kehadiran
                </button>
            </div>

            <input type="hidden" id="selfie">

        </div>
    </div>

    <video id="video" autoplay playsinline class="hidden"></video>
    <canvas id="canvas" class="hidden"></canvas>

    <audio id="shutterSound">
        <source src="{{ asset('templating/sound/sound-selfie.mp3') }}">
    </audio>
@endpush

@push('style-js')
    <script src="https://unpkg.com/@zxing/library@latest"></script>

    <script>
        let codeReader = null;
        let scanning = false;
        let scannedData = null;

        let video = document.getElementById('video');
        let canvas = document.getElementById('canvas');

        let currentStream = null;

        /* =========================
           START SCANNER (SAFE MODE)
        ========================= */
        async function startScanner() {

            try {

                stopScanner();

                scanning = true;
                codeReader = new ZXing.BrowserQRCodeReader();

                // 🔥 WAJIB: unlock permission dulu (ini yang sering bikin error)
                await navigator.mediaDevices.getUserMedia({
                    video: true
                });

                const devices = await ZXing.BrowserQRCodeReader.listVideoInputDevices();

                if (!devices || devices.length === 0) {
                    throw new Error("No camera device found");
                }

                let selectedDeviceId = devices[0].deviceId;

                // cari kamera belakang (kalau label sudah tersedia)
                for (let d of devices) {
                    if (d.label && d.label.toLowerCase().includes("back")) {
                        selectedDeviceId = d.deviceId;
                        break;
                    }
                }

                const videoElement = document.getElementById('reader');

                if (!videoElement) {
                    throw new Error("Reader element not found");
                }

                await codeReader.decodeFromVideoDevice(
                    selectedDeviceId,
                    videoElement,
                    (result, err) => {

                        if (result && scanning) {
                            scannedData = result.text;
                            onQRDetected(result.text);
                        }

                    }
                );

            } catch (err) {

                console.error("QR CAMERA ERROR:", err);

                Swal.fire({
                    icon: 'error',
                    title: 'Kamera QR gagal dibuka',
                    text: 'Cek izin kamera atau gunakan HTTPS'
                });

            }
        }

        /* =========================
           STOP SCANNER
        ========================= */
        function stopScanner() {
            scanning = false;

            try {
                if (codeReader) {
                    codeReader.reset();
                    codeReader = null;
                }
            } catch (e) {}

        }

        /* =========================
           QR DETECTED → STATE SAVE
        ========================= */
        function onQRDetected(text) {

            stopScanner();

            document.getElementById('qrResult').classList.remove('hidden');
            document.getElementById('qrText').innerText = text;

            document.getElementById('selfieSection').classList.remove('hidden');
            document.getElementById('finalSection').classList.remove('hidden');
        }

        /* =========================
           SELFIE OPTIONAL
        ========================= */
        function openSelfie() {

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "user"
                    }
                })
                .then(stream => {

                    currentStream = stream;
                    video.srcObject = stream;

                    video.classList.remove('hidden');

                    setTimeout(() => captureSelfie(), 2000);

                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Tidak bisa akses kamera selfie', 'error');
                });
        }

        function captureSelfie() {

            try {

                let sound = document.getElementById("shutterSound");
                sound.play().catch(() => {});

                canvas.width = video.videoWidth || 640;
                canvas.height = video.videoHeight || 480;

                let ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                let img = canvas.toDataURL('image/png');

                document.getElementById('selfie').value = img;
                document.getElementById('selfieBox').innerHTML = `<img src="${img}">`;

                if (currentStream) {
                    currentStream.getTracks().forEach(t => t.stop());
                }

                video.classList.add('hidden');

            } catch (e) {
                console.error(e);
            }
        }

        /* =========================
           SUBMIT FINAL
        ========================= */
        function submitData() {

            if (!scannedData) {
                Swal.fire('Warning', 'Belum scan QR', 'warning');
                return;
            }

            fetch("{{ url('/modules/scan-qr-guest') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        kode_token: scannedData,
                        selfie: document.getElementById('selfie').value || null
                    })
                })
                .then(res => res.json())
                .then(data => {

                    if (data.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data tersimpan',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        setTimeout(() => location.reload(), 1500);

                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Request gagal', 'error');
                });
        }

        /* =========================
           START SAFE
        ========================= */
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                startScanner();
            }, 300);
        });
    </script>
@endpush
