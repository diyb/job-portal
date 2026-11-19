<?php
$cur = $_GET['page'] ?? 'dashboard';
if ($cur === 'job_edit') $cur = 'jobs';
$me = current_user();
$items = [
    ['dashboard', 'speedometer2', 'Dashboard'],
    ['users', 'people', 'Kasutajad'],
    ['companies', 'buildings', 'Ettevõtted'],
    ['jobs', 'briefcase', 'Töökuulutused'],
    ['categories', 'tags', 'Kategooriad'],
    ['applications', 'file-earmark-text', 'Kandideerimised'],
    ['reports', 'flag', 'Kaebused'],
    ['settings', 'gear', 'Seaded'],
];
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Admin') ?> · JobPortal Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/style.css')) ?>" rel="stylesheet">
    <link href="public/css/admin.css" rel="stylesheet">
</head>
<body>
<div class="admin-wrap">
    <aside class="admin-side">
        <a class="admin-brand" href="<?= e(url('dashboard')) ?>"><i class="bi bi-briefcase-fill"></i> Job<span>Portal</span> Admin</a>
        <nav>
            <?php foreach ($items as [$p, $icon, $label]): ?>
                <a href="<?= e(url($p)) ?>" class="<?= $cur === $p ? 'active' : '' ?>"><i class="bi bi-<?= $icon ?>"></i> <?= e($label) ?></a>
            <?php endforeach; ?>
        </nav>
        <a class="back-link" href="<?= e(front_url('start')) ?>"><i class="bi bi-box-arrow-up-right"></i> Vaata veebilehte</a>
    </aside>
    <div class="admin-main">
        <header class="admin-top">
            <h1 class="h4 fw-bold mb-0"><?= e($title ?? '') ?></h1>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">Tere, <?= e($me['name'] ?? 'admin') ?>!</span>
                <form method="post" action="<?= e(url('logout')) ?>"><?= csrf_field() ?><button class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-right"></i> Välja</button></form>
            </div>
        </header>
        <div class="admin-content">
            <?php foreach (flashes() as $m): ?>
                <div class="alert alert-<?= e($m['type']) ?> alert-dismissible fade show"><?= e($m['msg']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endforeach; ?>
            <?= $content ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
