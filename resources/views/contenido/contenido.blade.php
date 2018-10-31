@extends('layouts.principal')

@section('title', 'Sistema Ventas - PájaroIT')

@section('content')
  <!-- Contenido Principal -->
  <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item"><a href="#">Admin</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
      </ol>

      @include('contenido.tabla')

      @include('modals.modal-nuevo-editar')

      @include('modals.modal-eliminar')
  </main>
@stop
