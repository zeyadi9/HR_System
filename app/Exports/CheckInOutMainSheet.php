<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CheckInOutMainSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $records;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($records, $periodStart, $periodEnd)
    {
        $this->records = $records;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function collection()
    {
        return $this->records->map(function ($item, $index) {
            return [
                'N'             => $index + 1,
                'Employee Name' => $item->name,
                'Job Title'     => $item->user->job_title ?? '—',
                'Type'          => $item->type,
                'Date'          => \Carbon\Carbon::parse($item->date)->format('d M Y'),
                'Day'           => $item->day,
                'Status'        => $item->status === 'accepted' ? 'تم القبول' : ($item->status === 'refused' ? 'تم الرفض' : 'قيد المراجعة'),
                'Actioned By'   => $item->actioned_by ?? '—',
                'Created At'    => $item->created_at->format('H:i'),
                'Refuse Reason' => $item->refuse_reason ?? '—',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'N', 'Employee Name', 'Job Title', 'Type', 'Date', 'Day',
            'Status', 'Actioned By', 'Created At', 'Refuse Reason',
        ];
    }

    public function title(): string
    {
        return 'سجل الحضور والانصراف';
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
