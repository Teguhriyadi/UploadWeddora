@extends('layouts.master')

@push('title-modules', 'Master Titip Kado')

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
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ url('/modules/titip-kado/create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Data
            </a>

            <button type="button" class="btn btn-danger btn-sm" id="btnDeleteSelected">
                <i class="fa fa-trash"></i> Hapus Terpilih
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="checkAll">
                            </th>
                            <th class="text-center">No.</th>
                            <th class="text-center">Aksi</th>
                            <th>Nama Tamu</th>
                            <th>Nama Kado</th>
                            <th class="text-center">QTY</th>
                            <th>Keterangan</th>
                            <th class="text-center">Foto</th>
                            <th class="text-center">Status</th>
                            <th>Petugas Penerima</th>
                            <th class="text-center">Waktu Dititipkan</th>
                            <th class="text-center">Waktu Diterima Pengantin</th>
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

    <div class="modal fade" id="previewImageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Preview Foto
                    </h5>

                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    <img id="previewImage" src="" class="img-fluid rounded" style="max-height:70vh;">
                </div>

            </div>

        </div>
    </div>
@endpush

@push('style-js')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            table = $("#dataTable").DataTable({
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
                ajax: {
                    url: "{{ url('/modules/titip-kado') }}",
                },
                columns: [{
                        data: 'checkbox',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama_tamu',
                        name: 'nama_tamu'
                    },
                    {
                        data: 'nama_kado',
                        name: 'nama_kado'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'foto',
                        name: 'foto'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'petugas',
                        name: 'petugas'
                    },
                    {
                        data: 'waktu_dititipkan',
                        name: 'waktu_dititipkan',
                        className: 'text-center'
                    },
                    {
                        data: 'waktu_diterima_pengantin',
                        name: 'waktu_diterima_pengantin'
                    },
                ]
            });
        });
    </script>
    <script>
        $(document).on('change', '#checkAll', function() {
            $('.row-checkbox').prop('checked', $(this).is(':checked'));
        });

        $('#btnDeleteSelected').click(function() {

            let ids = [];

            $('.row-checkbox:checked').each(function() {
                ids.push($(this).val());
            });

            if (ids.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pilih minimal 1 data'
                });
                return;
            }

            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dipilih akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ url('/modules/titip-kado/delete-selected') }}",
                        type: "POST",
                        data: {
                            ids: ids,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });

                            $('#checkAll').prop('checked', false);

                            $('#dataTable').DataTable().ajax.reload();
                        }
                    });

                }

            });

        });

        $(document).on('submit', '.delete-form', function(e) {

            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {
                    form.submit();
                }

            });

        });

        $(document).on('click', '.preview-image', function() {

            let imageUrl = $(this).data('image');

            $('#previewImage').attr('src', imageUrl);

            $('#previewImageModal').modal('show');
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}'
            });
        @endif
    </script>
@endpush
