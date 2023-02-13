<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModulesGroup;
use App\Models\ModulesUrl;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ModulesController extends Controller
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

        $roles = Role::get();
        $modules_groups = ModulesGroup::with('modules')->orderBy('sort')->get();
        // $modules = Module::with('modules_group')->orderBy('sort')->get();
        // $modules_groups = ModulesGroup::whereHas('modules')->with('modules')->get();
        // $modules_groups = ModulesGroup::whereHas('modules')->with('modules')->get();
        // dd($modules_groups->toArray());
        return view('admin.setting.module.index', ['roles' => $roles, 'modules_groups' => $modules_groups]);
    }
    public function create(Request $request)
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
    public function edit(Request $request)
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
            return $this->update($request);
        }
    }
    public function update($request)
    {
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
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        Module::where('id', $id)->delete();
    }
}
