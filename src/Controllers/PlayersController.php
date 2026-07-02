<?php

namespace AtmDashboard\Controllers;

use AtmDashboard\Services\AwardsService;
use Psr\Http\Message\ServerRequestInterface;

class PlayersController extends BaseController
{
    public function __invoke(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $pdo = $this->pdo;

        $totalQuests = (int) $pdo->query('SELECT COUNT(*) FROM quests')->fetchColumn();

        $tierConfig = AwardsService::tierConfig();

        $players = $pdo->query(
            'SELECT p.uuid, p.name, p.world_tier,
                    s.max_playtime, s.max_deaths, s.max_distance,
                    s.max_mobs_killed, s.max_lootr_looted
             FROM players p
             LEFT JOIN (
                 SELECT uuid,
                        MAX(playtime) AS max_playtime,
                        MAX(deaths) AS max_deaths,
                        MAX(distance_walked + distance_flown + distance_swum) AS max_distance,
                        MAX(mobs_killed) AS max_mobs_killed,
                        MAX(lootr_looted) AS max_lootr_looted
                 FROM player_snapshots
                 GROUP BY uuid
             ) s ON p.uuid = s.uuid
             ORDER BY max_playtime DESC'
        )->fetchAll(\PDO::FETCH_ASSOC);

        $questCounts = [];
        $r = $pdo->query(
            'SELECT uuid, COUNT(*) AS cnt FROM quest_progress WHERE completed_at IS NOT NULL GROUP BY uuid'
        )->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($r as $row) {
            $questCounts[$row['uuid']] = (int) $row['cnt'];
        }

        $playerAwards = AwardsService::loadAll($pdo);

        return $this->render('players', [
            'totalQuests' => $totalQuests,
            'tierConfig' => $tierConfig,
            'players' => $players,
            'questCounts' => $questCounts,
            'playerAwards' => $playerAwards,
            'currentPage' => 'players',
            'headTitle' => 'Players - Glip Glops ATM10',
        ]);
    }
}
