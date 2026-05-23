<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

use Illuminate\Support\Facades\DB;

$tables = ['overtimes', 'leaves', 'permissions', 'check_in_outs', 'penalties', 'incentives', 'settlements', 'admin_notes'];

foreach ($tables as $t) {
    try {
        $indexes = DB::select('SHOW INDEX FROM ' . $t);
        echo "$t: " . implode(', ', array_unique(array_map(fn($i) => $i->Key_name, $indexes))) . PHP_EOL;
    } catch (\Exception $e) {
        echo "$t: ERROR (" . $e->getMessage() . ")" . PHP_EOL;
    }
}
