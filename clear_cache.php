<?php
// Force clear Laravel caches on Railway
echo "Clearing Laravel cache...\n";

putenv('CACHE_DRIVER=file');
putenv('APP_URL=https://school-management-system-production-2fe2.up.railway.app');

exec('php artisan config:clear', $out1);
exec('php artisan cache:clear', $out2);
exec('php artisan route:clear', $out3);

echo implode("\n", $out1) . "\n";
echo implode("\n", $out2) . "\n";
echo implode("\n", $out3) . "\n";

echo "✅ Done!\n";
