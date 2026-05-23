<?php


namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class LoginController extends Controller
{
    function login(){
        return view('login');
    }
    function register(){
        return view('register');
    }


    function register_post(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'job_title' => 'required|string',
            'password' => 'required|min:6'
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['job_title'] = $request->job_title;
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);
        if($user){
            return redirect()->intended(route('login'))->with('success','Registration successful, you can now login');
            }
            return redirect()->intended(route('register'))->withErrors(['email'=>'Registration failed']);
    }

function login_post(Request $request){
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if(Auth::attempt($credentials)){
        $request->session()->regenerate();
        
        AuditLog::log(Auth::user()->name, 'Login', 'Auth', 'System');

        if(Auth::user()->role === 'admin'){
            return redirect()->route('home');
        } else {
            return redirect()->route('home');
        }
    }

    return back()->withErrors(['email' => 'Invalid credentials.']);
}

public function reset_password_index()
{
    if (Auth::user()->role !== 'super_admin') {
        abort(403);
    }
    $users = User::orderBy('name')->get();
    return view('admin_reset_password', compact('users'));
}

public function reset_password_post(Request $request)
{
    if (Auth::user()->role !== 'super_admin') {
        abort(403);
    }
    $request->validate([
        'user_id'  => 'required|exists:users,id',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $u = User::findOrFail($request->user_id);
    $u->update([
        'password' => Hash::make($request->password),
    ]);

    AuditLog::log(Auth::user()->name, 'Changed Password', 'Auth', $u->name);

    return redirect()->back()->with('success', 'Password reset successfully!');
}

function logout(Request $request){
    AuditLog::log(Auth::user()->name, 'Logout', 'Auth', 'System');
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}


}

// change password
// $user = App\Models\User::where('email', 'email@email.com')->first();
// $user->password = Hash::make('newpass123');
// $user->save();