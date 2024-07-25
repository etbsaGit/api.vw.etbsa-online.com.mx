<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Intranet\TypeController;
use App\Http\Controllers\Intranet\BrandController;
use App\Http\Controllers\Intranet\PriceController;
use App\Http\Controllers\Intranet\StateController;
use App\Http\Controllers\Intranet\FeatureController;
use App\Http\Controllers\Intranet\VehicleController;
use App\Http\Controllers\Intranet\MunicipalityController;
use App\Http\Controllers\Intranet\VehicleFeatureController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'cors'])->group(function () {

    Route::get('municipality/state/{id}', [MunicipalityController::class, 'getPerState']);
    Route::get('type/key/{key}', [TypeController::class, 'getPerKey']);
    Route::get('feature/type/{type}', [FeatureController::class, 'getPerType']);
    Route::get('vehicle/options', [VehicleController::class, 'getOptions']);
    Route::post('vehicles', [VehicleController::class, 'index']);
    Route::get('vehicleFeature/vehicle/{id}', [VehicleFeatureController::class, 'getPerVehicle']);
    Route::get('price/vehicle/{id}', [PriceController::class, 'getPerVehicle']);

    Route::apiResource('state', StateController::class);
    Route::apiResource('municipality', MunicipalityController::class);
    Route::apiResource('type', TypeController::class);
    Route::apiResource('feature', FeatureController::class);
    Route::apiResource('brand', BrandController::class);
    Route::apiResource('vehicle', VehicleController::class);
    Route::apiResource('vehicleFeature', VehicleFeatureController::class);
    Route::apiResource('price', PriceController::class);

});

