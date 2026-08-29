@extends('layouts.master')

@push('title-modules', 'Master Aktivitas Login')

@push('style-css')
    @include("layouts.components.dataTable.css.dataTable-css")
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
    @include("layouts.components.dataTable.js.dataTable-js")
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
