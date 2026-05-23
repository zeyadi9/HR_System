<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\CheckInOut;
use Carbon\Carbon;

$start = '2026-03-26';
$end = '2026-04-25';

$all = CheckInOut::whereBetween('date', [$start, $end])->get();
echo "Total records in range: " . $all->count() . "\n";
foreach($all as $r) {
    echo "- Name: {$r->name}, Date: {$r->date}, Status: {$r->status}, Type: {$r->type}\n";
}

$accepted = CheckInOut::whereBetween('date', [$start, $end])->where('status', 'accepted')->get();
echo "Total accepted records: " . $accepted->count() . "\n";

use App\Models\Settlement;
use App\Models\Incentive;
use App\Models\AdminNote;

echo "\nSettlements in range:\n";
$s = Settlement::whereBetween('date', [$start, $end])->get();
echo "Count: " . $s->count() . "\n";

echo "\nIncentives in range:\n";
$i = Incentive::whereBetween('date', [$start, $end])->get();
echo "Count: " . $i->count() . "\n";

echo "\nAdmin Notes in range:\n";
$n = AdminNote::whereBetween('date', [$start, $end])->get();
echo "Count: " . $n->count() . "\n";
