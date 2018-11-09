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
                        'personas.email','usuarios.usuario','usuarios.password','usuarios.condicion','usuarios.rol_uuid','roles.nombre as rol')
                        ->orderBy('personas.created_at')->paginate(4);
      }else{

        $usuarios = User::join('personas','usuarios.uuid','=','personas.uuid')
                        ->join('roles','usuarios.rol_uuid','=','roles.uuid')
                        ->select('personas.uuid','personas.nombre','personas.tipo_documento',
                        'personas.num_documento','personas.direccion','personas.telefono',
                        'personas.email','usuarios.usuario','usuarios.password','usuarios.condicion','usuarios.rol_uuid','roles.nombre as rol')
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

        $rules = [
          'nombre' => 'required|string|min:5|max:100',
          'email' => 'required|email',
          'tipo_documento' => 'sometimes|string|min:3|max:20',
          'num_documento' => 'required_with:tipo_documento|string|min:5|max:20',
          'direccion' => 'sometimes|string|min:5|max:70',
          'telefono' => 'sometimes|string|min:10|max:20',
          'password' => 'required|string|min:3|max:10',
          'usuario' => 'required|string|min:3|max:10',
          'rol_uuid' => 'required|uuid'
        ];

        $this->validate($request,$rules);

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

      $rules = [
        'nombre' => 'required|string|min:5|max:100',
        'email' => 'required|email',
        'tipo_documento' => 'sometimes|string|min:3|max:20',
        'num_documento' => 'required_with:tipo_documento|string|min:5|max:20',
        'direccion' => 'sometimes|string|min:5|max:70',
        'telefono' => 'sometimes|string|min:10|max:20',
        'password' => 'sometimes|string|min:3|max:10',
        'usuario' => 'sometimes|string|min:3|max:10',
        'rol_uuid' => 'required|uuid'
      ];

      $this->validate($request,$rules);

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

  public function activar(Request $request)
  {
    if (!request()->ajax()) return redirect('/');

    $this->validate($request,[
      'uuid' => 'required|uuid'
    ]);

    $usuario = User::findOrFail($request->uuid);

    if (!$usuario->condicion) {
      $usuario->condicion = 1;
      $usuario->save();
    }
  }

  public function desactivar(Request $request)
  {
    if (!request()->ajax()) return redirect('/');

    $this->validate($request,[
      'uuid' => 'required|uuid'
    ]);

    $usuario = User::findOrFail($request->uuid);

    if ($usuario->condicion) {
      $usuario->condicion = 0;
      $usuario->save();
    }
  }
}
