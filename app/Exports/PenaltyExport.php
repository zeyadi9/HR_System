<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PenaltyExport implements WithMultipleSheets
{
    protected $penalties;
    protected $employeeTotals;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($penalties, $employeeTotals, $periodStart, $periodEnd)
    {
        $this->penalties = $penalties;
        $this->employeeTotals = $employeeTotals;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function sheets(): array
    {
        return [
            new PenaltyMainSheet($this->penalties, $this->periodStart, $this->periodEnd),
            new PenaltyMonthlySheet($this->employeeTotals, $this->periodStart, $this->periodEnd),
        ];
    }
}
