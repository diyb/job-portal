<?php $sidebar = 'account'; ?>
<h1 class="h3 fw-bold mb-4">Minu kandideerimised</h1>
<div class="panel">
    <?php foreach ($list as $a): ?>
        <div class="row-link">
            <?= logo_html(['name' => $a['company_name'], 'logo' => $a['company_logo']], 40) ?>
            <span class="flex-grow-1"><a class="fw-semibold" href="<?= e(url('job', ['id' => $a['job_id']])) ?>"><?= e($a['title']) ?></a>
                <div class="small text-muted"><?= e($a['company_name']) ?></div></span>
            <span class="text-muted small me-3"><?= e(fmt_date($a['created_at'])) ?></span><?= status_badge($a['status']) ?>
        </div>
    <?php endforeach; ?>
    <?php if (!$list): ?><div class="p-4 text-muted">Kandideerimisi pole. <a href="<?= e(url('jobs')) ?>">Otsi töökohta</a>.</div><?php endif; ?>
</div>
