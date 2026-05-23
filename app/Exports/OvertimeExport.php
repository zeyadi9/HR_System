<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OvertimeExport implements WithMultipleSheets
{
    protected $overtime;
    protected $monthlyTotals;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($overtime, $monthlyTotals, $periodStart, $periodEnd)
    {
        $this->overtime = $overtime;
        $this->monthlyTotals = $monthlyTotals;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function sheets(): array
    {
        return [
            new OvertimeMainSheet($this->overtime, $this->periodStart, $this->periodEnd),
            new OvertimeMonthlySheet($this->monthlyTotals, $this->periodStart, $this->periodEnd),
        ];
    }
}
