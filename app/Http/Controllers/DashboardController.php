<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $anio = date('Y');
        $ingresos = DB::table('ingresos as i')
                    ->select(
                            DB::raw('MONTH(i.fecha_ingreso) as mes'),
                            DB::raw('YEAR(i.fecha_ingreso) as anio'),
                            DB::raw('SUM(i.total) as total')
                    )->whereYear('i.fecha_ingreso',$anio)
                    ->groupBy(DB::raw('MONTH(i.fecha_ingreso)'),DB::raw('YEAR(i.fecha_ingreso)'))
                    ->get();

        $ventas = DB::table('ventas as v')
                    ->select(
                        DB::raw('MONTH(v.fecha_venta) as mes'),
                        DB::raw('YEAR(v.fecha_venta) as anio'),
                        DB::raw('SUM(v.total) as total')
                    )->whereYear('v.fecha_venta',$anio)
                    ->groupBy(DB::raw('MONTH(v.fecha_venta)'),DB::raw('YEAR(v.fecha_venta)'))
                    ->get();

        return ['ingresos'=>$ingresos,'anio'=>$anio,'ventas'=>$ventas];
    }

}
