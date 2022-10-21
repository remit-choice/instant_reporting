<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request as FacadesRequest;

class RegisterController extends Controller
{
    public function create_user(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            $roles =  Role::where([['id', '!=', 1], ['id', '!=', 3]])->get();
            return view('admin.user.create')->with(['roles' => $roles]);
        } elseif (FacadesRequest::isMethod('post')) {
            $request->validate(
                [
                    'full_name' => 'required|string|min:3|max:255',
                    'user_name' => 'required|string|min:3|max:255',
                    'email' => 'required|string|email|max:255',
                    'password' => 'required|string|min:3|max:255',
                    'designation' => 'required|string|min:3|max:255',
                ],
                [
                    'full_name.required' => '*required',
                    'user_name.required' => '*required',
                    'email.required' => '*required',
                    'password.required' => '*required',
                    'designation.required' => '*required',
                ]
            );
            $full_name = $request->full_name;
            $user_name = $request->user_name;
            $email = $request->email;
            $password = $request->password;
            $Roll = $request->Roll;
            $designation = $request->designation;
            $Hashpassword = Hash::make($password);
            $token = bin2hex(random_bytes(15));
            $users_count = User::where('email', '=', $email)
                ->count();
            if ($users_count > 0) {
                return back()->with('failed', "email already Exist");
            } else {
                if ($password != '') {
                    User::insert([
                        'user_cat' => '1',
                        'r_id' => $Roll,
                        'full_name' => $full_name,
                        'user_name' => $user_name,
                        'email' => $email,
                        'password' => $Hashpassword,
                        'designation' => $designation,
                        'token' => $token,
                        'status' => '0',

                    ]);
                    $users =  User::get();
                    return redirect('/admin/user/index')->with(['users' => $users]);
                }
            }
        } else {
        }
    }
}
