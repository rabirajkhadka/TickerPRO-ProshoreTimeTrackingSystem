<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\AdminController;
use \App\Http\Controllers\UserController;
use \App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->prefix('user')->group(function () {
    Route::post('register', 'registerUser');
    Route::post('login', 'loginUser')->name('login');
    Route::get('logout', 'logoutUser')->middleware('auth:sanctum');
    Route::post('forgot-password', 'resetPass');
    Route::post('reset-success', 'willaddlater')->name('password.reset');
});

Route::controller(AdminController::class)->prefix('admin')->middleware(['auth:sanctum', 'user.status', 'isAdmin'])->group(function () {
    Route::get('all-users', 'viewAllUsers');
    Route::post('change-roles', 'assignRoles');
    Route::post('delete-user/{id}', 'deleteUser');
});

Route::controller(UserController::class)->prefix('user')->middleware(['auth:sanctum', 'user.status'])->group(function () {
    Route::get('me', 'viewMe');
    Route::patch('update', 'updateMe');
});





