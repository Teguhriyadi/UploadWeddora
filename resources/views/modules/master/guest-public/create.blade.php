@extends('layouts.master')

@push('title-modules', 'Tambah Data')

@push('style-css')
    <style>
        .form-label {
            font-weight: 600;
            font-size: 14px;
            color: #444;
        }

        .camera-wrapper {
            width: 100%;
            max-width: 420px;
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
            font-size: 13px;
            background: rgba(0, 0, 0, 0.5);
            padding: 5px 12px;
            border-radius: 20px;
        }

        #video {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transform: scaleX(-1);
        }

        .environment-camera {
            transform: scaleX(1) !important;
        }

        #countdown {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 70px;
            color: white;
            display: none;
            font-weight: bold;
            z-index: 10;
        }

        #flash {
            position: absolute;
            width: 100%;
            height: 100%;
            background: white;
            top: 0;
            left: 0;
            opacity: 0;
            pointer-events: none;
        }

        .flash-animation {
            animation: flashEffect 0.3s;
        }

        @keyframes flashEffect {
            0% {
                opacity: 0
            }

            50% {
                opacity: 1
            }

            100% {
                opacity: 0
            }
        }

        #previewSelfie img {
            margin-top: 15px;
            border-radius: 15px;
            width: 100%;
            max-width: 320px;
            border: 4px solid #0d6efd;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease;
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

        #btnSelfie,
        #btnSwitchCamera {
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 600;
            transition: 0.3s;
        }

        #btnSelfie:hover,
        #btnSwitchCamera:hover {
            transform: scale(1.05);
        }
    </style>
@endpush

@push('content-modules')

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Berhasil</strong>, {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            <strong>Gagal</strong>, {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="{{ url('/modules/guest-public') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <form action="{{ url('/modules/guest-public') }}" method="POST">
                    @csrf
                    <input type="hidden" name="selfie" id="selfie">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Nama Tamu <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        name="nama" placeholder="Masukkan Nama Tamu" value="{{ old('nama') }}">
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        No. Handphone
                                    </label>
                                    <input type="text"
                                        class="form-control @error('nomor_handphone') is-invalid @enderror"
                                        name="nomor_handphone" placeholder="Masukkan Nomor Handphone"
                                        value="{{ old('nomor_handphone') }}">

                                    @error('nomor_handphone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Pekerjaan
                                    </label>
                                    <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror"
                                        name="pekerjaan" placeholder="Masukkan Pekerjaan" value="{{ old('pekerjaan') }}">

                                    @error('pekerjaan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Jumlah Kedatangan
                                        <small class="text-danger">*</small>
                                    </label>
                                    <input type="number" min="0"
                                        class="form-control @error('jumlah_kedatangan') is-invalid @enderror"
                                        name="jumlah_kedatangan" placeholder="Masukkan Jumlah Kedatangan"
                                        value="{{ old('jumlah_kedatangan') }}">

                                    @error('jumlah_kedatangan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Alamat
                                    </label>
                                    <textarea name="alamat" class="form-control" rows="4" placeholder="Masukkan Alamat">{{ old('alamat') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">

                                        <h5 class="mb-2 text-primary">📸 Selfie Tamu</h5>
                                        <p class="text-muted small">Ambil foto sebelum menyimpan data</p>

                                        <div id="cameraArea">
                                            <div class="camera-wrapper">

                                                <video id="video" autoplay playsinline></video>

                                                <canvas id="canvas" style="display:none;"></canvas>

                                                <div id="countdown"></div>

                                                <div id="flash"></div>

                                            </div>
                                        </div>

                                        <div id="previewSelfie"></div>

                                        {{-- <button type="button" id="btnSelfie" class="btn btn-primary mt-3"
                                            onclick="takeSelfie()">
                                            <i class="fa fa-camera"></i> Ambil Selfie
                                        </button> --}}

                                        <div class="d-flex justify-content-center gap-2 flex-wrap mt-3">

                                            <button type="button" id="btnSelfie" class="btn btn-primary"
                                                onclick="takeSelfie()">

                                                <i class="fa fa-camera"></i>
                                                Ambil Selfie

                                            </button>

                                            <button type="button" class="btn btn-secondary" onclick="switchCamera()">

                                                <i class="fa fa-sync"></i>
                                                Ganti Kamera

                                            </button>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="reset" class="btn btn-danger btn-sm">
                            <i class="fa fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <audio id="shutterSound" preload="auto">
        <source src="{{ asset('templating/sound/sound-selfie.mp3') }}" type="audio/mpeg">
    </audio>
@endpush

@push('style-js')
    <script>
        let shutter = new Audio("{{ asset('templating/sound/sound-selfie.mp3') }}");

        let video = document.getElementById('video');

        let currentFacingMode = "user";

        let currentStream = null;

        let takingPhoto = false;

        async function startCamera() {

            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }

            try {

                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: {
                            ideal: currentFacingMode
                        },
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    },
                    audio: false
                });

                currentStream = stream;

                video.srcObject = stream;

                if (currentFacingMode === "environment") {

                    video.classList.add("environment-camera");

                } else {

                    video.classList.remove("environment-camera");

                }

                await video.play();

            } catch (error) {

                console.log(error);

                alert("Kamera tidak bisa diakses");

            }

        }

        startCamera();

        async function switchCamera() {

            if (takingPhoto) return;

            currentFacingMode =
                currentFacingMode === "user" ?
                "environment" :
                "user";

            await startCamera();

        }

        function takeSelfie() {

            let selfie = document.getElementById("selfie").value;

            if (selfie) {

                resetSelfie();

                return;

            }

            if (takingPhoto) return;

            takingPhoto = true;

            let countdown = document.getElementById("countdown");

            let count = 3;

            countdown.style.display = "block";

            countdown.innerText = count;

            let timer = setInterval(() => {

                count--;

                if (count > 0) {

                    countdown.innerText = count;

                } else {

                    clearInterval(timer);

                    countdown.style.display = "none";

                    capturePhoto();

                    takingPhoto = false;

                }

            }, 1000);

        }

        function capturePhoto() {

            let canvas = document.getElementById("canvas");

            canvas.width = video.videoWidth;

            canvas.height = video.videoHeight;

            let ctx = canvas.getContext("2d");

            if (currentFacingMode === "user") {

                ctx.translate(canvas.width, 0);

                ctx.scale(-1, 1);

            }

            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            let image = canvas.toDataURL("image/png");

            document.getElementById("selfie").value = image;

            document.getElementById("previewSelfie").innerHTML =
                `<img src="${image}" class="img-fluid">`;

            shutter.currentTime = 0;

            shutter.play().catch(() => {});

            let flash = document.getElementById("flash");

            flash.classList.add("flash-animation");

            setTimeout(() => {

                flash.classList.remove("flash-animation");

            }, 300);

            document.getElementById("cameraArea").style.display = "none";

            let btn = document.getElementById("btnSelfie");

            btn.innerHTML =
                `<i class="fa fa-refresh"></i> Ambil Ulang`;

            btn.classList.remove("btn-primary");

            btn.classList.add("btn-warning");

        }

        function resetSelfie() {

            document.getElementById("previewSelfie").innerHTML = "";

            document.getElementById("selfie").value = "";

            document.getElementById("cameraArea").style.display = "block";

            let btn = document.getElementById("btnSelfie");

            btn.innerHTML =
                `<i class="fa fa-camera"></i> Ambil Selfie`;

            btn.classList.remove("btn-warning");

            btn.classList.add("btn-primary");

        }

        window.addEventListener("beforeunload", () => {

            if (currentStream) {

                currentStream.getTracks().forEach(track => track.stop());

            }

        });
    </script>
@endpush
