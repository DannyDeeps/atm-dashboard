#!/usr/bin/env php
<?php

/**
 * Migrate all data from SQLite → Postgres
 *
 * Usage: php bin/migrate.php
 *
 * Requires Postgres credentials configured in config.php under 'database_config'.
 * The Postgres schema is created automatically by the PgDatabase constructor.
 */

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config.php';

if (!isset($config['database_config'])) {
  echo "Error: 'database_config' not found in config.php. Add your Postgres connection details first.\n";
  exit(1);
}

echo "[" . date('Y-m-d H:i:s') . "] Migration start\n";

// ── Source: SQLite ──
echo "  Connecting to SQLite...\n";
$sqlite = new AtmDashboard\Database($config['database']);

// ── Destination: Postgres ──
echo "  Connecting to Postgres at {$config['database_config']['host']}:{$config['database_config']['port']}...\n";
try {
  $pg = new AtmDashboard\Database($config['database_config']);
} catch (\Throwable $e) {
  echo "  Error connecting to Postgres: " . $e->getMessage() . "\n";
  echo "  Fill in the correct credentials in config.php under 'database_config'.\n";
  exit(1);
}

$pgPdo = $pg->pdo();

// ── Helper ──
function migrateTable(string $label, \PDO $src, \PDO $dst, string $table, array $columns, ?string $orderBy = null): int
{
  $colList = implode(', ', $columns);
  $placeholders = implode(', ', array_map(fn($c) => ':' . $c, $columns));

  $sql = "SELECT {$colList} FROM {$table}";
  if ($orderBy) $sql .= " ORDER BY {$orderBy}";

  $rows = $src->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
  if (empty($rows)) {
    echo "    {$label}: 0 rows (empty)\n";
    return 0;
  }

  $count = 0;
  $dst->beginTransaction();
  try {
    $stmt = $dst->prepare("INSERT INTO {$table} ({$colList}) VALUES ({$placeholders}) ON CONFLICT DO NOTHING");
    foreach ($rows as $row) {
      $params = [];
      foreach ($columns as $col) {
        $params[$col] = $row[$col] ?? null;
      }
      $stmt->execute($params);
      $count++;
    }
    $dst->commit();
  } catch (\Throwable $e) {
    $dst->rollBack();
    throw $e;
  }

  echo "    {$label}: {$count} rows\n";
  return $count;
}

// ── Migrate tables in dependency order ──

try {
  $total = 0;

  // 1. players
  $total += migrateTable('players', $sqlite->pdo(), $pgPdo, 'players', [
    'uuid', 'name', 'first_seen', 'last_seen', 'world_tier',
  ], 'first_seen');

  // 2. player_snapshots
  $total += migrateTable('player_snapshots', $sqlite->pdo(), $pgPdo, 'player_snapshots', [
    'id', 'uuid', 'collected_at',
    'playtime', 'deaths', 'distance_walked', 'distance_flown', 'distance_swum', 'distance_horseback',
    'blocks_mined', 'blocks_placed',
    'mobs_killed', 'players_killed', 'items_crafted', 'items_used',
    'damage_dealt', 'damage_taken',
    'jumps', 'falls',
    'time_since_death', 'time_since_rest',
    'trades', 'anvil_uses', 'chests_opened', 'records_played', 'bell_rings',
    'lootr_looted', 'mob_kills',
  ], 'id');

  // 3. quest_chapter_groups
  $total += migrateTable('quest_chapter_groups', $sqlite->pdo(), $pgPdo, 'quest_chapter_groups', [
    'id', 'title',
  ], 'id');

  // 4. quest_chapters
  $total += migrateTable('quest_chapters', $sqlite->pdo(), $pgPdo, 'quest_chapters', [
    'id', 'title', 'group_id', 'order_index', 'filename',
  ], 'order_index');

  // 5. quests
  $total += migrateTable('quests', $sqlite->pdo(), $pgPdo, 'quests', [
    'id', 'chapter_id', 'title', 'subtitle', 'description',
    'x', 'y', 'dependencies', 'optional', 'size', 'shape', 'min_width',
  ], 'id');

  // 6. quest_progress
  $total += migrateTable('quest_progress', $sqlite->pdo(), $pgPdo, 'quest_progress', [
    'id', 'uuid', 'quest_id', 'completed_at', 'started_at',
  ], 'id');

  // 7. player_wealth
  $total += migrateTable('player_wealth', $sqlite->pdo(), $pgPdo, 'player_wealth', [
    'uuid', 'diamonds', 'emeralds', 'allthemodium_ingots', 'vibranium_ingots',
    'unobtainium_ingots', 'uru_ingots', 'atm_stars', 'wealth_score',
  ], 'uuid');

  // ── Reset Postgres sequences to match migrated IDs ──
  echo "  Resetting sequences...\n";
  $pgPdo->exec("SELECT setval('player_snapshots_id_seq', COALESCE((SELECT MAX(id) FROM player_snapshots), 1))");
  $pgPdo->exec("SELECT setval('quest_progress_id_seq', COALESCE((SELECT MAX(id) FROM quest_progress), 1))");

  echo "[" . date('Y-m-d H:i:s') . "] Done. {$total} total rows migrated.\n";

} catch (\Throwable $e) {
  echo "  Migration failed: " . $e->getMessage() . "\n";
  if (isset($pgPdo) && $pgPdo->inTransaction()) {
    $pgPdo->rollBack();
  }
  exit(1);
}
