<template>
  <div>
        <!-- Modal -->
        <div class="modal fade" id="aperturaCajaModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apertura de caja</h5>
                <button type="button" class="close" @click="usuario">
                <span >&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <form>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Monto inicial</label>
                                    <input type="number" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Almacen</label>
                                    <select v-model="objetoAperturaCaja.id_almacen" class="form-control">
                                        <option disabled value="">Escoje un almacén</option>
                                        <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>-->
                    <button type="button" class="btn btn-primary" @click="usuario">Guardar</button>
                </div>
            </div>
            </div>
        </div>
        </div>
  </div>
</template>

<script>
export default {
    mounted(){
        this.listarUsuarios();
        this.listarAlmacen();
        this.obtenerFecha();
        this.abrirModalApertura();
    },
    data(){
        return{
            objetoAperturaCaja: {
                id_usuario: null,
                id_almacen: null,
                fecha: null,
                saldo_inicial: null,
                ventas_diarias: 0,
                ingresos: 0,
                egresos: 0,
                saldo_final: 0,
                estado: 'abierta'
            },
            arrayAlmacen: []
        }
    },
    methods:{
        abrirModalApertura(){
            $("#aperturaCajaModal").modal({

                show: true,
                backdrop: 'static',
                keyboard: false

            })
            //$('#aperturaCajaModal').modal('show');
            //console.log(this.objetoAperturaCaja)
        },
        cerrarModalApertura(){
            $('#aperturaCajaModal').modal('hide');
        },
        obtenerFecha(){
            this.objetoAperturaCaja.fecha = this.$moment().format("YYYY-MM-DD")
        },
        listarUsuarios(){
            var urlItem = '/user/logeado';
            axios.get(urlItem).then(response=>{
                this.objetoAperturaCaja.id_usuario = response.data.id
            })
        },
        listarAlmacen(){
            let me= this;
            var url='/almacen';
            axios.get(url).then(function (response){
                me.objetoAperturaCaja.id_almacen = response.data[0].id
                me.arrayAlmacen = response.data;
            })
        },
        usuario(){
            var me = this;
            $('#aperturaCajaModal').modal('hide');
            this.$root.menu = 1;
        }

    }
}
</script>

<style>

</style>