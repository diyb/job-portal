<?php $sidebar = match ($cu['role'] ?? '') { 'user' => 'account', 'employer' => 'employer', default => '' }; ?>
<?php if ($sidebar === ''): ?><div class="container py-4"><?php endif; ?>
<a href="<?= e(url('messages')) ?>" class="small"><i class="bi bi-arrow-left"></i> Kõik vestlused</a>
<h1 class="h4 fw-bold my-3">Vestlus: <?= e($other['name']) ?></h1>
<div class="panel p-3 chat">
    <?php foreach ($msgs as $m): $mine = (int)$m['sender_id'] === (int)$me['id']; ?>
        <div class="bubble-row <?= $mine ? 'mine' : '' ?>"><div class="bubble"><?= nl2br(e($m['body'])) ?><div class="time"><?= e(date('d.m H:i', strtotime($m['created_at']))) ?></div></div></div>
    <?php endforeach; ?>
    <?php if (!$msgs): ?><p class="text-muted mb-0">Alusta vestlust allpool.</p><?php endif; ?>
</div>
<form method="post" action="<?= e(url('message_send')) ?>" class="d-flex gap-2 mt-3"><?= csrf_field() ?>
    <input type="hidden" name="to" value="<?= (int)$other['id'] ?>">
    <textarea name="body" rows="2" class="form-control" required maxlength="3000" placeholder="Kirjuta sõnum..."></textarea>
    <button class="btn btn-primary px-4"><i class="bi bi-send"></i></button>
</form>
<?php if ($sidebar === ''): ?></div><?php endif; ?>
