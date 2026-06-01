<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeavesAnnualSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $leaves;
    protected $periodStart;
    protected $periodEnd;

    public function __construct($leaves, $periodStart, $periodEnd)
    {
        $this->leaves = $leaves;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function collection()
    {
        $annualTotals = $this->leaves->where('status', 'accepted')
            ->groupBy('name')
            ->map(fn($g) => ['name' => $g->first()->name, 'total' => $g->sum('days_count')]);

        return collect($annualTotals)->map(function ($record) {
            return [
                'Employee Name' => $record['name'],
                'Total Days In Period' => $record['total'] . ' days',
                'Period'        => $this->periodStart->format('d M Y') . ' - ' . $this->periodEnd->format('d M Y'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Employee Name', 'Total Days In Period', 'Period'];
    }

    public function title(): string { return 'الملخص خلال الدورة'; }

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
