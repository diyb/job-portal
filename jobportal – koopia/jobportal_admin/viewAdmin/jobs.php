<form method="get" action="index.php" class="d-flex gap-2 mb-3" style="max-width:420px"><input type="hidden" name="page" value="jobs">
    <input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Ametinimetus või ettevõte"><button class="btn btn-primary">Otsi</button></form>
<div class="panel table-responsive">
<table class="table align-middle mb-0">
    <thead><tr><th>#</th><th>Kuulutus</th><th>Ettevõte</th><th>Valdkond</th><th>Kandid.</th><th>Staatus</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($jobs as $j): ?>
        <tr>
            <td><?= (int)$j['id'] ?></td>
            <td><a class="fw-semibold" target="_blank" href="<?= e(front_url('job', ['id' => $j['id']])) ?>"><?= e($j['title']) ?></a><div class="small text-muted"><?= e(fmt_date($j['created_at'])) ?></div></td>
            <td><?= e($j['company_name']) ?></td>
            <td><?= e($j['category_name'] ?? '—') ?></td>
            <td><?= (int)$j['applicants'] ?></td>
            <td><?= $j['status'] === 'active' ? '<span class="badge text-bg-success">Aktiivne</span>' : '<span class="badge text-bg-secondary">Suletud</span>' ?></td>
            <td class="text-end text-nowrap">
                <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('job_edit', ['id' => $j['id']])) ?>" title="Muuda"><i class="bi bi-pencil"></i></a>
                <form class="d-inline" method="post" action="<?= e(url('job_toggle')) ?>"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$j['id'] ?>">
                    <button class="btn btn-sm btn-outline-warning" title="Ava / sulge"><i class="bi bi-toggle2-on"></i></button></form>
                <form class="d-inline" method="post" action="<?= e(url('job_delete')) ?>" onsubmit="return confirm('Kustutan kuulutuse?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$j['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger" title="Kustuta"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
