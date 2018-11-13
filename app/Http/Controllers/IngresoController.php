<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\DetalleIngreso;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
      //if (!$request->ajax()) return redirect('/');

      $buscar = $request->buscar;
      $criterio = $request->criterio;

      if ($buscar == '') {
        $ingresos = Ingreso::join('personas','ingresos.proveedor_uuid','=','personas.uuid')
                        ->join('usuarios','ingresos.usuario_uuid','=','usuarios.uuid')
                        ->select('ingresos.uuid','ingresos.tipo_comprobante','ingresos.serie_comprobante',
                        'ingresos.num_comprobante','ingresos.fecha_ingreso','ingresos.impuesto',
                        'ingresos.total','ingresos.estado','personas.nombre','usuarios.usuario')
                        ->orderBy('ingresos.created_at')->paginate(4);
      }else{

        $ingresos = Ingreso::join('personas','ingresos.proveedor_uuid','=','personas.uuid')
                        ->join('usuarios','ingresos.usuario_uuid','=','usuarios.uuid')
                        ->select('ingresos.uuid','ingresos.tipo_comprobante','ingresos.serie_comprobante',
                        'ingresos.num_comprobante','ingresos.fecha_ingreso','ingresos.impuesto',
                        'ingresos.total','ingresos.estado','personas.nombre','usuarios.usuario')
                        ->where('ingresos.'.$criterio,'like','%'.$buscar.'%')
                        ->orderBy('ingresos.created_at')->paginate(4);
      }

      return [
        'pagination' => [
          'total' => $ingresos->total(),
          'current_page' => $ingresos->currentPage(),
          'per_page' => $ingresos->perPage(),
          'last_page' => $ingresos->lastPage(),
          'from' => $ingresos->firstItem(),
          'to' => $ingresos->lastItem(),
        ],
        'ingresos' => $ingresos
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

        $rules = [
          'nombre' => 'required|string|min:5|max:100',
          'email' => 'required|email',
          'tipo_documento' => 'sometimes|string|min:3|max:20',
          'num_documento' => 'required_with:tipo_documento|string|min:5|max:20',
          'direccion' => 'sometimes|string|min:5|max:70',
          'telefono' => 'sometimes|string|min:10|max:20',
          'password' => 'required|string|min:3|max:10',
          'usuario' => 'required|string|min:3|max:10',
          'rol_uuid' => 'required|uuid|exists:roles,uuid'
        ];

        $this->validate($request,$rules);

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
        Log::error($e->getMessage());
        return response()->json(['error'=>$e->getMessage()],500);
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

      DB::beginTransaction();

      $persona = Persona::where('uuid',$usuario->uuid)->firstOrFail();

      $persona->fill($request->only('nombre','tipo_documento','num_documento','direccion','telefono','email'));

      if ($persona->isDirty()) $persona->save();

      $usuario->usuario = $request->usuario;
      if ($request->has('password') && $request->password) {
        $usuario->password = bcrypt($request->password);
      }
      $usuario->rol_uuid = $request->rol_uuid;

      if ($usuario->isDirty()) $usuario->save();

      DB::commit();

      return response()->json($usuario);

    } catch (\Exception $e) {
      DB::rollback();
      return response()-json($e->getMessage());
    }
  }

  //Funciones personalizadas

  public function desactivar(String $ingreso_uuid)
  {
    if (!request()->ajax()) return redirect('/');

    $usuario = User::findOrFail($ingreso_uuid);

    if ($usuario->condicion) {
      $usuario->condicion = 0;
      $usuario->save();
    }
  }
}
