<?php

namespace AtmDashboard\Controllers;

use AtmDashboard\Services\LeaderboardService;
use Psr\Http\Message\ServerRequestInterface;

class HomeController extends BaseController
{
    public function __invoke(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $config = $this->config;
        $pdo = $this->pdo;

        $totalPlayers = $pdo->query('SELECT COUNT(*) FROM players')->fetchColumn();

        $onlineCount = 0;
        $onlinePlayers = [];
        $addr = $config['server_addr'] ?? '127.0.0.1';
        $port = $config['server_port'] ?? 25565;

        $sock = null;
        $tryAddr = $addr;
        $tryPort = $port;
        $attempts = [["$addr", $port]];
        foreach ($config['servers'] as $s) {
            $uuid = basename($s['path']);
            $cid = @trim((string) shell_exec("docker ps --filter name=$uuid -q"));
            if ($cid) {
                $netinfo = @trim((string) shell_exec("docker inspect --format '{{range \$k, \$v := .NetworkSettings.Networks}}{{\$v.IPAddress}} {{end}}' $cid"));
                if ($netinfo) {
                    $ips = explode(' ', $netinfo);
                    $cport = $s['port'] ?? $port;
                    foreach ($ips as $ip) {
                        if (filter_var($ip, FILTER_VALIDATE_IP)) {
                            $attempts[] = [$ip, $cport];
                        }
                    }
                }
            }
        }

        foreach ($attempts as [$tryAddr, $tryPort]) {
            $s = @fsockopen($tryAddr, $tryPort, $errno, $errstr, 2);
            if ($s) { $sock = $s; break; }
        }

        if ($sock) {
            $wvi = function ($v) {
                $buf = '';
                while ($v > 0x7F) { $buf .= chr(($v & 0x7F) | 0x80); $v >>= 7; }
                $buf .= chr($v & 0x7F);
                return $buf;
            };
            $ws = function ($s) use ($wvi) { return $wvi(strlen($s)) . $s; };
            $rvi = function ($s) {
                $v = 0; $i = 0;
                while (true) {
                    $b = ord(fread($s, 1));
                    $v |= ($b & 0x7F) << ($i++ * 7);
                    if ($i > 5 || !($b & 0x80)) break;
                }
                return $v;
            };

            $hs = chr(0x00) . $wvi(-1) . $ws($tryAddr) . pack('n', $tryPort) . $wvi(1);
            fwrite($sock, $wvi(strlen($hs)) . $hs);
            fwrite($sock, chr(0x01) . chr(0x00));
            $len = $rvi($sock);
            $pid = $rvi($sock);
            $slen = $rvi($sock);
            $json = '';
            while (strlen($json) < $slen) {
                $chunk = fread($sock, $slen - strlen($json));
                if ($chunk === false) break;
                $json .= $chunk;
            }
            fclose($sock);

            $data = json_decode($json, true);
            if ($data && isset($data['players']['online'])) {
                $onlineCount = (int) $data['players']['online'];
                $onlinePlayers = isset($data['players']['sample']) ? $data['players']['sample'] : [];
            }
        } else {
            foreach ($config['servers'] as $s) {
                $pd = $s['path'] . '/world/playerdata';
                if (!is_dir($pd)) continue;
                $now = time();
                foreach (glob($pd . '/*.dat') as $f) {
                    if ($now - filemtime($f) < 300) $onlineCount++;
                }
            }
        }

        $leaderboards = [
            ['title' => 'The No-Lifer',     'description' => 'Most Playtime',          'color' => '#cba6f7', 'icon' => 'Invicon_Clock.gif',              'rows' => $this->lb(LeaderboardService::query($pdo, 'MAX(ps.playtime) AS val', 'val'), 'val', fn($v) => formatTicks($v))],
            ['title' => 'Fragile',          'description' => 'Most Deaths',            'color' => '#f38ba8', 'icon' => 'Invicon_Skeleton_Skull.png',    'rows' => $this->lb(LeaderboardService::query($pdo, 'MAX(ps.deaths) AS val', 'val'), 'val', fn($v) => number_format($v))],
            ['title' => 'Trailblazer',      'description' => 'Most Distance Traveled', 'color' => '#89b4fa', 'icon' => 'Invicon_Compass.gif',           'rows' => $this->lb(LeaderboardService::query($pdo, 'MAX(ps.distance_walked + ps.distance_flown + ps.distance_swum) AS val', 'val'), 'val', fn($v) => formatCm($v))],
            ['title' => 'Mob Masher',       'description' => 'Most Mobs Killed',       'color' => '#f5c2e7', 'icon' => 'Invicon_Diamond_Sword.png',     'rows' => $this->lb(LeaderboardService::query($pdo, 'MAX(ps.mobs_killed) AS val', 'val'), 'val', fn($v) => number_format($v))],
            ['title' => 'Strip Miner',      'description' => 'Most Blocks Mined',      'color' => '#fab387', 'icon' => 'Invicon_Diamond_Pickaxe.png',  'rows' => $this->lb(LeaderboardService::query($pdo, 'MAX(ps.blocks_mined) AS val', 'val'), 'val', fn($v) => number_format($v))],
            ['title' => 'Master Builder',   'description' => 'Most Blocks Placed',     'color' => '#a6e3a1', 'icon' => 'Invicon_Bricks.png',            'rows' => $this->lb(LeaderboardService::query($pdo, 'MAX(ps.blocks_placed) AS val', 'val'), 'val', fn($v) => number_format($v))],
            ['title' => 'One-man Factory',  'description' => 'Most Items Crafted',     'color' => '#89dceb', 'icon' => 'Invicon_Crafting_Table.png',   'rows' => $this->lb(LeaderboardService::query($pdo, 'MAX(ps.items_crafted) AS val', 'val'), 'val', fn($v) => number_format($v))],
            ['title' => 'Immortal',         'description' => 'Longest Life',           'color' => '#94e2d5', 'icon' => 'Invicon_Golden_Apple.png',     'rows' => $this->lb(LeaderboardService::queryLatest($pdo, 'ps.time_since_death', 'ps.time_since_death'), 'time_since_death', fn($v) => formatTicks($v))],
            ['title' => 'Loot Goblin',      'description' => 'Lootr Crates Opened',    'color' => '#f9e2af', 'icon' => 'Invicon_Chest.png',            'rows' => $this->lb(LeaderboardService::query($pdo, 'MAX(ps.lootr_looted) AS val', 'val'), 'val', fn($v) => number_format($v))],
        ];

        return $this->render('home', [
            'totalPlayers' => $totalPlayers,
            'onlineCount' => $onlineCount,
            'onlinePlayers' => $onlinePlayers,
            'leaderboards' => $leaderboards,
            'currentPage' => 'index',
            'headTitle' => 'Glip Glops - ATM10 Modded Minecraft Server',
        ]);
    }

    private function lb(array $rows, string $key, callable $format): array
    {
        return array_map(fn($r) => [
            'uuid' => $r['uuid'],
            'name' => $r['name'],
            'value' => $format((int)$r[$key]),
        ], $rows);
    }
}
