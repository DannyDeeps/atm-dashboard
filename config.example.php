<?php

return [
  'database' => [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => (int)(getenv('DB_PORT') ?: 5432),
    'dbname' => getenv('DB_NAME') ?: 'atm_dashboard',
    'user' => getenv('DB_USER') ?: 'postgres',
    'password' => getenv('DB_PASSWORD') ?: 'change_me',
  ],

  'server_addr' => getenv('MC_SERVER_ADDR') ?: '127.0.0.1',
  'server_port' => (int)(getenv('MC_SERVER_PORT') ?: 25569),
  'server_hostname' => getenv('MC_SERVER_HOSTNAME') ?: 'your-server.example.com',

  'webmap_url' => getenv('WEBMAP_URL') ?: '/bluemap/',
];
