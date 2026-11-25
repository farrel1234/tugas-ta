<?php

use Illuminate\Container\Container;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;

if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// Fix SCRIPT_NAME on Vercel so Laravel does not treat /api as the base URL
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Ensure writable storage directories exist in /tmp
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

$getEnv = static function (string $key): ?string {
    $val = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    return ($val !== false && $val !== null && $val !== '') ? (string) $val : null;
};

$setEnv = static function (string $key, string $value): void {
    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
};

// Detect Neon / Vercel Postgres or external database environment variables
$explicitConnection = $getEnv('DB_CONNECTION');
$pgUrl = $getEnv('DB_URL') ?? $getEnv('DATABASE_URL') ?? $getEnv('POSTGRES_URL');
$pgUnpooledUrl = $getEnv('DATABASE_URL_UNPOOLED') ?? $getEnv('POSTGRES_URL_NON_POOLING') ?? $pgUrl;
$pgHost = $getEnv('PGHOST') ?? $getEnv('POSTGRES_HOST');

$usePostgres = ($explicitConnection === 'pgsql')
    || (($explicitConnection === null || $explicitConnection === 'sqlite') && ($pgUrl !== null || $pgHost !== null));

if ($usePostgres) {
    $setEnv('DB_CONNECTION', 'pgsql');
    $setEnv('DB_SSLMODE', $getEnv('DB_SSLMODE') ?? 'require');

    if ($pgUrl !== null) {
        $setEnv('DB_URL', $pgUrl);
    }

    // One-time-per-container idempotent migration & initial seeding for Neon Postgres
    $migrationFiles = glob(__DIR__ . '/../database/migrations/*.php') ?: [];
    sort($migrationFiles);
    $migrationHash = md5(implode('|', array_map('basename', $migrationFiles)));
    $markerFile = '/tmp/.vercel_pg_bootstrapped_' . $migrationHash;

    if (!file_exists($markerFile)) {
        require_once __DIR__ . '/../vendor/autoload.php';

        // Use unpooled connection for safe DDL migrations on Neon when available
        if ($pgUnpooledUrl !== null) {
            $setEnv('DB_URL', $pgUnpooledUrl);
        }

        /** @var Application $consoleApp */
        $consoleApp = require __DIR__ . '/../bootstrap/app.php';
        $consoleKernel = $consoleApp->make(ConsoleKernel::class);
        $consoleKernel->bootstrap();

        try {
            $consoleKernel->call('migrate', ['--force' => true]);

            if (DB::table('menus')->count() === 0) {
                $consoleKernel->call('db:seed', [
                    '--class' => 'Database\\Seeders\\DatabaseSeeder',
                    '--force' => true,
                ]);
                $consoleKernel->call('db:seed', [
                    '--class' => 'Database\\Seeders\\AnggotaSeeder',
                    '--force' => true,
                ]);
            }

            file_put_contents($markerFile, date('c'));
        } finally {
            DB::purge();
            Facade::clearResolvedInstances();
            Container::setInstance(null);

            if ($pgUrl !== null) {
                $setEnv('DB_URL', $pgUrl);
            }
        }
    }
} elseif ($explicitConnection === null || $explicitConnection === 'sqlite') {
    // Fallback to bundled SQLite copied to writable /tmp if no external DB is configured
    $sourceDb = __DIR__ . '/../database/database.sqlite';
    $targetDb = '/tmp/database.sqlite';

    if (file_exists($sourceDb)) {
        if (!file_exists($targetDb) || filesize($targetDb) === 0 || filemtime($sourceDb) > filemtime($targetDb)) {
            copy($sourceDb, $targetDb);
            chmod($targetDb, 0666);
        }
    }

    $setEnv('DB_CONNECTION', 'sqlite');
    $setEnv('DB_DATABASE', $targetDb);
}

// Register the Composer autoloader...
require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
