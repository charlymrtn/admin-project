<?php

Route::put('ventas/desactivar/{ingreso_uuid}','VentaController@desactivar')->name('ventas.desactive');
Route::get('ventas/cabecera/{ingreso_uuid}','VentaController@cabecera')->name('ventas.cabecera');
Route::get('ventas/detalles/{ingreso_uuid}','VentaController@detalles')->name('ventas.detalles');

Route::resource('ventas', 'VentaController')->parameters([
    'ventas' => 'venta'
])->only(['index','store','update']);

Route::get('ventas/{uuid}/pdf','VentaController@pdf')->name('ventas.pdf');
