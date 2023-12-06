<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ItemController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->prefix('user')->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');

});

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::post('register', 'register');
});


Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::controller(ItemController::class)->prefix('items')->group(function () {
        Route::get('/', 'index');
        Route::post('add', 'add');
        Route::get('edit/{id}', 'edit');
        Route::post('update/{id}', 'update');
        Route::delete('delete/{id}', 'delete');
        Route::put('toggle-publish/{id}', 'togglePublish');
    });

    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/', 'index');
        Route::get('/get/no_pagination', 'getNoPagination');
        Route::post('add', 'add');
        Route::get('edit/{id}', 'edit');
        Route::post('update/{id}', 'update');
        Route::delete('delete/{id}', 'delete');
        Route::put('toggle-publish/{id}', 'togglePublish');
    });
});

