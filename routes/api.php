<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('send/verification/code', [AuthController::class, 'sendVerificationCode']);
    Route::post('check/verification/code', [AuthController::class, 'checkVerificationCode']);
    Route::post('reset/password', [AuthController::class, 'resetPassword']);
    Route::post('create', [AuthController::class, 'create']);
    Route::get('me', [AuthController::class, 'me']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);

});

Route::group(['middleware' => 'api', 'prefix' => 'user'], function ($router) {

    Route::get('get/users', [UserController::class, 'getUsers']);
    Route::get('get/user/{id}', [UserController::class, 'getUser']);
    Route::patch('change/valid/user/{id}', [UserController::class, 'changeValidUser']);
    Route::patch('change/info/user', [UserController::class, 'changeInfoUser']);

});

Route::group(['middleware' => 'api', 'prefix' => 'module'], function ($router) {

    Route::get('get/modules', [ModuleController::class, 'getModules']);
    Route::get('get/user/modules', [ModuleController::class, 'getUserModules']);
    Route::patch('change/user/module/{module_id}/{user_id}', [ModuleController::class, 'changeUserModule']);

});

Route::group(['middleware' => 'api', 'prefix' => 'role'], function ($router) {

    Route::get('get/roles/{module}', [RoleController::class, 'getRoles']);
    Route::post('create/role', [RoleController::class, 'createRole']);
    Route::patch('update/role', [RoleController::class, 'updateRole']);
    Route::delete('delete/role/{id}', [RoleController::class, 'deleteRole']);

    Route::get('get/permissions/{module}', [RoleController::class, 'getPermissions']);
    Route::patch('change/permission/to/role/{permission_id}/{role_id}', [RoleController::class, 'changePermissionToRole']);
    Route::patch('change/role/to/user/{role_id}/{user_id}', [RoleController::class, 'changeRoleToUser']);

});

Route::group(['middleware' => 'api', 'prefix' => 'chat'], function ($router) {

    Route::get('get/chats', [ChatController::class, 'getChats']);
    Route::get('get/chat/{id}', [ChatController::class, 'getChat']);
    Route::post('register/message', [ChatController::class, 'registerMessage']);
    Route::get('get/users', [ChatController::class, 'getUsers']);
    Route::get('get/user/chat/{id}', [ChatController::class, 'getUserChat']);

});

Route::group(['middleware' => 'api', 'prefix' => 'notification'], function ($router) {

    Route::get('get/notifications', [NotificationController::class, 'getNotifications']);
    Route::delete('delete/notification/{id}', [NotificationController::class, 'deleteNotification']);

});

Route::group(['middleware' => 'api', 'prefix' => 'profile'], function ($router) {

    Route::patch('change/info', [ProfileController::class, 'changeInfo']);
    Route::patch('change/picture', [ProfileController::class, 'changePicture']);

});

Route::group(['middleware' => 'api', 'prefix' => 'article'], function ($router) {

    Route::get('get/articles', [ArticleController::class, 'getArticles']);

});
