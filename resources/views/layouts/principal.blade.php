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

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"> --}}
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
      <header class="app-header navbar">
          <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
            <span class="navbar-toggler-icon"></span>
          </button>
          <a class="navbar-brand" href="#"></a>
          <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
            <span class="navbar-toggler-icon"></span>
          </button>
          <ul class="nav navbar-nav d-md-down-none">
              <li class="nav-item px-3">
                  <a class="nav-link" href="#">Escritorio</a>
              </li>
              <li class="nav-item px-3">
                  <a class="nav-link" href="#">Configuraciones</a>
              </li>
          </ul>
          <ul class="nav navbar-nav ml-auto">
              <notificacion-component :notificaciones="notificaciones"></notificacion-component>
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                      <img src={{asset("img/avatars/6.jpg")}} class="img-avatar" alt="admin@bootstrapmaster.com">
                      <span class="d-md-down-none">{{Auth::user()->usuario}}</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                      <div class="dropdown-header text-center">
                          <strong>Cuenta</strong>
                      </div>
                      <a class="dropdown-item" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-lock"></i> Cerrar sesión</a>

                      <form id="logout-form" action="{{route('logout')}}" method="post" style="display: none;">
                        {{ csrf_field() }}
                      </form>
                  </div>
              </li>
          </ul>
      </header>

      <div class="app-body">
          @if (Auth::check())
            @if (Auth::user()->rol_id == 1)
              @include('contenido.barra-lateral-admin')
            @elseif(Auth::user()->rol_id == 2)
              @include('contenido.barra-lateral-vendedor')
            @elseif(Auth::user()->rol_id == 3)
              @include('contenido.barra-lateral-almacen')
            @else

            @endif
          @endif

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
