<?php

use App\Http\Controllers\App\Auth\LoginController;
use App\Http\Controllers\App\Auth\RegisterController;
use App\Http\Controllers\App\FoundController;
use App\Http\Controllers\App\PetsController;
use App\Http\Controllers\App\SightedController;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\App\UserDeviceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/* Auth */
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    /* User */
    Route::get('profile', [UserController::class, 'getProfile']);
    Route::post('profile', [UserController::class, 'postProfile']);
    Route::post('logout', [UserController::class, 'logout']);

    /* Pets */
    Route::post('pets-store', [PetsController::class, 'petsStore']);
    Route::get('recents', [PetsController::class, 'recents']);
    Route::get('mypets', [PetsController::class, 'myPets']);
    Route::get('pets-lost', [PetsController::class, 'petsLost']);
    Route::get('pets-details/{id}', [PetsController::class, 'petsDetails']);

    /* Avistamento */
    Route::get('pets-sighted', [SightedController::class, 'petsSighted']);
    Route::get('pet-sightings/{id}', [SightedController::class, 'petSightings']);
    Route::post('pets-sighted-store', [SightedController::class, 'petsSightedStore']);

    /* Encontrado */
    Route::put('pet-found/{id}', [FoundController::class, 'petFound']);

    /* OneSignal */
    Route::prefix('notification')->group(function () {
        Route::post('/send', [FoundController::class, 'sendNotification']);
        Route::post('/register/device', [UserDeviceController::class, 'registerDevice']);
        Route::post('/update/status/{playerId}', [UserDeviceController::class, 'updateNotificationStatus']);
    });
});
