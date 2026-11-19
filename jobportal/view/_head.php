<?php
$cu = current_user();
$counts = $cu ? unread_counts((int)$cu['id']) : ['notifications' => 0, 'messages' => 0];
$cur = $_GET['page'] ?? 'start';
$nav = fn(string $p, string $label, array $also = []) =>
    '<li class="nav-item"><a class="nav-link' . (in_array($cur, array_merge([$p], $also), true) ? ' active' : '') . '" href="' . e(url($p)) . '">' . e($label) . '</a></li>';
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'JobPortal') ?> · <?= e(setting('site_name', 'JobPortal')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/style.css')) ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg jp-nav navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= e(url('start')) ?>"><i class="bi bi-briefcase-fill brand-icon"></i> Job<span>Portal</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <?= $nav('start', 'Avaleht') ?>
                <?= $nav('jobs', 'Tööpakkumised', ['job']) ?>
                <?= $nav('companies', 'Ettevõtted', ['company']) ?>
                <?php if ($cu && $cu['role'] === 'user'): ?><?= $nav('profile', 'Minu konto', ['profile_edit', 'applications', 'favorites', 'notifications']) ?><?php endif; ?>
                <?php if ($cu && $cu['role'] === 'employer'): ?><?= $nav('employer', 'Ettevõtte paneel', ['employer_jobs', 'employer_job_form', 'employer_candidates', 'employer_company', 'employer_stats']) ?><?php endif; ?>
            </ul>
            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                <?php if ($cu): ?>
                    <li class="nav-item"><a class="nav-link position-relative" href="<?= e(url('messages')) ?>" title="Sõnumid"><i class="bi bi-chat-dots"></i>
                        <?php if ($counts['messages']): ?><span class="badge rounded-pill text-bg-danger nav-badge"><?= (int)$counts['messages'] ?></span><?php endif; ?></a></li>
                    <li class="nav-item"><a class="nav-link position-relative" href="<?= e(url('notifications')) ?>" title="Teavitused"><i class="bi bi-bell"></i>
                        <?php if ($counts['notifications']): ?><span class="badge rounded-pill text-bg-danger nav-badge"><?= (int)$counts['notifications'] ?></span><?php endif; ?></a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                            <span class="avatar"><?= e(initials($cu['name'])) ?></span><span class="d-lg-none"><?= e($cu['name']) ?></span></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text small text-muted"><?= e($cu['name']) ?></span></li>
                            <?php if ($cu['role'] === 'user'): ?>
                                <li><a class="dropdown-item" href="<?= e(url('profile')) ?>">Minu konto</a></li>
                                <li><a class="dropdown-item" href="<?= e(url('applications')) ?>">Kandideerimised</a></li>
                                <li><a class="dropdown-item" href="<?= e(url('favorites')) ?>">Lemmikud</a></li>
                            <?php elseif ($cu['role'] === 'employer'): ?>
                                <li><a class="dropdown-item" href="<?= e(url('employer')) ?>">Ettevõtte paneel</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="jobportal_admin/index.php">Admin paneel</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><form method="post" action="<?= e(url('logout')) ?>"><?= csrf_field() ?><button class="dropdown-item">Logi välja</button></form></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('login')) ?>">Logi sisse</a></li>
                    <li class="nav-item"><a class="btn btn-primary btn-sm px-3" href="<?= e(url('register')) ?>">Registreeru</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php if ($f = flashes()): ?>
    <div class="container mt-3">
        <?php foreach ($f as $m): ?>
            <div class="alert alert-<?= e($m['type']) ?> alert-dismissible fade show mb-2" role="alert"><?= e($m['msg']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
