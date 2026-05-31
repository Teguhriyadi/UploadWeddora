@extends('layouts.master')

@push('title-modules', 'Dashboard')

@push('style-css')
    <style>
        .stat-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(78, 115, 223, 0.12);
            color: #4e73df;
        }

        .stat-icon.success {
            background: rgba(28, 200, 138, 0.12);
            color: #1cc88a;
        }

        .stat-icon.warning {
            background: rgba(246, 194, 62, 0.14);
            color: #f6c23e;
        }

        .stat-icon.info {
            background: rgba(54, 185, 204, 0.14);
            color: #36b9cc;
        }

        .chart-wrap {
            height: 280px;
        }

        .chart-wrap-sm {
            height: 240px;
        }

        .table-compact td,
        .table-compact th {
            vertical-align: middle;
        }

        .search-input {
            max-width: 260px;
        }
    </style>
@endpush

@push('content-modules')

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-primary font-weight-bold">Total Tamu</small>
                            <h4 class="font-weight-bold mb-0">{{ $totalTamu }}</h4>
                            <div class="text-muted small mt-1">Data tamu terdaftar</div>
                        </div>
                        <div class="stat-icon" aria-hidden="true">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-success font-weight-bold">Tamu Hadir</small>
                            <h4 class="font-weight-bold mb-0">{{ $tamuHadir }}</h4>
                            <div class="text-muted small mt-1">Check-in berhasil</div>
                        </div>
                        <div class="stat-icon success" aria-hidden="true">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-warning font-weight-bold">Belum Hadir</small>
                            <h4 class="font-weight-bold mb-0">{{ $belumHadir }}</h4>
                            <div class="text-muted small mt-1">Belum check-in</div>
                        </div>
                        <div class="stat-icon warning" aria-hidden="true">
                            <i class="fa fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-info shadow h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-info font-weight-bold">Total Orang Hadir</small>
                            <h4 class="font-weight-bold mb-0">{{ $totalHadir }}</h4>
                            <div class="text-muted small mt-1">Status hadir</div>
                        </div>
                        <div class="stat-icon info" aria-hidden="true">
                            <i class="fa fa-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <span>Persentase Kehadiran</span>
                        <span class="badge badge-pill badge-success">{{ $persen }}%</span>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="chart-wrap-sm mx-auto" style="max-width: 260px;">
                        <canvas id="chartPersen"></canvas>
                    </div>
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persen }}%"
                            aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header">
                    Kedatangan Tamu per Jam
                </div>
                <div class="card-body">
                    <div class="chart-wrap">
                        <canvas id="chartJam"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
                        <span>Tamu Terakhir Check-in</span>
                        <input type="text" class="form-control form-control-sm search-input" id="tableSearch"
                            placeholder="Cari nama / token...">
                    </div>
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
                            <div class="table-responsive">
                                <table class="table table-bordered table-compact" id="tableInvitation">
                                    <thead>
                                        <tr>
                                            <th>Kode Token</th>
                                            <th>Nama</th>
                                            <th>Nama di Undangan</th>
                                            <th>Keterangan</th>
                                            <th>Kategori</th>
                                            <th>Relasi</th>
                                            <th>Waktu Kehadiran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($guest_invitation as $invitation)
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-link p-0 font-weight-bold"
                                                        data-copy-token="{{ $invitation->guest->kode_token }}">
                                                        {{ $invitation->guest->kode_token }}
                                                    </button>
                                                </td>
                                                <td>{{ $invitation->guest->nama_tamu }}</td>
                                                <td>{{ $invitation->guest->nama_undangan }}</td>
                                                <td>{{ $invitation->guest->keterangan }}</td>
                                                <td>{{ $invitation->guest->kategori ? $invitation->guest->kategori->nama_kategori : '' }}
                                                </td>
                                                <td>{{ $invitation->guest->relasi }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($invitation->waktu_checkin)->locale('id')->translatedFormat('d F Y H:i') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">
                                                    <strong>Riwayat Belum Ada</strong>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade {{ request('tab') == 'tamu-luar' ? 'show active' : '' }}" id="tamu-luar">
                            <div class="table-responsive">
                                <table class="table table-bordered table-compact" id="tablePublic">
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
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.label}: ${ctx.raw}`
                        }
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
                    backgroundColor: '#4e73df',
                    borderRadius: 8,
                    maxBarThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        const tableSearch = document.getElementById('tableSearch');
        const tableInvitation = document.getElementById('tableInvitation');
        const tablePublic = document.getElementById('tablePublic');

        function filterTable(table, query) {
            if (!table) return;
            const q = (query || '').toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        }

        tableSearch?.addEventListener('input', (e) => {
            const q = e.target.value;
            const activeTab = document.querySelector('.tab-pane.active.show');
            if (activeTab?.id === 'tamu-luar') {
                filterTable(tablePublic, q);
            } else {
                filterTable(tableInvitation, q);
            }
        });

        document.querySelectorAll('[data-copy-token]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const token = btn.getAttribute('data-copy-token') || '';
                try {
                    await navigator.clipboard.writeText(token);
                    btn.classList.add('text-success');
                    setTimeout(() => btn.classList.remove('text-success'), 800);
                } catch (e) {}
            });
        });
    </script>
@endpush
