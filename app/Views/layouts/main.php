<?php $user = $_SESSION['user'] ?? null; ?>
<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$currentPath = $currentPath === '' ? '/' : (rtrim($currentPath, '/') ?: '/');
$activeClass = static function (array $paths) use ($currentPath): string {
    foreach ($paths as $path) {
        if ($path === '/' && $currentPath === '/') {
            return ' active';
        }
        if ($path !== '/' && str_starts_with($currentPath, $path)) {
            return ' active';
        }
    }
    return '';
};
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Hedging Syariah'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" integrity="sha512-SnH1ZSAwEhiN9I1HkM3ENbN7r+Bm1F1Ehb1r4nND+pEHc03TZGiJclwCjFZyghuUKNfFfLV3Gd1kKeZSrd3N3Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet" integrity="sha256-F/VKd7ejY0X0o2cHpBKG45t2OgHTzMuUlBQ7B3ix5fo=" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }
        .topbar-accent {
            height: 4px;
            background: linear-gradient(90deg, #f5365c, #fb6340);
        }
        .main-navbar {
            background: #fff;
            border-bottom: 1px solid rgba(78, 115, 223, 0.15);
        }
        .navbar-brand {
            font-weight: 800;
            color: #4e73df !important;
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .navbar-brand .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .navbar-nav .nav-link {
            font-weight: 600;
            color: rgba(90, 92, 105, 0.9) !important;
            padding-top: .75rem;
            padding-bottom: .75rem;
        }
        .navbar-nav .nav-link.active,
        .navbar-nav .nav-link:hover {
            color: #224abe !important;
        }
        .navbar-nav .nav-link.active::after {
            content: '';
            display: block;
            height: 3px;
            margin-top: .35rem;
            border-radius: 999px;
            background: linear-gradient(90deg, #4e73df, #1cc88a);
        }
        .topbar-search .form-control {
            border: none;
            background-color: #f1f5ff;
        }
        .topbar-search .input-group-text {
            border: none;
            background-color: #f1f5ff;
            color: #4e73df;
        }
        .icon-circle {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0.15rem 0.75rem rgba(58, 59, 69, 0.15);
            font-size: 1.15rem;
        }
        .content-wrapper {
            padding: 2.5rem 0 2rem;
        }
        footer {
            border-top: 1px solid rgba(78, 115, 223, 0.1);
        }
    </style>
</head>
<body id="page-top">
<div class="topbar-accent"></div>
<nav class="navbar navbar-expand-lg navbar-light main-navbar sticky-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <span class="brand-icon"><i class="fas fa-chart-line"></i></span>
            <span>Hedging Syariah</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link<?= $activeClass(['/']); ?>" href="/">
                        <i class="fas fa-gauge-high me-2"></i>Dashboard
                    </a>
                </li>
                <?php if ($user): ?>
                    <li class="nav-item">
                        <a class="nav-link<?= $activeClass(['/exposures']); ?>" href="/exposures">
                            <i class="fas fa-warehouse me-2"></i>Eksposur
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= $activeClass(['/hedges']); ?>" href="/hedges">
                            <i class="fas fa-shield-halved me-2"></i>Hedging
                        </a>
                    </li>
                    <?php if (in_array($user['role'], ['admin', 'auditor'], true)): ?>
                        <li class="nav-item">
                            <a class="nav-link<?= $activeClass(['/reviews']); ?>" href="/reviews">
                                <i class="fas fa-clipboard-check me-2"></i>Review Syariah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= $activeClass(['/audit']); ?>" href="/audit">
                                <i class="fas fa-file-signature me-2"></i>Audit Log
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= $activeClass(['/reports']); ?>" href="/reports">
                                <i class="fas fa-chart-pie me-2"></i>Laporan
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <form class="d-none d-xl-block topbar-search me-4" role="search">
                <div class="input-group shadow-sm rounded-pill overflow-hidden">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="search" class="form-control" placeholder="Cari laporan, eksposur, atau hedge..." aria-label="Pencarian">
                </div>
            </form>
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item d-none d-lg-inline text-muted small me-3">
                    Assalamu'alaikum, selamat bekerja!
                </li>
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="badge badge-danger badge-counter bg-danger text-white">3+</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow">
                        <h6 class="dropdown-header">Notifikasi</h6>
                        <a class="dropdown-item small" href="#">Review syariah membutuhkan perhatian.</a>
                        <a class="dropdown-item small" href="#">Eksposur baru berhasil ditambahkan.</a>
                        <a class="dropdown-item small text-primary" href="/reports">Lihat semua laporan</a>
                    </div>
                </li>
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-envelope fa-lg"></i>
                        <span class="badge badge-success badge-counter bg-success text-white">2</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow">
                        <h6 class="dropdown-header">Pesan Internal</h6>
                        <a class="dropdown-item small" href="#">Audit internal dijadwalkan pekan ini.</a>
                        <a class="dropdown-item small" href="#">Pembaruan kebijakan kepatuhan tersedia.</a>
                    </div>
                </li>
                <?php if ($user): ?>
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="me-2 text-end">
                                <span class="d-none d-lg-block text-gray-600 small fw-semibold"><?= htmlspecialchars($user['username']); ?></span>
                                <span class="d-none d-lg-block text-muted text-uppercase small"><?= htmlspecialchars($user['role']); ?></span>
                            </div>
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="fas fa-user"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                            <span class="dropdown-item-text text-muted small">Masuk sebagai <strong><?= htmlspecialchars($user['role']); ?></strong></span>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/logout">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                Keluar
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/login">Masuk</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="content-wrapper">
    <div class="container-fluid">
        <?= $content ?? '' ?>
    </div>
</main>
<footer class="bg-white shadow-sm py-3 mt-auto">
    <div class="container">
        <div class="text-center">
            <span class="text-muted small">&copy; <?= date('Y'); ?> Hedging Syariah · Kepatuhan dan Transparansi</span>
        </div>
    </div>
</footer>
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js" integrity="sha256-SJk3F6/Dr7guPmyAnbcW2CYwiVdc+GqOR/mdrIW6DC8=" crossorigin="anonymous"></script>
<?= $scripts ?? '' ?>
</body>
</html>
