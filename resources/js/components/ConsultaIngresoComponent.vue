<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Ingresos
                </div>
                <!-- Listado-->
                <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Número Comprobante</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Usuario</th>
                                    <th>Proveedor</th>
                                    <th>Tipo Comprobante</th>
                                    <th>Serie Comprobante</th>
                                    <th>Número Comprobante</th>
                                    <th>Fecha Hora</th>
                                    <th>Total</th>
                                    <th>Impuesto</th>
                                    <th>Estado</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="ingreso in ingresos" :key="ingreso.uuid">
                                    <td>
                                        <button type="button" @click="verIngreso(ingreso.uuid)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                        </button>
                                    </td>
                                    <td v-text="ingreso.usuario"></td>
                                    <td v-text="ingreso.nombre"></td>
                                    <td v-text="ingreso.tipo_comprobante"></td>
                                    <td v-text="ingreso.serie_comprobante"></td>
                                    <td v-text="ingreso.num_comprobante"></td>
                                    <td v-text="ingreso.fecha_hora"></td>
                                    <td v-text="ingreso.total"></td>
                                    <td v-text="ingreso.impuesto"></td>
                                    <td v-text="ingreso.estado"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!--Fin Listado-->
                <!-- Ver ingreso -->
                <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label style="font-weight: bold;" for="">Proveedor</label>
                                    <p v-text="proveedor"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label style="font-weight: bold;" for="">Impuesto</label>
                                <p v-text="impuesto"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label style="font-weight: bold;">Tipo Comprobante</label>
                                    <p v-text="tipo_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label style="font-weight: bold;">Serie Comprobante</label>
                                    <p v-text="serie_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label style="font-weight: bold;">Número Comprobante</label>
                                    <p v-text="num_comprobante"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                    <tr>
                                        <th>Artículo</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="detalles.length">
                                    <tr v-for="detalle in detalles" :key="detalle.uuid">
                                        <td v-text="detalle.articulo"></td>
                                        <td v-text="detalle.precio"></td>
                                        <td v-text="detalle.cantidad"></td>
                                        <td>
                                            $ {{(detalle.precio*detalle.cantidad).toFixed(2)}}
                                        </td>
                                    </tr>
                                    <tr style="background-color: #CEECF5;">
                                        <td colspan="3" align="right"><strong>Total Parcial:</strong></td>
                                        <td>$ {{total_parcial=(total-total_impuesto).toFixed(2)}}</td>
                                    </tr>
                                    <tr style="background-color: #CEECF5;">
                                        <td colspan="3" align="right"><strong>Total Impuesto:</strong></td>
                                        <td>$ {{total_impuesto=((total*impuesto).toFixed(2))}}</td>
                                    </tr>
                                    <tr style="background-color: #CEECF5;">
                                        <td colspan="3" align="right"><strong>Total Neto:</strong></td>
                                        <td>$ {{total}}</td>
                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                    <tr>
                                        <td colspan="4">
                                            No hay artículos agregados
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<script>
    import vSelect from 'vue-select';
    export default {
        data() {
            return{
                nombre: '',
                tipo_comprobante: 'RECIBO',
                serie_comprobante: '',
                num_comprobante: '',
                impuesto: 0.16,
                total: 0.0,
                total_impuesto: 0.0,
                total_parcial: 0.0,
                ingresos: [],
                detalles: [],
                proveedores: [],
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                error: 0,
                errors: [],
                ingreso_uuid: '',
                proveedor_uuid: '',
                proveedor: '',
                articulo_uuid: '',
                precio: 0.0,
                cantidad: 0,
                listado: 1,
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0
                },
                offset: 3,
                criterio: 'num_comprobante',
                buscar: '',
                criterio_articulo: 'nombre',
                buscar_articulo: '',
                articulos: [],
                codigo: '',
                articulo: ''
            }
        },
        components: {
            vSelect
        },
        computed: {
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if (!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pages = [];
                while (from <= to) {
                    pages.push(from);
                    from++;
                }
                return pages;
            },
            calcularTotal: function(){
                var resultado = 0.0;
                for (var i = 0; i < this.detalles.length; i++) {
                    resultado = resultado + (this.detalles[i].precio * this.detalles[i].cantidad);
                }
                return resultado;
            }
        },
        methods: {
            listarIngreso(page,buscar,criterio){
                let me = this;
                var url = '/ingresos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response){
                    me.ingresos = response.data.ingresos.data;
                    me.pagination = response.data.pagination;
                })
                    .catch(function (error){
                        console.log(error);
                    });
            },
            cambiarPagina(page,buscar,criterio){
                let me =this;
                me.pagination.current_page = page;

                me.listarIngreso(page,buscar,criterio);
            },
            mostrarDetalle(){
                this.listado=0;
                this.proveedor_uuid ='';
                this.tipo_comprobante= 'RECIBO',
                    this.serie_comprobante= '',
                    this.num_comprobante= '',
                    this.impuesto = 0.16;
                this.total = 0.0;
                this.articulo = '';
                this.articulo_uuid = '';
                this.cantidad = 0;
                this.precio = 0.0;
                this.detalles = [];
            },
            ocultarDetalle(){
                this.listado=1;
            },
            verIngreso(uuid){
                this.listado=2;

                var ingresoTemp = {};
                var me =this;

                //cabecera
                axios.get('ingresos/cabecera/'+uuid).then(function (response){
                    ingresoTemp = response.data.ingreso;

                    me.proveedor = ingresoTemp.nombre;
                    me.tipo_comprobante = ingresoTemp.tipo_comprobante;
                    me.serie_comprobante = ingresoTemp.serie_comprobante;
                    me.num_comprobante = ingresoTemp.num_comprobante;
                    me.impuesto = ingresoTemp.impuesto;
                    me.total = ingresoTemp.total;
                })
                    .catch(function (error){
                        console.log(error);
                    });

                //detalles
                axios.get('ingresos/detalles/'+uuid).then(function (response){
                    me.detalles = response.data.detalles;
                })
                    .catch(function (error){
                        console.log(error);
                    });
            }
        },
        mounted() {
            this.listarIngreso(1,this.buscar,this.criterio);

        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }
</style>
