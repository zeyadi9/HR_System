<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // جلب الإشعارات مرتبة من الأحدث للأقدم مع Pagination
        $notifications = $user->notifications()->paginate(15);
        
        return view('notifications', compact('notifications'));
    }
}
