<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            (new UserController)->main();
            return $next($request);
        });
    }
    public function index()
    {
        $users = User::get();
        $roles = User::get();
        return view('admin.setting.user.profile.index', ['users' => $users, 'roles' => $roles]);
    }
}
