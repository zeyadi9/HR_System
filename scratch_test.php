<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::insert("insert into overtimes (user_id, name, date, day, total_hours, reason, `from`, `to`, created_ip, updated_at, created_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [1, 'Zeyad Yasser', '2026-04-16', 'test', 15, 'test', '00:00', '15:00', '192.168.1.124', '2026-04-16 14:06:05', '2026-04-16 14:06:05']);
    echo "Inserted successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
