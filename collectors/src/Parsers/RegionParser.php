<?php

namespace AtmCollector\Parsers;

class RegionParser
{
  private const CONTAINER_IDS = [
    'minecraft:chest', 'minecraft:trapped_chest', 'minecraft:barrel',
    'minecraft:hopper', 'minecraft:dispenser', 'minecraft:dropper',
    'minecraft:furnace', 'minecraft:blast_furnace', 'minecraft:smoker',
    'minecraft:brewing_stand',
  ];

  private NbtParser $nbt;

  public function __construct()
  {
    $this->nbt = new NbtParser();
  }

  public function scanContainerItems(string $regionDir, string $dimension, array $claimedChunks): array
  {
    $result = [];

    // Group claimed chunks by region file
    $regionChunks = [];
    foreach ($claimedChunks as $c) {
      $rx = $c['x'] >> 5;
      $rz = $c['z'] >> 5;
      $key = "r.{$rx}.{$rz}.mca";
      if (!isset($regionChunks[$key])) {
        $regionChunks[$key] = [];
      }
      $regionChunks[$key][] = $c;
    }

    foreach ($regionChunks as $fileName => $chunks) {
      $path = $regionDir . '/' . $fileName;
      if (!file_exists($path)) continue;

      $data = file_get_contents($path);
      if ($data === false || strlen($data) < 8192) continue;

      foreach ($chunks as $c) {
        $items = $this->scanChunk($data, $c['x'], $c['z']);
        foreach ($items as $id => $count) {
          $result[$id] = ($result[$id] ?? 0) + $count;
        }
      }
    }

    return $result;
  }

  private function scanChunk(string $regionData, int $chunkX, int $chunkZ): array
  {
    $index = ($chunkZ & 31) * 32 + ($chunkX & 31);

    // Location entry: 4 bytes at offset index * 4
    $locOffset = $index * 4;
    if ($locOffset + 4 > strlen($regionData)) return [];

    $locBytes = substr($regionData, $locOffset, 4);
    $sectorOffset = (ord($locBytes[0]) << 16) | (ord($locBytes[1]) << 8) | ord($locBytes[2]);
    $sectorCount = ord($locBytes[3]);

    if ($sectorOffset === 0 || $sectorCount === 0) return [];

    // Chunk data at sector offset * 4096
    $chunkOffset = $sectorOffset * 4096;
    if ($chunkOffset + 5 > strlen($regionData)) return [];

    $chunkLen = unpack('N', substr($regionData, $chunkOffset, 4))[1];
    $compressionType = ord($regionData[$chunkOffset + 4]);

    if ($chunkLen < 1) return [];

    $compressed = substr($regionData, $chunkOffset + 5, $chunkLen - 1);

    $nbtData = match ($compressionType) {
      1 => @gzdecode($compressed),
      2 => @zlib_decode($compressed),
      3 => $compressed,
      default => false,
    };

    if ($nbtData === false) return [];

    $chunkNbt = $this->parseChunkNbt($nbtData);
    if ($chunkNbt === null) return [];

    return $this->extractContainerItems($chunkNbt);
  }

  private function parseChunkNbt(string $data): ?array
  {
    $offset = 0;
    $type = ord($data[$offset]);
    $offset++;

    if ($type !== 10) return null; // Must be TAG_Compound

    // Skip root name
    if ($offset + 2 > strlen($data)) return null;
    $nameLen = unpack('n', substr($data, $offset, 2))[1];
    $offset += 2 + $nameLen;

    $result = [];
    while ($offset < strlen($data)) {
      $t = ord($data[$offset]);
      if ($t === 0) break;
      $offset++;

      // Read tag name
      if ($offset + 2 > strlen($data)) return null;
      $nl = unpack('n', substr($data, $offset, 2))[1];
      $offset += 2;
      if ($offset + $nl > strlen($data)) return null;
      $name = substr($data, $offset, $nl);
      $offset += $nl;

      // Override: offset may now point to payload, not tag type
      // Actually we already consumed the type byte. Now parse payload.
      $val = $this->parsePayload($t, $data, $offset);
      if ($val === null && $t !== 0) return null;
      $result[$name] = $val;
    }

    return $result;
  }

  private function parsePayload(int $type, string $data, int &$offset): mixed
  {
    return match ($type) {
      0 => null,
      1 => $this->parseByte($data, $offset),
      2 => $this->parseShort($data, $offset),
      3 => $this->parseInt($data, $offset),
      4 => $this->parseLong($data, $offset),
      5 => $this->parseFloat($data, $offset),
      6 => $this->parseDouble($data, $offset),
      7 => $this->parseByteArray($data, $offset),
      8 => $this->parseString($data, $offset),
      9 => $this->parseList($data, $offset),
      10 => $this->parseCompoundPayload($data, $offset),
      11 => $this->parseIntArray($data, $offset),
      12 => $this->parseLongArray($data, $offset),
      default => null,
    };
  }

  private function parseByte(string $data, int &$offset): int
  {
    $v = ord($data[$offset]);
    $offset += 1;
    return $v > 127 ? $v - 256 : $v;
  }

  private function parseShort(string $data, int &$offset): int
  {
    $v = unpack('n', substr($data, $offset, 2))[1];
    $offset += 2;
    return $v > 0x7FFF ? $v - 0x10000 : $v;
  }

  private function parseInt(string $data, int &$offset): int
  {
    $v = unpack('N', substr($data, $offset, 4))[1];
    $offset += 4;
    return $v > 0x7FFFFFFF ? $v - 0x100000000 : $v;
  }

  private function parseLong(string $data, int &$offset): int
  {
    $v = unpack('J', substr($data, $offset, 8))[1];
    $offset += 8;
    return $v;
  }

  private function parseFloat(string $data, int &$offset): float
  {
    $v = unpack('G', substr($data, $offset, 4))[1];
    $offset += 4;
    return $v;
  }

  private function parseDouble(string $data, int &$offset): float
  {
    $v = unpack('E', substr($data, $offset, 8))[1];
    $offset += 8;
    return $v;
  }

  private function parseString(string $data, int &$offset): ?string
  {
    if ($offset + 2 > strlen($data)) return null;
    $len = unpack('n', substr($data, $offset, 2))[1];
    $offset += 2;
    if ($offset + $len > strlen($data)) return null;
    $str = substr($data, $offset, $len);
    $offset += $len;
    return $str;
  }

  private function parseList(string $data, int &$offset): ?array
  {
    if ($offset + 5 > strlen($data)) return null;
    $elemType = ord($data[$offset]);
    $offset++;
    $len = unpack('N', substr($data, $offset, 4))[1];
    $offset += 4;

    $items = [];
    for ($i = 0; $i < $len; $i++) {
      $val = $this->parsePayload($elemType, $data, $offset);
      if ($val === null && $elemType !== 0) return null;
      $items[] = $val;
    }
    return $items;
  }

  private function parseCompoundPayload(string $data, int &$offset): ?array
  {
    $result = [];
    while ($offset < strlen($data)) {
      $t = ord($data[$offset]);
      if ($t === 0) {
        $offset++;
        break;
      }
      $offset++;

      $nl = unpack('n', substr($data, $offset, 2))[1];
      $offset += 2;
      $name = substr($data, $offset, $nl);
      $offset += $nl;

      $val = $this->parsePayload($t, $data, $offset);
      if ($val === null && $t !== 0) return null;
      $result[$name] = $val;
    }
    return $result;
  }

  private function parseByteArray(string $data, int &$offset): array
  {
    $len = $this->parseInt($data, $offset);
    $bytes = array_values(unpack('C*', substr($data, $offset, $len)));
    $offset += $len;
    return $bytes;
  }

  private function parseIntArray(string $data, int &$offset): array
  {
    $len = $this->parseInt($data, $offset);
    $vals = [];
    for ($i = 0; $i < $len; $i++) {
      $vals[] = $this->parseInt($data, $offset);
    }
    return $vals;
  }

  private function parseLongArray(string $data, int &$offset): array
  {
    $len = $this->parseInt($data, $offset);
    $vals = [];
    for ($i = 0; $i < $len; $i++) {
      $vals[] = $this->parseLong($data, $offset);
    }
    return $vals;
  }

  private function extractContainerItems(array $chunkNbt): array
  {
    $items = [];

    // Modern format (1.18+): block_entities list
    $blockEntities = $chunkNbt['block_entities'] ?? [];
    if (!is_array($blockEntities)) return [];

    foreach ($blockEntities as $be) {
      if (!is_array($be)) continue;
      $id = $be['id'] ?? '';

      if (!in_array($id, self::CONTAINER_IDS, true)) continue;

      $beItems = $be['Items'] ?? [];
      if (!is_array($beItems)) continue;

      foreach ($beItems as $item) {
        if (!is_array($item)) continue;
        $itemId = $item['id'] ?? '';
        $count = $item['Count'] ?? 0;
        if (!is_int($count)) $count = ord($count) > 127 ? ord($count) - 256 : ord($count);
        if ($itemId === '' || $count <= 0) continue;

        // Flatten shulker box contents
        if ($this->isShulkerBox($itemId)) {
          $inner = $this->extractShulkerContents($item);
          foreach ($inner as $iid => $ic) {
            $items[$iid] = ($items[$iid] ?? 0) + $ic;
          }
        } else {
          $items[$itemId] = ($items[$itemId] ?? 0) + $count;
        }
      }
    }

    return $items;
  }

  private function isShulkerBox(string $id): bool
  {
    return str_starts_with($id, 'minecraft:shulker_box')
      || str_ends_with($id, '_shulker_box');
  }

  private function extractShulkerContents(array $shulkerItem): array
  {
    $items = [];
    $tag = $shulkerItem['tag'] ?? null;
    if (!is_array($tag)) return [];

    $blockEntityTag = $tag['BlockEntityTag'] ?? null;
    if (!is_array($blockEntityTag)) return [];

    $shulkerItems = $blockEntityTag['Items'] ?? [];
    if (!is_array($shulkerItems)) return [];

    foreach ($shulkerItems as $item) {
      if (!is_array($item)) continue;
      $itemId = $item['id'] ?? '';
      $count = $item['Count'] ?? 0;
      if (!is_int($count)) $count = ord($count) > 127 ? ord($count) - 256 : ord($count);
      if ($itemId === '' || $count <= 0) continue;
      $items[$itemId] = ($items[$itemId] ?? 0) + $count;
    }

    return $items;
  }
}
