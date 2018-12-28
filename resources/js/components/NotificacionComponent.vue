<template>
    <li class="nav-item d-md-down-none">
        <a class="nav-link" href="#" data-toggle="dropdown">
            <i class="icon-bell"></i>
            <span class="badge badge-pill badge-danger">{{notificaciones.length}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
                <strong>Notificaciones</strong>
            </div>
            <div v-if="listar">
                <a class="dropdown-item" href="#">
                    <i class="fa fa-envelope-o"></i> {{listar.datos.ingresos.msj}}
                    <span class="badge badge-success">{{listar.datos.ingresos.numero}}</span>
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fa fa-tasks"></i> {{listar.datos.ventas.msj}}
                    <span class="badge badge-danger">{{listar.datos.ventas.numero}}</span>
                </a>
            </div>
            <div v-else>
                <a><span>No tiene notificaciones.</span></a>
            </div>
        </div>
    </li>
</template>
<script>
    export default {
        props: ['notificaciones'],
        data(){
            return {
                arrayNotificaciones: []
            }
        },
        computed:{
            listar: function(){

                if (this.notificaciones == ''){
                    return this.arrayNotificaciones = [];
                } else {
                    this.arrayNotificaciones = Object.values(this.notificaciones[0]);
                    if (this.arrayNotificaciones.length > 3){
                        return JSON.parse(this.arrayNotificaciones[4]);
                    } else{
                        return this.arrayNotificaciones[0];
                    }
                }
            }
        }
    }
</script>
