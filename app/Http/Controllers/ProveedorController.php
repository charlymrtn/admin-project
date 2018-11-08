<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Proveedor;
use App\Models\Persona;
use DB;

class ProveedorController extends Controller
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
        $proveedores = Proveedor::join('personas','proveedores.uuid','=','personas.uuid')
                                ->select('personas.uuid','personas.nombre','personas.tipo_documento',
                                'personas.num_documento','personas.direccion','personas.telefono',
                                'personas.email','proveedores.contacto','proveedores.telefono_contacto')
                                ->orderBy('personas.created_at')->paginate(4);
      }else{
        $proveedores = Proveedor::join('personas','proveedores.uuid','=','personas.uuid')
                                ->select('personas.uuid','personas.nombre','personas.tipo_documento',
                                'personas.num_documento','personas.direccion','personas.telefono',
                                'personas.email','proveedores.contacto','proveedores.telefono_contacto')
                                ->where('personas.'.$criterio,'like','%'.$buscar.'%')
                                ->orderBy('personas.created_at')->paginate(4);
      }

      return [
        'pagination' => [
          'total' => $proveedores->total(),
          'current_page' => $proveedores->currentPage(),
          'per_page' => $proveedores->perPage(),
          'last_page' => $proveedores->lastPage(),
          'from' => $proveedores->firstItem(),
          'to' => $proveedores->lastItem(),
        ],
        'proveedores' => $proveedores
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

        $proveedor = Proveedor::create([
          'uuid' => $persona->uuid,
          'contacto' => $request->contacto,
          'telefono_contacto' => $request->telefono_contacto
        ]);

        DB::commit();

        return response()->json($proveedor->with('persona'));

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
  public function update(Request $request, Proveedor $proveedor)
  {
    if (!request()->ajax()) return redirect('/');

    try {

      DB::beginTransaction();

      $persona = Persona::where('uuid',$proveedor->uuid)->firstOrFail();

      $persona->update($request->only('nombre','tipo_documento','num_documento','direccion','telefono','email'));

      $proveedor->contacto = $request->contacto;
      $proveedor->telefono_contacto = $request->telefono_contacto;
      $proveedor->save();

      DB::commit();

      return response()->json($proveedor->with('persona'));

    } catch (\Exception $e) {
      DB::rollback();
    }
  }
}
