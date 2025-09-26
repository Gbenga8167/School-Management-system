<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

try {
    $kernel->call('config:clear');
    $kernel->call('cache:clear');
    $kernel->call('route:clear');

    echo "✅ Laravel caches cleared successfully!";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
