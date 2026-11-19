<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h4 fw-bold mb-0">Ettevõtted <span class="text-muted">(<?= count($companies) ?>)</span></h1>
        <form method="get" action="index.php" class="d-flex gap-2"><input type="hidden" name="page" value="companies">
            <input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Otsi ettevõtet"><button class="btn btn-primary">Otsi</button></form>
    </div>
    <div class="row g-3">
        <?php foreach ($companies as $c): ?>
            <div class="col-md-6 col-lg-4">
                <a class="company-card" href="<?= e(url('company', ['id' => $c['id']])) ?>">
                    <?= logo_html($c, 64) ?>
                    <div>
                        <div class="fw-bold"><?= e($c['name']) ?></div>
                        <div class="small text-muted"><?= e($c['city'] ?? '') ?></div>
                        <span class="chip chip-blue"><?= (int)$c['job_count'] ?> aktiivset kuulutust</span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
        <?php if (!$companies): ?><div class="empty-box">Ettevõtteid ei leitud.</div><?php endif; ?>
    </div>
</div>
