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
            <a href="{{ url('/modules/guest/download') }}" class="btn btn-success btn-sm">
                <i class="fa fa-download"></i> Download Data
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Kategori</th>
                            <th>Kode Token</th>
                            <th>Nama Tamu</th>
                            <th>Keluarga</th>
                            <th>Jumlah Diundang</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
                        data: 'keluarga',
                        name: 'keluarga'
                    },
                    {
                        data: 'jumlah_undangan',
                        name: 'jumlah_undangan'
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
@endpush
