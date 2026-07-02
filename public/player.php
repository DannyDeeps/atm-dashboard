<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AtmDashboard\Database;

$config = require __DIR__ . '/../config.php';
$db = new Database($config['database']);
$pdo = $db->pdo();

$uuid = Database::normalizeUuid($_GET['uuid'] ?? '');
if ($uuid === '') {
  header('Location: players.php');
  exit;
}

$tierConfig = [
  'haven' => ['label' => 'Haven', 'color' => '#a6e3a1', 'short' => 'H'],
  'frontier' => ['label' => 'Frontier', 'color' => '#fab387', 'short' => 'F'],
  'ascent' => ['label' => 'Ascent', 'color' => '#89b4fa', 'short' => 'A'],
  'sanctuary' => ['label' => 'Sanctuary', 'color' => '#cba6f7', 'short' => 'S'],
  'pinnacle' => ['label' => 'Pinnacle', 'color' => '#f9e2af', 'short' => 'P'],
  'dungeon' => ['label' => 'Dungeon', 'color' => '#f38ba8', 'short' => 'D'],
];

$player = $pdo->prepare('SELECT * FROM players WHERE uuid = ?');
$player->execute([$uuid]);
$player = $player->fetch(PDO::FETCH_ASSOC);

if (!$player) {
  header('Location: players.php');
  exit;
}

$snapshot = $pdo->prepare(
  'SELECT * FROM player_snapshots WHERE uuid = ? ORDER BY collected_at DESC LIMIT 1'
);
$snapshot->execute([$uuid]);
$snapshot = $snapshot->fetch(PDO::FETCH_ASSOC);

$maxLife = $pdo->prepare('SELECT MAX(time_since_death) FROM player_snapshots WHERE uuid = ?');
$maxLife->execute([$uuid]);
$maxLife = (int) $maxLife->fetchColumn();

$awardDefs = [
    'playtime' => ['title' => 'The No-Lifer', 'color' => '#cba6f7', 'desc' => 'Most playtime online'],
    'deaths' => ['title' => 'Fragile', 'color' => '#f38ba8', 'desc' => 'Most deaths'],
    'distance' => ['title' => 'Trailblazer', 'color' => '#89b4fa', 'desc' => 'Furthest distance traveled'],
    'mobs_killed' => ['title' => 'Mob Masher', 'color' => '#f5c2e7', 'desc' => 'Most mobs killed'],
    'blocks_mined' => ['title' => 'Strip Miner', 'color' => '#fab387', 'desc' => 'Most blocks mined'],
    'blocks_placed' => ['title' => 'Master Builder', 'color' => '#a6e3a1', 'desc' => 'Most blocks placed'],
    'items_crafted' => ['title' => 'One-man Factory', 'color' => '#89dceb', 'desc' => 'Most items crafted'],
    'time_since_death' => ['title' => 'Immortal', 'color' => '#94e2d5', 'desc' => 'Longest life without dying'],
    'lootr_looted' => ['title' => 'Loot Goblin', 'color' => '#f9e2af', 'desc' => 'Most Lootr crates looted'],
];
$awardFields = [
    'playtime' => 'playtime',
    'deaths' => 'deaths',
    'distance' => '(distance_walked + distance_flown + distance_swum)',
    'mobs_killed' => 'mobs_killed',
    'blocks_mined' => 'blocks_mined',
    'blocks_placed' => 'blocks_placed',
    'items_crafted' => 'items_crafted',
    'time_since_death' => 'time_since_death',
    'lootr_looted' => 'lootr_looted',
];
$playerAwards = [];
foreach ($awardFields as $key => $field) {
    if ($key === 'time_since_death') {
        $sql = "SELECT ps.uuid FROM player_snapshots ps GROUP BY ps.uuid ORDER BY MAX(ps.time_since_death) DESC LIMIT 1";
    } else {
        $sql = "SELECT ps.uuid FROM player_snapshots ps
                WHERE ps.collected_at = (SELECT MAX(collected_at) FROM player_snapshots WHERE uuid = ps.uuid)
                ORDER BY {$field} DESC LIMIT 1";
    }
    $topUuid = $pdo->query($sql)->fetchColumn();
    if ($topUuid && $topUuid === $uuid) {
        $playerAwards[] = $awardDefs[$key];
    }
}

function formatTicks(int $ticks): string {
  $seconds = intdiv($ticks, 20);
  $days = intdiv($seconds, 86400);
  $hours = intdiv($seconds % 86400, 3600);
  $minutes = intdiv($seconds % 3600, 60);
  if ($days > 0) return "{$days}d {$hours}h";
  if ($hours > 0) return "{$hours}h {$minutes}m";
  return "{$minutes}m";
}

function formatCm(int $cm): string {
  $meters = $cm / 100;
  if ($meters >= 1000) {
    return number_format($meters / 1000, 1) . ' km';
  }
  return number_format($meters, 0) . ' m';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php $headTitle = htmlspecialchars($player['name']) . ' - ATM10 Dashboard'; require __DIR__ . '/partials/head.php'; ?>
<body>
  <?php $currentPage = 'player'; require __DIR__ . '/partials/nav.php'; ?>

  <div class="container mx-auto px-4 pt-20 pb-8">
    <h1 class="text-3xl font-bold mb-1">
      <?= htmlspecialchars($player['name']) ?>
      <?php if ($player['world_tier'] && isset($tierConfig[$player['world_tier']])): $t = $tierConfig[$player['world_tier']]; ?>
      <span class="tooltip" data-tip="Apotheosis: <?= htmlspecialchars($t['label']) ?> World Tier">
        <span class="text-lg font-bold px-2 py-0.5 align-middle inline-block" style="background: <?= $t['color'] ?>; color: #1e1e2e;"><?= htmlspecialchars($t['label']) ?></span>
      </span>
      <?php endif; ?>
    </h1>
    <?php if ($playerAwards): ?>
    <div class="flex flex-wrap gap-2 mb-2">
      <?php foreach ($playerAwards as $award): ?>
       <span class="badge font-bold tooltip rounded-none" data-tip="<?= htmlspecialchars($award['desc']) ?>" style="border-left: 3px solid <?= htmlspecialchars($award['color']) ?>"><?= htmlspecialchars($award['title']) ?></span>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php
    $totalQuests = $pdo->query('SELECT COUNT(*) FROM quests')->fetchColumn();
    $completedQuests = $pdo->prepare('SELECT COUNT(*) FROM quest_progress WHERE uuid = ? AND completed_at IS NOT NULL');
    $completedQuests->execute([$uuid]);
    $completedQuests = $completedQuests->fetchColumn();

    $startedQuests = $pdo->prepare('SELECT COUNT(*) FROM quest_progress WHERE uuid = ?');
    $startedQuests->execute([$uuid]);
    $startedQuests = $startedQuests->fetchColumn();
    ?>

    <div class="card bg-base-200 mb-8 rounded-b-2xl rounded-t-none">
      <div class="card-body">
        <h2 class="card-title">Quest Progress</h2>
        <div class="stats">
          <div class="stat">
            <div class="stat-title text-xs">Completed</div>
            <div class="stat-value text-lg"><?= number_format($completedQuests) ?></div>
          </div>
          <div class="stat">
            <div class="stat-title text-xs">Started</div>
            <div class="stat-value text-lg"><?= number_format($startedQuests) ?></div>
          </div>
          <div class="stat">
            <div class="stat-title text-xs">Overall</div>
            <div class="stat-value text-lg"><?= $totalQuests > 0 ? number_format($completedQuests / $totalQuests * 100, 1) : 0 ?>%</div>
          </div>
        </div>
        <progress class="progress progress-primary w-full" value="<?= $totalQuests > 0 ? (int)round($completedQuests / $totalQuests * 100) : 0 ?>" max="100"></progress>
      </div>
    </div>

    <?php if ($snapshot): ?>
    <h2 class="text-xl font-bold mb-4">Stats</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mb-8">
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Playtime</div>
        <div class="stat-value text-lg"><?= formatTicks((int)$snapshot['playtime']) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Deaths</div>
        <div class="stat-value text-lg"><?= number_format((int)$snapshot['deaths']) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Distance</div>
        <div class="stat-value text-lg"><?= formatCm((int)($snapshot['distance_walked'] + $snapshot['distance_flown'] + $snapshot['distance_swum'])) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Mobs Killed</div>
        <div class="stat-value text-lg"><?= number_format((int)$snapshot['mobs_killed']) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Blocks Mined</div>
        <div class="stat-value text-lg"><?= number_format((int)$snapshot['blocks_mined']) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Blocks Placed</div>
        <div class="stat-value text-lg"><?= number_format((int)$snapshot['blocks_placed']) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Items Crafted</div>
        <div class="stat-value text-lg"><?= number_format((int)$snapshot['items_crafted']) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Damage Dealt</div>
        <div class="stat-value text-lg"><?= number_format((int)$snapshot['damage_dealt']) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Damage Taken</div>
        <div class="stat-value text-lg"><?= number_format((int)$snapshot['damage_taken']) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Jumps</div>
        <div class="stat-value text-lg"><?= number_format((int)$snapshot['jumps']) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Current Life</div>
        <div class="stat-value text-lg"><?= formatTicks((int)$snapshot['time_since_death']) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Best Life</div>
        <div class="stat-value text-lg text-primary"><?= formatTicks($maxLife) ?></div>
      </div>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs">Lootr Crates</div>
        <div class="stat-value text-lg"><?= number_format((int)$snapshot['lootr_looted']) ?></div>
      </div>
    </div>
    <?php endif; ?>

  </div>
</body>
</html>
