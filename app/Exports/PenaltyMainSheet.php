<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenaltyMainSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $penalties;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($penalties, $periodStart, $periodEnd)
    {
        $this->penalties = $penalties;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function collection()
    {
        return $this->penalties->map(function ($item, $index) {
            return [
                'N'             => $index + 1,
                'Employee Name' => $item->name,
                'Job Title'     => $item->user->job_title ?? '—',
                'Reason'        => $item->reason,
                'Amount'        => $item->amount,
                'Notes'         => $item->notes ?? '—',
                'Actioned By'   => $item->actioned_by ?? '—',
                'Status'        => ucfirst($item->status),
                'Date'          => $item->created_at->format('d M Y'),
                'Refuse Reason' => $item->refuse_reason ?? '—',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'N', 'Employee Name', 'Job Title', 'Reason', 'Amount',
            'Notes', 'Actioned By', 'Status', 'Date', 'Refuse Reason',
        ];
    }

    public function title(): string
    {
        return 'سجل الجزاءات';
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
