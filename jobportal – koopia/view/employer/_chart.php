<?php
// Lihtne SVG joongraafik. Vajab muutujat $chart = [['label'=>..., 'n'=>...], ...]
$W = 560; $H = 190; $padL = 28; $padB = 24; $padT = 12;
$max = max(1, ...array_column($chart, 'n'));
$cnt = count($chart);
$pts = [];
foreach ($chart as $i => $p) {
    $x = $padL + ($cnt > 1 ? $i * (($W - $padL - 10) / ($cnt - 1)) : 0);
    $y = $padT + ($H - $padT - $padB) * (1 - $p['n'] / $max);
    $pts[] = [round($x, 1), round($y, 1), $p];
}
$line = implode(' ', array_map(fn($p) => $p[0] . ',' . $p[1], $pts));
?>
<svg viewBox="0 0 <?= $W ?> <?= $H ?>" class="w-100" role="img" aria-label="Kandideerimised päevade lõikes">
    <?php for ($g = 0; $g <= 3; $g++):
        $gy = $padT + ($H - $padT - $padB) * ($g / 3); ?>
        <line x1="<?= $padL ?>" x2="<?= $W - 10 ?>" y1="<?= $gy ?>" y2="<?= $gy ?>" stroke="#e5e9f2"/>
        <text x="0" y="<?= $gy + 4 ?>" font-size="10" fill="#8a94a8"><?= round($max * (1 - $g / 3)) ?></text>
    <?php endfor; ?>
    <polyline points="<?= e($line) ?>" fill="none" stroke="#1f6bff" stroke-width="2.5" stroke-linejoin="round"/>
    <?php foreach ($pts as $i => [$x, $y, $p]): ?>
        <circle cx="<?= $x ?>" cy="<?= $y ?>" r="4" fill="#1f6bff"><title><?= e($p['label'] . ': ' . $p['n']) ?></title></circle>
        <?php if ($cnt <= 8 || $i % 2 === 0): ?><text x="<?= $x ?>" y="<?= $H - 6 ?>" font-size="10" text-anchor="middle" fill="#8a94a8"><?= e($p['label']) ?></text><?php endif; ?>
    <?php endforeach; ?>
</svg>
