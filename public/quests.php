<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AtmDashboard\Database;

$config = require __DIR__ . '/../config.php';
$db = new Database($config['database_config'] ?? $config['database']);
$pdo = $db->pdo();

$totalQuests = $pdo->query('SELECT COUNT(*) FROM quests')->fetchColumn();

$total = $totalQuests ?: 1;
$stmt = $pdo->prepare(
  'SELECT
    p.uuid, p.name,
    (SELECT COUNT(*) FROM quest_progress qp WHERE qp.uuid = p.uuid AND qp.completed_at IS NOT NULL) AS completed,
    CAST((SELECT COUNT(*) FROM quest_progress qp WHERE qp.uuid = p.uuid AND qp.completed_at IS NOT NULL) AS REAL) / CAST(:total AS REAL) * 100 AS completion_pct
  FROM players p
  WHERE (SELECT COUNT(*) FROM quest_progress qp WHERE qp.uuid = p.uuid) > 0
  ORDER BY completion_pct DESC'
);
$stmt->execute(['total' => $total]);
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quest Progress - ATM10 Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com">
  </script>
  <script src="https://unpkg.com/htmx.org@2.0.3"></script>
  <style>
    [data-theme="dark"] {
      --p: 0.87 0.31 140;
      --pc: 0 0 0;
      --rounded-box: 0;
      --rounded-btn: 0;
      --rounded-badge: 0;
    }
    .tooltip::after { z-index: 9999 !important; }
    .tooltip { z-index: 9999 !important; }
  </style>
</head>
<body>
  <div class="navbar bg-base-300/80 backdrop-blur border-b border-base-300 fixed top-0 z-50">
    <div class="flex-1">
      <a href="index.php" class="btn btn-ghost text-xl px-3 text-primary">Glip Glops</a>
    </div>
    <div class="flex-none">
      <ul class="menu menu-horizontal px-1 items-center">
        <li><a href="index.php">Home</a></li>
        <li><a href="players.php">Players</a></li>
        <li><a href="quests.php" class="font-bold">Quests</a></li>
        <li>
          <a href="https://discord.gg/ay8K5G8zCM" target="_blank" class="btn btn-sm ml-2 px-3 btn-primary font-bold">Discord</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="container mx-auto px-4 pt-20 pb-8">
    <h1 class="text-3xl font-bold mb-2">Quest Progress</h1>
    <p class="text-base-content/60 mb-8">FTB Quests tracking across all players</p>

    <div class="stats shadow mb-8">
      <div class="stat">
        <div class="stat-title">Total Quests</div>
        <div class="stat-value"><?= number_format($totalQuests) ?></div>
      </div>
      <div class="stat">
        <div class="stat-title">Players Tracked</div>
        <div class="stat-value"><?= count($players) ?></div>
      </div>
    </div>

    <h2 class="text-xl font-bold mb-4">Player Completion</h2>

    <div class="overflow-x-auto">
      <table class="table table-zebra table-hover">
        <thead>
          <tr>
            <th>Player</th>
            <th>Completed</th>
            <th>Completion</th>
            <th class="w-64">Progress</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($players as $player): ?>
          <tr>
            <td><a href="player.php?uuid=<?= htmlspecialchars($player['uuid']) ?>" class="link link-hover font-semibold"><?= htmlspecialchars($player['name']) ?></a></td>
            <td><?= number_format((int)$player['completed']) ?> / <?= number_format($totalQuests) ?></td>
            <td><?= number_format((float)$player['completion_pct'], 1) ?>%</td>
            <td>
              <progress class="progress progress-primary w-full" value="<?= (int)round((float)$player['completion_pct']) ?>" max="100"></progress>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>
</body>
</html>
