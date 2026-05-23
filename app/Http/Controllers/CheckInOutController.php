<?php

namespace App\Http\Controllers;

use App\Models\CheckInOut;
use App\Models\AuditLog;
use App\Models\User;
use App\Support\PayrollPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInOutController extends Controller
{
    public function index_front()
    {
        $todayRecords = CheckInOut::where('user_id', Auth::id())
            ->where('date', now()->toDateString())
            ->get();

        $hasCheckedIn = $todayRecords->where('type', 'حضور')->isNotEmpty();
        $hasCheckedOut = $todayRecords->where('type', 'انصراف')->isNotEmpty();

        return view('check_in_out', compact('hasCheckedIn', 'hasCheckedOut'));
    }

    public function store(Request $request)
    {
        if (!(Auth::user()->role === 'super_admin' || 
              Auth::user()->email === 'hend@gama.com' || 
              Auth::user()->email === 'RawanEssam@gamma.com' || 
              Auth::user()->email === 'esraa.abdulla30@gmail.com')) {
            return redirect()->route('home')->withErrors(['error' => 'عفواً، لا تملك صلاحية القيام بهذا الإجراء.']);
        }

        $request->validate([
            'type' => 'required|in:حضور,انصراف',
        ]);

        $name = Auth::user()->name;

        // Check if movement already exists for today
        $existing = CheckInOut::where('user_id', Auth::id())
            ->where('date', now()->toDateString())
            ->where('type', $request->type)
            ->first();

        if ($existing) {
            return redirect()->back()->withErrors(['error' => 'لقد قمت بتسجيل ' . $request->type . ' بالفعل اليوم.']);
        }
        
        $attendance = CheckInOut::create([
            'user_id' => Auth::id(),
            'name' => $name,
            'date' => now()->toDateString(),
            'day' => now()->locale('ar')->dayName,
            'type' => $request->type,
            'status' => 'accepted',
            'actioned_by' => 'نظام تلقائي',
        ]);

        if ($attendance) {
            AuditLog::log(Auth::user()->name, 'Created', 'CheckInOut', $name, "Type: {$request->type}, Date: " . now()->toDateString());
            return redirect()->route('home')->with('success', 'تم تسجيل ال' . $request->type . ' بنجاح!');
        }

        return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء التسجيل']);
    }

    public function index_view(Request $request)
    {
        $period = PayrollPeriod::fromRequest($request->query('period_start'));
        $periodStart = $period['start'];
        $periodEnd = $period['end'];

        $query = CheckInOut::whereBetween('date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->orderByDesc('date')
            ->orderByDesc('created_at');

        $users = [];

        if (Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin') {
            $users = User::orderBy('name')->get();
            if ($request->has('user_id') && $request->user_id) {
                $query->where('user_id', $request->user_id);
            }
        } else {
            $query->where('user_id', Auth::id());
        }

        $records = $query->get();

        // Summary per employee this cycle
        $summaryQuery = CheckInOut::whereBetween('date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->where('status', 'accepted');

        if (in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            if ($request->has('user_id') && $request->user_id) {
                $summaryQuery->where('user_id', $request->user_id);
            }
        } else {
            $summaryQuery->where('user_id', Auth::id());
        }

        $cycleSummary = $summaryQuery
            ->selectRaw("name, SUM(CASE WHEN type = 'حضور' THEN 1 ELSE 0 END) as check_in_count, SUM(CASE WHEN type = 'انصراف' THEN 1 ELSE 0 END) as check_out_count, COUNT(*) as total")
            ->groupBy('name')
            ->orderByDesc('total')
            ->get();

        $olderPendingCount = in_array(Auth::user()->role, ['admin', 'super_admin'])
            ? CheckInOut::where('status', 'pending')
                ->whereDate('date', '<', $periodStart->toDateString())
                ->count()
            : 0;

        return view('view_check_in_out', [
            'records' => $records,
            'users' => $users,
            'cycleSummary' => $cycleSummary,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'periodLabel' => $period['label'],
            'selectedPeriodStart' => $period['slug'],
            'previousPeriodStart' => $period['previous_start']->format('Y-m-d'),
            'nextPeriodStart' => $period['next_start']->format('Y-m-d'),
            'isCurrentPeriod' => $period['is_current'],
            'olderPendingCount' => $olderPendingCount,
        ]);
    }

    public function accept($id)
    {
        if (!(Auth::user()->role === 'super_admin' || 
              Auth::user()->email === 'hend@gama.com' || 
              Auth::user()->email === 'RawanEssam@gamma.com' || 
              Auth::user()->email === 'esraa.abdulla30@gmail.com')) {
            return redirect()->route('home')->withErrors(['error' => 'عفواً، لا تملك صلاحية القيام بهذا الإجراء.']);
        }

        $record = CheckInOut::findOrFail($id);
        $record->update([
            'status'      => 'accepted',
            'actioned_by' => Auth::user()->name,
        ]);

        AuditLog::log(Auth::user()->name, 'Accepted', 'CheckInOut', $record->name, "Type: {$record->type}, Date: {$record->date}");

        return redirect()->route('view_check_in_out')->with('success', 'تم قبول الحركة.');
    }

    public function refuse(Request $request, $id)
    {
        if (!(Auth::user()->role === 'super_admin' || 
              Auth::user()->email === 'hend@gama.com' || 
              Auth::user()->email === 'RawanEssam@gamma.com' || 
              Auth::user()->email === 'esraa.abdulla30@gmail.com')) {
            return redirect()->route('home')->withErrors(['error' => 'عفواً، لا تملك صلاحية القيام بهذا الإجراء.']);
        }

        $request->validate(['refuse_reason' => 'required|string|max:500']);
        
        $record = CheckInOut::findOrFail($id);
        $record->update([
            'status'        => 'refused',
            'refuse_reason' => $request->refuse_reason,
            'actioned_by'   => Auth::user()->name,
        ]);

        AuditLog::log(Auth::user()->name, 'Refused', 'CheckInOut', $record->name, "Reason: {$request->refuse_reason}, Date: {$record->date}");

        return redirect()->route('view_check_in_out')->with('success', 'تم رفض الحركة.');
    }

    public function export(Request $request)
    {
        if (Auth::user()->role !== 'super_admin') abort(403);

        $period = PayrollPeriod::fromRequest($request->query('period_start'));
        $periodStart = $period['start'];
        $periodEnd = $period['end'];

        $records = CheckInOut::whereBetween('date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->get();

        $summary = CheckInOut::whereBetween('date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->where('status', 'accepted')
            ->selectRaw("name, SUM(CASE WHEN type = 'حضور' THEN 1 ELSE 0 END) as check_in_count, SUM(CASE WHEN type = 'انصراف' THEN 1 ELSE 0 END) as check_out_count, COUNT(*) as total")
            ->groupBy('name')
            ->orderByDesc('total')
            ->get();

        $filename = 'check_in_out_' . $periodStart->format('Y_m_d') . '_to_' . $periodEnd->format('Y_m_d') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\CheckInOutExport($records, $summary, $periodStart, $periodEnd),
            $filename
        );
    }
}
