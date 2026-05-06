@extends('layouts.master')

@push('title-modules', 'Riwayat Tamu')

@push('style-css')
    <link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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
            <form method="GET" action="{{ url('/modules/history-guest') }}">
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

            <a href="{{ url('/modules/history-guest/download?dari=' . $dari . '&sampai=' . $sampai . '&tab=' . request('tab', 'tamu-undangan')) }}"
                class="btn btn-success btn-sm mb-3">
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
                                    <th class="text-center">Kategori</th>
                                    <th>Nama Tamu</th>
                                    <th>Keluarga</th>
                                    <th class="text-center">Metode</th>
                                    <th class="text-center">Tanggal Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guest_invitation as $index => $item)
                                    <tr>
                                        <td style="width: 5%" class="text-center">{{ $index + 1 }}</td>
                                        <td style="width: 10%" class="text-center">
                                            @if (empty($item['selfie_path']))
                                                <span class="badge bg-danger text-white">
                                                    Foto Kehadiran Tidak Ada
                                                </span>
                                            @else
                                                <img src="{{ Storage::disk('s3')->url('selfie/' . $item->selfie_path) }}"
                                                    width="70" class="rounded">
                                                <br>
                                                <button type="button" class="btn btn-primary btn-sm mt-2"
                                                    data-toggle="modal" data-target="#exampleModal" onclick="showImage(`{{ $item['id'] }}`)">
                                                    <i class="fa fa-eye"></i> Lihat Gambar
                                                </button>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $item->guest->kategori->nama_kategori }}
                                        </td>
                                        <td>{{ $item->guest->nama_tamu }}</td>
                                        <td>{{ $item->guest->keluarga }}</td>
                                        <td class="text-center text-uppercase">
                                            {{ $item->metode }}
                                        </td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($item->waktu_checkin)->locale('id')->translatedFormat('d F Y H:i:s') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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
                                    <th class="text-center">Waktu Checkin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guest_public as $index => $item)
                                    <tr>
                                        <td style="width: 5%" class="text-center">{{ $index + 1 }}</td>
                                        <td style="width: 10%" class="text-center">
                                            @if (empty($item['selfie_path']))
                                                <span class="badge bg-danger text-white">
                                                    Foto Kehadiran Tidak Ada
                                                </span>
                                            @else
                                                <img src="{{ Storage::disk('s3')->url('selfie/' . $item->selfie_path) }}"
                                                    width="70" class="rounded">
                                                    <br>

                                                <button type="button" class="btn btn-primary btn-sm mt-2"
                                                    data-toggle="modal" data-target="#exampleModal" onclick="showImageGuestPublic(`{{ $item['id'] }}`)">
                                                    <i class="fa fa-eye"></i> Lihat Gambar
                                                </button>
                                            @endif
                                        </td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->nomor_handphone ?? '-' }}</td>
                                        <td>{{ $item->pekerjaan ?? '-' }}</td>
                                        <td>{{ $item->alamat ?? '-' }}</td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($item->waktu_checkin)->locale('id')->translatedFormat('d F Y H:i:s') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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
        $(document).ready(function() {
            $('#dataTableInvitation').DataTable({
                order: [
                    [6, 'desc']
                ]
            });

            $('#dataTablePublic').DataTable({
                order: [
                    [6, 'desc']
                ]
            });

            $('.nav-tabs a').on('shown.bs.tab', function(e) {
                var tab = $(e.target).attr('href').replace('#', '');
                $('#tab_input').val(tab);
            });
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
                url: "{{ url('/modules/history-guest') }}" + "/" + id + "/guest-public",
                type: "GET",
                success: function(response) {
                    $("#modal-content-foto-kehadiran").html(response)
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        showImageGuestPublic
    </script>

@endpush
