<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Intranet\SaleController;
use App\Http\Controllers\Intranet\TypeController;
use App\Http\Controllers\Intranet\BrandController;
use App\Http\Controllers\Intranet\PriceController;
use App\Http\Controllers\Intranet\QuoteController;
use App\Http\Controllers\Intranet\StateController;
use App\Http\Controllers\Intranet\AgencyController;
use App\Http\Controllers\Intranet\StatusController;
use App\Http\Controllers\Intranet\TargetController;
use App\Http\Controllers\Intranet\FeatureController;
use App\Http\Controllers\Intranet\VehicleController;
use App\Http\Controllers\Intranet\CustomerController;
use App\Http\Controllers\Intranet\EmployeeController;
use App\Http\Controllers\Intranet\FollowUpController;
use App\Http\Controllers\Intranet\PositionController;
use App\Http\Controllers\Intranet\SaleDateController;
use App\Http\Controllers\Intranet\InventoryController;
use App\Http\Controllers\Intranet\AdditionalController;
use App\Http\Controllers\Intranet\DepartmentController;
use App\Http\Controllers\Intranet\FailedSaleController;
use App\Http\Controllers\Intranet\VehicleDocController;
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
    Route::get('price/inventory/{id}', [PriceController::class, 'getPerVehicle']);
    Route::get('vehicleDoc/vehicle/{id}', [VehicleDocController::class, 'getPerVehicle']);
    Route::post('customers', [CustomerController::class, 'index']);
    Route::get('customer/options', [CustomerController::class, 'getOptions']);
    Route::post('customer/excel', [CustomerController::class, 'insetExcel']);
    Route::get('status/key/{key}', [StatusController::class, 'getPerKey']);
    Route::post('employees', [EmployeeController::class, 'index']);
    Route::get('employee/options', [EmployeeController::class, 'getOptions']);
    Route::post('employee/zone/{employee}', [EmployeeController::class, 'attachMunicipalities']);
    Route::get('employee/zone/{employee}', [EmployeeController::class, 'getMunicipalities']);
    Route::post('sales', [SaleController::class, 'index']);
    Route::get('sale/options', [SaleController::class, 'getOptions']);
    Route::get('saleDate/sale/{id}', [SaleDateController::class, 'getPerSale']);
    Route::get('target/employee/{id}', [TargetController::class, 'getPerEmployee']);
    Route::get('targets/{year}', [TargetController::class, 'getTarget']);
    Route::get('targets/{month}/{year}/{agency}', [TargetController::class, 'getTargetsEmployee']);
    Route::post('inventories', [InventoryController::class, 'index']);
    Route::get('inventories/options', [InventoryController::class, 'getOptions']);
    Route::get('followUp/options', [FollowUpController::class, 'getOptions']);
    Route::post('followUps', [FollowUpController::class, 'index']);
    Route::post('followUp/next/{followUp}', [FollowUpController::class, 'nextFollowUp']);
    Route::get('followUp/lost/{followUp}', [FollowUpController::class, 'saleLost']);
    Route::get('followUp/win/{followUp}', [FollowUpController::class, 'saleWin']);
    Route::get('followUp/active/{followUp}', [FollowUpController::class, 'saleActive']);
    Route::get('followUp/all', [FollowUpController::class, 'allFollow']);
    Route::post('inventories/quote/{inventory}', [InventoryController::class, 'getPDFQuote']);
    Route::get('quotes/followUp/{followUp}', [QuoteController::class, 'getPerFollow']);
    Route::get('quotes/options', [QuoteController::class, 'getOptions']);

    // --Reportes--
    Route::post('sales/report/agency/all', [SaleController::class, 'getAgency']);
    Route::post('sales/report/agency/pdf', [SaleController::class, 'createPDF']);

    // --Api resourse--
    Route::apiResource('state', StateController::class);
    Route::apiResource('municipality', MunicipalityController::class);
    Route::apiResource('type', TypeController::class);
    Route::apiResource('feature', FeatureController::class);
    Route::apiResource('brand', BrandController::class);
    Route::apiResource('vehicle', VehicleController::class);
    Route::apiResource('vehicleFeature', VehicleFeatureController::class);
    Route::apiResource('price', PriceController::class);
    Route::apiResource('vehicleDoc', VehicleDocController::class);
    Route::apiResource('customer', CustomerController::class);
    Route::apiResource('status', StatusController::class);
    Route::apiResource('agency', AgencyController::class);
    Route::apiResource('employee', EmployeeController::class);
    Route::apiResource('sale', SaleController::class);
    Route::apiResource('saleDate', SaleDateController::class);
    Route::apiResource('position', PositionController::class);
    Route::apiResource('target', TargetController::class);
    Route::apiResource('department', DepartmentController::class);
    Route::apiResource('inventory', InventoryController::class);
    Route::apiResource('followUp', FollowUpController::class);
    Route::apiResource('failedSale', FailedSaleController::class);
    Route::apiResource('quote', QuoteController::class);
    Route::apiResource('additional', AdditionalController::class);
});
