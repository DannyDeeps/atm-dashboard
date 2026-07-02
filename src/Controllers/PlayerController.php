<?php

namespace AtmDashboard\Controllers;

use AtmDashboard\Database;
use AtmDashboard\Services\AwardsService;
use Psr\Http\Message\ServerRequestInterface;

class PlayerController extends BaseController
{
    public function __invoke(ServerRequestInterface $request, array $args): \Psr\Http\Message\ResponseInterface
    {
        $pdo = $this->pdo;
        $uuid = Database::normalizeUuid($args['uuid'] ?? '');
        if ($uuid === '') {
            return $this->redirect('players');
        }

        $tierConfig = AwardsService::tierConfig();

        $player = $pdo->prepare('SELECT * FROM players WHERE uuid = ?');
        $player->execute([$uuid]);
        $player = $player->fetch(\PDO::FETCH_ASSOC);

        if (!$player) {
            return $this->redirect('players');
        }

        $snapshot = $pdo->prepare(
            'SELECT * FROM player_snapshots WHERE uuid = ? ORDER BY collected_at DESC LIMIT 1'
        );
        $snapshot->execute([$uuid]);
        $snapshot = $snapshot->fetch(\PDO::FETCH_ASSOC);

        $maxLife = $pdo->prepare('SELECT MAX(time_since_death) FROM player_snapshots WHERE uuid = ?');
        $maxLife->execute([$uuid]);
        $maxLife = (int) $maxLife->fetchColumn();

        $playerAwards = AwardsService::loadForPlayer($pdo, $uuid);

        $totalQuests = $pdo->query('SELECT COUNT(*) FROM quests')->fetchColumn();
        $completedQuests = $pdo->prepare('SELECT COUNT(*) FROM quest_progress WHERE uuid = ? AND completed_at IS NOT NULL');
        $completedQuests->execute([$uuid]);
        $completedQuests = (int) $completedQuests->fetchColumn();
        $startedQuests = $pdo->prepare('SELECT COUNT(*) FROM quest_progress WHERE uuid = ?');
        $startedQuests->execute([$uuid]);
        $startedQuests = (int) $startedQuests->fetchColumn();

        $statBoxes = [];
        if ($snapshot) {
            $statBoxes = [
                ['title' => 'Playtime', 'value' => formatTicks((int)$snapshot['playtime'])],
                ['title' => 'Deaths', 'value' => number_format((int)$snapshot['deaths'])],
                ['title' => 'Distance', 'value' => formatCm((int)($snapshot['distance_walked'] + $snapshot['distance_flown'] + $snapshot['distance_swum']))],
                ['title' => 'Mobs Killed', 'value' => number_format((int)$snapshot['mobs_killed'])],
                ['title' => 'Blocks Mined', 'value' => number_format((int)$snapshot['blocks_mined'])],
                ['title' => 'Blocks Placed', 'value' => number_format((int)$snapshot['blocks_placed'])],
                ['title' => 'Items Crafted', 'value' => number_format((int)$snapshot['items_crafted'])],
                ['title' => 'Damage Dealt', 'value' => number_format((int)$snapshot['damage_dealt'])],
                ['title' => 'Damage Taken', 'value' => number_format((int)$snapshot['damage_taken'])],
                ['title' => 'Jumps', 'value' => number_format((int)$snapshot['jumps'])],
                ['title' => 'Current Life', 'value' => formatTicks((int)$snapshot['time_since_death'])],
                ['title' => 'Best Life', 'value' => formatTicks($maxLife), 'extra' => 'text-primary'],
                ['title' => 'Lootr Crates', 'value' => number_format((int)$snapshot['lootr_looted'])],
            ];
        }

        return $this->render('player', [
            'player' => $player,
            'snapshot' => $snapshot,
            'statBoxes' => $statBoxes,
            'playerAwards' => $playerAwards,
            'tierConfig' => $tierConfig,
            'totalQuests' => $totalQuests,
            'completedQuests' => $completedQuests,
            'startedQuests' => $startedQuests,
            'currentPage' => 'player',
            'headTitle' => htmlspecialchars($player['name']) . ' - ATM10 Dashboard',
        ]);
    }
}
