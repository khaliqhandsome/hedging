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
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Dashboard</h1>
        <p class="text-muted mb-0">Pantau eksposur, kepatuhan syariah, dan aktivitas hedging dalam satu tempat.</p>
    </div>
    <?php if (in_array($user['role'], ['admin', 'auditor'], true)): ?>
        <a href="/reports" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Lihat Laporan
        </a>
    <?php endif; ?>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row g-0 align-items-center">
                    <div class="col pe-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Eksposur</div>
                        <div class="h4 mb-0 fw-bold text-gray-800"><?= $totalExposures; ?></div>
                        <div class="text-muted small">Notional: <?= number_format($exposureNotional, 2); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row g-0 align-items-center">
                    <div class="col pe-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Review Syariah</div>
                        <div class="h4 mb-0 fw-bold text-gray-800"><?= $pendingReviews; ?> Pending</div>
                        <div class="text-muted small">Perlu segera divalidasi</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row g-0 align-items-center">
                    <div class="col pe-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Portofolio Hedging</div>
                        <div class="h4 mb-0 fw-bold text-gray-800"><?= $hedgeCount; ?> Transaksi</div>
                        <div class="text-muted small">Notional: <?= number_format($hedgeNotional, 2); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shield-halved fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row g-0 align-items-center">
                    <div class="col pe-2">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Coverage Hedging</div>
                        <div class="h4 mb-0 fw-bold text-gray-800"><?= $coverageDisplay; ?>%</div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $coverageDisplay; ?>%;" aria-valuenow="<?= $coverage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="text-muted small mt-2">Perbandingan notional hedging terhadap eksposur</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Status Kepatuhan Syariah</h6>
                <span class="badge bg-light text-secondary">Total <?= $statusTotal; ?> Eksposur</span>
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
    <div class="col-lg-5 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Distribusi Akad Hedging</h6>
                <span class="badge bg-light text-secondary"><?= $hedgeCount; ?> Transaksi</span>
            </div>
            <div class="card-body">
                <?php if ($hedgeCount > 0): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($akadTypes as $key => $label): ?>
                            <?php $data = $hedgeDistribution[$key] ?? ['total' => 0, 'notional' => 0.0]; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-semibold text-gray-800"><?= htmlspecialchars($label); ?></span>
                                    <div class="text-muted small">Notional <?= number_format($data['notional'], 2); ?></div>
                                </div>
                                <span class="badge bg-primary-subtle text-primary fw-semibold"><?= $data['total']; ?> trx</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="mt-3 small text-muted">Total notional hedging: <?= number_format($totalHedgeNotional, 2); ?></div>
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

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
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
    <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Hedging Terbaru</h6>
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
