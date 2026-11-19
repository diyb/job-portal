<div class="container py-4">
    <div class="row g-4">
        <aside class="col-lg-3">
            <form method="get" action="index.php" class="filter-box">
                <input type="hidden" name="page" value="jobs">
                <h2 class="h5 fw-bold mb-3">Filtrid</h2>
                <label class="form-label small fw-semibold">Märksõna</label>
                <input type="text" name="q" value="<?= e($f['q']) ?>" class="form-control mb-3" placeholder="Ametinimetus, ettevõte">
                <label class="form-label small fw-semibold">Valdkond</label>
                <select name="category" class="form-select mb-3"><option value="">Kõik valdkonnad</option>
                    <?php foreach ($categories as $c): ?><option value="<?= (int)$c['id'] ?>" <?= (int)$f['category'] === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select>
                <label class="form-label small fw-semibold">Asukoht</label>
                <select name="location" class="form-select mb-3"><option value="">Kõik asukohad</option>
                    <?php foreach ($locations as $l): ?><option value="<?= (int)$l['id'] ?>" <?= (int)$f['location'] === (int)$l['id'] ? 'selected' : '' ?>><?= e($l['name']) ?></option><?php endforeach; ?></select>
                <label class="form-label small fw-semibold">Töökoormus</label>
                <select name="type" class="form-select mb-3"><option value="">Kõik</option>
                    <?php foreach (['Täistööaeg', 'Osaline tööaeg', 'Kaugtöö', 'Praktika'] as $t): ?><option <?= $f['type'] === $t ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?></select>
                <label class="form-label small fw-semibold">Palgavahemik</label>
                <?php foreach (['1' => '0 – 1000 €', '2' => '1000 – 2000 €', '3' => '2000 € +'] as $k => $lbl): ?>
                    <div class="form-check"><input class="form-check-input" type="checkbox" name="salary[]" value="<?= $k ?>" id="s<?= $k ?>" <?= in_array((string)$k, $f['salary'], true) ? 'checked' : '' ?>>
                        <label class="form-check-label small" for="s<?= $k ?>"><?= e($lbl) ?></label></div>
                <?php endforeach; ?>
                <button class="btn btn-primary w-100 mt-3">Rakenda filtrid</button>
                <a class="btn btn-link w-100 btn-sm" href="<?= e(url('jobs')) ?>">Tühista filtrid</a>
            </form>
        </aside>
        <main class="col-lg-9">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h1 class="h4 fw-bold mb-0">Tööpakkumised <span class="text-muted">(<?= (int)$total ?>)</span></h1>
                <form method="get" action="index.php" class="d-flex align-items-center gap-2">
                    <?php foreach ($_GET as $k => $v): if ($k === 'sort' || $k === 'p') continue;
                        foreach ((array)$v as $vv): ?><input type="hidden" name="<?= e($k) ?><?= is_array($v) ? '[]' : '' ?>" value="<?= e($vv) ?>"><?php endforeach; endforeach; ?>
                    <label class="small text-muted">Sorteeri:</label>
                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="new" <?= $f['sort'] !== 'salary' ? 'selected' : '' ?>>Uuemad esmalt</option>
                        <option value="salary" <?= $f['sort'] === 'salary' ? 'selected' : '' ?>>Kõrgeim palk</option>
                    </select>
                </form>
            </div>
            <?php foreach ($jobs as $job) include __DIR__ . '/_jobcard.php'; ?>
            <?php if (!$jobs): ?><div class="empty-box">Otsingule vastavaid kuulutusi ei leitud.</div><?php endif; ?>

            <?php if ($pg['pages'] > 1):
                $q = $_GET; unset($q['p']); ?>
                <nav class="mt-4"><ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $pg['pages']; $i++): ?>
                        <li class="page-item <?= $i === $pg['page'] ? 'active' : '' ?>"><a class="page-link" href="index.php?<?= e(http_build_query($q + ['p' => $i])) ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                </ul></nav>
            <?php endif; ?>
        </main>
    </div>
</div>
