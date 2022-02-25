<?php

use App\Http\Controllers\App\Auth\LoginController;
use App\Http\Controllers\App\Auth\RegisterController;
use App\Http\Controllers\App\PetsController;
use App\Http\Controllers\App\UserController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')
    ->group(function () {

        Route::get('profile', [UserController::class, 'getProfile']);

        Route::get('recents', [PetsController::class, 'recents']);
        Route::get('mypets', [PetsController::class, 'myPets']);
        Route::get('pets-lost', [PetsController::class, 'petsLost']);
        Route::get('pets-sighted', [PetsController::class, 'petsSighted']);
        Route::get('pets-details/{id}', [PetsController::class, 'petsDetails']);
    });
