<?php
require __DIR__ . '/../public/index.php';

$seeder = require __DIR__ . '/../database/seeds/DatabaseSeeder.php';
if (is_callable($seeder)) {
    $seeder();
    echo "Seeded database" . PHP_EOL;
}
