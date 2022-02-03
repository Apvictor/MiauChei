<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PetsController;
use App\Http\Controllers\PetsFoundController;
use App\Http\Controllers\PetsLostController;
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

        // Pets Encontrados
        Route::get('pets/found', [PetsFoundController::class, 'foundIndex'])->name('pets.found.index');
        Route::post('pets/found/search', [PetsFoundController::class, 'foundSearch'])->name('pets.found.search');
        Route::get('pets/found/{id}', [PetsFoundController::class, 'lostPet'])->name('pets.found.lost');

        // Pets Perdidos
        Route::get('pets/lost', [PetsLostController::class, 'lostIndex'])->name('pets.lost.index');
        Route::get('pets/lost/create', [PetsLostController::class, 'lostCreate'])->name('pets.lost.create');
        Route::post('pets/lost/store', [PetsLostController::class, 'lostStore'])->name('pets.lost.store');
        Route::post('pets/lost/search', [PetsLostController::class, 'lostSearch'])->name('pets.lost.search');
        Route::get('pets/lost/{id}', [PetsLostController::class, 'foundPet'])->name('pets.lost.found');

        // Pets comum
        Route::get('pets/{id}/edit', [PetsController::class, 'edit'])->name('pets.edit');
        Route::get('pets/{id}', [PetsController::class, 'show'])->name('pets.show');
        Route::put('pets/{id}/update', [PetsController::class, 'update'])->name('pets.update');
        Route::delete('pets/{id}/delete', [PetsController::class, 'destroy'])->name('pets.destroy');
    });
