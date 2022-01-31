<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PetsController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});

Route::middleware('auth')
    ->group(function () {
        Route::get('home', [HomeController::class, 'index'])->name('home.index');

        // Usuários
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{id}/update', [UserController::class, 'update'])->name('users.update');
        Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::delete('users/{id}/delete', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('users/search', [UserController::class, 'search'])->name('users.search');

        // Status
        Route::get('status', [StatusController::class, 'index'])->name('status.index');
        Route::get('status/create', [StatusController::class, 'create'])->name('status.create');
        Route::post('status/store', [StatusController::class, 'store'])->name('status.store');
        Route::get('status/{id}/edit', [StatusController::class, 'edit'])->name('status.edit');
        Route::put('status/{id}/update', [StatusController::class, 'update'])->name('status.update');
        Route::get('status/{id}', [StatusController::class, 'show'])->name('status.show');
        Route::delete('status/{id}/delete', [StatusController::class, 'destroy'])->name('status.destroy');
        Route::post('status/search', [StatusController::class, 'search'])->name('status.search');

        Route::get('pets', [PetsController::class, 'index'])->name('pets.index');
    });
