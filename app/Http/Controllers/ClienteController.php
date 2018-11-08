<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;

class ClienteController extends Controller
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
        $clientes = Persona::orderBy('created_at')->paginate(4);
      }else{
        $clientes = Persona::where($criterio,'like','%'.$buscar.'%')->orderBy('created_at')->paginate(4);
      }

      return [
        'pagination' => [
          'total' => $clientes->total(),
          'current_page' => $clientes->currentPage(),
          'per_page' => $clientes->perPage(),
          'last_page' => $clientes->lastPage(),
          'from' => $clientes->firstItem(),
          'to' => $clientes->lastItem(),
        ],
        'clientes' => $clientes
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

      $rules = [
        'nombre' => 'required|string|min:5|max:100',
        'email' => 'required|email',
        'tipo_documento' => 'sometimes|string|min:3|max:20',
        'num_documento' => 'required_with:tipo_documento|string|min:5|max:20',
        'direccion' => 'sometimes|string|min:5|max:70',
        'telefono' => 'sometimes|string|min:10|max:20',
      ];

      $this->validate($request, $rules);

      $persona = Persona::create($request->only('nombre','email','tipo_documento','num_documento','direccion','telefono'));

      return $persona;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Categoria  $categoria
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Persona $cliente)
  {
    if (!request()->ajax()) return redirect('/');

    $rules = [
      'nombre' => 'required|string|min:5|max:100',
      'email' => 'required|email',
      'tipo_documento' => 'sometimes|string|min:3|max:20',
      'num_documento' => 'required_with:tipo_documento|string|min:5|max:20',
      'direccion' => 'sometimes|string|min:5|max:70',
      'telefono' => 'sometimes|string|min:10|max:20',
    ];

    $this->validate($request, $rules);

    $cliente->update($request->only('nombre','email','tipo_documento','num_documento','direccion','telefono'));

    return $cliente;
  }

}
