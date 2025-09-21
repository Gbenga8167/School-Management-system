<?php
// Temporary script to clear Laravel cache on Railway

echo "Clearing config, cache, and routes...\n";

exec('php artisan config:clear', $output1);
exec('php artisan cache:clear', $output2);
exec('php artisan route:clear', $output3);

echo implode("\n", $output1);
echo "\n";
echo implode("\n", $output2);
echo "\n";
echo implode("\n", $output3);
echo "\nDone!";
