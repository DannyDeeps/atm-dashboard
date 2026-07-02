<?php

namespace AtmCollector\Parsers;

class StatsParser
{
    public static function parse(array $rawStats): array
    {
        $custom = $rawStats['stats']['minecraft:custom'] ?? [];
        $mined = $rawStats['stats']['minecraft:mined'] ?? [];
        $crafted = $rawStats['stats']['minecraft:crafted'] ?? [];
        $used = $rawStats['stats']['minecraft:used'] ?? [];

        return [
            'playtime' => self::getInt($custom, 'minecraft:play_time'),
            'deaths' => self::getInt($custom, 'minecraft:deaths'),
            'distance_walked' => self::getInt($custom, 'minecraft:walk_one_cm'),
            'distance_flown' => self::getInt($custom, 'minecraft:fly_one_cm'),
            'distance_swum' => self::getInt($custom, 'minecraft:swim_one_cm'),
            'distance_horseback' => self::getInt($custom, 'minecraft:horse_one_cm'),
            'blocks_mined' => array_sum(array_values($mined)),
            'blocks_placed' => array_sum(array_values($used)),
            'mobs_killed' => self::getInt($custom, 'minecraft:mob_kills'),
            'players_killed' => self::getInt($custom, 'minecraft:player_kills'),
            'items_crafted' => array_sum(array_values($crafted)),
            'items_used' => array_sum(array_values($used)),
            'damage_dealt' => self::getInt($custom, 'minecraft:damage_dealt') / 10.0,
            'damage_taken' => self::getInt($custom, 'minecraft:damage_taken') / 10.0,
            'jumps' => self::getInt($custom, 'minecraft:jump'),
            'falls' => self::getInt($custom, 'minecraft:fall_one_cm'),
            'time_since_death' => self::getInt($custom, 'minecraft:time_since_death'),
            'time_since_rest' => self::getInt($custom, 'minecraft:time_since_rest'),
            'trades' => self::getInt($custom, 'minecraft:traded_with_villager'),
            'anvil_uses' => self::getInt($custom, 'minecraft:interact_with_anvil'),
            'chests_opened' => self::getInt($custom, 'minecraft:open_chest') + self::getInt($custom, 'minecraft:open_barrel'),
            'records_played' => self::getInt($custom, 'minecraft:play_record'),
            'bell_rings' => self::getInt($custom, 'minecraft:bell_ring'),
            'lootr_looted' => self::getInt($custom, 'lootr:looted_stat'),
            'mob_kills' => json_encode($rawStats['stats']['minecraft:killed'] ?? []),
        ];
    }

    private static function getInt(array $stats, string $key): int
    {
        return (int)($stats[$key] ?? 0);
    }
}
