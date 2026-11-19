<?php
// Tööandja külgmenüü – kasutatakse nii tööandja paneelis kui ka sõnumite/teavituste lehtedel
$items = [
    ['employer', 'speedometer2', 'Dashboard'],
    ['employer_jobs', 'briefcase', 'Töökuulutused'],
    ['employer_job_form', 'plus-circle', 'Lisa kuulutus'],
    ['employer_company', 'building', 'Ettevõtte profiil'],
    ['employer_stats', 'bar-chart', 'Statistika'],
    ['messages', 'chat-dots', 'Sõnumid'],
    ['notifications', 'bell', 'Teavitused'],
];
$active = $_GET['page'] ?? 'employer';
if ($active === 'employer_candidates') $active = 'employer_jobs';
if ($active === 'thread') $active = 'messages';
?>
<div class="side-nav side-dark">
    <div class="side-title">Ettevõtte paneel</div>
    <?php foreach ($items as [$p, $icon, $label]): ?>
        <a href="<?= e(url($p)) ?>" class="<?= $active === $p ? 'active' : '' ?>"><i class="bi bi-<?= $icon ?>"></i> <?= e($label) ?></a>
    <?php endforeach; ?>
    <form method="post" action="<?= e(url('logout')) ?>"><?= csrf_field() ?><button type="submit" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logi välja</button></form>
</div>
