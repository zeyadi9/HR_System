<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Leave;
use App\Models\Permission;
use App\Models\Overtime;
use App\Models\Penalty;
use Illuminate\Support\Facades\Auth;
use App\Support\PayrollPeriod;
use Carbon\Carbon;

class EmployeeProfileController extends Controller
{
    /**
     * Display current user's profile with monthly calendar navigation.
     */
    public function myProfile(Request $request)
    {
        $user = Auth::user();
        return $this->renderProfileView($user, $request);
    }

    /**
     * Admin view of a specific employee profile.
     */
    public function show(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'super_admin']) && Auth::id() != $id) {
            abort(403, 'غير مصرح لك باستعراض هذا البروفايل');
        }

        $user = User::findOrFail($id);
        return $this->renderProfileView($user, $request);
    }

    /**
     * Calculate stats for employee profile edit view.
     */
    private function getProfileStats(User $user)
    {
        $period = PayrollPeriod::current();
        
        // Leaves
        $monthLeaves = Leave::where('user_id', $user->id)
            ->whereBetween('date', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->where('status', '!=', 'refused')
            ->sum('days_count');

        $yearLeaves = Leave::where('user_id', $user->id)
            ->whereYear('date', now()->year)
            ->where('status', '!=', 'refused')
            ->sum('days_count');

        // Permissions
        $permissions = Permission::where('user_id', $user->id)
            ->whereBetween('date', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->where('status', '!=', 'refused')
            ->get();

        $totalPermissionMinutes = 0;
        foreach ($permissions as $p) {
            if ($p->from && $p->to) {
                $from = Carbon::parse($p->from);
                $to   = Carbon::parse($p->to);
                $totalPermissionMinutes += $from->diffInMinutes($to);
            }
        }

        // Overtime
        $overtimeHours = Overtime::where('user_id', $user->id)
            ->whereBetween('date', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->where('status', 'accepted')
            ->sum('total_hours');

        $overtimeValue = 0;
        if ($user->hourly_rate > 0) {
            $overtimeValue = $overtimeHours * 1.5 * $user->hourly_rate;
        }

        return [
            'period_label' => $period['label'],
            'leaves' => [
                'month' => $monthLeaves,
                'year'  => $yearLeaves,
                'month_limit' => 2,
                'year_limit'  => 14,
            ],
            'permissions' => [
                'minutes' => $totalPermissionMinutes,
                'hours'   => round($totalPermissionMinutes / 60, 2),
                'limit_minutes' => 180,
            ],
            'overtime' => [
                'hours' => round($overtimeHours, 2),
                'value' => round($overtimeValue, 2),
            ]
        ];
    }

    /**
     * Render the employee profile view with monthly data and annual leave balance.
     */
    private function renderProfileView(User $user, Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        if ($month < 1 || $month > 12) {
            $month = (int) now()->month;
        }

        if ($year < 2020 || $year > 2035) {
            $year = (int) now()->year;
        }

        // Standard Payroll Period calculation: 26th of (month - 1) to 25th of selected month
        $periodEnd = Carbon::createFromDate($year, $month, 25)->endOfDay();
        $periodStart = $periodEnd->copy()->subMonthNoOverflow()->day(26)->startOfDay();

        $startStr = $periodStart->format('Y-m-d');
        $endStr   = $periodEnd->format('Y-m-d');

        $selectedDate = Carbon::createFromDate($year, $month, 1);
        $prevDate = $selectedDate->copy()->subMonth();
        $nextDate = $selectedDate->copy()->addMonth();

        $arabicMonths = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];

        $startMonthName = $arabicMonths[$periodStart->month];
        $endMonthName   = $arabicMonths[$periodEnd->month];

        $selectedMonthLabel = "شهر {$endMonthName} {$year} (من 26 {$startMonthName} إلى 25 {$endMonthName})";

        // --- Monthly Activity Data (26th to 25th Payroll Period) ---
        // 1. Leaves (الإجازات)
        $leaves = Leave::where('user_id', $user->id)
            ->whereBetween('date', [$startStr, $endStr])
            ->orderBy('date', 'desc')
            ->get();
        $monthAcceptedLeavesDays = $leaves->where('status', 'accepted')->sum(function($l) {
            return (float) ($l->days_count ?? 0);
        });

        // 2. Overtime (الإضافي)
        $overtimes = Overtime::where('user_id', $user->id)
            ->whereBetween('date', [$startStr, $endStr])
            ->orderBy('date', 'desc')
            ->get();
        $monthAcceptedOvertimeHours = $overtimes->where('status', 'accepted')->sum(function($o) {
            return (float) ($o->total_hours ?? 0);
        });
        $monthOvertimeValue = ($user->hourly_rate > 0) ? ($monthAcceptedOvertimeHours * 1.5 * $user->hourly_rate) : 0;

        // 3. Permissions (الأذونات)
        $permissions = Permission::where('user_id', $user->id)
            ->whereBetween('date', [$startStr, $endStr])
            ->orderBy('date', 'desc')
            ->get();
        $monthPermissionMinutes = 0;
        foreach ($permissions as $p) {
            if ($p->status === 'accepted' && $p->from && $p->to) {
                $from = Carbon::parse($p->from);
                $to   = Carbon::parse($p->to);
                $monthPermissionMinutes += $from->diffInMinutes($to);
            }
        }
        $monthPermissionHours = round($monthPermissionMinutes / 60, 2);

        // 4. Penalties (الجزاءات)
        $penalties = Penalty::where('user_id', $user->id)
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->orderBy('created_at', 'desc')
            ->get();
        $monthAcceptedPenaltiesCount = $penalties->where('status', 'accepted')->count();
        $monthPenaltiesAmount = $penalties->where('status', 'accepted')->sum(function($p) {
            if (is_numeric($p->amount)) {
                return (float) $p->amount;
            }
            $extracted = preg_replace('/[^0-9.]/', '', (string) $p->amount);
            return is_numeric($extracted) ? (float) $extracted : 0;
        });

        // --- Annual Leave Balance Calculation ---
        $annualQuota = 14; // Official 14 days annual leave balance
        $yearUsedLeavesDays = Leave::where('user_id', $user->id)
            ->where(function($q) {
                $q->whereYear('date', now()->year)
                  ->orWhere('date', 'like', now()->year . '-%');
            })
            ->where('status', 'accepted')
            ->get()
            ->sum(function($l) {
                return (float) ($l->days_count ?? 0);
            });
        $remainingAnnualLeaves = max(0, $annualQuota - $yearUsedLeavesDays);

        $profileRoute = (request()->routeIs('employee_profiles.show') || Auth::id() !== $user->id)
            ? route('employee_profiles.show', $user->id)
            : route('my_profile');

        return view('employee_profiles.show', [
            'user' => $user,
            'year' => $year,
            'month' => $month,
            'selectedDate' => $selectedDate,
            'prevYear' => $prevDate->year,
            'prevMonth' => $prevDate->month,
            'nextYear' => $nextDate->year,
            'nextMonth' => $nextDate->month,
            'selectedMonthLabel' => $selectedMonthLabel,
            'arabicMonths' => $arabicMonths,
            'profileRoute' => $profileRoute,
            // Monthly Records & Totals
            'leaves' => $leaves,
            'monthAcceptedLeavesDays' => $monthAcceptedLeavesDays,
            'overtimes' => $overtimes,
            'monthAcceptedOvertimeHours' => $monthAcceptedOvertimeHours,
            'monthOvertimeValue' => $monthOvertimeValue,
            'permissions' => $permissions,
            'monthPermissionMinutes' => $monthPermissionMinutes,
            'monthPermissionHours' => $monthPermissionHours,
            'penalties' => $penalties,
            'monthAcceptedPenaltiesCount' => $monthAcceptedPenaltiesCount,
            'monthPenaltiesAmount' => $monthPenaltiesAmount,
            // Annual Leave Balance
            'annualQuota' => $annualQuota,
            'yearUsedLeavesDays' => $yearUsedLeavesDays,
            'remainingAnnualLeaves' => $remainingAnnualLeaves,
        ]);
    }

    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            abort(403, 'غير مصرح لك بالوصول لبروفايلات الموظفين');
        }

        $query = User::orderBy('name');

        if ($request->filled('user_id')) {
            $query->where('id', $request->user_id);
        }

        $users = $query->paginate(20)->withQueryString();
        $allUsers = User::orderBy('name')->get();

        return view('employee_profiles.index', compact('users', 'allUsers'));
    }

    public function edit($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            abort(403, 'غير مصرح لك بتعديل بروفايل الموظف');
        }

        $user = User::findOrFail($id);
        $stats = $this->getProfileStats($user);
        return view('employee_profiles.edit', compact('user', 'stats'));
    }

    public function update(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            abort(403, 'غير مصرح لك بتعديل بروفايل الموظف');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'job_title' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
        ], [
            'hourly_rate.required' => 'قيمة الساعة مطلوبة',
        ]);

        $user->update([
            'job_title' => $request->job_title,
            'hourly_rate' => $request->hourly_rate,
        ]);

        AuditLog::log(
            Auth::user()->name, 
            'Updated', 
            'Employee Profile', 
            $user->name, 
            "Job: {$request->job_title}, Rate: {$request->hourly_rate}"
        );

        return redirect()->route('employee_profiles.index')->with('success', "✅ تم تحديث بروفايل {$user->name} بنجاح!");
    }
}


