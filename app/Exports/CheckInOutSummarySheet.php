<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CheckInOutSummarySheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $summary;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($summary, $periodStart, $periodEnd)
    {
        $this->summary = $summary;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function collection()
    {
        return $this->summary->map(function ($record) {
            return [
                'Employee Name'        => $record->name ?? 'غير محدد',
                'Check In (Accepted)'  => ($record->check_in_count ?? 0) . ' مرة',
                'Check Out (Accepted)' => ($record->check_out_count ?? 0) . ' مرة',
                'Regular Shifts'       => ($record->regular_shifts ?? 0) . ' شيفت عادي',
                'Friday Shifts'        => ($record->friday_shifts ?? 0) . ' شيفت جمعة',
                'Friday Hours (x1.5)'  => number_format($record->friday_bonus_hours ?? 0, 2) . ' ساعة',
                'Total Shifts'         => ($record->shifts_count ?? 0) . ' شيفت',
                'Total Records'        => ($record->total ?? 0) . ' سجل',
                'Period'               => $this->periodStart->format('d M Y') . ' → ' . $this->periodEnd->format('d M Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'اسم الموظف',
            'حضور (مقبول)',
            'انصراف (مقبول)',
            'شيفتات عادية',
            'شيفتات جمعة',
            'ساعات الجمعة (× 1.5)',
            'إجمالي الشيفتات',
            'إجمالي الحركات',
            'الفترة'
        ];
    }

    public function title(): string
    {
        return 'ملخص الحضور والانصراف';
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
