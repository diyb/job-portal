<section class="hero">
    <div class="container">
        <h1>Leia oma unistuste <span>töö</span></h1>
        <p class="lead">Avasta tuhandeid tööpakkumisi ja leia endale sobiv töökoht kiiresti ja lihtsalt.</p>
        <form class="search-bar" method="get" action="index.php">
            <input type="hidden" name="page" value="jobs">
            <div class="input-group-text-wrap"><i class="bi bi-search"></i><input type="text" name="q" class="form-control" placeholder="Otsi töökohta, nt arendaja..."></div>
            <div class="input-group-text-wrap loc"><i class="bi bi-geo-alt"></i>
                <select name="location" class="form-select"><option value="">Kõik asukohad</option>
                    <?php foreach ($locations as $l): ?><option value="<?= (int)$l['id'] ?>"><?= e($l['name']) ?></option><?php endforeach; ?></select></div>
            <button class="btn btn-primary px-4">Otsi</button>
        </form>
        <div class="hero-stats">
            <div><i class="bi bi-briefcase-fill"></i><div><strong><?= (int)$stats['jobs'] ?></strong><span>Tööpakkumist</span></div></div>
            <div><i class="bi bi-buildings-fill"></i><div><strong><?= (int)$stats['companies'] ?></strong><span>Ettevõtet</span></div></div>
            <div><i class="bi bi-people-fill"></i><div><strong><?= (int)$stats['users'] ?></strong><span>Kasutajat</span></div></div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h2 class="h4 fw-bold mb-0">Uusimad tööpakkumised</h2>
        <a href="<?= e(url('jobs')) ?>">Vaata kõiki <i class="bi bi-arrow-right"></i></a>
    </div>
    <?php foreach ($jobs as $job) include __DIR__ . '/_jobcard.php'; ?>
    <?php if (!$jobs): ?><p class="text-muted">Tööpakkumisi veel ei ole.</p><?php endif; ?>

    <h2 class="h4 fw-bold mt-5 mb-3">Valdkonnad</h2>
    <div class="d-flex flex-wrap gap-2">
        <?php foreach ($categories as $c): ?>
            <a class="chip chip-lg" href="<?= e(url('jobs', ['category' => $c['id']])) ?>"><?= e($c['name']) ?></a>
        <?php endforeach; ?>
    </div>

    <div class="d-flex justify-content-between align-items-end mt-5 mb-3">
        <h2 class="h4 fw-bold mb-0">Ettevõtted</h2>
        <a href="<?= e(url('companies')) ?>">Vaata kõiki <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-3">
        <?php foreach ($companies as $c): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a class="company-tile" href="<?= e(url('company', ['id' => $c['id']])) ?>">
                    <?= logo_html($c, 56) ?>
                    <div class="fw-semibold mt-2"><?= e($c['name']) ?></div>
                    <div class="small text-muted"><?= (int)$c['job_count'] ?> kuulutust</div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
