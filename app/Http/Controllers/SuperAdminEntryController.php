<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Overtime;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class SuperAdminEntryController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('super_admin_entry', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_type'  => 'required|in:overtime,leave,permission',
            'employee_name' => 'required|string|max:255',
            'date'        => 'required|date',
            'day'         => 'required|string|max:50',
            'reason'      => 'required|string|max:1000',
        ]);

        // Find user by name if they exist in system
        $user = User::where('name', $request->employee_name)->first();
        $userId = $user?->id; // null if not in system

        $type = $request->entry_type;

        if ($type === 'overtime') {
            $request->validate([
                'from'       => 'required|date_format:H:i',
                'to'         => 'required|date_format:H:i|after:from',
            ]);

            $from = \Carbon\Carbon::createFromFormat('H:i', $request->from);
            $to   = \Carbon\Carbon::createFromFormat('H:i', $request->to);
            $totalHours = round($from->diffInMinutes($to) / 60, 2);

            Overtime::create([
                'user_id'     => $userId,
                'name'        => $request->employee_name,
                'date'        => $request->date,
                'day'         => $request->day,
                'reason'      => $request->reason,
                'from'        => $request->from,
                'to'          => $request->to,
                'total_hours' => $totalHours,
                'status'      => 'accepted',
                'actioned_by' => Auth::user()->name,
            ]);

            AuditLog::log(Auth::user()->name, 'Created', 'Overtime', $request->employee_name, "Manual Entry - Hours: {$totalHours}");

            return redirect()->route('super_admin.entry')->with('success', "✅ تم إضافة الإضافي لـ {$request->employee_name} بنجاح!");
        }

        if ($type === 'leave') {
            $request->validate([
                'substitute' => 'required|string|max:255',
                'days_count' => 'required|integer|min:1',
            ]);

            Leave::create([
                'user_id'    => $userId,
                'name'       => $request->employee_name,
                'date'       => $request->date,
                'day'        => $request->day,
                'reason'     => $request->reason,
                'substitute' => $request->substitute,
                'days_count' => $request->days_count,
                'status'     => 'accepted',
                'actioned_by' => Auth::user()->name,
            ]);

            AuditLog::log(Auth::user()->name, 'Created', 'Leave', $request->employee_name, "Manual Entry - Days: {$request->days_count}");

            return redirect()->route('super_admin.entry')->with('success', "✅ تم إضافة الإجازة لـ {$request->employee_name} بنجاح!");
        }

        if ($type === 'permission') {
            $request->validate([
                'permission_type' => 'required|string|in:إذن تأخير,إذن انصراف باكر,إذن نسيان بصمة حضور,إذن نسيان بصمة انصراف',
                'perm_from'       => 'nullable|date_format:H:i',
                'perm_to'         => 'nullable|date_format:H:i|after:perm_from',
            ]);

            Permission::create([
                'user_id'         => $userId,
                'name'            => $request->employee_name,
                'date'            => $request->date,
                'day'             => $request->day,
                'reason'          => $request->reason,
                'from'            => $request->perm_from,
                'to'              => $request->perm_to,
                'permission_type' => $request->permission_type,
                'status'          => 'accepted',
                'actioned_by'     => Auth::user()->name,
            ]);

            AuditLog::log(Auth::user()->name, 'Created', 'Permission', $request->employee_name, "Manual Entry - Type: {$request->permission_type}");

            return redirect()->route('super_admin.entry')->with('success', "✅ تم إضافة الإذن لـ {$request->employee_name} بنجاح!");
        }

        return redirect()->back()->withErrors(['error' => 'نوع الإدخال غير صالح']);
    }
}
