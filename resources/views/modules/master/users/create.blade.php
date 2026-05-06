@extends('layouts.master')

@push('title-modules', 'Master Users')

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
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="{{ url('/modules/users') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <form action="{{ url('/modules/users') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">
                                Nama
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    name="nama" id="nama" placeholder="Masukkan Nama" value="{{ old('nama') }}">

                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="username" class="col-sm-2 col-form-label">
                                Username
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                    name="username" id="username" placeholder="Masukkan Username"
                                    value="{{ old('username') }}">

                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">
                                Email
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    name="email" id="email" placeholder="Masukkan Email" value="{{ old('email') }}">

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="role_id" class="col-sm-2 col-form-label">
                                Nama Role
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-10">
                                <select name="role_id" class="form-control select2 @error('role_id') is-invalid @enderror"
                                    id="role_id">
                                    <option value="">- Pilih -</option>
                                    @foreach ($role as $item)
                                        <option value="{{ $item['id'] }}"
                                            {{ old('role_id') == $item['id'] ? 'selected' : '' }}>
                                            {{ $item['nama_role'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('role_id')
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

@push("style-js")
    <script src="{{ asset('templating/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Nama Role'
            });
        });
    </script>
@endpush
