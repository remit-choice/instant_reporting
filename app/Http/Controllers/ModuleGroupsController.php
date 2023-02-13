<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleGroup;
use App\Models\ModulesGroup;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Validator;

class ModuleGroupsController extends Controller
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
        if (FacadesRequest::isMethod('get')) {
            $roles = Role::get();
            $modules_groups = ModulesGroup::orderBy('sort', 'ASC')->get();
            return view('admin.setting.module.group.index', ['roles' => $roles, 'modules_groups' => $modules_groups]);
        } else {
            return back();
        }
    }
    public function create(Request $request)
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
            $request->validate(
                [
                    'name' => 'required|string|max:255',
                    'icon' => 'required|string|max:255',
                ],
                [
                    'name.required' => "*Name is required",
                    'icon.required' => "*Icon is required",
                ]
            );
            $name = $request->name;
            $icon = $request->icon;
            $status = $request->status;

            ModulesGroup::create([
                'name' => $name,
                'icon' => $icon,
                'status' => $status
            ]);
        } else {
            return back();
        }
    }
    public function edit(Request $request)
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
            if (!empty($request->sort)) {
                $modules_groups = ModulesGroup::all();
                foreach ($modules_groups as $modules_group) {
                    foreach ($request->sort as $sort) {
                        if ($sort['id'] == $modules_group->id) {
                            $modules_group->update(['sort' => $sort['position']]);
                        }
                    }
                }
                return true;
            } else {
                $request->validate(
                    [
                        'name' => 'required|string|max:255',
                        'icon' => 'required|string|max:255',
                    ],
                    [
                        'name.required' => "*Name is required",
                        'icon.required' => "*Icon is required",
                    ]
                );
                $id = $request->id;
                $name = $request->name;
                $icon = $request->icon;
                $status = $request->status;

                ModulesGroup::where('id', $id)->update([
                    'name' => $name,
                    'icon' => $icon,
                    'status' => $status
                ]);
            }
            // $sort_records =  ModulesGroup::where('sort', '>=', $sort)->get();
            // $sort_count =  ModulesGroup::where('sort', '>=', $sort)->count();
            // $sort_count_begin =  ModulesGroup::where('sort', '<', $sort)->count();
            // dd($sort_count);
            // if ($sort_count >= 0) {
            //     ModulesGroup::where('id', $id)->update([
            //         'sort' => $sort,
            //     ]);
            //     foreach ($sort_records as $sort_record) {
            //         ModulesGroup::where('id', $sort_record->id)->update([
            //             'sort' => $sort++,
            //         ]);
            //     }
            //     return true;
            // }
            //  elseif (!empty($sort_data)) {
            //     if ($sort_count <= 1 && $id == $sort_data->id) {
            //         ModulesGroup::where('id', $id)->update([
            //             'sort' => $sort,
            //         ]);
            //         return true;
            //     } else {
            //         return "false";
            //     }
            // } 
            // else {
            //     return "false";
            // }
        } else {
            return back();
        }
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        ModulesGroup::where('id', $id)->delete();
        return back();
    }
}
