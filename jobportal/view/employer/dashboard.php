<h1 class="h3 fw-bold mb-4">Tere tulemast, <?= e($c['name']) ?>!</h1>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-briefcase-fill c-blue"></i><div><small>Aktiivseid kuulutusi</small><strong><?= (int)$stats['active_jobs'] ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-people-fill c-green"></i><div><small>Kandidaate</small><strong><?= (int)$stats['applicants'] ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-envelope-open-fill c-violet"></i><div><small>Uusi avaldusi</small><strong><?= (int)$stats['new'] ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-check2-square c-orange"></i><div><small>Sobivaid</small><strong><?= (int)$stats['suitable'] ?></strong></div></div></div>
</div>
<div class="row g-3">
    <div class="col-xl-7"><div class="panel p-3 h-100">
        <h2 class="h6 fw-bold">Kandideerimiste graafik (7 päeva)</h2>
        <?php include __DIR__ . '/_chart.php'; ?>
    </div></div>
    <div class="col-xl-5"><div class="panel p-3 h-100">
        <div class="d-flex justify-content-between"><h2 class="h6 fw-bold">Viimased kandideerimised</h2><a class="small" href="<?= e(url('employer_jobs')) ?>">Vaata kõiki →</a></div>
        <?php foreach ($latest as $a): ?>
            <a class="d-flex align-items-center gap-2 py-2 border-bottom text-decoration-none text-body" href="<?= e(url('employer_candidates', ['job_id' => $a['job_id']])) ?>">
                <span class="avatar avatar-lg"><?= e(initials($a['user_name'])) ?></span>
                <span><strong class="d-block"><?= e($a['user_name']) ?></strong><small class="text-muted"><?= e($a['title']) ?> – <?= e(fmt_date($a['created_at'])) ?></small></span>
            </a>
        <?php endforeach; ?>
        <?php if (!$latest): ?><p class="text-muted small mt-2">Kandideerimisi veel pole.</p><?php endif; ?>
    </div></div>
</div>
