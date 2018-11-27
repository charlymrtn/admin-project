<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
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
          $categorias = Categoria::orderBy('nombre')->paginate(4);
        }else{
          $categorias = Categoria::where($criterio,'like','%'.$buscar.'%')->orderBy('nombre')->paginate(4);
        }

        return [
          'pagination' => [
            'total' => $categorias->total(),
            'current_page' => $categorias->currentPage(),
            'per_page' => $categorias->perPage(),
            'last_page' => $categorias->lastPage(),
            'from' => $categorias->firstItem(),
            'to' => $categorias->lastItem(),
          ],
          'categorias' => $categorias
        ];
    }

    public function select()
    {
      if (!request()->ajax()) return redirect('/');

      $categorias = Categoria::where('condicion',1)
                              ->select('uuid','nombre')
                              ->orderBy('nombre')
                              ->get();

      return ['categorias' => $categorias];
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
          'nombre' => 'required|string|min:5|max:50',
          'descripcion' => 'nullable|string|min:5|max:256',
        ];
        $this->validate($request, $rules);

        $categoria = Categoria::create($request->only(['nombre','descripcion']));

        return $categoria;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
      if (!request()->ajax()) return redirect('/');
      $rules = [
        'nombre' => 'required|string|min:5|max:50',
        'descripcion' => 'sometimes|string|min:5|max:256',
      ];
      $this->validate($request, $rules);

      if (!$categoria->condicion) {
        $categoria->condicion = 1;
        $categoria->save();
      }

      $categoria->update($request->only('nombre','descripcion'));

      return $categoria;
    }

    //Funciones personalizadas

    public function activar(String $categoria_uuid)
    {
      if (!request()->ajax()) return redirect('/');
      $categoria = Categoria::findOrFail($categoria_uuid);

      if (!$categoria->condicion) {
        $categoria->condicion = 1;
        $categoria->save();
      }
    }

    public function desactivar(String $categoria_uuid)
    {
      if (!request()->ajax()) return redirect('/');
      $categoria = Categoria::findOrFail($categoria_uuid);

      if ($categoria->condicion) {
        $categoria->condicion = 0;
        $categoria->save();
      }
    }
}
