<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Eksposur</h1>
    <a href="/exposures/create" class="btn btn-primary">Tambah Eksposur</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Deskripsi</th>
                    <th>Pihak Lawan</th>
                    <th>Mata Uang</th>
                    <th>Notional</th>
                    <th>Status Syariah</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exposures as $exposure): ?>
                    <tr>
                        <td><?= $exposure['id']; ?></td>
                        <td><?= htmlspecialchars($exposure['description']); ?></td>
                        <td><?= htmlspecialchars($exposure['counterparty']); ?></td>
                        <td><?= htmlspecialchars($exposure['currency']); ?></td>
                        <td><?= number_format((float)$exposure['notional'], 2); ?></td>
                        <td><span class="badge bg-<?= $exposure['shariah_status'] === 'approved' ? 'success' : ($exposure['shariah_status'] === 'rejected' ? 'danger' : 'secondary'); ?>"><?= $exposure['shariah_status'] ?? 'pending'; ?></span></td>
                        <td class="text-end">
                            <a href="/exposures/edit?id=<?= $exposure['id']; ?>" class="btn btn-sm btn-outline-primary">Ubah</a>
                            <form action="/exposures/delete" method="post" class="d-inline" onsubmit="return confirm('Hapus eksposur ini?');">
                                <input type="hidden" name="id" value="<?= $exposure['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
