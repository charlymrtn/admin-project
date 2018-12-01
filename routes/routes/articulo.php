<?php

Route::resource('articulos', 'ArticuloController')->parameters([
    'articulos' => 'articulo'
])->only(['index','store','update']);

Route::put('articulos/activar/{articulo_uuid}','ArticuloController@activar')->name('articulos.active');
Route::put('articulos/desactivar/{articulo_uuid}','ArticuloController@desactivar')->name('articulos.desactive');

Route::get('articulos/buscar','ArticuloController@buscar')->name('articulos.search');
Route::get('articulos/listar','ArticuloController@listar')->name('articulos.list');
Route::get('articulos/buscarVenta','ArticuloController@buscarParaVenta')->name('articulos.searchPurchase');
Route::get('articulos/listarVenta','ArticuloController@listarParaVenta')->name('articulos.listPurchase');

Route::get('articulos/pdf','ArticuloController@listarPDF')->name('articulos.listPDF');
