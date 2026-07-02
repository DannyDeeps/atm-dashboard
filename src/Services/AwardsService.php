<?php

namespace AtmDashboard\Services;

class AwardsService
{
    public static function tierConfig(): array
    {
        return [
            'haven' => ['label' => 'Haven', 'color' => '#a6e3a1', 'short' => 'H'],
            'frontier' => ['label' => 'Frontier', 'color' => '#fab387', 'short' => 'F'],
            'ascent' => ['label' => 'Ascent', 'color' => '#89b4fa', 'short' => 'A'],
            'sanctuary' => ['label' => 'Sanctuary', 'color' => '#cba6f7', 'short' => 'S'],
            'pinnacle' => ['label' => 'Pinnacle', 'color' => '#f9e2af', 'short' => 'P'],
            'dungeon' => ['label' => 'Dungeon', 'color' => '#f38ba8', 'short' => 'D'],
        ];
    }

    public static function defs(): array
    {
        return [
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
    }

    public static function fields(): array
    {
        return [
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
    }

    public static function loadAll(\PDO $pdo): array
    {
        $defs = self::defs();

        // Get latest snapshot per player (except time_since_death which uses all-time max)
        $latest = $pdo->query(
            'SELECT s.uuid,
                    s.playtime, s.deaths, s.mobs_killed, s.blocks_mined,
                    s.blocks_placed, s.items_crafted, s.lootr_looted,
                    (s.distance_walked + s.distance_flown + s.distance_swum) AS distance
             FROM player_snapshots s
             JOIN (SELECT uuid, MAX(collected_at) AS max_at FROM player_snapshots GROUP BY uuid) latest
               ON s.uuid = latest.uuid AND s.collected_at = latest.max_at'
        )->fetchAll(\PDO::FETCH_ASSOC);

        // Get all-time max time_since_death per player
        $tsd = $pdo->query(
            'SELECT uuid, MAX(time_since_death) AS val FROM player_snapshots GROUP BY uuid'
        )->fetchAll(\PDO::FETCH_ASSOC);

        $fieldMap = [
            'playtime' => 'playtime',
            'deaths' => 'deaths',
            'distance' => 'distance',
            'mobs_killed' => 'mobs_killed',
            'blocks_mined' => 'blocks_mined',
            'blocks_placed' => 'blocks_placed',
            'items_crafted' => 'items_crafted',
            'lootr_looted' => 'lootr_looted',
        ];

        $awards = [];
        foreach ($fieldMap as $key => $col) {
            $best = null;
            $bestUuid = null;
            foreach ($latest as $row) {
                $val = (int) $row[$col];
                if ($best === null || $val > $best) {
                    $best = $val;
                    $bestUuid = $row['uuid'];
                }
            }
            if ($bestUuid) {
                $awards[$bestUuid][] = $defs[$key];
            }
        }

        // time_since_death
        $best = null;
        $bestUuid = null;
        foreach ($tsd as $row) {
            $val = (int) $row['val'];
            if ($best === null || $val > $best) {
                $best = $val;
                $bestUuid = $row['uuid'];
            }
        }
        if ($bestUuid) {
            $awards[$bestUuid][] = $defs['time_since_death'];
        }

        return $awards;
    }

    public static function loadForPlayer(\PDO $pdo, string $uuid): array
    {
        $all = self::loadAll($pdo);
        return $all[$uuid] ?? [];
    }
}
