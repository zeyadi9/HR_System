<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IncentiveExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $incentives;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($incentives, $periodStart, $periodEnd)
    {
        $this->incentives = $incentives;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function collection()
    {
        return $this->incentives->map(function ($item, $index) {
            return [
                'N'              => $index + 1,
                'Employee Name'  => $item->name,
                'Job Title'      => $item->user->job_title ?? '—',
                'Evaluation'     => $item->evaluation,
                'Date'           => \Carbon\Carbon::parse($item->date)->format('d M Y'),
                'Created At'     => $item->created_at->format('d M Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return ['N', 'Employee Name', 'Job Title', 'Evaluation', 'Date', 'Created At'];
    }

    public function title(): string
    {
        return 'الحوافز';
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
