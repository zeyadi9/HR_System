<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FullReportExport implements WithMultipleSheets
{
    protected $overtime;
    protected $overtimeTotals;
    protected $leaves;
    protected $permissions;
    protected $permissionTotals;
    protected $penalties;
    protected $penaltyTotals;
    protected $checkInOut;
    protected $checkInOutSummary;
    protected $adminNotes;
    protected $incentives;
    protected $settlements;
    protected $periodStart;
    protected $periodEnd;

    public function __construct(
        $overtime, $overtimeTotals,
        $leaves,
        $permissions, $permissionTotals,
        $penalties, $penaltyTotals,
        $checkInOut, $checkInOutSummary,
        $adminNotes,
        $incentives,
        $settlements,
        $periodStart, $periodEnd
    ) {
        $this->overtime = $overtime;
        $this->overtimeTotals = $overtimeTotals;
        $this->leaves = $leaves;
        $this->permissions = $permissions;
        $this->permissionTotals = $permissionTotals;
        $this->penalties = $penalties;
        $this->penaltyTotals = $penaltyTotals;
        $this->checkInOut = $checkInOut;
        $this->checkInOutSummary = $checkInOutSummary;
        $this->adminNotes = $adminNotes;
        $this->incentives = $incentives;
        $this->settlements = $settlements;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function sheets(): array
    {
        return [
            new OvertimeMainSheet($this->overtime, $this->periodStart, $this->periodEnd),
            new OvertimeMonthlySheet($this->overtimeTotals, $this->periodStart, $this->periodEnd),
            new LeavesMainSheet($this->leaves, $this->periodStart, $this->periodEnd),
            new LeavesSummarySheet($this->periodStart, $this->periodEnd),
            new PermissionsMainSheet($this->permissions, $this->periodStart, $this->periodEnd),
            new PermissionsMonthlySheet($this->permissionTotals, $this->periodStart, $this->periodEnd),
            new PenaltyMainSheet($this->penalties, $this->periodStart, $this->periodEnd),
            new PenaltyMonthlySheet($this->penaltyTotals, $this->periodStart, $this->periodEnd),
            new CheckInOutMainSheet($this->checkInOut, $this->periodStart, $this->periodEnd),
            new CheckInOutSummarySheet($this->checkInOutSummary, $this->periodStart, $this->periodEnd),
            new AdminNoteExport($this->adminNotes, $this->periodStart, $this->periodEnd),
            new IncentiveExport($this->incentives, $this->periodStart, $this->periodEnd),
            new SettlementExport($this->settlements, $this->periodStart, $this->periodEnd),
        ];
    }
}
