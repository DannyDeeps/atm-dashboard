<?php

namespace AtmCollector;

class Database
{
  private \PDO $pdo;

  public function __construct(array $dbConfig)
  {
    $driver = $dbConfig['driver'] ?? 'pgsql';
    $dsn = sprintf(
      'pgsql:host=%s;port=%s;dbname=%s',
      $dbConfig['host'] ?? '127.0.0.1',
      $dbConfig['port'] ?? 5432,
      $dbConfig['dbname'] ?? 'atm_dashboard'
    );

    $this->pdo = new \PDO($dsn, $dbConfig['user'] ?? null, $dbConfig['password'] ?? null);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    $this->pdo->exec('SET search_path TO public');
  }

  public function pdo(): \PDO
  {
    return $this->pdo;
  }

  public static function normalizeUuid(string $uuid): string
  {
    return str_replace('-', '', strtolower($uuid));
  }

  public function upsertPlayer(string $uuid, string $name): void
  {
    $stmt = $this->pdo->prepare(
      'INSERT INTO players (uuid, name, last_seen)
      VALUES (:uuid, :name, CURRENT_TIMESTAMP)
      ON CONFLICT(uuid) DO UPDATE SET
        name = excluded.name,
        last_seen = CURRENT_TIMESTAMP'
    );
    $stmt->execute(['uuid' => $uuid, 'name' => $name]);
  }

  public function updatePlayerWorldTier(string $uuid, string $worldTier): void
  {
    $stmt = $this->pdo->prepare(
      'UPDATE players SET world_tier = :world_tier WHERE uuid = :uuid'
    );
    $stmt->execute(['uuid' => $uuid, 'world_tier' => $worldTier]);
  }

  public function insertSnapshot(string $uuid, array $stats): void
  {
    $stmt = $this->pdo->prepare(
      'INSERT INTO player_snapshots (
        uuid, playtime, deaths, distance_walked, distance_flown,
        distance_swum, distance_horseback, blocks_mined, blocks_placed,
        mobs_killed, players_killed, items_crafted, items_used,
        damage_dealt, damage_taken, jumps, falls,
        time_since_death, time_since_rest,
        trades, anvil_uses, chests_opened, records_played, bell_rings,
        lootr_looted, mob_kills
      ) VALUES (
        :uuid, :playtime, :deaths, :distance_walked, :distance_flown,
        :distance_swum, :distance_horseback, :blocks_mined, :blocks_placed,
        :mobs_killed, :players_killed, :items_crafted, :items_used,
        :damage_dealt, :damage_taken, :jumps, :falls,
        :time_since_death, :time_since_rest,
        :trades, :anvil_uses, :chests_opened, :records_played, :bell_rings,
        :lootr_looted, :mob_kills
      )'
    );

    $stmt->execute([
      'uuid' => $uuid,
      'playtime' => $stats['playtime'] ?? 0,
      'deaths' => $stats['deaths'] ?? 0,
      'distance_walked' => $stats['distance_walked'] ?? 0,
      'distance_flown' => $stats['distance_flown'] ?? 0,
      'distance_swum' => $stats['distance_swum'] ?? 0,
      'distance_horseback' => $stats['distance_horseback'] ?? 0,
      'blocks_mined' => $stats['blocks_mined'] ?? 0,
      'blocks_placed' => $stats['blocks_placed'] ?? 0,
      'mobs_killed' => $stats['mobs_killed'] ?? 0,
      'players_killed' => $stats['players_killed'] ?? 0,
      'items_crafted' => $stats['items_crafted'] ?? 0,
      'items_used' => $stats['items_used'] ?? 0,
      'damage_dealt' => $stats['damage_dealt'] ?? 0,
      'damage_taken' => $stats['damage_taken'] ?? 0,
      'jumps' => $stats['jumps'] ?? 0,
      'falls' => $stats['falls'] ?? 0,
      'time_since_death' => $stats['time_since_death'] ?? 0,
      'time_since_rest' => $stats['time_since_rest'] ?? 0,
      'trades' => $stats['trades'] ?? 0,
      'anvil_uses' => $stats['anvil_uses'] ?? 0,
      'chests_opened' => $stats['chests_opened'] ?? 0,
      'records_played' => $stats['records_played'] ?? 0,
      'bell_rings' => $stats['bell_rings'] ?? 0,
      'lootr_looted' => $stats['lootr_looted'] ?? 0,
      'mob_kills' => $stats['mob_kills'] ?? '{}',
    ]);
  }

  public function insertChapterGroup(string $id, ?string $title): void
  {
    $stmt = $this->pdo->prepare(
      'INSERT INTO quest_chapter_groups (id, title)
      VALUES (:id, :title)
      ON CONFLICT(id) DO UPDATE SET title = excluded.title'
    );
    $stmt->execute(['id' => $id, 'title' => $title]);
  }

  public function insertChapter(string $id, string $title, ?string $groupId, int $orderIndex, string $filename): void
  {
    $stmt = $this->pdo->prepare(
      'INSERT INTO quest_chapters (id, title, group_id, order_index, filename)
      VALUES (:id, :title, :group_id, :order_index, :filename)
      ON CONFLICT(id) DO UPDATE SET
        title = excluded.title,
        group_id = excluded.group_id,
        order_index = excluded.order_index,
        filename = excluded.filename'
    );
    $stmt->execute([
      'id' => $id,
      'title' => $title,
      'group_id' => $groupId,
      'order_index' => $orderIndex,
      'filename' => $filename,
    ]);
  }

  public function insertQuest(string $id, string $chapterId, ?string $title, ?string $subtitle, ?string $description, float $x, float $y, array $dependencies, bool $optional, float $size, string $shape, int $minWidth): void
  {
    $stmt = $this->pdo->prepare(
      'INSERT INTO quests (id, chapter_id, title, subtitle, description, x, y, dependencies, optional, size, shape, min_width)
      VALUES (:id, :chapter_id, :title, :subtitle, :description, :x, :y, :dependencies, :optional, :size, :shape, :min_width)
      ON CONFLICT(id) DO UPDATE SET
        chapter_id = excluded.chapter_id,
        title = excluded.title,
        subtitle = excluded.subtitle,
        description = excluded.description,
        x = excluded.x,
        y = excluded.y,
        dependencies = excluded.dependencies,
        optional = excluded.optional,
        size = excluded.size,
        shape = excluded.shape,
        min_width = excluded.min_width'
    );
    $stmt->execute([
      'id' => $id,
      'chapter_id' => $chapterId,
      'title' => $title,
      'subtitle' => $subtitle,
      'description' => $description,
      'x' => $x,
      'y' => $y,
      'dependencies' => json_encode($dependencies),
      'optional' => $optional ? 1 : 0,
      'size' => $size,
      'shape' => $shape,
      'min_width' => $minWidth,
    ]);
  }

  public function upsertQuestProgress(string $uuid, string $questId, ?int $completedAt, ?int $startedAt): void
  {
    $completedDt = $completedAt ? date('Y-m-d H:i:s', intdiv($completedAt, 1000)) : null;
    $startedDt = $startedAt ? date('Y-m-d H:i:s', intdiv($startedAt, 1000)) : null;

    $stmt = $this->pdo->prepare(
      'INSERT INTO quest_progress (uuid, quest_id, completed_at, started_at)
      VALUES (:uuid, :quest_id, :completed_at, :started_at)
      ON CONFLICT(uuid, quest_id) DO UPDATE SET
        completed_at = COALESCE(:completed_at2, quest_progress.completed_at),
        started_at = COALESCE(:started_at2, quest_progress.started_at)'
    );
    $stmt->execute([
      'uuid' => $uuid,
      'quest_id' => $questId,
      'completed_at' => $completedDt,
      'started_at' => $startedDt,
      'completed_at2' => $completedDt,
      'started_at2' => $startedDt,
    ]);
  }

  public function clearQuestProgress(string $uuid): void
  {
    $stmt = $this->pdo->prepare('DELETE FROM quest_progress WHERE uuid = :uuid');
    $stmt->execute(['uuid' => $uuid]);
  }

  public function upsertWealth(string $uuid, array $data): void
  {
    $stmt = $this->pdo->prepare(
      'INSERT INTO player_wealth (uuid, diamonds, emeralds, allthemodium_ingots, vibranium_ingots, unobtainium_ingots, uru_ingots, atm_stars, wealth_score)
      VALUES (:uuid, :diamonds, :emeralds, :allthemodium_ingots, :vibranium_ingots, :unobtainium_ingots, :uru_ingots, :atm_stars, :wealth_score)
      ON CONFLICT(uuid) DO UPDATE SET
        diamonds = excluded.diamonds,
        emeralds = excluded.emeralds,
        allthemodium_ingots = excluded.allthemodium_ingots,
        vibranium_ingots = excluded.vibranium_ingots,
        unobtainium_ingots = excluded.unobtainium_ingots,
        uru_ingots = excluded.uru_ingots,
        atm_stars = excluded.atm_stars,
        wealth_score = excluded.wealth_score'
    );
    $stmt->execute([
      'uuid' => $uuid,
      'diamonds' => $data['diamonds'] ?? 0,
      'emeralds' => $data['emeralds'] ?? 0,
      'allthemodium_ingots' => $data['allthemodium_ingots'] ?? 0,
      'vibranium_ingots' => $data['vibranium_ingots'] ?? 0,
      'unobtainium_ingots' => $data['unobtainium_ingots'] ?? 0,
      'uru_ingots' => $data['uru_ingots'] ?? 0,
      'atm_stars' => $data['atm_stars'] ?? 0,
      'wealth_score' => $data['wealth_score'] ?? 0,
    ]);
  }
}
