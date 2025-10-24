<h1 class="h3 mb-3">Audit Log</h1>
<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                    <th>Entitas</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['created_at']); ?></td>
                        <td><?= htmlspecialchars($log['username'] ?? 'Sistem'); ?></td>
                        <td><?= htmlspecialchars($log['action']); ?></td>
                        <td><?= htmlspecialchars($log['entity_type'] . '#' . $log['entity_id']); ?></td>
                        <td><?= htmlspecialchars($log['details']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
