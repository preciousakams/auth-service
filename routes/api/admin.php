<?php

use App\Http\Controllers\Administrator\Auth\LoginController;
use App\Http\Controllers\Administrator\Auth\RegisterController;
use App\Http\Controllers\Administrator\Auth\VerificationController;
use App\Http\Controllers\Administrator\Auth\ForgotPasswordController;
use App\Http\Controllers\Administrator\Auth\ResetPasswordController;
use App\Http\Controllers\Administrator\UserController;
use App\Http\Controllers\Administrator\AdministratorController;
use App\Http\Controllers\Administrator\PermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrator\OtherInformationController;

/*
|--------------------------------------------------------------------------
| Administrator API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for users
|
*/

Route::group(['prefix' => 'auth'], function() {
    Route::post('login', [LoginController::class, 'login']);
    Route::get('logout', [LoginController::class, 'logout']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('/email/verify/{id}', [VerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::get('/email/resend-verification', [VerificationController::class, 'resend']);

    Route::prefix('password')->group(function () {
        Route::post('forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::post('reset', [ResetPasswordController::class, 'reset']);
        Route::post('change', [ResetPasswordController::class, 'changePassword']);
    });
});

Route::group(['middleware' => ['auth:api_user', 'role:Super Admin', 'verified', 'change.password']], function() {

    //User
    Route::prefix('users')->group(function() {
        Route::get('', [UserController::class, 'index'])->middleware('permission:users fetch');
        Route::get('details/{user:email}', [UserController::class, 'details'])->middleware('permission:users details');
        Route::get('export', [UserController::class, 'export'])->middleware('permission:users export');
        Route::post('make/admin', [UserController::class, 'makeAdministrator'])->middleware('permission:users make administrator');
        Route::post('revoke/admin', [UserController::class, 'revokeAdministrator'])->middleware('permission:users revoke administrator');
        ///// Other information ////////
        Route::get('other-details/{user:email}', [OtherInformationController::class, 'details'])->middleware('permission:users details');

    });

    //Administrator
    Route::prefix('administrators')->group(function() {
        Route::get('', [AdministratorController::class, 'index'])->middleware('permission:administrators fetch');
        Route::get('export', [AdministratorController::class, 'export'])->middleware('permission:administrators export');
    });

    Route::prefix('permissions')->group(function () {
        Route::get('', [PermissionController::class, 'index'])->middleware('permission:permission fetch');
        Route::get('delete/{permission}', [PermissionController::class, 'delete'])->middleware('permission:permission delete');
        Route::post('create', [PermissionController::class, 'create'])->middleware('permission:permission create');
        Route::post('assign', [PermissionController::class, 'assign'])->middleware('permission:permission assign');
        Route::post('revoke', [PermissionController::class, 'revoke'])->middleware('permission:permission revoke');
        Route::get('user/{user:email}', [PermissionController::class, 'userPermissions'])->middleware('permission:permission see user');
    });

});

