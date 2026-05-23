<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PermissionsExport implements WithMultipleSheets
{
    protected $permissions;
    protected $monthlyTotals;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($permissions, $monthlyTotals, $periodStart, $periodEnd)
    {
        $this->permissions = $permissions;
        $this->monthlyTotals = $monthlyTotals;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function sheets(): array
    {
        return [
            new PermissionsMainSheet($this->permissions, $this->periodStart, $this->periodEnd),
            new PermissionsMonthlySheet($this->monthlyTotals, $this->periodStart, $this->periodEnd),
        ];
    }
}
