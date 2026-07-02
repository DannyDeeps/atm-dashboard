#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AtmCollector\Collectors\PlayerDataCollector;
use AtmCollector\Collectors\QuestCollector;
use AtmCollector\Collectors\StatsCollector;
use AtmCollector\Collectors\WealthCollector;
use AtmCollector\Database;

$config = require __DIR__ . '/../config.php';

echo "[" . date('Y-m-d H:i:s') . "] Starting collection run\n";

$db = new Database($config['database']);

$totalStats = 0;
$totalQuests = ['chapters_found' => 0, 'quests_found' => 0, 'players_found' => 0];

foreach ($config['servers'] as $serverKey => $serverConfig) {
  echo "  Collecting from: {$serverConfig['display_name']}\n";

  $statsCollector = new StatsCollector($serverConfig['path'], $serverConfig['world_name'], $db);
  $count = $statsCollector->collect();
  echo "    Stats: {$count} players\n";
  $totalStats += $count;

  $questCollector = new QuestCollector($serverConfig['path'], $serverConfig['world_name'], $db);
  $questResult = $questCollector->collect();
  echo "    Quests: {$questResult['chapters_found']} chapters, {$questResult['quests_found']} quests, {$questResult['players_found']} players with progress\n";

  foreach (['chapters_found', 'quests_found', 'players_found'] as $k) {
    $totalQuests[$k] += $questResult[$k];
  }

  $playerDataCollector = new PlayerDataCollector($serverConfig['path'], $serverConfig['world_name'], $db);
  $tierResults = $playerDataCollector->collect();
  echo "    Player Data: " . count($tierResults) . " world tiers\n";

  $wealthCollector = new WealthCollector($serverConfig['path'], $serverConfig['world_name'], $db);
  $wealthCount = $wealthCollector->collect();
  echo "    Wealth: {$wealthCount} players\n";
}

echo "[" . date('Y-m-d H:i:s') . "] Done. Stats: {$totalStats} players. Quests: {$totalQuests['chapters_found']} chapters, {$totalQuests['quests_found']} quests, {$totalQuests['players_found']} players tracked.\n";
