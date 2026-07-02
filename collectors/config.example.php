<?php

return [
  'servers' => [
    'atm10' => [
      'path' => '/path/to/minecraft/server',
      'world_name' => 'world',
      'display_name' => 'ATM10',
    ],
  ],

  'database' => [
    'driver' => 'pgsql',
    'host' => '192.168.0.x',
    'port' => 1234,
    'dbname' => 'atm_dashboard',
    'user' => 'your_user',
    'password' => 'your_password',
  ],

  'collection_interval_seconds' => 300,
];
