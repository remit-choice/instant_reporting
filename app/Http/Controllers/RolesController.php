<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModulesGroup;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\UserController;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            (new UserController)->main();
            return $next($request);
        });
    }
    public function roles_list(Request $request)
    {
        $roles = Role::get();
        return view('admin.setting.role.index', ['roles' => $roles]);
        // $roles = Role::get();
        // $modules_groups = ModulesGroup::get();
        // return view('admin.setting.role.index', ['roles' => $roles, 'modules_groups' => $modules_groups]);
    }
    public function add_role(Request $request)
    {
        if (FacadesRequest::isMethod('post')) {
            $request->validate(
                [
                    'name' => 'required|string|min:3',
                ],
                [
                    'name.required' => '*required',
                ]
            );
            $name = $request->name;
            $status = $request->status;
            Role::create(['name' => $name, 'status' => $status]);
        } else {
            return back();
        }
    }
    public function edit_role(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            $id = $request->id;
            $roles = Role::where('id', $id)->get();
            return view('admin.setting.role.edit', ['roles' => $roles]);
        } elseif (FacadesRequest::isMethod('post')) {
            $request->validate(
                [
                    'name' => 'required|string|min:3',
                ],
                [
                    'name.required' => '*required',
                ]
            );
            $id = $request->id;
            $name = $request->name;
            $status = $request->status;

            Role::where('id', $id)->update(['name' => $name, 'status' => $status]);
        } else {
            return back();
        }
    }
    public function delete_role(Request $request)
    {
        if (FacadesRequest::isMethod('post')) {
            $id = $request->id;
            Role::where('id', $id)->delete();
            // Permission::where('r_id', $id)->delete();
        } else {
            return back();
        }
    }
}
