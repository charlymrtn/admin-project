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
              <i class="fa fa-align-justify"></i> Ventas
              <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                  <i class="icon-plus"></i>&nbsp;Nuevo
              </button>
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
                          <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                          <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                      </div>
                  </div>
              </div>
              <div class="table-responsive">
                  <table class="table table-bordered table-striped table-sm">
                      <thead>
                          <tr>
                              <th>Opciones</th>
                              <th>Usuario</th>
                              <th>Cliente</th>
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
                          <tr v-for="venta in ventas" :key="venta.uuid">
                              <td>
                                  <button type="button" @click="verVenta(venta.uuid)" class="btn btn-success btn-sm">
                                  <i class="icon-eye"></i>
                                  </button> &nbsp;
                                  <button type="button" @click="pdfVenta(venta.uuid)" class="btn btn-info btn-sm">
                                  <i class="icon-doc"></i>
                                  </button> &nbsp;
                                  <template v-if="venta.estado=='Registrado'">
                                      <button type="button" class="btn btn-danger btn-sm" @click="desactivar(venta.uuid)">
                                          <i class="icon-trash"></i>
                                      </button>
                                  </template>
                              </td>
                              <td v-text="venta.usuario"></td>
                              <td v-text="venta.nombre"></td>
                              <td v-text="venta.tipo_comprobante"></td>
                              <td v-text="venta.serie_comprobante"></td>
                              <td v-text="venta.num_comprobante"></td>
                              <td v-text="venta.fecha_venta"></td>
                              <td v-text="venta.total"></td>
                              <td v-text="venta.impuesto"></td>
                              <td v-text="venta.estado"></td>
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
          <!-- Detalle-->
          <template v-else-if="listado==0">
          <div class="card-body">
              <div class="form-group row border">
                  <div class="col-md-9">
                      <div class="form-group">
                          <label style="font-weight: bold;" for="">Cliente(*)</label>
                          <v-select
                          :on-search="selectCliente"
                          label="nombre"
                          :options="clientes"
                          placeholder="Buscar Clientes..."
                          :onChange="getDatosCliente"
                          >

                          </v-select>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <label style="font-weight: bold;" for="">Impuesto(*)</label>
                      <input type="text" class="form-control" v-model="impuesto">
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label style="font-weight: bold;">Tipo Comprobante(*)</label>
                          <select class="form-control" v-model="tipo_comprobante">
                              <option value="" disabled>Seleccione</option>
                              <option value="RECIBO">Recibo</option>
                              <option value="FACTURA">Factura</option>
                              <option value="TICKET">Ticket</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label style="font-weight: bold;">Serie Comprobante</label>
                          <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label style="font-weight: bold;">Número Comprobante(*)</label>
                          <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx">
                      </div>
                  </div>
                  <div class="col-md-12">
                    <div v-show="error" class="form-group row div-error">
                      <div class="text-center text-error">
                        <div v-for="error in errors" :key="error" v-text="error">

                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group row border">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label style="font-weight: bold;">Artículo <span style="color:red; font-weight:normal;" v-show="articulo_uuid==''">(*Seleccione)</span></label>
                          <div class="form-inline">
                              <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Ingrese artículo">
                              <button @click="abrirModal(buscar_articulo,criterio_articulo)" class="btn btn-primary">...</button>
                              <input type="text" readonly class="form-control" v-model="articulo">
                          </div>
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          <label style="font-weight: bold;">Precio <span style="color:red; font-weight:normal;" v-show="precio==0">(*Ingrese precio)</span></label>
                          <input type="number" value="0" step="any" class="form-control" v-model="precio">
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          <label style="font-weight: bold;">Cantidad <span style="color:red; font-weight:normal;" v-show="cantidad==0">(*Ingrese cantidad)</span></label>
                          <input type="number" value="0" class="form-control" v-model="cantidad">
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          <label style="font-weight: bold;">Descuento</label>
                          <input type="number" value="0" class="form-control" v-model="descuento">
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                      </div>
                  </div>
              </div>
              <div class="form-group row border">
                  <div class="table-responsive col-md-12">
                      <table class="table table-bordered table-striped table-sm">
                          <thead>
                            <tr>
                              <th>Opciones</th>
                              <th>Artículo</th>
                              <th>Precio</th>
                              <th>Cantidad</th>
                              <th>Descuento %</th>
                              <th>Subtotal</th>
                            </tr>
                          </thead>
                          <tbody v-if="detalles.length">
                            <tr v-for="(detalle,index) in detalles" :key="detalle.uuid">
                              <td>
                                  <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                      <i class="icon-close"></i>
                                  </button>
                              </td>
                              <td v-text="detalle.articulo"></td>
                              <td>
                                  <input v-model="detalle.precio" type="number" step="any" class="form-control text-right">
                              </td>
                              <td>
                                  <span style="color:red;" v-show="detalle.cantidad>detalle.existencias">Existencias: {{detalle.existencias}}</span>
                                  <input v-model="detalle.cantidad" type="number" class="form-control text-right">
                              </td>
                              <td>
                                  <span style="color:red;" v-show="detalle.descuento>100">Descuento invalído</span>
                                  <input v-model="detalle.descuento" type="number" class="form-control text-right">
                              </td>
                              <td>
                                  $ {{(detalle.precio*detalle.cantidad-((detalle.descuento*(detalle.precio*detalle.cantidad))/100)).toFixed(2)}}
                              </td>
                            </tr>
                            <tr style="background-color: #CEECF5;">
                                <td colspan="5" align="right"><strong>Total Parcial:</strong></td>
                                <td>$ {{total_parcial=(total-total_impuesto).toFixed(2)}}</td>
                            </tr>
                            <tr style="background-color: #CEECF5;">
                                <td colspan="5" align="right"><strong>Total Impuesto:</strong></td>
                                <td>$ {{total_impuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</td>
                            </tr>
                            <tr style="background-color: #CEECF5;">
                                <td colspan="5" align="right"><strong>Total Neto:</strong></td>
                                <td>$ {{total=calcularTotal.toFixed(2)}}</td>
                            </tr>
                          </tbody>
                          <tbody v-else>
                            <tr>
                              <td colspan="6">
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
                      <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
                  </div>
              </div>
          </div>
          </template>
          <!-- Fin Detalle-->
          <!-- Ver ingreso -->
          <template v-else-if="listado==2">
          <div class="card-body">
              <div class="form-group row border">
                  <div class="col-md-9">
                      <div class="form-group">
                          <label style="font-weight: bold;" for="">Cliente</label>
                          <p v-text="cliente"></p>
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
                              <th>Descuento %</th>
                              <th>Subtotal</th>
                            </tr>
                          </thead>
                          <tbody v-if="detalles.length">
                            <tr v-for="detalle in detalles" :key="detalle.uuid">
                              <td v-text="detalle.articulo"></td>
                              <td v-text="detalle.precio"></td>
                              <td v-text="detalle.cantidad"></td>
                              <td v-text="detalle.descuento"></td>
                              <td>
                                    $ {{(detalle.precio*detalle.cantidad-((detalle.descuento*(detalle.precio*detalle.cantidad))/100)).toFixed(2)}}
                              </td>
                            </tr>
                            <tr style="background-color: #CEECF5;">
                                <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                <td>$ {{total_parcial=(total-total_impuesto).toFixed(2)}}</td>
                            </tr>
                            <tr style="background-color: #CEECF5;">
                                <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                <td>$ {{total_impuesto=((total*impuesto).toFixed(2))}}</td>
                            </tr>
                            <tr style="background-color: #CEECF5;">
                                <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                <td>$ {{total}}</td>
                            </tr>
                          </tbody>
                          <tbody v-else>
                            <tr>
                              <td colspan="5">
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
  <!--Inicio del modal agregar/actualizar-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-primary modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" v-text="tituloModal"></h4>
                  <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="form-group row">
                      <div class="col-md-6">
                          <div class="input-group">
                              <select class="form-control col-md-4" v-model="criterio_articulo">
                                <option value="nombre">Nombre</option>
                                <option value="codigo">Código</option>
                                <option value="descripcion">Descripción</option>
                                <option value="categoria">Categoría</option>
                              </select>
                              <input type="text" class="form-control" placeholder="Texto a buscar" v-model="buscar_articulo" @keyup.enter="listarArticulo(buscar_articulo,criterio_articulo)">
                              <button type="submit" @click="listarArticulo(buscar_articulo,criterio_articulo)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                          </div>
                      </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Precio unitario</th>
                                <th>Unidades en almácen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="articulo in articulos" :key="articulo.uuid">
                                <td>
                                    <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                      <i class="icon-check"></i>
                                    </button>
                                </td>
                                <td v-text="articulo.codigo"></td>
                                <td v-text="articulo.nombre"></td>
                                <td v-text="articulo.nombre_categoria"></td>
                                <td v-text="articulo.precio"></td>
                                <td v-text="articulo.existencias"></td>
                            </tr>
                        </tbody>
                    </table>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
              </div>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal-->
</main>
</template>

<script>
    import vSelect from 'vue-select';
    export default {
        data() {
          return{
            tipo_comprobante: 'RECIBO',
            serie_comprobante: '',
            num_comprobante: '',
            impuesto: 0.16,
            total: 0.0,
            total_impuesto: 0.0,
            total_parcial: 0.0,
            ventas: [],
            detalles: [],
            clientes: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            error: 0,
            errors: [],
            venta_uuid: '',
            cliente_uuid: '',
            cliente: '',
            articulo_uuid: '',
            precio: 0.0,
            cantidad: 0,
            descuento: 0,
            existencias: 0,
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
              resultado = resultado + ((this.detalles[i].precio * this.detalles[i].cantidad)-((this.detalles[i].descuento*(this.detalles[i].precio * this.detalles[i].cantidad))/100));
            }
            return resultado;
          }
        },
        methods: {
          listarVenta(page,buscar,criterio){
            let me = this;
            var url = '/ventas?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response){
              me.ventas = response.data.ventas.data;
              me.pagination = response.data.pagination;
            })
            .catch(function (error){
              console.log(error);
            });
          },
          cambiarPagina(page,buscar,criterio){
            let me =this;
            me.pagination.current_page = page;

            me.listarVenta(page,buscar,criterio);
          },
          encuentra(uuid){
            var sw = false;
            for (var i = 0; i < this.detalles.length; i++) {
              if (this.detalles[i].articulo_uuid == uuid) sw = true;
            }
            return sw;
          },
          agregarDetalle(){
            let me = this;
            if (me.articulo_uuid == '' || me.cantidad == 0 || me.precio == 0) {
              swal({
                type: 'error',
                title: 'Error',
                text: 'faltan datos! Verifique.'
              })
            }else{
              if (me.encuentra(me.articulo_uuid)) {
                swal({
                  type: 'error',
                  title: 'Error',
                  text: 'Ese articulo ya se encuentra agregado!'
                })
              }else{
                if (me.cantidad>me.existencias) {
                  swal({
                    type: 'error',
                    title: 'Error',
                    text: 'No hay existencias disponibles'
                  })
                }else {
                  me.detalles.push({
                    articulo_uuid: me.articulo_uuid,
                    articulo: me.articulo,
                    cantidad: me.cantidad,
                    precio: me.precio,
                    descuento: me.descuento,
                    existencias: me.existencias
                  });
                  me.codigo = '';
                  me.articulo_uuid = '';
                  me.articulo = '';
                  me.cantidad = 0;
                  me.precio = 0.0;
                  me.descuento = 0;
                  me.existencias = 0;
                }
              }
            }
          },
          agregarDetalleModal(data = []){
            let me = this;
            if (me.encuentra(data['uuid'])) {
              swal({
                type: 'error',
                title: 'Error',
                text: 'Ese articulo ya se encuentra agregado!'
              })
            }else{
              me.detalles.push({
                articulo_uuid: data['uuid'],
                articulo: data['nombre'],
                cantidad: 1,
                precio: data['precio'],
                descuento: 0,
                existencias: data['existencias']
              });
            }
          },
          listarArticulo(buscar,criterio){
            let me = this;
            var url = '/articulos/listarVenta?buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response){
              me.articulos = response.data.articulos.data;
            })
            .catch(function (error){
              console.log(error);
            });
          },
          eliminarDetalle(index){
            const swalWithBootstrapButtons = swal.mixin({
              confirmButtonClass: 'btn btn-success',
              cancelButtonClass: 'btn btn-danger',
              buttonsStyling: false,
            })

            swalWithBootstrapButtons({
              title: '¿Estás segur@ de eliminar este elemento?',
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Aceptar',
              cancelButtonText: 'Cancelar',
              reverseButtons: true
            }).then((result) => {
              if (result.value) {
                let me = this;
                me.detalles.splice(index,1);
              }
            })
          },
          registrarVenta(){
            if (this.validar()) {
              return;
            }
            let me = this;
            axios.post('/ventas',{
              'cliente_uuid': me.cliente_uuid,
              'tipo_comprobante': me.tipo_comprobante,
              'serie_comprobante': me.serie_comprobante,
              'num_comprobante': me.num_comprobante,
              'impuesto': me.impuesto,
              'total': me.total,
              'detalles': me.detalles
            }).then(function (response){
              swal(
                'Exito!',
                'Venta Creada',
                'success'
              );
              me.listado = 1;
              me.listarVenta(1,'','tipo_comprobante');
              me.cliente_uuid = '';
              me.tipo_comprobante= 'RECIBO',
              me.serie_comprobante= '',
              me.num_comprobante= '',
              me.impuesto = 0.16;
              me.total = 0.0;
              me.articulo = '';
              me.articulo_uuid = '';
              me.cantidad = 0;
              me.precio = 0.0;
              me.existencias = 0;
              me.descuento = 0;
              me.codigo = '';
              me.descuento = 0;
              me.detalles = [];
              var url = window.location.protocol + "//" + window.location.host + '/ventas/'+response.data.id+'/pdf';
              window.open(url);
            })
            .catch(function (error){
              console.log(error);
              swal(
                'Error!',
                'Error interno contacte al administrador',
                'error'
              );
              me.listarVenta(1,'','tipo_comprobante');
            });
          },
          selectCliente(search,loading){
            let me = this;
            loading(true)
            var url = "/clientes/seleccionar?filtro="+search;
            axios.get(url).then(function (response){
              let respuesta = response.data;
              q: search
              me.clientes = respuesta.clientes;
              loading(false)
            })
            .catch(function (error){
              console.log(error);

            });
          },
          getDatosCliente(val){
            let me = this;
            me.loading = true;

            me.cliente_uuid = val.uuid;
          },
          buscarArticulo(){
            let me = this;
            var url = 'articulos/buscarVenta?filtro='+me.codigo;

            axios.get(url).then(function (response){
              var respuesta =response.data;
              me.articulos = respuesta.articulos;
              if (me.articulos.length>0) {
                var item = me.articulos[0];
                me.articulo = item['nombre'];
                me.articulo_uuid = item['uuid'];
                me.precio = item['precio'];
                me.existencias = item['existencias'];
              }else{
                me.articulo = 'No existe artículo';
                me.articulo_uuid = '';
              }
            })
            .catch(function (error){
              console.log(error);
            });
          },
          pdfVenta(uuid){
            var url = window.location.protocol + "//" + window.location.host + '/ventas/'+uuid+'/pdf';
            window.open(url);
          },
          abrirModal(){
            this.articulos = [];
            this.modal = 1;
            this.tituloModal = 'Seleccione artículo(s)';
          },
          cerrarModal(){
            this.modal = 0;
            this.tituloModal = '';
          },
          desactivar(venta_uuid){
            const swalWithBootstrapButtons = swal.mixin({
              confirmButtonClass: 'btn btn-success',
              cancelButtonClass: 'btn btn-danger',
              buttonsStyling: false,
            })

            swalWithBootstrapButtons({
              title: '¿Estás segur@?',
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Aceptar',
              cancelButtonText: 'Cancelar',
              reverseButtons: true
            }).then((result) => {
              if (result.value) {

                let me = this;
                axios.put('ventas/desactivar/'+venta_uuid).then(function (response){
                  me.listarVenta(1,'','tipo_comprobante');

                  swalWithBootstrapButtons(
                    'Desactivada!',
                    'La venta ha sido anulada.',
                    'success'
                  )
                })
                .catch(function (error){
                  console.log(error);
                });

              } else if (
                // Read more about handling dismissals
                result.dismiss === swal.DismissReason.cancel
              ) {
                swalWithBootstrapButtons(
                  'Cancelado',
                  'La venta sigue activa',
                  'error'
                )
              }
            })
          },
          validar(){
            let me = this;
            me.error = 0;
            me.errors = [];
            var art;

            me.detalles.map(function(x){
              if (x.cantidad > x.existencias) {
                art = x.articulo+' con existencias insuficiente';
                me.errors.push(art);
              }
            });

            if(!me.cliente_uuid) me.errors.push('Seleccione un cliente');
            if(!me.tipo_comprobante) me.errors.push('Seleccione el tipo de comprobante');
            if(!me.num_comprobante) me.errors.push('Ingrese el número de comprobante');
            if(!me.impuesto) me.errors.push('Ingrese el impuesto');
            if(me.detalles.length<=0) me.errors.push('Ingrese detalles de la venta');

            if (me.errors.length) me.error = 1;

            return me.error;
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
          verVenta(uuid){
            this.listado=2;

            var ventaTemp = {};
            var me =this;

            //cabecera
            axios.get('ventas/cabecera/'+uuid).then(function (response){
              ventaTemp = response.data.venta;

              me.cliente = ventaTemp.nombre;
              me.tipo_comprobante = ventaTemp.tipo_comprobante;
              me.serie_comprobante = ventaTemp.serie_comprobante;
              me.num_comprobante = ventaTemp.num_comprobante;
              me.impuesto = ventaTemp.impuesto;
              me.total = ventaTemp.total;
            })
            .catch(function (error){
              console.log(error);
            });

            //detalles
            axios.get('ventas/detalles/'+uuid).then(function (response){
              me.detalles = response.data.detalles;
            })
            .catch(function (error){
              console.log(error);
            });
          }
        },
        mounted() {
            this.listarVenta(1,this.buscar,this.criterio);

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
