<?php

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

Route::get('/', function () {
    return view('contenido.contenido');
});

Route::resource('categorias', 'CategoriaController')->parameters([
    'categorias' => 'categoria'
])->only(['index','store','update']);

Route::get('categorias/activar','CategoriaController@activar')->name('categorias.active');
Route::get('categorias/desactivar','CategoriaController@desactivar')->name('categorias.desactive');
