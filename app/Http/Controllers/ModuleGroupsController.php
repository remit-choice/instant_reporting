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
            $modules_groups = ModulesGroup::orderBy('sort')->get();
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
            $sort = $request->sort;
            if (!empty($request->status)) {
                $status = '1';
            } else {
                $status = '0';
            }
            // $sort_result =  ModulesGroup::where('sort', $sort)->first();
            $sort_count =  ModulesGroup::where('sort', $sort)->count();
            if ($sort_count <= 0) {
                ModulesGroup::insert([
                    'name' => $name,
                    'icon' => $icon,
                    'sort' => $sort,
                    'status' => $status
                ]);
                return true;
            } else {
                return "false";
            }
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
            $sort = $request->sort;
            if (!empty($request->status)) {
                $status = 1;
            } else {
                $status = 0;
            }

            ModulesGroup::where('id', $id)->update([
                'name' => $name,
                'icon' => $icon,
                'status' => $status
            ]);

            $sort_data =  ModulesGroup::where('sort', $sort)->first();
            $sort_count =  ModulesGroup::where('sort', $sort)->count();
            // dd($sort_data->id);
            if ($sort_count <= 0) {
                ModulesGroup::where('id', $id)->update([
                    'sort' => $sort,
                ]);
                return true;
            } elseif (!empty($sort_data)) {
                if ($sort_count <= 1 && $id == $sort_data->id) {
                    ModulesGroup::where('id', $id)->update([
                        'sort' => $sort,
                    ]);
                    return true;
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
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
