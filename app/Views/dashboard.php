<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Total Eksposur</h5>
                <p class="display-6"><?= (int)($summary['total'] ?? 0); ?></p>
                <p class="text-muted">Notional: <?= number_format((float)($summary['notional'] ?? 0), 2); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning h-100">
            <div class="card-body">
                <h5 class="card-title">Review Syariah Pending</h5>
                <p class="display-6"><?= (int)$pendingReviews; ?></p>
                <p class="text-muted">Eksposur menunggu persetujuan</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success h-100">
            <div class="card-body">
                <h5 class="card-title">Ringkasan Hedging</h5>
                <?php if (!empty($hedgeStats)): ?>
                    <ul class="list-unstyled">
                        <?php foreach ($hedgeStats as $stat): ?>
                            <li><strong><?= htmlspecialchars($stat['akad_type']); ?>:</strong> <?= (int)$stat['total']; ?> transaksi (<?= number_format((float)$stat['notional'], 2); ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">Belum ada hedging</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
