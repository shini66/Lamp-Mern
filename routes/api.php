<?php

use App\Http\Controllers\api\MongoController;
use App\Http\Controllers\UsersTestController;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/productos', [MongoController::class, 'products']);
Route::get('/users', [MongoController::class, 'users']);
Route::get('/sales', [MongoController::class, 'sales']);
//Route::get('/groupSales', [MongoController::class, 'groupSales']);
//Route::get('/filter/users', [MongoController::class, 'filterAgeUser']);
//Route::get('/users/status', [MongoController::class, 'filterUser']);
//Route::get('/sales/search', [MongoController::class, 'searchSales']);

Route::post('/login', [MongoController::class, 'login']);

Route::middleware('auth:sanctum')->get('/protected', function (Request $request) {
    
    if($request->status == 1){
        return response()->json(['message' => 'Ruta protegida'], 201);
    }else{
        return response()->json(['message' => 'Falta de pago'], 403);
    }

});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/groupSales', [MongoController::class, 'groupSales']);
    Route::get('/filter/users', [MongoController::class, 'filterAgeUser']);
    Route::get('/users/status', [MongoController::class, 'filterUser']);
    Route::get('/sales/search', [MongoController::class, 'searchSales']);
});

Route::post('/register', [UsersTestController::class, 'register']);