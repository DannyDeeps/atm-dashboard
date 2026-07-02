<?php

namespace AtmCollector\Collectors;

use AtmCollector\Database;
use AtmCollector\Parsers\StatsParser;

class StatsCollector {
  private string $statsDir;
  private Database $db;

  public function __construct(string $serverPath, string $worldName, Database $db) {
    $this->statsDir = "$serverPath/$worldName/stats";
    $this->db = $db;
  }

  public function collect(): int {
    if (!is_dir($this->statsDir)) {
      echo "Stats directory not found: {$this->statsDir}\n";
      return 0;
    }

    $files = glob($this->statsDir . '/*.json');
    if ($files === false || count($files) === 0) {
      echo "No stats files found.\n";
      return 0;
    }

    $collected = 0;

    foreach ($files as $file) {
      $uuid = Database::normalizeUuid(basename($file, '.json'));

      $content = file_get_contents($file);
      if ($content === false) {
        continue;
      }

      $raw = json_decode($content, true);
      if (!is_array($raw)) {
        continue;
      }

      $playerName = $this->resolvePlayerName($uuid);
      if ($playerName === null) {
        continue;
      }

      $stats = StatsParser::parse($raw);

      $this->db->upsertPlayer($uuid, $playerName);
      $this->db->insertSnapshot($uuid, $stats);

      $collected++;
    }

    return $collected;
  }

  private function resolvePlayerName(string $uuid): ?string
  {
    $playerdataDir = dirname($this->statsDir) . '/playerdata';

    if (!is_dir($playerdataDir)) {
      return null;
    }

    // UUID may be normalized (no hyphens), but files on disk use hyphens
    $datFile = $playerdataDir . '/' . $uuid . '.dat';
    if (!file_exists($datFile)) {
      // Try with hyphens (standard UUID format)
      $withHyphens = substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' . substr($uuid, 16, 4) . '-' . substr($uuid, 20, 12);
      $datFile = $playerdataDir . '/' . $withHyphens . '.dat';
      if (!file_exists($datFile)) {
        return null;
      }
    }

    $cacheFile = __DIR__ . '/../../data/name_cache.json';
    $cache = [];
    if (file_exists($cacheFile)) {
      $cache = json_decode(file_get_contents($cacheFile), true) ?: [];
    }

    // Rebuild cache with normalized UUIDs
    $normalizedCache = [];
    foreach ($cache as $key => $val) {
      $normalizedCache[Database::normalizeUuid($key)] = $val;
    }

    if (isset($normalizedCache[$uuid])) {
      return $normalizedCache[$uuid];
    }

    $name = $this->lookupNameViaMojang($uuid);
    if ($name !== null) {
      $normalizedCache[$uuid] = $name;
      file_put_contents($cacheFile, json_encode($normalizedCache, JSON_PRETTY_PRINT));
    }

    return $name;
  }

  private function lookupNameViaMojang(string $uuid): ?string
  {
    $formattedUuid = str_replace('-', '', $uuid);

    $ch = curl_init("https://sessionserver.mojang.com/session/minecraft/profile/{$formattedUuid}");
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 5,
      CURLOPT_FOLLOWLOCATION => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode !== 200 || $response === false) {
      return null;
    }

    $data = json_decode($response, true);
    return $data['name'] ?? null;
  }
}
