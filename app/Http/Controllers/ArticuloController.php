<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;

class ArticuloController extends Controller
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
           $articulos = Articulo::join('categorias','articulos.categoria_uuid','=','categorias.uuid')
                                 ->select('articulos.uuid','articulos.categoria_uuid','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio','articulos.existencias','articulos.condicion','articulos.descripcion')
                                 ->orderBy('articulos.nombre')->paginate(4);
         }else{
           if ($criterio != 'categoria') {
             $articulos = Articulo::join('categorias','articulos.categoria_uuid','=','categorias.uuid')
                                   ->select('articulos.uuid','articulos.categoria_uuid','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio','articulos.existencias','articulos.condicion','articulos.descripcion')
                                   ->where('articulos.'.$criterio,'like','%'.$buscar.'%')
                                   ->orderBy('articulos.nombre')->paginate(4);
           }else{
             $articulos = Articulo::join('categorias','articulos.categoria_uuid','=','categorias.uuid')
                                   ->select('articulos.uuid','articulos.categoria_uuid','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio','articulos.existencias','articulos.condicion','articulos.descripcion')
                                   ->where('categorias.nombre','like','%'.$buscar.'%')
                                   ->orderBy('articulos.nombre')->paginate(4);
           }

         }

         return [
           'pagination' => [
             'total' => $articulos->total(),
             'current_page' => $articulos->currentPage(),
             'per_page' => $articulos->perPage(),
             'last_page' => $articulos->lastPage(),
             'from' => $articulos->firstItem(),
             'to' => $articulos->lastItem(),
           ],
           'articulos' => $articulos
         ];
     }

     public function buscar(Request $request)
     {
       if (!request()->ajax()) return redirect('/');

       $filtro = $request->filtro;
       $articulos = Articulo::where('codigo',$filtro)
                            ->select('uuid','nombre')
                            ->orderBy('nombre','asc')->take(1)->get();

      return ['articulos' => $articulos];
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

         $rules = [
           'nombre' => 'required|string|min:5|max:100',
           'categoria_uuid' => 'required|uuid',
           'descripcion' => 'nullable|string|min:5|max:256',
           'codigo' => 'required|string|min:5|max:50',
           'precio' => 'required|numeric|min:5|max:10000',
           'existencias' => 'required|numeric|min:5|max:10000',
         ];

         $this->validate($request, $rules);

         $articulo = Articulo::create($request->only(['nombre','descripcion','categoria_uuid','codigo','precio','existencias']));

         return $articulo;
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Articulo  $articulo
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, Articulo $articulo)
     {
       if (!request()->ajax()) return redirect('/');

       $rules = [
         'nombre' => 'nullable|string|min:5|max:100',
         'categoria_uuid' => 'nullable|uuid',
         'descripcion' => 'nullable|string|min:5|max:256',
         'codigo' => 'nullable|string|min:5|max:50',
         'precio' => 'nullable|numeric|min:5|max:10000',
         'existencias' => 'nullable|numeric|min:5|max:10000',
       ];

       $this->validate($request, $rules);

       if (!$articulo->condicion) {
         $articulo->condicion = 1;
         $articulo->save();
       }

       $articulo->update($request->only(['nombre','descripcion','categoria_uuid','codigo','precio','existencias']));

       return $articulo;
     }

     public function activar(String $articulo_uuid)
     {
       if (!request()->ajax()) return redirect('/');

       $articulo = Articulo::findOrFail($articulo_uuid);

       if (!$articulo->condicion) {
         $articulo->condicion = 1;
         $articulo->save();
       }
     }

     public function desactivar(String $articulo_uuid)
     {
       if (!request()->ajax()) return redirect('/');

       $articulo = Articulo::findOrFail($articulo_uuid);

       if ($articulo->condicion) {
         $articulo->condicion = 0;
         $articulo->save();
       }
     }

     public function listar(Request $request)
     {
         if (!$request->ajax()) return redirect('/');

         $buscar = $request->buscar;
         $criterio = $request->criterio;

         if ($buscar == '') {
           $articulos = Articulo::join('categorias','articulos.categoria_uuid','=','categorias.uuid')
                                 ->select('articulos.uuid','articulos.categoria_uuid','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio','articulos.existencias','articulos.condicion','articulos.descripcion')
                                 ->where('articulos.condicion',1)
                                 ->orderBy('articulos.nombre')->paginate(6);
         }else{
           if ($criterio != 'categoria') {
             $articulos = Articulo::join('categorias','articulos.categoria_uuid','=','categorias.uuid')
                                   ->select('articulos.uuid','articulos.categoria_uuid','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio','articulos.existencias','articulos.condicion','articulos.descripcion')
                                   ->where('articulos.'.$criterio,'like','%'.$buscar.'%')
                                   ->where('articulos.condicion',1)
                                   ->orderBy('articulos.nombre')->paginate(6);
           }else{
             $articulos = Articulo::join('categorias','articulos.categoria_uuid','=','categorias.uuid')
                                   ->select('articulos.uuid','articulos.categoria_uuid','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio','articulos.existencias','articulos.condicion','articulos.descripcion')
                                   ->where('categorias.nombre','like','%'.$buscar.'%')
                                   ->where('articulos.condicion',1)
                                   ->orderBy('articulos.nombre')->paginate(6);
           }

         }

         return ['articulos' => $articulos];
     }
}
