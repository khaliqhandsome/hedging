<h1 class="h3 mb-3">Laporan</h1>
<a href="/reports/csv" class="btn btn-success mb-3">Unduh CSV</a>
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">Eksposur</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Deskripsi</th>
                                <th>Notional</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exposures as $exposure): ?>
                                <tr>
                                    <td><?= $exposure['id']; ?></td>
                                    <td><?= htmlspecialchars($exposure['description']); ?></td>
                                    <td><?= number_format((float)$exposure['notional'], 2); ?></td>
                                    <td><?= htmlspecialchars($exposure['shariah_status'] ?? 'pending'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-success text-white">Hedging</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Akad</th>
                                <th>Notional</th>
                                <th>Periode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hedges as $hedge): ?>
                                <tr>
                                    <td><?= $hedge['id']; ?></td>
                                    <td><?= htmlspecialchars($hedge['akad_type']); ?></td>
                                    <td><?= number_format((float)$hedge['notional'], 2); ?></td>
                                    <td><?= htmlspecialchars(($hedge['start_date'] ?? '') . ' - ' . ($hedge['end_date'] ?? '')); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
