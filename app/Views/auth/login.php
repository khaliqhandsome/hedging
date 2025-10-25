<?php $errors = $errors ?? []; ?>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3 text-center">Masuk</h1>
                <?php if (!empty($errors['auth'])): ?>
                    <div class="alert alert-danger">
                        <?= implode('<br>', $errors['auth']); ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="/login">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                        <?php if (!empty($errors['username'])): ?>
                            <div class="text-danger small"><?= implode('<br>', $errors['username']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <?php if (!empty($errors['password'])): ?>
                            <div class="text-danger small"><?= implode('<br>', $errors['password']); ?></div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Masuk</button>
                </form>
            </div>
        </div>
    </div>
</div>
