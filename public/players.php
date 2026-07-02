<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AtmDashboard\Database;

$config = require __DIR__ . '/../config.php';
$db = new Database($config['database']);
$pdo = $db->pdo();

$totalQuests = (int) $pdo->query('SELECT COUNT(*) FROM quests')->fetchColumn();

$tierConfig = [
  'haven' => ['label' => 'Haven', 'color' => '#a6e3a1', 'short' => 'H'],
  'frontier' => ['label' => 'Frontier', 'color' => '#fab387', 'short' => 'F'],
  'ascent' => ['label' => 'Ascent', 'color' => '#89b4fa', 'short' => 'A'],
  'sanctuary' => ['label' => 'Sanctuary', 'color' => '#cba6f7', 'short' => 'S'],
  'pinnacle' => ['label' => 'Pinnacle', 'color' => '#f9e2af', 'short' => 'P'],
  'dungeon' => ['label' => 'Dungeon', 'color' => '#f38ba8', 'short' => 'D'],
];

$players = $pdo->query(
    'SELECT
        p.uuid,
        p.name,
        p.world_tier,
        (SELECT MAX(playtime) FROM player_snapshots WHERE uuid = p.uuid) AS max_playtime,
        (SELECT MAX(deaths) FROM player_snapshots WHERE uuid = p.uuid) AS max_deaths,
        (SELECT MAX(distance_walked + distance_flown + distance_swum) FROM player_snapshots WHERE uuid = p.uuid) AS max_distance,
        (SELECT MAX(mobs_killed) FROM player_snapshots WHERE uuid = p.uuid) AS max_mobs_killed,
        (SELECT MAX(lootr_looted) FROM player_snapshots WHERE uuid = p.uuid) AS max_lootr_looted
     FROM players p
     ORDER BY max_playtime DESC'
)->fetchAll(PDO::FETCH_ASSOC);

$questCounts = [];
$r = $pdo->query(
    'SELECT uuid, COUNT(*) AS cnt FROM quest_progress WHERE completed_at IS NOT NULL GROUP BY uuid'
)->fetchAll(PDO::FETCH_ASSOC);
foreach ($r as $row) {
    $questCounts[$row['uuid']] = (int) $row['cnt'];
}

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
    $uuid = $pdo->query($sql)->fetchColumn();
    if ($uuid) {
        $playerAwards[$uuid][] = $awardDefs[$key];
    }
}

function formatTicks(int $ticks): string
{
    $seconds = intdiv($ticks, 20);
    $days = intdiv($seconds, 86400);
    $hours = intdiv($seconds % 86400, 3600);
    $minutes = intdiv($seconds % 3600, 60);
    if ($days > 0) return "{$days}d {$hours}h";
    if ($hours > 0) return "{$hours}h {$minutes}m";
    return "{$minutes}m";
}

function formatCm(int $cm): string
{
    $meters = $cm / 100;
    if ($meters >= 1000) {
        return number_format($meters / 1000, 1) . ' km';
    }
    return number_format($meters, 0) . ' m';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php $headTitle = 'Players - Glip Glops ATM10'; require __DIR__ . '/partials/head.php'; ?>
<body>
  <?php $currentPage = 'players'; require __DIR__ . '/partials/nav.php'; ?>

  <div class="container mx-auto px-4 pt-20 pb-8">
    <h1 class="text-3xl font-bold mb-6">Players <span class="font-normal text-base-content/40">(<?= count($players) ?>)</span></h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
      <?php foreach ($players as $player):
        $completed = $questCounts[$player['uuid']] ?? 0;
        $pct = $totalQuests > 0 ? round($completed / $totalQuests * 100) : 0;
      ?>
      <a href="player.php?uuid=<?= htmlspecialchars($player['uuid']) ?>" class="card bg-base-200 border border-base-300 overflow-visible transition-all duration-200 hover:top-[-0.125rem] cursor-pointer relative rounded-b-2xl rounded-t-none<?php if ($player['world_tier'] && isset($tierConfig[$player['world_tier']])): ?> border-t-4" style="border-top-color: <?= $tierConfig[$player['world_tier']]['color'] ?><?php endif; ?>">
        <?php if ($player['world_tier'] && isset($tierConfig[$player['world_tier']])): $t = $tierConfig[$player['world_tier']]; ?>
        <div class="absolute -top-2 right-2 tooltip tooltip-left" data-tip="Apotheosis: <?= htmlspecialchars($t['label']) ?> World Tier">
          <span class="text-xs font-bold px-1.5 py-0.5 leading-none inline-block" style="background: <?= $t['color'] ?>; color: #1e1e2e;"><?= htmlspecialchars($t['short']) ?></span>
        </div>
        <?php endif; ?>
        <div class="card-body p-4 gap-3">
          <div class="flex items-center gap-3">
            <img src="/head-cache.php?uuid=<?= htmlspecialchars($player['uuid']) ?>" alt="" class="w-10 h-10 shrink-0 bg-base-300">
            <div class="flex flex-col min-w-0">
              <span class="font-bold truncate"><?= htmlspecialchars($player['name']) ?></span>
              <?php $awards = $playerAwards[$player['uuid']] ?? []; ?>
              <?php if ($awards): $visible = array_slice($awards, 0, 2); $extra = array_slice($awards, 2); ?>
              <div class="flex flex-wrap gap-1 mt-0.5">
                <?php foreach ($visible as $award): ?>
                <span class="tooltip tooltip-top badge badge-xs font-medium rounded-none" data-tip="<?= htmlspecialchars($award['desc']) ?>" style="border-left: 3px solid <?= htmlspecialchars($award['color']) ?>"><?= htmlspecialchars($award['title']) ?></span>
                <?php endforeach; ?>
                <?php if ($extra): ?>
                <span class="tooltip tooltip-top text-[0.625rem] leading-none font-medium px-1 py-0.5" data-tip="<?= htmlspecialchars(implode(', ', array_column($extra, 'title'))) ?>">+<?= count($extra) ?></span>
                <?php endif; ?>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <div>
            <div class="flex justify-between text-xs mb-1">
              <span class="text-base-content/40">Quests</span>
              <span class="font-semibold"><?= $completed ?>/<?= $totalQuests ?></span>
            </div>
            <progress class="progress progress-primary w-full" value="<?= $completed ?>" max="<?= $totalQuests ?>"></progress>
          </div>

          <div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-sm">
            <div>
              <div class="text-xs text-base-content/40">Playtime</div>
              <div class="font-semibold"><?= formatTicks((int)$player['max_playtime']) ?></div>
            </div>
            <div>
              <div class="text-xs text-base-content/40">Deaths</div>
              <div class="font-semibold"><?= number_format((int)$player['max_deaths']) ?></div>
            </div>
            <div>
              <div class="text-xs text-base-content/40">Distance</div>
              <div class="font-semibold"><?= formatCm((int)$player['max_distance']) ?></div>
            </div>
            <div>
              <div class="text-xs text-base-content/40">Mobs Killed</div>
              <div class="font-semibold"><?= number_format((int)$player['max_mobs_killed']) ?></div>
            </div>
            <div>
              <div class="text-xs text-base-content/40">Lootr Crates</div>
              <div class="font-semibold"><?= number_format((int)$player['max_lootr_looted']) ?></div>
            </div>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
