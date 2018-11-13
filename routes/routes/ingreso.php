<?php

Route::put('ingresos/desactivar/{ingreso_uuid}','IngresoController@desactivar')->name('ingresos.desactive');

Route::resource('ingresos', 'IngresoController')->parameters([
    'ingresos' => 'ingreso'
])->only(['index','store']);
