<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h1 class="h3 fw-bold mb-0">Minu töökuulutused</h1>
    <a class="btn btn-primary" href="<?= e(url('employer_job_form')) ?>"><i class="bi bi-plus-lg"></i> Lisa kuulutus</a>
</div>
<div class="panel table-responsive">
    <table class="table align-middle mb-0">
        <thead><tr><th>Ametinimetus</th><th>Asukoht</th><th>Palk</th><th>Kandidaate</th><th>Staatus</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($jobs as $j): ?>
            <tr>
                <td><a class="fw-semibold" href="<?= e(url('job', ['id' => $j['id']])) ?>"><?= e($j['title']) ?></a><div class="small text-muted"><?= e(fmt_date($j['created_at'])) ?></div></td>
                <td><?= e($j['location_name'] ?? '—') ?></td>
                <td class="text-nowrap"><?= e(money_range($j['salary_min'], $j['salary_max'])) ?></td>
                <td><a href="<?= e(url('employer_candidates', ['job_id' => $j['id']])) ?>"><span class="badge text-bg-primary"><?= (int)$j['applicants'] ?></span></a></td>
                <td><?= $j['status'] === 'active' ? '<span class="badge text-bg-success">Aktiivne</span>' : '<span class="badge text-bg-secondary">Suletud</span>' ?></td>
                <td class="text-end text-nowrap">
                    <a class="btn btn-sm btn-outline-primary" href="<?= e(url('employer_candidates', ['job_id' => $j['id']])) ?>" title="Kandidaadid"><i class="bi bi-people"></i></a>
                    <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('employer_job_form', ['id' => $j['id']])) ?>" title="Muuda"><i class="bi bi-pencil"></i></a>
                    <form class="d-inline" method="post" action="<?= e(url('employer_job_delete')) ?>" onsubmit="return confirm('Kustutan kuulutuse koos kandideerimistega?')"><?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int)$j['id'] ?>"><button class="btn btn-sm btn-outline-danger" title="Kustuta"><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$jobs): ?><tr><td colspan="6" class="text-center text-muted py-4">Kuulutusi pole. Lisa esimene!</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
