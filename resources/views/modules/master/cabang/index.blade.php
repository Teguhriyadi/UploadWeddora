@extends('layouts.master')

@push('title-modules', 'Master Cabang')

@push('style-css')

    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @include("layouts.components.css.is-invalid")
    @include("layouts.components.dataTable.css.dataTable-css")
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
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fa fa-plus"></i> Tambah
                    </h6>
                </div>
                <form action="{{ url('/modules/cabang') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">
                                Nama Cabang
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama"
                                class="form-control @error('nama') is-invalid @enderror" id="nama"
                                placeholder="Masukkan Nama Cabang" value="{{ old('nama') }}">

                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kota">
                                Kota
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="kota"
                                class="form-control @error('kota') is-invalid @enderror" id="kota"
                                placeholder="Masukkan Kota Cabang" value="{{ old('kota') }}">

                            @error('kota')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="alamat">
                                Alamat
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="alamat"
                                class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                placeholder="Masukkan Alamat Cabang" value="{{ old('alamat') }}">

                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
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
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        DATA CABANG
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Nama Cabang</th>
                                    <th>Kota</th>
                                    <th>Daerah</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('style-js')
    @include("layouts.components.dataTable.js.dataTable-js")
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                autoWidth: false,
                scrollX: true,
                pageLength: 25,
                lengthMenu: [
                    [25, 50, 75, 100],
                    [25, 50, 75, 100]
                ],
                ajax: "{{ url('/modules/cabang') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '50px'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        width: '100px'
                    },
                    {
                        data: 'kota',
                        name: 'kota'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '100px'
                    }
                ]
            });
        });
    </script>
@endpush
