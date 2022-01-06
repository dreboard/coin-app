<?php

use App\Http\Controllers\Admin\AdminBanUserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserActionsController;
use App\Http\Controllers\Users\DashController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

try {

    Route::get('/', function () {
        return view('welcome');
    });
    /*
    |--------------------------------------------------------------------------
    | Site web Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::middleware(['auth', 'verified'])->group(function () {

        /*
        |-------------------------------------------------------------------------------
        | Admin section
        |-------------------------------------------------------------------------------
        | Prefix:         admin/ OR .admin
        | Controller:     Admin/AdminController
        | Method:         MIXED
        | Description:    Admin actions
        */
        Route::prefix('admin')
            ->middleware(['is_admin'])
            ->name('admin.')
            ->group(function () {

                Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

                // User actions
                Route::get('/view_user/{user_id}', [AdminUserActionsController::class, 'viewUser'])->name('view_user')->where('user_id', '[0-9]+');
                Route::get('/view_users', [AdminUserActionsController::class, 'viewAllUsers'])->name('view_users');
                Route::post('/find_user', [AdminUserActionsController::class, 'findUser'])->name('find_user');
                Route::get('/delete_user/{user_id}', [AdminUserActionsController::class, 'deleteUser'])->name('delete_user')->where('user_id', '[0-9]+');
                Route::get('/user_status/{user_id}', [AdminUserActionsController::class, 'changeUserStatus'])->name('user_status')->where('user_id', '[0-9]+');

                // Clone users
                Route::get('/clone_user/{user_id}', [AdminUserActionsController::class, 'cloneUser'])->name('clone_user')->where('user_id', '[0-9]+');

                // Banned users
                Route::post('/ban_user', [AdminBanUserController::class, 'banUser'])->name('ban_user');
                Route::get('/unban_user/{user_id}', [AdminBanUserController::class, 'unbanUser'])->name('unban_user')->where('user_id', '[0-9]+');
                Route::get('/view_banned_users', [AdminBanUserController::class, 'viewAllBannedUsers'])->name('view_banned_users');

            });

        /*
        |-------------------------------------------------------------------------------
        | User section
        |-------------------------------------------------------------------------------
        | Prefix:         user/ OR .user
        | Controller:     Users/UserController
        | Method:         MIXED
        | Description:    User actions
        */
        Route::prefix('user')
            ->name('user.')
            ->middleware(['forbid-banned-user', 'is_online'])
            ->group(function () {
                Route::get('/dashboard', [DashController::class, 'index'])->name('dashboard');
                Route::get('/user_profile', [UserController::class, 'viewProfile'])->name('user_profile');
                Route::get('/user_change_password', [UserController::class, 'changePassword'])->name('user_change_password');
                Route::post('/user_save_password', [UserController::class, 'savePassword'])->name('user_save_password');
                Route::post('/user_change_visibility', [UserController::class, 'toggleVisibility'])->name('user_change_visibility');
            });


    });



    /*
    |-------------------------------------------------------------------------------
    | Auth Routes
    |-------------------------------------------------------------------------------
    | Prefix:         NONE
    | Controller:     RegisteredUserController, AuthenticatedSessionController, PasswordResetLinkController
    | Method:         MIXED
    | Description:    Auth routes
    */
    require __DIR__.'/auth.php';

}catch (\Throwable $e){
    Log::error($e->getMessage());
    return view('welcome');
}
