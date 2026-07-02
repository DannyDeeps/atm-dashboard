<?php

namespace AtmDashboard;

class Database
{
  private \PDO $pdo;

  /**
   * @param string|array $dbConfig  string = SQLite path (legacy), array = full config
   */
  public function __construct(string|array $dbConfig)
  {
    if (is_string($dbConfig)) {
      $dsn = 'sqlite:' . $dbConfig;
      $dir = dirname($dbConfig);
      if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
      }
    } else {
      $driver = $dbConfig['driver'] ?? 'sqlite';
      $dsn = match ($driver) {
        'pgsql' => sprintf(
          'pgsql:host=%s;port=%s;dbname=%s',
          $dbConfig['host'] ?? '127.0.0.1',
          $dbConfig['port'] ?? 5432,
          $dbConfig['dbname'] ?? 'atm_dashboard'
        ),
        default => 'sqlite:' . ($dbConfig['path'] ?? __DIR__ . '/../data/atm-dashboard.sqlite'),
      };
    }

    $user = is_array($dbConfig) ? ($dbConfig['user'] ?? $dbConfig['username'] ?? null) : null;
    $pass = is_array($dbConfig) ? ($dbConfig['password'] ?? $dbConfig['pass'] ?? null) : null;

    $this->pdo = new \PDO($dsn, $user, $pass);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

    if (is_array($dbConfig) && ($dbConfig['driver'] ?? 'sqlite') === 'pgsql') {
      $this->pdo->exec('SET search_path TO public');
    } elseif (is_string($dbConfig) || ($dbConfig['driver'] ?? 'sqlite') === 'sqlite') {
      $this->pdo->exec('PRAGMA journal_mode=WAL');
      $this->pdo->exec('PRAGMA busy_timeout=5000');
    }
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
