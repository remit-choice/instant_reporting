<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModulesGroup;
use App\Models\ModulesUrl;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ModuleUrlController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app(UserController::class)->main();
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
    public function delete(Request $request)
    {
        $id = $request->id;
        ModulesUrl::where('id', $id)->delete();
    }
}
