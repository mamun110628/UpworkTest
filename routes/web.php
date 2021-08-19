<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/user/register/{code}', [App\Http\Controllers\HomeController::class, 'user_register'])->name('user.register');
Route::post('/register/user', [App\Http\Controllers\HomeController::class, 'user_register_store'])->name('register.user');
Route::get('/verify/user/{token}', [App\Http\Controllers\HomeController::class, 'verify_user'])->name('verify.user');
Route::post('/verification', [App\Http\Controllers\HomeController::class, 'verification'])->name('verification');
Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
Route::post('/update/user', [App\Http\Controllers\HomeController::class, 'update_profile'])->name('update.user');
Route::post('/login-user', [App\Http\Controllers\LoginController::class, 'login'])->name('custom.login');
Route::post('/admin/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('admin.logout');
Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('profile-setting', [App\Http\Controllers\HomeController::class, 'profile_setting'])->name('admin_profile.setting');
    
    Route::post('post-invite', [App\Http\Controllers\HomeController::class, 'post_invite'])->name('post.invite');
});
