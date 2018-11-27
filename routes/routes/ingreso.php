<?php

Route::put('ingresos/desactivar/{ingreso_uuid}','IngresoController@desactivar')->name('ingresos.desactive');
Route::get('ingresos/cabecera/{ingreso_uuid}','IngresoController@cabecera')->name('ingresos.cabecera');
Route::get('ingresos/detalles/{ingreso_uuid}','IngresoController@detalles')->name('ingresos.detalles');

Route::resource('ingresos', 'IngresoController')->parameters([
    'ingresos' => 'ingreso'
])->only(['index','store']);
