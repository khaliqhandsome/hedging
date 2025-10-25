<?php

use App\Models\Hedge;

$totalExposures = (int)($summary['total'] ?? 0);
$exposureNotional = (float)($summary['notional'] ?? 0);
$pendingReviews = (int)($pendingReviews ?? 0);
$hedgeTotals = $hedgeTotals ?? ['total' => 0, 'notional' => 0];
$hedgeCount = (int)($hedgeTotals['total'] ?? 0);
$hedgeNotional = (float)($hedgeTotals['notional'] ?? 0);
$coverage = $exposureNotional > 0 ? min(100, round(($hedgeNotional / $exposureNotional) * 100, 1)) : 0.0;
$coverageDisplay = number_format($coverage, 1);

$statusMap = [
    'pending' => 0,
    'approved' => 0,
    'rejected' => 0,
];
foreach ($statusBreakdown as $row) {
    $statusMap[$row['status']] = (int)$row['total'];
}
$statusTotal = array_sum($statusMap);
$statusMeta = [
    'pending' => ['label' => 'Menunggu Review', 'class' => 'bg-warning'],
    'approved' => ['label' => 'Disetujui', 'class' => 'bg-success'],
    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
];

$akadTypes = Hedge::AKAD_TYPES;
$hedgeDistribution = [];
foreach ($hedgeStats as $stat) {
    $key = $stat['akad_type'];
    $hedgeDistribution[$key] = [
        'total' => (int)($stat['total'] ?? 0),
        'notional' => (float)($stat['notional'] ?? 0),
    ];
}
$totalHedgeNotional = array_sum(array_column($hedgeDistribution, 'notional')) ?: 0.0;

$trendLabels = $trendLabels ?? [];
$trendExposureSeries = $trendExposureSeries ?? [];
$trendHedgeSeries = $trendHedgeSeries ?? [];
$trendHasData = array_sum($trendExposureSeries) > 0 || array_sum($trendHedgeSeries) > 0;

$palette = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'];
$hedgeChartLabels = [];
$hedgeChartValues = [];
foreach ($akadTypes as $key => $label) {
    $data = $hedgeDistribution[$key] ?? ['total' => 0, 'notional' => 0.0];
    $hedgeChartLabels[] = $label;
    $hedgeChartValues[] = round((float)$data['notional'], 2);
}
$colorMap = [];
foreach ($hedgeChartLabels as $idx => $label) {
    $colorMap[$label] = $palette[$idx % count($palette)];
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Dashboard</h1>
        <p class="text-muted mb-0">Pantau eksposur, kepatuhan syariah, dan performa hedging secara real-time.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="/hedges/create" class="btn btn-sm btn-outline-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Tambah Hedging
        </a>
        <?php if (in_array($user['role'], ['admin', 'auditor'], true)): ?>
            <a href="/reports" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Laporan Lengkap
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-xs text-uppercase fw-bold text-muted mb-1">Eksposur Aktif</p>
                        <h4 class="fw-bold text-primary mb-0"><?= $totalExposures; ?></h4>
                        <span class="text-muted small">Total notional <?= number_format($exposureNotional, 2); ?></span>
                    </div>
                    <div class="icon-circle bg-gradient-primary text-white">
                        <i class="fas fa-warehouse"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-xs text-uppercase fw-bold text-muted mb-1">Review Syariah Pending</p>
                        <h4 class="fw-bold text-warning mb-0"><?= $pendingReviews; ?></h4>
                        <span class="text-muted small">Perlu segera divalidasi</span>
                    </div>
                    <div class="icon-circle bg-gradient-warning text-white">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-xs text-uppercase fw-bold text-muted mb-1">Portofolio Hedging</p>
                        <h4 class="fw-bold text-success mb-0"><?= $hedgeCount; ?></h4>
                        <span class="text-muted small">Notional <?= number_format($hedgeNotional, 2); ?></span>
                    </div>
                    <div class="icon-circle bg-gradient-success text-white">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-xs text-uppercase fw-bold text-muted mb-1">Coverage Hedging</p>
                        <h4 class="fw-bold text-info mb-0"><?= $coverageDisplay; ?>%</h4>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $coverageDisplay; ?>%;" aria-valuenow="<?= $coverage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-muted small">Perbandingan notional hedging terhadap eksposur</span>
                    </div>
                    <div class="icon-circle bg-gradient-info text-white">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Performa Notional Bulanan</h6>
                <span class="badge bg-primary-subtle text-primary fw-semibold">6 bulan terakhir</span>
            </div>
            <div class="card-body">
                <?php if ($trendHasData): ?>
                    <canvas id="exposureTrendChart" height="160"></canvas>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-chart-area fa-2x mb-3 text-gray-300"></i>
                        <p class="mb-0">Belum ada data historis untuk ditampilkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Distribusi Akad</h6>
                <span class="badge bg-light text-secondary"><?= $hedgeCount; ?> transaksi</span>
            </div>
            <div class="card-body">
                <?php if ($hedgeCount > 0 && array_sum($hedgeChartValues) > 0): ?>
                    <canvas id="hedgeDistributionChart" height="200"></canvas>
                    <div class="mt-4">
                        <?php foreach ($akadTypes as $key => $label): ?>
                            <?php $data = $hedgeDistribution[$key] ?? ['total' => 0, 'notional' => 0.0]; ?>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-circle" style="background-color: <?= $colorMap[$label] ?? '#4e73df'; ?>; width: 10px; height: 10px;"></span>
                                    <span class="text-muted small"><?= htmlspecialchars($label); ?></span>
                                </div>
                                <div class="text-end">
                                    <span class="fw-semibold text-gray-700 small"><?= $data['total']; ?> trx</span>
                                    <div class="text-muted small">Notional <?= number_format($data['notional'], 2); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-chart-pie fa-2x mb-3 text-gray-300"></i>
                        <p class="mb-0">Belum ada transaksi hedging yang terekam.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Status Kepatuhan Syariah</h6>
                <span class="badge bg-light text-secondary">Total <?= $statusTotal; ?> eksposur</span>
            </div>
            <div class="card-body">
                <?php if ($statusTotal > 0): ?>
                    <?php foreach ($statusMap as $status => $count): ?>
                        <?php $percentage = $statusTotal > 0 ? round(($count / $statusTotal) * 100) : 0; ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold text-gray-800 text-capitalize"><?= $statusMeta[$status]['label']; ?></span>
                                <span class="text-muted small"><?= $count; ?> · <?= $percentage; ?>%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar <?= $statusMeta[$status]['class']; ?>" role="progressbar" style="width: <?= $percentage; ?>%;" aria-valuenow="<?= $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x mb-3 text-gray-300"></i>
                        <p class="mb-0">Belum ada data eksposur untuk diringkas.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Insight Kepatuhan</h6>
                <span class="badge bg-primary-subtle text-primary fw-semibold">Rekomendasi</span>
            </div>
            <div class="card-body d-flex align-items-center">
                <div class="me-4">
                    <img src="https://cdn.jsdelivr.net/gh/creativetimofficial/public-assets/illustrations/rocket-launch.svg" alt="Illustration" width="120" class="d-none d-md-block">
                </div>
                <div>
                    <p class="text-muted small mb-2">Pastikan setiap eksposur dilengkapi bukti underlying dan tujuan tahawwut untuk menjaga integritas portofolio.</p>
                    <p class="text-muted small mb-3">Gunakan laporan HTML atau CSV untuk audit trail dan tinjauan regulator yang lebih cepat.</p>
                    <a href="/reports" class="btn btn-sm btn-outline-primary">Kelola Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Eksposur Terbaru</h6>
                <span class="badge bg-primary-subtle text-primary fw-semibold"><?= $totalExposures; ?> total</span>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($recentExposures)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Eksposur</th>
                                    <th class="text-end">Notional</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentExposures as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-gray-800"><?= htmlspecialchars($item['description']); ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($item['counterparty']); ?> · <?= date('d M Y', strtotime($item['created_at'])); ?></div>
                                        </td>
                                        <td class="text-end"><?= number_format((float)$item['notional'], 2); ?> <?= htmlspecialchars($item['currency']); ?></td>
                                        <td class="text-end">
                                            <?php
                                            $status = $item['shariah_status'] ?? 'pending';
                                            $badgeClass = [
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'pending' => 'secondary',
                                            ][$status] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $badgeClass; ?> text-uppercase"><?= htmlspecialchars($status); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-folder-open fa-2x mb-3 text-gray-300"></i>
                        <p class="mb-0">Belum ada eksposur yang dibuat.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Aktivitas Hedging Terbaru</h6>
                <span class="badge bg-success-subtle text-success fw-semibold"><?= $hedgeCount; ?> total</span>
            </div>
            <div class="card-body">
                <?php if (!empty($recentHedges)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentHedges as $item): ?>
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                                <div class="me-3">
                                    <div class="fw-semibold text-gray-800"><?= htmlspecialchars($akadTypes[$item['akad_type']] ?? strtoupper($item['akad_type'])); ?></div>
                                    <div class="text-muted small">Eksposur: <?= htmlspecialchars($item['exposure_description']); ?></div>
                                    <div class="text-muted small"><?= date('d M Y', strtotime($item['created_at'])); ?></div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success"><?= number_format((float)$item['notional'], 2); ?></div>
                                    <span class="badge bg-success-subtle text-success fw-semibold">Aktif</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-shield fa-2x mb-3 text-gray-300"></i>
                        <p class="mb-0">Belum ada transaksi hedging yang tercatat.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($trendHasData || ($hedgeCount > 0 && array_sum($hedgeChartValues) > 0)): ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const trendLabels = <?= json_encode($trendLabels, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        const exposureSeries = <?= json_encode($trendExposureSeries, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        const hedgeSeries = <?= json_encode($trendHedgeSeries, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        const hedgeLabels = <?= json_encode($hedgeChartLabels, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        const hedgeValues = <?= json_encode($hedgeChartValues, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        const palette = <?= json_encode($palette, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        if (trendLabels.length && (exposureSeries.some(v => v > 0) || hedgeSeries.some(v => v > 0))) {
            const ctx = document.getElementById('exposureTrendChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: trendLabels,
                        datasets: [
                            {
                                label: 'Eksposur',
                                data: exposureSeries,
                                borderColor: '#4e73df',
                                backgroundColor: 'rgba(78, 115, 223, 0.15)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 3,
                            },
                            {
                                label: 'Hedging',
                                data: hedgeSeries,
                                borderColor: '#1cc88a',
                                backgroundColor: 'rgba(28, 200, 138, 0.15)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 3,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        const value = context.parsed.y || 0;
                                        return `${context.dataset.label}: ${value.toLocaleString('id-ID', { minimumFractionDigits: 0 })}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => value.toLocaleString('id-ID')
                                }
                            }
                        }
                    }
                });
            }
        }

        if (hedgeLabels.some((_, idx) => hedgeValues[idx] > 0)) {
            const ctx = document.getElementById('hedgeDistributionChart');
            if (ctx) {
                const colors = hedgeLabels.map((_, index) => palette[index % palette.length]);
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: hedgeLabels,
                        datasets: [{
                            data: hedgeValues,
                            backgroundColor: colors,
                            hoverBackgroundColor: colors,
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            }
                        },
                        cutout: '65%'
                    }
                });
            }
        }
    });
</script>
<?php endif; ?>
