<template>
  <!-- Contenido Principal -->
  <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item"><a href="#">Admin</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
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
                              <select class="form-control col-md-3" id="opcion" name="opcion">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                              </select>
                              <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar">
                              <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                          <li class="page-item">
                              <a class="page-link" href="#">Ant</a>
                          </li>
                          <li class="page-item active">
                              <a class="page-link" href="#">1</a>
                          </li>
                          <li class="page-item">
                              <a class="page-link" href="#">2</a>
                          </li>
                          <li class="page-item">
                              <a class="page-link" href="#">3</a>
                          </li>
                          <li class="page-item">
                              <a class="page-link" href="#">4</a>
                          </li>
                          <li class="page-item">
                              <a class="page-link" href="#">Sig</a>
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
            categoria_uuid: ''
          }
        },
        methods: {
          listarCategoria(){
            let me = this;
            axios.get('/categorias').then(function (response){
              me.categorias =response.data;
            })
            .catch(function (error){
              console.log(error);
            });
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
              me.listarCategoria();
            })
            .catch(function (error){
              console.log(error);
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
              me.listarCategoria();
            })
            .catch(function (error){
              console.log(error);
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
              case 'articulo':
                {

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
            console.log(categoria_uuid);
            const swalWithBootstrapButtons = swal.mixin({
              confirmButtonClass: 'btn btn-success',
              cancelButtonClass: 'btn btn-danger',
              buttonsStyling: false,
            })

            swalWithBootstrapButtons({
              title: '¿Estás seguro?',
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Aceptar',
              cancelButtonText: 'Cancelar',
              reverseButtons: true
            }).then((result) => {
              if (result.value) {

                let me = this;
                axios.put('/categorias/desactivar/'+categoria_uuid).then(function (response){
                  me.listarCategoria();

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
                  'Cancelado',
                  'La categoría sigue activa',
                  'error'
                )
              }
            })
          },
          activar(categoria_uuid){
            console.log(categoria_uuid);
            const swalWithBootstrapButtons = swal.mixin({
              confirmButtonClass: 'btn btn-success',
              cancelButtonClass: 'btn btn-danger',
              buttonsStyling: false,
            })

            swalWithBootstrapButtons({
              title: '¿Estás seguro?',
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Aceptar',
              cancelButtonText: 'Cancelar',
              reverseButtons: true
            }).then((result) => {
              if (result.value) {

                let me = this;
                axios.put('/categorias/activar/'+categoria_uuid).then(function (response){
                  me.listarCategoria();

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
            this.listarCategoria();
            window.swal = require('sweetalert2');
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
