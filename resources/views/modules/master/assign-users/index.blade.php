@extends('layouts.master')

@push('title-modules', 'Master Assign Users')

@push('style-css')
    @include("layouts.components.dataTable.css.dataTable-css")
@endpush

@push('content-modules')
    <div class="card shadow mb-4">
        @if (Auth::user()->role->nama_role == 'Administrator')
            <div class="card-header py-3">
                <a href="{{ url('/modules/assign-users/create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Aksi</th>
                            <th class="text-center">Nama Event</th>
                            <th class="text-center">Nama User</th>
                            <th>Jabatan</th>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
                    url: "{{ url('/modules/assign-users') }}",
                    // data: function(d) {
                    //     d.kehadiran = $('#filterKehadiran').val();
                    //     d.keterangan = $('#filterKeterangan').val();
                    //     d.status = $('#filterKehadiranNikah').val()
                    // }
                },
                columns: [
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
                        data: 'event',
                        name: 'event',
                        className: 'text-center'
                    },
                    {
                        data: 'user',
                        name: 'user',
                        className: 'text-center'
                    },
                    {
                        data: 'jabatan',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ]
            });
        });

        $('#filterKehadiran, #filterKeterangan, #filterKehadiranNikah').change(function() {
            table.ajax.reload();
        });

        $('#btnDownload').click(function(e) {
            e.preventDefault();

            let kehadiran = $('#filterKehadiran').val();
            let keterangan = $('#filterKeterangan').val();
            let status = $('#filterKehadiranNikah').val();

            let url =
                "{{ url('/modules/guest/download') }}" +
                "?kehadiran=" + encodeURIComponent(kehadiran ?? '') +
                "&keterangan=" + encodeURIComponent(keterangan ?? '') +
                "&status=" + encodeURIComponent(status ?? '');

            window.location.href = url;
        });

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

        $(document).on('click', '.change-status-kehadiran', function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            let value = $(this).data('value');

            let text = value == 1 ?
                'Sudah Hadir' :
                'Belum Hadir';

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Ubah status menjadi "' + text + '" ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ubah',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ url('/modules/guest/update-status-kehadiran') }}",
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: id,
                            status_kehadiran: value
                        },
                        success: function(response) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(() => {
                                location.reload();
                            });

                            $('#datatable').DataTable().ajax.reload(null, false);
                        }
                    });

                }
            });
        });

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
                        url: "{{ url('/modules/guest/delete-selected') }}",
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
