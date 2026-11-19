<form method="get" action="index.php" class="d-flex gap-2 mb-3" style="max-width:420px"><input type="hidden" name="page" value="users">
    <input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Nimi või e-post"><button class="btn btn-primary">Otsi</button></form>
<div class="panel table-responsive">
<table class="table align-middle mb-0">
    <thead><tr><th>#</th><th>Nimi</th><th>E-post</th><th>Roll</th><th>Staatus</th><th>Liitus</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($users as $x): $self = (int)$x['id'] === (int)$cu['id']; ?>
        <tr>
            <td><?= (int)$x['id'] ?></td>
            <td class="fw-semibold"><?= e($x['name']) ?></td>
            <td><?= e($x['email']) ?></td>
            <td>
                <?php if ($self): ?><span class="badge text-bg-dark">admin (sina)</span>
                <?php else: ?>
                <form method="post" action="<?= e(url('user_role')) ?>" class="d-flex gap-1"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php foreach (['user' => 'Tööotsija', 'employer' => 'Tööandja', 'admin' => 'Admin'] as $k => $l): ?><option value="<?= $k ?>" <?= $x['role'] === $k ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?>
                    </select></form>
                <?php endif; ?>
            </td>
            <td><?= $x['status'] === 'active' ? '<span class="badge text-bg-success">Aktiivne</span>' : '<span class="badge text-bg-danger">Blokeeritud</span>' ?></td>
            <td class="small text-muted"><?= e(fmt_date($x['created_at'])) ?></td>
            <td class="text-end text-nowrap">
                <?php if (!$self): ?>
                <form class="d-inline" method="post" action="<?= e(url('user_block')) ?>"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button class="btn btn-sm btn-outline-warning" title="Blokeeri / taasta"><i class="bi bi-slash-circle"></i></button></form>
                <form class="d-inline" method="post" action="<?= e(url('user_delete')) ?>" onsubmit="return confirm('Kustutan kasutaja ja kõik tema andmed?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger" title="Kustuta"><i class="bi bi-trash"></i></button></form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
