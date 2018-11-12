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



Route::middleware(['guest'])->namespace('Auth')->group(function(){
    Route::get('/', 'LoginController@showLoginForm')->name('home');
    Route::post('ingresar', 'LoginController@login')->name('login');
});

Route::middleware('auth')->group(function(){
  Route::get('principal', function () {
      return view('contenido.contenido');
  })->name('principal');

  Route::namespace('Auth')->group(function(){
    Route::post('salir', 'LoginController@logout')->name('logout');
  });
  
  Route::middleware('Almacen')->group(function(){
    require base_path('routes/routes/categoria.php');

    require base_path('routes/routes/articulo.php');

    require base_path('routes/routes/proveedor.php');
  });

  Route::middleware('Vendedor')->group(function(){
    require base_path('routes/routes/cliente.php');
  });

  Route::middleware('Administrador')->group(function(){
    require base_path('routes/routes/categoria.php');

    require base_path('routes/routes/articulo.php');

    require base_path('routes/routes/proveedor.php');

    require base_path('routes/routes/cliente.php');

    Route::get('roles','RolController@index')->name('roles.index');
    Route::get('roles/select','RolController@select')->name('roles.select');

    Route::put('usuarios/activar/{usuario_uuid}','UsuarioController@activar')->name('usuarios.active');
    Route::put('usuarios/desactivar/{usuario_uuid}','UsuarioController@desactivar')->name('usuarios.desactive');

    Route::resource('usuarios', 'UsuarioController')->parameters([
        'usuarios' => 'usuario'
    ])->only(['index','store','update']);

  });


});
