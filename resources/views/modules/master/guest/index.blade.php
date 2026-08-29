@extends('layouts.master')

@push('title-modules', 'Master Tamu Undangan')

@push('style-css')
    @include("layouts.components.dataTable.css.dataTable-css")
@endpush

@push('content-modules')
    <div class="card shadow mb-4">
        @notadmin
            <div class="card-header py-3">
                <a href="{{ url('/modules/guest/create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
                <a href="#" class="btn btn-success btn-sm" id="btnDownload">
                    <i class="fa fa-download"></i> Download Data
                </a>
                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-book"></i> Upload Data
                </button>
                <a href="{{ url('/modules/guest/generate-all') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-download"></i> Generate Kategori Cetak
                </a>

                <button type="button" class="btn btn-danger btn-sm" id="btnDeleteSelected">
                    <i class="fa fa-trash"></i> Hapus Terpilih
                </button>
            </div>
        @endnotadmin
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="filterKehadiranNikah" class="form-label"> Status Kehadiran Nikah </label>
                    <select id="filterKehadiranNikah" class="form-control form-control-sm">
                        <option value="all">Semua Kehadiran</option>
                        <option value="1">Sudah Hadir</option>
                        <option value="0">Tidak Hadir</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filterKehadiran" class="form-label"> Status Kehadiran </label>
                    <select id="filterKehadiran" class="form-control form-control-sm">
                        <option value="null">Belum Ditentukan</option>
                        <option value="1">Pasti Hadir</option>
                        <option value="0">Kemungkinan Tidak Hadir</option>
                        <option value="2">Tidak Hadir</option>
                        <option value="" selected>Semua Kehadiran</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filterKeterangan" class="form-label"> Keterangan Keluarga </label>
                    <select id="filterKeterangan" class="form-control form-control-sm">
                        <option value="" selected>Semua Keterangan</option>
                        <option value="CPP">CPP</option>
                        <option value="CPW">CPW</option>
                    </select>
                </div>
            </div>
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
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Status</th>
                            <th>Nama Event</th>
                            <th>Kode Token</th>
                            <th>Nama</th>
                            <th>Nama Undangan</th>
                            <th>Status Undangan Terkirim</th>
                            <th>Kehadiran</th>
                            <th>Relasi</th>
                            <th class="text-center">Jenis Undangan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade app-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">

        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <span class="modal-title-icon"><i class="fa fa-book"></i></span>
                        Upload Data
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

                        <div class="modal-note">
                            Gunakan file Excel yang sesuai format agar proses import tamu berjalan lebih rapi dan cepat.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
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
                    url: "{{ url('/modules/guest') }}",
                    data: function(d) {
                        d.kehadiran = $('#filterKehadiran').val();
                        d.keterangan = $('#filterKeterangan').val();
                        d.status = $('#filterKehadiranNikah').val();
                        d.event = $('#filterEvent').val();
                    }
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
                        data: 'kategori',
                        name: 'kategori.nama_kategori',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'event',
                        name: 'event'
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
                    }
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
