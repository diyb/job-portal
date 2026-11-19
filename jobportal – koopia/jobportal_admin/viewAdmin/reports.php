<div class="panel table-responsive">
<table class="table align-middle mb-0">
    <thead><tr><th>#</th><th>Kuulutus</th><th>Kaebaja</th><th>Põhjus</th><th>Kuupäev</th><th>Staatus</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($reports as $r): ?>
        <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?php if ($r['job_id']): ?><a target="_blank" href="<?= e(front_url('job', ['id' => $r['job_id']])) ?>"><?= e($r['job_title']) ?></a> <a class="small" href="<?= e(url('job_edit', ['id' => $r['job_id']])) ?>">(muuda)</a><?php else: ?><span class="text-muted">kustutatud</span><?php endif; ?></td>
            <td><?= e($r['reporter_name']) ?></td>
            <td style="max-width:340px"><?= nl2br(e($r['reason'])) ?></td>
            <td class="small"><?= e(fmt_date($r['created_at'])) ?></td>
            <td><?= $r['status'] === 'open' ? '<span class="badge text-bg-danger">Avatud</span>' : '<span class="badge text-bg-success">Lahendatud</span>' ?></td>
            <td class="text-end"><form method="post" action="<?= e(url('report_status')) ?>"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                <input type="hidden" name="status" value="<?= $r['status'] === 'open' ? 'resolved' : 'open' ?>">
                <button class="btn btn-sm btn-outline-primary"><?= $r['status'] === 'open' ? 'Märgi lahendatuks' : 'Ava uuesti' ?></button></form></td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$reports): ?><tr><td colspan="7" class="text-center text-muted py-4">Kaebusi pole.</td></tr><?php endif; ?>
    </tbody>
</table>
</div>
