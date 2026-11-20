<?php

// Target database in writable /tmp directory on Vercel
$sourceDb = __DIR__ . '/../database/database.sqlite';
$targetDb = '/tmp/database.sqlite';

if (file_exists($sourceDb) && (!file_exists($targetDb) || filesize($targetDb) === 0)) {
    copy($sourceDb, $targetDb);
    chmod($targetDb, 0666);
}

// Ensure storage directories exist in /tmp
$dirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Set environment variables for SQLite database in /tmp
putenv("DB_CONNECTION=sqlite");
putenv("DB_DATABASE=/tmp/database.sqlite");
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = '/tmp/database.sqlite';

// Forward Vercel requests to Laravel's public entry point
require __DIR__ . '/../public/index.php';
