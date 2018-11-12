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
                  <i class="fa fa-align-justify"></i> Categorías
                  <button type="button" @click="abrirModal('categoria','registrar')" class="btn btn-secondary">
                      <i class="icon-plus"></i>&nbsp;Nuevo
                  </button>
              </div>
              <div class="card-body">
                  <div class="form-group row">
                      <div class="col-md-6">
                          <div class="input-group">
                              <select class="form-control col-md-3" v-model="criterio">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                              </select>
                              <input type="text" class="form-control" placeholder="Texto a buscar" v-model="buscar" @keyup.enter="listarCategoria(1,buscar,criterio)">
                              <button type="submit" @click="listarCategoria(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                          </div>
                      </div>
                  </div>
                  <table class="table table-bordered table-striped table-sm">
                      <thead>
                          <tr>
                              <th>Opciones</th>
                              <th>Nombre</th>
                              <th>Descripción</th>
                              <th>Estado</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="categoria in categorias" :key="categoria.uuid">
                              <td>
                                  <button type="button" @click="abrirModal('categoria','actualizar',categoria)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp;
                                  <template v-if="categoria.condicion">
                                    <button type="button" @click="desactivar(categoria.uuid)" class="btn btn-danger btn-sm">
                                      <i class="icon-trash"></i>
                                    </button>
                                  </template>
                                  <template v-else>
                                    <button type="button" @click="activar(categoria.uuid)" class="btn btn-success btn-sm">
                                      <i class="icon-check"></i>
                                    </button>
                                  </template>

                              </td>
                              <td v-text="categoria.nombre"></td>
                              <td v-text="categoria.descripcion"></td>
                              <td>
                                  <div v-if="categoria.condicion">
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
                              <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="descripcion" class="form-control" placeholder="Descripción de la categoría">
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
                      <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCategoria()">Guardar</button>
                      <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCategoria()">Actualizar</button>
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
    export default {
        data() {
          return{
            nombre: '',
            descripcion: '',
            categorias: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            error: 0,
            errors: [],
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
            buscar: ''
          }
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
          listarCategoria(page,buscar,criterio){
            let me = this;
            var url = '/categorias?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response){
              me.categorias = response.data.categorias.data;
              me.pagination = response.data.pagination;
            })
            .catch(function (error){
              console.log(error);
            });
          },
          cambiarPagina(page,buscar,criterio){
            let me =this;
            me.pagination.current_page = page;

            me.listarCategoria(page,buscar,criterio);
          },
          registrarCategoria(){
            if (this.validar()) {
              return;
            }
            let me = this;
            axios.post('/categorias',{
              'nombre': me.nombre,
              'descripcion': me.descripcion
            }).then(function (response){
              me.cerrarModal();
              swal(
                'Exito!',
                'Operación exitosa',
                'success'
              );
              me.listarCategoria(1,'','nombre');
            })
            .catch(function (error){
              console.log(error);
              me.cerrarModal();
              swal(
                'Error!',
                'Error interno contacte al administrador',
                'error'
              );
              me.listarCategoria(1,'','nombre');
            });
          },
          actualizarCategoria(){
            if (this.validar()) {
              return;
            }
            let me = this;
            axios.put('/categorias/'+this.categoria_uuid,{
              'nombre': me.nombre,
              'descripcion': me.descripcion
            }).then(function (response){
              me.cerrarModal();
              swal(
                'Exito!',
                'Operación exitosa',
                'success'
              );
              me.listarCategoria(1,'','nombre');
            })
            .catch(function (error){
              console.log(error);
              me.cerrarModal();
              swal(
                'Error!',
                'Error interno contacte al administrador',
                'error'
              );
              me.listarCategoria(1,'','nombre');
            });
          },
          abrirModal(modelo, accion, data = []){
            switch (modelo) {
              case 'categoria':
              {
                switch (accion) {
                  case 'registrar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Registrar Categoría';
                    this.nombre = '';
                    this.descripcion = '';
                    this.tipoAccion = 1;
                    break;
                  }
                  case 'actualizar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Actualizar Categoría '+data.nombre;
                    this.nombre = data.nombre;
                    this.descripcion = data.descripcion;
                    this.categoria_uuid = data.uuid;
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
            this.nombre = "";
            this.descripcion = "";
          },
          desactivar(categoria_uuid){
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
                axios.put('/categorias/desactivar/'+categoria_uuid).then(function (response){
                  me.listarCategoria(1,'','nombre');

                  swalWithBootstrapButtons(
                    'Desactivada!',
                    'La categoría ha sido desactivada.',
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
                  'Cancelada',
                  'La categoría sigue activa',
                  'error'
                )
              }
            })
          },
          activar(categoria_uuid){
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
                axios.put('/categorias/activar/'+categoria_uuid).then(function (response){
                  me.listarCategoria(1,'','nombre');

                  swalWithBootstrapButtons(
                    'Activada!',
                    'La categoría ha sido activada.',
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
                  'La categoría sigue desactivada',
                  'error'
                )
              }
            })
          },
          validar(){
            this.error = 0;
            this.errors = [];

            if(!this.nombre) this.errors.push('El nombre es obligatorio');

            if (this.errors.length) {
              this.error = 1;
            }
            return this.error;
          }
        },
        mounted() {
            this.listarCategoria(1,this.buscar,this.criterio);

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
