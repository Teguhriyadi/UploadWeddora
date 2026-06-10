@extends('layouts.master')

@push("title-modules", "Input Kehadiran Tamu")

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

        .info-box {
            background: #f8f9ff;
            border-radius: 14px;
            padding: 14px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.04);
        }

        .selfie-preview-img {
            width: 100%;
            max-width: 320px;
            border-radius: 12px;
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.18);
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

        .panel-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .hint-box {
            border: 1px dashed rgba(0, 0, 0, 0.15);
            border-radius: 14px;
            padding: 12px;
            background: #fff;
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
                    <div class="panel-title mb-3">
                        <div>
                            <h4 class="mb-1 font-weight-bold text-primary">Input Kehadiran Tamu</h4>
                            <div class="text-muted small">Cari tamu → (opsional) ambil selfie → check-in.</div>
                        </div>
                        <div class="d-flex flex-wrap" style="gap: 8px;">
                            <button type="button" class="btn btn-outline-danger btn-sm" id="btnClearGuest" disabled>
                                <i class="fa fa-sign-out-alt"></i> Ganti Tamu
                            </button>
                        </div>
                    </div>

                    <form action="{{ url('/modules/input-attendance') }}" method="POST" id="attendanceForm">
                        @csrf

                        <input type="hidden" name="selfie" id="selfie">

                        <div class="row">
                            <div class="col-lg-7">
                                <div class="mb-2 font-weight-bold text-dark">Pilih Tamu</div>

                                <div class="form-group mb-3">
                                    <label>Nama Tamu</label>
                                    <select name="guest_id" id="guest_id"
                                        class="form-control select2 @error('guest_id') is-invalid @enderror">
                                    </select>
                                    <div class="text-muted small mt-2">Ketik nama tamu. Data hanya menampilkan tamu yang belum check-in.</div>
                                </div>

                                <div class="info-box d-none" id="infoGuest">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
                                        <h6 class="mb-0 text-primary font-weight-bold">Informasi Tamu</h6>
                                        <span class="badge badge-pill badge-success">Siap Check-in</span>
                                    </div>
                                    <div class="mt-3 row">
                                        <div class="col-sm-6">
                                            <div class="text-muted small">Nama</div>
                                            <div class="font-weight-bold" id="guestNama"></div>
                                        </div>
                                        <div class="col-sm-6 mt-3 mt-sm-0">
                                            <div class="text-muted small">Relasi</div>
                                            <div class="font-weight-bold" id="guestRelasi"></div>
                                        </div>
                                        <div class="col-sm-6 mt-3">
                                            <div class="text-muted small">Nama di Undangan</div>
                                            <div class="font-weight-bold" id="guestNamaUndangan"></div>
                                        </div>
                                        <div class="col-sm-6 mt-3">
                                            <div class="text-muted small">Jenis Undangan</div>
                                            <div class="font-weight-bold" id="guestJenisUndangan"></div>
                                        </div>
                                        <div class="col-sm-6 mt-3">
                                            <div class="text-muted small">Keterangan</div>
                                            <div class="font-weight-bold" id="guestKeterangan"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="hint-box mt-3">
                                    <div class="font-weight-bold mb-1">Tips Cepat</div>
                                    <div class="text-muted small">Kalau kondisi ramai, check-in bisa dilakukan tanpa selfie. Selfie bersifat opsional dan bisa diulang.</div>
                                </div>
                            </div>

                            <div class="col-lg-5 mt-3 mt-lg-0">
                                <div class="mb-2 font-weight-bold text-dark">Selfie (Opsional) &amp; Submit</div>
                                <div class="border rounded p-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
                                        <div class="font-weight-bold">Selfie Tamu</div>
                                        <span class="badge badge-pill badge-secondary" id="selfieStatus">Belum Ada</span>
                                    </div>

                                    <div class="mt-3 text-center">
                                        <div id="selfiePreview" class="text-muted small">Tidak ada selfie.</div>
                                    </div>

                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal"
                                            data-target="#selfieModal" id="btnOpenSelfie" disabled>
                                            Ambil Selfie
                                        </button>

                                        <div class="d-flex flex-wrap mt-2" style="gap: 10px;">
                                            <button type="button" class="btn btn-outline-warning btn-lg flex-fill d-none"
                                                id="btnRetakeSelfie" data-toggle="modal" data-target="#selfieModal">
                                                Ulang
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-lg flex-fill d-none"
                                                id="btnRemoveSelfie">
                                                Hapus
                                            </button>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-lg btn-block mt-2" id="btnSubmit" disabled>
                                            <i class="fa fa-check"></i> CHECKIN TAMU
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
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
                            text: `(${item.nama_tamu}) - ${item.nama_undangan} - ${item.relasi} - ${item.keterangan}`
                        }))
                    })
                }
            });
        });

        $('#guest_id').on('change', function() {
            let id = $(this).val();
            if (!id) {
                resetGuest();
                return;
            }

            $.get(`{{ url('/modules/guest/info') }}/` + id, function(data) {
                $('#infoGuest').removeClass('d-none');
                $('#guestNama').text(data.nama || '-');
                $('#guestNamaUndangan').text(data.nama_undangan || '-');
                $('#guestRelasi').text(data.relasi || '-');
                $('#guestJenisUndangan').text(data.jenis_undangan || '-');
                $('#guestKeterangan').text(data.keterangan || '-');

                document.getElementById('btnSubmit').disabled = false;
                document.getElementById('btnOpenSelfie').disabled = false;
                document.getElementById('btnClearGuest').disabled = false;
            });
        });

        const state = {
            selfieStream: null,
            selfieFacingMode: 'user',
        };

        const elAttendanceForm = document.getElementById('attendanceForm');
        const elSelfieInput = document.getElementById('selfie');
        const elSelfieStatus = document.getElementById('selfieStatus');
        const elSelfiePreview = document.getElementById('selfiePreview');
        const elBtnOpenSelfie = document.getElementById('btnOpenSelfie');
        const elBtnRetakeSelfie = document.getElementById('btnRetakeSelfie');
        const elBtnRemoveSelfie = document.getElementById('btnRemoveSelfie');
        const elBtnClearGuest = document.getElementById('btnClearGuest');
        const elBtnSubmit = document.getElementById('btnSubmit');

        const elSelfieVideo = document.getElementById('selfieVideo');
        const elSelfieCanvas = document.getElementById('selfieCanvas');
        const elBtnCamFront = document.getElementById('btnCamFront');
        const elBtnCamBack = document.getElementById('btnCamBack');
        const elBtnStartSelfie = document.getElementById('btnStartSelfie');
        const elBtnCapture = document.getElementById('btnCapture');
        const elCountdownLayer = document.getElementById('selfieCountdownLayer');
        const elCountdownNumber = document.getElementById('selfieCountdownNumber');

        function setSelfie(base64) {
            elSelfieInput.value = base64 || '';

            if (!base64) {
                elSelfieStatus.className = 'badge badge-pill badge-secondary';
                elSelfieStatus.innerText = 'Belum Ada';
                elSelfiePreview.className = 'text-muted small';
                elSelfiePreview.innerText = 'Tidak ada selfie.';
                elBtnRemoveSelfie.classList.add('d-none');
                elBtnRetakeSelfie.classList.add('d-none');
                elBtnOpenSelfie.classList.remove('d-none');
                return;
            }

            elSelfieStatus.className = 'badge badge-pill badge-success';
            elSelfieStatus.innerText = 'Ada';
            elSelfiePreview.className = '';
            elSelfiePreview.innerHTML = `<img src="${base64}" class="selfie-preview-img" alt="Selfie">`;
            elBtnRemoveSelfie.classList.remove('d-none');
            elBtnRetakeSelfie.classList.remove('d-none');
            elBtnOpenSelfie.classList.add('d-none');
        }

        function resetGuest() {
            $('#infoGuest').addClass('d-none');
            $('#guestNama').text('');
            $('#guestNamaUndangan').text('');
            $('#guestRelasi').text('');
            $('#guestJenisUndangan').text('');
            $('#guestKeterangan').text('');
            setSelfie(null);
            elBtnSubmit.disabled = true;
            elBtnOpenSelfie.disabled = true;
            elBtnClearGuest.disabled = true;
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

        async function captureSelfie() {
            if (!state.selfieStream) {
                Swal.fire('Oops', 'Kamera belum aktif', 'warning');
                return;
            }

            elBtnCapture.disabled = true;
            elBtnStartSelfie.disabled = true;

            elCountdownLayer.style.display = 'flex';
            for (let n = 3; n >= 1; n--) {
                elCountdownNumber.innerText = String(n);
                await wait(700);
            }
            elCountdownLayer.style.display = 'none';

            const ctx = elSelfieCanvas.getContext('2d');
            elSelfieCanvas.width = elSelfieVideo.videoWidth;
            elSelfieCanvas.height = elSelfieVideo.videoHeight;
            ctx.drawImage(elSelfieVideo, 0, 0, elSelfieCanvas.width, elSelfieCanvas.height);
            const image = elSelfieCanvas.toDataURL('image/png');
            setSelfie(image);
            $('#selfieModal').modal('hide');

            elBtnCapture.disabled = false;
            elBtnStartSelfie.disabled = false;
        }

        elBtnRemoveSelfie.addEventListener('click', () => {
            setSelfie(null);
        });

        elBtnClearGuest.addEventListener('click', () => {
            $('#guest_id').val(null).trigger('change');
            resetGuest();
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

        elBtnCapture.addEventListener('click', captureSelfie);

        $('#selfieModal').on('shown.bs.modal', async function() {
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
            elCountdownLayer.style.display = 'none';
            elBtnCapture.disabled = false;
            elBtnStartSelfie.disabled = false;
        });

        elAttendanceForm.addEventListener('submit', function(e) {
            const guestId = document.getElementById('guest_id').value;
            if (!guestId) {
                e.preventDefault();
                Swal.fire('Oops', 'Pilih tamu dulu ya', 'warning');
            }
        });

        resetGuest();
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
