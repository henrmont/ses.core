<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleUser;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function getModules()
    {
        $modules = Module::with(['module'])->get();
        return response()->json($modules);
    }

    public function getUserModule($id)
    {
        $module = Module::find($id);
        return response()->json($module);
    }

    public function getUserModules()
    {
        $modules = ModuleUser::with(['module'])->where('user_id',auth()->user()->id)->get();
        return response()->json($modules);
    }

    public function changeUserModule($module_id, $user_id)
    {
        $module = ModuleUser::where('module_id', $module_id)->where('user_id', $user_id)->first();
        if ($module) {
            $module->delete();
            return response()->json('Module removed');
        } else {
            ModuleUser::create([
                'user_id' => $user_id,
                'module_id' => $module_id
            ]);
            return response()->json('Module added');
        }
    }

}
