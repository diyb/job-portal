<?php require __DIR__ . '/../_head.php'; ?>
<div class="container py-4">
    <div class="row g-4">
        <aside class="col-lg-3"><?php require __DIR__ . '/_sidebar.php'; ?></aside>
        <main class="col-lg-9"><?= $content ?></main>
    </div>
</div>
<?php require __DIR__ . '/../_foot.php';
