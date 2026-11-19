<?php $isUser = ($cu['role'] ?? '') === 'user'; ?>
<div class="container py-4">
    <a href="<?= e(url('jobs')) ?>" class="small"><i class="bi bi-arrow-left"></i> Tagasi otsingusse</a>
    <div class="detail-head mt-3">
        <?= logo_html(['name' => $job['company_name'], 'logo' => $job['company_logo']], 84) ?>
        <div class="flex-grow-1">
            <h1 class="h2 fw-bold mb-1"><?= e($job['title']) ?> <span class="chip chip-blue align-middle fs-6"><?= e($job['employment_type']) ?></span>
                <?php if ($job['status'] !== 'active'): ?><span class="chip chip-red fs-6">Suletud</span><?php endif; ?></h1>
            <div class="text-muted">
                <i class="bi bi-building"></i> <a href="<?= e(url('company', ['id' => $job['company_id']])) ?>"><?= e($job['company_name']) ?></a>
                <?php if ($job['location_name']): ?> &nbsp; <i class="bi bi-geo-alt"></i> <?= e($job['location_name']) ?>, Eesti<?php endif; ?>
                &nbsp; <i class="bi bi-cash-stack"></i> <?= e(money_range($job['salary_min'], $job['salary_max'])) ?>
            </div>
        </div>
        <div class="d-flex flex-column gap-2">
            <?php if ($applied): ?>
                <span class="btn btn-success disabled"><i class="bi bi-check2-circle"></i> Oled kandideerinud</span>
            <?php elseif (!$cu): ?>
                <a href="<?= e(url('login')) ?>" class="btn btn-primary btn-lg">Logi sisse ja kandideeri</a>
            <?php elseif ($isUser && $job['status'] === 'active'): ?>
                <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#applyModal">Kandideeri nüüd</button>
            <?php endif; ?>
            <?php if ($isUser): ?>
                <form method="post" action="<?= e(url('favorite_toggle')) ?>"><?= csrf_field() ?>
                    <input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>"><input type="hidden" name="back" value="<?= e(url('job', ['id' => $job['id']])) ?>">
                    <button class="btn btn-outline-secondary w-100"><i class="bi bi-heart<?= $isFav ? '-fill text-danger' : '' ?>"></i> <?= $isFav ? 'Lemmikutes' : 'Salvesta lemmikuks' ?></button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-8">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t1">Kirjeldus</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t2">Nõuded</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t3">Ettevõte</button></li>
            </ul>
            <div class="tab-content pt-3">
                <div class="tab-pane fade show active" id="t1"><div class="prose"><?= nl2br(e($job['description'])) ?></div></div>
                <div class="tab-pane fade" id="t2"><div class="prose"><?= $job['requirements'] ? nl2br(e($job['requirements'])) : '<span class="text-muted">Nõudeid pole lisatud.</span>' ?></div></div>
                <div class="tab-pane fade" id="t3">
                    <div class="prose"><?= $company['description'] ? nl2br(e($company['description'])) : '<span class="text-muted">Ettevõtte kirjeldus puudub.</span>' ?></div>
                    <a class="btn btn-outline-primary btn-sm mt-3" href="<?= e(url('company', ['id' => $company['id']])) ?>">Ettevõtte profiil</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="info-box">
                <h2 class="h5 fw-bold">Lisa info</h2>
                <ul class="list-unstyled mb-0">
                    <li><i class="bi bi-calendar3"></i> <strong>Töökoht:</strong> <?= e($job['employment_type']) ?></li>
                    <li><i class="bi bi-geo-alt"></i> <strong>Asukoht:</strong> <?= e($job['location_name'] ?? '—') ?></li>
                    <li><i class="bi bi-tag"></i> <strong>Valdkond:</strong> <?= e($job['category_name'] ?? '—') ?></li>
                    <li><i class="bi bi-cash-stack"></i> <strong>Palk:</strong> <?= e(money_range($job['salary_min'], $job['salary_max'])) ?></li>
                    <li><i class="bi bi-clock"></i> <strong>Tööle asumine:</strong> <?= e($job['start_date']) ?></li>
                    <li><i class="bi bi-megaphone"></i> <strong>Avaldatud:</strong> <?= e(fmt_date($job['created_at'])) ?></li>
                </ul>
                <?php if ($cu && $cu['role'] === 'user'): ?>
                    <a class="btn btn-outline-primary btn-sm mt-3 w-100" href="<?= e(url('thread', ['with' => $company['user_id']])) ?>"><i class="bi bi-chat-dots"></i> Kirjuta tööandjale</a>
                <?php endif; ?>
            </div>
            <?php if ($cu && $cu['role'] !== 'admin'): ?>
                <details class="mt-3 small">
                    <summary class="text-muted">Teata sobimatust kuulutusest</summary>
                    <form method="post" action="<?= e(url('report')) ?>" class="mt-2"><?= csrf_field() ?>
                        <input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>">
                        <textarea name="reason" class="form-control form-control-sm mb-2" rows="3" required minlength="5" placeholder="Kirjelda probleemi"></textarea>
                        <button class="btn btn-sm btn-outline-danger">Saada kaebus</button>
                    </form>
                </details>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($isUser && !$applied && $job['status'] === 'active'): ?>
<div class="modal fade" id="applyModal" tabindex="-1"><div class="modal-dialog"><form method="post" action="<?= e(url('apply')) ?>" class="modal-content">
    <?= csrf_field() ?><input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>">
    <div class="modal-header"><h5 class="modal-title">Kandideeri: <?= e($job['title']) ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <label class="form-label">CV</label>
        <?php if ($cvs): ?>
            <select name="cv_id" class="form-select mb-3"><?php foreach ($cvs as $cv): ?><option value="<?= (int)$cv['id'] ?>"><?= e($cv['original_name']) ?></option><?php endforeach; ?></select>
        <?php else: ?>
            <div class="alert alert-warning py-2 small">CV-d pole üles laaditud. Saad selle lisada <a href="<?= e(url('profile_edit')) ?>">profiilis</a> või kandideerida ilma.</div>
            <input type="hidden" name="cv_id" value="">
        <?php endif; ?>
        <label class="form-label">Saatekiri</label>
        <textarea name="message" class="form-control" rows="5" maxlength="3000" placeholder="Räägi endast ja miks sobid sellele ametikohale"></textarea>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Katkesta</button><button class="btn btn-primary">Saada avaldus</button></div>
</form></div></div>
<?php endif; ?>
