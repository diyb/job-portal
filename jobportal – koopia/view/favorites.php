<?php $sidebar = 'account'; ?>
<h1 class="h3 fw-bold mb-4">Lemmikud</h1>
<?php foreach ($jobs as $job) include __DIR__ . '/_jobcard.php'; ?>
<?php if (!$jobs): ?><div class="empty-box">Lemmikuid pole. Vajuta kuulutusel südamele, et see siia salvestada.</div><?php endif; ?>
