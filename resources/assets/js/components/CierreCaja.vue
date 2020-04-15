<template>
    <main class="main">
        <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h4>Cierre de caja</h4>
                    </div>
                </div>
            </div>
        <spinner v-if="loading"></spinner>
        <div class="card-body" v-else-if="initiated">
            <table class="table table-sm table-striped table-bordered dt-responsive nowrap"  id="miTabla" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" class="text-center align-middle">Acciones</th>
                        <th scope="col" class="text-center align-middle">N° Documento</th>
                        <th scope="col" class="text-center align-middle">Responsable</th>
                        <th scope="col" class="text-center align-middle">Fecha</th>
                        <th scope="col" class="text-center align-middle">Saldo Inicial</th>
                        <th scope="col" class="text-center align-middle">Total ventas</th>
                        <th scope="col" class="text-center align-middle">Ingresos</th>
                        <th scope="col" class="text-center align-middle">Egresos</th>
                        <th scope="col" class="text-center align-middle">Saldo Final</th>
                        <th scope="col" class="text-center align-middle">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(cierre) of arrayCierres" :key="cierre.key">
                        <!--<th scope="row" class="text-center align-middle">{{index+1}}</th>-->
                        <td class="text-center align-middle">
                            <div>
                                <span class="badge badge-danger" v-if="cierre.estado == 'abierto'" @click="cerrarCaja(cierre)">Cerrar caja</span>
                                <span class="btn btn-primary btn-sm boton"  @click="abrirModalMetodosPago(cierre)"><i class="icon-eye" ></i></span>
                            </div>
                        </td>
                        <td class="text-center align-middle">{{cierre.id}}</td>
                        <td class="text-center align-middle">{{cierre.responsable}}</td>
                        <td class="text-center align-middle">{{cierre.fecha}}</td>
                        <td class="text-center align-middle">{{cierre.saldo_inicial}}</td>
                        <td class="text-center align-middle">{{cierre.ventas_diarias}}</td>
                        <td class="text-center align-middle">{{cierre.ingresos}}</td>
                        <td class="text-center align-middle">{{cierre.egresos}}</td>
                        <td class="text-center align-middle">{{cierre.saldo_final}}</td>
                        <td class="text-center align-middle">{{cierre.estado}}</td>
                            
                            <!--<div v-else>
                                <span class="btn btn-primary btn-sm boton" ><i class="icon-eye" ></i></span>
                                <span class="btn btn-danger btn-sm boton"><i class="icon-trash"  ></i></span>
                            </div>-->
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="modalSocial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
            <div class="modal-dialog cascading-modal modal-dialog-centered" role="document">
                <!--Content-->
                <div class="modal-content">
                <!--Header-->
                <div class="modal-header light-blue darken-3 white-text">
                    <h4 class="title"><i class="fa fa-users"></i>  Total ventas categorizadas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                </div>
                <!--Body-->
                <div class="modal-body mb-0 text-center">
                    <!--Pagos Efectivo-->
                    <a type="button" class="btn btn-primary" style="border-radius: 10px" @click="tipoPagoElegido = 1, abrirModalLista()"><i class="fa fa-money" style="font-size:36px"></i></a>
                    <!--Pagos Visa-->
                    <a type="button" class="btn btn-success" style="border-radius: 10px" @click="tipoPagoElegido = 2, abrirModalLista()"><i class="fa fa-cc-visa" style="font-size:36px"></i></a>
                    <!--Pagos Cheque-->
                    <a type="button" class="btn btn-info" style="border-radius: 10px" @click="tipoPagoElegido = 3, abrirModalLista()"><i class="fa fa-list-alt" style="font-size:36px"></i></a>
                    
                </div>

                </div>
                <!--/.Content-->

            </div>
        </div>
        <div class="modal fade" id="modalCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <!--Header-->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Your cart</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <!--Body-->
                <div class="modal-body">

                    <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Producto</th>
                            <th class="text-center align-middle">Precio</th>
                            <th class="text-center align-middle">Cantidad</th>
                            <th class="text-center align-middle">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(producto, index) in arrayProductosVenta" :key="producto.id">
                            <th scope="row" class="text-center align-middle">{{index+1}}</th>
                            <td class="text-center align-middle">{{producto.descripcion_producto}}</td>
                            <td class="text-center align-middle">S/ {{producto.precio_producto}}</td>
                            <td class="text-center align-middle">{{producto.cantidad_producto}}</td>
                            <td class="text-center align-middle">S/ {{producto.total_producto}}</td>
                        </tr>
                        <tr class="total">
                            <th scope="row"></th>
                            <td></td>
                            <td></td>
                            <td class="text-center align-middle">Total</td>
                            <td class="text-center align-middle">S/ {{totalItem}}</td>
                        </tr>
                    </tbody>
                    </table>

                </div>
                <!--Footer-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Checkout</button>
                </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    mounted(){
        this.obtenerCierres()
        //this.obtenerCierres();
    },
    data(){
        return{
            initiated: false,
            loading: false,
            arrayCierres: [],
            almacen: '',
            tipoPagoElegido: 1, // 1 -> Efectivo, 2 -> VISA, 3 -> Cheque,
            cajaSeleccionada: null,
            arrayProductosVenta: []
        }
    },
    computed:{
        usuarioLogeado(){
            return this.$store.getters.arrayUsuarioLogeado;
        },
        totalItem: function(){
            let sum = 0;
            for(let i = 0; i < this.arrayProductosVenta.length; i++){
                sum += parseFloat(this.arrayProductosVenta[i].total_producto);
            }
            return sum;
        }
    },
    methods:{
        obtenerCierres(){
            this.loading = true
            axios.get(`cierre_caja/${this.usuarioLogeado.id_almacen}`).then((response)=>{
                this.arrayCierres  = response.data
                this.loading = false;
                this.initiated = true;
                this.miTabla();
            })
        },
        miTabla(){
            $( function () {
                $('#miTabla').DataTable({
                    searching: true,
                    aaSorting: []
                });
            } );
        },
        cerrarCaja(item){
            let formDatos = new FormData();
            formDatos.append('estado', 'cerrado')
            formDatos.append("_method", "put");
            let formDatosUsuario = new FormData();
            formDatosUsuario.append('id_almacen', 0);
            formDatosUsuario.append("_method", "put");
            Vue.swal({
                title: 'Estas seguro de cerrar caja?',
                text: "No podrás volver a activarla!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Cerrar caja!',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.value) {
                    axios.get(`cierre_caja/${item.id}/consultarCajaSeleccionada`).then(respuesta=>{
                        if(this.usuarioLogeado.id == respuesta.data.id_usuario){
                            axios.post(`cierre_caja/${item.id}/cerrarCaja`, formDatos).then(async (response)=>{
                                axios.post(`/user/actualizar/${this.usuarioLogeado.id}`, formDatosUsuario).then((response)=>{

                                })
                                Vue.swal({
                                    title: 'Cierre de caja exitoso!',
                                    text: 'El cierre de caja ha sido procesado con éxito!',
                                    icon: 'success'
                                })
                                $( function () {
                                    $('#miTabla').DataTable().destroy();
                                } );
                                await this.$store.dispatch('cargarUsuarioLogeado').then(()=>{
                                     this.obtenerCierres()
                                     console.log(this.usuarioLogeado)
                                })
                            }).catch(error=>{
                                Vue.swal({
                                    title: 'Cierre de caja fallido!',
                                    text: 'No ha podido procesarse el cierre de caja!',
                                    icon: 'error'
                                });
                            })
                        }else{
                            Vue.swal({
                                title: 'Cierre de caja fallido!',
                                text: 'No eres el usuario responsable de esta caja!',
                                icon: 'error'
                            });
                        }
                    })
                    
                }
            })
        },
        abrirModalMetodosPago(caja){
            $('#modalSocial').modal('show');
            this.cajaSeleccionada = caja
        },
        abrirModalLista(){
            var tipo_pago = ''
            if(this.tipoPagoElegido == 1){
                tipo_pago = 'efectivo'
            }else if(this.tipoPagoElegido == 2){
                tipo_pago = 'tarjeta'
            }else if(this.tipoPagoElegido == 3){
                tipo_pago = 'cheque'
            }
            axios.get(`/c_fact/${tipo_pago}/${this.cajaSeleccionada.fecha}/${this.cajaSeleccionada.id_almacen}/mostrarVentasTipoPago`).then(response=>{
                this.arrayProductosVenta = response.data
                $('#modalCart').modal('show');
            }).catch(error=>{
                Vue.swal({
                    title: `No hay ventas con ${tipo_pago}!`,
                    text: `Por el momento no se han registrado ventas con ${tipo_pago}`,
                    icon: 'error'
                })
            })
        }
                    
    }

}
</script>

<style>


.badge{
    cursor: pointer;
}

</style>