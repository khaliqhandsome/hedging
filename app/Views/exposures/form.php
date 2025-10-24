<?php $errors = $errors ?? []; ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-4"><?= $exposure ? 'Ubah Eksposur' : 'Tambah Eksposur'; ?></h1>
                <form action="<?= $action; ?>" method="post" enctype="multipart/form-data">
                    <?php if ($exposure): ?>
                        <input type="hidden" name="id" value="<?= $exposure['id']; ?>">
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($exposure['description'] ?? ''); ?>" required>
                            <?php if (!empty($errors['description'])): ?><div class="text-danger small"><?= implode('<br>', $errors['description']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pihak Lawan</label>
                            <input type="text" name="counterparty" class="form-control" value="<?= htmlspecialchars($exposure['counterparty'] ?? ''); ?>" required>
                            <?php if (!empty($errors['counterparty'])): ?><div class="text-danger small"><?= implode('<br>', $errors['counterparty']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mata Uang</label>
                            <input type="text" name="currency" class="form-control" value="<?= htmlspecialchars($exposure['currency'] ?? ''); ?>" required>
                            <?php if (!empty($errors['currency'])): ?><div class="text-danger small"><?= implode('<br>', $errors['currency']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Notional</label>
                            <input type="number" step="0.01" name="notional" class="form-control" value="<?= htmlspecialchars($exposure['notional'] ?? ''); ?>" required>
                            <?php if (!empty($errors['notional'])): ?><div class="text-danger small"><?= implode('<br>', $errors['notional']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Eksposur</label>
                            <input type="date" name="exposure_date" class="form-control" value="<?= htmlspecialchars($exposure['exposure_date'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tujuan</label>
                            <input type="text" name="purpose" class="form-control" value="<?= htmlspecialchars($exposure['purpose'] ?? 'tahawwut'); ?>" required>
                            <?php if (!empty($errors['purpose'])): ?><div class="text-danger small"><?= implode('<br>', $errors['purpose']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bukti Underlying</label>
                            <input type="file" name="underlying_file" class="form-control" <?= $exposure ? '' : 'required'; ?>>
                            <?php if ($exposure && $exposure['underlying_file']): ?>
                                <div class="form-text">Berkas saat ini: <?= htmlspecialchars($exposure['underlying_file']); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($errors['underlying_file'])): ?><div class="text-danger small"><?= implode('<br>', $errors['underlying_file']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Biaya Transparan</label>
                            <select name="cost_transparent" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="1" <?= isset($exposure['cost_transparent']) && (int)$exposure['cost_transparent'] === 1 ? 'selected' : ''; ?>>Ya, biaya transparan</option>
                                <option value="0" <?= isset($exposure['cost_transparent']) && (int)$exposure['cost_transparent'] === 0 ? 'selected' : ''; ?>>Tidak</option>
                            </select>
                            <?php if (!empty($errors['cost_transparent'])): ?><div class="text-danger small"><?= implode('<br>', $errors['cost_transparent']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Denda Riba</label>
                            <select name="penalty_clause" class="form-select" required>
                                <option value="0" <?= isset($exposure['penalty_clause']) && (int)$exposure['penalty_clause'] === 0 ? 'selected' : ''; ?>>Tidak ada denda riba</option>
                                <option value="1" <?= isset($exposure['penalty_clause']) && (int)$exposure['penalty_clause'] === 1 ? 'selected' : ''; ?>>Mengandung denda riba</option>
                            </select>
                            <?php if (!empty($errors['penalty_clause'])): ?><div class="text-danger small"><?= implode('<br>', $errors['penalty_clause']); ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="/exposures" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
