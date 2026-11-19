<h1 class="h3 fw-bold mb-4">Statistika</h1>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-briefcase-fill c-blue"></i><div><small>Aktiivseid</small><strong><?= (int)$stats['active_jobs'] ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-people-fill c-green"></i><div><small>Kandideerimisi kokku</small><strong><?= (int)$stats['applicants'] ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-envelope-open-fill c-violet"></i><div><small>Uusi</small><strong><?= (int)$stats['new'] ?></strong></div></div></div>
    <div class="col-6 col-xl-3"><div class="stat-card"><i class="bi bi-check2-square c-orange"></i><div><small>Sobivaid</small><strong><?= (int)$stats['suitable'] ?></strong></div></div></div>
</div>
<div class="panel p-3 mb-4"><h2 class="h6 fw-bold">Kandideerimised viimase 14 päeva jooksul</h2><?php include __DIR__ . '/_chart.php'; ?></div>
<div class="panel table-responsive">
    <table class="table mb-0"><thead><tr><th>Kuulutus</th><th>Staatus</th><th class="text-end">Kandidaate</th></tr></thead><tbody>
    <?php foreach ($jobs as $j): ?>
        <tr><td><?= e($j['title']) ?></td><td><?= $j['status'] === 'active' ? 'Aktiivne' : 'Suletud' ?></td><td class="text-end"><?= (int)$j['applicants'] ?></td></tr>
    <?php endforeach; ?></tbody></table>
</div>
