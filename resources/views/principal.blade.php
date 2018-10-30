<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas Laravel Vue Js- PajaroIT">
    <meta name="author" content="pajaro.com">
    <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">
    <link rel="shortcut icon" href={{asset("img/favicon.png")}}>
    <title>Sistema Ventas - PájaroIT</title>
    <!-- Icons -->
    <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/simple-line-icons.min.css')}}" rel="stylesheet">
    <!-- Main styles for this application -->
    <link href={{asset("css/style.css")}} rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    @include('contenido.cabecera')

    <div class="app-body">
        @include('contenido.barra-lateral')

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
        <!-- /Fin del contenido principal -->
    </div>

    <footer class="app-footer">
        <span><a href="https://github.com/charlymrtn">PájaroIT</a> &copy; 2018</span>
        <span class="ml-auto">Desarrollado por <a href="https://twitter.com/charlie_mrtn">Pájaro</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src={{asset("js/jquery.min.js")}}></script>
    <script src={{asset("js/popper.min.js")}}></script>
    <script src={{asset("js/bootstrap.min.js")}}></script>
    <script src={{asset("js/pace.min.js")}}></script>
    <!-- Plugins and scripts required by all views -->
    <script src={{asset("js/Chart.min.js")}}></script>
    <!-- GenesisUI main scripts -->
    <script src={{asset("js/template.js")}}></script>
</body>

</html>
