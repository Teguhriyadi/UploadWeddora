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
                    <a href="{{ url('/modules/guest') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <form action="{{ url('/modules/guest') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="kategori_id" class="col-sm-2 col-form-label">Kategori Tamu</label>
                            <div class="col-sm-6">
                                <select name="kategori_id"
                                    class="form-control select2 @error('kategori_id') is-invalid @enderror"
                                    id="kategori_id">
                                    <option value=""></option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item['id'] }}"
                                            {{ old('kategori_id') == $item['id'] ? 'selected' : '' }}>
                                            {{ $item['nama_kategori'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('kategori_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">
                                Nama Tamu
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama_tamu') is-invalid @enderror"
                                    name="nama_tamu" id="nama_tamu" placeholder="Masukkan Nama Tamu"
                                    value="{{ old('nama_tamu') }}">

                                @error('nama_tamu')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama_undangan" class="col-sm-2 col-form-label">
                                Nama di Undangan
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama_undangan') is-invalid @enderror"
                                    name="nama_undangan" id="nama_undangan" placeholder="Masukkan di Undangan"
                                    value="{{ old('nama_undangan') }}">

                                @error('nama_undangan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="relasi" class="col-sm-2 col-form-label">
                                Relasi
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <select name="relasi" class="form-control select2 @error('relasi') is-invalid @enderror" id="relasi">
                                    <option value="">- Pilih -</option>
                                    <option {{ old('relasi') == "Saudara" ? 'selected' : '' }} value="Saudara">Saudara</option>
                                    <option {{ old('relasi') == "Teman Kerja" ? 'selected' : '' }} value="Teman Kerja">Teman Kerja</option>
                                    <option {{ old('relasi') == "Teman SMA" ? 'selected' : '' }} value="Teman SMA">Teman SMA</option>
                                    <option {{ old('relasi') == "Relasi Ortu" ? 'selected' : '' }} value="Relasi Ortu">Relasi Ortu</option>
                                    <option {{ old('relasi') == "Teman Kuliah" ? 'selected' : '' }} value="Teman Kuliah">Teman Kuliah</option>
                                    <option {{ old('relasi') == "Atasan" ? 'selected' : '' }} value="Atasan">Atasan</option>
                                </select>

                                @error('relasi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="jenis_undangan" class="col-sm-2 col-form-label">
                                Jenis Undangan
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <select name="jenis_undangan" class="form-control select2 @error('jenis_undangan') is-invalid @enderror" id="jenis_undangan">
                                    <option value="">- Pilih -</option>
                                    <option {{ old('jenis_undangan') == "Cetak" ? 'selected' : '' }} value="Cetak">Cetak</option>
                                    <option {{ old('jenis_undangan') == "Digital" ? 'selected' : '' }} value="Digital">Digital</option>
                                </select>

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
                                <select name="keterangan" class="form-control select2 @error('keterangan') is-invalid @enderror" id="keterangan">
                                    <option value="">- Pilih -</option>
                                    <option {{ old('keterangan') == 'CPP' ? 'selected' : '' }} value="CPP">CPP</option>
                                    <option {{ old('keterangan') == 'CPW' ? 'selected' : '' }} value="CPW">CPW</option>
                                </select>

                                @error('keterangan')
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
                placeholder: '- Pilih -',
                allowClear: true
            });
        });
    </script>
@endpush
