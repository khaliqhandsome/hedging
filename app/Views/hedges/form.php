<?php $errors = $errors ?? []; ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-4"><?= $hedge ? 'Ubah Hedging' : 'Tambah Hedging'; ?></h1>
                <form action="<?= $action; ?>" method="post" enctype="multipart/form-data">
                    <?php if ($hedge): ?>
                        <input type="hidden" name="id" value="<?= $hedge['id']; ?>">
                        <input type="hidden" name="exposure_id" value="<?= $hedge['exposure_id']; ?>">
                    <?php endif; ?>
                    <div class="row g-3">
                        <?php if (!$hedge): ?>
                            <div class="col-md-6">
                                <label class="form-label">Eksposur</label>
                                <select name="exposure_id" class="form-select" required>
                                    <option value="">Pilih eksposur</option>
                                    <?php foreach ($exposureOptions as $id => $label): ?>
                                        <option value="<?= $id; ?>" <?= isset($hedge['exposure_id']) && (int)$hedge['exposure_id'] === (int)$id ? 'selected' : ''; ?>><?= htmlspecialchars($label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (!empty($errors['exposure_id'])): ?><div class="text-danger small"><?= implode('<br>', $errors['exposure_id']); ?></div><?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-6">
                            <label class="form-label">Akad</label>
                            <select name="akad_type" class="form-select" required>
                                <option value="">Pilih akad</option>
                                <?php foreach ($akadOptions as $key => $label): ?>
                                    <option value="<?= $key; ?>" <?= ($hedge['akad_type'] ?? '') === $key ? 'selected' : ''; ?>><?= htmlspecialchars($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errors['akad_type'])): ?><div class="text-danger small"><?= implode('<br>', $errors['akad_type']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Notional</label>
                            <input type="number" step="0.01" name="notional" class="form-control" value="<?= htmlspecialchars($hedge['notional'] ?? ''); ?>" required>
                            <?php if (!empty($errors['notional'])): ?><div class="text-danger small"><?= implode('<br>', $errors['notional']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rate</label>
                            <input type="number" step="0.0001" name="rate" class="form-control" value="<?= htmlspecialchars($hedge['rate'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Biaya</label>
                            <input type="text" name="cost_breakdown" class="form-control" value="<?= htmlspecialchars($hedge['cost_breakdown'] ?? ''); ?>" required>
                            <?php if (!empty($errors['cost_breakdown'])): ?><div class="text-danger small"><?= implode('<br>', $errors['cost_breakdown']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($hedge['start_date'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Jatuh Tempo</label>
                            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($hedge['end_date'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="no_riba_clause" value="1" id="noRibaClause" <?= !empty($hedge['no_riba_clause']) ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="noRibaClause">Tanpa denda riba</label>
                                <?php if (!empty($errors['no_riba_clause'])): ?><div class="text-danger small"><?= implode('<br>', $errors['no_riba_clause']); ?></div><?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dokumen Pendukung</label>
                            <input type="file" name="supporting_document" class="form-control" <?= $hedge ? '' : 'required'; ?>>
                            <?php if ($hedge && $hedge['document_path']): ?>
                                <div class="form-text">Berkas saat ini: <?= htmlspecialchars($hedge['document_path']); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($errors['supporting_document'])): ?><div class="text-danger small"><?= implode('<br>', $errors['supporting_document']); ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="/hedges" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
