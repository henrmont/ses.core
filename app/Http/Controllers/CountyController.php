<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\Request;

class CountyController extends Controller
{
    public function getCounties()
    {
        $counties = County::with(['health_region'])->get();
        return response()->json($counties);
    }
}
