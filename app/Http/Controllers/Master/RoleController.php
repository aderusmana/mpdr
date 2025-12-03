<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB as FacadeDB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view role', ['only' => ['index']]);
        $this->middleware('permission:create role', ['only' => ['create','store','addPermissionToRole','givePermissionToRole']]);
        $this->middleware('permission:update role', ['only' => ['update','edit']]);
        $this->middleware('permission:delete role', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Role::query();
            return DataTables::of($query)->toJson();
        }

        $roles = Role::get();

         // Dialog Sweet Alert
         $title = 'Delete User!';
         $text = "Are you sure you want to delete?";
         confirmDelete($title, $text);
        
        return view('page.master.roles.index', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:roles,name']
        ]);

        if ($v->fails()) {
            return response()->json(["status" => "error", "message" => "Validation failed", "errors" => $v->errors()], 422);
        }

        try {
            Role::create(['name' => $request->name]);
            return response()->json(["status" => "success", "message" => "Successfully Created Role"]);
        } catch (\Exception $e) {
            Log::error('Role store error: ' . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Failed to create role"], 500);
        }
    }



    public function update(Request $request, Role $role)
    {
        $v = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:roles,name,'.$role->id]
        ]);

        if ($v->fails()) {
            return response()->json(["status" => "error", "message" => "Validation failed", "errors" => $v->errors()], 422);
        }

        try {
            $role->update(['name' => $request->name]);
            return response()->json(["status" => "success", "message" => "Successfully Updated Role"]);
        } catch (\Exception $e) {
            Log::error('Role update error: ' . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Failed to update role"], 500);
        }
    }

    public function destroy($roleId)
    {
        try {
            $role = Role::find($roleId);
            if ($role) {
                $role->delete();
                return response()->json(["status" => "success", "message" => "Successfully Deleted Role"]);
            }
            return response()->json(["status" => "error", "message" => "Role not found"], 404);
        } catch (\Exception $e) {
            Log::error('Role destroy error: ' . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Failed to delete role"], 500);
        }
    }

    public function addPermissionToRole(Request $request, $roleId)
    {
        $permissions = Permission::get();
        $role = Role::findOrFail($roleId);
        $rolePermissions = FacadeDB::table('role_has_permissions')
                                ->where('role_has_permissions.role_id', $role->id)
                                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                ->all();

        if ($request->ajax()) {
            return response()->json([
                'role' => $role,
                'permissions' => $permissions,
                'rolePermissions' => array_values($rolePermissions),
            ]);
        }

        return view('page.master.roles.add-permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function givePermissionToRole(Request $request, $roleId)
    {
        // normalize input to support either 'permissions' (array) or 'permission' (single or array)
        $inputPermissions = $request->has('permissions') ? $request->input('permissions') : $request->input('permission', []);

        if (is_string($inputPermissions)) {
            $inputPermissions = [$inputPermissions];
        }

        $v = Validator::make(['permissions' => $inputPermissions], [
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        if ($v->fails()) {
            return response()->json(["status" => "error", "message" => "Validation failed", "errors" => $v->errors()], 422);
        }

        try {
            $role = Role::findOrFail($roleId);
            $role->syncPermissions($inputPermissions);
            return response()->json(["status" => "success", "message" => "Successfully Added Permissions to Role"]);
        } catch (\Exception $e) {
            Log::error('Give permission to role error: ' . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Failed to assign permissions"], 500);
        }
    }
}
