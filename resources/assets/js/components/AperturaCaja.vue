<template>
  <div>
        <!-- Modal -->
        <div class="modal fade" id="aperturaCajaModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apertura de caja</h5>
                <button type="button" class="close" @click="cerrarModalApertura">
                <span >&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <form action="" @submit.prevent="usuario()">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Monto inicial</label>
                                    <input type="number" step="any" class="form-control" v-model="objetoAperturaCaja.saldo_inicial">
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
                        <div class="modal-footer">
                            <!--<button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>-->
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
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
                estado: 'abierto'
            },
            usuarioLogeado: {},
            arrayAlmacen: [],
            contador: '',
            contadorVendedores: ''
        }
    },
    methods:{
        abrirModalApertura(){
            $("#aperturaCajaModal").modal({

                show: true,
                backdrop: 'static',
                keyboard: false

            })
        },
        cerrarModalApertura(){
            $('#aperturaCajaModal').modal('hide');
            this.$root.menu = 1;
        },
        obtenerFecha(){
            this.objetoAperturaCaja.fecha = this.$moment().format("YYYY-MM-DD")
        },
        listarUsuarios(){
            var urlItem = '/user/logeado';
            axios.get(urlItem).then(response=>{
                this.objetoAperturaCaja.id_usuario = response.data.id
                this.usuarioLogeado = response.data;
                axios.get(`/cierre_caja/${this.usuarioLogeado.id_almacen}/verificarEstadoCaja`).then((response)=>{
                    this.contador = 0
                    this.contadorVendedores = 0
                    console.log(response.data)
                    for(var i = 0; i < response.data.length; i++){
                        if(response.data[i].estado == 'abierto' && response.data[i].fecha == this.$moment().format("YYYY-MM-DD")){
                            this.contador += 1
                            if(this.usuarioLogeado.id == response.data[i].id_usuario){
                                this.contadorVendedores += 1
                            }
                        }
                    }
                    if(this.contador > 0){
                        if(this.contadorVendedores > 0 && this.usuarioLogeado.id_almacen != 0){
                            $('#aperturaCajaModal').modal('hide');
                            if(this.usuarioLogeado.idrole == 1){
                                this.$root.menu = 1;
                            }else if(this.usuarioLogeado.idrole == 2){
                                this.$root.menu = 5;
                            }
                        }else{
                            Vue.swal({
                                icon: 'error',
                                title: 'Existe una caja abierta',
                                text: 'Comunicate con el administrador para solucionarlo!',
                            })
                        }
                    }else{
                        if(this.usuarioLogeado.id_almacen == 0 || this.contador < 1){
                            this.listarAlmacen();
                            this.obtenerFecha();
                            this.abrirModalApertura();
                        }
                    }
                })
                //
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
            //this.usuarioLogeado.id_almacen = this.objetoAperturaCaja.id_almacen
            let formDatos = new FormData();
            formDatos.append('id_almacen', this.objetoAperturaCaja.id_almacen)
            formDatos.append("_method", "put");
            $('#aperturaCajaModal').modal('hide');
            if(this.usuarioLogeado.id_almacen != 0 && this.contador > 0){
                Vue.swal({
                    icon: 'error',
                    title: 'Caja existente',
                    text: 'Existe una caja existente para este usuario!',
                    onClose: ()=>{
                        this.objetoAperturaCaja.saldo_inicial = null
                        this.abrirModalApertura();
                    }
                })
            }else{
                axios.post('/cierre_caja', this.objetoAperturaCaja).then((response)=>{
                    axios.post(`/user/actualizar/${this.usuarioLogeado.id}`, formDatos).then((response)=>{

                    })
                })
                this.$root.menu = 1;
            }
        }

    }
}
</script>

<style>

</style>