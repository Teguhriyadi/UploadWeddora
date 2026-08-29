@extends('layouts.master')

@push('title-modules', 'Master Tema')

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
                    <a href="{{ url('/modules/landing-page/tema/create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Tambah Data
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Nama Tema</th>
                                    <th>Kategori</th>
                                    <th>Subtitle</th>
                                    <th>Badge</th>
                                    <th>Deskripsi</th>
                                    <th>Gambar Background</th>
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
                ajax: "{{ url('/modules/landing-page/tema') }}",
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
                        data: 'kategori',
                        name: 'kategori',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'subtitle',
                        name: 'subtitle',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'badge',
                        name: 'badge',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'image',
                        name: 'image',
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
