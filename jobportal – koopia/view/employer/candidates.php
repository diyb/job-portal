<a href="<?= e(url('employer_jobs')) ?>" class="small"><i class="bi bi-arrow-left"></i> Töökuulutused</a>
<h1 class="h3 fw-bold my-3">Kandidaadid: <?= e($job['title']) ?> <span class="text-muted">(<?= count($apps) ?>)</span></h1>
<?php foreach ($apps as $a): ?>
    <div class="panel p-3 mb-3">
        <div class="d-flex flex-wrap gap-3 align-items-start">
            <span class="avatar avatar-lg"><?= e(initials($a['user_name'])) ?></span>
            <div class="flex-grow-1">
                <div class="fw-bold"><?= e($a['user_name']) ?> <?= status_badge($a['status']) ?></div>
                <div class="small text-muted">
                    <i class="bi bi-envelope"></i> <a href="mailto:<?= e($a['user_email']) ?>"><?= e($a['user_email']) ?></a>
                    <?php if ($a['user_phone']): ?> · <i class="bi bi-telephone"></i> <?= e($a['user_phone']) ?><?php endif; ?>
                    <?php if ($a['user_city']): ?> · <i class="bi bi-geo-alt"></i> <?= e($a['user_city']) ?><?php endif; ?>
                    · Kandideeris <?= e(fmt_date($a['created_at'])) ?>
                </div>
                <?php if ($a['message']): ?><div class="mt-2 p-2 bg-light rounded small"><?= nl2br(e($a['message'])) ?></div><?php endif; ?>
                <div class="mt-2">
                    <?php if ($a['cv_id']): ?><a class="btn btn-sm btn-outline-primary" href="<?= e(url('cv_download', ['id' => $a['cv_id']])) ?>"><i class="bi bi-download"></i> CV: <?= e($a['cv_name']) ?></a>
                    <?php else: ?><span class="small text-muted">CV lisamata</span><?php endif; ?>
                    <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('thread', ['with' => $a['user_id']])) ?>"><i class="bi bi-chat-dots"></i> Sõnum</a>
                </div>
            </div>
            <form method="post" action="<?= e(url('employer_app_status')) ?>" class="d-flex gap-1"><?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                <select name="status" class="form-select form-select-sm">
                    <?php foreach ($statuses as $s): ?><option <?= $a['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?>
                </select>
                <button class="btn btn-sm btn-primary">OK</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<?php if (!$apps): ?><div class="empty-box">Sellele kuulutusele pole veel kandideeritud.</div><?php endif; ?>
