@extends('layouts.master')

@push('title-modules', 'Dashboard')

@push('style-css')
    <style>
        .dashboard-hero {
            position: relative;
            padding: 1.6rem;
            border-radius: 28px;
            overflow: hidden;
            color: #fff;
            background:
                radial-gradient(circle at top right, rgba(216, 180, 106, 0.18), transparent 24%),
                linear-gradient(135deg, #173f37 0%, #225248 58%, #2d6a5e 100%);
            box-shadow: 0 20px 44px rgba(22, 60, 53, 0.18);
        }

        .dashboard-hero::after {
            content: "";
            position: absolute;
            right: -60px;
            bottom: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }

        .hero-label {
            display: inline-flex;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            width: fit-content;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.12);
        }

        .hero-title {
            font-size: clamp(1.5rem, 2vw, 2.2rem);
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .hero-description {
            max-width: 680px;
            color: rgba(255, 255, 255, 0.82);
            line-height: 1.75;
        }

        .hero-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .hero-summary-item {
            position: relative;
            z-index: 1;
            padding: 1rem 1.1rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .hero-summary-item strong {
            display: block;
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .hero-summary-item span {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.74);
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-top: 1rem;
        }

        .quick-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            border-radius: 18px;
            text-decoration: none !important;
            color: #1f2937;
            background: #fff;
            border: 1px solid rgba(148, 163, 184, 0.16);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.05);
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }

        .quick-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 30px rgba(15, 23, 42, 0.08);
            color: #1f2937;
        }

        .quick-link-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #21554b;
            background: rgba(33, 85, 75, 0.1);
        }

        .quick-link-title {
            font-weight: 700;
            margin-bottom: 2px;
        }

        .quick-link-text {
            color: #6b7280;
            font-size: 13px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .stat-card {
            position: relative;
            border-radius: 22px;
            padding: 1.15rem;
            background: #fff;
            border: 1px solid rgba(148, 163, 184, 0.16);
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.05);
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 5px;
            border-radius: 22px 0 0 22px;
            background: #21554b;
        }

        .stat-card.success::before {
            background: #1cc88a;
        }

        .stat-card.warning::before {
            background: #f6c23e;
        }

        .stat-card.info::before {
            background: #36b9cc;
        }

        .stat-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: clamp(1.55rem, 2vw, 2rem);
            font-weight: 800;
            line-height: 1;
            color: #111827;
        }

        .stat-caption {
            margin-top: 10px;
            font-size: 13px;
            color: #6b7280;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
            background: rgba(33, 85, 75, 0.1);
            color: #21554b;
        }

        .stat-card.success .stat-icon {
            background: rgba(28, 200, 138, 0.14);
            color: #1cc88a;
        }

        .stat-card.warning .stat-icon {
            background: rgba(246, 194, 62, 0.18);
            color: #f6c23e;
        }

        .stat-card.info .stat-icon {
            background: rgba(54, 185, 204, 0.16);
            color: #36b9cc;
        }

        .soft-card {
            border-radius: 24px;
            padding: 1.25rem;
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(148, 163, 184, 0.16);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.05);
            height: 100%;
        }

        .section-title {
            margin: 0 0 4px;
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
        }

        .section-subtitle {
            margin: 0;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.6;
        }

        .dashboard-section {
            margin-bottom: 1rem;
        }

        .hero-side-card {
            position: relative;
            z-index: 1;
            border-radius: 22px;
            padding: 1.2rem;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(8px);
        }

        .hero-side-title {
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.72);
        }

        .hero-side-value {
            margin-top: 0.75rem;
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
        }

        .hero-side-note {
            margin-top: 0.7rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
            font-size: 0.92rem;
        }

        .hero-mini-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-top: 1rem;
        }

        .hero-mini-stat {
            padding: 0.9rem;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.08);
        }

        .hero-mini-stat strong {
            display: block;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .hero-mini-stat span {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.8rem;
            line-height: 1.5;
        }

        .chart-wrap {
            height: 300px;
        }

        .chart-wrap-sm {
            height: 230px;
        }

        .status-list {
            display: grid;
            gap: 12px;
            margin-top: 18px;
        }

        .status-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .status-item-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #334155;
            font-weight: 600;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .status-item-value {
            font-weight: 800;
            color: #0f172a;
        }

        .activity-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .search-input {
            max-width: 280px;
            min-height: 42px;
        }

        .table-compact td,
        .table-compact th {
            vertical-align: middle;
        }

        .token-button {
            border: 0;
            padding: 0;
            background: transparent;
            font-weight: 700;
            color: #21554b;
        }

        .tab-content > .tab-pane {
            margin-top: 1rem;
        }

        .nav-tabs .nav-link {
            border: 0;
            border-radius: 999px;
            color: #64748b;
            font-weight: 700;
            padding: 0.6rem 1rem;
        }

        .nav-tabs .nav-link.active {
            background: rgba(33, 85, 75, 0.1);
            color: #21554b;
        }

        @media (max-width: 1399.98px) {
            .quick-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1199.98px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .hero-summary {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .dashboard-hero,
            .soft-card {
                padding: 1rem;
                border-radius: 20px;
            }

            .quick-grid,
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .activity-head {
                align-items: stretch;
            }

            .search-input {
                max-width: 100%;
                width: 100%;
            }

            .hero-mini-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('content-modules')
    @php
        $peakTotal = !empty($chartTotal) ? max($chartTotal) : 0;
        $peakIndex = !empty($chartTotal) ? array_search($peakTotal, $chartTotal) : null;
        $peakHour = $peakIndex !== null && isset($chartJam[$peakIndex]) ? $chartJam[$peakIndex] : '-';
        $presentPercent = $totalTamu > 0 ? round(($tamuHadir / $totalTamu) * 100) : 0;
    @endphp

    <div class="content-page-header">
        <div class="content-page-label">
            <i class="fas fa-sparkles"></i>
            Ringkasan Hari Ini
        </div>
        <h1 class="content-page-title">Dashboard operasional acara</h1>
        <p class="content-page-subtitle">
            Pantau pergerakan kehadiran tamu, akses proses check-in lebih cepat, dan lihat ringkasan data utama
            tanpa harus berpindah-pindah halaman.
        </p>
    </div>

    <div class="dashboard-hero dashboard-section">
        <div class="row align-items-center">
            <div class="col-xl-8 mb-4 mb-xl-0">
                <div class="hero-label">
                    <i class="fas fa-heart"></i>
                    Dashboard Admin
                </div>
                <div class="hero-title mt-3">Operasional tamu lebih cepat, rapi, dan mudah dipantau.</div>
                <p class="hero-description mt-3 mb-4">
                    Gunakan halaman ini untuk melihat progres check-in, kepadatan kedatangan, dan akses cepat ke modul
                    yang paling sering dipakai saat acara berlangsung.
                </p>

                <div class="hero-summary">
                    <div class="hero-summary-item">
                        <strong>{{ now()->translatedFormat('d F Y') }}</strong>
                        <span>Ringkasan dashboard untuk hari ini</span>
                    </div>
                    <div class="hero-summary-item">
                        <strong>{{ $peakHour }}</strong>
                        <span>Jam kedatangan paling ramai</span>
                    </div>
                    <div class="hero-summary-item">
                        <strong>{{ $presentPercent }}%</strong>
                        <span>Persentase check-in tamu undangan</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="hero-side-card">
                    <div class="hero-side-title">Check-in Hari Ini</div>
                    <div class="hero-side-value">{{ $tamuHadir }}</div>
                    <div class="hero-side-note">
                        Tamu yang sudah terverifikasi masuk ke area acara melalui QR maupun input manual.
                    </div>

                    <div class="hero-mini-stats">
                        <div class="hero-mini-stat">
                            <strong>{{ $peakHour }}</strong>
                            <span>Jam paling ramai</span>
                        </div>
                        <div class="hero-mini-stat">
                            <strong>{{ $persen }}%</strong>
                            <span>Persentase hadir</span>
                        </div>
                        <div class="hero-mini-stat">
                            <strong>{{ $totalHadir }}</strong>
                            <span>Total orang hadir</span>
                        </div>
                        <div class="hero-mini-stat">
                            <strong>{{ $totalTamuLuarHadir }}</strong>
                            <span>Tamu luar tercatat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-section">
        <div class="d-flex align-items-start justify-content-between flex-wrap mb-3" style="gap: 12px;">
            <div>
                <h3 class="section-title">Akses Cepat</h3>
                <p class="section-subtitle">Shortcut ke modul yang paling sering dipakai saat operasional berlangsung.</p>
            </div>
        </div>

        <div class="quick-grid">
            <a href="{{ url('/modules/scan-qr-guest') }}" class="quick-link">
                <div class="quick-link-icon"><i class="fas fa-qrcode"></i></div>
                <div>
                    <div class="quick-link-title">Scan QR</div>
                    <div class="quick-link-text">Check-in cepat lewat kamera</div>
                </div>
            </a>
            <a href="{{ url('/modules/input-attendance') }}" class="quick-link">
                <div class="quick-link-icon"><i class="fas fa-keyboard"></i></div>
                <div>
                    <div class="quick-link-title">Input Manual</div>
                    <div class="quick-link-text">Alternatif saat QR tidak dipakai</div>
                </div>
            </a>
            <a href="{{ url('/modules/guest') }}" class="quick-link">
                <div class="quick-link-icon"><i class="fas fa-user-friends"></i></div>
                <div>
                    <div class="quick-link-title">Tamu Undangan</div>
                    <div class="quick-link-text">Kelola data tamu utama</div>
                </div>
            </a>
            <a href="{{ url('/modules/history-guest') }}" class="quick-link">
                <div class="quick-link-icon"><i class="fas fa-history"></i></div>
                <div>
                    <div class="quick-link-title">Riwayat Tamu</div>
                    <div class="quick-link-text">Lihat data kehadiran terbaru</div>
                </div>
            </a>
        </div>
    </div>

    <div class="stats-grid dashboard-section">
        <div class="stat-card">
            <div class="stat-top">
                <div>
                    <div class="stat-label">Total Tamu Terdaftar</div>
                    <div class="stat-value">{{ $totalTamu }}</div>
                    <div class="stat-caption">Seluruh daftar undangan yang sudah masuk sistem.</div>
                </div>
                <div class="stat-icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-top">
                <div>
                    <div class="stat-label">Tamu Sudah Check-in</div>
                    <div class="stat-value">{{ $tamuHadir }}</div>
                    <div class="stat-caption">Tamu yang berhasil masuk lewat QR atau input manual.</div>
                </div>
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="stat-card warning">
            <div class="stat-top">
                <div>
                    <div class="stat-label">Belum Hadir</div>
                    <div class="stat-value">{{ $belumHadir }}</div>
                    <div class="stat-caption">Masih bisa dipantau untuk kebutuhan follow up lapangan.</div>
                </div>
                <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
            </div>
        </div>
        <div class="stat-card info">
            <div class="stat-top">
                <div>
                    <div class="stat-label">Tamu Luar Hadir</div>
                    <div class="stat-value">{{ $totalTamuLuarHadir }}</div>
                    <div class="stat-caption">Tambahan tamu luar yang tercatat hadir di acara.</div>
                </div>
                <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 mb-4">
            <div class="soft-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h3 class="section-title">Persentase Kehadiran</h3>
                        <p class="section-subtitle">Membandingkan tamu yang sudah check-in dan yang belum hadir.</p>
                    </div>
                    <span class="badge badge-success">{{ $persen }}%</span>
                </div>

                <div class="text-center mt-4">
                    <div class="chart-wrap-sm mx-auto" style="max-width: 260px;">
                        <canvas id="chartPersen"></canvas>
                    </div>
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persen }}%"
                            aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="status-list">
                    <div class="status-item">
                        <div class="status-item-label">
                            <span class="status-dot" style="background:#1cc88a;"></span>
                            <span>Tamu hadir</span>
                        </div>
                        <div class="status-item-value">{{ $tamuHadir }}</div>
                    </div>
                    <div class="status-item">
                        <div class="status-item-label">
                            <span class="status-dot" style="background:#f6c23e;"></span>
                            <span>Belum hadir</span>
                        </div>
                        <div class="status-item-value">{{ $belumHadir }}</div>
                    </div>
                    <div class="status-item">
                        <div class="status-item-label">
                            <span class="status-dot" style="background:#36b9cc;"></span>
                            <span>Tamu luar</span>
                        </div>
                        <div class="status-item-value">{{ $totalTamuLuarHadir }}</div>
                    </div>
                    <div class="status-item">
                        <div class="status-item-label">
                            <span class="status-dot" style="background:#21554b;"></span>
                            <span>Puncak kedatangan</span>
                        </div>
                        <div class="status-item-value">{{ $peakHour }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 mb-4">
            <div class="soft-card">
                <div class="d-flex align-items-start justify-content-between flex-wrap" style="gap: 12px;">
                    <div>
                        <h3 class="section-title">Kedatangan Tamu per Jam</h3>
                        <p class="section-subtitle">Memudahkan tim melihat rentang waktu yang sedang paling sibuk.</p>
                    </div>
                    <div class="text-md-right">
                        <div class="small text-muted">Puncak kedatangan</div>
                        <div class="font-weight-bold text-dark">{{ $peakHour }} · {{ $peakTotal }} tamu</div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="chart-wrap">
                        <canvas id="chartJam"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mb-4">
            <div class="soft-card">
                <div class="activity-head">
                    <div>
                        <h3 class="section-title">Aktivitas Kehadiran Terakhir</h3>
                        <p class="section-subtitle">Pantau check-in tamu undangan maupun tamu luar dari satu tempat.</p>
                    </div>
                    <input type="text" class="form-control form-control-sm search-input" id="tableSearch"
                            placeholder="Cari nama / token...">
                </div>

                <ul class="nav nav-tabs border-0" id="myTab">
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

                <div class="tab-content">
                    <div class="tab-pane fade {{ request('tab', 'tamu-undangan') == 'tamu-undangan' ? 'show active' : '' }}"
                        id="tamu-undangan">
                        <div class="table-responsive">
                            <table class="table table-bordered table-compact mb-0" id="tableInvitation">
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
                                                <button type="button" class="token-button"
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
                                            <td colspan="7"><strong>Riwayat belum ada</strong></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade {{ request('tab') == 'tamu-luar' ? 'show active' : '' }}"
                        id="tamu-luar">
                        <div class="table-responsive">
                            <table class="table table-bordered table-compact mb-0" id="tablePublic">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>No. Handphone</th>
                                        <th>Alamat</th>
                                        <th>Pekerjaan</th>
                                        <th>Relasi</th>
                                        <th>Keterangan</th>
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
                                            <td>{{ $public->relasi }}</td>
                                            <td>{{ $public->keterangan }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($public->waktu_checkin)->locale('id')->translatedFormat('d F Y H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7"><strong>Riwayat belum ada</strong></td>
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
