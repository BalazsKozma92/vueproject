<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;

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

Route::get('/vehicles', [VehicleController::class, 'index']);
Route::get('/yaml', [VehicleController::class, 'getYaml']);
Route::prefix('/vehicle')->group(function() {
    Route::post('/store', [VehicleController::class, 'store']);
    Route::post('/addAxle/{id}', [VehicleController::class, 'addAxle']);
    Route::post('/removeAxle/{id}', [VehicleController::class, 'removeAxle']);
    Route::put('/{id}', [VehicleController::class, 'update']);
    Route::delete('/{id}', [VehicleController::class, 'destroy']);
});