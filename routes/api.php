<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\VideoCatagoryController;
use App\Http\Controllers\VideoTypeController;
use App\Http\Controllers\VideoController;

use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\ArticlesCatagoryController;
use App\Http\Controllers\ArticlesTagController;

use App\Http\Controllers\RecipesController;
use App\Http\Controllers\RecipesCatagoryController;

use App\Http\Controllers\Controller;



Route::post('/v1/auth', [UserController::class, 'auth']);


Route::apiResource('/v1/user', UserController::class)->middleware('auth:api');

Route::apiResource('/v1/video', VideoController::class);
Route::apiResource('/v1/videoCatagory', VideoCatagoryController::class);
Route::apiResource('/v1/videotype', VideoTypeController::class);


Route::apiResource('/v1/article', ArticlesController::class);
Route::apiResource('/v1/articleCatagory', ArticlesCatagoryController::class);
Route::apiResource('/v1/articleTag', ArticlesTagController::class);

Route::apiResource('/v1/recipes', RecipesController::class);
Route::apiResource('/v1/recipesCatagory', RecipesCatagoryController::class);

// Route::resource('/v1/user', UserController::class, ['except' => ['']]);

// Route::resource('users', UserController::class, ['except' => ['']])->middleware('auth:api');