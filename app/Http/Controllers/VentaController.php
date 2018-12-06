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
      if (!$request->ajax()) return redirect('/');

      $validator = Validator::make($request->all(), [
            'cliente_uuid' => 'required|uuid',
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

        $venta = new Venta();
        $venta->cliente_uuid = $request->cliente_uuid;
        $venta->usuario_uuid = Auth::user()->uuid;
        $venta->tipo_comprobante = $request->tipo_comprobante;
        $venta->serie_comprobante = $request->serie_comprobante;
        $venta->num_comprobante = $request->num_comprobante;
        $venta->impuesto = $request->impuesto;
        $venta->total = $request->total;
        $venta->estado = 'Registrado';
        $venta->fecha_venta = $time->toDateString();

        $venta->save();

        foreach ($request->detalles as $item) {
          $detalle = new DetalleVenta();
          $detalle->venta_uuid = $venta->uuid;
          $detalle->articulo_uuid = $item['articulo_uuid'];
          $detalle->cantidad = $item['cantidad'];
          $detalle->precio = $item['precio'];
          $detalle->descuento = $item['descuento'];

          $detalle->save();

        }

        DB::commit();

        return response()->json($venta);

      } catch (\Exception $e) {
        DB::rollback();
        Log::error($e);
        return response()->json(['error'=>$e->getMessage()],500);
      }

  }


  //Funciones personalizadas

  public function desactivar(String $venta_uuid)
  {
    if (!request()->ajax()) return redirect('/');

    $venta = Venta::findOrFail($venta_uuid);

    $venta->estado = 'Anulado';

    $venta->save();
  }

  public function cabecera(String $venta_uuid)
  {
    if (!request()->ajax()) return redirect('/');

    $venta = Venta ::join('personas','ventas.cliente_uuid','=','personas.uuid')
                    ->join('usuarios','ventas.usuario_uuid','=','usuarios.uuid')
                    ->select('ventas.uuid','ventas.tipo_comprobante','ventas.serie_comprobante',
                    'ventas.num_comprobante','ventas.fecha_venta','ventas.impuesto',
                    'ventas.total','ventas.estado','personas.nombre','usuarios.usuario')
                    ->where('ventas.uuid','=',$venta_uuid)
                    ->orderBy('ventas.created_at')->first();

    return ['venta' => $venta];
  }

  public function detalles(String $venta_uuid)
  {
    if (!request()->ajax()) return redirect('/');

    $detalles = DetalleVenta::join('articulos','detalle_ventas.articulo_uuid','=','articulos.uuid')
                    ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento','articulos.nombre as articulo')
                    ->where('detalle_ventas.venta_uuid','=',$venta_uuid)
                    ->orderBy('detalle_ventas.created_at')->get();

    return ['detalles' => $detalles];
  }

  public function pdf(Request $request, String $uuid)
  {
    $venta = Venta::join('personas','ventas.cliente_uuid','=','personas.uuid')
                  ->join('usuarios','ventas.usuario_uuid';'=','usuarios.uuid')
                  ->select('ventas.uuid','ventas.tipo_comprobante','ventas.serie_comprobante','ventas.num_comprobante','ventas.created_at',
                  'ventas.impuesto','ventas.total','vemtas.estado','personas.nombre','personas.tipo_documento','personas.num_documento',
                  'personas.direccion','personas.email','personas.telefono','usuarios.usuario')
                  ->where('ventas.uuid','=',$uuid)
                  ->orderBy('ventas.created_at','desc')
                  ->first();
  }
}
