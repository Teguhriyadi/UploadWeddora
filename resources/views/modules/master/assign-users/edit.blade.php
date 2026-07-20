@extends('layouts.master')

@push('title-modules', 'Master Assign Users')

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
                    <a href="{{ url('/modules/assign-users') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Kembali
                    </a>
                </div>
                <form action="{{ url('/modules/assign-users/' . $edit['id']) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="event_id" class="col-sm-2 col-form-label">
                                Nama Event
                                <small class="text-danger">*</small>
                            </label>
                            <div class="col-sm-6">
                                <select name="event_id"
                                    class="form-control select2 @error('event_id') is-invalid @enderror"
                                    id="event_id">
                                    <option value=""></option>
                                    @foreach ($event as $item)
                                        <option value="{{ $item['id'] }}"
                                            {{ old('event_id', $edit['event_id']) == $item['id'] ? 'selected' : '' }}>
                                            {{ $item['nama_event'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('event_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="user_id" class="col-sm-2 col-form-label">
                                Nama Users
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <select name="user_id"
                                    class="form-control select2 @error('user_id') is-invalid @enderror"
                                    id="user_id">
                                    <option value=""></option>
                                    @foreach ($users as $item)
                                        <option value="{{ $item['id'] }}"
                                            {{ old('event_id', $edit['user_id']) == $item['id'] ? 'selected' : '' }}>
                                            {{ $item['nama'] }} - {{ $item['role']['nama_role'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('nama_tamu')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="jabatan" class="col-sm-2 col-form-label">
                                Jabatan
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <select name="jabatan"
                                    class="form-control select2 @error('jabatan') is-invalid @enderror"
                                    id="jabatan">
                                    <option value="">- Pilih Jabatan -</option>
                                    <option {{ old('jabatan', $edit['jabatan']) == "CUSTOMER" ? 'selected' : '' }} value="CUSTOMER">CUSTOMER</option>
                                    <option {{ old('jabatan', $edit['jabatan']) == "PJ" ? 'selected' : '' }} value="PJ">PJ</option>
                                    <option {{ old('jabatan', $edit['jabatan']) == "PETUGAS" ? 'selected' : '' }} value="PETUGAS">PETUGAS</option>
                                </select>

                                @error('jabatan')
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
