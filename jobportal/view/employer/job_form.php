<?php $v = fn($k, $d = '') => e($job[$k] ?? $d); ?>
<h1 class="h3 fw-bold mb-4"><?= $job && !empty($job['id']) ? 'Muuda töökuulutust' : 'Lisa töökuulutus' ?></h1>
<form method="post" action="<?= e(url('employer_job_save')) ?>" class="panel p-4"><?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)($job['id'] ?? 0) ?>">
    <div class="row g-3">
        <div class="col-12"><label class="form-label">Ametinimetus *</label><input name="title" class="form-control" value="<?= $v('title') ?>" required maxlength="190"></div>
        <div class="col-md-6"><label class="form-label">Valdkond</label>
            <select name="category_id" class="form-select"><option value="">—</option>
                <?php foreach ($categories as $x): ?><option value="<?= (int)$x['id'] ?>" <?= (int)($job['category_id'] ?? 0) === (int)$x['id'] ? 'selected' : '' ?>><?= e($x['name']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-6"><label class="form-label">Asukoht</label>
            <select name="location_id" class="form-select"><option value="">—</option>
                <?php foreach ($locations as $x): ?><option value="<?= (int)$x['id'] ?>" <?= (int)($job['location_id'] ?? 0) === (int)$x['id'] ? 'selected' : '' ?>><?= e($x['name']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="form-label">Töökoormus</label>
            <select name="employment_type" class="form-select">
                <?php foreach (['Täistööaeg', 'Osaline tööaeg', 'Kaugtöö', 'Praktika'] as $t): ?><option <?= ($job['employment_type'] ?? '') === $t ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="form-label">Palk alates (€)</label><input type="number" min="0" name="salary_min" class="form-control" value="<?= $v('salary_min') ?>"></div>
        <div class="col-md-4"><label class="form-label">Palk kuni (€)</label><input type="number" min="0" name="salary_max" class="form-control" value="<?= $v('salary_max') ?>"></div>
        <div class="col-md-6"><label class="form-label">Tööle asumine</label><input name="start_date" class="form-control" value="<?= $v('start_date', 'Esimesel võimalusel') ?>"></div>
        <div class="col-md-6"><label class="form-label">Staatus</label>
            <select name="status" class="form-select"><option value="active" <?= ($job['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Aktiivne</option><option value="closed" <?= ($job['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Suletud</option></select></div>
        <div class="col-12"><label class="form-label">Kirjeldus ja tööülesanded *</label><textarea name="description" rows="8" class="form-control" required><?= $v('description') ?></textarea></div>
        <div class="col-12"><label class="form-label">Nõuded</label><textarea name="requirements" rows="5" class="form-control"><?= $v('requirements') ?></textarea></div>
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Salvesta</button><a class="btn btn-light" href="<?= e(url('employer_jobs')) ?>">Tühista</a></div>
</form>
