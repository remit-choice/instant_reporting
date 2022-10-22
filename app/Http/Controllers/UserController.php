<?php

namespace App\Http\Controllers;

use App\Models\ModulesGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->main();
            return $next($request);
        });
    }
    public function main()
    {
        $r_id = Session::get('r_id');
        $modules = function ($query) use ($r_id) {
            $query->where('permissions.r_id', $r_id);
        };
        $module_groups = ModulesGroup::whereHas('modules.permissions', $modules)->orwhereDoesntHave('modules.permissions', $modules)
            ->with('modules.permissions', $modules)
            ->orderBy("sort")
            ->get();
        // dd($module_groups->toArray());
        View::share(['module_groups' => $module_groups]);
    }
}
