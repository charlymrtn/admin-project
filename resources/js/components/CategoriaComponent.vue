<template>
  <!-- Contenido Principal -->
  <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item"><a href="#">Admin</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
      </ol>

      <table-content-component></table-content-component>

      <modal-nuevoeditar-component></modal-nuevoeditar-component>

      <modal-eliminar-component></modal-eliminar-component>

  </main>
</template>

<script>
    export default {
        data() {
          return{
            nombre: '',
            descripcion: ''
          }          
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
