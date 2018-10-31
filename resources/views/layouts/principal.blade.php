<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas Laravel Vue Js- PajaroIT">
    <meta name="author" content="pajaro.com">
    <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href={{asset("img/favicon.png")}}>
    <title>@yield('title')</title>
    <!-- Icons -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
      @include('contenido.cabecera')

      <div class="app-body">
          @include('contenido.barra-lateral')

          @yield('content')
      </div>
    </div>

    <footer class="app-footer">
        <span><a href="https://github.com/charlymrtn">PájaroIT</a> &copy; 2018</span>
        <span class="ml-auto">Desarrollado por <a href="https://twitter.com/charlie_mrtn">Pájaro</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src={{asset("js/vue.js")}}></script>
    <script src={{asset("js/app.js")}}></script>
</body>

</html>
