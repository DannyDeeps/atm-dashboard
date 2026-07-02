<?php

namespace AtmCollector\Collectors;

use AtmCollector\Database;
use AtmCollector\Parsers\SnbtParser;

class QuestCollector
{
  private static function stripColorCodes(?string $str): ?string
  {
    if ($str === null) return null;
    return preg_replace(['/&[0-9a-fk-or]/i', '/\\\\&/'], ['', '&'], $str);
  }

  private string $serverPath;
  private string $worldName;
  private Database $db;
  private SnbtParser $parser;

  public function __construct(string $serverPath, string $worldName, Database $db)
  {
    $this->serverPath = $serverPath;
    $this->worldName = $worldName;
    $this->db = $db;
    $this->parser = new SnbtParser();
  }

  public function collect(): array
  {
    $questsDir = $this->discoverQuestsDir();
    $lang = $questsDir ? $this->loadLang($questsDir) : [];
    $chapterGroups = $questsDir ? $this->loadChapterGroups($questsDir) : [];

    $result = [
      'chapters_found' => 0,
      'quests_found' => 0,
      'players_found' => 0,
    ];

    // 1. Load chapter groups
    foreach ($chapterGroups as $groupId => $groupTitle) {
      $this->db->insertChapterGroup($groupId, $groupTitle);
    }

    // 2. Load chapters and their quests
    if ($questsDir === null) {
      echo "    Quest definitions directory not found (searched config/ftbquests/quests)\n";
      return $result;
    }

    $chaptersDir = $questsDir . '/chapters';
    if (!is_dir($chaptersDir)) {
      echo "    Chapters directory not found: {$chaptersDir}\n";
      return $result;
    }

    $chapterFiles = glob($chaptersDir . '/*.snbt');
    if ($chapterFiles === false || count($chapterFiles) === 0) {
      echo "    No chapter files found.\n";
      return $result;
    }

    foreach ($chapterFiles as $file) {
      $chapterData = $this->loadSnbtFile($file);
      if ($chapterData === null) {
        continue;
      }

      $chapterId = $chapterData['id'] ?? null;
      if ($chapterId === null) {
        continue;
      }

      $chapterTitle = self::stripColorCodes($lang["chapter.{$chapterId}.title"] ?? $chapterData['filename'] ?? $chapterId);
      $groupId = $chapterData['group'] ?? null;
      $orderIndex = $chapterData['order_index'] ?? 0;
      $filename = $chapterData['filename'] ?? basename($file, '.snbt');

      $this->db->insertChapter($chapterId, $chapterTitle, $groupId, (int)$orderIndex, $filename);
      $result['chapters_found']++;

      // Process quests in this chapter
      $quests = $chapterData['quests'] ?? [];
      foreach ($quests as $quest) {
        $questId = $quest['id'] ?? null;
        if ($questId === null) {
          continue;
        }

        $dependencies = $quest['dependencies'] ?? [];
        if (!is_array($dependencies)) {
          $dependencies = [$dependencies];
        }

        $this->db->insertQuest(
          $questId,
          $chapterId,
          self::stripColorCodes($lang["quest.{$questId}.title"] ?? $quest['title'] ?? null),
          self::stripColorCodes($lang["quest.{$questId}.quest_subtitle"] ?? null),
          $this->formatDescription($lang["quest.{$questId}.quest_desc"] ?? null),
          (float)($quest['x'] ?? 0),
          (float)($quest['y'] ?? 0),
          $dependencies,
          (bool)($quest['optional'] ?? false),
          (float)($quest['size'] ?? 1.0),
          (string)($quest['shape'] ?? 'circle'),
          (int)($quest['min_width'] ?? 0)
        );

        $result['quests_found']++;
      }
    }

    // 3. Load player progress
    $progressDir = $this->serverPath . '/' . $this->worldName . '/ftbquests';
    if (!is_dir($progressDir)) {
      echo "    Player quest progress directory not found: {$progressDir}\n";
      return $result;
    }

    $progressFiles = glob($progressDir . '/*.snbt');
    if ($progressFiles === false || count($progressFiles) === 0) {
      echo "    No player progress files found.\n";
      return $result;
    }

    foreach ($progressFiles as $file) {
      $progressData = $this->loadSnbtFile($file);
      if ($progressData === null) {
        continue;
      }

      $uuid = Database::normalizeUuid($progressData['uuid'] ?? '');
      if ($uuid === '') {
        continue;
      }

      $name = $progressData['name'] ?? null;
      if ($name !== null && str_contains($name, '#')) {
        $name = explode('#', $name, 2)[0];
      }

      if ($name !== null) {
        $this->db->upsertPlayer($uuid, $name);
      }

      // Clear old progress for this player and re-insert
      $this->db->clearQuestProgress($uuid);

      $completed = $progressData['completed'] ?? [];
      $started = $progressData['started'] ?? [];

      foreach ($completed as $questId => $timestamp) {
        $ts = is_int($timestamp) || is_float($timestamp) ? (int)$timestamp : null;
        $startTs = null;
        if (isset($started[$questId])) {
          $startTs = is_int($started[$questId]) || is_float($started[$questId]) ? (int)$started[$questId] : null;
        }
        $this->db->upsertQuestProgress($uuid, $questId, $ts, $startTs);
      }

      // Also store quests that are started but not completed
      foreach ($started as $questId => $timestamp) {
        if (!isset($completed[$questId])) {
          $ts = is_int($timestamp) || is_float($timestamp) ? (int)$timestamp : null;
          $this->db->upsertQuestProgress($uuid, $questId, null, $ts);
        }
      }

      $result['players_found']++;
    }

    return $result;
  }

  private function discoverQuestsDir(): ?string
  {
    $candidates = [
      $this->serverPath . '/config/ftbquests/quests',
      $this->serverPath . '/defaultconfigs/ftbquests/quests',
    ];

    foreach ($candidates as $path) {
      if (is_dir($path)) {
        return $path;
      }
    }

    return null;
  }

  private function loadLang(string $questsDir): array
  {
    $langFile = $questsDir . '/lang/en_us.snbt';
    if (!file_exists($langFile)) {
      return [];
    }

    $data = $this->loadSnbtFile($langFile);
    return $data ?? [];
  }

  private function loadChapterGroups(string $questsDir): array
  {
    $groupsFile = $questsDir . '/chapter_groups.snbt';
    if (!file_exists($groupsFile)) {
      return [];
    }

    $data = $this->loadSnbtFile($groupsFile);
    if ($data === null) {
      return [];
    }

    $groups = $data['chapter_groups'] ?? [];
    $result = [];
    foreach ($groups as $group) {
      $id = $group['id'] ?? null;
      if ($id !== null) {
        $result[$id] = null;
      }
    }

    // Try to get titles from the lang file
    $langFile = $questsDir . '/lang/en_us.snbt';
    if (file_exists($langFile)) {
      $lang = $this->loadSnbtFile($langFile);
      if (is_array($lang)) {
        foreach ($result as $id => $_) {
          $titleKey = "chapter_group.{$id}.title";
          $result[$id] = self::stripColorCodes($lang[$titleKey] ?? null);
        }
      }
    }

    return $result;
  }

  private function formatDescription($desc): ?string
  {
    if ($desc === null) {
      return null;
    }
    if (is_array($desc)) {
      return implode("\n", $desc);
    }
    return (string)$desc;
  }

  private function loadSnbtFile(string $path): ?array
  {
    $content = @file_get_contents($path);
    if ($content === false) {
      return null;
    }
    try {
      $result = $this->parser->parse($content);
      return is_array($result) ? $result : null;
    } catch (\Throwable $e) {
      echo "    Warning: Failed to parse {$path}: {$e->getMessage()}\n";
      return null;
    }
  }
}
