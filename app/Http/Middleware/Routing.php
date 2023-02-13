<?php

namespace App\Http\Middleware;

use App\Models\ModulesGroup;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class Routing
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('session_time')) {
            $session_time = Session::get('session_time');
            $current_time = time();
            $inactive = 3600;
            if ($current_time - $session_time > $inactive) {
                $r_id = Session::get('r_id');
                User::where('r_id', $r_id)->update(['status' => 0]);
                $request->session()->flush();
                return redirect('/admin');
            } else {
                if (Session::has('full_name')) {
                    // $url = route($request->url, ['id']);
                    $REQUEST_URI = '/' . Route::current(['id'])->uri();
                    $modules_urls = function ($query) use ($REQUEST_URI) {
                        $query->where('url', $REQUEST_URI);
                        $query->orderBy('id', 'ASC');
                        $query->where('status', '1');
                    };
                    // dd($REQUEST_URI);
                    $r_id = Session::get('r_id');
                    $permissions = function ($query) use ($r_id) {
                        $query->where('r_id', $r_id);
                        $query->orderBy('id', 'ASC');
                    };
                    $ordeing = function ($query) {
                        $query->orderBy('id', 'ASC');
                        $query->where('status', '1');
                    };
                    $module_groups = ModulesGroup::whereHas('modules', $ordeing)->whereHas('modules.permissions', $permissions)->whereHas('modules.modules_urls', $modules_urls)
                        ->with('modules', $ordeing)
                        ->with('modules.permissions', $permissions)
                        ->with('modules.modules_urls', $modules_urls)
                        ->get();
                    // dd(
                    //     $module_groups->toArray()
                    // );
                    $response = response(view('errors.404'), 404);
                    foreach ($module_groups as $module_group) {
                        foreach ($module_group->modules as $module) {
                            if (!empty($module->modules_urls)) {
                                foreach ($module->modules_urls as $modules_url) {
                                    if (!empty($module->permissions) && $module->id == $module->permissions['m_id'] && $module->permissions['view'] == 1 && $modules_url->url == $REQUEST_URI) {
                                        $create = $module->permissions['add'];
                                        $edit = $module->permissions['edit'];
                                        $delete = $module->permissions['delete'];
                                        $module_name = $module->name;
                                        View::share(['create' => $create, 'edit' => $edit, 'delete' => $delete, 'module_name' => $module_name]);
                                        $response = $next($request);
                                    } else {
                                        $response;
                                    }
                                }
                            } else {
                                $response;
                            }
                        }
                    }

                    // $response = $next($request);

                    return $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
                }
            }
        } else {
            return redirect()->route('admin.login');
        }
    }
}
