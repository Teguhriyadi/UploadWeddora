@extends('layouts.master')

@push('title-modules', 'Master Titip Kehadiran')

@push('style-css')
    @include("layouts.components.dataTable.css.dataTable-css")
@endpush

@push('content-modules')
    <div class="card shadow mb-4">
        @notadmin
            <div class="card-header py-3">
                <a href="{{ url('/modules/titip-kehadiran/create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>

                <button type="button" class="btn btn-danger btn-sm" id="btnDeleteSelected">
                    <i class="fa fa-trash"></i> Hapus Terpilih
                </button>
            </div>
        @endnotadmin
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            @notadmin
                                <th class="text-center">
                                    <input type="checkbox" id="checkAll">
                                </th>
                                <th class="text-center">No.</th>
                                <th class="text-center">Aksi</th>
                            @endnotadmin
                            <th>Wakil Tamu</th>
                            <th>Nama Tamu Berhalangan</th>
                            <th class="text-center">Alasan</th>
                            <th>Catatan</th>
                            <th>Petugas</th>
                            <th class="text-center">Waktu Penitipan</th>
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
                    url: "{{ url('/modules/titip-kehadiran') }}",
                },
                columns: [
                    @notadmin
                        {
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
                    @endnotadmin
                    {
                        data: 'wakil_tamu',
                        name: 'wakil_tamu'
                    },
                    {
                        data: 'nama_tamu_berhalangan',
                        name: 'nama_tamu_berhalangan'
                    },
                    {
                        data: 'alasan_tidak_hadir',
                        name: 'alasan_tidak_hadir'
                    },
                    {
                        data: 'catatan',
                        name: 'catatan'
                    },
                    {
                        data: 'petugas',
                        name: 'petugas'
                    },
                    {
                        data: 'waktu_penitipan',
                        name: 'waktu_penitipan',
                        className: 'text-center'
                    }
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
