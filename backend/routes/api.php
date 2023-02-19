<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Backend\Authen;
use App\Http\Controllers\Api\V1\Client\ClientAccountController;
use App\Http\Controllers\Api\V1\Client\PostController;

use  App\Http\Controllers\Api\V1\Backend\CategoryController;

use App\Http\Middleware\AdminAuthentication;
use App\Http\Middleware\ClientAuthentication;

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
Route::get("/", function () {
    return view('welcome');
});

Route::post('/register', [ClientAccountController::class, 'register']);
Route::get('/active-account/{id}/{token}', [ClientAccountController::class, 'active']);
Route::post('/login', [ClientAccountController::class, 'login']);
Route::post('/logout', [ClientAccountController::class, 'logout']);

Route::post('/forgot-password', [ClientAccountController::class, 'forgotPassword']);
Route::post('/change-password/{id}/{token}', [ClientAccountController::class, 'changePasswordForgot']);
Route::post('/logout', [ClientAccountController::class, 'logout']);
Route::get('/posts', [PostController::class, 'index']);

Route::middleware([ClientAuthentication::class])->group(function () {
    Route::post('/posts/add/story', [PostController::class, 'store']);
    Route::get('/posts/list/story', [PostController::class, 'getListPostUser']);
    Route::post('/posts/update/story/{id}', [PostController::class, 'update']);
});



// backend
Route::get('category', [CategoryController::class, 'index']);

Route::post('backend/admin/login', [Authen::class, 'loginAdmin']);
Route::post('check/level/user', [Authen::class, 'checkLevelUser']);

Route::middleware([AdminAuthentication::class])->group(function () {
    Route::post('/backend/category', [CategoryController::class, 'store']);
    Route::patch('/backend/category/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/backend/category/delete/{id}', [CategoryController::class, 'destroy']);
});
