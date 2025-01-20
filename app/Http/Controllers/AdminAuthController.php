<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    protected $username;

    public function __construct()
    {
        $this->middleware('admin.guest')->except('logout', 'logoutGet');
        $this->username = $this->findUsername();
    }

    public function showLoginForm()
    {
        $data['page_title'] = "Admin Login";
        return view('auth.admin_login', $data);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        return $this->authenticated($request);
    }

    protected function validateLogin(Request $request)
    {
        $validation_rule = [
            $this->username() => 'required|string',
            'password' => 'required|string'
        ];

        $request->validate($validation_rule);
    }

    public function authenticated($request)
    {
        $credentials = $request->only($this->username(), 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $user = auth()->guard('admin')->user();
            return redirect()->route('admin.dashboard');
        }

        return back()->withError('User not found.');
    }

    public function findUsername()
    {
        $login = request()->input('username');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->withSuccess('You have been logged out.');
    }
}
