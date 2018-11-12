@extends('layouts.principal')

@section('title', 'Sistema Ventas - PájaroIT')

@section('content')

  @if (Auth::check())
    @if (Auth::user()->rol_id == 1)

      <template v-if="menu==0">
        <h1>Escritorio</h1>
      </template>
      <template v-if="menu==1">
        <categoria-component></categoria-component>
      </template>
      <template v-if="menu==2">
        <articulo-component></articulo-component>
      </template>
      <template v-if="menu==3">
        <h1>Ingresos</h1>
      </template>
      <template v-if="menu==4">
        <proveedor-component></proveedor-component>
      </template>
      <template v-if="menu==5">
        <h1>Ventas</h1>
      </template>
      <template v-if="menu==6">
        <cliente-component></cliente-component>
      </template>
      <template v-if="menu==7">
        <usuario-component></usuario-component>
      </template>
      <template v-if="menu==8">
        <rol-component></rol-component>
      </template>
      <template v-if="menu==9">
        <h1>reporte ingresos</h1>
      </template>
      <template v-if="menu==10">
        <h1>reporte ventas</h1>
      </template>
      <template v-if="menu==11">
        <h1>ayuda</h1>
      </template>
      <template v-if="menu==12">
        <h1>acerca de..</h1>
      </template>

    @elseif(Auth::user()->rol_id == 2)

      <template v-if="menu==0">
        <h1>Escritorio</h1>
      </template>
      <template v-if="menu==5">
        <h1>Ventas</h1>
      </template>
      <template v-if="menu==6">
        <cliente-component></cliente-component>
      </template>
      <template v-if="menu==10">
        <h1>reporte ventas</h1>
      </template>
      <template v-if="menu==11">
        <h1>ayuda</h1>
      </template>
      <template v-if="menu==12">
        <h1>acerca de..</h1>
      </template>

    @elseif(Auth::user()->rol_id == 3)

      <template v-if="menu==0">
        <h1>Escritorio</h1>
      </template>
      <template v-if="menu==1">
        <categoria-component></categoria-component>
      </template>
      <template v-if="menu==2">
        <articulo-component></articulo-component>
      </template>
      <template v-if="menu==3">
        <h1>Ingresos</h1>
      </template>
      <template v-if="menu==4">
        <proveedor-component></proveedor-component>
      </template>
      <template v-if="menu==9">
        <h1>reporte ingresos</h1>
      </template>
      <template v-if="menu==11">
        <h1>ayuda</h1>
      </template>
      <template v-if="menu==12">
        <h1>acerca de..</h1>
      </template>

    @else

    @endif
  @endif

@endsection
