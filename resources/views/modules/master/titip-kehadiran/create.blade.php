@extends('layouts.master')

@push('title-modules', 'Master Titip Kehadiran')

@push('style-css')
    <link href="{{ asset('templating/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('templating/select2/css/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" />
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
                    <a href="{{ url('/modules/titip-kehadiran') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <form action="{{ url('/modules/titip-kehadiran') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="wakil_id" class="col-sm-3 col-form-label">
                                Wakil Tamu <br>
                                <small>
                                    (Wakil harus checkin dahulu)
                                </small>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <div id="selectWakilWrapper">
                                    <div class="input-group">
                                        <select name="wakil_id" id="wakil_id"
                                            class="form-select select2 @error('wakil_id') is-invalid @enderror">
                                            <option value="">- Pilih Tamu -</option>

                                            @foreach ($wakil as $tamu)
                                                <option {{ old('wakil_id') == $tamu['id'] ? 'selected' : '' }} value="{{ $tamu->id }}">
                                                    {{ $tamu->nama_tamu }} - {{ $tamu->nama_undangan }} - Relasi :
                                                    {{ $tamu->relasi }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btnWakilTamuLuar">
                                            <i class="fa fa-users"></i> Dari Daftar Tamu Luar?
                                        </button>
                                    </div>

                                    @error('wakil_id')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div id="selectWakilPublicWrapper" style="display: none;">
                                    <div class="input-group">
                                        <select name="wakil_guest_public_id" id="wakil_guest_public_id"
                                            class="form-select select2 @error('wakil_guest_public_id') is-invalid @enderror">
                                            <option value="">- Pilih Tamu Luar -</option>

                                            @foreach ($wakil_public as $tamuLuar)
                                                <option {{ old('wakil_guest_public_id') == $tamuLuar['id'] ? 'selected' : '' }} value="{{ $tamuLuar->id }}">
                                                    {{ $tamuLuar->nama }}
                                                    @if (!empty($tamuLuar->nomor_handphone))
                                                        - {{ $tamuLuar->nomor_handphone }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btnPilihWakilUndangan">
                                            <i class="fa fa-search"></i> Pilih dari Tamu Undangan
                                        </button>
                                    </div>

                                    @error('wakil_guest_public_id')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="guest_id" class="col-sm-3 col-form-label">
                                Nama Tamu Berhalangan
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">

                                {{-- Select Tamu --}}
                                <div id="selectTamuWrapper">
                                    <div class="input-group">
                                        <select name="guest_id" id="guest_id"
                                            class="form-select select2 @error('guest_id') is-invalid @enderror">
                                            <option value="">- Pilih Tamu -</option>

                                            @foreach ($guest as $guest)
                                                <option {{ old('guest_id') == $guest['id'] ? 'selected' : '' }} value="{{ $guest->id }}">
                                                    {{ $guest->nama_tamu }} - {{ $guest->nama_undangan }} - Relasi :
                                                    {{ $guest->relasi }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btnTamuLain">
                                            <i class="fa fa-user"></i> Tidak Ada Daftar Tamu?
                                        </button>
                                    </div>

                                    @error('guest_id')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Input Manual --}}
                                <div id="inputTamuWrapper" style="display: none;">
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('nama_tamu') is-invalid @enderror"
                                            name="nama_tamu" id="nama_tamu" placeholder="Masukkan Nama Tamu">

                                        <button type="button" class="btn btn-outline-primary" id="btnPilihTamu">
                                            <i class="fa fa-search"></i> Pilih dari Daftar Tamu
                                        </button>
                                    </div>

                                    @error('nama_tamu')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="alasan_tidak_hadir" class="col-sm-3 col-form-label">
                                Alasan Tidak Hadir
                            </label>
                            <div class="col-sm-8">
                                <select name="alasan_tidak_hadir" class="form-control select2 @error('alasan_tidak_hadir') is-invalid @enderror" id="alasan_tidak_hadir">
                                    <option value=""></option>
                                    <option {{ old('alasan_tidak_hadir') == "Jarak Jauh" ? 'selected' : '' }} value="Jarak Jauh">Jarak Jauh</option>
                                    <option {{ old('alasan_tidak_hadir') == "Ada Keperluan" ? 'selected' : '' }} value="Ada Keperluan">Ada Keperluan</option>
                                    <option {{ old('alasan_tidak_hadir') == "Jadwal Padat" ? 'selected' : '' }} value="Jadwal Padat">Jadwal Padat</option>
                                    <option {{ old('alasan_tidak_hadir') == "Sedang Sakit" ? 'selected' : '' }} value="Sedang Sakit">Sedang Sakit</option>
                                    <option {{ old('alasan_tidak_hadir') == "Lainnya" ? 'selected' : '' }} value="Lainnya">Lainnya</option>
                                </select>

                                @error('alasan_tidak_hadir')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="catatan" class="col-sm-3 col-form-label">
                                Catatan
                            </label>
                            <div class="col-sm-6">
                                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" id="catatan" rows="5"
                                    placeholder="Masukkan Catatan">{{ old('catatan') }}</textarea>

                                @error('catatan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
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
@endpush

@push('style-js')
    <script src="{{ asset('templating/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '- Pilih -'
            });

            let wakilGuestPublicId = "{{ old('wakil_guest_public_id', '') }}";

            if (wakilGuestPublicId !== '') {
                $('#selectWakilWrapper').hide();
                $('#selectWakilPublicWrapper').show();
            } else {
                $('#selectWakilPublicWrapper').hide();
                $('#selectWakilWrapper').show();
            }

            $('#btnTamuLain').click(function() {
                $('#selectTamuWrapper').hide();
                $('#inputTamuWrapper').show();

                $('#guest_id').val(null).trigger('change');
            });

            $('#btnPilihTamu').click(function() {
                $('#inputTamuWrapper').hide();
                $('#selectTamuWrapper').show();

                $('#nama_tamu').val('');
            });

            $('#btnWakilTamuLuar').click(function() {
                $('#selectWakilWrapper').hide();
                $('#selectWakilPublicWrapper').show();

                $('#wakil_id').val(null).trigger('change');
            });

            $('#btnPilihWakilUndangan').click(function() {
                $('#selectWakilPublicWrapper').hide();
                $('#selectWakilWrapper').show();

                $('#wakil_guest_public_id').val(null).trigger('change');
            });

        });
    </script>
@endpush
