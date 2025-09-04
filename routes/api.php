<?php

use App\Http\Controllers\Api\EmpresaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Custom route for deleting inactive empresas (must be before apiResource)
Route::delete('/empresas/inactive', [EmpresaController::class, 'deleteInactive']);

// Empresas API Routes
Route::apiResource('empresas', EmpresaController::class, [
    'parameters' => ['empresas' => 'nit']
]);

// Override the default destroy route since we don't want it to be available
Route::match(['DELETE'], '/empresas/{nit}', [EmpresaController::class, 'destroy']);
