<template>
    <main class="main">
        <div class="card-body">
            <table class="table table-striped table-bordered dt-responsive nowrap"  id="miTabla" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" class="text-center align-middle">N° Documento</th>
                        <th scope="col" class="text-center align-middle">Responsable</th>
                        <th scope="col" class="text-center align-middle">Fecha</th>
                        <th scope="col" class="text-center align-middle">Saldo Inicial</th>
                        <th scope="col" class="text-center align-middle">Total ventas</th>
                        <th scope="col" class="text-center align-middle">Ingresos</th>
                        <th scope="col" class="text-center align-middle">Egresos</th>
                        <th scope="col" class="text-center align-middle">Saldo Final</th>
                        <th scope="col" class="text-center align-middle">Estado</th>
                        <th scope="col" class="text-center align-middle">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(cierre) of arrayCierres" :key="cierre.key">
                        <!--<th scope="row" class="text-center align-middle">{{index+1}}</th>-->
                        <td class="text-center align-middle">{{cierre.id}}</td>
                        <td class="text-center align-middle">{{cierre.id_usuario}}</td>
                        <td class="text-center align-middle">{{cierre.fecha}}</td>
                        <td class="text-center align-middle">{{cierre.saldo_inicial}}</td>
                        <td class="text-center align-middle">{{cierre.ventas_diarias}}</td>
                        <td class="text-center align-middle">{{cierre.ingresos}}</td>
                        <td class="text-center align-middle">{{cierre.egresos}}</td>
                        <td class="text-center align-middle">{{cierre.saldo_final}}</td>
                        <td class="text-center align-middle">{{cierre.estado}}</td>
                        <td>
                            <div>
                                <span class="btn btn-primary btn-sm boton"  ><i class="icon-pencil"></i></span>
                                <span class="btn btn-primary btn-sm boton"  ><i class="icon-eye" ></i></span>
                            </div>
                        </td>
                            
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
        this.obtenerCierres();
    },
    data(){
        return{
            arrayCierres: []
        }
    },
    methods:{
        obtenerCierres(){
            axios.get('cierre_caja').then((response)=>{
                this.arrayCierres  = response.data
                this.miTabla();
            })
        },
        miTabla(){
            $( function () {
                $('#miTabla').DataTable({
                    searching: true
                });
            } );
        },
    }

}
</script>

<style>

</style>