<?php

return [
    'app_name' => 'Royal Inventory V1',
    'base_path' => dirname(__DIR__),
    'base_url' => rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/'),
    'db' => [
        'host' => getenv('RI_DB_HOST') ?: '127.0.0.1',
        'port' => getenv('RI_DB_PORT') ?: '3306',
        'database' => getenv('RI_DB_NAME') ?: 'gestion_stock_test',
        'username' => getenv('RI_DB_USER') ?: 'root',
        'password' => getenv('RI_DB_PASS') ?: '',
        'charset' => getenv('RI_DB_CHARSET') ?: 'utf8mb4',
    ],
    'legacy_base_url' => getenv('RI_LEGACY_BASE_URL') ?: '../ancien_code/modernisation/',
    'allow_offline_login' => getenv('RI_ALLOW_OFFLINE_LOGIN') === '1',
];

