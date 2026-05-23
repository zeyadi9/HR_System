<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Leave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeavesSummarySheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $periodStart;
    protected $periodEnd;

    public function __construct($periodStart, $periodEnd)
    {
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    public function collection()
    {
        $users = User::all();
        $year = $this->periodStart->year;

        return $users->map(function ($user) use ($year) {
            $totalTaken = Leave::where('user_id', $user->id)
                ->where('status', 'accepted')
                ->whereYear('date', $year)
                ->sum('days_count');

            $remaining = 14 - $totalTaken;

            return [
                'Employee Name' => $user->name,
                'Job Title'     => $user->job_title ?? '—',
                'Annual Total'  => 14,
                'Total Taken'   => $totalTaken,
                'Remaining'     => $remaining,
                'Year'          => $year,
            ];
        });
    }

    public function headings(): array
    {
        return ['Employee Name', 'Job Title', 'Annual Total (Days)', 'Total Taken (Days)', 'Remaining (Days)', 'Year'];
    }

    public function title(): string
    {
        return 'ملخص الإجازات';
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
