<form method="post" action="<?= e(url('job_edit', ['id' => $job['id']])) ?>" class="panel p-4"><?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)$job['id'] ?>">
    <p class="text-muted small">Ettevõte: <strong><?= e($job['company_name']) ?></strong></p>
    <div class="row g-3">
        <div class="col-12"><label class="form-label">Ametinimetus</label><input name="title" class="form-control" value="<?= e($job['title']) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Valdkond</label><select name="category_id" class="form-select"><option value="">—</option>
            <?php foreach ($categories as $x): ?><option value="<?= (int)$x['id'] ?>" <?= (int)$job['category_id'] === (int)$x['id'] ? 'selected' : '' ?>><?= e($x['name']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="form-label">Asukoht</label><select name="location_id" class="form-select"><option value="">—</option>
            <?php foreach ($locations as $x): ?><option value="<?= (int)$x['id'] ?>" <?= (int)$job['location_id'] === (int)$x['id'] ? 'selected' : '' ?>><?= e($x['name']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="form-label">Staatus</label><select name="status" class="form-select">
            <option value="active" <?= $job['status'] === 'active' ? 'selected' : '' ?>>Aktiivne</option><option value="closed" <?= $job['status'] === 'closed' ? 'selected' : '' ?>>Suletud</option></select></div>
        <div class="col-12"><label class="form-label">Kirjeldus</label><textarea name="description" rows="7" class="form-control" required><?= e($job['description']) ?></textarea></div>
        <div class="col-12"><label class="form-label">Nõuded</label><textarea name="requirements" rows="4" class="form-control"><?= e($job['requirements']) ?></textarea></div>
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Salvesta</button><a class="btn btn-light" href="<?= e(url('jobs')) ?>">Tühista</a></div>
</form>
