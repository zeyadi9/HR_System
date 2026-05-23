<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SettlementExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $settlements;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($settlements, $periodStart, $periodEnd)
    {
        $this->settlements = $settlements;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function collection()
    {
        return $this->settlements->map(function ($item, $index) {
            $statusLabel = $item->status === 'accepted' ? 'تم القبول' : ($item->status === 'refused' ? 'تم الرفض' : 'قيد المراجعة');
            return [
                'N'              => $index + 1,
                'Employee Name'  => $item->name,
                'Job Title'      => $item->user->job_title ?? '—',
                'Note'           => $item->note,
                'Date'           => \Carbon\Carbon::parse($item->date)->format('d M Y'),
                'Day'            => $item->day,
                'Status'         => $statusLabel,
                'Accept Note'    => $item->accept_note ?? '—',
                'Refuse Reason'  => $item->refuse_reason ?? '—',
                'Actioned By'    => $item->actioned_by ?? '—',
                'Created At'     => $item->created_at->format('d M Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return ['N', 'Employee Name', 'Job Title', 'Note', 'Date', 'Day', 'Status', 'Accept Note', 'Refuse Reason', 'Actioned By', 'Created At'];
    }

    public function title(): string
    {
        return 'التسويات';
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
