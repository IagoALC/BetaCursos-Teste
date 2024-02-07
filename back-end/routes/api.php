<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/produtos', 'App\Http\Controllers\ProdutosController@lists');
Route::post('/produtos', 'App\Http\Controllers\ProdutosController@create');
Route::put('/produtos/{id}', 'App\Http\Controllers\ProdutosController@update');
Route::delete('/produtos/{id}', 'App\Http\Controllers\ProdutosController@delete');

Route::get('/categorias', 'App\Http\Controllers\CategoriasController@lists');
Route::post('/categorias', 'App\Http\Controllers\CategoriasController@create');
Route::put('/categorias/{id}', 'App\Http\Controllers\CategoriasController@update');
Route::delete('/categorias/{id}', 'App\Http\Controllers\CategoriasController@delete');
