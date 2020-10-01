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

Route::apiResource('/v1/banner', BannerController::class);

Route::apiResource('/v1/promocode', promocodeController::class);

// Route::resource('/v1/user', UserController::class, ['except' => ['']]);

// Route::resource('users', UserController::class, ['except' => ['']])->middleware('auth:api');

/****************************************************************************************** */
// Route::get('/v1/favoritevideo', function () {
//     $video = Video::find(1);	
 
//     dd($video->tags);
// });


// Route::get('/v1/favoritevideosave', function () {
//     $video = Video::find(2);	
 
//     // $tag = new Tag;
//     // $tag->name = "Madona";
     
//     $video->tags()->save();
//     // $video->tags()->save($tag);

// });


// Route::get('/v1/test', function () {
//    // getting comments for a sample page...
//   $video = Video::find(3);
// //   dd($video->comment);
//   $video->comments->save();
// //   foreach($video->comment as $comment)
// //   {
// //     // working with comment here...
// //   }
// });