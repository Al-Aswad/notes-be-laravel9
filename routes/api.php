<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\TagController;
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


Route::group(['prefix' => 'v1'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::post('me', 'me');
    });
    Route::controller(NoteController::class)->group(function () {
        Route::post('notes', 'create');
        Route::get('notes', 'index');
        Route::get('notes/{id}', 'show');
        Route::put('notes/{id}', 'update');
        Route::delete('notes/{id}', 'delete');
    });
    Route::controller(CategoryController::class)->group(function () {
        Route::post('categories', 'create');
        Route::get('categories', 'index');
        Route::get('categories/{id}', 'show');
        Route::put('categories/{id}', 'update');
        Route::delete('categories/{id}', 'delete');
    });
    Route::controller(TagController::class)->group(function () {
        Route::post('tags', 'create');
        Route::get('tags', 'index');
        Route::get('tags/{id}', 'show');
        Route::put('tags/{id}', 'update');
        Route::delete('tags/{id}', 'delete');
    });
});
