<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function changeInfo(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $user->update([
            'name' => $request->name,
        ]);
    }

    public function changePicture(Request $request)
    {
        $profile = User::find(auth()->user()->id);
        $profile->update([
            'picture' => $request->picture,
        ]);
        return response()->json(['message' => 'Imagem alterada com sucesso.'], 200);
    }
}
