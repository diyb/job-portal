<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-people-fill c-blue"></i><div><small>Kasutajad</small><strong><?= number_format($t['users'], 0, ',', ' ') ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-buildings-fill c-green"></i><div><small>Ettevõtteid</small><strong><?= number_format($t['companies'], 0, ',', ' ') ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-briefcase-fill c-violet"></i><div><small>Töökuulutusi</small><strong><?= number_format($t['jobs'], 0, ',', ' ') ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-file-earmark-text-fill c-orange"></i><div><small>Kandideerimisi</small><strong><?= number_format($t['applications'], 0, ',', ' ') ?></strong></div></div></div>
</div>
<div class="row g-3">
    <div class="col-lg-7"><div class="panel p-3 h-100">
        <h2 class="h6 fw-bold">Viimased tegevused</h2>
        <?php foreach ($recent as $r): ?>
            <div class="d-flex justify-content-between border-bottom py-2"><span><i class="bi bi-circle-fill text-success me-2" style="font-size:.5rem"></i><strong><?= e($r['label']) ?>:</strong> <?= e($r['what']) ?></span>
                <span class="small text-muted"><?= e(date('d.m.Y H:i', strtotime($r['created_at']))) ?></span></div>
        <?php endforeach; ?>
        <?php if (!$recent): ?><p class="text-muted small mt-2">Tegevusi pole.</p><?php endif; ?>
        <?php if ($t['reports_open']): ?><a class="btn btn-sm btn-outline-danger mt-3" href="<?= e(url('reports')) ?>"><i class="bi bi-flag"></i> Lahendamata kaebusi: <?= (int)$t['reports_open'] ?></a><?php endif; ?>
    </div></div>
    <div class="col-lg-5"><div class="panel p-3 h-100">
        <h2 class="h6 fw-bold">Süsteemi statistika</h2>
        <?php
        $parts = [['Kasutajad', $t['users'], '#1f6bff'], ['Ettevõtted', $t['companies'], '#19a463'], ['Töökuulutused', $t['jobs'], '#f59a23']];
        $sum = max(1, array_sum(array_column($parts, 1)));
        $C = 2 * M_PI * 15.9155; $off = 0;
        ?>
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <svg viewBox="0 0 42 42" width="150" height="150" role="img" aria-label="Süsteemi statistika">
                <circle cx="21" cy="21" r="15.9155" fill="none" stroke="#eef1f8" stroke-width="6"/>
                <?php foreach ($parts as [$lbl, $n, $col]): $len = $n / $sum * 100; ?>
                    <circle cx="21" cy="21" r="15.9155" fill="none" stroke="<?= $col ?>" stroke-width="6"
                            stroke-dasharray="<?= round($len, 2) ?> <?= round(100 - $len, 2) ?>" stroke-dashoffset="<?= round(25 - $off, 2) ?>"><title><?= e($lbl . ': ' . $n) ?></title></circle>
                    <?php $off += $len; endforeach; ?>
            </svg>
            <ul class="list-unstyled mb-0 small">
                <?php foreach ($parts as [$lbl, $n, $col]): ?><li><i class="bi bi-circle-fill me-2" style="color:<?= $col ?>;font-size:.6rem"></i><?= e($lbl) ?> <strong><?= (int)$n ?></strong></li><?php endforeach; ?>
            </ul>
        </div>
    </div></div>
</div>
