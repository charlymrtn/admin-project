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
          mesIngreso: [],

          venta: null,
          charVenta: null,
          ventas: [],
          totalVenta: [],
          mesVenta: []
      }
  },
  methods: {
      getEstadisticas(){
        let me = this;
        var url = '/dashboard';
        axios.get(url).then(function (response){
            var respuesta = response.data;
            me.ingresos = respuesta.ingresos;
            me.ventas = respuesta.ventas;
            me.loadIngresos();
            me.loadVentas();
        })
        .catch(function (error){
            console.log(error);
         })
    },
    loadIngresos(){
        let me = this;
        me.ingresos.map(function(x){
           me.mesIngreso.push(x.mes);
           me.totalIngreso.push(x.total);
        });
        me.ingreso = document.getElementById('ingresos').getContext('2d');

        me.charIngreso = new Chart(me.ingreso, {
            type: 'bar',
            data: {
                labels: me.mesIngreso,
                datasets: [{
                    label: 'Ingresos',
                    data: me.totalIngreso,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    },
      loadVentas(){
          let me = this;
          me.ventas.map(function(x){
              me.mesVenta.push(x.mes);
              me.totalVenta.push(x.total);
          });
          me.venta = document.getElementById('ventas').getContext('2d');

          me.charVenta = new Chart(me.venta, {
              type: 'bar',
              data: {
                  labels: me.mesVenta,
                  datasets: [{
                      label: 'Ventas',
                      data: me.totalVenta,
                      backgroundColor: 'rgba(48, 78, 187, 0.2)',
                      borderColor: 'rgba(48, 78, 187, 0.2)',
                      borderWidth: 1
                  }]
              },
              options: {
                  scales: {
                      yAxes: [{
                          ticks: {
                              beginAtZero:true
                          }
                      }]
                  }
              }
          });
      },
  },
  mounted() {
    this.getEstadisticas();
  }
}
</script>

<style lang="css">
</style>
