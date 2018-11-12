<?php

Route::resource('proveedores', 'ProveedorController')->parameters([
    'proveedores' => 'proveedor'
])->only(['index','store','update']);
