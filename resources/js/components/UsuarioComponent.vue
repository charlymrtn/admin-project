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
                  <i class="fa fa-align-justify"></i> Proveedores
                  <button type="button" @click="abrirModal('proveedor','registrar')" class="btn btn-secondary">
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
                              <input type="text" class="form-control" placeholder="Texto a buscar" v-model="buscar" @keyup.enter="listarProveedor(1,buscar,criterio)">
                              <button type="submit" @click="listarProveedor(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                              <th>Contacto</th>
                              <th>Teléfono de contacto</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="proveedor in proveedores" :key="proveedor.uuid">
                              <td>
                                  <button type="button" @click="abrirModal('proveedor','actualizar',proveedor)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp;
                              </td>
                              <td v-text="proveedor.nombre"></td>
                              <td v-text="proveedor.email"></td>
                              <td v-text="proveedor.tipo_documento"></td>
                              <td v-text="proveedor.num_documento"></td>
                              <td v-text="proveedor.direccion"></td>
                              <td v-text="proveedor.telefono"></td>
                              <td v-text="proveedor.contacto"></td>
                              <td v-text="proveedor.telefono_contacto"></td>
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
                                  <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de proveedor">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Correo electrónico</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="email" class="form-control" placeholder="Correo electrónico">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Tipo de documento</label>
                              <div class="col-md-9">
                                  <select v-model="tipo_documento" class="form-control">
                                    <option value="INE">INE</option>
                                    <option value="pasaporte">Pasaporte</option>
                                    <option value="RFC">RFC</option>
                                    <option value="cedula">Cédula Profesional</option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Número de documento</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="num_documento" class="form-control" placeholder="Número de documento">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Contacto</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="contacto" class="form-control" placeholder="Contacto">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Teléfono de contacto</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="telefono_contacto" class="form-control" placeholder="Teléfono de contacto">
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
                      <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarProveedor()">Guardar</button>
                      <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarProveedor()">Actualizar</button>
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
            contacto: '',
            telefono_contacto: '',
            proveedores: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            error: 0,
            errors: [],
            proveedor_uuid: '',
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
          listarProveedor(page,buscar,criterio){
            let me = this;
            var url = '/proveedores?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response){
              me.proveedores = response.data.proveedores.data;
              me.pagination = response.data.pagination;
            })
            .catch(function (error){
              console.log(error);
            });
          },
          cambiarPagina(page,buscar,criterio){
            let me =this;
            me.pagination.current_page = page;

            me.listarProveedor(page,buscar,criterio);
          },
          registrarProveedor(){
            if (this.validar()) {
              return;
            }
            let me = this;
            axios.post('/proveedores',{
              'nombre': me.nombre,
              'email': me.email,
              'tipo_documento': me.tipo_documento,
              'num_documento': me.num_documento,
              'direccion': me.direccion,
              'telefono': me.telefono,
              'contacto': me.contacto,
              'telefono_contacto': me.telefono_contacto
            }).then(function (response){
              me.cerrarModal();
              me.listarProveedor(1,'','nombre');
            })
            .catch(function (error){
              console.log(error);
            });
          },
          actualizarProveedor(){
            if (this.validar()) {
              return;
            }
            let me = this;
            var url = '/proveedores/'+this.proveedor_uuid;
            axios.put(url,{
              'nombre': me.nombre,
              'email': me.email,
              'tipo_documento': me.tipo_documento,
              'num_documento': me.num_documento,
              'direccion': me.direccion,
              'telefono': me.telefono,
              'contacto': me.contacto,
              'telefono_contacto': me.telefono_contacto
            }).then(function (response){
              me.cerrarModal();
              me.listarProveedor(1,'','nombre');
            })
            .catch(function (error){
              console.log(error);
            });
          },
          abrirModal(modelo, accion, data = []){
            switch (modelo) {
              case 'proveedor':
              {
                switch (accion) {
                  case 'registrar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Registrar Proveedor';
                    this.nombre = '';
                    this.tipo_documento = 'RFC';
                    this.num_documento = '';
                    this.email = '';
                    this.direccion = '';
                    this.telefono = '';
                    this.contacto = '';
                    this.telefono_contacto = '';
                    this.tipoAccion = 1;
                    break;
                  }
                  case 'actualizar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Actualizar Proveedor '+data.nombre;
                    this.nombre = data.nombre;
                    this.tipo_documento = data.tipo_documento;
                    this.num_documento = data.num_documento;
                    this.email = data.email;
                    this.direccion = data.direccion;
                    this.telefono = data.telefono;
                    this.contacto = data.contacto;
                    this.telefono_contacto = data.telefono_contacto;
                    this.proveedor_uuid = data.uuid;
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
            this.tipo_documento = 'RFC';
            this.num_documento = '';
            this.email = '';
            this.direccion = '';
            this.telefono = '';
            this.contacto = '';
            this.telefono_contacto = '';
            this.error = 0;
          },
          validar(){
            this.error = 0;
            this.errors = [];

            if(!this.nombre) this.errors.push('El nombre es obligatorio');
            if(!this.email) this.errors.push('El correo es obligatorio');

            if (this.errors.length) {
              this.error = 1;
            }
            return this.error;
          }
        },
        mounted() {
            this.listarProveedor(1,this.buscar,this.criterio);

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
