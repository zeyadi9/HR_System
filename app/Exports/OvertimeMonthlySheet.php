<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OvertimeMonthlySheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $monthlyTotals;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($monthlyTotals, $periodStart, $periodEnd)
    {
        $this->monthlyTotals = $monthlyTotals;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function collection()
    {
        return $this->monthlyTotals->map(function ($record) {
            $amount = 0;
            if ($record->user && $record->user->hourly_rate > 0) {
                $amount = $record->total * 1.5 * $record->user->hourly_rate;
            }

            return [
                'Employee Name'   => $record->name ?? 'غير محدد',
                'Job Title'       => $record->user->job_title ?? '—',
                'Total Hours'     => round($record->total, 2) . '  ',
                'OT Amount (EGP)' => $amount > 0 ? number_format($amount, 2) . ' ج.م' : 'قيمة الساعة غير محددة',
                'Period'          => $this->periodStart->format('d M Y') . ' → ' . $this->periodEnd->format('d M Y'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Employee Name', 'Job Title', 'Total Hours This Cycle', 'Overtime Amount (EGP)', 'Period'];
    }

    public function title(): string
    {
        return 'إجمالي الإضافي';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F81BD']
                ]
            ],
        ];
    }
}
