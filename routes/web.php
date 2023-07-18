<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
    // Route::get('earthquakeLatest', [PageController::class, 'earthquakeLatest'])->name("earthquakeLatest");
Route::middleware(['auth'])->group(function () {
    Route::get('/', [PageController::class, 'index']);
    Route::get('weather', [PageController::class, 'weather']);
    Route::get('earthquake', [PageController::class, 'earthquake']);
    Route::get('hydrometeorology', [PageController::class, 'hydrometeorology']);
    Route::get('forestfire', [PageController::class, 'forestfire']);
    Route::get('volcano', [PageController::class, 'volcano']);
    Route::get('radar', [PageController::class, 'radar']);
    Route::get('satellite', [PageController::class, 'satellite']);
    Route::get('observation', [PageController::class, 'observation']);
    Route::get('dibi', [PageController::class, 'dibi']);
    Route::get('inarisk', [PageController::class, 'inarisk']);
    Route::get('testing', [PageController::class, 'testing']);


    Route::get('getDetailForecast', [DataController::class, 'getDetailForecast']);
    Route::get('getDibi', [DataController::class, 'getDibi']);
    Route::get('getDibiRegency', [DataController::class, 'getDibiRegency']);
    Route::get('getDibiProvince', [DataController::class, 'getDibiProvince']);
    Route::get('getHazard', [DataController::class, 'getHazard']);
    Route::get('getHotspot', [DataController::class, 'getHotspot']);
    Route::get('getWindmap', [DataController::class, 'getWindmap']);
    Route::get('getObservations', [DataController::class, 'getObservations']);
});

    // Route::get('test', [PageController::class, 'test']);
    require __DIR__.'/auth.php';