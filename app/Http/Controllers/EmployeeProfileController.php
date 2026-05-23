<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\Leave;
use App\Models\Permission;
use App\Models\Overtime;
use Illuminate\Support\Facades\Auth;
use App\Support\PayrollPeriod;
use Carbon\Carbon;

class EmployeeProfileController extends Controller
{
    protected $allowedEmails = ['z@z.z', 'Y@Y.Y', 'a@a.a'];

    private function hasAccess()
    {
        if (!Auth::check()) {
            return false;
        }

        return in_array(Auth::user()->email, $this->allowedEmails);
    }

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

    public function myProfile()
    {
        if (!$this->hasAccess()) {
            return view('coming_soon');
        }

        $user = Auth::user();
        $isFirstOfMonth = now()->day === 1;
        $previousCycleData = null;

        if ($isFirstOfMonth) {
            // Last completed cycle is the one that ended on the 25th of the previous month
            $referenceDate = now()->subMonthNoOverflow(); 
            $period = PayrollPeriod::current($referenceDate);
            
            $hours = Overtime::where('user_id', $user->id)
                ->where('status', 'accepted')
                ->whereBetween('date', [$period['start']->toDateString(), $period['end']->toDateString()])
                ->sum('total_hours');
                
            $value = 0;
            if ($user->hourly_rate > 0) {
                $value = $hours * 1.5 * $user->hourly_rate;
            }

            $previousCycleData = [
                'label' => $period['label'],
                'hours' => round($hours, 2),
                'value' => round($value, 2),
            ];
        }

        // Fetch history for the last 6 months
        $overtimeHistory = [];
        for ($i = 1; $i <= 6; $i++) {
            $refDate = now()->subMonthsNoOverflow($i);
            $period = PayrollPeriod::fromDate($refDate);
            
            $hours = Overtime::where('user_id', $user->id)
                ->where('status', 'accepted')
                ->whereBetween('date', [$period['start']->toDateString(), $period['end']->toDateString()])
                ->sum('total_hours');
                
            if ($hours > 0) {
                $value = 0;
                if ($user->hourly_rate > 0) {
                    $value = $hours * 1.5 * $user->hourly_rate;
                }
                
                $overtimeHistory[] = [
                    'label' => $period['label'],
                    'hours' => round($hours, 2),
                    'value' => round($value, 2),
                ];
            }
        }

        return view('employee_profiles.show', [
            'user' => $user,
            'isFirstOfMonth' => $isFirstOfMonth,
            'previousCycleData' => $previousCycleData,
            'overtimeHistory' => $overtimeHistory,
            'stats' => $this->getProfileStats($user),
        ]);
    }

    public function index(Request $request)
    {
        if (!$this->hasAccess()) {
            return view('coming_soon');
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
        if (!$this->hasAccess()) {
            return view('coming_soon');
        }
        $user = User::findOrFail($id);
        $stats = $this->getProfileStats($user);
        return view('employee_profiles.edit', compact('user', 'stats'));
    }

    public function update(Request $request, $id)
    {
        if (!$this->hasAccess()) {
            return view('coming_soon');
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
