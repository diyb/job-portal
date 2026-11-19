<?php
require __DIR__ . '/_head.php';
if (($sidebar ?? '') === 'account'):
    $cur = $_GET['page'] ?? 'profile';
    $items = [
        ['profile', 'house-door', 'Minu konto'],
        ['profile_edit', 'person-lines-fill', 'Profiil ja CV'],
        ['favorites', 'heart', 'Lemmikud'],
        ['applications', 'file-earmark-text', 'Kandideerimised'],
        ['messages', 'chat-dots', 'Sõnumid'],
        ['notifications', 'bell', 'Teavitused'],
    ];
    ?>
    <div class="container py-4">
        <div class="row g-4">
            <aside class="col-lg-3">
                <div class="side-nav">
                    <?php foreach ($items as [$p, $icon, $label]): ?>
                        <a href="<?= e(url($p)) ?>" class="<?= $cur === $p || ($p === 'messages' && $cur === 'thread') ? 'active' : '' ?>"><i class="bi bi-<?= $icon ?>"></i> <?= e($label) ?></a>
                    <?php endforeach; ?>
                    <form method="post" action="<?= e(url('logout')) ?>"><?= csrf_field() ?><button type="submit" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logi välja</button></form>
                </div>
            </aside>
            <main class="col-lg-9"><?= $content ?></main>
        </div>
    </div>
<?php elseif (($sidebar ?? '') === 'employer'): ?>
    <div class="container py-4">
        <div class="row g-4">
            <aside class="col-lg-3"><?php require __DIR__ . '/employer/_sidebar.php'; ?></aside>
            <main class="col-lg-9"><?= $content ?></main>
        </div>
    </div>
<?php else: ?>
    <?= $content ?>
<?php endif;
require __DIR__ . '/_foot.php';
