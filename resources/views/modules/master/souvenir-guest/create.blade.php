@extends('layouts.master')

@push('title-modules', 'Master Tamu Undangan')

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
                    <a href="{{ url('/modules/titip-kado') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <form action="{{ url('/modules/titip-kado') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="guest_id" class="col-sm-2 col-form-label">
                                Nama Tamu
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <div id="selectTamuWrapper">
                                    <div class="input-group">
                                        <select name="guest_id" id="guest_id"
                                            class="form-select select2 @error('guest_id') is-invalid @enderror">
                                            <option value="">- Pilih Tamu -</option>

                                            @foreach ($guest as $guest)
                                                <option {{ old('guest_id') == $guest->id ? 'selected' : '' }} value="{{ $guest->id }}">
                                                    {{ $guest->nama_tamu }} - {{ $guest->nama_undangan }} - Relasi : {{ $guest->relasi }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="button" class="btn btn-primary btn-sm mt-2" id="btnTamuLuar">
                                            Dari Daftar Tamu Luar?
                                        </button>
                                    </div>

                                    @error('guest_id')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div id="selectTamuLuarWrapper" style="display: none;">
                                    <div class="input-group">
                                        <select name="guest_public_id" id="guest_public_id"
                                            class="form-select select2 @error('guest_public_id') is-invalid @enderror">
                                            <option value="">- Pilih Tamu Luar -</option>

                                            @foreach ($guest_public as $tamuLuar)
                                                <option {{ old('guest_public_id') == $tamuLuar->id ? 'selected' : '' }} value="{{ $tamuLuar->id }}">
                                                    {{ $tamuLuar->nama }}
                                                    @if (!empty($tamuLuar->nomor_handphone))
                                                        - {{ $tamuLuar->nomor_handphone }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="button" class="btn btn-secondary" id="btnPilihTamuUndangan">
                                            Pilih dari Tamu Undangan
                                        </button>
                                    </div>

                                    @error('guest_public_id')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama_kado" class="col-sm-2 col-form-label">
                                Nama Kado
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama_kado') is-invalid @enderror"
                                    name="nama_kado" id="nama_kado" placeholder="Masukkan Kado"
                                    value="{{ old('nama_kado') }}">

                                @error('nama_kado')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="qty" class="col-sm-2 col-form-label">
                                QTY
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control @error('qty') is-invalid @enderror" name="qty"
                                    id="qty" placeholder="0" value="{{ old('qty') }}">

                                @error('jenis_undangan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="keterangan" class="col-sm-2 col-form-label">
                                Keterangan
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" rows="5" placeholder="Masukkan Keterangan">{{ old('keterangan') }}</textarea>

                                @error('keterangan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="foto" class="col-sm-2 col-form-label">
                                Foto Kado
                            </label>
                            <div class="col-sm-6">
                                <input type="file" class="form-control @error('foto') is-invalid @enderror"
                                    name="foto" id="foto">

                                @error('foto')
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

            let guestPublicId = "{{ old('guest_public_id', '') }}";

            if (guestPublicId !== '') {
                $('#selectTamuWrapper').hide();
                $('#selectTamuLuarWrapper').show();
            }

            $('#btnTamuLuar').click(function() {
                $('#selectTamuWrapper').hide();
                $('#selectTamuLuarWrapper').show();

                $('#guest_id').val(null).trigger('change');
            });

            $('#btnPilihTamuUndangan').click(function() {
                $('#selectTamuLuarWrapper').hide();
                $('#selectTamuWrapper').show();

                $('#guest_public_id').val(null).trigger('change');
            });

        });
    </script>
@endpush
