@extends('layouts.master')

@push('title-modules', 'Input Kehadiran')

@push('style-css')
    <link href="{{ asset('templating/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('templating/select2/css/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" />

    <style>
        .card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: transparent;
            border-bottom: none;
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

        .info-box {
            background: #f8f9ff;
            border-radius: 15px;
            padding: 15px;
            margin-top: 10px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
        }
    </style>
@endpush


@push('content-modules')

    <div class="card shadow mb-4">
        <div class="card-body text-center">

            <h3 class="fw-bold text-primary mb-1">📷 Input Kehadiran</h3>
            <p class="text-muted mb-3">Pilih tamu dan ambil selfie sebelum check-in</p>

            <form action="{{ url('/modules/input-attendance') }}" method="POST" onsubmit="return validateForm()">
                @csrf

                <input type="hidden" name="selfie" id="selfie">

                {{-- SELECT TAMU --}}
                <div class="form-group mb-3 text-start">
                    <label>Nama Tamu</label>
                    <select name="guest_id" id="guest_id"
                        class="form-control select2 @error('guest_id') is-invalid @enderror">
                    </select>
                </div>

                {{-- INFO TAMU --}}
                <div class="info-box d-none" id="infoGuest">
                    <h6 class="mb-2 text-primary">👤 Informasi Tamu</h6>
                    <p><b>Nama:</b> <span id="guestNama"></span></p>
                    <p><b>Kategori:</b> <span id="guestKategori"></span></p>
                    <p><b>Keluarga:</b> <span id="guestKeluarga"></span></p>
                    <p><b>Jumlah:</b> <span id="guestJumlah"></span></p>
                </div>

                <hr>

                {{-- CAMERA --}}
                <h5 class="mb-3">📸 Selfie Tamu</h5>

                <div id="cameraArea">
                    <div class="camera-wrapper">
                        <video id="video" autoplay playsinline></video>
                        <canvas id="canvas" style="display:none;"></canvas>
                        <div id="countdown"></div>
                        <div id="flash"></div>
                    </div>
                </div>

                <div id="previewSelfie"></div>

                <button type="button" id="btnSelfie" class="btn btn-primary mt-3" onclick="takeSelfie()">
                    <i class="fa fa-camera"></i> Ambil Selfie
                </button>

                <hr>

                <button type="submit" class="btn btn-success w-100 mt-2">
                    <i class="fa fa-check"></i> CHECKIN TAMU
                </button>

            </form>
        </div>
    </div>

    <audio id="shutterSound" preload="auto">
        <source src="{{ asset('templating/sound/sound-selfie.mp3') }}" type="audio/mpeg">
    </audio>

@endpush


@push('style-js')

    <script src="{{ asset('templating/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#guest_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Ketik Nama Tamu',
                ajax: {
                    url: "{{ url('/modules/input-attendance/search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        q: params.term
                    }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: `(${item.kategori}) ${item.nama_tamu}`
                        }))
                    })
                }
            });
        });

        let shutter = document.getElementById("shutterSound");

        $('#guest_id').on('change', function() {
            let id = $(this).val();
            if (!id) return;

            $.get(`{{ url('/modules/guest/info') }}/` + id, function(data) {
                $('#infoGuest').removeClass('d-none');
                $('#guestNama').text(data.nama);
                $('#guestKategori').text(data.kategori);
                $('#guestKeluarga').text(data.keluarga);
                $('#guestJumlah').text(data.jumlah);
            });
        });

        function validateForm() {
            let selfie = document.getElementById("selfie").value;

            if (!selfie) {
                Swal.fire('Oops', 'Harus Foto Selfie Terlebih Dahulu', 'warning');
                return false;
            }

            return true;
        }

        let video = document.getElementById('video');
        let canvas = document.getElementById('canvas');
        let takingPhoto = false;

        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(stream => {
                video.srcObject = stream;
            });

        function takeSelfie() {

            let selfie = document.getElementById("selfie").value;

            if (selfie) {
                resetSelfie();
                return;
            }

            if (takingPhoto) return;

            shutter.play().then(() => {
                shutter.pause();
                shutter.currentTime = 0;
            }).catch(() => {});

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

            shutter.currentTime = 0;
            shutter.play().catch(() => {});
            let ctx = canvas.getContext("2d");

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            ctx.drawImage(video, 0, 0);

            let image = canvas.toDataURL("image/png");

            document.getElementById("selfie").value = image;

            document.getElementById("previewSelfie").innerHTML =
                `<img src="${image}">`;

            let flash = document.getElementById("flash");
            flash.style.opacity = 1;
            setTimeout(() => flash.style.opacity = 0, 100);

            document.getElementById("cameraArea").style.display = "none";

            let btn = document.getElementById("btnSelfie");
            btn.innerHTML = `<i class="fa fa-refresh"></i> Ambil Ulang`;
            btn.classList.remove("btn-primary");
            btn.classList.add("btn-warning");
        }

        function resetSelfie() {
            document.getElementById("selfie").value = "";
            document.getElementById("previewSelfie").innerHTML = "";
            document.getElementById("cameraArea").style.display = "block";

            let btn = document.getElementById("btnSelfie");
            btn.innerHTML = `<i class="fa fa-camera"></i> Ambil Selfie`;
            btn.classList.remove("btn-warning");
            btn.classList.add("btn-primary");
        }
    </script>
    <script>
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil 🎉',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Gagal ❌',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endpush
