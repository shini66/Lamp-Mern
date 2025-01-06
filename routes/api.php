<?php

use App\Http\Controllers\api\MongoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/productos', [MongoController::class, 'products']);
Route::get('/users', [MongoController::class, 'users']);
Route::get('/sales', [MongoController::class, 'sales']);
Route::get('/groupSales', [MongoController::class, 'groupSales']);
Route::get('/filter/users', [MongoController::class, 'filterAgeUser']);
Route::get('/users/status', [MongoController::class, 'filterUser']);
Route::get('/sales/search', [MongoController::class, 'searchSales']);