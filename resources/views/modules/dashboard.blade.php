@extends('layouts.master')

@push('title-modules', 'Dashboard')

@push('content-modules')

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <small class="text-primary font-weight-bold">Total Tamu</small>
                    <h4 class="font-weight-bold">{{ $totalTamu }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <small class="text-success font-weight-bold">Tamu Hadir</small>
                    <h4 class="font-weight-bold">{{ $tamuHadir }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <small class="text-warning font-weight-bold">Belum Hadir</small>
                    <h4 class="font-weight-bold">{{ $belumHadir }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <small class="text-info font-weight-bold">Total Orang Hadir</small>
                    <h4 class="font-weight-bold">{{ $totalHadir }}</h4>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header">
                    Persentase Kehadiran
                </div>
                <div class="card-body text-center">
                    <div style="width:200px;margin:auto">
                        <canvas id="chartPersen"></canvas>
                    </div>
                    <h4 class="mt-3 text-success font-weight-bold">
                        {{ $persen }}%
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header">
                    Kedatangan Tamu per Jam
                </div>
                <div class="card-body">
                    <canvas id="chartJam"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    Tamu Terakhir Check-in
                </div>
                <div class="card-body">
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
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kode Token</th>
                                        <th>Nama</th>
                                        <th>Keluarga</th>
                                        <th>Kategori</th>
                                        <th>Waktu Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($guest_invitation as $invitation)
                                        <tr>
                                            <td>{{ $invitation->guest->kode_token }}</td>
                                            <td>{{ $invitation->guest->nama_tamu }}</td>
                                            <td>{{ $invitation->guest->keluarga }}</td>
                                            <td>{{ $invitation->guest->kategori->nama_kategori ?? '-' }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($invitation->waktu_checkin)->locale('id')->translatedFormat('d F Y H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <strong>Riwayat Belum Ada</strong>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade {{ request('tab') == 'tamu-luar' ? 'show active' : '' }}" id="tamu-luar">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>No. Handphone</th>
                                        <th>Alamat</th>
                                        <th>Pekerjaan</th>
                                        <th>Waktu Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($guest_public as $public)
                                        <tr>
                                            <td>{{ $public->nama }}</td>
                                            <td>{{ $public->nomor_handphone }}</td>
                                            <td>{{ $public->alamat }}</td>
                                            <td>{{ $public->pekerjaan ?? '-' }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($public->waktu_checkin)->locale('id')->translatedFormat('d F Y H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <strong>Riwayat Belum Ada</strong>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endpush

@push('style-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const persenChart = document.getElementById('chartPersen');

        new Chart(persenChart, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Belum Hadir'],
                datasets: [{
                    data: [{{ $tamuHadir }}, {{ $belumHadir }}],
                    backgroundColor: [
                        '#1cc88a',
                        '#f6c23e'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        const jamChart = document.getElementById('chartJam');

        new Chart(jamChart, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartJam) !!},
                datasets: [{
                    label: 'Jumlah Tamu',
                    data: {!! json_encode($chartTotal) !!},
                    backgroundColor: '#4e73df'
                }]
            }
        });
    </script>
@endpush
