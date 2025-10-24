<?php $user = $_SESSION['user'] ?? null; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Hedging Syariah'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Hedging Syariah</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($user): ?>
                    <li class="nav-item"><a class="nav-link" href="/exposures">Eksposur</a></li>
                    <li class="nav-item"><a class="nav-link" href="/hedges">Hedging</a></li>
                    <?php if (in_array($user['role'], ['admin', 'auditor'], true)): ?>
                        <li class="nav-item"><a class="nav-link" href="/reviews">Review Syariah</a></li>
                        <li class="nav-item"><a class="nav-link" href="/audit">Audit Log</a></li>
                        <li class="nav-item"><a class="nav-link" href="/reports">Laporan</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($user): ?>
                    <li class="nav-item"><span class="navbar-text">Halo, <?= htmlspecialchars($user['username']); ?> (<?= htmlspecialchars($user['role']); ?>)</span></li>
                    <li class="nav-item"><a class="nav-link" href="/logout">Keluar</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/login">Masuk</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container mb-5">
    <?= $content ?? '' ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
