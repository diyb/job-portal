<form method="get" action="index.php" class="d-flex gap-2 mb-3" style="max-width:320px"><input type="hidden" name="page" value="applications">
    <select name="status" class="form-select" onchange="this.form.submit()"><option value="">Kõik staatused</option>
        <?php foreach ($statuses as $s): ?><option <?= $status === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select></form>
<div class="panel table-responsive">
<table class="table align-middle mb-0">
    <thead><tr><th>#</th><th>Kandidaat</th><th>Kuulutus</th><th>Ettevõte</th><th>Kuupäev</th><th>Staatus</th></tr></thead>
    <tbody>
    <?php foreach ($apps as $a): ?>
        <tr><td><?= (int)$a['id'] ?></td><td><?= e($a['user_name']) ?><div class="small text-muted"><?= e($a['user_email']) ?></div></td>
            <td><?= e($a['title']) ?></td><td><?= e($a['company_name']) ?></td><td class="small"><?= e(fmt_date($a['created_at'])) ?></td><td><?= status_badge($a['status']) ?></td></tr>
    <?php endforeach; ?>
    <?php if (!$apps): ?><tr><td colspan="6" class="text-center text-muted py-4">Kandideerimisi pole.</td></tr><?php endif; ?>
    </tbody>
</table>
</div>
