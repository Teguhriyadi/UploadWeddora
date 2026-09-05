@extends('layouts.master')

@push('title-modules', 'Master Tamu Luar')

@push('style-css')
    @include("layouts.components.dataTable.css.dataTable-css")
@endpush

@push('content-modules')
    <div class="card shadow mb-4">
        @notadmin
            <div class="card-header py-3">
                <a href="{{ url('/modules/guest-public/create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
                @if (Auth::user()->role->nama_role == "Customer")
                    <a href="{{ url('/modules/guest-public/download') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-download"></i> Download Data
                    </a>
                @endif
            </div>
        @endnotadmin
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            @notadmin
                                <th class="text-center">No.</th>
                                <th class="text-center">Aksi</th>
                            @endnotadmin
                            <th class="text-center">Waktu Checkin</th>
                            <th>Nama Tamu</th>
                            <th class="text-center">Jumlah Kedatangan</th>
                            <th class="text-center">Relasi</th>
                            <th class="text-center">Keterangan</th>
                            <th>No. Handphone</th>
                            <th>Pekerjaan</th>
                            <th>Alamat</th>
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
                ajax: "{{ url('/modules/guest-public') }}",
                columns: [
                    @notadmin
                        {
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }, {
                            data: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                    @endnotadmin {
                        data: 'waktu_checkin',
                        name: 'waktu_checkin',
                        className: 'text-center'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'jumlah_kedatangan',
                        name: 'jumlah_kedatangan',
                        className: 'text-center'
                    },
                    {
                        data: 'relasi',
                        name: 'relasi',
                        className: 'text-center'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        className: 'text-center'
                    },
                    {
                        data: 'nomor_handphone',
                        name: 'nomor_handphone'
                    },
                    {
                        data: 'pekerjaan',
                        name: 'pekerjaan'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                ]
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
