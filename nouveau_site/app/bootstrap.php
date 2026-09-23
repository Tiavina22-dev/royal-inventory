<?php

declare(strict_types=1);

session_start();

$config = require __DIR__ . '/../config/config.php';
$legacyRoutes = require __DIR__ . '/../config/legacy_routes.php';

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/Services/SalesService.php';
require_once __DIR__ . '/Services/StockService.php';

$db = new Database($config['db']);
$auth = new Auth($db, $config);
$repository = new Repository($db);
$salesService = new SalesService($db, $repository);
$stockService = new StockService($db, $repository);

