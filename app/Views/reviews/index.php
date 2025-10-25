<h1 class="h3 mb-3">Review Syariah</h1>
<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Deskripsi</th>
                    <th>Notional</th>
                    <th>Pihak Lawan</th>
                    <th>Tujuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr><td colspan="6" class="text-center text-muted">Tidak ada eksposur menunggu review</td></tr>
                <?php endif; ?>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?= $review['id']; ?></td>
                        <td><?= htmlspecialchars($review['description']); ?></td>
                        <td><?= number_format((float)$review['notional'], 2); ?></td>
                        <td><?= htmlspecialchars($review['counterparty']); ?></td>
                        <td><?= htmlspecialchars($review['purpose']); ?></td>
                        <td>
                            <form action="/reviews/submit" method="post" class="d-inline">
                                <input type="hidden" name="exposure_id" value="<?= $review['id']; ?>">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                            </form>
                            <form action="/reviews/submit" method="post" class="d-inline">
                                <input type="hidden" name="exposure_id" value="<?= $review['id']; ?>">
                                <input type="hidden" name="status" value="rejected">
                                <input type="hidden" name="notes" value="Tidak memenuhi prinsip syariah">
                                <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
