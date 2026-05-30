@extends('layouts.master')

@push('style-css')
    <style>
        .card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 30px;
        }

        #reader {
            width: 100%;
            height: 360px;
            background: #000;
            border-radius: 16px;
            object-fit: cover;
        }

        .video-shell {
            max-width: 520px;
            margin: 0 auto;
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: #000;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.18);
        }

        .video-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.35));
            pointer-events: none;
        }

        .selfie-shell {
            width: 100%;
            height: 360px;
            border-radius: 16px;
            overflow: hidden;
            background: #000;
            position: relative;
        }

        #selfieVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .selfie-preview-img {
            width: 100%;
            max-width: 180px;
            border-radius: 12px;
        }

        .countdown-layer {
            position: absolute;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.25);
        }

        .countdown-number {
            width: 96px;
            height: 96px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.55);
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 46px;
            font-weight: 800;
            line-height: 1;
        }
    </style>
@endpush

@push('content-modules')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                        <div>
                            <h4 class="mb-1 font-weight-bold text-primary">Scan Kehadiran Tamu</h4>
                            <div class="text-muted small">Scan QR terlebih dahulu, selfie opsional, lalu submit.</div>
                        </div>
                        <span class="badge badge-pill badge-info" id="stepBadge">Step 1/3</span>
                    </div>

                    <div id="scanSection">
                        <div class="mb-2 font-weight-bold text-dark">1) Scan QR Code</div>
                        <div class="d-flex flex-wrap align-items-center justify-content-center mb-2" style="gap: 8px;">
                            <div class="btn-group btn-group-sm" role="group" aria-label="Pilih Kamera Scan">
                                <button type="button" class="btn btn-outline-secondary active" id="btnScanFront">Kamera Depan</button>
                                <button type="button" class="btn btn-outline-secondary" id="btnScanBack">Kamera Belakang</button>
                            </div>
                        </div>
                        <div class="video-shell mb-3">
                            <video id="reader" autoplay playsinline muted></video>
                            <div class="video-overlay">
                                <div class="spinner-border text-light" role="status" style="display:none;"
                                    id="scanLoading"></div>
                            </div>
                        </div>
                    </div>

                    <div id="resultSection" class="d-none">
                        <hr>
                        <div class="mb-2 font-weight-bold text-dark">2) Data Tamu</div>
                        <div class="alert alert-success py-2 mb-3" id="guestInfo"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="font-weight-bold">Selfie (Opsional)</div>
                                        <span class="badge badge-pill badge-secondary" id="selfieStatus">Belum Ada</span>
                                    </div>

                                    <div id="selfiePreview" class="text-center text-muted small">Tidak ada selfie.</div>

                                    <div class="mt-3 d-flex flex-wrap" style="gap: 8px;">
                                        <button type="button" class="btn btn-primary btn-sm" id="btnOpenSelfie"
                                            data-toggle="modal" data-target="#selfieModal">
                                            Ambil Selfie
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm d-none"
                                            id="btnRemoveSelfie">
                                            Hapus Selfie
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3 mt-md-0">
                                <div class="border rounded p-3 h-100">
                                    <div class="font-weight-bold mb-2">3) Submit Kehadiran</div>
                                    <button type="button" class="btn btn-success btn-block" id="btnSubmit" disabled>
                                        Submit Check-in
                                    </button>
                                    <button type="button" class="btn btn-link btn-sm btn-block mt-2" id="btnScanNew">
                                        Scan QR Lain
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="selfie">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="selfieModal" tabindex="-1" aria-labelledby="selfieModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selfieModalLabel">Ambil Selfie (Opsional)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2" style="gap: 8px;">
                        <div class="btn-group btn-group-sm" role="group" aria-label="Pilih Kamera">
                            <button type="button" class="btn btn-outline-secondary" id="btnCamFront">Kamera Depan</button>
                            <button type="button" class="btn btn-outline-secondary" id="btnCamBack">Kamera Belakang</button>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnStartSelfie">Mulai Kamera</button>
                    </div>

                    <div class="selfie-shell">
                        <video id="selfieVideo" autoplay playsinline muted></video>
                        <canvas id="selfieCanvas" style="display:none;"></canvas>
                        <div class="countdown-layer" id="selfieCountdownLayer">
                            <div class="countdown-number" id="selfieCountdownNumber">3</div>
                        </div>
                    </div>

                    <div class="mt-3 text-center" id="selfieModalPreview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnCapture">Ambil Foto</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('style-js')
    <script src="https://unpkg.com/@zxing/library@latest"></script>

    <script type="text/javascript">
        const state = {
            kode_token: null,
            guest: null,
            selfie: null,
            scanning: false,
            codeReader: null,
            selfieStream: null,
            selfieFacingMode: 'user',
            selfieDraft: null,
            scanFacingMode: 'user',
        };

        const elReader = document.getElementById('reader');
        const elScanLoading = document.getElementById('scanLoading');
        const elScanSection = document.getElementById('scanSection');
        const elResultSection = document.getElementById('resultSection');
        const elGuestInfo = document.getElementById('guestInfo');
        const elStepBadge = document.getElementById('stepBadge');
        const elBtnScanNew = document.getElementById('btnScanNew');
        const elBtnSubmit = document.getElementById('btnSubmit');
        const elBtnRemoveSelfie = document.getElementById('btnRemoveSelfie');
        const elSelfieStatus = document.getElementById('selfieStatus');
        const elSelfiePreview = document.getElementById('selfiePreview');
        const elSelfieInput = document.getElementById('selfie');

        const elSelfieVideo = document.getElementById('selfieVideo');
        const elSelfieCanvas = document.getElementById('selfieCanvas');
        const elSelfieModalPreview = document.getElementById('selfieModalPreview');
        const elBtnCamFront = document.getElementById('btnCamFront');
        const elBtnCamBack = document.getElementById('btnCamBack');
        const elBtnStartSelfie = document.getElementById('btnStartSelfie');
        const elBtnCapture = document.getElementById('btnCapture');
        const elSelfieCountdownLayer = document.getElementById('selfieCountdownLayer');
        const elSelfieCountdownNumber = document.getElementById('selfieCountdownNumber');

        const elBtnScanFront = document.getElementById('btnScanFront');
        const elBtnScanBack = document.getElementById('btnScanBack');

        function setStep(stepText) {
            elStepBadge.innerText = stepText;
        }

        function setLoading(isLoading) {
            elScanLoading.style.display = isLoading ? 'inline-block' : 'none';
        }

        function getCameraHelpText(error) {
            if (!window.isSecureContext) {
                return 'Akses kamera butuh HTTPS. Buka halaman lewat link HTTPS (misalnya ngrok HTTPS) atau localhost.';
            }
            const name = (error && error.name) ? error.name : '';
            if (name === 'NotAllowedError' || name === 'PermissionDeniedError') {
                return 'Izin kamera ditolak. Coba allow kamera di browser, lalu refresh halaman.';
            }
            if (name === 'NotFoundError' || name === 'DevicesNotFoundError') {
                return 'Kamera tidak ditemukan. Pastikan perangkat punya kamera dan tidak sedang dipakai aplikasi lain.';
            }
            if (name === 'NotReadableError' || name === 'TrackStartError') {
                return 'Kamera sedang dipakai aplikasi lain atau gagal dibuka. Tutup aplikasi kamera lain lalu coba lagi.';
            }
            return 'Kalau tetap gagal, cek permission kamera di browser.';
        }

        function stopScanner() {
            state.scanning = false;
            state.scanSessionId = (state.scanSessionId || 0) + 1;
            if (state.codeReader) {
                try {
                    state.codeReader.reset();
                } catch (e) {}
                state.codeReader = null;
            }
            if (elReader && elReader.srcObject) {
                elReader.srcObject.getTracks().forEach(track => track.stop());
                elReader.srcObject = null;
            }
        }

        async function startScanner() {
            try {
                stopScanner();
                setLoading(true);

                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    setLoading(false);
                    Swal.fire('Error', 'Browser tidak mendukung kamera (getUserMedia).', 'error');
                    return;
                }

                const mySession = state.scanSessionId;

                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = (devices || []).filter(d => d.kind === 'videoinput');
                if (!videoDevices || videoDevices.length === 0) {
                    setLoading(false);
                    Swal.fire('Error', 'Tidak ada kamera ditemukan', 'error');
                    return;
                }

                let preferredDeviceId = null;
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: {
                                ideal: state.scanFacingMode
                            }
                        }
                    });
                    const track = stream.getVideoTracks()[0];
                    preferredDeviceId = track?.getSettings?.().deviceId || null;
                    stream.getTracks().forEach(t => t.stop());
                } catch (e) {}

                const callback = (result, err) => {
                    if (!state.scanning) return;
                    if (result && result.text) {
                        onScan(result.text);
                    }
                };

                const preferred = [];
                const fallback = [];
                for (let device of videoDevices) {
                    const label = (device.label || '').toLowerCase();
                    const isBack = label.includes('back') || label.includes('environment') || label.includes('rear');
                    const isFront = label.includes('front') || label.includes('user') || label.includes('facetime');
                    if (state.scanFacingMode === 'environment') {
                        (isBack ? preferred : fallback).push(device.deviceId);
                    } else {
                        (isFront ? preferred : fallback).push(device.deviceId);
                    }
                }

                const ordered = [];
                if (preferredDeviceId) {
                    ordered.push(preferredDeviceId);
                }
                for (let id of [...preferred, ...fallback]) {
                    if (!id) continue;
                    if (!ordered.includes(id)) ordered.push(id);
                }

                let lastErr = null;

                const tryDevice = (index) => {
                    if (state.scanSessionId !== mySession) return;
                    if (index >= ordered.length) {
                        setLoading(false);
                        const err = lastErr || new Error('No camera device available');
                        throw err;
                    }

                    const deviceId = ordered[index];
                    stopScanner();
                    state.scanSessionId = mySession;
                    state.codeReader = new ZXing.BrowserQRCodeReader();
                    state.scanning = true;
                    setLoading(false);

                    const p = state.codeReader.decodeFromVideoDevice(deviceId, 'reader', callback);
                    if (p && typeof p.catch === 'function') {
                        p.catch((e) => {
                            if (state.scanSessionId !== mySession) return;
                            lastErr = e;
                            setLoading(true);
                            tryDevice(index + 1);
                        });
                    }
                };

                tryDevice(0);
            } catch (e) {
                setLoading(false);
                const msg = e && e.message ? e.message : '';
                const name = e && e.name ? e.name : 'Error';
                if (name === 'NotAllowedError' || name === 'PermissionDeniedError') {
                    Swal.fire({
                        title: 'Izin Kamera Diperlukan',
                        html: `<small>${getCameraHelpText(e)}</small>`,
                        icon: 'warning',
                        confirmButtonText: 'Aktifkan Kamera'
                    }).then(() => {
                        startScanner();
                    });
                    return;
                }

                Swal.fire('Error', `${name}: Kamera QR gagal dibuka.<br><small>${msg}</small><br><small>${getCameraHelpText(e)}</small>`, 'error');
            }
        }

        async function validateToken(kodeToken) {
            setLoading(true);
            const res = await fetch("{{ url('/modules/scan-qr-guest/validate') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    kode_token: kodeToken
                })
            });
            setLoading(false);
            return res.json();
        }

        function setSelfie(base64) {
            state.selfie = base64 || null;
            elSelfieInput.value = state.selfie || '';

            if (!state.selfie) {
                elSelfieStatus.className = 'badge badge-pill badge-secondary';
                elSelfieStatus.innerText = 'Belum Ada';
                elSelfiePreview.className = 'text-center text-muted small';
                elSelfiePreview.innerText = 'Tidak ada selfie.';
                elBtnRemoveSelfie.classList.add('d-none');
                return;
            }

            elSelfieStatus.className = 'badge badge-pill badge-success';
            elSelfieStatus.innerText = 'Ada';
            elSelfiePreview.className = 'text-center';
            elSelfiePreview.innerHTML = `<img src="${state.selfie}" class="selfie-preview-img" alt="Selfie">`;
            elBtnRemoveSelfie.classList.remove('d-none');
        }

        function resetFlow() {
            state.kode_token = null;
            state.guest = null;
            setSelfie(null);
            elResultSection.classList.add('d-none');
            elScanSection.classList.remove('d-none');
            elBtnSubmit.disabled = true;
            setStep('Step 1/3');
        }

        async function onScan(decodedText) {
            if (state.kode_token) return;
            stopScanner();

            const data = await validateToken(decodedText);
            if (data.status !== 'success') {
                Swal.fire('Error', data.message || 'QR Code tidak valid', 'error');
                resetFlow();
                setTimeout(() => startScanner(), 500);
                return;
            }

            state.kode_token = decodedText;
            state.guest = data.guest;

            elGuestInfo.innerHTML =
                `<b>Nama:</b> ${state.guest.nama_tamu}<br><span class="text-muted small">Kode: ${state.guest.kode_token}</span>`;

            elScanSection.classList.add('d-none');
            elResultSection.classList.remove('d-none');
            elBtnSubmit.disabled = false;
            setStep('Step 2/3');
        }

        async function submitCheckin() {
            if (!state.kode_token) {
                Swal.fire('Oops', 'Scan QR dulu ya', 'warning');
                return;
            }

            elBtnSubmit.disabled = true;
            const res = await fetch("{{ url('/modules/scan-qr-guest') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    kode_token: state.kode_token,
                    selfie: state.selfie
                })
            });
            const data = await res.json();

            if (data.status === 'success') {
                Swal.fire({
                    title: 'Check-in Berhasil',
                    text: `Terima kasih ${data.nama} sudah hadir`,
                    icon: 'success',
                    timer: 1800,
                    showConfirmButton: false
                });
                resetFlow();
                setTimeout(() => startScanner(), 500);
                return;
            }

            Swal.fire('Error', data.message || 'Gagal menyimpan data', 'error');
            elBtnSubmit.disabled = false;
        }

        async function stopSelfieStream() {
            if (state.selfieStream) {
                state.selfieStream.getTracks().forEach(track => track.stop());
                state.selfieStream = null;
            }
            if (elSelfieVideo) {
                elSelfieVideo.srcObject = null;
            }
        }

        async function startSelfieStream() {
            await stopSelfieStream();
            const constraints = {
                video: {
                    facingMode: {
                        ideal: state.selfieFacingMode
                    }
                }
            };
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            state.selfieStream = stream;
            elSelfieVideo.srcObject = stream;
            await elSelfieVideo.play();
        }

        function wait(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        async function captureSelfieDraft() {
            if (!state.selfieStream) {
                Swal.fire('Oops', 'Kamera belum aktif', 'warning');
                return;
            }
            elBtnCapture.disabled = true;
            elBtnStartSelfie.disabled = true;

            elSelfieCountdownLayer.style.display = 'flex';
            for (let n = 3; n >= 1; n--) {
                elSelfieCountdownNumber.innerText = String(n);
                await wait(700);
            }
            elSelfieCountdownLayer.style.display = 'none';

            const ctx = elSelfieCanvas.getContext('2d');
            elSelfieCanvas.width = elSelfieVideo.videoWidth;
            elSelfieCanvas.height = elSelfieVideo.videoHeight;
            ctx.drawImage(elSelfieVideo, 0, 0, elSelfieCanvas.width, elSelfieCanvas.height);
            state.selfieDraft = elSelfieCanvas.toDataURL('image/png');
            setSelfie(state.selfieDraft);
            state.selfieDraft = null;
            elSelfieModalPreview.innerHTML = '';
            $('#selfieModal').modal('hide');
            setStep('Step 3/3');

            elBtnCapture.disabled = false;
            elBtnStartSelfie.disabled = false;
        }

        elBtnScanNew.addEventListener('click', () => {
            resetFlow();
            startScanner();
        });

        elBtnSubmit.addEventListener('click', submitCheckin);

        elBtnRemoveSelfie.addEventListener('click', () => {
            setSelfie(null);
        });

        elBtnCamFront.addEventListener('click', () => {
            state.selfieFacingMode = 'user';
            elBtnCamFront.classList.add('active');
            elBtnCamBack.classList.remove('active');
            if (state.selfieStream) {
                startSelfieStream().catch(() => {});
            }
        });

        elBtnCamBack.addEventListener('click', () => {
            state.selfieFacingMode = 'environment';
            elBtnCamBack.classList.add('active');
            elBtnCamFront.classList.remove('active');
            if (state.selfieStream) {
                startSelfieStream().catch(() => {});
            }
        });

        elBtnStartSelfie.addEventListener('click', async () => {
            try {
                await startSelfieStream();
            } catch (e) {
                Swal.fire('Error', 'Kamera selfie gagal dibuka', 'error');
            }
        });

        elBtnCapture.addEventListener('click', captureSelfieDraft);

        elBtnScanFront.addEventListener('click', () => {
            state.scanFacingMode = 'user';
            elBtnScanFront.classList.add('active');
            elBtnScanBack.classList.remove('active');
            if (!state.kode_token) {
                startScanner();
            }
        });

        elBtnScanBack.addEventListener('click', () => {
            state.scanFacingMode = 'environment';
            elBtnScanBack.classList.add('active');
            elBtnScanFront.classList.remove('active');
            if (!state.kode_token) {
                startScanner();
            }
        });

        $('#selfieModal').on('shown.bs.modal', async function() {
            state.selfieFacingMode = state.selfieFacingMode || 'user';
            if (state.selfieFacingMode === 'environment') {
                elBtnCamBack.classList.add('active');
                elBtnCamFront.classList.remove('active');
            } else {
                elBtnCamFront.classList.add('active');
                elBtnCamBack.classList.remove('active');
            }
            try {
                await startSelfieStream();
            } catch (e) {}
        });

        $('#selfieModal').on('hidden.bs.modal', async function() {
            await stopSelfieStream();
            state.selfieDraft = null;
            elSelfieModalPreview.innerHTML = '';
            elSelfieCountdownLayer.style.display = 'none';
            elBtnCapture.disabled = false;
            elBtnStartSelfie.disabled = false;
        });

        document.addEventListener("click", async () => {
            try {
                await navigator.mediaDevices.getUserMedia({
                    video: true
                });
            } catch (e) {}
        }, {
            once: true
        });

        setStep('Step 1/3');
        startScanner();
    </script>
@endpush
