<template>
    <main class="main">
        <b-card>
            <div class="estadisticas">
                <spinner v-if="loading"></spinner>
                
                    <div  v-for="estadistica in arrayEstadisticas" :key="estadistica.id"   style="width: 50%;" v-else-if="initiated">
                    
                        <h4 style="margin-top: 10px; text-align: center;">Ventas de {{estadistica.titulo}}</h4>
                            <ve-line :data="estadistica"  style="margin-top: 50px;"></ve-line>
                    </div>  
                
            </div>
        </b-card>
    </main>
</template>
<script>
import VeLine from 'v-charts/lib/line.common'
export default {
    components:{
        VeLine
    },
    mounted(){
        this.cargarVentasPorAlmacen()
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
      return {
        loading: false,
        initiated: false,
        arrayEstadisticas:[]
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
