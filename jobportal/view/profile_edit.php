<?php $sidebar = 'account'; ?>
<h1 class="h3 fw-bold mb-4">Profiil ja CV</h1>
<div class="panel p-4 mb-4">
    <h2 class="h5 fw-bold mb-3">Isikuandmed</h2>
    <form method="post" action="<?= e(url('profile_edit')) ?>"><?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nimi</label><input name="name" class="form-control" value="<?= e($u['name']) ?>" required></div>
            <div class="col-md-6"><label class="form-label">E-post</label><input class="form-control" value="<?= e($u['email']) ?>" disabled></div>
            <div class="col-md-6"><label class="form-label">Telefon</label><input name="phone" class="form-control" value="<?= e($u['phone']) ?>"></div>
            <div class="col-md-6"><label class="form-label">Linn</label><input name="city" class="form-control" value="<?= e($u['city']) ?>"></div>
            <div class="col-12"><label class="form-label">Endast</label><textarea name="about" rows="4" class="form-control"><?= e($u['about']) ?></textarea></div>
        </div>
        <button class="btn btn-primary mt-3">Salvesta</button>
    </form>
</div>
<div class="panel p-4">
    <h2 class="h5 fw-bold mb-3">Minu CV-d</h2>
    <?php foreach ($cvs as $cv): ?>
        <div class="d-flex align-items-center gap-3 border-bottom py-2">
            <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
            <div class="flex-grow-1"><a href="<?= e(url('cv_download', ['id' => $cv['id']])) ?>"><?= e($cv['original_name']) ?></a>
                <div class="small text-muted"><?= round($cv['size'] / 1024) ?> KB · <?= e(fmt_date($cv['created_at'])) ?></div></div>
            <form method="post" action="<?= e(url('cv_delete')) ?>" onsubmit="return confirm('Kustutan CV?')"><?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$cv['id'] ?>"><button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
        </div>
    <?php endforeach; ?>
    <?php if (!$cvs): ?><p class="text-muted">CV-d pole veel lisatud.</p><?php endif; ?>
    <form method="post" action="<?= e(url('cv_upload')) ?>" enctype="multipart/form-data" class="mt-3 d-flex flex-wrap gap-2"><?= csrf_field() ?>
        <input type="file" name="cv" class="form-control w-auto flex-grow-1" accept=".pdf,.doc,.docx" required>
        <button class="btn btn-primary"><i class="bi bi-upload"></i> Lae üles</button>
    </form>
    <div class="form-text">Lubatud: PDF, DOC, DOCX · max 3 MB.</div>
</div>
