<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\FacturaController;

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

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

# Productos
Route::get('listarproductos', [ProductoController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::post('checksession', [PassportAuthController::class, 'check_session']);
    Route::post('logout', [PassportAuthController::class, 'logout']);
});

Route::middleware(['auth:api', 'scope:admin'])->group(function () {
    // Route::resource('productos', ProductoController::class);
    # Productos
    Route::post('crearproducto', [ProductoController::class, 'store']);
    Route::post('editarproducto/{id}', [ProductoController::class, 'update']);
    Route::post('eliminarproducto/{id}', [ProductoController::class, 'destroy']);
    # Facturas
    Route::post('generarfactura', [FacturaController::class, 'store']);
    Route::post('listarfacturas', [FacturaController::class, 'listar_facturas']);
    Route::post('detallefactura', [FacturaController::class, 'detalle_factura']);
});

Route::middleware(['auth:api', 'scope:cliente'])->group(function () {
    Route::post('agregarcompra', [CompraController::class, 'store']);
});

Route::get('/login', function () {
    return view('login');
});
Route::get('/inicio', function () {
    return view('inicio');
});
Route::get('/crudproductos', function () {
    return view('productos');
});
