<?php

use App\Http\Controllers\User\Auth\LoginController;
use \App\Http\Controllers\User\Auth\RegisterController;
use \App\Http\Controllers\User\Auth\VerificationController;
use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\User\Auth\ResetPasswordController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\RoleController;

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for users
|
*/
Route::middleware('auth:api_user')->group(function() {
    //User Profile
    Route::get('logs', [ProfileController::class, 'logs']);
    Route::post('profile/update', [ProfileController::class, 'updateProfile']);
    Route::get('profile', [ProfileController::class, 'getProfile']);
    Route::post('business-information/update', [ProfileController::class, 'updateBusinessInformation']);
    Route::get('business-information', [ProfileController::class, 'getBusinessInformation']);
    Route::post('other-information/update', [ProfileController::class, 'updateOtherInformation']);
    Route::get('other-information', [ProfileController::class, 'getOtherInformation']);
    Route::get('validate/liveizy/{user:liveizy_id}', [ProfileController::class, 'validateUserId']);
    Route::get('get/details/{user:liveizy_id}', [ProfileController::class, 'getDetails']);

    //Assign Role
    Route::post('roles/assign', [RoleController::class, 'assignRole']);
    Route::delete('roles/assign', [RoleController::class, 'removeRole']);
});

// Authentications
Route::group(['prefix' => 'auth', 'namespace' => 'User\Auth'], function() {
    Route::post('login', [LoginController::class, 'login']);
    Route::get('logout', [LoginController::class, 'logout']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('/email/verify', [VerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::get('/email/resend-verification', [VerificationController::class, 'resend']);

    Route::prefix('password')->group(function () {
        Route::post('forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::post('reset', [ResetPasswordController::class, 'reset']);
    });
});
