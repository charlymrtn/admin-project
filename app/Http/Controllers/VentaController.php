<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;

use DB;
use Validator;
use Auth;
use Log;
use Carbon\Carbon;

class VentaController extends Controller
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
        $ventas = Venta::join('personas','ventas.cliente_uuid','=','personas.uuid')
                        ->join('usuarios','ventas.usuario_uuid','=','usuarios.uuid')
                        ->select('ventas.uuid','ventas.tipo_comprobante','ventas.serie_comprobante',
                        'ventas.num_comprobante','ventas.fecha_venta','ventas.impuesto',
                        'ventas.total','ventas.estado','personas.nombre','usuarios.usuario')
                        ->orderBy('ventas.created_at')->paginate(4);
      }else{

        $ventas = Venta::join('personas','ventas.cliente_uuid','=','personas.uuid')
                        ->join('usuarios','ventas.usuario_uuid','=','usuarios.uuid')
                        ->select('ventas.uuid','ventas.tipo_comprobante','ventas.serie_comprobante',
                        'ventas.num_comprobante','ventas.fecha_venta','ventas.impuesto',
                        'ventas.total','ventas.estado','personas.nombre','usuarios.usuario')
                        ->where('ventas.'.$criterio,'like','%'.$buscar.'%')
                        ->orderBy('ventas.created_at')->paginate(4);
      }

      return [
        'pagination' => [
          'total' => $ventas->total(),
          'current_page' => $ventas->currentPage(),
          'per_page' => $ventas->perPage(),
          'last_page' => $ventas->lastPage(),
          'from' => $ventas->firstItem(),
          'to' => $ventas->lastItem(),
        ],
        'ventas' => $ventas
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
            'total' => 'required|numeric|min:1',
            'detalles' => 'required|array'
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

        foreach ($request->detalles as $item) {
          $detalle = new DetalleIngreso();
          $detalle->ingreso_uuid = $ingreso->uuid;
          $detalle->articulo_uuid = $item['articulo_uuid'];
          $detalle->cantidad = $item['cantidad'];
          $detalle->precio = $item['precio'];

          $detalle->save();

        }

        DB::commit();

        return response()->json($ingreso);

      } catch (\Exception $e) {
        DB::rollback();
        Log::error($e);
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

  public function cabecera(String $ingreso_uuid)
  {
    if (!request()->ajax()) return redirect('/');

    $ingreso = Ingreso::join('personas','ingresos.proveedor_uuid','=','personas.uuid')
                    ->join('usuarios','ingresos.usuario_uuid','=','usuarios.uuid')
                    ->select('ingresos.uuid','ingresos.tipo_comprobante','ingresos.serie_comprobante',
                    'ingresos.num_comprobante','ingresos.fecha_ingreso','ingresos.impuesto',
                    'ingresos.total','ingresos.estado','personas.nombre','usuarios.usuario')
                    ->where('ingresos.uuid','=',$ingreso_uuid)
                    ->orderBy('ingresos.created_at')->first();

    return ['ingreso' => $ingreso];
  }

  public function detalles(String $ingreso_uuid)
  {
    if (!request()->ajax()) return redirect('/');

    $detalles = DetalleIngreso::join('articulos','detalle_ingresos.articulo_uuid','=','articulos.uuid')
                    ->select('detalle_ingresos.cantidad','detalle_ingresos.precio','articulos.nombre as articulo')
                    ->where('detalle_ingresos.ingreso_uuid','=',$ingreso_uuid)
                    ->orderBy('detalle_ingresos.created_at')->get();

    return ['detalles' => $detalles];
  }
}
