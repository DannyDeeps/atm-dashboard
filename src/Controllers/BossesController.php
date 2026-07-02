<?php

namespace AtmDashboard\Controllers;

use Psr\Http\Message\ServerRequestInterface;

class BossesController extends BaseController
{
    public function __invoke(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $pdo = $this->pdo;

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

        $bossImages = [
            'minecraft:wither' => '/images/bosses/Invicon_Wither_Spawn_Egg.png',
            'minecraft:ender_dragon' => '/images/bosses/Invicon_Ender_Dragon_Spawn_Egg.png',
            'minecraft:warden' => '/images/bosses/Invicon_Warden_Spawn_Egg.png',
            'minecraft:elder_guardian' => '/images/bosses/Invicon_Elder_Guardian_Spawn_Egg.png',
            'minecraft:ravager' => '/images/bosses/Invicon_Ravager_Spawn_Egg.png',
            'cataclysm:ignis' => '/images/bosses/ignis.png',
            'cataclysm:netherite_monstrosity' => '/images/bosses/netherite_monstrosity.png',
            'cataclysm:the_leviathan' => '/images/bosses/Invicon_Wither_Skeleton_Skull.png',
            'cataclysm:the_harbinger' => '/images/bosses/Invicon_Wither_Skeleton_Skull.png',
            'cataclysm:ender_golem' => '/images/bosses/Invicon_Wither_Skeleton_Skull.png',
            'cataclysm:ender_guardian' => '/images/bosses/ender_guardian.png',
            'cataclysm:ancient_remnant' => '/images/bosses/Invicon_Wither_Skeleton_Skull.png',
            'iceandfire:fire_dragon' => '/images/bosses/Invicon_Wither_Skeleton_Skull.png',
            'iceandfire:ice_dragon' => '/images/bosses/Invicon_Wither_Skeleton_Skull.png',
            'iceandfire:lightning_dragon' => '/images/bosses/Invicon_Wither_Skeleton_Skull.png',
            'iceandfire:sea_serpent' => '/images/bosses/Invicon_Wither_Skeleton_Skull.png',
            'twilightforest:naga' => '/images/bosses/naga.png',
            'twilightforest:lich' => '/images/bosses/twilight_lich.png',
            'twilightforest:minoshroom' => '/images/bosses/minoshroom.png',
            'twilightforest:hydra' => '/images/bosses/hydra.png',
            'twilightforest:knight_phantom' => '/images/bosses/knight_phantom.png',
            'twilightforest:ur_ghast' => '/images/bosses/ur_ghast.png',
            'twilightforest:alpha_yeti' => '/images/bosses/alpha_yeti.png',
            'twilightforest:snow_queen' => '/images/bosses/snow_queen.png',
            'ars_nouveau:wilden_boss' => '/images/bosses/Invicon_Wither_Skeleton_Skull.png',
            'deeperdarker:stalker' => '/images/bosses/Invicon_Wither_Skeleton_Skull.png',
            'deeperdarker:warden_helmet' => '/images/bosses/Invicon_Wither_Skeleton_Skull.png',
        ];

        $rows = $pdo->query(
            'SELECT p.uuid, p.name, ps.mob_kills
            FROM players p
            JOIN player_snapshots ps ON ps.uuid = p.uuid
            WHERE ps.collected_at = (SELECT MAX(collected_at) FROM player_snapshots WHERE uuid = p.uuid)'
        )->fetchAll(\PDO::FETCH_ASSOC);

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

        return $this->render('bosses', [
            'bosses' => $bosses,
            'bossImages' => $bossImages,
            'bossKills' => $bossKills,
            'currentPage' => 'bosses',
            'headTitle' => 'Boss Kills - Glip Glops ATM10',
        ]);
    }
}
