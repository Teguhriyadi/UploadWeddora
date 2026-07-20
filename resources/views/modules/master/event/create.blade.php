@extends('layouts.master')

@push('title-modules', 'Master Event')

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
                    <a href="{{ url('/modules/event') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <form action="{{ url('/modules/event') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="cabang_id" class="col-sm-2 col-form-label">
                                Nama Cabang
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-4">
                                <select name="cabang_id"
                                    class="form-control select2 @error('cabang_id') is-invalid @enderror"
                                    id="cabang_id">
                                    <option value=""></option>
                                    @foreach ($cabang as $item)
                                        <option value="{{ $item['id'] }}"
                                            {{ old('cabang_id') == $item['id'] ? 'selected' : '' }}>
                                            {{ $item['nama'] }}
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
                            <label for="nama_cpp" class="col-sm-2 col-form-label">
                                Nama CPP
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama_cpp') is-invalid @enderror"
                                    name="nama_cpp" id="nama_cpp" placeholder="Masukkan Nama Calon Pengantin Pria"
                                    value="{{ old('nama_cpp') }}">

                                @error('nama_cpp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama_cpw" class="col-sm-2 col-form-label">
                                Nama CPW
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama_cpw') is-invalid @enderror"
                                    name="nama_cpw" id="nama_cpw" placeholder="Masukkan Nama Calon Pengantin Wanita"
                                    value="{{ old('nama_cpw') }}">

                                @error('nama_cpw')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama_event" class="col-sm-2 col-form-label">
                                Nama Event
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('nama_event') is-invalid @enderror" name="nama_event" id="nama_event" placeholder="Masukkan Nama Event" value="{{ old('nama_event') }}">

                                @error('nama_event')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tanggal" class="col-sm-2 col-form-label">
                                Tanggal
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal') }}">

                                @error('tanggal')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="lokasi" class="col-sm-2 col-form-label">
                                Lokasi
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" placeholder="Masukkan Lokasi">

                                @error('lokasi')
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
