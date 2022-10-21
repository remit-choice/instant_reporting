<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModulesGroup;
use App\Models\ModulesUrl;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class ModulesController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app(UserController::class)->main();
            return $next($request);
        });
    }

    public function admin_module_list()
    {
        $roles = Role::get();
        $modules = Module::with('modules_group')->get();
        $modules_groups = ModulesGroup::get();
        // $modules_groups = ModulesGroup::whereHas('modules')->with('modules')->get();
        // $modules_groups = ModulesGroup::whereHas('modules')->with('modules')->get();
        // dd($modules->toArray());
        return view('admin.setting.module.index', ['roles' => $roles, 'modules_groups' => $modules_groups, 'modules' => $modules]);
    }
    public function add_admin_module(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            if ($request->name && $request->m_g_id) {
                $name = $request->name;
                $m_g_id = $request->m_g_id;

                $modules = Module::where([['m_g_id', '=', $m_g_id], ['name', '=', $name]])->count();
                // dd($modules);
                if ($modules > 0) {
                } else {
                    return "OK";
                }
            } else {
                return back();
            }
        } elseif (FacadesRequest::isMethod('post')) {
            $m_g_id = $request->m_g_id;
            $name = $request->name;
            $icon = $request->icon;
            $type = $request->type;


            if (isset($request->status)) {
                $status = $request->status;
            } else {
                $status = 0;
            }
            // dd($m_g_id);
            Module::create([
                'm_g_id' => $m_g_id,
                'name' => $name,
                'icon' => $icon,
                'status' => $status,
                'type' => $type,
            ]);
        } else {
        }
    }
    public function edit_admin_module(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            if ($request->name && $request->m_g_id) {
                $name = $request->name;
                $m_g_id = $request->m_g_id;
                $modules = Module::where([['name', '=', $name], ['m_g_id', '=', $m_g_id]])->count();
                if ($modules > 0) {
                } else {
                    return "OK";
                }
            } else {
                return back();
            }
        } elseif (FacadesRequest::isMethod('post')) {
            $id = $request->id;
            $name = $request->name;
            $icon = $request->icon;
            $m_g_id = $request->m_g_id;
            if (isset($request->status)) {
                $status = $request->status;
            } else {
                $status = 0;
            }
            $type = $request->type;

            Module::where('id', $id)->update([
                'name' => $name,
                'icon' => $icon,
                'm_g_id' => $m_g_id,
                'status' => $status,
                'type' => $type,
            ]);
            $roles = Role::get();
            $modules = Module::with('modules_group')->get();
            $modules_groups = ModulesGroup::get();
            return redirect('/admin/setting/module')->with(['roles' => $roles, 'modules_groups' => $modules_groups, 'modules' => $modules]);
        } else {
            return back();
        }
    }
    public function delete_admin_module(Request $request)
    {
        $id = $request->id;
        Module::where('id', $id)->delete();
    }

    //Modules Url
    public function admin_module_url_list(Request $request)
    {
        $id = $request->id;
        $request->session()->pull('m_id');
        $request->session()->put('m_id', $id);
        $modules = function ($query) use ($id) {
            $query->where('modules.id', $id);
        };
        $modules_urls = ModulesUrl::whereHas('modules', $modules)->with('modules')->get();
        $modules_groups = ModulesGroup::get();
        // dd($modules_urls->toArray());
        return view('admin.setting.module.url.index', ['modules_urls' => $modules_urls, 'modules_groups' => $modules_groups]);
    }
    public function add_admin_module_url(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            if ($request->url) {
                $url = $request->url;
                $id = $request->id;
                $m_id = $request->m_id;
                $modules = Module::where([['url', '=', $url], ['m_id', '=', $m_id]])->count();
                if (!empty($modules) && $modules > 0) {
                } else {
                    return "OK";
                }
            } else {
                return back();
            }
        } elseif (FacadesRequest::isMethod('post')) {
            $url = route($request->url, ['id']);
            $url = parse_url($url, PHP_URL_PATH);
            $url  = str_replace("id", "{id}", $url);
            // dd($url);

            $name = $request->url;
            $m_id = $request->m_id;
            if (isset($request->status)) {
                $status = $request->status;
            } else {
                $status = 0;
            }
            // $m_type =  Module::where('id', $m_id)->select('type')->first();
            if (!empty($request->type)) {
                $type = $request->type;
            } else {
                $type = $request->type;
            }
            // dd(count($url));
            ModulesUrl::insert([
                'm_id' => $m_id,
                'url' => $url,
                'name' => $name,
                'status' => $status,
                'type' => $type,
            ]);
        } else {
            return back();
        }
    }
    public function edit_admin_module_url(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            if ($request->url) {
                $url = $request->url;
                $id = $request->id;
                $m_id = $request->m_id;
                $modules = Module::where([['url', '=', $url], ['m_id', '=', $m_id]])->count();
                if (!empty($modules) && $modules > 0) {
                } else {
                    return "OK";
                }
            } else {
                return back();
            }
        } elseif (FacadesRequest::isMethod('post')) {
            $id = $request->id;
            $url = route($request->url, ['id']);
            $url = parse_url($url, PHP_URL_PATH);
            $url  = str_replace("id", "{id}", $url);

            // dd($id);

            $name = $request->url;
            $m_id = $request->m_id;

            if (isset($request->status)) {
                $status = $request->status;
            } else {
                $status = 0;
            }
            if (!empty($request->type)) {
                $type = $request->type;
            } else {
                $type = $request->type;
            }
            ModulesUrl::where('id', $id)->update([
                'url' => $url,
                'name' => $name,
                'type' => $type,
                'status' => $status,
            ]);
            $request->session()->pull('m_id');
            $request->session()->put('m_id', $m_id);
        } else {
            return back();
        }
    }
    public function delete_admin_module_url(Request $request)
    {
        $id = $request->id;
        ModulesUrl::where('id', $id)->delete();
    }
}
