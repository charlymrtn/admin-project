<?php

Route::put('usuarios/activar/{usuario_uuid}','UsuarioController@activar')->name('usuarios.active');
Route::put('usuarios/desactivar/{usuario_uuid}','UsuarioController@desactivar')->name('usuarios.desactive');

Route::resource('usuarios', 'UsuarioController')->parameters([
    'usuarios' => 'usuario'
])->only(['index','store','update']);
