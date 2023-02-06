<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ModulesGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Session;

// use Illuminate\Support\Facades\Request;

class LoginController extends Controller
{
    public function admin_login(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            if (Session::has('r_id') && Session::get('status') == 1) {
                return redirect()->route('admin.dashboard');
            } else {
                return view('admin.auth.login');
            }
        } elseif (FacadesRequest::isMethod('post')) {
            $request->validate(
                [
                    'email' => 'required|string|email|max:255',
                    'password' => 'required|string|min:6|max:255',
                ],
                [
                    'email.required' => "*Email is required",
                    'password.required' => "*Password is required",
                ]
            );
            $email = $request->email;
            $password = $request->password;
            $user = User::where('email', '=', $request->input('email'))->first();
            if ($user === null) {
                return back()->with('failed', "Email doesn't Exist");
            } else if (!Hash::check($password, $user->password) && ($password != '')) {
                return back()->with('failed', "Login Fail, pls check password")->with('email', $email);
            } else {
                $email = $user->email;
                $r_id = $user->r_id;
                $module_groups =  ModulesGroup::with('modules')->get();
                if ($request->has('RememberMe')) {
                    $this->admin_login_sessions($request);
                    $email_cookie =  Cookie::make('emailcookie', $email, time() + 86400);
                    $password_cookie = Cookie::make('passwordcookie', $password, time() + 86400);

                    return redirect()->route('admin.dashboard.index')->with('module_groups', $module_groups)->withCookie($email_cookie)->withCookie($password_cookie);
                } else {
                    $this->admin_login_sessions($request);
                    return redirect()->route('admin.dashboard.index')->with('module_groups', $module_groups);
                }
            }
        } else {
        }
    }
    public function admin_login_sessions($request)
    {
        $user = User::where('email', '=', $request->input('email'))->first();
        $full_name = $user->full_name;
        $user_name = $user->user_name;
        $image = $user->image;
        $email = $user->email;
        $r_id = $user->r_id;
        $u_id = $user->id;
        $designation = $user->designation;
        User::where('email', $email)
            ->update(
                [
                    'status' => 1,
                ]
            );

        $selectStatus = User::where('email', '=', $email)->first();
        $request->session()->put('full_name', $full_name);
        $request->session()->put('user_name', $user_name);
        $request->session()->put('image',  $image);
        $request->session()->put('email',  $email);
        $request->session()->put('r_id',  $r_id);
        $request->session()->put('u_id',  $u_id);
        $request->session()->put('designation',  $designation);
        $request->session()->put('session_time',  time());
        $request->session()->put('status',  $selectStatus->status);
    }
}
