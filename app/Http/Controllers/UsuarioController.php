<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Models\Persona;
use DB;

class UsuarioController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
      if (!$request->ajax()) return redirect('/');

      $buscar = $request->buscar;
      $criterio = $request->criterio;

      if ($buscar == '') {
        $usuarios = User::join('personas','usuarios.uuid','=','personas.uuid')
                        ->join('roles','usuarios.rol_uuid','=','roles.uuid')
                        ->select('personas.uuid','personas.nombre','personas.tipo_documento',
                        'personas.num_documento','personas.direccion','personas.telefono',
                        'personas.email','usuarios.usuario','usuarios.condicion','usuarios.rol_uuid','roles.nombre as rol')
                        ->orderBy('personas.created_at')->paginate(4);
      }else{

        $usuarios = User::join('personas','usuarios.uuid','=','personas.uuid')
                        ->join('roles','usuarios.rol_uuid','=','roles.uuid')
                        ->select('personas.uuid','personas.nombre','personas.tipo_documento',
                        'personas.num_documento','personas.direccion','personas.telefono',
                        'personas.email','usuarios.usuario','usuarios.condicion','usuarios.rol_uuid','roles.nombre as rol')
                        ->where('personas.'.$criterio,'like','%'.$buscar.'%')
                        ->orderBy('personas.created_at')->paginate(4);
      }

      return [
        'pagination' => [
          'total' => $usuarios->total(),
          'current_page' => $usuarios->currentPage(),
          'per_page' => $usuarios->perPage(),
          'last_page' => $usuarios->lastPage(),
          'from' => $usuarios->firstItem(),
          'to' => $usuarios->lastItem(),
        ],
        'usuarios' => $usuarios
      ];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
      if (!request()->ajax()) return redirect('/');

      try {

        DB::beginTransaction();

        $persona = Persona::create($request->only('nombre','tipo_documento','num_documento','direccion','telefono','email'));

        $usuario = User::create([
          'uuid' => $persona->uuid,
          'usuario' => $request->usuario,
          'password' => bcrypt($request->password),
          'rol_uuid' => $request->rol_uuid,
        ]);

        DB::commit();

        return response()->json($usuario);

      } catch (\Exception $e) {
        DB::rollback();
      }

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Categoria  $categoria
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, User $usuario)
  {
    if (!request()->ajax()) return redirect('/');

    try {

      DB::beginTransaction();

      $persona = Persona::where('uuid',$usuario->uuid)->firstOrFail();

      $persona->update($request->only('nombre','tipo_documento','num_documento','direccion','telefono','email'));

      $usuario->usuario = $request->usuario;
      if ($request->has('password')) {
        $usuario->password = bcrypt($request->password);
      }
      $usuario->rol_uuid = $request->rol_uuid;
      $usuario->save();

      DB::commit();

      return response()->json($usuario);

    } catch (\Exception $e) {
      DB::rollback();
    }
  }

  //Funciones personalizadas

  public function activar(String $usuario_uuid)
  {
    if (!request()->ajax()) return redirect('/');
    $usuario = User::findOrFail($usuario_uuid);

    if (!$usuario->condicion) {
      $usuario->condicion = 1;
      $usuario->save();
    }
  }

  public function desactivar(String $usuario_uuid)
  {
    if (!request()->ajax()) return redirect('/');
    $usuario = Categoria::findOrFail($usuario_uuid);

    if ($usuario->condicion) {
      $usuario->condicion = 0;
      $usuario->save();
    }
  }
}
