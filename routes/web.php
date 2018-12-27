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

  Route::get('/dashboard','DashboardController')->name('dashboard');

  Route::post('notificacion/get','NotificacionController@get')->name('notificaciones.get');

  Route::middleware('Almacen')->group(function(){
    require base_path('routes/routes/categoria.php');

    require base_path('routes/routes/articulo.php');

    require base_path('routes/routes/proveedor.php');

    require base_path('routes/routes/ingreso.php');
  });

  Route::middleware('Vendedor')->group(function(){
    require base_path('routes/routes/cliente.php');

    require base_path('routes/routes/venta.php');
  });

  Route::middleware('Administrador')->group(function(){
    require base_path('routes/routes/categoria.php');

    require base_path('routes/routes/articulo.php');

    require base_path('routes/routes/proveedor.php');

    require base_path('routes/routes/cliente.php');

    require base_path('routes/routes/ingreso.php');

    require base_path('routes/routes/rol.php');

    require base_path('routes/routes/usuario.php');

    require base_path('routes/routes/venta.php');
  });


});
