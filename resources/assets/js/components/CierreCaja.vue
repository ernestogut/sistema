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
                                <span class="btn btn-primary btn-sm boton"  v-else><i class="icon-eye" ></i></span>
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
    </main>
</template>

<script>
export default {
    mounted(){
        this.listarUsuarios();
        //this.obtenerCierres();
    },
    data(){
        return{
            initiated: false,
            loading: false,
            arrayCierres: [],
            almacen: '',
            usuarioLogeado: {}
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
        listarUsuarios(){
            var urlItem = '/user/logeado';
            axios.get(urlItem).then(response=>{
                //this.objetoAperturaCaja.id_usuario = response.data.id
                this.usuarioLogeado = response.data;
                this.obtenerCierres();
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
                    axios.post(`cierre_caja/${item.id}/cerrarCaja`, formDatos).then((response)=>{
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
                        this.listarUsuarios();
                    }).catch(error=>{
                        Vue.swal({
                            title: 'Cierre de caja fallido!',
                            text: 'No ha podido procesarse el cierre de caja!',
                            icon: 'error'
                        });
                    })
                }
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