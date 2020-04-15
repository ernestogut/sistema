<template>
  <main class="main">
     <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h4>Pedidos de la página web</h4>
                </div>
            </div>
        </div>
    </div>
      <div class="card-body">
        <spinner v-if="loading"></spinner>
        <table  v-else-if="initiated" class="table table-hover table-bordered dt-responsive nowrap"  id="myTablePedidos" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" class="text-center align-middle" v-for="cabecera of cabeceras" :key="cabecera.id">{{cabecera}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(pedido) of arrayPedidos" :key="pedido.key" :class="[(pedido.estado_compra == 'Procesando')?'bg-danger text-white':(pedido.estado_compra == 'Completado')?'bg-success text-white':(pedido.estado_compra == 'En espera')?'bg-warning text-dark':'']">
                        <td class="text-center align-middle">
                            <div>
                                <span class="btn btn-primary btn-sm boton" ><i class="icon-plus" ></i></span>
                                <span class="btn btn-dark btn-sm boton" @click="verDetalle(pedido)"><i class="icon-eye"></i></span>
                            </div>
                        </td>
                        <td class="text-center align-middle">{{pedido.order_id}}</td>
                        <td class="text-center align-middle">{{pedido.nombre_facturacion}} {{pedido.apellido_facturacion}}</td>
                        <td class="text-center align-middle">{{pedido.telefono_facturacion}}</td>
                        <td class="text-center align-middle">{{pedido.estado_compra}}</td>
                        <td class="text-center align-middle">{{pedido.total_orden}}</td>
                        <td class="text-center align-middle">{{pedido.fecha_compra}}</td>
                    </tr>
                </tbody>
            </table>
      </div>
      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalDetalle">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Detalle pedido #{{numPedido}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item cursor-pointer">
                                                <p style="color: black;">
                                                    {{ordenDetalle}}
                                                </p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
  </main>
</template>

<script>
export default {
    mounted(){
        this.cargarPedidos();
    },
    data(){
        return{
            cabeceras: ['Acciones', 'N° Orden', 'Cliente', 'Teléfono', 'Estado', 'Total pedido', 'Fecha'],
            ordenDetalle: '',
            numPedido: '',
            loading: false,
            initiated: false
        }
    },
    computed:{
        arrayPedidos(){
            return this.$store.getters.arrayPedidos;
        }
    },
    methods:{
        async cargarPedidos(){
            this.loading = true
            await this.$store.dispatch('cargarPedidos').then(()=>{
                this.loading = false;
                this.initiated = true;
                this.miTablaPedidos();
            })
        },
        miTablaPedidos(){
            $( function () {
                $('#myTablePedidos').DataTable();
            } );
        },
        verDetalle(pedido){
            $('#modalDetalle').modal('show');
            this.ordenDetalle = pedido.productos_orden
            this.numPedido = pedido.order_id
        }
    }
}
</script>

<style>

</style>