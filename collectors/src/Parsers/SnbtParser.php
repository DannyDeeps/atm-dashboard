<?php

namespace AtmCollector\Parsers;

class SnbtParser
{
  private string $input;
  private int $pos;
  private int $len;

  public function parse(string $input): mixed
  {
    $this->input = $input;
    $this->pos = 0;
    $this->len = strlen($input);
    return $this->parseValue();
  }

  private function parseValue(): mixed
  {
    $this->skipWhitespace();
    if ($this->pos >= $this->len) {
      return null;
    }
    $ch = $this->input[$this->pos];
    return match (true) {
      $ch === '{' => $this->parseObject(),
      $ch === '[' => $this->parseArray(),
      $ch === '"' || $ch === "'" => $this->parseString(),
      default => $this->parseLiteral(),
    };
  }

  private function parseObject(): array
  {
    $this->pos++; // skip {
    $result = [];
    while ($this->pos < $this->len) {
      $this->skipWhitespace();
      if ($this->input[$this->pos] === '}') {
        $this->pos++;
        return $result;
      }
      $key = $this->parseValue();
      if (!is_string($key) && !is_int($key) && !is_float($key)) {
        $key = (string)$key;
      }
      $this->skipWhitespace();
      if ($this->pos < $this->len && $this->input[$this->pos] === ':') {
        $this->pos++;
      }
      $value = $this->parseValue();
      $result[$key] = $value;
      $this->skipWhitespace();
      if ($this->pos < $this->len && $this->input[$this->pos] === ',') {
        $this->pos++;
      }
    }
    return $result;
  }

  private function parseArray(): array
  {
    $this->pos++; // skip [
    $this->skipWhitespace();
    if ($this->pos < $this->len && preg_match('/[BILbils]/', $this->input[$this->pos]) && $this->pos + 1 < $this->len && $this->input[$this->pos + 1] === ';') {
      // Typed array like [B;1,2,3] or [I;...], [L;...]
      $this->pos += 2;
      $result = [];
      while ($this->pos < $this->len) {
        $this->skipWhitespace();
        if ($this->input[$this->pos] === ']') {
          $this->pos++;
          return $result;
        }
        $result[] = $this->parseLiteral();
        $this->skipWhitespace();
        if ($this->pos < $this->len && $this->input[$this->pos] === ',') {
          $this->pos++;
        }
      }
      $this->pos++;
      return $result;
    }
    $result = [];
    while ($this->pos < $this->len) {
      $this->skipWhitespace();
      if ($this->input[$this->pos] === ']') {
        $this->pos++;
        return $result;
      }
      $result[] = $this->parseValue();
      $this->skipWhitespace();
      if ($this->pos < $this->len && $this->input[$this->pos] === ',') {
        $this->pos++;
      }
    }
    return $result;
  }

  private function parseString(): string
  {
    $quote = $this->input[$this->pos];
    $this->pos++;
    $result = '';
    while ($this->pos < $this->len) {
      $ch = $this->input[$this->pos];
      if ($ch === '\\') {
        $this->pos++;
        if ($this->pos < $this->len) {
          $result .= $this->input[$this->pos];
          $this->pos++;
        }
      } elseif ($ch === $quote) {
        $this->pos++;
        return $result;
      } else {
        $result .= $ch;
        $this->pos++;
      }
    }
    return $result;
  }

  private function parseLiteral(): mixed
  {
    $start = $this->pos;
    while ($this->pos < $this->len && !$this->isDelimiter($this->input[$this->pos])) {
      $this->pos++;
    }
    $raw = substr($this->input, $start, $this->pos - $start);

    if ($raw === 'true') {
      return true;
    }
    if ($raw === 'false') {
      return false;
    }
    if ($raw === 'null' || $raw === '') {
      return null;
    }
    if (preg_match('/^-?\d+[lL]$/', $raw)) {
      return (int)substr($raw, 0, -1);
    }
    if (preg_match('/^-?\d+[bBsS]$/', $raw)) {
      return (int)substr($raw, 0, -1);
    }
    if (preg_match('/^-?\d+[fF]$/', $raw)) {
      return (float)substr($raw, 0, -1);
    }
    if (preg_match('/^-?[\d.]+(?:[eE][-+]?\d+)?[dD]$/', $raw)) {
      return (float)substr($raw, 0, -1);
    }
    if (is_numeric($raw)) {
      return str_contains($raw, '.') ? (float)$raw : (int)$raw;
    }
    return $raw;
  }

  private function skipWhitespace(): void
  {
    while ($this->pos < $this->len && ($this->input[$this->pos] === ' ' || $this->input[$this->pos] === "\t" || $this->input[$this->pos] === "\n" || $this->input[$this->pos] === "\r")) {
      $this->pos++;
    }
  }

  private function isDelimiter(string $ch): bool
  {
    return $ch === ' ' || $ch === "\t" || $ch === "\n" || $ch === "\r" || $ch === '{' || $ch === '}' || $ch === '[' || $ch === ']' || $ch === ':' || $ch === ',' || $ch === '"' || $ch === "'";
  }
}
