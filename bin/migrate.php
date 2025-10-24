<?php
require __DIR__ . '/../public/index.php';

use Core\Database;

$db = Database::getConnection();

$migrationFiles = glob(__DIR__ . '/../database/migrations/*.php');
foreach ($migrationFiles as $file) {
    $migration = require $file;
    if (is_callable($migration)) {
        $migration($db);
        echo 'Migrated: ' . basename($file) . PHP_EOL;
    }
}
