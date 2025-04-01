<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(["status" => true], 200);
});
