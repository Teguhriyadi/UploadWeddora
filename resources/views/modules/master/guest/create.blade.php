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
        <div class="col-md-10">
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
                            <label for="kategori_id" class="col-sm-2 col-form-label">
                                Kategori Tamu
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-10">
                                <select name="kategori_id"
                                    class="form-control select2 @error('kategori_id') is-invalid @enderror"
                                    id="kategori_id">
                                    <option value="">- Pilih -</option>
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
                            <label for="nama_tamu" class="col-sm-2 col-form-label">
                                Nama Tamu
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-10">
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
                            <label for="keluarga" class="col-sm-2 col-form-label">
                                Keluarga
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('keluarga') is-invalid @enderror"
                                    name="keluarga" id="keluarga" placeholder="Masukkan Nama Keluarga"
                                    value="{{ old('keluarga') }}">

                                @error('keluarga')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="jumlah_undangan" class="col-sm-2 col-form-label">Jumlah Undangan</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="jumlah_undangan" id="jumlah_undangan"
                                    placeholder="0" min="1" value="{{ old('jumlah_undangan') }}">

                                @error('jumlah_undangan')
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
                placeholder: 'Pilih Kategori'
            });
        });
    </script>
@endpush
