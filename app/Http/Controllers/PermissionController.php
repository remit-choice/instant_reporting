<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleGroup;
use App\Models\ModulesGroup;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            app(UserController::class)->main();
            return $next($request);
        });
    }

    public function edit_permissions(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            $id = $request->id;
            $type = $request->type;
            $request->session()->pull('roll_id');
            $request->session()->put('roll_id', $id);
            $roles = Role::where('id', $id)->get();
            $modules = function ($query) use ($id) {
                $query->where('permissions.r_id', $id);
            };
            $modules_groups = ModulesGroup::whereHas('modules.permissions', $modules)
                ->with('modules.permissions', $modules)
                ->get();
            // dd($modules_groups->toArray());
            return view('admin.setting.role.permission.edit', ['roles' => $roles, 'modules_groups' => $modules_groups, 'type' => $type]);
        } elseif (FacadesRequest::isMethod('post')) {
            if (!empty($request->m_id)) {
                $r_id =  $request->r_id;
                $m_id =  $request->m_id;
                $count_m_id = count($m_id);
                $view =  [];
                $add =  [];
                $edit =  [];
                $delete =  [];
                for ($i = 0; $i < $count_m_id; $i++) {
                    if (empty($request->view[$i])) {
                        $view[] = "0";
                    } else {
                        $view[] = "1";
                    }
                    if (empty($request->add[$i])) {
                        $add[] = "0";
                    } else {
                        $add[] = "1";
                    }
                    if (empty($request->edit[$i])) {
                        $edit[] = "0";
                    } else {
                        $edit[] = "1";
                    }
                    if (empty($request->delete[$i])) {
                        $delete[] = "0";
                    } else {
                        $delete[] = "1";
                    }
                    Permission::where([['r_id', '=', $r_id], ['m_id', '=', $m_id[$i]]])->update([
                        'view' => $view[$i],
                        'add' => $add[$i],
                        'edit' => $edit[$i],
                        'delete' => $delete[$i],
                    ]);
                }
            } else {
            }
            $roles = Role::get();
            $request->session()->pull('roll_id');
            return redirect('/admin/setting/role')->with(['roles' => $roles, 'success', "Permissions Updated Successfully"]);
        } else {
        }
    }
    public function add_permissions(Request $request)
    {
        if (FacadesRequest::isMethod('get')) {
            $id = $request->id;
            $request->session()->pull('roll_id');
            $request->session()->put('roll_id', $id);
            $roles = Role::where('id', $id)->get();
            $modules = function ($query) use ($id) {
                $query->where('r_id', $id);
            };
            $modules_groups = ModulesGroup::whereDoesntHave('modules.permissions', $modules)
                ->with('modules.permissions', $modules)
                ->get();
            return view('admin.setting.role.permission.create', ['roles' => $roles, 'modules_groups' => $modules_groups]);
        } elseif (FacadesRequest::isMethod('post')) {
            if (!empty($request->m_id)) {
                $r_id =  $request->r_id;
                $m_id =  $request->m_id;
                $m_ids =  $request->m_ids;
                if (!empty($request->m_ids)) {
                    $m_id_count =  count($m_ids);
                    $m_id_kays = array_keys($m_ids);
                } else {
                    $m_id_count = 0;
                }
                $view =  [];
                $add =  [];
                $edit =  [];
                $delete =  [];
                for ($i = 0; $i < $m_id_count; $i++) {
                    if (empty($request->view[$m_id_kays[$i]])) {
                        $view[$m_id_kays[$i]] = null;
                    } else {
                        $view[$m_id_kays[$i]] = "1";
                    }
                    if (empty($request->add[$m_id_kays[$i]])) {
                        $add[$m_id_kays[$i]] = null;
                    } else {
                        $add[$m_id_kays[$i]] = "1";
                    }
                    if (empty($request->edit[$m_id_kays[$i]])) {
                        $edit[$m_id_kays[$i]] = null;
                    } else {
                        $edit[$m_id_kays[$i]] = "1";
                    }
                    if (empty($request->delete[$m_id_kays[$i]])) {
                        $delete[$m_id_kays[$i]] = null;
                    } else {
                        $delete[$m_id_kays[$i]] = "1";
                    }
                    $permision_count = Permission::where([['r_id', $r_id], ['m_id', $m_id[$m_id_kays[$i]]]])->count();
                    if ($permision_count <= 0) {
                        Permission::insert([
                            'r_id' =>  $r_id,
                            'm_id' => $m_id[$m_id_kays[$i]],
                            'view' => $view[$m_id_kays[$i]],
                            'add' => $add[$m_id_kays[$i]],
                            'edit' => $edit[$m_id_kays[$i]],
                            'delete' => $delete[$m_id_kays[$i]],
                        ]);
                    } else {
                        Permission::where([['r_id', $r_id], ['m_id', $m_id[$m_id_kays[$i]]]])->update([
                            'r_id' =>  $r_id,
                            'm_id' => $m_id[$m_id_kays[$i]],
                            'view' => $view[$m_id_kays[$i]],
                            'add' => $add[$m_id_kays[$i]],
                            'edit' => $edit[$m_id_kays[$i]],
                            'delete' => $delete[$m_id_kays[$i]],
                        ]);
                    }
                }
            }

            $roles = Role::get();
            $request->session()->pull('roll_id');
            return redirect()->route('admin.role.index')->with(['roles' => $roles, 'success', "Permissions Inserted Successfully"]);
        } else {
        }
    }
}
