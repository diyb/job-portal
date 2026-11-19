<?php
$blocks = ['category' => ['Töövaldkonnad (kategooriad)', $categories], 'location' => ['Asukohad', $locations]];
?>
<div class="row g-4">
<?php foreach ($blocks as $type => [$heading, $rows]): ?>
    <div class="col-lg-6"><div class="panel p-3">
        <h2 class="h6 fw-bold"><?= e($heading) ?></h2>
        <form method="post" action="<?= e(url('category_save')) ?>" class="d-flex gap-2 mb-3"><?= csrf_field() ?>
            <input type="hidden" name="type" value="<?= $type ?>">
            <input name="name" class="form-control" placeholder="Uus nimi" required maxlength="100"><button class="btn btn-primary">Lisa</button></form>
        <?php foreach ($rows as $r): ?>
            <div class="d-flex gap-2 align-items-center border-top py-2">
                <form method="post" action="<?= e(url('category_save')) ?>" class="d-flex gap-2 flex-grow-1"><?= csrf_field() ?>
                    <input type="hidden" name="type" value="<?= $type ?>"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <input name="name" class="form-control form-control-sm" value="<?= e($r['name']) ?>" required maxlength="100">
                    <button class="btn btn-sm btn-outline-primary" title="Salvesta"><i class="bi bi-check2"></i></button></form>
                <span class="badge text-bg-light" title="Kasutusi kuulutustes"><?= (int)$r['uses'] ?></span>
                <form method="post" action="<?= e(url('category_delete')) ?>" onsubmit="return confirm('Kustutan? Kuulutustes jääb väli tühjaks.')"><?= csrf_field() ?>
                    <input type="hidden" name="type" value="<?= $type ?>"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
            </div>
        <?php endforeach; ?>
    </div></div>
<?php endforeach; ?>
</div>
