@extends('layouts.master')

@push('title-modules', 'Master Tema')

@push('style-css')
    <link href="{{ asset('templating/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('templating/select2/css/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" />
    @include("layouts.components.css.is-invalid")
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
                    <a href="{{ url('/modules/landing-page/tema') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <form action="{{ url('/modules/landing-page/tema/' . $edit['id']) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="lp_kategori_id" class="col-sm-2 col-form-label">
                                Nama Kategori
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-4">
                                <select name="lp_kategori_id"
                                    class="form-control select2 @error('lp_kategori_id') is-invalid @enderror" id="lp_kategori_id">
                                    <option value=""></option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item['id'] }}"
                                            {{ old('lp_kategori_id', $edit['lp_kategori_id']) == $item['id'] ? 'selected' : '' }}>
                                            {{ $item['nama_kategori'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('lp_kategori_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">
                                Nama Tema
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    name="nama" id="nama" placeholder="Masukkan Nama Tema"
                                    value="{{ old('nama', $edit['nama']) }}">

                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="subtitle" class="col-sm-2 col-form-label">
                                Subtitle
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                                    name="subtitle" id="subtitle" placeholder="Masukkan Tema Subtitle"
                                    value="{{ old('subtitle', $edit['subtitle']) }}">

                                @error('subtitle')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="deskripsi" class="col-sm-2 col-form-label">
                                Deskripsi
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" rows="5" placeholder="Masukkan Deskripsi">{{ old('deskripsi', $edit['deskripsi']) }}</textarea>

                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="badge" class="col-sm-2 col-form-label">
                                Badge
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control @error('badge') is-invalid @enderror"
                                    id="badge" name="badge" placeholder="Masukkan Badge" value="{{ old('badge', $edit['badge']) }}">

                                @error('badge')
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
