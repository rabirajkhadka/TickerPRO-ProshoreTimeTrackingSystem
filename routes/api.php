<?php

use App\Http\Controllers\Actions\Auth\{
    ForgotPasswordAction,
    LoginAction,
    RegisterAction,
    LogoutAction,
    ResetPasswordAction,
    VerifyInviteTokenAction,
    VerifyPasswordTokenAction
};
use App\Http\Controllers\Actions\Admin\{DeleteUserAction, UpdateUserStatusAction};
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Actions\Report\{GeneratePdfAction, GenerateReportAction};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('user')->group(function () {
    Route::post('login', LoginAction::class)->name('login');
    Route::post('register', RegisterAction::class)->name('register');
    Route::post('forgot-password', ForgotPasswordAction::class)->name('forgot-password');
    Route::post('reset-password', ResetPasswordAction::class)->name('reset-password');
    Route::get('verify/reset-token', VerifyPasswordTokenAction::class);
    Route::get('verify/invite-token', VerifyInviteTokenAction::class);
});
Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('logout', LogoutAction::class)->name('logout');
});

Route::middleware(['auth:sanctum', 'user.status'])->group(function () {
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('me', 'viewMe');
        Route::patch('edit', 'updateMe');
    });
    Route::controller(ProjectController::class)->prefix('project')->group(function () {
        Route::get('/', 'viewAllProjects');
    });
    Route::prefix('log')->group(function () {
        Route::post('report', GenerateReportAction::class)->name('report');
        Route::post('report/pdf', GeneratePdfAction::class)->name('report-pdf');
    });
    Route::controller(TimeLogController::class)->prefix('log')->group(function () {
        Route::post('/', 'addActivity');
        Route::get('{id}', 'viewLogs');
        Route::patch('{id}', 'editActivity');
        Route::delete('{id}', 'removeActivity');
    });
    Route::middleware(['isAdmin'])->group(function () {
        Route::controller(AdminController::class)->prefix('admin')->group(function () {
            Route::get('users', 'viewAllUsers');
            Route::post('change-roles', 'assignRoles');
            Route::get('user-roles/{id}', 'viewUserRole');
            Route::post('invite', 'inviteOthers');
        });
        Route::prefix('admin')->group(function () {
            Route::delete('user/{id}', DeleteUserAction::class);
            Route::patch('user-status/{id}', UpdateUserStatusAction::class);
        });
        Route::controller(InviteController::class)->prefix('invite')->group(function () {
            Route::get('invited-users', 'listInvitedUsers');
            Route::post('resend', 'reInvite');
            Route::delete('revoke/{id}', 'revoke');
        });
        Route::controller(ProjectController::class)->prefix('project')->group(function () {
            Route::post('/', 'addActivity');
            Route::patch('{id}', 'updateActivity');
            Route::patch('project-status/{id}', 'updateProjectStatus');
            Route::delete('{id}', 'deleteProject');
        });
        Route::apiResource('client', ClientController::class)->except(['show']);
    });
    Route::middleware(['project.status'])->group(function () {
        Route::controller(ProjectController::class)->prefix('project')->group(function () {
            Route::patch('billable-status/{id}', 'updateBillableStatus');
        });
    });
});
