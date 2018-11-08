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
                  <i class="fa fa-align-justify"></i> Clientes
                  <button type="button" @click="abrirModal('cliente','registrar')" class="btn btn-secondary">
                      <i class="icon-plus"></i>&nbsp;Nuevo
                  </button>
              </div>
              <div class="card-body">
                  <div class="form-group row">
                      <div class="col-md-6">
                          <div class="input-group">
                              <select class="form-control col-md-3" v-model="criterio">
                                <option value="nombre">Nombre</option>
                                <option value="email">Correo</option>
                                <option value="tipo_documento">Tipo de documento</option>
                                <option value="num_documento">Número de documento</option>
                              </select>
                              <input type="text" class="form-control" placeholder="Texto a buscar" v-model="buscar" @keyup.enter="listarCliente(1,buscar,criterio)">
                              <button type="submit" @click="listarCliente(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                          </div>
                      </div>
                  </div>
                  <table class="table table-bordered table-striped table-sm">
                      <thead>
                          <tr>
                              <th>Opciones</th>
                              <th>Nombre</th>
                              <th>Correo</th>
                              <th>Tipo Documento</th>
                              <th>Número Documento</th>
                              <th>Dirección</th>
                              <th>Teléfono</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="cliente in clientes" :key="cliente.uuid">
                              <td>
                                  <button type="button" @click="abrirModal('cliente','actualizar',cliente)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp;
                              </td>
                              <td v-text="cliente.nombre"></td>
                              <td v-text="cliente.email"></td>
                              <td v-text="cliente.tipo_documento"></td>
                              <td v-text="cliente.num_documento"></td>
                              <td v-text="cliente.direccion"></td>
                              <td v-text="cliente.telefono"></td>
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
                      <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCliente()">Guardar</button>
                      <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCliente()">Actualizar</button>
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
            tipo_documento: 'INE',
            num_documento: '',
            email: '',
            direccion: '',
            telefono: '',
            clientes: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            error: 0,
            errors: [],
            cliente_uuid: '',
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
          listarCliente(page,buscar,criterio){
            let me = this;
            var url = '/clientes?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response){
              me.clientes = response.data.clientes.data;
              me.pagination = response.data.pagination;
            })
            .catch(function (error){
              console.log(error);
            });
          },
          cambiarPagina(page,buscar,criterio){
            let me =this;
            me.pagination.current_page = page;

            me.listarCliente(page,buscar,criterio);
          },
          registrarCliente(){
            if (this.validar()) {
              return;
            }
            let me = this;
            axios.post('/clientes',{
              'nombre': me.nombre,
              'descripcion': me.descripcion
            }).then(function (response){
              me.cerrarModal();
              me.listarCliente(1,'','nombre');
            })
            .catch(function (error){
              console.log(error);
            });
          },
          actualizarCliente(){
            if (this.validar()) {
              return;
            }
            let me = this;
            axios.put('/categorias/'+this.cliente_uuid,{
              'nombre': me.nombre,
              'descripcion': me.descripcion
            }).then(function (response){
              me.cerrarModal();
              me.listarCliente(1,'','nombre');
            })
            .catch(function (error){
              console.log(error);
            });
          },
          abrirModal(modelo, accion, data = []){
            switch (modelo) {
              case 'cliente':
              {
                switch (accion) {
                  case 'registrar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Registrar Cliente';
                    this.nombre = '';
                    this.tipo_documento = 'INE',
                    this.num_documento = '',
                    this.email = '',
                    this.direccion = '',
                    this.telefono = '',
                    this.tipoAccion = 1;
                    break;
                  }
                  case 'actualizar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Actualizar Cliente '+data.nombre;
                    this.nombre = data.nombre;
                    this.tipo_documento = data.tipo_documento,
                    this.num_documento = data.num_documento,
                    this.email = data.email,
                    this.direccion = data.direccion,
                    this.telefono = data.telefono,
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
            this.nombre = '';
            this.tipo_documento = 'INE',
            this.num_documento = '',
            this.email = '',
            this.direccion = '',
            this.telefono = ''
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
            this.listarCliente(1,this.buscar,this.criterio);

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
