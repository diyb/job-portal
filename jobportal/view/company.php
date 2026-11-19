<div class="container py-4">
    <a href="<?= e(url('companies')) ?>" class="small"><i class="bi bi-arrow-left"></i> Kõik ettevõtted</a>
    <div class="detail-head mt-3">
        <?= logo_html($c, 84) ?>
        <div class="flex-grow-1">
            <h1 class="h2 fw-bold mb-1"><?= e($c['name']) ?></h1>
            <div class="text-muted">
                <?php if ($c['city']): ?><i class="bi bi-geo-alt"></i> <?= e($c['city']) ?> &nbsp;<?php endif; ?>
                <?php if ($c['website']): ?><i class="bi bi-globe"></i> <a href="<?= e($c['website']) ?>" target="_blank" rel="noopener noreferrer nofollow"><?= e($c['website']) ?></a> &nbsp;<?php endif; ?>
                <?php if ($c['email']): ?><i class="bi bi-envelope"></i> <?= e($c['email']) ?><?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($c['description']): ?><div class="info-box mt-3"><h2 class="h5 fw-bold">Meist</h2><div class="prose"><?= nl2br(e($c['description'])) ?></div></div><?php endif; ?>
    <h2 class="h4 fw-bold mt-4 mb-3">Aktiivsed töökuulutused (<?= count($jobs) ?>)</h2>
    <?php foreach ($jobs as $job) include __DIR__ . '/_jobcard.php'; ?>
    <?php if (!$jobs): ?><div class="empty-box">Aktiivseid kuulutusi hetkel pole.</div><?php endif; ?>
</div>
