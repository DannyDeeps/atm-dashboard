<?php

namespace AtmCollector\Collectors;

use AtmCollector\Database;
use AtmCollector\Parsers\NbtParser;
use AtmCollector\Parsers\RegionParser;
use AtmCollector\Parsers\SnbtParser;

class WealthCollector
{
  private string $serverPath;
  private string $worldName;
  private Database $db;
  private NbtParser $nbt;
  private RegionParser $region;

  private const DIM_MAP = [
    'minecraft:overworld' => 'region',
    'minecraft:the_nether' => 'DIM-1/region',
    'minecraft:the_end' => 'DIM1/region',
  ];

  private const WEALTH_ITEMS = [
    'minecraft:diamond' => ['field' => 'diamonds', 'weight' => 1],
    'minecraft:diamond_block' => ['field' => 'diamonds', 'weight' => 9],
    'minecraft:emerald' => ['field' => 'emeralds', 'weight' => 0.5],
    'minecraft:emerald_block' => ['field' => 'emeralds', 'weight' => 4.5],
    'allthemodium:allthemodium_ingot' => ['field' => 'allthemodium_ingots', 'weight' => 100],
    'allthemodium:allthemodium_block' => ['field' => 'allthemodium_ingots', 'weight' => 900],
    'allthemodium:allthemodium_nugget' => ['field' => 'allthemodium_ingots', 'weight' => 11],
    'allthemodium:vibranium_ingot' => ['field' => 'vibranium_ingots', 'weight' => 500],
    'allthemodium:vibranium_block' => ['field' => 'vibranium_ingots', 'weight' => 4500],
    'allthemodium:vibranium_nugget' => ['field' => 'vibranium_ingots', 'weight' => 55],
    'allthemodium:unobtainium_ingot' => ['field' => 'unobtainium_ingots', 'weight' => 2000],
    'allthemodium:unobtainium_block' => ['field' => 'unobtainium_ingots', 'weight' => 18000],
    'allthemodium:unobtainium_nugget' => ['field' => 'unobtainium_ingots', 'weight' => 222],
    'mythicmetals:uru_ingot' => ['field' => 'uru_ingots', 'weight' => 5000],
    'mythicmetals:uru' => ['field' => 'uru_ingots', 'weight' => 5000],
    'mythicmetals:uru_block' => ['field' => 'uru_ingots', 'weight' => 45000],
    'allthetweaks:atm_star' => ['field' => 'atm_stars', 'weight' => 10000],
    'allthetweaks:atm_star_block' => ['field' => 'atm_stars', 'weight' => 90000],
  ];

  public function __construct(string $serverPath, string $worldName, Database $db)
  {
    $this->serverPath = $serverPath;
    $this->worldName = $worldName;
    $this->db = $db;
    $this->nbt = new NbtParser();
    $this->region = new RegionParser();
  }

  public function collect(): int
  {
    $playerdataDir = $this->serverPath . '/' . $this->worldName . '/playerdata';
    if (!is_dir($playerdataDir)) {
      echo "    Playerdata directory not found: {$playerdataDir}\n";
      return 0;
    }

    $files = glob($playerdataDir . '/*.dat');
    if ($files === false || count($files) === 0) {
      echo "    No playerdata files found.\n";
      return 0;
    }

    $collected = 0;
    foreach ($files as $file) {
      $uuid = Database::normalizeUuid(basename($file, '.dat'));

      $nbt = $this->nbt->parseFile($file);
      if ($nbt === null) continue;

      $allItems = array_merge($nbt['Inventory'] ?? [], $nbt['EnderItems'] ?? []);

      $counts = [];
      $score = 0;

      // Player inventory + ender chest
      foreach ($allItems as $item) {
        $this->countItem($item, $counts, $score);
      }

      // Claimed chunk containers via region files
      $claimedChunks = $this->getClaimedChunks($uuid);
      if (!empty($claimedChunks)) {
        $containerItems = $this->scanClaimedContainers($claimedChunks);
        foreach ($containerItems as $id => $cnt) {
          $this->applyWealth($id, $cnt, $counts, $score);
        }
      }

      $this->db->upsertWealth($uuid, [
        'diamonds' => $counts['diamonds'] ?? 0,
        'emeralds' => $counts['emeralds'] ?? 0,
        'allthemodium_ingots' => $counts['allthemodium_ingots'] ?? 0,
        'vibranium_ingots' => $counts['vibranium_ingots'] ?? 0,
        'unobtainium_ingots' => $counts['unobtainium_ingots'] ?? 0,
        'uru_ingots' => $counts['uru_ingots'] ?? 0,
        'atm_stars' => $counts['atm_stars'] ?? 0,
        'wealth_score' => $score,
      ]);

      $collected++;
    }

    return $collected;
  }

  private function countItem(array $item, array &$counts, float &$score): void
  {
    $id = $item['id'] ?? '';
    $count = $item['Count'] ?? 0;
    if (!is_int($count)) {
      $count = ord($count) > 127 ? ord($count) - 256 : ord($count);
    }
    if ($id === '' || $count <= 0) return;

    // Flatten shulker boxes inside inventories
    if ($this->isShulkerBox($id)) {
      $inner = $this->extractShulkerContents($item);
      foreach ($inner as $iid => $ic) {
        $this->applyWealth($iid, $ic, $counts, $score);
      }
    }

    $this->applyWealth($id, $count, $counts, $score);
  }

  private function applyWealth(string $id, int $count, array &$counts, float &$score): void
  {
    $def = self::WEALTH_ITEMS[$id] ?? null;
    if ($def !== null && $count > 0) {
      $field = $def['field'];
      $counts[$field] = ($counts[$field] ?? 0) + $count;
      $score += $count * $def['weight'];
    }
  }

  private function getClaimedChunks(string $uuid): array
  {
    $ftbDir = $this->serverPath . '/' . $this->worldName . '/ftbchunks';
    $ftbFile = $ftbDir . '/' . $uuid . '.snbt';

    if (!file_exists($ftbFile)) {
      // Try with hyphenated UUID
      $withHyphens = substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' . substr($uuid, 16, 4) . '-' . substr($uuid, 20, 12);
      $ftbFile = $ftbDir . '/' . $withHyphens . '.snbt';
      if (!file_exists($ftbFile)) return [];
    }

    $parser = new SnbtParser();
    $data = $parser->parse(file_get_contents($ftbFile));
    if (!is_array($data)) return [];

    $chunks = $data['chunks'] ?? [];
    if (!is_array($chunks)) return [];

    return $chunks;
  }

  private function scanClaimedContainers(array $claimedChunks): array
  {
    $worldDir = $this->serverPath . '/' . $this->worldName;
    $allItems = [];

    foreach ($claimedChunks as $dim => $chunkList) {
      $regionSubdir = self::DIM_MAP[$dim] ?? null;
      if ($regionSubdir === null) continue;

      $regionDir = $worldDir . '/' . $regionSubdir;
      if (!is_dir($regionDir)) continue;

      if (!is_array($chunkList)) continue;

      $items = $this->region->scanContainerItems($regionDir, $dim, $chunkList);
      foreach ($items as $id => $cnt) {
        $allItems[$id] = ($allItems[$id] ?? 0) + $cnt;
      }
    }

    return $allItems;
  }

  private function isShulkerBox(string $id): bool
  {
    if (str_starts_with($id, 'minecraft:') === false) return false;
    $suffix = substr($id, 10); // after "minecraft:"
    return $suffix === 'shulker_box' || str_ends_with($suffix, '_shulker_box');
  }

  private function extractShulkerContents(array $item): array
  {
    $items = [];
    $tag = $item['tag'] ?? null;
    if (!is_array($tag)) return $items;

    $blockEntityTag = $tag['BlockEntityTag'] ?? null;
    if (!is_array($blockEntityTag)) return $items;

    $shulkerItems = $blockEntityTag['Items'] ?? [];
    if (!is_array($shulkerItems)) return $items;

    foreach ($shulkerItems as $si) {
      if (!is_array($si)) continue;
      $id = $si['id'] ?? '';
      $count = $si['Count'] ?? 0;
      if (!is_int($count)) $count = ord($count) > 127 ? ord($count) - 256 : ord($count);
      if ($id === '' || $count <= 0) continue;
      $items[$id] = ($items[$id] ?? 0) + $count;
    }

    return $items;
  }
}
