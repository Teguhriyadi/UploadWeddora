@extends('layouts.master')

@push('title-modules', 'Master Event')

@push('style-css')
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
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="{{ url('/modules/event/create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Tambah Data
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Nama Event</th>
                                    <th class="text-center">Cabang</th>
                                    <th>Nama CPP</th>
                                    <th>Nama CPW</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Is Active</th>
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
                ajax: "{{ url('/modules/event') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '50px'
                    },
                    {
                        data: 'nama_event',
                        name: 'nama_event',
                        width: '100px'
                    },
                    {
                        data: 'cabang',
                        name: 'cabang',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'nama_cpp',
                        name: 'nama_cpp',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'nama_cpw',
                        name: 'nama_cpw',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi',
                        orderable: false,
                        searchable: false,
                        width: '100px'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '100px'
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

        $(document).on('click', '.btn-toggle-status', function() {

            let id = $(this).data('id');
            let status = $(this).data('status');

            $.ajax({
                url: "{{ url('/modules/kategori/toggle-status') }}/"  + id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status
                },
                success: function() {
                    location.reload();
                },
                error: function() {
                    alert('Gagal update status');
                }
            });

        });
    </script>
@endpush
