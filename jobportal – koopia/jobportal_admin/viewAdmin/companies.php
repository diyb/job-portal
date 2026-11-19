<div class="panel table-responsive">
<table class="table align-middle mb-0">
    <thead><tr><th>#</th><th>Ettevõte</th><th>Omanik</th><th>Linn</th><th>Kuulutusi</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($companies as $c): ?>
        <tr>
            <td><?= (int)$c['id'] ?></td>
            <td><a class="fw-semibold" href="<?= e(front_url('company', ['id' => $c['id']])) ?>" target="_blank"><?= e($c['name']) ?></a></td>
            <td><?= e($c['owner_email']) ?> <?= $c['owner_status'] === 'blocked' ? '<span class="badge text-bg-danger">blokeeritud</span>' : '' ?></td>
            <td><?= e($c['city'] ?? '—') ?></td>
            <td><?= (int)$c['job_count'] ?></td>
            <td class="text-end"><form method="post" action="<?= e(url('company_delete')) ?>" onsubmit="return confirm('Kustutan ettevõtte koos kuulutustega?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<p class="small text-muted mt-2">Ettevõtte konto blokeerimiseks ava jaotis „Kasutajad".</p>
