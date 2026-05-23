<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login User & Generate Sanctum Token
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات المدخلات غير صالحة',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات الدخول غير صحيحة'
            ], 401);
        }

        // Generate Token
        $token = $user->createToken('mobile-api-token')->plainTextToken;

        // Log the action
        AuditLog::log($user->name, 'Login (Mobile)', 'Auth', 'System');

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'data'    => [
                'token' => $token,
                'user'  => [
                    'id'          => $user->id,
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'role'        => $user->role ?? 'user',
                    'job_title'   => $user->job_title,
                    'hourly_rate' => $user->hourly_rate,
                ]
            ]
        ], 200);
    }

    /**
     * Register User (Web mirroring validation)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email',
            'job_title' => 'required|string',
            'password'  => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'فشل التحقق من البيانات',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'job_title' => $request->job_title,
            'password'  => bcrypt($request->password),
            'role'      => 'user', // Default role
        ]);

        if ($user) {
            AuditLog::log($user->name, 'Register (Mobile)', 'Auth', 'System');
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الحساب بنجاح، يمكنك الآن تسجيل الدخول'
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء التسجيل'
        ], 500);
    }

    /**
     * Get Authenticated User Info
     */
    public function user(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'role'        => $user->role ?? 'user',
                'job_title'   => $user->job_title,
                'hourly_rate' => $user->hourly_rate,
            ]
        ], 200);
    }

    /**
     * Logout User (Revoke Token)
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        
        // Revoke the token that was used to authenticate the request
        $user->currentAccessToken()->delete();

        AuditLog::log($user->name, 'Logout (Mobile)', 'Auth', 'System');

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج بنجاح'
        ], 200);
    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'فشل التحقق من صحة البيانات المعطاة',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة المرور الحالية غير صحيحة'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        AuditLog::log($user->name, 'Changed Own Password (Mobile)', 'Auth', $user->name);

        return response()->json([
            'success' => true,
            'message' => 'تم تعديل كلمة المرور بنجاح'
        ], 200);
    }
}
