<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AtmDashboard\Database;

$config = require __DIR__ . '/../config.php';
$db = new Database($config['database']);
$pdo = $db->pdo();

$bosses = [
  'minecraft:wither' => 'Wither',
  'minecraft:ender_dragon' => 'Ender Dragon',
  'minecraft:warden' => 'Warden',
  'minecraft:elder_guardian' => 'Elder Guardian',
  'minecraft:ravager' => 'Ravager',

  'cataclysm:ignis' => 'Ignis',
  'cataclysm:netherite_monstrosity' => 'Netherite Monstrosity',
  'cataclysm:the_leviathan' => 'The Leviathan',
  'cataclysm:the_harbinger' => 'The Harbinger',
  'cataclysm:ender_golem' => 'Ender Golem',
  'cataclysm:ender_guardian' => 'Ender Guardian',
  'cataclysm:ancient_remnant' => 'Ancient Remnant',

  'iceandfire:fire_dragon' => 'Fire Dragon',
  'iceandfire:ice_dragon' => 'Ice Dragon',
  'iceandfire:lightning_dragon' => 'Lightning Dragon',
  'iceandfire:sea_serpent' => 'Sea Serpent',

  'twilightforest:naga' => 'Naga',
  'twilightforest:lich' => 'Lich',
  'twilightforest:minoshroom' => 'Minoshroom',
  'twilightforest:hydra' => 'Hydra',
  'twilightforest:knight_phantom' => 'Knight Phantom',
  'twilightforest:ur_ghast' => 'Ur-Ghast',
  'twilightforest:alpha_yeti' => 'Alpha Yeti',
  'twilightforest:snow_queen' => 'Snow Queen',

  'ars_nouveau:wilden_boss' => 'Wilden Chimera',
  'deeperdarker:stalker' => 'Stalker',
  'deeperdarker:warden_helmet' => 'Sculk Warden',
];

$rows = $pdo->query(
  'SELECT p.uuid, p.name, ps.mob_kills
  FROM players p
  JOIN player_snapshots ps ON ps.uuid = p.uuid
  WHERE ps.collected_at = (SELECT MAX(collected_at) FROM player_snapshots WHERE uuid = p.uuid)'
)->fetchAll(PDO::FETCH_ASSOC);

$bossKills = [];
foreach ($rows as $row) {
  $kills = json_decode($row['mob_kills'], true);
  if (!is_array($kills)) continue;
  foreach ($kills as $entity => $count) {
    if (!isset($bosses[$entity])) continue;
    $bossKills[$entity][$row['uuid']] = [
      'name' => $row['name'],
      'uuid' => $row['uuid'],
      'count' => ($bossKills[$entity][$row['uuid']]['count'] ?? 0) + (int)$count,
    ];
  }
}

uksort($bossKills, function ($a, $b) use ($bossKills) {
  $totalA = array_sum(array_column($bossKills[$a], 'count'));
  $totalB = array_sum(array_column($bossKills[$b], 'count'));
  return $totalB <=> $totalA;
});

function totalBossKills(array $killedBy): int {
  return array_sum(array_column($killedBy, 'count'));
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php $headTitle = 'Boss Kills - Glip Glops ATM10'; require __DIR__ . '/partials/head.php'; ?>
<body>
  <?php $currentPage = 'bosses'; require __DIR__ . '/partials/nav.php'; ?>

  <section class="hero bg-cover bg-center" style="background-image: url(images/hero.webp);">
    <div class="hero-overlay bg-base-300/10 backdrop-blur-xs"></div>
    <div class="hero-content z-10 pt-24 w-full flex-col">
      <div class="max-w-5xl mx-auto px-4 w-full">

        <div class="text-center mb-8">
          <h1 class="text-3xl md:text-5xl font-extrabold">
            <span class="text-[#f38ba8]">Boss</span>
            <span class="text-base-content">Kills</span>
          </h1>
          <p class="text-sm text-base-content/60 mt-1">Who slayed what — total tracked boss kills server-wide</p>
        </div>

        <?php if (empty($bossKills)): ?>
        <div class="text-center py-16">
          <p class="text-lg text-base-content/40">No boss kill data yet. Run the collector first.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <?php foreach ($bossKills as $entity => $killedBy): ?>
          <?php
            $total = totalBossKills($killedBy);
            usort($killedBy, fn($a, $b) => $b['count'] <=> $a['count']);
          ?>
          <div class="bg-base-200/50 backdrop-blur-sm rounded-lg">
            <div class="px-4 py-3 border-b border-base-300/50">
              <div class="text-lg font-bold text-base-content"><?= htmlspecialchars($bosses[$entity]) ?></div>
              <div class="text-xs text-base-content/60">
                <span class="text-[#f38ba8] font-semibold"><?= number_format($total) ?></span> total kills
                <span class="ml-2 text-base-content/40"><?= count($killedBy) ?> player<?= count($killedBy) !== 1 ? 's' : '' ?></span>
              </div>
            </div>
            <div class="px-4 py-2 space-y-1.5">
              <?php foreach ($killedBy as $kb): ?>
              <div class="flex items-center gap-2 py-0.5">
                <div class="avatar">
                  <div class="w-6 rounded-full">
                    <img src="/head-cache.php?uuid=<?= htmlspecialchars($kb['uuid']) ?>" alt="" loading="lazy" />
                  </div>
                </div>
                <a href="player.php?uuid=<?= htmlspecialchars($kb['uuid']) ?>" class="link link-hover text-sm text-base-content truncate"><?= htmlspecialchars($kb['name']) ?></a>
                <span class="ml-auto font-semibold text-sm text-primary"><?= number_format((int) $kb['count']) ?></span>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <footer class="footer footer-center py-4 text-xs bg-base-300 text-base-content/40 border-t border-base-300">
    <p>Glip Glops ATM10</p>
  </footer>
</body>
</html>
