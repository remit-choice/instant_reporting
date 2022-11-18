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
        $modules = Module::with('modules_group')->orderBy('sort')->get();
        $modules_groups = ModulesGroup::with('modules')->orderBy('sort')->get();
        // $modules_groups = ModulesGroup::whereHas('modules')->with('modules')->get();
        // $modules_groups = ModulesGroup::whereHas('modules')->with('modules')->get();
        // dd($modules_groups->toArray());
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
            $request->validate(
                [
                    'name' => 'required|string|max:255',
                    'icon' => 'required|string|max:255',
                    'type' => 'required|string|max:255',
                ],
                [
                    'name.required' => "*Name is required",
                    'icon.required' => "*Icon is required",
                    'type.required' => "*Type is required",
                ]
            );
            $m_g_id = $request->m_g_id;
            $name = $request->name;
            $icon = $request->icon;
            $type = $request->type;

            $status = $request->status;


            Module::insert([
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
            if (!empty($request->sort)) {
                $modules = Module::all();
                foreach ($modules as $module) {
                    foreach ($request->sort as $sort) {
                        if ($sort['id'] == $module->id) {
                            $module->update(['sort' => $sort['position']]);
                        }
                    }
                }
                return true;
            } else {
                $request->validate(
                    [
                        'name' => 'required|string|max:255',
                        'icon' => 'required|string|max:255',
                        'type' => 'required|string|max:255',
                    ],
                    [
                        'name.required' => "*Name is required",
                        'icon.required' => "*Icon is required",
                        'type.required' => "*Type is required",
                    ]
                );
                $id = $request->id;
                $name = $request->name;
                $icon = $request->icon;
                $m_g_id = $request->m_g_id;
                $status = $request->status;

                $type = $request->type;
                Module::where('id', $id)->update([
                    'name' => $name,
                    'icon' => $icon,
                    'm_g_id' => $m_g_id,
                    'status' => $status,
                    'type' => $type,
                ]);
            }
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
            $url  = str_replace("/instantreporting", "", $url);
            // dd($url);

            $name = $request->url;
            $m_id = $request->m_id;
            $status = $request->status;
            $type = $request->type;
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
            $url  = str_replace("/instantreporting", "", $url);


            // dd($id);

            $name = $request->url;
            $m_id = $request->m_id;
            $status = $request->status;
            $type = $request->type;
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
