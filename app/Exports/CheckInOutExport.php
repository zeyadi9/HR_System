<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CheckInOutExport implements WithMultipleSheets
{
    protected $records;
    protected $summary;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($records, $summary, $periodStart, $periodEnd)
    {
        $this->records = $records;
        $this->summary = $summary;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function sheets(): array
    {
        return [
            new CheckInOutMainSheet($this->records, $this->periodStart, $this->periodEnd),
            new CheckInOutSummarySheet($this->summary, $this->periodStart, $this->periodEnd),
        ];
    }
}
