@extends('layouts.master')

@push('title-modules', 'Master Tamu Undangan')

@push('style-css')

    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            border-radius: 12px;
        }

        #dataTable {
            width: 100% !important;
            min-width: 1100px;
        }

        #dataTable th,
        #dataTable td {
            white-space: nowrap;
            vertical-align: middle;
        }

        #dataTable thead th {
            background: #f8f9fc;
        }

        div.dataTables_wrapper {
            width: 100%;
        }

        div.dataTables_wrapper .dataTables_length,
        div.dataTables_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }

        div.dataTables_wrapper .dataTables_paginate {
            margin-top: 15px;
        }

        div.dataTables_wrapper .dataTables_info {
            padding-top: 15px;
        }

        @media (max-width: 768px) {

            div.dataTables_wrapper .dataTables_length,
            div.dataTables_wrapper .dataTables_filter,
            div.dataTables_wrapper .dataTables_info,
            div.dataTables_wrapper .dataTables_paginate {
                text-align: center;
                float: none !important;
            }

            div.dataTables_wrapper .dataTables_filter input {
                width: 100%;
                margin-left: 0 !important;
                margin-top: 10px;
            }
        }
    </style>
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

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ url('/modules/guest/create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Data
            </a>
            {{-- <a href="{{ url('/modules/guest/download') }}" class="btn btn-success btn-sm">
                <i class="fa fa-download"></i> Download Data
            </a> --}}
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-book"></i> Upload Data
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Link Undangan</th>
                            <th class="text-center">Kategori</th>
                            <th>Kode Token</th>
                            <th>Nama</th>
                            <th>Nama Undangan</th>
                            <th>Status Undangan Terkirim</th>
                            <th>Kehadiran</th>
                            <th>Relasi</th>
                            <th class="text-center">Jenis Undangan</th>
                            <th>Keterangan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">

        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fa fa-book"></i> Upload Data
                    </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ url('/modules/guest/upload-file') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file_upload">
                                Upload File
                                <small class="text-danger">*</small>
                            </label>
                            <input type="file" class="form-control" name="file_upload" id="file_upload">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                            <i class="fa fa-times"></i> Batal
                        </button>

                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-book"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('style-js')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
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
                ajax: "{{ url('/modules/guest') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: "link_undangan",
                        name: "link_undangan",
                        className: 'text-center'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori.nama_kategori',
                        className: 'text-center'
                    },
                    {
                        data: 'kode_token',
                        name: 'kode_token'
                    },
                    {
                        data: 'nama_tamu',
                        name: 'nama_tamu'
                    },
                    {
                        data: 'nama_undangan',
                        name: 'nama_undangan'
                    },
                    {
                        data: 'status_undangan',
                        name: 'status_undangan'
                    },
                    {
                        data: 'kehadiran',
                        name: 'kehadiran',
                        className: 'text-center'
                    },
                    {
                        data: 'relasi',
                        name: 'relasi'
                    },
                    {
                        data: 'jenis_undangan',
                        name: 'jenis_undangan',
                        className: 'text-center'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });
        });
    </script>
    <script>
        $(document).on('change', '.change-kehadiran', function() {

            let id = $(this).data('id');
            let value = $(this).val();
            let select = $(this);

            Swal.fire({
                title: 'Apakah ingin mengubah data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, ubah',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: '{{ url('/modules/guest/update-kehadiran') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: id,
                            kehadiran: value
                        },
                        success: function(response) {

                            Swal.fire('Berhasil', response.message, 'success');

                            // 🔥 penting: update data-old
                            select.data('old', value);

                        },
                        error: function(xhr) {

                            Swal.fire('Gagal', xhr.responseJSON?.message || 'Error', 'error');

                            select.val(select.data('old'));

                        }
                    });

                } else {
                    select.val(select.data('old'));
                }
            });

        });

        $(document).on('change', '.change-status-undangan', function() {

            let id = $(this).data('id');
            let value = $(this).val();

            $.ajax({
                url: '{{ url('/modules/guest/update-status-undangan') }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    status_undangan: value
                },
                success: function(response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                },

                error: function(xhr) {

                    let message = 'Terjadi kesalahan';

                    if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: message
                    });

                }
            });

        });
    </script>
@endpush
