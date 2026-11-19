<?php $sidebar = match ($cu['role'] ?? '') { 'user' => 'account', 'employer' => 'employer', default => '' }; ?>
<?php if ($sidebar === ''): ?><div class="container py-4"><?php endif; ?>
<h1 class="h3 fw-bold mb-4">Sõnumid</h1>
<div class="panel">
    <?php foreach ($convs as $c): ?>
        <a class="row-link <?= $c['unread'] ? 'unread' : '' ?>" href="<?= e(url('thread', ['with' => $c['other_id']])) ?>">
            <span class="avatar avatar-lg"><?= e(initials($c['other_name'])) ?></span>
            <span class="flex-grow-1"><strong><?= e($c['other_name']) ?></strong>
                <div class="small text-muted text-truncate"><?= e(mb_substr($c['body'], 0, 90)) ?></div></span>
            <?php if ($c['unread']): ?><span class="badge text-bg-primary me-2"><?= (int)$c['unread'] ?></span><?php endif; ?>
            <span class="small text-muted"><?= e(date('d.m H:i', strtotime($c['created_at']))) ?></span>
        </a>
    <?php endforeach; ?>
    <?php if (!$convs): ?><div class="p-4 text-muted">Vestlusi pole. Vajuta kuulutusel „Kirjuta tööandjale" või kandidaadi juures „Sõnum".</div><?php endif; ?>
</div>
<?php if ($sidebar === ''): ?></div><?php endif; ?>
