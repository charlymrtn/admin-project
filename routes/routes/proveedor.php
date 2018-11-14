<?php

Route::resource('proveedores', 'ProveedorController')->parameters([
    'proveedores' => 'proveedor'
])->only(['index','store','update']);

Route::get('proveedores/seleccionar','ProveedorController@select')->name('proveedores.select');
