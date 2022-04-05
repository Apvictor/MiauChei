<?php

use App\Http\Controllers\App\Auth\LoginController;
use App\Http\Controllers\App\Auth\RegisterController;
use App\Http\Controllers\App\PetsController;
use App\Http\Controllers\App\UserController;
use Illuminate\Support\Facades\Route;

/* Auth */
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    /* User */
    Route::get('profile', [UserController::class, 'getProfile']);
    Route::post('profile', [UserController::class, 'postProfile']);

    /* Pets */
    Route::post('pets-store', [PetsController::class, 'petsStore']);
    Route::get('recents', [PetsController::class, 'recents']);
    Route::get('mypets', [PetsController::class, 'myPets']);
    Route::get('pets-lost', [PetsController::class, 'petsLost']);
    Route::get('pets-sighted', [PetsController::class, 'petsSighted']);
    Route::get('pets-details/{id}', [PetsController::class, 'petsDetails']);
    Route::get('pet-sightings/{id}', [PetsController::class, 'petSightings']);
    Route::post('pets-sighted-store', [PetsController::class, 'petsSightedStore']);
    Route::put('pet-found/{id}', [PetsController::class, 'petFound']);
});
