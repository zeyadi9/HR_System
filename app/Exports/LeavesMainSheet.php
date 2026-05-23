<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeavesMainSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
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
        return $this->leaves->map(function ($item, $index) {
            $userId = $item->user_id;
            
            // Total taken in the year of this leave record
            $year = \Carbon\Carbon::parse($item->date)->year;
            $totalTaken = \App\Models\Leave::where('user_id', $userId)
                ->where('status', 'accepted')
                ->whereYear('date', $year)
                ->sum('days_count');

            $remaining = 14 - $totalTaken;

            return [
                'N'             => $index + 1,
                'Employee Name' => $item->name,
                'Job Title'     => $item->user->job_title ?? '—',
                'Date'          => \Carbon\Carbon::parse($item->date)->format('d M Y'),
                'Day'           => $item->day,
                'Days Count'    => $item->days_count . ' days',
                'Reason'        => $item->reason,
                'Substitute'    => $item->substitute,
                'Status'        => ucfirst($item->status),
                'Refuse Reason' => $item->refuse_reason ?? '—',
                'Total Taken'   => $totalTaken,
                'Remaining'     => $remaining,
            ];
        });
    }

    public function headings(): array
    {
        return ['N', 'Employee Name', 'Job Title', 'Date', 'Day', 'Days Count', 'Reason', 'Substitute', 'Status', 'Refuse Reason', 'Total Taken', 'Remaining'];
    }

    public function title(): string { return 'سجل الإجازات'; }

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
