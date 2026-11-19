<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Admin') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/style.css')) ?>" rel="stylesheet">
    <link href="public/css/admin.css" rel="stylesheet">
</head>
<body class="admin-login">
<div class="container py-5">
    <?php foreach (flashes() as $m): ?>
        <div class="alert alert-<?= e($m['type']) ?> mx-auto" style="max-width:440px"><?= e($m['msg']) ?></div>
    <?php endforeach; ?>
    <?= $content ?>
</div>
</body>
</html>
