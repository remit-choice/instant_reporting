<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app(UserController::class)->main();
            return $next($request);
        });
    }
    public function admin_dashboard(Request $request)
    {
        if ($request->session()->has('full_name')) {
            return view('admin.dashboard.index');
        } else {
            return redirect('/');
        }
    }
}
