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

    public function getUserModules()
    {
        $modules = ModuleUser::with(['module'])->where('user_id',auth()->user()->id)->get();
        return response()->json($modules);
    }

    public function changeUserModule($module_id, $user_id)
    {
        $module = ModuleUser::where('module_id', $module_id)->where('user_id', $user_id);
        if ($module->first()) {
            $module->delete();
            return response()->json('Module removed');
        } else {

            if ($module->withTrashed()->first()) {
                $module->restore();
            } else {
                ModuleUser::create([
                    'user_id' => $user_id,
                    'module_id' => $module_id
                ]);
            }
            return response()->json('Module added');
        }
    }

}
