<template>
  <!-- Contenido Principal -->
  <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
      </ol>

      <div class="container-fluid">
          <!-- Ejemplo de tabla Listado -->
          <div class="card">
              <div class="card-header">
                  <i class="fa fa-align-justify"></i> Artículos
                  <button type="button" @click="abrirModal('articulo','registrar')" class="btn btn-secondary">
                      <i class="icon-plus"></i>&nbsp;Nuevo
                  </button>
                  <button type="button" @click="cargarPDF()" class="btn btn-info">
                      <i class="icon-doc"></i>&nbsp;Reporte
                  </button>
              </div>
              <div class="card-body">
                  <div class="form-group row">
                      <div class="col-md-6">
                          <div class="input-group">
                              <select class="form-control col-md-3" v-model="criterio">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                                <option value="categoria">Categoría</option>
                              </select>
                              <input type="text" class="form-control" placeholder="Texto a buscar" v-model="buscar" @keyup.enter="listarArticulo(1,buscar,criterio)">
                              <button type="submit" @click="listarArticulo(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                          </div>
                      </div>
                  </div>
                  <table class="table table-bordered table-striped table-sm">
                      <thead>
                          <tr>
                              <th>Opciones</th>
                              <th>Código</th>
                              <th>Nombre</th>
                              <th>Categoría</th>
                              <th>Precio unitario</th>
                              <th>Unidades en almácen</th>
                              <th>Descripción</th>
                              <th>Estado</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="articulo in articulos" :key="articulo.uuid">
                              <td>
                                  <button type="button" @click="abrirModal('articulo','actualizar',articulo)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp;
                                  <template v-if="articulo.condicion">
                                    <button type="button" @click="desactivar(articulo.uuid)" class="btn btn-danger btn-sm">
                                      <i class="icon-trash"></i>
                                    </button>
                                  </template>
                                  <template v-else>
                                    <button type="button" @click="activar(articulo.uuid)" class="btn btn-success btn-sm">
                                      <i class="icon-check"></i>
                                    </button>
                                  </template>

                              </td>
                              <td v-text="articulo.codigo"></td>
                              <td v-text="articulo.nombre"></td>
                              <td v-text="articulo.nombre_categoria"></td>
                              <td v-text="articulo.precio"></td>
                              <td v-text="articulo.existencias"></td>
                              <td v-text="articulo.descripcion"></td>
                              <td>
                                  <div v-if="articulo.condicion">
                                    <span class="badge badge-success">Activo</span>
                                  </div>
                                  <div v-else>
                                    <span class="badge badge-danger">No Activo</span>
                                  </div>
                              </td>
                          </tr>
                      </tbody>
                  </table>
                  <nav>
                      <ul class="pagination">
                          <li class="page-item" v-if="pagination.current_page > 1">
                              <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                          </li>
                          <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                              <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                          </li>
                          <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                              <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                          </li>
                      </ul>
                  </nav>
              </div>
          </div>
          <!-- Fin ejemplo de tabla Listado -->
      </div>

      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
          <div class="modal-dialog modal-primary modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title" v-text="tituloModal"></h4>
                      <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                              <div class="col-md-9">
                                  <select class="form-control" v-model="categoria_uuid">
                                    <option value="" disabled>Seleccione</option>
                                    <option v-for="categoria in categorias" :key="categoria.uuid" :value="categoria.uuid" v-text="categoria.nombre"></option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Código</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="codigo" class="form-control" placeholder="Código de barras">
                                  <barcode :value="codigo" :options="{format: 'EAN-13'}">
                                    Generando código de barras.
                                  </barcode>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de artículo">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Precio unitario</label>
                              <div class="col-md-9">
                                  <input type="number" v-model="precio" class="form-control" placeholder="">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Piezas existentes</label>
                              <div class="col-md-9">
                                  <input type="number" v-model="existencias" class="form-control" placeholder="">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="descripcion" class="form-control" placeholder="Descripción del artículo">
                              </div>
                          </div>
                          <div v-show="error" class="form-group row div-error">
                            <div class="text-center text-error">
                              <div v-for="error in errors" :key="error" v-text="error">

                              </div>
                            </div>
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cerrar</button>
                      <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarArticulo()">Guardar</button>
                      <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarArticulo()">Actualizar</button>
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
    import VueBarcode from 'vue-barcode';
    export default {
        data() {
          return{
            nombre: '',
            nombre_categoria: '',
            codigo: '',
            precio: 0,
            existencias: 0,
            descripcion: '',
            articulos: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            error: 0,
            errors: [],
            articulo_uuid: '',
            categoria_uuid: '',
            pagination: {
              'total': 0,
              'current_page': 0,
              'per_page': 0,
              'last_page': 0,
              'from': 0,
              'to': 0
            },
            offset: 3,
            criterio: 'nombre',
            buscar: '',
            categorias: []
          }
        },
        components: {
          'barcode': VueBarcode
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
          listarArticulo(page,buscar,criterio){
            let me = this;
            var url = '/articulos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response){
              me.articulos = response.data.articulos.data;
              me.pagination = response.data.pagination;
            })
            .catch(function (error){
              console.log(error);
            });
          },
          cargarPDF(){
            var url = window.location.protocol + "//" + window.location.host + '/articulos/pdf';
            window.open(url);
          },
          selectCategoria(){
            let me = this;
            var url = '/categorias/seleccionar';
            axios.get(url).then(function (response){
              me.categorias = response.data.categorias;
            })
            .catch(function (error){
              console.log(error);
            });
          },
          cambiarPagina(page,buscar,criterio){
            let me =this;
            me.pagination.current_page = page;

            me.listarArticulo(page,buscar,criterio);
          },
          registrarArticulo(){
            if (this.validar()) {
              return;
            }
            let me = this;
            axios.post('/articulos',{
              'categoria_uuid': me.categoria_uuid,
              'codigo': me.codigo,
              'precio': me.precio,
              'existencias': me.existencias,
              'nombre': me.nombre,
              'descripcion': me.descripcion
            }).then(function (response){
              me.cerrarModal();
              swal(
                'Exito!',
                'Operación exitosa',
                'success'
              );
              me.listarArticulo(1,'','nombre');
            })
            .catch(function (error){
              console.log(error);
              me.cerrarModal();
              swal(
                'Error!',
                'Error interno contacte al administrador',
                'error'
              );
              me.listarArticulo(1,'','nombre');
            });
          },
          actualizarArticulo(){
            if (this.validar()) {
              return;
            }
            let me = this;
            axios.put('/articulos/'+this.articulo_uuid,{
              'categoria_uuid': me.categoria_uuid,
              'codigo': me.codigo,
              'precio': me.precio,
              'existencias': me.existencias,
              'nombre': me.nombre,
              'descripcion': me.descripcion
            }).then(function (response){
              me.cerrarModal();
              swal(
                'Exito!',
                'Operación exitosa',
                'success'
              );
              me.listarArticulo(1,'','nombre');
            })
            .catch(function (error){
              console.log(error);
              me.cerrarModal();
              swal(
                'Error!',
                'Error interno contacte al administrador',
                'error'
              );
              me.listarArticulo(1,'','nombre');
            });
          },
          abrirModal(modelo, accion, data = []){
            switch (modelo) {
              case 'articulo':
              {
                switch (accion) {
                  case 'registrar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Registrar Artículo';
                    this.categoria_uuid = '';
                    this.nombre_categoria = '';
                    this.codigo = '';
                    this.precio = 0;
                    this.existencias = 0;
                    this.nombre = '';
                    this.descripcion = '';
                    this.tipoAccion = 1;
                    break;
                  }
                  case 'actualizar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Actualizar Artículo '+data.nombre;
                    this.nombre = data.nombre;
                    this.descripcion = data.descripcion;
                    this.articulo_uuid = data.uuid;
                    this.categoria_uuid = data.categoria_uuid;
                    this.codigo = data.codigo;
                    this.precio = data.precio;
                    this.existencias = data.existencias;
                    this.tipoAccion = 2;
                    break;
                  }
                }
              }
            }
            this.selectCategoria();
          },
          cerrarModal(){
            this.modal = 0;
            this.tituloModal = "";
            this.nombre = "";
            this.descripcion = "";
            this.categoria_uuid = "";
            this.nombre_categoria = "";
            this.codigo = "";
            this.precio = 0;
            this.existencias = 0;
            this.error = 0;
          },
          desactivar(articulo_uuid){
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
                axios.put('/articulos/desactivar/'+articulo_uuid).then(function (response){
                  me.listarArticulo(1,'','nombre');

                  swalWithBootstrapButtons(
                    'Desactivada!',
                    'El artículo ha sido desactivado.',
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
                  'El artículo sigue activo',
                  'error'
                )
              }
            })
          },
          activar(articulo_uuid){

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
                axios.put('/articulos/activar/'+articulo_uuid).then(function (response){
                  me.listarArticulo(1,'','nombre');

                  swalWithBootstrapButtons(
                    'Activada!',
                    'El artículo ha sido activado.',
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
                  'El artículo sigue desactivado',
                  'error'
                )
              }
            })
          },
          validar(){
            this.error = 0;
            this.errors = [];

            if(this.categoria_uuid == '') this.errors.push('Seleccione una categoría');
            if(!this.nombre) this.errors.push('El nombre es obligatorio');
            if(!this.existencias) this.errors.push('las piezas debe ser un valor númerico y no puede estar vacío');
            if(!this.precio) this.errors.push('el precio debe ser un valor númerico y no puede estar vacío');

            if (this.errors.length) {
              this.error = 1;
            }
            return this.error;
          }
        },
        mounted() {
            this.listarArticulo(1,this.buscar,this.criterio);
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
</style>
