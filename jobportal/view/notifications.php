<?php $sidebar = match ($cu['role'] ?? '') { 'user' => 'account', 'employer' => 'employer', default => '' }; ?>
<?php if ($sidebar === ''): ?><div class="container py-4"><?php endif; ?>
<h1 class="h3 fw-bold mb-4">Teavitused</h1>
<div class="panel">
    <?php foreach ($list as $n): ?>
        <a class="row-link <?= $n['is_read'] ? '' : 'unread' ?>" href="<?= e($n['link'] ?: url('profile')) ?>">
            <i class="bi bi-bell<?= $n['is_read'] ? '' : '-fill text-primary' ?>"></i>
            <span class="flex-grow-1"><?= e($n['text']) ?></span>
            <span class="small text-muted"><?= e(date('d.m.Y H:i', strtotime($n['created_at']))) ?></span>
        </a>
    <?php endforeach; ?>
    <?php if (!$list): ?><div class="p-4 text-muted">Teavitusi pole.</div><?php endif; ?>
</div>
<?php if ($sidebar === ''): ?></div><?php endif; ?>
