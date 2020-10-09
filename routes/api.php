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

use App\Http\Controllers\BannerController;

use App\Http\Controllers\promocodeController;

use App\Http\Controllers\Controller;

use App\Models\Article;
use App\Models\Video;
use App\Models\Tag;
use App\Models\Recipes;
use App\Models\User;
use App\Models\Comment;


use App\Http\Resources\ArticlesResource;


Route::post('/v1/auth', [UserController::class, 'auth']);
Route::post('/v1/logout', [UserController::class, 'logout'])->middleware('auth:api');
Route::post('/v1/userfavorites', [UserController::class, 'userFavorites'])->middleware('auth:api');
Route::apiResource('/v1/user', UserController::class)->middleware('auth:api');
Route::post('/v1/user/addtointeresteds', [UserController::class, 'interesteds'])->middleware('auth:api');


Route::apiResource('/v1/video', VideoController::class);
Route::apiResource('/v1/videoCatagory', VideoCatagoryController::class);
Route::apiResource('/v1/videotype', VideoTypeController::class);
Route::post('/v1/video/addtofavorites', [VideoController::class, 'addToFavorites'])->middleware('auth:api');
Route::post('/v1/video/removefromfavorites', [VideoController::class, 'removeFromFavorites'])->middleware('auth:api');
Route::post('/v1/video/videofillter', [VideoController::class, 'videoFillter']);


Route::apiResource('/v1/article', ArticlesController::class);
Route::apiResource('/v1/articleCatagory', ArticlesCatagoryController::class);
Route::apiResource('/v1/articleTag', ArticlesTagController::class);
Route::post('/v1/article/addtofavorites', [ArticlesController::class, 'addToFavorites'])->middleware('auth:api');
Route::post('/v1/article/removefromfavorites', [ArticlesController::class, 'removeFromFavorites'])->middleware('auth:api');
Route::post('/v1/article/articlefillter', [ArticlesController::class, 'articleFillter']);


Route::apiResource('/v1/recipes', RecipesController::class);
Route::apiResource('/v1/recipesCatagory', RecipesCatagoryController::class);
Route::post('/v1/recipes/addtofavorites', [RecipesController::class, 'addToFavorites'])->middleware('auth:api');
Route::post('/v1/recipes/removefromfavorites', [RecipesController::class, 'removeFromFavorites'])->middleware('auth:api');
Route::post('/v1/recipes/recipesfillter', [RecipesController::class, 'RecipesFillter']);


Route::apiResource('/v1/banner', BannerController::class);

Route::apiResource('/v1/promocode', promocodeController::class);