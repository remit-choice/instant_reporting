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
            return view('admin.setting.role.permission.edit', ['roles' => $roles, 'modules_groups' => $modules_groups, 'type' => $type]);
        } elseif (FacadesRequest::isMethod('post')) {
            if ($request->type == 0 && !empty($request->m_id)) {
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
                }
                // $count = Permission::where('r_id', $r_id)->count();
                for ($m = 0; $m < $count_m_id; $m++) {
                    Permission::where([['r_id', '=', $r_id], ['m_id', '=', $m_id[$m]]])->update([
                        'view' => $view[$m],
                        'add' => $add[$m],
                        'edit' => $edit[$m],
                        'delete' => $delete[$m],
                    ]);
                    // $group =  Permission::where([['r_id', '=', $r_id], ['m_id', '=', $m_id[$m]], ['view', '=', '0']])->get();
                }
            } elseif ($request->type == 1 && !empty($request->m_id)) {
                $r_id =  $request->r_id;
                $m_id =  $request->m_id;
                $count_m_id = count($m_id);
                $view =  [];
                $add =  [];
                $edit =  [];
                $delete =  [];
                for ($i = 0; $i < $count_m_id; $i++) {
                    if (empty($request->view[$i])) {
                        $view[] = NULL;
                    } else {
                        $view[] = "1";
                    }
                    if (empty($request->add[$i])) {
                        $add[] = NULL;
                    } else {
                        $add[] = "1";
                    }
                    if (empty($request->edit[$i])) {
                        $edit[] = NULL;
                    } else {
                        $edit[] = "1";
                    }
                    if (empty($request->delete[$i])) {
                        $delete[] = NULL;
                    } else {
                        $delete[] = "1";
                    }
                }
                $count = Permission::where('r_id', $r_id)->count();
                for ($m = 0; $m < $count; $m++) {
                    Permission::where([['r_id', '=', $r_id], ['m_id', '=', $m_id[$m]]])->update([
                        'view' => $view[$m],
                        'add' => $add[$m],
                        'edit' => $edit[$m],
                        'delete' => $delete[$m],
                    ]);
                    // $group =  Permission::where([['r_id', '=', $r_id], ['m_id', '=', $m_id[$m]], ['view', '=', '0']])->get();
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
            $modules_groups = ModulesGroup::whereHas('modules.permissions', $modules)->orwhereDoesntHave('modules.permissions', $modules)
                ->with('modules.permissions', $modules)
                ->get();
            return view('admin.setting.role.permission.create', ['roles' => $roles, 'modules_groups' => $modules_groups]);
        } elseif (FacadesRequest::isMethod('post')) {
            if (!empty($request->m_id)) {
                $r_id =  $request->r_id;
                $m_id =  $request->m_id;
                $m_id_array_key = array_keys($m_id);
                $count_m_id = count($m_id);
                $view =  [];
                $add =  [];
                $edit =  [];
                $delete =  [];
                // if (!empty(count($request->view))) {
                //     $count = count($request->view);
                // } elseif (!empty($request->add)) {
                //     $count = count($request->add);
                // } elseif (!empty($request->edit)) {
                //     $count = count($request->edit);
                // } elseif (!empty($request->delete)) {
                //     $count = count($request->delete);
                // } else {
                // }
                // dd($request->toArray());
                // $permision_count = Permission::where([['r_id', $r_id], ['m_id', $m_id[$i]]])->count();
                // dd($permision_count);
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
                    $permision_count = Permission::where([['r_id', $r_id], ['m_id', $m_id[$i]]])->count();
                    if ($permision_count <= 0) {
                        // if ($m_id_array_key[$i] == $m_id[$i])
                        Permission::insert([
                            'r_id' =>  $r_id,
                            'm_id' => $m_id[$i],
                            'view' => $view[$i],
                            'add' => $add[$i],
                            'edit' => $edit[$i],
                            'delete' => $delete[$i],
                        ]);
                    } else {
                        Permission::where([['r_id', $r_id], ['m_id', $m_id[$i]]])->update([
                            'r_id' =>  $r_id,
                            'm_id' => $m_id[$i],
                            'view' => $view[$i],
                            'add' => $add[$i],
                            'edit' => $edit[$i],
                            'delete' => $delete[$i],
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
