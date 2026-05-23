<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminNoteExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $notes;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($notes, $periodStart, $periodEnd)
    {
        $this->notes = $notes;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function collection()
    {
        return $this->notes->map(function ($item, $index) {
            return [
                'N'          => $index + 1,
                'Note'       => $item->note,
                'Date'       => \Carbon\Carbon::parse($item->date)->format('d M Y'),
                'Created At' => $item->created_at->format('d M Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return ['N', 'Note', 'Date', 'Created At'];
    }

    public function title(): string
    {
        return 'ملاحظات الإدارة';
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
