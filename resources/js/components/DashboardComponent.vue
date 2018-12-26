<template lang="html">
<main class="main">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
  </ol>
  <div class="container-fluid">
    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4>Ingresos</h4>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <canvas id="ingresos">

                                </canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p>Compras de los últimos meses.</p>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4>Ventas</h4>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <canvas id="ventas">

                                </canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p>Ventas de los últimos meses.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
  </div>
</main>
</template>

<script>
export default {
  data() {

      return {
          ingreso: null,
          charIngreso: null,
          ingresos: [],
          totalIngreso: [],
          mesIngreso: []
      }
  },
  methods: {

  },
  mounted() {

  }
}
</script>

<style lang="css">
</style>
