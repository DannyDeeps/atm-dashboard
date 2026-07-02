<?php

namespace AtmCollector\Parsers;

class NbtParser
{
  private int $offset;
  private string $data;

  public function parseFile(string $path): array
  {
    $raw = file_get_contents($path);
    if ($raw === false) {
      throw new \RuntimeException("Cannot read file: {$path}");
    }

    // Detect GZip header
    if (strlen($raw) >= 2 && ord($raw[0]) === 0x1f && ord($raw[1]) === 0x8b) {
      $decompressed = gzdecode($raw);
      if ($decompressed === false) {
        throw new \RuntimeException("Failed to GZip decompress: {$path}");
      }
      $raw = $decompressed;
    }

    return $this->parseString($raw);
  }

  public function parseString(string $data): array
  {
    $this->data = $data;
    $this->offset = 0;

    $tagType = $this->readByte();
    $name = $this->readString();

    return ['name' => $name, 'value' => $this->readTag($tagType)];
  }

  private function readTag(int $tagType): mixed
  {
    return match ($tagType) {
      0 => null, // TAG_End
      1 => $this->readByte(),
      2 => $this->readShort(),
      3 => $this->readInt(),
      4 => $this->readLong(),
      5 => $this->readFloat(),
      6 => $this->readDouble(),
      7 => $this->readByteArray(),
      8 => $this->readStringValue(),
      9 => $this->readList(),
      10 => $this->readCompound(),
      11 => $this->readIntArray(),
      12 => $this->readLongArray(),
      default => throw new \RuntimeException("Unknown NBT tag type: {$tagType}"),
    };
  }

  private function readByte(): int
  {
    $val = unpack('c', $this->data[$this->offset])[1];
    $this->offset++;
    return $val;
  }

  private function readShort(): int
  {
    $val = unpack('n', substr($this->data, $this->offset, 2))[1];
    $this->offset += 2;
    return $val;
  }

  private function readInt(): int
  {
    $val = unpack('N', substr($this->data, $this->offset, 4))[1];
    $this->offset += 4;

    // Handle unsigned overflow
    if ($val >= 0x80000000) {
      $val -= 0x100000000;
    }
    return $val;
  }

  private function readLong(): int
  {
    $val = unpack('J', substr($this->data, $this->offset, 8))[1];
    $this->offset += 8;

    // Handle unsigned overflow (PHP int may be 64-bit, but be safe)
    if ($val >= 1 << 63) {
      $val -= 1 << 64;
    }
    return $val;
  }

  private function readFloat(): float
  {
    $val = unpack('G', substr($this->data, $this->offset, 4))[1];
    $this->offset += 4;
    return $val;
  }

  private function readDouble(): float
  {
    $val = unpack('E', substr($this->data, $this->offset, 8))[1];
    $this->offset += 8;
    return $val;
  }

  private function readByteArray(): string
  {
    $length = $this->readInt();
    $val = substr($this->data, $this->offset, $length);
    $this->offset += $length;
    return $val;
  }

  private function readString(): string
  {
    $length = $this->readShort();
    if ($length === 0) {
      return '';
    }
    $val = substr($this->data, $this->offset, $length);
    $this->offset += $length;
    return $val;
  }

  private function readStringValue(): string
  {
    return $this->readString();
  }

  private function readList(): array
  {
    $elemType = $this->readByte();
    $length = $this->readInt();
    $items = [];
    for ($i = 0; $i < $length; $i++) {
      $items[] = $this->readTag($elemType);
    }
    return $items;
  }

  private function readCompound(): array
  {
    $result = [];
    while (true) {
      $tagType = $this->readByte();
      if ($tagType === 0) { // TAG_End
        break;
      }
      $name = $this->readString();
      $result[$name] = $this->readTag($tagType);
    }
    return $result;
  }

  private function readIntArray(): array
  {
    $length = $this->readInt();
    $items = [];
    for ($i = 0; $i < $length; $i++) {
      $items[] = $this->readInt();
    }
    return $items;
  }

  private function readLongArray(): array
  {
    $length = $this->readInt();
    $items = [];
    for ($i = 0; $i < $length; $i++) {
      $items[] = $this->readLong();
    }
    return $items;
  }
}
