<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Users\DashController;
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
    | User Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::middleware(['auth', 'verified'])->group(function () {

        Route::prefix('admin')
            ->middleware(['is_admin'])
            ->name('admin.')
            ->group(function () {
                Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
                Route::get('/view_user/{user_id}', [AdminController::class, 'viewUser'])->name('view_user')->where('user_id', '[0-9]+');
                Route::get('/view_users', [AdminController::class, 'viewAllUsers'])->name('view_users');
                Route::post('/find_user', [AdminController::class, 'findUser'])->name('find_user');
                Route::get('/delete_user/{user_id}', [AdminController::class, 'deleteUser'])->name('delete_user')->where('user_id', '[0-9]+');
                Route::get('/clone_user/{user_id}', [AdminController::class, 'cloneUser'])->name('clone_user')->where('user_id', '[0-9]+');
                Route::get('/user_status/{user_id}', [AdminController::class, 'changeUserStatus'])->name('user_status')->where('user_id', '[0-9]+');
            });

        Route::prefix('user')
            ->name('user.')
            ->group(function () {
                Route::get('/dashboard', [DashController::class, 'index'])->name('dashboard');
                Route::get('/user_profile', [DashController::class, 'viewProfile'])->name('user_profile');
            });


    });



    require __DIR__.'/auth.php';

}catch (\Throwable $e){
    Log::error($e->getMessage());
    return view('welcome');
}
