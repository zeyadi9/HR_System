<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LeavesExport implements WithMultipleSheets
{
    protected $leaves;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($leaves, $periodStart, $periodEnd)
    {
        $this->leaves = $leaves;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function sheets(): array
    {
        return [
            new LeavesMainSheet($this->leaves, $this->periodStart, $this->periodEnd),
            new LeavesAnnualSheet($this->leaves, $this->periodStart->format('Y')),
        ];
    }
}
