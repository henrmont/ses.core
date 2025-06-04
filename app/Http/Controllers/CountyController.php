<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CountyController extends Controller
{
    use AuthorizesRequests;

    public function getCounties($module)
    {
        $this->authorize($module.'/município listar');
        $counties = County::with(['health_region'])->get();
        return response()->json($counties);
    }
}
