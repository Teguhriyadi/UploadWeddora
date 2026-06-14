@extends('layouts.master')

@push('title-modules', 'Riwayat Tamu')

@push('style-css')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .table-responsive {
            width: 100%;
        }

        #dataTableInvitation,
        #dataTablePublic {
            width: 100% !important;
        }

        .dataTables_wrapper {
            width: 100%;
        }

        .dataTables_wrapper .row {
            margin-left: 0;
            margin-right: 0;
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
            <h6 class="m-0 font-weight-bold text-primary">
                DATA RIWAYAT KEHADIRAN
            </h6>
        </div>

        <div class="card-body">
            <form id="filterForm">
                <input type="hidden" name="tab" id="tab_input" value="{{ request('tab', 'tamu-undangan') }}">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Dari Tanggal</label>
                        <input type="date" class="form-control" name="dari" value="{{ request('dari', $dari) }}">
                    </div>
                    <div class="col-md-3">
                        <label>Sampai Tanggal</label>
                        <input type="date" class="form-control" name="sampai" value="{{ request('sampai', $sampai) }}">
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-search"></i> FILTER
                        </button>
                    </div>
                </div>
            </form>

            <hr>

            <a href="#" class="btn btn-success btn-sm mb-3" id="btnDownload">
                <i class="fa fa-download"></i> Download Data
            </a>

            <ul class="nav nav-tabs" id="myTab">
                <li class="nav-item">
                    <a class="nav-link {{ request('tab', 'tamu-undangan') == 'tamu-undangan' ? 'active' : '' }}"
                        data-toggle="tab" href="#tamu-undangan">
                        Tamu Undangan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request('tab') == 'tamu-luar' ? 'active' : '' }}" data-toggle="tab"
                        href="#tamu-luar">
                        Tamu Luar
                    </a>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane fade {{ request('tab', 'tamu-undangan') == 'tamu-undangan' ? 'show active' : '' }}"
                    id="tamu-undangan">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableInvitation">
                            <thead>
                                <tr>
                                    <th style="width: 5%" class="text-center">No</th>
                                    <th style="width: 10%" class="text-center">Foto Kehadiran</th>
                                    <th>Nama di Undangan</th>
                                    <th>Nama Tamu</th>
                                    <th>Relasi</th>
                                    <th class="text-center">Metode</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Tanggal Waktu</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade {{ request('tab') == 'tamu-luar' ? 'show active' : '' }}" id="tamu-luar">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTablePublic">
                            <thead>
                                <tr>
                                    <th style="width: 5%" class="text-center">No</th>
                                    <th style="width: 10%" class="text-center">Foto Kehadiran</th>
                                    <th>Nama Tamu</th>
                                    <th>No Handphone</th>
                                    <th>Pekerjaan</th>
                                    <th>Alamat</th>
                                    <th>Relasi</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Waktu Checkin</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fa fa-eye"></i> Lihat Foto Kehadiran
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modal-content-foto-kehadiran">

                </div>
            </div>
        </div>
    </div>

@endpush

@push('style-js')
    <script src="{{ asset('templating/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $('#filterForm').submit(function(e) {
            e.preventDefault();

            tableInvitation.ajax.reload();
            tablePublic.ajax.reload();
        });

        $(document).ready(function() {
            $('.nav-tabs a').on('shown.bs.tab', function(e) {

                let tab = $(e.target)
                    .attr('href')
                    .replace('#', '');

                $('#tab_input').val(tab);

            });
        });

        $('#btnDownload').click(function(e) {

            e.preventDefault();

            let dari = $('input[name="dari"]').val();
            let sampai = $('input[name="sampai"]').val();
            let tab = $('#tab_input').val();

            let url =
                "{{ url('/modules/history-guest/download') }}" +
                "?dari=" + encodeURIComponent(dari ?? '') +
                "&sampai=" + encodeURIComponent(sampai ?? '') +
                "&tab=" + encodeURIComponent(tab ?? '');

            window.location.href = url;

        });

        function showImage(id) {
            $.ajax({
                url: "{{ url('/modules/history-guest') }}" + "/" + id,
                type: "GET",
                success: function(response) {
                    $("#modal-content-foto-kehadiran").html(response)
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function showImageGuestPublic(id) {
            $.ajax({
                url: "{{ url('/modules/history-guest') }}" + "/" + id + "/guest-public/show-image",
                type: "GET",
                success: function(response) {
                    $("#modal-content-foto-kehadiran").html(response)
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        const tableInvitation = $('#dataTableInvitation').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/modules/history-guest/data/invitation') }}",
                data: function(d) {
                    d.dari = $('input[name="dari"]').val();
                    d.sampai = $('input[name="sampai"]').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,
                    className: 'text-center'
                },
                {
                    data: 'foto',
                    searchable: false,
                    orderable: false,
                    className: 'text-center'
                },
                {
                    data: 'nama_undangan'
                },
                {
                    data: 'nama_tamu'
                },
                {
                    data: 'relasi'
                },
                {
                    data: 'metode',
                    className: 'text-center'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'waktu_checkin',
                    className: 'text-center'
                }
            ]
        });

        const tablePublic = $('#dataTablePublic').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/modules/history-guest/data/public') }}",
                data: function(d) {
                    d.dari = $('input[name="dari"]').val();
                    d.sampai = $('input[name="sampai"]').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,
                    className: 'text-center'
                },
                {
                    data: 'foto',
                    searchable: false,
                    orderable: false,
                    className: 'text-center'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'nomor_handphone'
                },
                {
                    data: 'pekerjaan'
                },
                {
                    data: 'alamat'
                },
                {
                    data: 'relasi'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'waktu_checkin',
                    className: 'text-center'
                }
            ]
        });
    </script>
@endpush
