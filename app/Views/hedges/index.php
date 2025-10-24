<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Hedging</h1>
    <a href="/hedges/create" class="btn btn-primary">Tambah Hedging</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Eksposur</th>
                    <th>Akad</th>
                    <th>Notional</th>
                    <th>Periode</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hedges as $hedge): ?>
                    <tr>
                        <td><?= $hedge['id']; ?></td>
                        <td><?= htmlspecialchars($hedge['exposure_description'] ?? $hedge['exposure_id']); ?></td>
                        <td><?= htmlspecialchars($hedge['akad_type']); ?></td>
                        <td><?= number_format((float)$hedge['notional'], 2); ?></td>
                        <td><?= htmlspecialchars(($hedge['start_date'] ?? '') . ' - ' . ($hedge['end_date'] ?? '')); ?></td>
                        <td class="text-end">
                            <a href="/hedges/edit?id=<?= $hedge['id']; ?>" class="btn btn-sm btn-outline-primary">Ubah</a>
                            <form action="/hedges/delete" method="post" class="d-inline" onsubmit="return confirm('Hapus hedging ini?');">
                                <input type="hidden" name="id" value="<?= $hedge['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
