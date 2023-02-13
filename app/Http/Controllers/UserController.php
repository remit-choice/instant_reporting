<?php

namespace App\Http\Controllers;

use App\Models\ModulesGroup;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->main();
            return $next($request);
        });
    }
    public function main()
    {
        $r_id = Session::get('r_id');
        $modules = function ($query) use ($r_id) {
            $query->where('permissions.r_id', $r_id);
        };
        $module_groups = ModulesGroup::whereHas('modules.permissions', $modules)->orwhereDoesntHave('modules.permissions', $modules)
            ->with('modules.permissions', $modules)
            ->orderBy("sort")
            ->get();
        $permissions_view = '';
        $permissions_create = '';
        $permissions_edit = '';
        $permissions_delete = '';
        $permissions_buyer_report_view = '';

        foreach ($module_groups as $module_group) {
            foreach ($module_group->modules as $module) {
                if (!empty($module->permissions) && $module->name == 'Permissions') {
                    $permissions_create = $module->permissions['add'];
                    $permissions_edit = $module->permissions['edit'];
                }
                if (!empty($module->permissions) && $module->name == 'Modules URL') {
                    $permissions_view = $module->permissions['view'];
                    $permissions_create = $module->permissions['add'];
                    $permissions_edit = $module->permissions['edit'];
                    $permissions_delete = $module->permissions['delete'];
                }
                if (!empty($module->permissions) && $module->name == 'Payment Method') {
                    $permissions_view = $module->permissions['view'];
                    $permissions_create = $module->permissions['add'];
                    $permissions_edit = $module->permissions['edit'];
                    $permissions_delete = $module->permissions['delete'];
                }
                if (!empty($module->permissions) && $module->name == 'Buyers') {
                    $permissions_buyer_report_view = $module->permissions['view'];
                }
            }
        }
        View::share(['module_groups' => $module_groups, 'permissions_view' => $permissions_view, 'permissions_create' => $permissions_create, 'permissions_edit' => $permissions_edit, 'permissions_delete' => $permissions_delete, 'permissions_buyer_report_view' => $permissions_buyer_report_view]);
    }
    public function index()
    {
        $users =  User::with('roles')->get();
        $roles =  Role::get();
        // dd($users->toArray());
        // return View::make('admin.setting.user.index', ['users' => $users, 'roles' => $roles]);
        return view('admin.setting.user.index', ['users' => $users, 'roles' => $roles]);
    }
    public function show()
    {
        $U_ID =  Session::get('U_ID');
        $UpdateData = User::with('roles')->where('ID', $U_ID)->get();
        return view('admin.profile.index', ['UpdateData' => $UpdateData]);
    }
    public function create(Request $request)
    {
        if (FacadesRequest::isMethod('post')) {
            $request->validate(
                [
                    'full_name' => 'required|string|min:3|max:255',
                    'user_name' => 'required|string|min:3|max:255',
                    'email' => 'required|string|email|max:255',
                    'password' => 'required|string|min:8|max:255',
                    'designation' => 'required|string|min:3|max:255',
                    'r_id' => 'required|integer',
                ],
                [
                    'full_name' => 'required|string|min:3|max:255',
                    'user_name.required' => '*required',
                    'email.required' => '*required',
                    'password.required' => '*required',
                    'designation.required' => '*required',
                    'r_id.required' => '*required',
                ]
            );
            $full_name = $request->full_name;
            $user_name = $request->user_name;
            $email = $request->email;
            $password = $request->password;
            $r_id = $request->r_id;
            $designation = $request->designation;
            $hash_password = Hash::make($password);

            $users_count = User::where('email', '=', $email)->count();
            $Admin = User::where('email', '=', $request->input('email'))->first();

            if ($users_count > 0) {
                return back()->with('failed', "email already Exist");
            } else {
                User::create([
                    'r_id' => $r_id,
                    'full_name' => $full_name,
                    'user_name' => $user_name,
                    'email' => $email,
                    'password' => $hash_password,
                    'designation' => $designation,
                    'status' => $designation,
                ]);
            }
            $users =  User::with('roles')->get();
            $roles =  Role::get();
            // dd($users->toArray());
            return view('admin.setting.user.index', ['users' => $users, 'roles' => $roles]);
        } else {
        }
    }
    public function edit(Request $request)
    {
        if (FacadesRequest::isMethod('post')) {
            $request->validate(
                [
                    'full_name' => 'required|string|min:3|max:255',
                    'user_name' => 'required|string|min:3|max:255',
                    'email' => 'required|string|email|max:255',
                    'designation' => 'required|string|min:3|max:255',
                    'r_id' => 'required|integer',
                ],
                [
                    'full_name' => 'required|string|min:3|max:255',
                    'user_name.required' => '*required',
                    'email.required' => '*required',
                    'designation.required' => '*required',
                    'r_id.required' => '*required',
                ]
            );
            $full_name = $request->full_name;
            $user_name = $request->user_name;
            $email = $request->email;
            $password = $request->password;
            $r_id = $request->r_id;
            $id = $request->id;
            $designation = $request->designation;
            $hash_password = Hash::make($password);
            $status = $request->status;

            $users_count = User::where('email', $email)->count();

            if ($users_count > 1) {
                return back()->with('failed', "email already Exist");
            } else {
                if ($password != 0) {
                    User::where('id', $id)->update([
                        'full_name' => $full_name,
                        'user_name' => $user_name,
                        'email' => $email,
                        'r_id' => $r_id,
                        'designation' => $designation,
                        'password' => $hash_password,
                        'status' => $status,
                    ]);
                } else {
                    User::where('id', $id)->update([
                        'full_name' => $full_name,
                        'user_name' => $user_name,
                        'email' => $email,
                        'r_id' => $r_id,
                        'designation' => $designation,
                        'status' => $status,
                    ]);
                }
                $users =  User::where('id', $id)->first();
                $id =  Session::get('u_id');
                if ($users->id == $id) {
                    session()->pull('full_name');
                    session()->put('full_name', $full_name);
                }
            }
        } else {
        }
    }
    public function delete(Request $request)
    {
        $is = $request->id;
        User::where('id', $is)->delete();
    }
}
