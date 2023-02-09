<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            (new UserController)->main();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        if ($request->session()->has('full_name')) {
            return view('admin.dashboard.index');
        } else {
            return redirect('/');
        }
    }
}
