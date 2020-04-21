<template>
    <main class="main">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h4>Movimiento de caja</h4>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalNuevoMovimiento()">Nuevo movimiento de caja</button>
                    </div>   
                </div>
            </div>
            </div>
            <spinner v-if="loading"></spinner>
            <div class="card-body" v-else-if="initiated">
                <table class="table table-striped table-bordered dt-responsive nowrap"  id="miTabla" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center align-middle" >Acciones</th>
                            <th scope="col" class="text-center align-middle" >N°</th>
                            <th scope="col" class="text-center align-middle" >Responsable</th>
                            <th scope="col" class="text-center align-middle" >Tienda</th>
                            <th scope="col" class="text-center align-middle" >Tipo de movimiento</th>
                            <th scope="col" class="text-center align-middle" >Monto</th>
                            <th scope="col" class="text-center align-middle" >Observación</th>
                            <th scope="col" class="text-center align-middle" >Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(movimiento, index) of arrayMovimientos" :key="movimiento.key">
                            <td class="text-center align-middle">
                                <div >
                                    <span class="btn btn-primary btn-sm boton"  ><i class="icon-eye" ></i></span>
                                    <span class="btn btn-danger btn-sm boton"><i class="icon-trash"></i></span>
                                </div>
                            </td>
                            <th scope="row" class="text-center align-middle">{{index+1}}</th>
                            <td class="text-center align-middle">{{movimiento.responsable}}</td>
                            <td class="text-center align-middle">{{movimiento.tienda}}</td>
                            <td class="text-center align-middle">{{movimiento.tipo_movimiento}}</td>
                            <td class="text-center align-middle">{{movimiento.monto}}</td>
                            <td class="text-center align-middle">{{movimiento.observacion}}</td>
                            <td class="text-center align-middle">{{movimiento.fecha}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!---->
            <div class="modal fade bd-example-modal-lg1" id="modalMovimiento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="modalMovimiento" style="overflow-y: scroll;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo movimiento de caja</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form  action="" @submit.prevent="insertarMovimiento()">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Tienda</label>
                                    <select v-model="objetoMovimiento.id_almacen" class="form-control">
                                        <option disabled value="">Escoje un almacén</option>
                                        <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Responsable</label>
                                    <select required="required" class="form-control" v-model="objetoMovimiento.id_usuario">
                                        <option v-for="usuario in arrayUsuarios" :value="usuario.id" :key="usuario.key">{{usuario.usuario}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tipo de movimiento</label>
                                    <select required="required" class="form-control" v-model="objetoMovimiento.tipo_movimiento">
                                        <option value="ingreso">Ingreso</option>
                                        <option value="egreso">Egreso</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Fecha de emisión</label>
                                    <input type="date" name="FechaReg"  id="FechaReg" class="form-control" v-model="objetoMovimiento.fecha">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Observación</label>
                                    <input type="text" class="form-control" v-model="objetoMovimiento.observacion">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Monto</label>
                                    <input type="number" step="any" class="form-control" id="monto" v-model="objetoMovimiento.monto">
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                    </div>  
                </div>
            </div>
            </div>
    </main>
</template>

<script>
export default {
    mounted(){
        //this.listarMovimientosCaja()
        this.objetoMovimiento.id_usuario = this.usuarioLogeado.id
        this.objetoMovimiento.id_almacen = this.usuarioLogeado.id_almacen;
        this.listarMovimientosCaja()
        $(this.$refs.modalMovimiento).on("hidden.bs.modal", this.limpiarModalMovimiento)
        //this.listarUsuarios();
    },
    data(){
        return{
            initiated: false,
            loading: false,
            arrayMovimientos: [],
            objetoMovimiento:{
                id_usuario: null,
                id_almacen: null,
                fecha: null,
                observacion: null,
                monto: 0.00,
                tipo_movimiento: null,
            },
            arrayAlmacen: []
        }
    },
    computed:{
        usuarioLogeado(){
            return this.$store.getters.arrayUsuarioLogeado;
        },
        arrayUsuarios(){
            return this.$store.getters.arrayUsuarios;
        },
    },
    methods:{
        limpiarModalMovimiento(){
            this.objetoMovimiento.id_usuario = this.usuarioLogeado.id
            this.objetoMovimiento.id_almacen = this.usuarioLogeado.id_almacen;
            this.objetoMovimiento.fecha =  null
            this.objetoMovimiento.observacion =  null
            this.objetoMovimiento.monto = 0.00
            this.objetoMovimiento.tipo_movimiento =  null
        },
        listarMovimientosCaja(){
            this.loading = true
            axios.get(`/movimiento_caja/${this.objetoMovimiento.id_almacen}`).then(response=>{
                this.arrayMovimientos = response.data;
                this.loading = false;
                this.initiated = true;
                this.miTabla();
            })
        },
        /*listarUsuarios(){
            var urlItem = '/user/logeado';
            axios.get(urlItem).then(response=>{
                this.objetoMovimiento.id_usuario = response.data.id
                this.objetoMovimiento.id_almacen = response.data.id_almacen;
                this.listarMovimientosCaja()
            })
            var urlItem = '/user';
            axios.get(urlItem).then(response=>{
                this.arrayUsuarios = response.data;
            })
        },*/
        seleccionarAlmacen(){
            let me= this;
            var url='/almacen';
            axios.get(url).then(function (response){
                me.arrayAlmacen = response.data;
            })
        },
        abrirModalNuevoMovimiento(){
                //this.listarUsuarios();
                this.seleccionarAlmacen();
                this.obtenerFecha()
                $("#monto").on('blur change input', function() {
                    $(this).val(function(i, input) {
                        input = input.replace(/\D/g, '');
                        return (input / 100).toFixed(2);
                    });
                }).trigger('blur');
                $('#modalMovimiento').modal('show');
        },
        obtenerFecha(){
            this.objetoMovimiento.fecha = this.$moment().format("YYYY-MM-DD")
        },
        miTabla(){
            $( function () {
                $('#miTabla').DataTable();
            } );
        },
        insertarMovimiento(){
                var me = this;
                axios.post('/movimiento_caja', this.objetoMovimiento).then((response)=>{
                    $('#modalMovimiento').modal('hide');
                    Vue.swal({
                        title: `${this.objetoMovimiento.tipo_movimiento} exitoso!`,
                        text: `El ${this.objetoMovimiento.tipo_movimiento} ha sido procesado con éxito`,
                        icon: 'success'
                    });
                    $('#miTabla').DataTable().destroy();
                    this.listarMovimientosCaja();
                })
                .catch(error=>{
                    Vue.swal({
                        title: `${this.objetoMovimiento.tipo_movimiento} fallido`,
                        text: `El ${this.objetoMovimiento.tipo_movimiento} no pudo procesarse`,
                        icon: 'error'
                    });
                })
        },
    }
}
</script>

<style>

</style>