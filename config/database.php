<?php

return [
    'host' => getenv('DB_HOST') ?: 'shuttle.proxy.rlwy.net',
    'dbname' => getenv('DB_NAME') ?: 'LaCasitaDeVero',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: 'anJkMDnhTJoXaMDjgYFpfmkMBUskRZFu',
    'charset' => 'utf8mb4',
    'port' => getenv('DB_PORT') ?: 21840
];
?>