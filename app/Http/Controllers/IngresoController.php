<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\DetalleIngreso;
use Illuminate\Http\Request;

use DB;
use Validator;
use Carbon\Carbon;

class IngresoController extends Controller
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

      $validator = Validator::make($request->all(), [
            'proveedor_uuid' => 'required|uuid',
            'tipo_comprobante' => 'required|string',
            'serie_comprobante' => 'sometimes|string',
            'num_comprobante' => 'required|string',
            'impuesto' => 'required|numeric',
            'total' => 'required|numeric|min:1'
        ]);

      if ($validator->fails()) {
        return response()->json($validator,500);
      }

      try {

        DB::beginTransaction();

        $time = Carbon::now('America/Mexico_City');

        $ingreso = new Ingreso();
        $ingreso->proveedor_uuid = $request->proveedor_uuid;
        $ingreso->usuario_uuid = Auth::user()->uuid;
        $ingreso->tipo_comprobante = $request->tipo_comprobante;
        $ingreso->serie_comprobante = $request->serie_comprobante;
        $ingreso->num_comprobante = $request->num_comprobante;
        $ingreso->impuesto = $request->impuesto;
        $ingreso->total = $request->total;
        $ingreso->estado = 'Registrado';
        $ingreso->fecha_ingreso = $time->toDateString();

        $ingreso->save();

        $detalles = $request->data;

        foreach ($detalles as $key => $det) {
          $detalle = new DetalleIngreso();
          $detalle->ingreso_uuid = $ingreso->uuid;
          $detalle->articulo_uuid = $det->articulo_uuid;
          $detalle->cantidad = $det->cantidad;
          $detalle->precio = $det->precio;

          $detalle->save();

        }

        DB::commit();

        return response()->json($ingreso);

      } catch (\Exception $e) {
        DB::rollback();
        Log::error($e->getMessage());
        return response()->json(['error'=>$e->getMessage()],500);
      }

  }


  //Funciones personalizadas

  public function desactivar(String $ingreso_uuid)
  {
    if (!request()->ajax()) return redirect('/');

    $ingreso = Ingreso::findOrFail($ingreso_uuid);

    $ingreso->estado = 'Anulado';

    $ingreso->save();
  }
}
