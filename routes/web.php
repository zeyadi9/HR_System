<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\view_Overtime;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\SuperAdminEntryController;
use App\Http\Controllers\LeavePermissionController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\CheckInOutController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminNoteController;
use App\Http\Controllers\IncentiveController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\EmployeeProfileController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('login', [LoginController::class, 'login'])->name('login');
Route::post('login', [LoginController::class, 'login_post'])->name('login_post');

Route::get('register', [LoginController::class, 'register'])->name('register');
Route::post('register', [LoginController::class, 'register_post'])->name('register_post');

Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('logout', [LoginController::class, 'logout']);


// ==========================================
// يوزر + ادمن + سوبر ادمن (كل المسجلين)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // اضافي
    Route::get('/overtime', [OvertimeController::class, 'overtime'])->name('Overtime');
    Route::post('/overtime_post', [OvertimeController::class, 'overtime_post'])->name('overtime_post');
    Route::get('/view_overtime', [view_Overtime::class, 'view_Overtime'])->name('view_Overtime');

    // اجازات
    Route::get('/leave', [LeavePermissionController::class, 'leave_index'])->name('leave');
    Route::post('/leave', [LeavePermissionController::class, 'leave_post'])->name('leave_post');
    Route::get('/view_leave', [LeavePermissionController::class, 'view_leave'])->name('view_leave');

    // اذونات
    Route::get('/permission', [LeavePermissionController::class, 'permission_index'])->name('permission');
    Route::post('/permission', [LeavePermissionController::class, 'permission_post'])->name('permission_post');
    Route::get('/view_permission', [LeavePermissionController::class, 'view_permission'])->name('view_permission');

    // حضور وانصراف يدوي
    Route::get('/check-in-out', [CheckInOutController::class, 'index_front'])->name('check_in_out');
    Route::post('/check-in-out', [CheckInOutController::class, 'store'])->name('check_in_out_post');
    Route::get('/view-check-in-out', [CheckInOutController::class, 'index_view'])->name('view_check_in_out');

    // جزاءات - عرض (للكل)
    Route::get('/my-penalties', [PenaltyController::class, 'view_penalties'])->name('view_penalties');

    // تسويات - للموظف والكل
    Route::get('/settlement', [SettlementController::class, 'create'])->name('settlements.create');
    Route::post('/settlement', [SettlementController::class, 'store'])->name('settlements.store');
    Route::get('/view_settlements', [SettlementController::class, 'index'])->name('settlements.index');

    // بروفايلي الشخصي
    Route::get('/my-profile', [EmployeeProfileController::class, 'myProfile'])->name('my_profile');

    // صفحة الإشعارات
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // جلب الإشعارات غير المقروءة للموبايل
    Route::post('/poll-notifications', function (\Illuminate\Http\Request $request) {
        if (auth()->check()) {
            $user = auth()->user();
            $notifications = $user->unreadNotifications;
            
            // تحويل الإشعارات لمصفوفة بسيطة
            $data = $notifications->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->data['title'] ?? 'إشعار جديد',
                    'message' => $notif->data['message'] ?? '',
                ];
            });

            // تعليم الإشعارات كمقروءة فور جلبها للموبايل
            if ($notifications->count() > 0) {
                $user->unreadNotifications->markAsRead();
            }

            return response()->json(['status' => 'success', 'notifications' => $data]);
        }
        return response()->json(['status' => 'error'], 400);
    })->name('poll_notifications');
});


// ==========================================
// ادمن + سوبر ادمن: قبول/رفض الطلبات + إضافة جزاء
// ==========================================
Route::middleware(['auth', 'admin'])->group(function () {

    // قبول/رفض اضافي
    Route::post('/overtime/{id}/accept', [OvertimeController::class, 'accept'])->name('overtime.accept');
    Route::post('/overtime/{id}/refuse', [OvertimeController::class, 'refuse'])->name('overtime.refuse');

    // قبول/رفض اجازات
    Route::post('/leave/{id}/accept', [LeavePermissionController::class, 'leave_accept'])->name('leave.accept');
    Route::post('/leave/{id}/refuse', [LeavePermissionController::class, 'leave_refuse'])->name('leave.refuse');

    // قبول/رفض اذونات
    Route::post('/permission/{id}/accept', [LeavePermissionController::class, 'permission_accept'])->name('permission.accept');
    Route::post('/permission/{id}/refuse', [LeavePermissionController::class, 'permission_refuse'])->name('permission.refuse');

    // قبول/رفض الحضور والانصراف اليدوي
    Route::post('/check-in-out/{id}/accept', [CheckInOutController::class, 'accept'])->name('check_in_out.accept');
    Route::post('/check-in-out/{id}/refuse', [CheckInOutController::class, 'refuse'])->name('check_in_out.refuse');

    // إضافة جزاء (بدون قبول/رفض للادمن العادي)
    Route::get('/admin/penalties', [PenaltyController::class, 'index'])->name('admin.penalties');
    Route::post('/admin/penalties', [PenaltyController::class, 'store'])->name('admin.penalties.store');
});


// ==========================================
// سوبر ادمن فقط: export + reset password + قبول/رفض جزاءات
// ==========================================
Route::middleware(['auth', 'super_admin'])->group(function () {

    // تصدير Excel
    Route::get('/overtime/export', [OvertimeController::class, 'export'])->name('overtime.export');
    Route::get('/leaves/export', [LeavePermissionController::class, 'export_lev'])->name('leave.export');
    Route::get('/permissions/export', [LeavePermissionController::class, 'export_per'])->name('permission.export');
    Route::get('/penalties/export', [PenaltyController::class, 'export'])->name('penalties.export');
    Route::get('/check-in-out/export', [CheckInOutController::class, 'export'])->name('check_in_out.export');

    // إعادة ضبط كلمة السر
    Route::get('/admin/reset-password', [LoginController::class, 'reset_password_index'])->name('admin.reset_password');
    Route::post('/admin/reset-password', [LoginController::class, 'reset_password_post'])->name('admin.reset_password.post');

    // قبول/رفض الجزاءات
    Route::post('/penalties/{id}/accept', [PenaltyController::class, 'accept'])->name('penalties.accept');
    Route::post('/penalties/{id}/refuse', [PenaltyController::class, 'refuse'])->name('penalties.refuse');

    // إضافة يدوية للموظفين غير المسجلين
    Route::get('/super-admin/entry', [SuperAdminEntryController::class, 'index'])->name('super_admin.entry');
    Route::post('/super-admin/entry', [SuperAdminEntryController::class, 'store'])->name('super_admin.entry.store');

    // Audit Log - Restricted to tech admin inside controller
    Route::get('/audit-log', [AuditController::class, 'index'])->name('audit_log');

    // التقارير الشاملة
    Route::get('/full-report', [ReportController::class, 'index'])->name('full_report.index');
    Route::get('/full-report/export', [ReportController::class, 'export'])->name('full_report.export');

    // ملاحظات الإدارة
    Route::get('/admin-notes/create', [AdminNoteController::class, 'create'])->name('admin_notes.create');
    Route::post('/admin-notes/store', [AdminNoteController::class, 'store'])->name('admin_notes.store');
    Route::get('/admin-notes', [AdminNoteController::class, 'index'])->name('admin_notes.index');
    Route::get('/admin-notes/export', [AdminNoteController::class, 'export'])->name('admin_notes.export');

    // الحوافز
    Route::get('/incentives/create', [IncentiveController::class, 'create'])->name('incentives.create');
    Route::post('/incentives/store', [IncentiveController::class, 'store'])->name('incentives.store');
    Route::get('/incentives', [IncentiveController::class, 'index'])->name('incentives.index');
    Route::get('/incentives/export', [IncentiveController::class, 'export'])->name('incentives.export');

    // قبول/رفض التسويات
    Route::post('/settlements/{id}/accept', [SettlementController::class, 'accept'])->name('settlements.accept');
    Route::post('/settlements/{id}/refuse', [SettlementController::class, 'refuse'])->name('settlements.refuse');
    Route::get('/settlements/export', [SettlementController::class, 'export'])->name('settlements.export');
    // بروفايل الموظفين
    Route::get('/employee-profiles', [EmployeeProfileController::class, 'index'])->name('employee_profiles.index');
    Route::get('/employee-profiles/{id}/edit', [EmployeeProfileController::class, 'edit'])->name('employee_profiles.edit');
    Route::post('/employee-profiles/{id}/update', [EmployeeProfileController::class, 'update'])->name('employee_profiles.update');
});