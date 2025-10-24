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
        .sidebar {
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%) !important;
        }
        .sidebar .sidebar-brand {
            height: 5rem;
        }
        .sidebar .sidebar-brand-icon {
            font-size: 1.5rem;
        }
        .sidebar .nav-item .nav-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding-top: .85rem;
            padding-bottom: .85rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
        }
        .sidebar .nav-item .nav-link span {
            font-size: 0.9rem;
        }
        .sidebar .nav-item.active > .nav-link,
        .sidebar .nav-item .nav-link.active,
        .sidebar .nav-item .nav-link:hover {
            color: #fff;
        }
        .sidebar.toggled {
            width: 6.5rem !important;
            overflow: hidden;
        }
        body.sidebar-toggled #accordionSidebar {
            width: 6.5rem !important;
        }
        .topbar {
            background-color: #f8f9fc;
            border-bottom: 1px solid rgba(78, 115, 223, 0.15);
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
        .topbar .input-group > .form-control {
            border: none;
            background-color: #f1f5ff;
        }
        .topbar .input-group-text {
            border: none;
            background-color: #f1f5ff;
            color: #4e73df;
        }
        .topbar .nav-link {
            color: #5a5c69;
        }
        .badge-counter {
            position: absolute;
            transform: scale(.7);
            transform-origin: top right;
            top: .35rem;
            right: .35rem;
        }
        .scroll-to-top {
            background-color: #4e73df;
        }
        @media (max-width: 991.98px) {
            #accordionSidebar {
                margin-left: -15rem;
                transition: margin .25s ease-in-out;
            }
            body.sidebar-toggled #accordionSidebar {
                margin-left: 0;
            }
        }
    </style>
</head>
<body id="page-top">
<div class="topbar-accent"></div>
<div id="wrapper">
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Hedging<br>Syariah</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item<?= $activeClass(['/']); ?>">
            <a class="nav-link<?= $activeClass(['/']); ?>" href="/">
                <i class="fas fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <?php if ($user): ?>
            <li class="nav-item<?= $activeClass(['/exposures']); ?>">
                <a class="nav-link<?= $activeClass(['/exposures']); ?>" href="/exposures">
                    <i class="fas fa-warehouse"></i>
                    <span>Eksposur</span>
                </a>
            </li>
            <li class="nav-item<?= $activeClass(['/hedges']); ?>">
                <a class="nav-link<?= $activeClass(['/hedges']); ?>" href="/hedges">
                    <i class="fas fa-shield-halved"></i>
                    <span>Hedging</span>
                </a>
            </li>
            <?php if (in_array($user['role'], ['admin', 'auditor'], true)): ?>
                <li class="nav-item<?= $activeClass(['/reviews']); ?>">
                    <a class="nav-link<?= $activeClass(['/reviews']); ?>" href="/reviews">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Review Syariah</span>
                    </a>
                </li>
                <li class="nav-item<?= $activeClass(['/audit']); ?>">
                    <a class="nav-link<?= $activeClass(['/audit']); ?>" href="/audit">
                        <i class="fas fa-file-signature"></i>
                        <span>Audit Log</span>
                    </a>
                </li>
                <li class="nav-item<?= $activeClass(['/reports']); ?>">
                    <a class="nav-link<?= $activeClass(['/reports']); ?>" href="/reports">
                        <i class="fas fa-chart-pie"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle" type="button" aria-label="Toggle sidebar"></button>
        </div>
    </ul>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light topbar shadow-sm">
                <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button" aria-label="Toggle sidebar">
                    <i class="fa fa-bars"></i>
                </button>
                <form class="d-none d-sm-inline-block form-inline me-auto ms-3 my-2 my-md-0 w-50">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari laporan, eksposur, atau hedge..." aria-label="Pencarian">
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
            </nav>
            <div class="container-fluid py-4">
                <?= $content ?? '' ?>
            </div>
        </div>
        <footer class="sticky-footer bg-white shadow-sm mt-auto">
            <div class="container my-auto">
                <div class="text-center my-auto">
                    <span class="text-muted small">&copy; <?= date('Y'); ?> Hedging Syariah · Kepatuhan dan Transparansi</span>
                </div>
            </div>
        </footer>
    </div>
</div>
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js" integrity="sha256-SJk3F6/Dr7guPmyAnbcW2CYwiVdc+GqOR/mdrIW6DC8=" crossorigin="anonymous"></script>
<script>
    const sidebar = document.getElementById('accordionSidebar');
    const toggleButtons = [
        document.getElementById('sidebarToggle'),
        document.getElementById('sidebarToggleTop')
    ].filter(Boolean);
    toggleButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-toggled');
            sidebar.classList.toggle('toggled');
        });
    });
</script>
<?= $scripts ?? '' ?>
</body>
</html>
