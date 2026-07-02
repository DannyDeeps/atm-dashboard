<?php

namespace AtmCollector\Collectors;

use AtmCollector\Database;
use AtmCollector\Parsers\NbtParser;

class PlayerDataCollector
{
  private string $serverPath;
  private string $worldName;
  private Database $db;
  private NbtParser $parser;

  public function __construct(string $serverPath, string $worldName, Database $db)
  {
    $this->serverPath = $serverPath;
    $this->worldName = $worldName;
    $this->db = $db;
    $this->parser = new NbtParser();
  }

  public function collect(): array
  {
    $playerdataDir = "{$this->serverPath}/{$this->worldName}/playerdata";
    if (!is_dir($playerdataDir)) {
      return [];
    }

    $files = glob("{$playerdataDir}/*.dat");
    $results = [];

    foreach ($files as $file) {
      $basename = basename($file);
      $uuid = str_replace('.dat', '', $basename);
      $uuid = Database::normalizeUuid($uuid);

      try {
        $parsed = $this->parser->parseFile($file);
        $data = $parsed['value'] ?? [];

        $worldTier = $data['neoforge:attachments']['apotheosis:world_tier'] ?? null;

        if ($worldTier !== null) {
          $this->db->updatePlayerWorldTier($uuid, $worldTier);
          $results[$uuid] = $worldTier;
        }
      } catch (\Throwable $e) {
        // Silently skip — player .dat may be mid-write
        continue;
      }
    }

    return $results;
  }
}
