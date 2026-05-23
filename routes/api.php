<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\PenaltyController;
use App\Http\Controllers\Api\AdminReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes (Requires Sanctum Token)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth & General User info
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // ----------------------------------------------------
    // Employee Routes (بوابة الموظف)
    // ----------------------------------------------------
    
    // Attendance (حضور وانصراف)
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('/attendance', [AttendanceController::class, 'store']);

    // Overtime (إضافي)
    Route::get('/overtime', [RequestController::class, 'overtimeIndex']);
    Route::post('/overtime', [RequestController::class, 'overtimeStore']);

    // Leaves (إجازات)
    Route::get('/leaves', [RequestController::class, 'leaveIndex']);
    Route::post('/leaves', [RequestController::class, 'leaveStore']);

    // Permissions (أذونات)
    Route::get('/permissions', [RequestController::class, 'permissionIndex']);
    Route::post('/permissions', [RequestController::class, 'permissionStore']);

    // Penalties (جزاءات)
    Route::get('/penalties', [PenaltyController::class, 'index']);

    // Settlements (تسويات)
    Route::get('/settlements', [PenaltyController::class, 'settlementIndex']);
    Route::post('/settlements', [PenaltyController::class, 'settlementStore']);

    // Incentives (حوافز)
    Route::get('/incentives', [AdminReportController::class, 'incentiveIndex']);

    // Admin Notes (ملاحظات الإدارة)
    Route::get('/admin-notes', [AdminReportController::class, 'notesIndex']);


    // ----------------------------------------------------
    // Admin Routes (بوابة المدير - admin / super_admin)
    // ----------------------------------------------------
    Route::middleware(['admin'])->group(function () {
        
        // Approve/Reject Overtime
        Route::get('/admin/overtime', [RequestController::class, 'adminOvertimeIndex']);
        Route::post('/admin/overtime/{id}/accept', [RequestController::class, 'overtimeAccept']);
        Route::post('/admin/overtime/{id}/refuse', [RequestController::class, 'overtimeRefuse']);

        // Approve/Reject Leaves
        Route::get('/admin/leaves', [RequestController::class, 'adminLeaveIndex']);
        Route::post('/admin/leaves/{id}/accept', [RequestController::class, 'leaveAccept']);
        Route::post('/admin/leaves/{id}/refuse', [RequestController::class, 'leaveRefuse']);

        // Approve/Reject Permissions
        Route::get('/admin/permissions', [RequestController::class, 'adminPermissionIndex']);
        Route::post('/admin/permissions/{id}/accept', [RequestController::class, 'permissionAccept']);
        Route::post('/admin/permissions/{id}/refuse', [RequestController::class, 'permissionRefuse']);

        // Approve/Reject Check-In/Out (Attendance)
        Route::get('/admin/attendance', [AttendanceController::class, 'adminIndex']);
        Route::post('/admin/attendance/{id}/accept', [AttendanceController::class, 'accept']);
        Route::post('/admin/attendance/{id}/refuse', [AttendanceController::class, 'refuse']);

        // Add Penalty directly
        Route::post('/admin/penalties', [PenaltyController::class, 'store']);
    });


    // ----------------------------------------------------
    // Super Admin Routes (بوابة المدير العام)
    // ----------------------------------------------------
    Route::middleware(['super_admin'])->group(function () {
        
        // Approve/Reject Penalties
        Route::get('/super-admin/penalties', [PenaltyController::class, 'superAdminIndex']);
        Route::post('/super-admin/penalties/{id}/accept', [PenaltyController::class, 'penaltyAccept']);
        Route::post('/super-admin/penalties/{id}/refuse', [PenaltyController::class, 'penaltyRefuse']);

        // Approve/Reject Settlements
        Route::get('/super-admin/settlements', [PenaltyController::class, 'superAdminSettlementIndex']);
        Route::post('/super-admin/settlements/{id}/accept', [PenaltyController::class, 'settlementAccept']);
        Route::post('/super-admin/settlements/{id}/refuse', [PenaltyController::class, 'settlementRefuse']);

        // Super Admin manual entry for employee register
        Route::post('/super-admin/employee-entry', [AdminReportController::class, 'employeeEntry']);

        // Employee Profiles management
        Route::get('/super-admin/employee-profiles', [AdminReportController::class, 'profilesIndex']);
        Route::get('/super-admin/employee-profiles/{id}', [AdminReportController::class, 'profileShow']);
        Route::post('/super-admin/employee-profiles/{id}/update', [AdminReportController::class, 'profileUpdate']);
        Route::post('/super-admin/employee-profiles/{id}/reset-password', [AdminReportController::class, 'resetPassword']);

        // Audit Logs & Reports
        Route::get('/super-admin/audit-logs', [AdminReportController::class, 'auditLogs']);
        Route::get('/super-admin/full-report', [AdminReportController::class, 'fullReport']);

        // Manage Notes
        Route::post('/super-admin/notes', [AdminReportController::class, 'notesStore']);

        // Manage Incentives
        Route::post('/super-admin/incentives', [AdminReportController::class, 'incentiveStore']);
    });

});
