<h1 class="h3 fw-bold mb-4">Ettevõtte profiil</h1>
<form method="post" action="<?= e(url('employer_company')) ?>" enctype="multipart/form-data" class="panel p-4"><?= csrf_field() ?>
    <div class="d-flex align-items-center gap-3 mb-4">
        <?= logo_html($c, 80) ?>
        <div><label class="form-label mb-1">Logo (PNG, JPG, WEBP · max 1 MB)</label><input type="file" name="logo" class="form-control" accept=".png,.jpg,.jpeg,.webp"></div>
    </div>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Ettevõtte nimi *</label><input name="name" class="form-control" value="<?= e($c['name']) ?>" required></div>
        <div class="col-md-6"><label class="form-label">Linn</label><input name="city" class="form-control" value="<?= e($c['city']) ?>"></div>
        <div class="col-md-4"><label class="form-label">Kontakt e-post</label><input type="email" name="email" class="form-control" value="<?= e($c['email']) ?>"></div>
        <div class="col-md-4"><label class="form-label">Telefon</label><input name="phone" class="form-control" value="<?= e($c['phone']) ?>"></div>
        <div class="col-md-4"><label class="form-label">Veebileht</label><input name="website" class="form-control" placeholder="https://" value="<?= e($c['website']) ?>"></div>
        <div class="col-12"><label class="form-label">Kirjeldus</label><textarea name="description" rows="6" class="form-control"><?= e($c['description']) ?></textarea></div>
    </div>
    <button class="btn btn-primary mt-4">Salvesta</button>
</form>
