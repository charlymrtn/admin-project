<?php

Route::resource('categorias', 'CategoriaController')->parameters([
    'categorias' => 'categoria'
])->only(['index','store','update']);

Route::put('categorias/activar/{categoria_uuid}','CategoriaController@activar')->name('categorias.active');
Route::put('categorias/desactivar/{categoria_uuid}','CategoriaController@desactivar')->name('categorias.desactive');

Route::get('categorias/seleccionar','CategoriaController@select')->name('categorias.select');
