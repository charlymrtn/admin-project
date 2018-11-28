<?php

Route::resource('clientes', 'ClienteController')->parameters([
    'clientes' => 'cliente'
])->only(['index','store','update']);

Route::get('clientes/seleccionar','ClienteController@select')->name('clientes.select');

?>
