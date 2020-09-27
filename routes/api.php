<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoCatagoryController;
use App\Http\Controllers\VideoController;

use App\Http\Controllers\Controller;


Route::post('/v1/auth', [UserController::class, 'auth']);

Route::apiResource('/v1/user', UserController::class);

Route::apiResource('/v1/VideoCatagory', VideoCatagoryController::class);

Route::apiResource('/v1/Video', VideoController::class);


// Route::resource('/v1/user', UserController::class, ['except' => ['']]);

// Route::resource('users', UserController::class, ['except' => ['']])->middleware('auth:api');