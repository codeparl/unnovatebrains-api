<?php

// 1. Safety first: Prevent browser execution
if (PHP_SAPI !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    echo "Access Denied: Migrations can only be run via the CLI.\n";
    exit(1);
}

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;

// 2. Load our shared database bootstrap
$capsule = require_once __DIR__ . '/database.php';

// 3. Get the Database Resolver (this implements ConnectionResolverInterface!)
$resolver = $capsule->getDatabaseManager();

// 4. Initialize repository safely using the resolver
$repository = new DatabaseMigrationRepository($resolver, 'schema_migrations');

if (!$repository->repositoryExists()) {
    $repository->createRepository();
    echo "Initialized migration repository table 'schema_migrations'.\n";
}

// 5. Setup Migrator (passing the resolver here as well)
$migrator = new Migrator(
    $repository,
    $resolver,
    new Filesystem(),
    new Dispatcher()
);

$migrationsPath = __DIR__ . '/migrations';

if (!is_dir($migrationsPath)) {
    fwrite(STDERR, "Error: Migrations directory not found at {$migrationsPath}\n");
    exit(1);
}

// 5. Run and Output Results
try {
    echo "Checking for pending migrations...\n";
    $executed = $migrator->run($migrationsPath);

    if (empty($executed)) {
        echo "Nothing to migrate. Database is up to date!\n";
    } else {
        foreach ($executed as $file) {
            echo "Migrated: " . basename($file) . "\n";
        }
        echo "Successfully ran " . count($executed) . " migration(s).\n";
    }
} catch (\Throwable $e) {
    fwrite(STDERR, "Migration failed! Details: " . $e->getMessage() . "\n");
    exit(1);
}