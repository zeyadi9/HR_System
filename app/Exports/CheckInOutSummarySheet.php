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
                'Employee Name'  => $record->name ?? 'غير محدد',
                'Check In (Accepted)' => $record->check_in_count . ' مرة',
                'Check Out (Accepted)' => $record->check_out_count . ' مرة',
                'Pending Movements' => $record->pending_count . ' طلب',
                'Total'          => $record->total . ' سجل',
                'Period'         => $this->periodStart->format('d M Y') . ' → ' . $this->periodEnd->format('d M Y'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Employee Name', 'Check In (Accepted)', 'Check Out (Accepted)', 'Pending Movements', 'Total Records', 'Period'];
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
