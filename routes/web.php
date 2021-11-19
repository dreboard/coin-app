<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Users\DashController;
use Illuminate\Support\Facades\Route;

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
            Route::get('/delete_user/{user_id}', [AdminController::class, 'deleteUser'])->name('delete_user');
            Route::get('/clone_user/{user_id}', [AdminController::class, 'cloneUser'])->name('clone_user');
            Route::get('/suspended_user/{user_id}', [AdminController::class, 'suspendedUser'])->name('suspended_user');
            Route::impersonate();
    });

    Route::prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('/dashboard', [DashController::class, 'index'])->name('dashboard');
            Route::get('/user_profile', [DashController::class, 'viewProfile'])->name('user_profile');
        });


});



require __DIR__.'/auth.php';
