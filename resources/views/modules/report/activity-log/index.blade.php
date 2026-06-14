@extends('layouts.master')

@push('title-modules', 'Master Aktivitas Login')

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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Module</th>
                            <th>Aksi</th>
                            <th>User ID</th>
                            <th>Asal Model</th>
                            <th>ID Model</th>
                            <th class="text-center">Method</th>
                            <th>URL</th>
                            <th class="text-center">IP Address</th>
                            <th>User Asal</th>
                            <th>Data Sebelum</th>
                            <th>Data Sesudah</th>
                            <th>Meta</th>
                            <th class="text-center">Logged At</th>
                            <th class="text-center">Created At</th>
                            <th class="text-center">Updated At</th>
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
                    url: "{{ url('/modules/riwayat-aktifitas') }}"
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'module',
                        name: 'module',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'user',
                        name: 'user',
                    },
                    {
                        data: 'subject_type',
                        name: 'subject_type'
                    },
                    {
                        data: 'subject_id',
                        name: 'subject_id'
                    },
                    {
                        data: 'method',
                        name: 'method',
                        className: 'text-center'
                    },
                    {
                        data: 'url',
                        name: 'url',
                    },
                    {
                        data: 'ip',
                        name: 'ip',
                        className: 'text-center'
                    },
                    {
                        data: 'user_agent',
                        name: 'user_agent',
                        className: 'text-center'
                    },
                    {
                        data: 'before',
                        name: 'before'
                    },
                    {
                        data: 'after',
                        name: 'after'
                    },
                    {
                        data: 'meta',
                        name: 'meta'
                    },
                    {
                        data: 'logged_at',
                        name: 'logged_at'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    }
                ]
            });
        });
    </script>
@endpush
