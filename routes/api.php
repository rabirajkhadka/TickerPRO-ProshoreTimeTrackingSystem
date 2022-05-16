<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\AdminController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\InviteController;
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

Route::get('all-roles', [UserController::class, 'allUserRoles']);

Route::controller(AuthController::class)->prefix('user')->group(function () {
    Route::post('register', 'registerUser')->name('register');
    Route::post('login', 'loginUser')->name('login');
    Route::get('logout', 'logoutUser')->middleware('auth:sanctum');
    Route::post('forgot-password', 'forgotPass');
    Route::post('reset-password', 'resetPass');
});

Route::controller(AdminController::class)->prefix('admin')->middleware(['auth:sanctum', 'user.status', 'isAdmin'])->group(function () {
    Route::get('all-users', 'viewAllUsers');
    Route::post('change-roles', 'assignRoles');
    Route::post('delete-user/{id}', 'deleteUser');
    Route::post('invite', 'inviteOthers');
    Route::post('update-status/{id}', 'updateUserStatus');
});

// Invite related actions
Route::controller(InviteController::class)->prefix('invite')->middleware(['auth:sanctum', 'user.status', 'isAdmin'])->group(function () {
    Route::get('invited-users', 'listInvitedUsers');
    Route::post('resend', 'reInvite');
    Route::get('revoke/{id}', 'revoke');
});

Route::controller(UserController::class)->prefix('user')->middleware(['auth:sanctum', 'user.status'])->group(function () {
    Route::get('me', 'viewMe');
    Route::patch('update', 'updateMe');
});






