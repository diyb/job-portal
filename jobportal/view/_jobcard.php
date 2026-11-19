<?php
// Töökuulutuse kaart. Vajab muutujaid: $job, $favIds
$isUser = ($cu['role'] ?? '') === 'user';
$isFav = in_array((int)$job['id'], $favIds ?? [], true);
$back = 'index.php?' . ($_SERVER['QUERY_STRING'] ?? 'page=start');
?>
<div class="job-card">
    <a class="logo-wrap" href="<?= e(url('job', ['id' => $job['id']])) ?>"><?= logo_html(['name' => $job['company_name'], 'logo' => $job['company_logo'] ?? null], 56) ?></a>
    <div class="flex-grow-1 min-w-0">
        <a class="job-title" href="<?= e(url('job', ['id' => $job['id']])) ?>"><?= e($job['title']) ?></a>
        <div class="text-muted small"><?= e($job['company_name']) ?><?php if (!empty($job['location_name'])): ?> <span class="dot">◆</span> <?= e($job['location_name']) ?><?php endif; ?></div>
        <div class="mt-1">
            <span class="chip chip-blue"><?= e($job['employment_type']) ?></span>
            <?php if (!empty($job['category_name'])): ?><span class="chip chip-violet"><?= e($job['category_name']) ?></span><?php endif; ?>
        </div>
    </div>
    <div class="text-end ms-2">
        <div class="salary"><?= e(money_range($job['salary_min'], $job['salary_max'])) ?></div>
        <?php if (!$cu): ?>
            <a href="<?= e(url('login')) ?>" class="heart" title="Logi sisse, et salvestada"><i class="bi bi-heart"></i></a>
        <?php elseif ($isUser): ?>
            <form method="post" action="<?= e(url('favorite_toggle')) ?>" class="d-inline">
                <?= csrf_field() ?><input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>"><input type="hidden" name="back" value="<?= e($back) ?>">
                <button class="heart <?= $isFav ? 'on' : '' ?>" title="Lemmik"><i class="bi bi-heart<?= $isFav ? '-fill' : '' ?>"></i></button>
            </form>
        <?php endif; ?>
    </div>
</div>
