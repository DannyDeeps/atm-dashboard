<?php

namespace AtmDashboard;

class Database
{
  private \PDO $pdo;

  public function __construct(array $dbConfig)
  {
    $dsn = sprintf(
      'pgsql:host=%s;port=%s;dbname=%s',
      $dbConfig['host'] ?? '127.0.0.1',
      $dbConfig['port'] ?? 5432,
      $dbConfig['dbname'] ?? 'atm_dashboard'
    );

    $this->pdo = new \PDO($dsn, $dbConfig['user'] ?? null, $dbConfig['password'] ?? null);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    $this->pdo->exec('SET search_path TO public');
  }

  public function pdo(): \PDO
  {
    return $this->pdo;
  }

  public static function normalizeUuid(string $uuid): string
  {
    return str_replace('-', '', strtolower($uuid));
  }
}
