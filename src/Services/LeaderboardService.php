<?php

namespace AtmDashboard\Services;

class LeaderboardService
{
    /** For cumulative stats (playtime, deaths, distance, etc.) — uses MAX() across all snapshots. */
    public static function query(\PDO $pdo, string $select, string $order): array
    {
        return $pdo->query(
            "SELECT p.uuid, p.name, {$select}
            FROM players p
            JOIN player_snapshots ps ON ps.uuid = p.uuid
            GROUP BY p.uuid, p.name
            ORDER BY {$order} DESC
            LIMIT 10"
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** For non-cumulative stats (e.g. time_since_death) — reads the latest snapshot per player. */
    public static function queryLatest(\PDO $pdo, string $select, string $order): array
    {
        return $pdo->query(
            "SELECT p.uuid, p.name, {$select}
            FROM players p
            JOIN player_snapshots ps ON ps.uuid = p.uuid
            WHERE ps.collected_at = (SELECT MAX(collected_at) FROM player_snapshots WHERE uuid = p.uuid)
            ORDER BY {$order} DESC
            LIMIT 10"
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function format(array $rows, string $key, callable $format): array
    {
        return array_map(fn($r) => [
            'uuid' => $r['uuid'],
            'name' => $r['name'],
            'value' => $format((int)$r[$key]),
        ], $rows);
    }
}
