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
                                DB::raw('(SUM(i.total) as total')
                        )->whereYear('i.fecha_ingreso',$anio)
                        ->groupBy(DB::raw('MONTH(i.fecha_ingreso)'),DB::raw('YEAR(i.fecha_ingreso)'))
                        ->get();

        return ['imgresos'=>$ingresos,'anio'=>$anio];
    }

}
