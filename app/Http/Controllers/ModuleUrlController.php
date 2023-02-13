<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModulesGroup;
use App\Models\ModulesUrl;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ModuleUrlController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            (new UserController)->main();
            return $next($request);
        });
    }
    //Modules Url
    public function index(Request $request)
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
    public function create(Request $request)
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
            $mode = $request->mode;
            ModulesUrl::create([
                'm_id' => $m_id,
                'url' => $url,
                'name' => $name,
                'status' => $status,
                'type' => $type,
                'mode' => $mode,
            ]);
        } else {
            return back();
        }
    }
    public function edit(Request $request)
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
            } elseif ($request->module_mode) {
                $m_id = $request->m_id;
                $module_mode = $request->module_mode;
                $modules = ModulesUrl::where([['m_id', '=', $m_id], ['mode', '=', $module_mode]])->count();
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
            $mode = $request->mode;
            ModulesUrl::where('id', $id)->update([
                'url' => $url,
                'name' => $name,
                'type' => $type,
                'mode' => $mode,
                'status' => $status,
            ]);
            $request->session()->pull('m_id');
            $request->session()->put('m_id', $m_id);
        } else {
            return back();
        }
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        $modules = ModulesUrl::where('id', $id)->select('m_id', 'mode')->first();
        $permisions = Permission::where('m_id', $modules->m_id)->get();
        if (!empty($permisions)) {
            if ($modules->mode == 1) {
                Permission::where('m_id', $modules->m_id)->update([
                    'view' => NULL
                ]);
            } elseif ($modules->mode == 2) {
                Permission::where('m_id', $modules->m_id)->update([
                    'add' => NULL
                ]);
            } elseif ($modules->mode == 3) {
                Permission::where('m_id', $modules->m_id)->update([
                    'edit' => NULL
                ]);
            } elseif ($modules->mode == 4) {
                Permission::where('m_id', $modules->m_id)->update([
                    'delete' => NULL
                ]);
            }
        }
        ModulesUrl::where('id', $id)->delete();
    }
}
