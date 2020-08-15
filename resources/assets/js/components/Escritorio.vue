<template>
    <main class="main">
        <b-card>
            <spinner v-if="loadingCards"></spinner>
            <div class="row" v-else>

            <!-- Ventas Lince -->
            <div class="col-xl-3 col-md-6 mb-4" v-for="ventaDiaria in arrayVentasAlmacen" :key="ventaDiaria.id">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ventas {{ventaDiaria.almacen.substr(0,5)}}</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">S/ {{ventaDiaria.total}}</div>
                    </div>
                    <div class="col-auto">
                      <h2 class="text-muted text-bold"><strong>S/ </strong></h2>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Tasks Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tareas</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                        </div>
                        <div class="col">
                          <div class="progress progress-sm mr-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Traslados pendientes -->
            <div class="col-xl-3 col-md-6 mb-4" style="cursor:pointer;" @click="$root.menu = 14">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Traslados pendientes</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{cantidadTrasladosPendientes}}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fa fa-comments fa-2x text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
            <!-- Estadisticas semanales -->
            <b-card  class="border-left-warning shadow h-100 py-2">
                <div class="estadisticas">
                
                        <div  v-for="estadistica in arrayEstadisticasSemana" :key="estadistica.id"   style="width: 50%;" >
                        
                            <h4 style="margin-top: 10px; text-align: center;">Ventas de {{estadistica.titulo.substr(0,5)}} semanal</h4>
                                <ve-line :data="estadistica"  style="margin-top: 50px;" :toolbox="toolbox" :loading="loadingSemana" :settings="chartSettings"></ve-line>
                        </div>  
                    
                </div>
            </b-card>
            <!-- Estadisticas mensuales -->
            <b-card class="border-left-warning shadow h-100 py-2">
                <div class="estadisticas">
                    <div  v-for="estadistica in arrayEstadisticas" :key="estadistica.id"   style="width: 50%;" >
                    
                        <h4 style="margin-top: 10px; text-align: center;">Ventas de {{estadistica.titulo.substr(0,5)}} mensual</h4>
                            <ve-line :data="estadistica"  style="margin-top: 50px;" :loading="loading" :toolbox="toolbox"></ve-line>
                    </div>  
                </div>
                    
                
            </b-card>
            
            
        </b-card>
    </main>
</template>
<script>
import VeLine from 'v-charts/lib/line.common'
import 'v-charts/lib/style.css'
export default {
    created: function (){
        this.toolbox = {
        feature: {
          magicType: {type: ['line', 'bar']},
          saveAsImage: {}
        }
      }
    },
    components:{
        VeLine
    },
    mounted(){
        this.cargarVentaDiaria().then(()=>{
            this.loadingCards = false
        });
        this.cargarTrasladosPendientes();
        this.cargarVentasPorAlmacen()
        this.cargarVentasPorAlmacenSemana()
    },
    computed:{
        arrayVentas(){
            return this.$store.getters.arrayVentas;
        },
        arrayAlmacenFijo(){
            return this.$store.getters.arrayAlmacen
        }
    },
    data () {
        this.chartSettings = {
            labelMap: {
                total: 'total S/ '
            },
        }
      return {  
        loading: false,
        initiated: false,
        loadingSemana: false,
        initiatedSemana: false,
        loadingCards: false,
        arrayEstadisticas:[],
        arrayEstadisticasSemana: [],
        arrayVentasAlmacen:[],
        cantidadTrasladosPendientes: 0
        
      }
    },
    methods:{
        async cargarVentasPorAlmacen(){
            this.loading = true
            for(var i = 0; i < this.arrayAlmacenFijo.length; i++){
                var data =  await axios.get(`/reporte_venta/${this.arrayAlmacenFijo[i].id}`)
                var consola = data.data
                var objeto = {
                    titulo: '',
                    columns: ['month', 'ordenes', 'total'],
                    rows: []
                }
                objeto.titulo = this.arrayAlmacenFijo[i].descripcion
                objeto.rows = consola
                this.arrayEstadisticas.push(objeto)
            }
            this.loading = false
            this.initiated = true
        },
        async cargarVentasPorAlmacenSemana(){
            this.loadingSemana = true
            for(var i = 0; i < this.arrayAlmacenFijo.length; i++){
                var data =  await axios.get(`/reporte_venta/${this.arrayAlmacenFijo[i].id}/mostrarVentasPorSemana`)
                var consola = data.data
                var objeto = {
                    titulo: '',
                    columns: ['dia_semana', 'ordenes', 'total'],
                    rows: []
                }
                objeto.titulo = this.arrayAlmacenFijo[i].descripcion
                objeto.rows = consola
                this.arrayEstadisticasSemana.push(objeto)
            }
            this.loadingSemana = false
            this.initiatedSemana = true
        },
        async cargarVentaDiaria(){
            this.loadingCards = true;
            for(var i = 0; i < this.arrayAlmacenFijo.length; i++){
                var data =  await axios.get(`/reporte_venta/${this.arrayAlmacenFijo[i].id}/obtenerVentasDiariasPorAlmacen`)
                var ventasDiarias = data.data
                this.arrayVentasAlmacen.push(ventasDiarias[0]);
            }
        },
        async cargarTrasladosPendientes(){
            var conteo_traslados = await axios.get('/cabecera_traslado/obtenerTrasladosPendientes');
            this.cantidadTrasladosPendientes = conteo_traslados.data[0].cantidad;
        }
    }
  }
</script>

<style scoped>
.estadisticas{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
}

</style>
