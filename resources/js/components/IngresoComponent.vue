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
              <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                  <i class="icon-plus"></i>&nbsp;Nuevo
              </button>
          </div>
          <!-- Listado-->
          <template v-if="listado">
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
                          <tr v-for="ingreso in ingresos" :key="ingreso.id">
                              <td>
                                  <button type="button" @click="abrirModal('ingreso','actualizar',ingreso)" class="btn btn-success btn-sm">
                                  <i class="icon-eye"></i>
                                  </button> &nbsp;
                                  <template v-if="ingreso.estado=='Registrado'">
                                      <button type="button" class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)">
                                          <i class="icon-trash"></i>
                                      </button>
                                  </template>
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
          <!-- Detalle-->
          <template v-else>
          <div class="card-body">
              <div class="form-group row border">
                  <div class="col-md-9">
                      <div class="form-group">
                          <label for="">Proveedor(*)</label>
                          <v-select
                          :on-search="selectProveedor"
                          label="nombre"
                          :options="proveedores"
                          placeholder="Buscar Proveedores..."
                          :onChange="getDatosProveedor"
                          >

                          </v-select>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <label for="">Impuesto(*)</label>
                      <input type="text" class="form-control" v-model="impuesto">
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label>Tipo Comprobante(*)</label>
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
                          <label>Serie Comprobante</label>
                          <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label>Número Comprobante(*)</label>
                          <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx">
                      </div>
                  </div>
              </div>
              <div class="form-group row border">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label>Artículo</label>
                          <div class="form-inline">
                              <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Ingrese artículo">
                              <button class="btn btn-primary">...</button>
                              <input type="text" readonly class="form-control" v-model="articulo">
                          </div>
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          <label>Precio</label>
                          <input type="number" value="0" step="any" class="form-control" v-model="precio">
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          <label>Cantidad</label>
                          <input type="number" value="0" class="form-control" v-model="cantidad">
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
                              <th>Subtotal</th>
                            </tr>
                          </thead>
                          <tbody v-if="detalles.length">
                            <tr v-for="detalle in detalles" :key="detalle.uuid">
                              <td>
                                  <button type="button" class="btn btn-danger btn-sm">
                                      <i class="icon-close"></i>
                                  </button>
                              </td>
                              <td v-text="detalle.articulo"></td>
                              <td>
                                  <input v-model="detalle.precio" type="number" step="any" class="form-control">
                              </td>
                              <td>
                                  <input v-model="detalle.cantidad" type="number" class="form-control">
                              </td>
                              <td>
                                  $ {{detalle.precio*detalle.cantidad}}
                              </td>
                            </tr>
                            <tr style="background-color: #CEECF5;">
                                <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                <td>$ 5</td>
                            </tr>
                            <tr style="background-color: #CEECF5;">
                                <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                <td>$ 1</td>
                            </tr>
                            <tr style="background-color: #CEECF5;">
                                <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                <td>$ 6</td>
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
                      <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
                  </div>
              </div>
          </div>
          </template>
          <!-- Fin Detalle-->
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

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                  <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                  <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
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
            nombre: '',
            tipo_comprobante: 'RECIBO',
            serie_comprobante: '',
            num_comprobante: '',
            impuesto: 0.16,
            total: 0.0,
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
          agregarDetalle(){
            let me = this;
            me.detalles.push({
              articulo_uuid: me.articulo_uuid,
              articulo: me.articulo,
              cantidad: me.cantidad,
              precio: me.precio
            });
          },
          registrarIngreso(){
            if (this.validar()) {
              return;
            }
            let me = this;
            axios.post('/ingresos',{
              'proveedor_uuid': me.proveedor_uuid,
              'tipo_comprobante': me.tipo_comprobante,
              'serie_comprobante': me.serie_comprobante,
              'num_comprobante': me.num_comprobante,
              'impuesto': me.impuesto,
              'total': me.total
            }).then(function (response){
              me.cerrarModal();
              swal(
                'Exito!',
                'Ingreso Creado',
                'success'
              );
              me.listarIngreso(1,'','tipo_comprobante');
            })
            .catch(function (error){
              me.cerrarModal();
              swal(
                'Error!',
                'Error interno contacte al administrador',
                'error'
              );
              me.listarIngreso(1,'','tipo_comprobante');
            });
          },
          selectProveedor(search,loading){
            let me = this;
            loading(true)
            var url = "/proveedores/seleccionar?filtro="+search;
            axios.get(url).then(function (response){
              let respuesta = response.data;
              q: search
              me.proveedores = respuesta.proveedores;
              loading(false)
            })
            .catch(function (error){
              console.log(error);

            });
          },
          getDatosProveedor(val){
            let me = this;
            me.loading = true;

            me.proveedor_uuid = val.uuid;
          },
          buscarArticulo(){
            let me = this;
            var url = 'articulos/buscar?filtro='+me.codigo;

            axios.get(url).then(function (response){
              var respuesta =response.data;
              me.articulos = respuesta.articulos;
              if (me.articulos.length>0) {
                var item = me.articulos[0];
                me.articulo = item['nombre'];
                me.articulo_uuid = item['uuid'];
              }else{
                me.articulo = 'No existe artículo';
                me.articulo_uuid = '';
              }
            })
            .catch(function (error){
              console.log(error);
            });
          },
          abrirModal(modelo, accion, data = []){
            switch (modelo) {
              case 'ingreso':
              {
                switch (accion) {
                  case 'registrar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Registrar Ingreso';
                    this.nombre = '';
                    this.tipo_documento = 'Recibo';
                    this.num_documento = '';
                    this.email = '';
                    this.direccion = '';
                    this.telefono = '';
                    this.nombre_usuario = '';
                    this.password = '';
                    this.rol_uuid = '';
                    this.tipoAccion = 1;
                    break;
                  }
                  case 'actualizar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Actualizar Usuario '+data.nombre;
                    this.nombre = data.nombre;
                    this.tipo_documento = data.tipo_documento;
                    this.num_documento = data.num_documento;
                    this.email = data.email;
                    this.direccion = data.direccion;
                    this.telefono = data.telefono;
                    this.nombre_usuario = data.usuario;
                    this.password = data.password;
                    this.rol_uuid = data.rol_uuid;
                    this.usuario_uuid = data.uuid;
                    this.tipoAccion = 2;
                    break;
                  }
                }
              }
            }
          },
          cerrarModal(){
            this.modal = 0;
            this.tituloModal = "";
            this.nombre = '';
            this.tipo_documento = 'INE';
            this.num_documento = '';
            this.email = '';
            this.direccion = '';
            this.telefono = '';
            this.nombre_usuario = 0;
            this.password = '';
            this.rol_uuid = '';
            this.error = 0;
          },
          desactivar(ingreso_uuid){
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
                axios.put('ingresos/desactivar/'+ingreso_uuid).then(function (response){
                  me.listarIngreso(1,'','tipo_comprobante');

                  swalWithBootstrapButtons(
                    'Desactivado!',
                    'El usuario ha sido desactivado.',
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
                  'El usuario sigue activo',
                  'error'
                )
              }
            })
          },
          validar(){
            this.error = 0;
            this.errors = [];

            if(!this.nombre) this.errors.push('El nombre es obligatorio');
            if(!this.email) this.errors.push('El correo es obligatorio');
            if(!this.nombre_usuario) this.errors.push('El nombre de usuario es obligatorio');
            if(!this.password && this.tipoAccion == 1) this.errors.push('La contraseña es obligatoria');
            if(!this.rol_uuid) this.errors.push('El rol es obligatorio');

            if (this.errors.length) {
              this.error = 1;
            }
            return this.error;
          },
          mostrarDetalle(){
              this.listado=0;
          },
          ocultarDetalle(){
              this.listado=1;
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
