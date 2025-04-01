<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::with('modules.module')->where('id','<>',1)->where('id','<>',auth()->user()->id)->get();
        return response()->json($users, 200);
    }

    public function getUser($id)
    {
        $users = User::with(['modules.module','roles'])->find($id);
        return response()->json($users, 200);
    }

    public function changeValidUser($id)
    {
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

    public function changeInfoUser(Request $request)
    {
        $user = User::find($request->id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return response()->json(['message' => 'Informações do usuário alteradas com sucesso.'], 200);
    }
}
