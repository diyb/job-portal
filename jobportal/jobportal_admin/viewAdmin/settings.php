<form method="post" action="<?= e(url('settings_save')) ?>" class="panel p-4" style="max-width:560px"><?= csrf_field() ?>
    <label class="form-label">Portaali nimi</label>
    <input name="site_name" class="form-control mb-3" value="<?= e(setting('site_name')) ?>" required>
    <label class="form-label">Kontakt e-post</label>
    <input type="email" name="contact_email" class="form-control mb-3" value="<?= e(setting('contact_email')) ?>" required>
    <label class="form-label">Kuulutusi lehel</label>
    <input type="number" min="1" max="100" name="jobs_per_page" class="form-control mb-3" value="<?= e(setting('jobs_per_page', '10')) ?>" required>
    <button class="btn btn-primary">Salvesta</button>
</form>
