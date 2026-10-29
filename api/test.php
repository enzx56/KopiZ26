<?php

// Test script to check runtime environment and catch error
header('Content-Type: text/plain');

echo "=== PHP RUNTIME CHECK ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Loaded Extensions: " . implode(', ', get_loaded_extensions()) . "\n";

echo "\n=== ENVIRONMENT VARIABLES ===\n";
echo "APP_KEY: " . (getenv('APP_KEY') ? 'EXISTS' : 'MISSING') . "\n";
echo "DB_CONNECTION: " . getenv('DB_CONNECTION') . "\n";
echo "DATABASE_URL: " . (getenv('DATABASE_URL') ? 'EXISTS' : 'MISSING') . "\n";
echo "POSTGRES_URL: " . (getenv('POSTGRES_URL') ? 'EXISTS' : 'MISSING') . "\n";

echo "\n=== STORAGE WRITE TEST ===\n";
$testFile = '/tmp/test.txt';
if (@file_put_contents($testFile, 'OK')) {
    echo "/tmp write: SUCCESS\n";
} else {
    echo "/tmp write: FAILED\n";
}

echo "\n=== BOOTSTRAP TEST ===\n";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "Composer Autoload: SUCCESS\n";

    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "Laravel Bootstrap: SUCCESS\n";

    // Test DB connection
    $db = $app->make('db');
    $pdo = $db->connection()->getPdo();
    echo "DB Connection: SUCCESS (" . get_class($pdo) . ")\n";
} catch (\Throwable $e) {
    echo "ERROR OCCURRED:\n";
    echo "Type: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
