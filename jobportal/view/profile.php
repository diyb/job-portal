<?php $sidebar = 'account'; ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-0">Tere tulemast, <?= e(explode(' ', $u['name'])[0]) ?>!</h1>
        <div class="text-muted">Siin on sinu konto ülevaade</div>
    </div>
    <a class="btn btn-outline-primary btn-sm" href="<?= e(url('profile_edit')) ?>">Muuda profiili</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-briefcase-fill c-blue"></i><div><small>Kandideerimised</small><strong><?= (int)$appCount ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-star-fill c-green"></i><div><small>Lemmikud</small><strong><?= (int)$favCount ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-bell-fill c-violet"></i><div><small>Teavitused</small><strong><?= (int)$notifCount ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-file-earmark-text-fill c-orange"></i><div><small>CV staatus</small><strong class="fs-6"><?= $cvs ? 'Aktiivne <i class="bi bi-check-circle-fill text-success"></i>' : 'Puudub' ?></strong></div></div></div>
</div>
<div class="d-flex justify-content-between align-items-center mb-2">
    <h2 class="h5 fw-bold mb-0">Viimased kandideerimised</h2>
    <a href="<?= e(url('applications')) ?>" class="small">Vaata kõiki <i class="bi bi-arrow-right"></i></a>
</div>
<div class="panel">
    <?php foreach ($applications as $a): ?>
        <a class="row-link" href="<?= e(url('job', ['id' => $a['job_id']])) ?>">
            <span class="flex-grow-1"><strong><?= e($a['title']) ?></strong> <span class="text-muted">– <?= e($a['company_name']) ?></span></span>
            <span class="text-muted small me-3"><?= e(fmt_date($a['created_at'])) ?></span><?= status_badge($a['status']) ?>
        </a>
    <?php endforeach; ?>
    <?php if (!$applications): ?><div class="p-3 text-muted">Sa pole veel kandideerinud. <a href="<?= e(url('jobs')) ?>">Vaata tööpakkumisi</a>.</div><?php endif; ?>
</div>
