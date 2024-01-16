<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SettingController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/admin', function () {
    if (auth('admins')->user()) {
        return redirect('admin/dashboard');
    } else {
        return redirect('admin/login');
    }
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'admin'], function () {

    //login
    Route::get('login', [AdminController::class, 'login_form'])->name('login.form');
    Route::post('login-functionality', [AdminController::class, 'login_functionality'])->name('login.functionality');

    Route::group(['middleware' => 'admin'], function () {

        //logout
        Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');

        //dashboard
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        //setting
        Route::get('web-setting', [SettingController::class, 'index'])->name('web.index');
        Route::post('web-setting', [SettingController::class, 'store']);

        //profile password
        Route::get('profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::post('profile', [AdminController::class, 'updateprofile'])->name('admin.profileupdate');
    });
});

require __DIR__ . '/auth.php';
