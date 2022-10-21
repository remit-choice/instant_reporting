<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleGroup;
use App\Models\ModulesGroup;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ModuleGroupsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app(UserController::class)->main();
            return $next($request);
        });
    }

    public function admin_module_group_list()
    {
        if (FacadesRequest::isMethod('get')) {
            $roles = Role::get();
            $modules_groups = ModulesGroup::get();
            return view('admin.setting.module.group.index', ['roles' => $roles, 'modules_groups' => $modules_groups]);
        } else {
            return back();
        }
    }
    public function add_admin_module_group(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            if ($request->name) {
                $name = $request->name;
                $selectGroupNameStatus = ModulesGroup::where('name', '=', $name)->count();
                if ($selectGroupNameStatus > 0) {
                } else {
                    return "OK";
                }
            } else {
            }
        } elseif (FacadesRequest::isMethod('post')) {
            $name = $request->name;
            $icon = $request->icon;
            if (!empty($request->status)) {
                $status = '1';
            } else {
                $status = '0';
            }
            ModulesGroup::insert([
                'name' => $name,
                'icon' => $icon,
                'status' => $status,
            ]);
        } else {
            return back();
        }
    }
    public function edit_admin_module_group(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            if ($request->name) {
                $name = $request->name;
                $selectGroupNameStatus = ModulesGroup::where('name', '=', $name)->count();
                if ($selectGroupNameStatus > 1) {
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
            ModulesGroup::where('id', $id)->update([
                'name' => $name,
                'icon' => $icon,
            ]);
            $roles = Role::get();
            $modules_groups = ModulesGroup::get();
            return view('admin.setting.module.group.index', ['roles' => $roles, 'modules_groups' => $modules_groups]);
        } else {
            return back();
        }
    }
    public function delete_admin_module_group(Request $request)
    {
        $id = $request->id;
        ModulesGroup::where('id', $id)->delete();
        return back();
    }
}
