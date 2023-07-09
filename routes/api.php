<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function() {

    //
    Route::get("post/deleted",[PostController::class,"deletedPosts"]);
    Route::get("post/restore/{id}",[PostController::class,"restorPost"]);
    Route::post('logout',[AuthController::class, "logout"]);
    Route::apiResource("tag",TagController::class);
    Route::apiResource("post",PostController::class);


   });

Route::post('register',[AuthController::class, "register"]);
Route::post('login',[AuthController::class, "login"]);
Route::get('stats',[StatsController::class, "stats"]);



// Route::apiResource("tag",TagController::class);
//  Route::apiResource("post",PostController::class);


