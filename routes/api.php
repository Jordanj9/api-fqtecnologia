<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('register', [AuthController::class,'register'])->middleware('cors');
    Route::post('login', [AuthController::class,'login'])->middleware('cors');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [AuthController::class,'logout']);

    Route::controller(ProjectController::class)->group(function () {
        Route::get('/project', [ProjectController::class,'index']);
        Route::get('/project/{id}', [ProjectController::class,'show']);
        Route::post('/project', [ProjectController::class,'store']);
        Route::put('/project/update/{id}', [ProjectController::class,'update']);
        Route::delete('/project/{id}/delete', [ProjectController::class,'destroy']);
        Route::get('/project/report/mouth',[ProjectController::class,'mouthReport']);
        Route::get('/project/report/type',[ProjectController::class,'projectTypeReport']);
    });
});


