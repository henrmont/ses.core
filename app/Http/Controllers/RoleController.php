<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function getRoles($module)
    {
        $this->authorize('sesadm/regra listar');
        $roles = Role::with('permissions')->where('name','LIKE',"%".$module."%")->get();
        return response()->json($roles, 200);
    }

    public function createRole(Request $request)
    {
        $this->authorize('sesadm/regra criar');
        Role::create([
            'name' => $request->name
        ]);
        return response()->json(['message' => 'Regra criada com sucesso.'], 200);
    }

    public function updateRole(Request $request)
    {
        $this->authorize('sesadm/regra editar');
        $role = Role::find($request->id);
        $role->update([
            'name' => $request->name
        ]);
        return response()->json(['message' => 'Regra editada com sucesso.'], 200);
    }

    public function deleteRole($id)
    {
        $this->authorize('sesadm/regra apagar');
        $role = Role::find($id);
        $role->delete();
        return response()->json(['message' => 'Regra apagada com sucesso.'], 200);
    }

    public function getPermissions($module)
    {
        $this->authorize('sesadm/regra listar');
        $permissions = Permission::with('roles')->where('name','LIKE',"%".$module."%")->get();
        return response()->json($permissions, 200);
    }

    public function changePermissionToRole($permission_id, $role_id)
    {
        $this->authorize('sesadm/regra editar');
        $permission = Permission::find($permission_id);
        $role = Role::find($role_id);
        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        } else {
            $role->givePermissionTo($permission);
        }
    }

    public function changeRoleToUser($role_id, $user_id)
    {
        $this->authorize('sesadm/regra editar');
        $user = User::find($user_id);
        $role = Role::find($role_id);
        if ($user->hasRole($role)) {
            $user->removeRole($role);
        } else {
            $user->assignRole($role);
        }
    }

}
