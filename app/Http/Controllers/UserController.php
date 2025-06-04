<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleUser;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function getUsers()
    {
        $this->authorize('sesadm/usuário listar');
        $users = User::with(['module','modules','roles'])->where('id','<>',1)->where('id','<>',auth()->user()->id)->orderBy('created_at','desc')->get();
        return response()->json($users, 200);
    }

    public function getUser($id)
    {
        $this->authorize('sesadm/usuário listar');
        $users = User::with(['module','modules','roles'])->find($id);
        return response()->json($users, 200);
    }

    public function changeValidUser($module, $id)
    {
        $this->authorize($module.'/usuário editar');
        $user = User::find($id);
        if ($user->is_valid) {
            $user->update([
                'is_valid' => false,
            ]);
        } else {
            $user->update([
                'is_valid' => true,
            ]);
        }
    }

    public function changeInfoUser(Request $request, $module)
    {
        $this->authorize($module.'/usuário editar');
        $user = User::find($request->id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return response()->json(['message' => 'Informações do usuário alteradas com sucesso.'], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('sesadm/usuário criar');
        $user = User::where('email',$request->email)->first();
        if ($user) {
            return response()->json(['message' => 'Email já está em uso.'], 400);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(md5(now())),
            ]);
            return response()->json(['message' => 'Usuário cadastrado com sucesso.'], 200);
        }
    }

    public function createModuleUser($module, Request $request)
    {
        $this->authorize($module.'/usuário criar');
        $user = User::where('email',$request->email)->first();
        if ($user) {
            return response()->json(['message' => 'Email já está em uso.'], 400);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(md5(now())),
            ]);
            $user_module = Module::where('name',$module)->first();
            ModuleUser::create([
                'user_id' => $user->id,
                'module_id' => $user_module->id
            ]);
            return response()->json(['message' => 'Usuário cadastrado com sucesso.'], 200);
        }
    }

    public function changeModuleUser($id)
    {
        $user = User::find(auth()->user()->id);
        $user->update([
            'module_id' => $id
        ]);
    }

    public function deleteUser($module, $id)
    {
        $this->authorize($module.'/usuário apagar');
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'Usuário deletado com sucesso.'], 200);
    }

}
