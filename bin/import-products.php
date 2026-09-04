#!/usr/bin/env php
<?php

declare(strict_types=1);

// Dit script haalt producten op bij https://dummyjson.com/products en zet
// ze in onze eigen database. Draai het zo:
//
//   docker compose exec app php bin/import-products.php

use MaxServ\App\Service\ProductImporter;
use MaxServ\Core\Bootstrap;

DEFINE('APPLICATION_ROOT', dirname(__DIR__));

require_once APPLICATION_ROOT . '/vendor/autoload.php';

$container = (new Bootstrap())->buildContainer();

/** @var ProductImporter $importer */
$importer = $container->get(ProductImporter::class);

echo "Producten importeren vanaf dummyjson.com...\n";

$aantal = $importer->import();

echo "Klaar! {$aantal} producten geïmporteerd.\n";
